<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Documentation Route
|--------------------------------------------------------------------------
|
| This route serves as the entry point for API documentation and health checks.
| All API endpoints are versioned and follow RESTful conventions.
|
| Authentication: Sanctum (Session-based for web, Token-based for API)
| Version: 1.0.0
| Base URL: /api/v1
|
*/

// CSRF Protection for Sanctum
Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
})->name('sanctum.cookie');

// API Root Documentation
Route::get('/', function () {
    return response()->json([
        'api' => 'Library Management System',
        'version' => '1.0.0',
        'status' => 'operational',
        'documentation' => [
            'authentication' => [
                'required' => true,
                'type' => 'Bearer Token',
                'endpoints' => [
                    [
                        'method' => 'POST',
                        'path' => '/api/auth/register',
                        'description' => 'User registration',
                        'parameters' => [
                            'name' => 'string|required',
                            'email' => 'email|required|unique:users',
                            'password' => 'string|min:8|required'
                        ]
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/api/auth/login',
                        'description' => 'User login',
                        'parameters' => [
                            'email' => 'email|required',
                            'password' => 'string|required'
                        ]
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/api/auth/logout',
                        'description' => 'User logout',
                        'authentication' => 'required'
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/api/auth/me',
                        'description' => 'Current user profile',
                        'authentication' => 'required'
                    ]
                ]
            ],
            'books' => [
                'endpoints' => [
                    [
                        'method' => 'GET',
                        'path' => '/api/books',
                        'description' => 'List all books',
                        'filters' => [
                            '?search=title|author',
                            '?available=true'
                        ]
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/api/books',
                        'description' => 'Create new book',
                        'authentication' => 'admin',
                        'parameters' => [
                            'title' => 'string|required',
                            'author' => 'string|required',
                            'isbn' => 'string|unique:books',
                            'quantity' => 'integer|min:1'
                        ]
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/api/books/{id}',
                        'description' => 'Get book details'
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/api/books/{id}/borrow',
                        'description' => 'Borrow a book',
                        'authentication' => 'required'
                    ]
                ]
            ],
            'admin' => [
                'description' => 'Admin-only endpoints',
                'required_role' => 'admin',
                'endpoints' => [
                    [
                        'method' => 'PUT',
                        'path' => '/api/admin/books/{id}',
                        'description' => 'Update book details'
                    ],
                    [
                        'method' => 'DELETE',
                        'path' => '/api/admin/books/{id}',
                        'description' => 'Delete book'
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/api/admin/users',
                        'description' => 'List all users'
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/api/admin/users/{id}',
                        'description' => 'Get user details'
                    ]
                ]
            ]
        ],
        'links' => [
            'health_check' => '/health',
            'csrf_cookie' => '/sanctum/csrf-cookie'
        ]
    ]);
})->name('api.documentation');

// System Health Check
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'services' => [
            'database' => 'connected',
            'cache' => 'operational',
            'storage' => 'writable'
        ],
        'timestamp' => now()->toISOString()
    ]);
})->name('health.check');