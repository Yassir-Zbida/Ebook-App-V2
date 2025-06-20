# Ebook Ecommerce API Documentation

## Overview

This API provides comprehensive functionality for an ebook ecommerce platform, including user authentication, ebook browsing, purchasing, admin management, and analytics.

**Base URL:** `http://localhost:8000/api/v1`

## Authentication

The API uses Laravel Sanctum for authentication. Most endpoints require a Bearer token in the Authorization header.

### Getting a Token

```bash
# Register a new user
POST /register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}

# Login
POST /login
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "token": "1|abc123def456..."
    }
}
```

### Using the Token

Include the token in your requests:
```bash
curl -H "Authorization: Bearer 1|abc123def456..." \
     -H "Accept: application/json" \
     http://localhost:8000/api/v1/user/profile
```

## Public Endpoints (No Authentication Required)

### Authentication

#### Register User
- **POST** `/register`
- **Body:**
  ```json
  {
      "name": "John Doe",
      "email": "john@example.com",
      "password": "password123",
      "password_confirmation": "password123"
  }
  ```

#### Login
- **POST** `/login`
- **Body:**
  ```json
  {
      "email": "john@example.com",
      "password": "password123"
  }
  ```

#### Forgot Password
- **POST** `/forgot-password`
- **Body:**
  ```json
  {
      "email": "john@example.com"
  }
  ```

#### Reset Password
- **POST** `/reset-password`
- **Body:**
  ```json
  {
      "email": "john@example.com",
      "password": "newpassword123",
      "password_confirmation": "newpassword123",
      "token": "reset_token_here"
  }
  ```

### Ebook Browsing

#### List All Ebooks
- **GET** `/ebooks`
- **Query Parameters:**
  - `page` (optional): Page number for pagination
  - `search` (optional): Search term
  - `category` (optional): Filter by category ID
  - `price_min` (optional): Minimum price
  - `price_max` (optional): Maximum price

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "title": "Complete Guide to E-commerce",
                "description": "A comprehensive guide...",
                "price": "49.99",
                "cover_image": null,
                "categories_count": 3,
                "resources_count": 0,
                "created_at": "2025-06-05T23:07:33.000000Z",
                "updated_at": "2025-06-05T23:07:33.000000Z"
            }
        ],
        "total": 1,
        "per_page": 12
    }
}
```

#### Get Single Ebook
- **GET** `/ebooks/{id}`
- **Response:** Detailed ebook information with categories and resources

#### Get Ebook Categories
- **GET** `/ebooks/{id}/categories`
- **Response:** All categories associated with the ebook

#### Search Ebooks
- **GET** `/search/ebooks?q={search_term}`
- **Response:** Paginated search results

#### Featured Ebooks
- **GET** `/featured/ebooks`
- **Response:** List of featured ebooks

#### Popular Ebooks
- **GET** `/popular/ebooks`
- **Response:** List of popular ebooks based on sales/views

### Categories

#### List All Categories
- **GET** `/categories`
- **Response:** Hierarchical category structure with children

#### Get Single Category
- **GET** `/categories/{id}`
- **Response:** Category details with associated ebooks

#### Get Category Resources
- **GET** `/categories/{id}/resources`
- **Response:** All resources in the category

#### Search Categories
- **GET** `/search/categories?q={search_term}`
- **Response:** Search results for categories

## Protected Endpoints (Authentication Required)

### User Profile Management

#### Get User Profile
- **GET** `/user/profile`
- **Headers:** `Authorization: Bearer {token}`

#### Update User Profile
- **PUT** `/user/profile`
- **Headers:** `Authorization: Bearer {token}`
- **Body:**
  ```json
  {
      "name": "John Doe Updated",
      "email": "john.updated@example.com"
  }
  ```

#### Change Password
- **PUT** `/user/password`
- **Headers:** `Authorization: Bearer {token}`
- **Body:**
  ```json
  {
      "current_password": "oldpassword",
      "password": "newpassword123",
      "password_confirmation": "newpassword123"
  }
  ```

#### Logout
- **POST** `/logout`
- **Headers:** `Authorization: Bearer {token}`

### Purchased Ebooks

#### Get User's Purchased Ebooks
- **GET** `/user/ebooks`
- **Headers:** `Authorization: Bearer {token}`

#### Get Purchased Ebook Detail
- **GET** `/user/ebooks/{id}`
- **Headers:** `Authorization: Bearer {token}`

#### Get Purchased Ebook Categories
- **GET** `/user/ebooks/{id}/categories`
- **Headers:** `Authorization: Bearer {token}`

#### Get Purchased Ebook Resources
- **GET** `/user/ebooks/{id}/categories/{category_id}/resources`
- **Headers:** `Authorization: Bearer {token}`

### Wishlist Management

#### Get User Wishlist
- **GET** `/user/wishlist`
- **Headers:** `Authorization: Bearer {token}`

#### Add to Wishlist
- **POST** `/user/wishlist/{ebook_id}`
- **Headers:** `Authorization: Bearer {token}`

#### Remove from Wishlist
- **DELETE** `/user/wishlist/{ebook_id}`
- **Headers:** `Authorization: Bearer {token}`

### Shopping Cart

#### Get Cart
- **GET** `/user/cart`
- **Headers:** `Authorization: Bearer {token}`

#### Add to Cart
- **POST** `/user/cart/add`
- **Headers:** `Authorization: Bearer {token}`
- **Body:**
  ```json
  {
      "ebook_id": 1,
      "quantity": 1
  }
  ```

#### Update Cart
- **PUT** `/user/cart/update`
- **Headers:** `Authorization: Bearer {token}`
- **Body:**
  ```json
  {
      "items": [
          {
              "ebook_id": 1,
              "quantity": 2
          }
      ]
  }
  ```

#### Remove from Cart
- **DELETE** `/user/cart/remove/{ebook_id}`
- **Headers:** `Authorization: Bearer {token}`

#### Clear Cart
- **DELETE** `/user/cart/clear`
- **Headers:** `Authorization: Bearer {token}`

### Orders

#### Create Order
- **POST** `/orders`
- **Headers:** `Authorization: Bearer {token}`
- **Body:**
  ```json
  {
      "ebooks": [1, 2, 3],
      "payment_method": "stripe",
      "billing_address": {
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

#### Get Order Details
- **GET** `/orders/{id}`
- **Headers:** `Authorization: Bearer {token}`

#### Get User Orders
- **GET** `/user/orders`
- **Headers:** `Authorization: Bearer {token}`

#### Get User Order Detail
- **GET** `/user/orders/{id}`
- **Headers:** `Authorization: Bearer {token}`

#### Checkout
- **POST** `/checkout`
- **Headers:** `Authorization: Bearer {token}`
- **Body:** Same as Create Order

### Reviews and Ratings

#### Get Ebook Reviews
- **GET** `/ebooks/{id}/reviews`
- **Headers:** `Authorization: Bearer {token}`

#### Create Review
- **POST** `/ebooks/{id}/reviews`
- **Headers:** `Authorization: Bearer {token}`
- **Body:**
  ```json
  {
      "rating": 5,
      "comment": "Excellent book! Very informative."
  }
  ```

#### Update Review
- **PUT** `/ebooks/{id}/reviews/{review_id}`
- **Headers:** `Authorization: Bearer {token}`
- **Body:** Same as Create Review

#### Delete Review
- **DELETE** `/ebooks/{id}/reviews/{review_id}`
- **Headers:** `Authorization: Bearer {token}`

## Admin Endpoints (Admin Role Required)

All admin endpoints require both authentication and admin role middleware.

### Ebook Management

#### List All Ebooks (Admin)
- **GET** `/admin/ebooks`
- **Headers:** `Authorization: Bearer {admin_token}`

#### Create Ebook
- **POST** `/admin/ebooks`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Body:**
  ```json
  {
      "title": "New Ebook Title",
      "description": "Ebook description",
      "price": "29.99",
      "cover_image": "base64_encoded_image_or_url",
      "is_published": true
  }
  ```

#### Update Ebook
- **PUT** `/admin/ebooks/{id}`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Body:** Same as Create Ebook

#### Delete Ebook
- **DELETE** `/admin/ebooks/{id}`
- **Headers:** `Authorization: Bearer {admin_token}`

#### Publish Ebook
- **POST** `/admin/ebooks/{id}/publish`
- **Headers:** `Authorization: Bearer {admin_token}`

#### Unpublish Ebook
- **POST** `/admin/ebooks/{id}/unpublish`
- **Headers:** `Authorization: Bearer {admin_token}`

### Category Management

#### List All Categories (Admin)
- **GET** `/admin/categories`
- **Headers:** `Authorization: Bearer {admin_token}`

#### Create Category
- **POST** `/admin/categories`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Body:**
  ```json
  {
      "name": "New Category",
      "description": "Category description",
      "icon": "ri-icon-name",
      "sort_order": 1,
      "parent_id": null,
      "ebook_id": 1
  }
  ```

#### Update Category
- **PUT** `/admin/categories/{id}`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Body:** Same as Create Category

#### Delete Category
- **DELETE** `/admin/categories/{id}`
- **Headers:** `Authorization: Bearer {admin_token}`

#### Add Resource to Category
- **POST** `/admin/categories/{id}/resources`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Body:**
  ```json
  {
      "title": "Resource Title",
      "content": "Resource content or URL",
      "content_type": "text",
      "sort_order": 1
  }
  ```

### Resource Management

#### List All Resources (Admin)
- **GET** `/admin/resources`
- **Headers:** `Authorization: Bearer {admin_token}`

#### Create Resource
- **POST** `/admin/resources`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Body:**
  ```json
  {
      "title": "Resource Title",
      "content": "Resource content",
      "content_type": "text",
      "category_id": 1,
      "sort_order": 1
  }
  ```

#### Update Resource
- **PUT** `/admin/resources/{id}`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Body:** Same as Create Resource

#### Delete Resource
- **DELETE** `/admin/resources/{id}`
- **Headers:** `Authorization: Bearer {admin_token}`

### Order Management

#### List All Orders (Admin)
- **GET** `/admin/orders`
- **Headers:** `Authorization: Bearer {admin_token}`

#### Get Order Details (Admin)
- **GET** `/admin/orders/{id}`
- **Headers:** `Authorization: Bearer {admin_token}`

#### Update Order Status
- **PUT** `/admin/orders/{id}/status`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Body:**
  ```json
  {
      "status": "completed"
  }
  ```

### User Management

#### List All Users (Admin)
- **GET** `/admin/users`
- **Headers:** `Authorization: Bearer {admin_token}`

#### Get User Details (Admin)
- **GET** `/admin/users/{id}`
- **Headers:** `Authorization: Bearer {admin_token}`

#### Update User (Admin)
- **PUT** `/admin/users/{id}`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Body:**
  ```json
  {
      "name": "Updated Name",
      "email": "updated@example.com",
      "role": "customer"
  }
  ```

#### Delete User (Admin)
- **DELETE** `/admin/users/{id}`
- **Headers:** `Authorization: Bearer {admin_token}`

### Analytics

#### Sales Analytics
- **GET** `/admin/analytics/sales`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Query Parameters:**
  - `period` (optional): daily, weekly, monthly, yearly
  - `start_date` (optional): YYYY-MM-DD
  - `end_date` (optional): YYYY-MM-DD

#### Ebook Analytics
- **GET** `/admin/analytics/ebooks`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Response:** Sales, views, and performance metrics for ebooks

#### User Analytics
- **GET** `/admin/analytics/users`
- **Headers:** `Authorization: Bearer {admin_token}`
- **Response:** User registration, activity, and engagement metrics

## Error Handling

The API returns consistent error responses:

### Validation Errors (422)
```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

### Authentication Errors (401)
```json
{
    "success": false,
    "message": "Unauthenticated."
}
```

### Authorization Errors (403)
```json
{
    "success": false,
    "message": "Access denied. Admin role required."
}
```

### Not Found Errors (404)
```json
{
    "success": false,
    "message": "Resource not found."
}
```

### Server Errors (500)
```json
{
    "success": false,
    "message": "Internal server error."
}
```

## Rate Limiting

The API implements rate limiting to prevent abuse:
- Public endpoints: 60 requests per minute
- Authenticated endpoints: 120 requests per minute
- Admin endpoints: 300 requests per minute

## Pagination

List endpoints support pagination with the following parameters:
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: varies by endpoint)

**Response format:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [...],
        "first_page_url": "...",
        "from": 1,
        "last_page": 5,
        "last_page_url": "...",
        "next_page_url": "...",
        "path": "...",
        "per_page": 12,
        "prev_page_url": null,
        "to": 12,
        "total": 50
    }
}
```

## Content Types

Resources support different content types:
- `text`: Plain text content
- `html`: HTML formatted content
- `markdown`: Markdown formatted content
- `video`: Video URL or embed code
- `audio`: Audio file URL
- `pdf`: PDF file URL
- `image`: Image URL

## Testing the API

### Using cURL

```bash
# Test health endpoint
curl -X GET "http://localhost:8000/api/health" \
     -H "Accept: application/json"

# Register a user
curl -X POST "http://localhost:8000/api/v1/register" \
     -H "Accept: application/json" \
     -H "Content-Type: application/json" \
     -d '{
         "name": "Test User",
         "email": "test@example.com",
         "password": "password123",
         "password_confirmation": "password123"
     }'

# Login and get token
curl -X POST "http://localhost:8000/api/v1/login" \
     -H "Accept: application/json" \
     -H "Content-Type: application/json" \
     -d '{
         "email": "test@example.com",
         "password": "password123"
     }'

# Use token to access protected endpoint
curl -X GET "http://localhost:8000/api/v1/user/profile" \
     -H "Accept: application/json" \
     -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Using Postman

1. Import the API endpoints into Postman
2. Set the base URL to `http://localhost:8000/api/v1`
3. For authentication, use the Bearer Token type with your token
4. Set the Accept header to `application/json`

## Development Notes

- The API uses Laravel Sanctum for authentication
- All responses are JSON formatted
- Error handling is consistent across all endpoints
- Admin endpoints require the `role:admin` middleware
- File uploads are handled through base64 encoding or URLs
- The API supports CORS for cross-origin requests

## Support

For API support or questions, please refer to the Laravel documentation or contact the development team. 