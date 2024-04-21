# Task Management API

This API provides endpoints for managing tasks, including CRUD operations and authentication for users.

## Features

- **Authentication**: Users can authenticate using their credentials.
- **Task CRUD**: Users can perform CRUD operations on tasks.
- **Swagger Documentation**: API endpoints are documented using Swagger/OpenAPI.
- **Feature Tests**: Feature tests are included to ensure API functionality.

## Endpoints

### Authentication

- `POST /api/register`: Register a new user.
- `POST /api/login`: Authenticate user and generate token.



### Task Management

- `GET /api/tasks`: Retrieve all tasks.
- `POST /api/tasks`: Create a new task.
- `GET /api/tasks/{taskId}`: Retrieve a task by ID.
- `PUT /api/tasks/{taskId}`: Update a task by ID.
- `DELETE /api/tasks/{taskId}`: Delete a task by ID.

## Documentation

API endpoints are documented using Swagger/OpenAPI. You can access the Swagger documentation by running the application and visiting `/public/swagger` folder.

## Installation

1. Clone the repository:

```bash
git clone https://github.com/moharami/heli
```

2. Install dependencies:

```bash
composer install
```

3. Set up environment variables:

```bash
cp .env.example .env
```

4. Generate application key:

```bash
php artisan key:generate
```

5. Configure database settings in `.env` file.

6. Run migrations:

```bash
php artisan migrate
```

7. Serve the application:

```bash
php artisan serve
```

## Running Tests

You can run feature tests using PHPUnit:

```bash
php artisan test
```

## Usage

1. Authenticate using `/api/login` endpoint to obtain a token.
2. Use the token in the Authorization header for accessing protected endpoints.


Certainly! Here is the revised section for setting up a cron job in your README:

---

## Automating Task Completion with Cron Job

To automatically mark tasks as complete after they have been active for more than 2 days, you can set up a cron job in your server. Follow the steps below to configure the cron job:

1. **Open Terminal**: Access the terminal or SSH into your server.

2. **Edit Crontab**: Edit the crontab by running:

    ```bash
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
    ```

3. **Testing**: Ensure to test the command locally before deploying it to the production server.

By setting up this cron job, tasks in your application will automatically be marked as complete after exceeding a 2-day period of activity, streamlining your task management process.


