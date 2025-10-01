# Event Management System

Simple web application for managing and booking events.

## Setup
1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate:fresh --seed`
5. `php artisan serve`

## Test Accounts
- **Organizers:** john@example.com / password, jane@example.com / password
- **Attendees:** alice@example.com / password, bob@example.com / password

## Features
- Organizers can create/manage events
- Attendees can browse/book events
- Role-based access control
- Event booking with validation
- Dashboard for both user types

Open http://127.0.0.1:8000 to use the application.