# User Registration API Documentation

## Overview

The registration API endpoint allows new users to create accounts in the ebook ecommerce platform. This endpoint accepts JSON data and returns a Bearer token for authentication.

## Endpoint Details

- **URL:** `POST /api/v1/register`
- **Content-Type:** `application/json`
- **Authentication:** Not required (public endpoint)

## Request Body (JSON)

### Required Fields

```json
{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "SecurePassword123!",
    "password_confirmation": "SecurePassword123!"
}
```

### Field Descriptions

| Field | Type | Required | Validation Rules | Description |
|-------|------|----------|------------------|-------------|
| `name` | string | Yes | max:255 | Full name of the user |
| `email` | string | Yes | email, unique:users, max:255 | Valid email address (must be unique) |
| `password` | string | Yes | min:8, mixed case, numbers, symbols | Secure password following Laravel's default rules |
| `password_confirmation` | string | Yes | same:password | Password confirmation (must match password) |

### Password Requirements

The password must meet Laravel's default password rules:
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- At least one symbol

## Response Format

### Success Response (201 Created)

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

### Error Response (422 Unprocessable Entity)

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "name": [
            "The name field is required."
        ],
        "email": [
            "The email field is required.",
            "The email must be a valid email address.",
            "The email has already been taken."
        ],
        "password": [
            "The password field is required.",
            "The password confirmation does not match.",
            "The password must be at least 8 characters.",
            "The password must contain at least one uppercase and one lowercase letter.",
            "The password must contain at least one number.",
            "The password must contain at least one symbol."
        ]
    }
}
```

## Example Usage

### cURL Example

```bash
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "SecurePassword123!",
    "password_confirmation": "SecurePassword123!"
  }'
```

### JavaScript/Fetch Example

```javascript
const response = await fetch('http://localhost:8000/api/v1/register', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        name: 'John Doe',
        email: 'john.doe@example.com',
        password: 'SecurePassword123!',
        password_confirmation: 'SecurePassword123!'
    })
});

const data = await response.json();
console.log(data);
```

### PHP Example

```php
$response = Http::post('http://localhost:8000/api/v1/register', [
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'password' => 'SecurePassword123!',
    'password_confirmation' => 'SecurePassword123!'
]);

$data = $response->json();
```

## Using the Authentication Token

After successful registration, you'll receive a Bearer token. Use this token in subsequent API requests:

```bash
curl -H "Authorization: Bearer 1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz567" \
     -H "Accept: application/json" \
     http://localhost:8000/api/v1/user/profile
```

## User Role Assignment

- All newly registered users are automatically assigned the `customer` role
- Users are set as `is_active = true` by default
- Only administrators can change user roles through the admin panel

## Security Considerations

1. **Password Security**: Passwords are hashed using Laravel's bcrypt algorithm
2. **Email Uniqueness**: Each email address can only be used once
3. **Token Security**: Tokens are generated using Laravel Sanctum
4. **Input Validation**: All inputs are validated and sanitized
5. **Rate Limiting**: Consider implementing rate limiting for production use

## Error Handling

The API returns appropriate HTTP status codes:

- `201 Created`: Registration successful
- `422 Unprocessable Entity`: Validation errors
- `500 Internal Server Error`: Server errors

## Database Schema

The user record is created in the `users` table with the following structure:

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    is_active BOOLEAN DEFAULT TRUE,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Related Endpoints

- `POST /api/v1/login` - User login
- `POST /api/v1/forgot-password` - Password reset request
- `POST /api/v1/reset-password` - Password reset
- `POST /api/v1/logout` - User logout (requires authentication) 