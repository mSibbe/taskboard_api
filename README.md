<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


# Laravel Task Management API

RESTful API for a task management system developed with Laravel. This
application implements an
API only, without any web user interface.

------------------------------------------------------------------------

## Project Description

The API allows users to create, update, delete and list tasks.  
Each task has the following properties:

- Title
- Description
- Status (todo, in_progress, done)

Access to protected endpoints is handled via token-based authentication
using Laravel Sanctum.

------------------------------------------------------------------------

## Requirements

- PHP >= 8.2
- Composer
- MySQL
- Git

------------------------------------------------------------------------

## Installation

Clone the repository:

```bash
git clone https://github.com/mSibbe/taskboard_api.git
cd taskboard_api
```

Install dependencies:

```bash
composer install
```

Create environment file:

```bash
cp .env.example .env
php artisan key:generate
```

------------------------------------------------------------------------

## Database Configuration

### Example MySQL configuration in `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskboard_api
DB_USERNAME=root
DB_PASSWORD=
```

### Run migrations

```bash
php artisan migrate
```

------------------------------------------------------------------------

## Authentication (Laravel Sanctum)

Sanctum is used for token-based API authentication.

If Sanctum needs to be installed manually:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

------------------------------------------------------------------------

## Start Server

```bash
php artisan serve
```

API base URL:

```
http://localhost:8000/api
```

------------------------------------------------------------------------

## Auth Endpoints

### Register

```
POST /api/register
```

```json
{
  "name": "Marvin",
  "email": "marvin@test.com",
  "password": "secret123"
}
```

### Login

```
POST /api/login
```

Response returns an access token:

```json
{
  "token": "1|abcdef..."
}
```

Use this token in all protected requests:

```
Authorization: Bearer <TOKEN>
```

### Logout

```
POST /api/logout
```

------------------------------------------------------------------------

## Task API Endpoints (protected)

| Method | Endpoint | Description |
|--------|-----------|------------|
| GET | /api/tasks | Get all tasks |
| POST | /api/tasks | Create new task |
| GET | /api/tasks/{id} | Get single task |
| PUT | /api/tasks/{id} | Update task |
| DELETE | /api/tasks/{id} | Delete task |

------------------------------------------------------------------------

## Validation Rules

| Field | Rule |
|--------|------|
| title | required, max:255 |
| description | required |
| status | todo, in_progress, done |

------------------------------------------------------------------------

## Tests

PHPUnit feature tests are implemented for the API.

Run tests:

```bash
php artisan test
```

Expected output:

```
PASS Tests\Feature\Api\TaskApiTest
Tests: 5 passed
```

------------------------------------------------------------------------

## Technology Stack

- Laravel 11
- Laravel Sanctum
- PHPUnit
- MySQL / SQLite

