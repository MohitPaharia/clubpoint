# Club Management System

A **Laravel-based club management platform** designed to manage clubs, events, authentication, and user registrations. The application can be **self-hosted** or deployed to **Railway**.

## Features

* Authentication system
* Admin panel
* Club CRUD operations
* Event CRUD operations
* Club search and lookup
* Event search and lookup
* Free and paid-entry event creation
* Admin approval for clubs
* User location detection based on IP address
* SQLite support for local development
* PostgreSQL support for production deployments
* Self-hostable architecture

## Admin Panel

The admin panel provides administrative control over clubs, including:

* Creating clubs
* Deleting clubs
* Approving clubs before they become available to users

## Requirements

For local development, install:

* PHP
* Composer

The project is configured to use **SQLite** by default, but **PostgreSQL** is also supported.

---

## Local Setup

### 1. Clone the repository

```bash
git clone https://github.com/MohitPaharia/clubpoint.git
cd clubpoint
```

### 2. Configure the environment

Copy the example environment file:

```bash
cp .env.example .env
```

### 3. Install dependencies

```bash
composer update
```

### 4. Generate the application key

```bash
php artisan key:generate
```

### 5. Run database migrations

```bash
php artisan migrate
```

### 6. Seed the database

```bash
php artisan db:seed
```

### 7. Start the application

```bash
php artisan serve
```

The application will now be available through the Laravel development server.

---

## Railway Deployment

The project can be deployed on **Railway**, which provides dedicated support for Laravel applications.

Before connecting the application to the Railway database:

1. Create a PostgreSQL database on Railway.
2. Dump the existing database schema.
3. Create the required schema in the Railway database.
4. Seed the database with the required data.
5. Configure the Laravel environment variables for the Railway database.
6. Deploy and configure the Laravel application through Railway.

The application can use **PostgreSQL** for the Railway deployment even though SQLite is used for local development.

### Mail Service

Email functionality works locally when configured **Correctly**.

For Railway deployments, sending email requires a **paid Railway subscription or a paid external mail provider**.

Since user registration depends on email functionality, **registration will not work on Railway until a supported mail service is configured**.

---

## Database Support

| Environment          | Database   |
| -------------------- | ---------- |
| Local development    | SQLite     |
| Production / Railway | PostgreSQL |

Both databases are supported by the application.

## Project Structure

The application is built using the **Laravel PHP framework**, with the backend, authentication, database operations, and application logic managed through Laravel.

## Deployment

The application is designed to support both:

* **Self-hosting** — suitable for running on your own server.
* **Railway** — recommended for convenient cloud deployment and Laravel hosting.


