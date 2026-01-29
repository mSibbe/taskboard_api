<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


# Laravel Taskboard API (Extended)

This project is a Laravel-based REST API for managing tasks with
authentication, projects, deadlines, notifications, and role-based
authorization.

------------------------------------------------------------------------

## Project Description

This project provides a RESTful API built with Laravel for managing
tasks, projects, and users.

The API allows authenticated users to create, update, delete, and list
their own tasks.\
Each task contains the following properties:

-   Title
-   Description
-   Status (todo, in_progress, done)
-   Deadline (validated to be a future datetime)
-   Assigned User
-   Optional Project assignment

Additionally, the system supports:

-   Projects that can contain multiple tasks
-   Filtering tasks by user or project
-   An endpoint for retrieving overdue tasks
-   Role-based authorization for editing overdue tasks
-   Automatic notifications when tasks become overdue (Event +
    Listener + Notification)

Access to protected endpoints is handled via token-based authentication
using **Laravel Sanctum**, and middleware ensures that users can only
access and modify their own tasks.


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
http://taskboard.test/api
```

------------------------------------------------------------------------

## Auth Endpoints

### Register

```
POST /api/register
```

```json
{
  "name": "John Doe",
  "email": "john@test.com",
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

## Roles

To make a user admin:

``` sql
UPDATE users SET role = 'admin' WHERE id = 1;
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

| Field       | Rule                    |
|-------------|-------------------------|
| title       | required, max:255       |
| description | required                |
| status      | todo, in_progress, done |
| deadline    | nullable, date, after:now   |

------------------------------------------------------------------------
### Deadline validation

-   Must be a valid datetime
-   Must be in the future

Example:

``` json
{
  "deadline": "2030-01-01 12:00:00"
}
```

------------------------------------------------------------------------

## Projects

| Method | Endpoint | Description                |
|--------|-----------|----------------------------|
| GET | /api/projects/{project}/tasks | Get all tasks from project |

------------------------------------------------------------------------

## Users

| Method | Endpoint | Description             |
|--------|-----------|-------------------------|
| GET | /api/users/{user}/tasks | Get all tasks from user |

------------------------------------------------------------------------

## Overdue Tasks

| Method | Endpoint | Description                       |
|--------|-----------|-----------------------------------|
| GET | /api/overdue | Get all overdue tasks |

Returns all overdue tasks of the authenticated user where:

-   deadline \< now
-   status != done

------------------------------------------------------------------------

------------------------------------------------------------------------

## Authorization & Middleware

### Own Tasks Only

Middleware ensures users can only:

-   view
-   update
-   delete

their own tasks.

### Overdue Task Authorization

Only users with role:

    admin

can edit overdue tasks.

User roles are stored in:

    users.role

Default: `user`

------------------------------------------------------------------------

## Notifications (Event + Listener)

When a task becomes overdue:

1.  TaskObserver detects it
2.  TaskOverdue event is fired
3.  Listener sends notification
4.  Stored in database table `notifications`

### View notifications:

| Method | Endpoint            | Description           |
|--------|---------------------|-----------------------|
| GET | /api/notifications  | Get all notifications |

Returns all notifications of the authenticated user

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
Tests: 11 passed
```

Includes tests for:

-   CRUD API
-   Authentication
-   Middleware security
-   Relations (User ↔ Task, Project ↔ Task)
-   Overdue endpoint
-   Deadline validation
-   Event + Notification system

------------------------------------------------------------------------

## Postman

Import the provided Postman collection:

    Laravel_Task_API_Extended.postman_collection.json

------------------------------------------------------------------------

## Technology Stack

- Laravel 11
- Laravel Sanctum
- PHPUnit
- MySQL / SQLite

