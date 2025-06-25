# Stripe Payment Integration Documentation

## Overview

This documentation covers the complete Stripe payment integration for the Ebook Ecommerce API. The integration supports secure payment processing for ebook purchases with proper order management and transaction tracking.

## Environment Configuration

Add the following environment variables to your `.env` file:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here
STRIPE_WEBHOOK_TOLERANCE=300
```

## Payment Flow

### 1. Standard Payment Flow

1. **Create Order**: User adds items to cart and initiates checkout
2. **Create Payment Intent**: System creates a Stripe payment intent
3. **Process Payment**: Frontend processes payment using Stripe Elements
4. **Confirm Payment**: Backend confirms payment and completes order
5. **Fulfill Order**: Ebooks are added to user's library

### 2. API Endpoints

#### Checkout (Create Order)

```http
POST /api/v1/checkout
Authorization: Bearer {token}
Content-Type: application/json

{
    "payment_method": "stripe",
    "billing_info": {
        "name": "John Doe",
        "email": "john@example.com",
        "address": "123 Main St",
        "city": "New York",
        "state": "NY",
        "zip": "10001",
        "country": "US"
    }
}
```

**Response for Stripe Payment:**
```json
{
    "success": true,
    "message": "Order created successfully. Please complete payment.",
    "data": {
        "order_id": 1,
        "order_number": "ORD-1640995200-1",
        "total_amount": 29.99,
        "items_count": 2,
        "status": "pending",
        "payment_method": "stripe",
        "payment_status": "pending",
        "created_at": "2023-12-31T12:00:00.000000Z",
        "next_step": "Create payment intent using /v1/stripe/payment-intent endpoint"
    }
}
```

#### Create Payment Intent

```http
POST /api/v1/stripe/payment-intent
Authorization: Bearer {token}
Content-Type: application/json

{
    "order_id": 1,
    "customer_data": {
        "address": {
            "line1": "123 Main St",
            "city": "New York",
            "state": "NY",
            "postal_code": "10001",
            "country": "US"
        },
        "phone": "+1234567890"
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "client_secret": "pi_1234567890_secret_abcdef",
        "payment_intent_id": "pi_1234567890",
        "amount": 29.99,
        "currency": "USD",
        "order": {
            "id": 1,
            "order_number": "ORD-1640995200-1",
            "total_amount": 29.99
        },
        "transaction_id": 1
    }
}
```

#### Confirm Payment

```http
POST /api/v1/stripe/confirm-payment
Authorization: Bearer {token}
Content-Type: application/json

{
    "payment_intent_id": "pi_1234567890"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Payment confirmed successfully",
    "data": {
        "order": {
            "id": 1,
            "order_number": "ORD-1640995200-1",
            "status": "completed",
            "payment_status": "paid",
            "total_amount": 29.99,
            "completed_at": "2023-12-31T12:05:00.000000Z"
        },
        "transaction": {
            "id": 1,
            "transaction_id": "TXN-20231231-ABC123",
            "status": "completed",
            "amount": 29.99
        }
    }
}
```

## Frontend Integration Guide

### 1. Install Stripe.js

```bash
npm install @stripe/stripe-js
```

### 2. Complete Payment Flow Example

```javascript
import { loadStripe } from '@stripe/stripe-js';

const stripePromise = loadStripe('pk_test_your_publishable_key_here');

// Complete payment flow
const handleCheckout = async (cartData, paymentMethod) => {
    try {
        // Step 1: Create order
        const orderResult = await createOrder(cartData);
        if (!orderResult.success) throw new Error(orderResult.message);
        
        // Step 2: Create payment intent
        const paymentIntentResult = await createPaymentIntent(
            orderResult.data.order_id,
            cartData.customer_data
        );
        if (!paymentIntentResult.success) throw new Error(paymentIntentResult.message);
        
        // Step 3: Process payment with Stripe
        const stripe = await stripePromise;
        const { error, paymentIntent } = await stripe.confirmCardPayment(
            paymentIntentResult.data.client_secret,
            { payment_method: paymentMethod }
        );
        
        if (error) throw new Error(error.message);
        
        // Step 4: Confirm payment on backend
        const confirmResult = await confirmPayment(paymentIntent.id);
        if (!confirmResult.success) throw new Error(confirmResult.message);
        
        return confirmResult.data;
        
    } catch (error) {
        console.error('Payment failed:', error);
        throw error;
    }
};
```

## Security Features

- Payment intent verification (users can only pay their own orders)
- Webhook signature verification
- Duplicate purchase prevention
- Comprehensive transaction logging

## Testing

Use Stripe's test card numbers:
- **Successful payment**: `4242424242424242`
- **Declined payment**: `4000000000000002`

## Production Setup

1. Replace test keys with live keys in `.env`
2. Ensure HTTPS for webhook endpoint
3. Configure webhook in Stripe Dashboard: `https://yourdomain.com/api/v1/stripe/webhook`
4. Select events: `payment_intent.succeeded`, `payment_intent.payment_failed` 