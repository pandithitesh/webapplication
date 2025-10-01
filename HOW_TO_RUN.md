# How to Run the Event Management System

## Quick Start Guide

### 1. Navigate to Project Directory
```bash
cd "/Users/hiteshsharma/Downloads/Web application/event-management-system"
```

### 2. Install Dependencies (if not already done)
```bash
composer install
```

### 3. Set Up Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Create database in MySQL (if not exists)
mysql -u root -p
CREATE DATABASE event_management;
exit

# Run migrations and seed data
php artisan migrate:fresh --seed
```

### 5. Start the Server
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### 6. Access the Application
Open your browser and go to: **http://127.0.0.1:8000**

## Test User Accounts

### Organizer Accounts:
- **Email:** john.organizer@example.com
- **Password:** password

- **Email:** sarah.organizer@example.com  
- **Password:** password

### Attendee Accounts:
- **Email:** attendee1@example.com
- **Password:** password

- **Email:** attendee2@example.com
- **Password:** password

### Your Test Account:
- **Email:** hs9812000@gmail.com
- **Password:** password

## Features to Test

1. **Public Access:**
   - Browse events without login
   - View event details

2. **As Attendee:**
   - Login with attendee account
   - Book events
   - View "My Bookings"
   - See personalized recommendations

3. **As Organizer:**
   - Login with organizer account
   - Create new events
   - Edit/delete your events
   - View dashboard with statistics

## Troubleshooting

### If you get "Address already in use" error:
```bash
# Find and kill process using port 8000
lsof -ti:8000 | xargs kill -9
```

### If you get database errors:
```bash
# Reset database
php artisan migrate:fresh --seed
```

### If you get permission errors:
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

## Application Structure

- **Home Page:** http://127.0.0.1:8000
- **Events List:** http://127.0.0.1:8000/events
- **Login:** http://127.0.0.1:8000/login
- **Register:** http://127.0.0.1:8000/register

## Development Commands

```bash
# Run tests
php artisan test

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# View routes
php artisan route:list
```

## Stop the Server
Press `Ctrl + C` in the terminal where the server is running.
