<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StripeService;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Create payment intent for an order
     */
    public function createPaymentIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'customer_data' => 'nullable|array',
            'customer_data.address' => 'nullable|array',
            'customer_data.phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $order = Order::where('id', $request->order_id)
                     ->where('user_id', $user->id)
                     ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or access denied'
            ], 404);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order has already been paid'
            ], 400);
        }

        $result = $this->stripeService->createPaymentIntent(
            $order, 
            $request->customer_data ?? []
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment intent',
                'error' => $result['error'] ?? 'Unknown error'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'client_secret' => $result['client_secret'],
                'payment_intent_id' => $result['payment_intent']->id,
                'amount' => $order->total_amount,
                'currency' => 'USD',
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                ],
                'transaction_id' => $result['transaction']->id,
            ]
        ]);
    }

    /**
     * Confirm payment intent
     */
    public function confirmPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_intent_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        // Verify the payment intent belongs to the user
        $transaction = Transaction::where('gateway_transaction_id', $request->payment_intent_id)
                                 ->where('user_id', $user->id)
                                 ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Payment intent not found or access denied'
            ], 404);
        }

        $result = $this->stripeService->confirmPaymentIntent($request->payment_intent_id);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Payment confirmation failed',
                'error' => $result['error'] ?? 'Unknown error',
                'result' => $result
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed successfully',
            'data' => [
                'order' => [
                    'id' => $result['order']->id,
                    'order_number' => $result['order']->order_number,
                    'status' => $result['order']->status,
                    'payment_status' => $result['order']->payment_status,
                    'total_amount' => $result['order']->total_amount,
                    'completed_at' => $result['order']->completed_at,
                ],
                'transaction' => [
                    'id' => $result['transaction']->id,
                    'transaction_id' => $result['transaction']->transaction_id,
                    'status' => $result['transaction']->status,
                    'amount' => $result['transaction']->amount,
                ],
            ]
        ]);
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(Request $request, $paymentIntentId)
    {
        $user = $request->user();
        
        $transaction = Transaction::where('gateway_transaction_id', $paymentIntentId)
                                 ->where('user_id', $user->id)
                                 ->with('order')
                                 ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'payment_intent_id' => $paymentIntentId,
                'status' => $transaction->status,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'order' => [
                    'id' => $transaction->order->id,
                    'order_number' => $transaction->order->order_number,
                    'status' => $transaction->order->status,
                    'payment_status' => $transaction->order->payment_status,
                ],
                'created_at' => $transaction->created_at,
                'processed_at' => $transaction->processed_at,
            ]
        ]);
    }

    /**
     * Handle Stripe webhooks
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (!$signature) {
            return response()->json([
                'success' => false,
                'message' => 'Missing Stripe signature'
            ], 400);
        }

        try {
            $result = $this->stripeService->handleWebhook(
                json_decode($payload, true),
                $signature
            );

            if ($result['success']) {
                return response()->json(['success' => true]);
            }

            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Webhook processing failed'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Stripe webhook processing error', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed'
            ], 500);
        }
    }

    /**
     * Get user's payment methods (future enhancement)
     */
    public function getPaymentMethods(Request $request)
    {
        // This can be implemented later to show saved payment methods
        return response()->json([
            'success' => true,
            'data' => [
                'payment_methods' => [],
                'message' => 'Payment methods feature coming soon'
            ]
        ]);
    }
} 