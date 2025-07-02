<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for an order
     */
    public function createPaymentIntent(Order $order, array $customerData = []): array
    {
        try {
            // Create or retrieve Stripe customer
            $customer = $this->createOrRetrieveCustomer($order->user, $customerData);

            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $this->convertToStripeAmount($order->total_amount),
                'currency' => 'usd', // You can make this configurable
                'customer' => $customer->id,
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            // Create transaction record
            $transaction = Transaction::create([
                'transaction_id' => Transaction::generateTransactionId(),
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'type' => 'payment',
                'status' => 'pending',
                'gateway' => 'stripe',
                'gateway_transaction_id' => $paymentIntent->id,
                'amount' => $order->total_amount,
                'currency' => 'USD',
                'gateway_response' => [
                    'payment_intent_id' => $paymentIntent->id,
                    'client_secret' => $paymentIntent->client_secret,
                    'status' => $paymentIntent->status,
                ],
                'metadata' => [
                    'customer_id' => $customer->id,
                ],
            ]);

            return [
                'success' => true,
                'payment_intent' => $paymentIntent,
                'client_secret' => $paymentIntent->client_secret,
                'transaction' => $transaction,
            ];

        } catch (ApiErrorException $e) {
            Log::error('Stripe Payment Intent Creation Failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'code' => $e->getStripeCode(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getStripeCode(),
            ];
        }
    }

    /**
     * Confirm a payment intent
     */
    public function confirmPaymentIntent(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            // Find the transaction
            $transaction = Transaction::where('gateway_transaction_id', $paymentIntentId)->first();
            
            if (!$transaction) {
                return [
                    'success' => false,
                    'error' => 'Transaction not found',
                    'payment_intent' => $paymentIntent,
                ];
            }

            // Check payment intent status
            switch ($paymentIntent->status) {
                case 'succeeded':
                    // Payment is successful, complete the order
                    $transaction->update([
                        'status' => 'completed',
                        'processed_at' => now(),
                        'gateway_response' => array_merge(
                            $transaction->gateway_response ?? [],
                            [
                                'status' => $paymentIntent->status,
                                'charges' => $paymentIntent->charges->data ?? [],
                            ]
                        ),
                    ]);

                    // Update order status
                    $order = $transaction->order;
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);

                    // Complete the purchase (add ebooks to user's library)
                    $transaction->completePurchase();

                    return [
                        'success' => true,
                        'payment_intent' => $paymentIntent,
                        'transaction' => $transaction,
                        'order' => $order,
                    ];

                case 'requires_payment_method':
                    return [
                        'success' => false,
                        'error' => 'Payment method required. Please complete the payment on the frontend.',
                        'payment_intent' => $paymentIntent,
                        'status' => 'requires_payment_method',
                        'client_secret' => $paymentIntent->client_secret,
                    ];

                case 'requires_confirmation':
                    return [
                        'success' => false,
                        'error' => 'Payment requires confirmation.',
                        'payment_intent' => $paymentIntent,
                        'status' => 'requires_confirmation',
                    ];

                case 'requires_action':
                    return [
                        'success' => false,
                        'error' => 'Payment requires additional action (3D Secure, etc.).',
                        'payment_intent' => $paymentIntent,
                        'status' => 'requires_action',
                        'client_secret' => $paymentIntent->client_secret,
                    ];

                case 'processing':
                    return [
                        'success' => false,
                        'error' => 'Payment is being processed.',
                        'payment_intent' => $paymentIntent,
                        'status' => 'processing',
                    ];

                case 'canceled':
                    return [
                        'success' => false,
                        'error' => 'Payment was canceled.',
                        'payment_intent' => $paymentIntent,
                        'status' => 'canceled',
                    ];

                default:
                    return [
                        'success' => false,
                        'error' => 'Payment status not handled: ' . $paymentIntent->status,
                        'payment_intent' => $paymentIntent,
                        'status' => $paymentIntent->status,
                    ];
            }

        } catch (ApiErrorException $e) {
            Log::error('Stripe Payment Confirmation Failed', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
                'code' => $e->getStripeCode(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getStripeCode(),
            ];
        }
    }

    /**
     * Handle Stripe webhook
     */
    public function handleWebhook(array $payload, string $signature): array
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                json_encode($payload),
                $signature,
                config('services.stripe.webhook.secret')
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    return $this->handlePaymentIntentSucceeded($event->data->object);
                
                case 'payment_intent.payment_failed':
                    return $this->handlePaymentIntentFailed($event->data->object);
                
                default:
                    Log::info('Unhandled Stripe webhook event', ['type' => $event->type]);
                    return ['success' => true, 'message' => 'Event not handled'];
            }

        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create or retrieve Stripe customer
     */
    private function createOrRetrieveCustomer($user, array $customerData = []): Customer
    {
        try {
            // Try to find existing customer by email
            $customers = Customer::all(['email' => $user->email, 'limit' => 1]);
            
            if (!empty($customers->data)) {
                return $customers->data[0];
            }

            // Create new customer
            return Customer::create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => [
                    'user_id' => $user->id,
                ],
                'address' => $customerData['address'] ?? null,
                'phone' => $customerData['phone'] ?? null,
            ]);

        } catch (ApiErrorException $e) {
            Log::error('Stripe Customer Creation Failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle successful payment intent
     */
    private function handlePaymentIntentSucceeded($paymentIntent): array
    {
        $transaction = Transaction::where('gateway_transaction_id', $paymentIntent->id)->first();
        
        if (!$transaction) {
            return ['success' => false, 'error' => 'Transaction not found'];
        }

        $transaction->update([
            'status' => 'completed',
            'processed_at' => now(),
            'gateway_response' => array_merge(
                $transaction->gateway_response ?? [],
                [
                    'webhook_status' => $paymentIntent->status,
                    'charges' => $paymentIntent->charges->data ?? [],
                ]
            ),
        ]);

        // Update order
        $order = $transaction->order;
        $order->update([
            'payment_status' => 'paid',
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Complete the purchase (add ebooks to user's library)
        $transaction->completePurchase();

        return [
            'success' => true,
            'transaction' => $transaction,
            'order' => $order,
        ];
    }

    /**
     * Handle failed payment intent
     */
    private function handlePaymentIntentFailed($paymentIntent): array
    {
        $transaction = Transaction::where('gateway_transaction_id', $paymentIntent->id)->first();
        
        if (!$transaction) {
            return ['success' => false, 'error' => 'Transaction not found'];
        }

        $transaction->update([
            'status' => 'failed',
            'failure_reason' => $paymentIntent->last_payment_error->message ?? 'Payment failed',
            'gateway_response' => array_merge(
                $transaction->gateway_response ?? [],
                [
                    'webhook_status' => $paymentIntent->status,
                    'error' => $paymentIntent->last_payment_error ?? null,
                ]
            ),
        ]);

        // Update order
        $order = $transaction->order;
        $order->update([
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ]);

        return [
            'success' => true,
            'transaction' => $transaction,
            'order' => $order,
        ];
    }

    /**
     * Convert amount to Stripe format (cents)
     */
    private function convertToStripeAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    /**
     * Get payment intent status without confirming
     */
    public function getPaymentIntentStatus(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            $transaction = Transaction::where('gateway_transaction_id', $paymentIntentId)->first();
            
            return [
                'success' => true,
                'payment_intent' => $paymentIntent,
                'transaction' => $transaction,
                'status' => $paymentIntent->status,
                'amount' => $this->convertFromStripeAmount($paymentIntent->amount),
                'currency' => $paymentIntent->currency,
                'client_secret' => $paymentIntent->client_secret,
            ];

        } catch (ApiErrorException $e) {
            Log::error('Stripe Payment Intent Status Check Failed', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
                'code' => $e->getStripeCode(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getStripeCode(),
            ];
        }
    }

    /**
     * Convert amount from Stripe format (cents) to dollars
     */
    private function convertFromStripeAmount(int $amount): float
    {
        return $amount / 100;
    }
} 