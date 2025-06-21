# Ebook App API Documentation

## Overview

This API provides endpoints for managing an ebook ecommerce platform. It includes user authentication, ebook browsing, shopping cart management, wishlist functionality, and order processing.

## Base URL

```
http://localhost:8000/api/v1
```

## Authentication

Most endpoints require authentication using Bearer tokens. Include the token in the Authorization header:

```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## Authentication Endpoints

### User Registration

**POST** `/register`

Register a new user account.

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "SecurePassword123!",
    "password_confirmation": "SecurePassword123!"
}
```

**Response:**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john.doe@example.com",
            "role": "customer"
        },
        "token": "1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz567",
        "token_type": "Bearer"
    }
}
```

### User Login

**POST** `/login`

Authenticate user and get access token.

**Request Body:**
```json
{
    "email": "john.doe@example.com",
    "password": "SecurePassword123!"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john.doe@example.com",
            "role": "customer"
        },
        "token": "1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz567",
        "token_type": "Bearer"
    }
}
```

### Forgot Password

**POST** `/forgot-password`

Send password reset link to user's email.

**Request Body:**
```json
{
    "email": "john.doe@example.com"
}
```

### Reset Password

**POST** `/reset-password`

Reset user password using token.

**Request Body:**
```json
{
    "token": "reset_token_here",
    "email": "john.doe@example.com",
    "password": "NewPassword123!",
    "password_confirmation": "NewPassword123!"
}
```

### Logout

**POST** `/logout` *(Requires Authentication)*

Invalidate current access token.

---

## Ebook Endpoints

### Get All Ebooks

**GET** `/ebooks`

Get paginated list of available ebooks.

**Query Parameters:**
- `search` - Search term for title/description
- `min_price` - Minimum price filter
- `max_price` - Maximum price filter
- `sort_by` - Sort field (created_at, title, price)
- `sort_order` - Sort direction (asc, desc)
- `per_page` - Items per page (default: 12)

**Response:**
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "title": "Sample Ebook",
                "description": "A great ebook",
                "price": "29.99",
                "cover_image": "http://localhost:8000/storage/ebooks/covers/sample.jpg",
                "categories_count": 3,
                "resources_count": 15,
                "created_at": "2025-06-21T10:00:00.000000Z"
            }
        ],
        "current_page": 1,
        "per_page": 12,
        "total": 50
    }
}
```

### Get Single Ebook

**GET** `/ebooks/{id}`

Get detailed information about a specific ebook.

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Sample Ebook",
        "description": "A great ebook",
        "price": "29.99",
        "cover_image": "http://localhost:8000/storage/ebooks/covers/sample.jpg",
        "categories_count": 3,
        "resources_count": 15,
        "created_at": "2025-06-21T10:00:00.000000Z"
    }
}
```

### Search Ebooks

**GET** `/search/ebooks?q=search_term`

Search ebooks by title or description.

**Query Parameters:**
- `q` - Search term (required)
- `per_page` - Items per page

### Get Featured Ebooks

**GET** `/featured/ebooks`

Get list of featured ebooks.

### Get Popular Ebooks

**GET** `/popular/ebooks`

Get list of popular ebooks based on sales.

---

## Shopping Cart Endpoints *(Requires Authentication)*

### Get Cart

**GET** `/cart`

Get user's shopping cart with all items.

**Response:**
```json
{
    "success": true,
    "data": {
        "cart_items": [
            {
                "id": 1,
                "ebook": {
                    "id": 1,
                    "title": "Sample Ebook",
                    "price": "29.99",
                    "cover_image": "http://localhost:8000/storage/ebooks/covers/sample.jpg"
                },
                "quantity": 1,
                "price": "29.99",
                "discount_amount": "0.00",
                "subtotal": "29.99"
            }
        ],
        "subtotal": "29.99",
        "discount_amount": "0.00",
        "tax_amount": "0.00",
        "total": "29.99",
        "items_count": 1,
        "coupon_code": null,
        "coupon_discount": "0.00"
    }
}
```

### Add Item to Cart

**POST** `/cart/add`

Add an ebook to the shopping cart.

**Request Body:**
```json
{
    "ebook_id": 1,
    "quantity": 1
}
```

### Update Cart Item

**PUT** `/cart/items/{itemId}`

Update quantity of a cart item.

**Request Body:**
```json
{
    "quantity": 2
}
```

### Remove Item from Cart

**DELETE** `/cart/items/{itemId}`

Remove an item from the cart.

### Clear Cart

**DELETE** `/cart/clear`

Remove all items from the cart.

### Apply Coupon

**POST** `/cart/apply-coupon`

Apply a coupon code to the cart.

**Request Body:**
```json
{
    "coupon_code": "SAVE20"
}
```

### Remove Coupon

**DELETE** `/cart/remove-coupon`

Remove applied coupon from the cart.

---

## Wishlist Endpoints *(Requires Authentication)*

### Get Wishlist

**GET** `/wishlist`

Get user's wishlist with all saved ebooks.

**Query Parameters:**
- `sort_by` - Sort field (created_at, title)
- `sort_order` - Sort direction (asc, desc)
- `per_page` - Items per page

### Add to Wishlist

**POST** `/wishlist/{ebookId}`

Add an ebook to the wishlist.

### Remove from Wishlist

**DELETE** `/wishlist/{ebookId}`

Remove an ebook from the wishlist.

### Check Wishlist Status

**GET** `/wishlist/{ebookId}/check`

Check if an ebook is in the user's wishlist.

**Response:**
```json
{
    "success": true,
    "data": {
        "ebook_id": 1,
        "is_in_wishlist": true
    }
}
```

### Move to Cart

**POST** `/wishlist/{ebookId}/move-to-cart`

Move an ebook from wishlist to cart.

### Clear Wishlist

**DELETE** `/wishlist/clear`

Remove all items from the wishlist.

### Get Wishlist Count

**GET** `/wishlist/count`

Get the number of items in the wishlist.

**Response:**
```json
{
    "success": true,
    "data": {
        "count": 5
    }
}
```

---

## User Profile Endpoints *(Requires Authentication)*

### Get Profile

**GET** `/user/profile`

Get current user's profile information.

### Update Profile

**PUT** `/user/profile`

Update user's profile information.

**Request Body:**
```json
{
    "name": "John Doe Updated",
    "email": "john.updated@example.com"
}
```

### Change Password

**PUT** `/user/password`

Change user's password.

**Request Body:**
```json
{
    "current_password": "OldPassword123!",
    "password": "NewPassword123!",
    "password_confirmation": "NewPassword123!"
}
```

---

## Order Endpoints *(Requires Authentication)*

### Get User Orders

**GET** `/user/orders`

Get user's order history.

### Get Order Details

**GET** `/user/orders/{orderId}`

Get detailed information about a specific order.

### Checkout

**POST** `/checkout`

Process checkout from cart.

**Request Body:**
```json
{
    "payment_method": "stripe"
}
```

---

## Category Endpoints

### Get All Categories

**GET** `/categories`

Get list of all categories.

### Get Category Details

**GET** `/categories/{id}`

Get detailed information about a category.

### Get Category Resources

**GET** `/categories/{id}/resources`

Get resources for a specific category.

---

## Error Responses

All endpoints return consistent error responses:

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

Common HTTP Status Codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## Rate Limiting

API requests are rate limited to prevent abuse. Limits may vary by endpoint.

---

## Versioning

This API is versioned. The current version is `v1`. Include the version in the URL path.

---

## Support

For API support, please contact the development team or refer to the internal documentation. 