# Tanbal PHP Router

A lightweight PHP router and backend scaffolding installer for PHP projects.

## Installation

Install the package using Composer:

```bash
composer require tanbal/php-router
```

After installation, the package automatically creates a `backend` directory in your project.

Existing files will not be overwritten.

## Generated Structure

```text
backend/
├── router.php
├── utils/
│   ├── json.php
│   └── response.php
└── routes/
```

## Running the PHP Server

From your project root, run:

```bash
php -S localhost:8000 backend/router.php
```

## Creating Routes

Create a directory for each resource inside `backend/routes/`.

For example:

```text
backend/
└── routes/
    └── users/
        ├── route.php
        └── [id]/
            └── route.php
```

- `route.php` handles requests to `/users`.
- `[id]/route.php` handles requests to `/users/{id}`.

The route files have access to:

- `$method`: HTTP request method.
- `$id`: Resource ID (or `null`).

## Utilities

### JSON

The `JSON` class provides methods for working with JSON files:

- `exists()`
- `load()`
- `save($data)`
- `remove($id)`

### Response

The `sendError()` function sends a JSON error response and terminates execution.

```php
sendError('Not found', 404);
```

## Requirements

- PHP 8.0+
- Composer 2

## License

MIT
