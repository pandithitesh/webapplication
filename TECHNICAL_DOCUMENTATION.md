Event Management System - Technical Documentation

Student: Hitesh Sharma
Course: Web Application Development

How Everything Works in My Event Management System

This document explains the technical details of how my event management system works, including the code flow, file structure, logic, and implementation.

Database Structure and Relationships

My system uses MySQL with these main tables:

users table
- Stores both organizers and attendees
- Has role field: 'organizer' or 'attendee'
- Contains basic user info like name, email, password

events table
- Stores all event information
- Connected to users table via organizer_id
- Has fields like title, description, venue, dates, capacity, price
- Uses soft deletes (deleted_at column)

bookings table
- Tracks when attendees register for events
- Connected to both users and events tables
- Has booking_reference, ticket_quantity, total_amount
- Stores payment and booking status

categories table
- Stores event categories like Technology, Business, Arts
- Has name, description, is_active fields

event_categories table
- Junction table for many-to-many relationship
- Links events to multiple categories
- Primary key is (event_id, category_id)

How User Authentication Works

When someone tries to log in:
1. Login form submits to /login route
2. AuthController@login method handles the request
3. Laravel's built-in authentication checks email/password
4. If valid, user gets logged in and redirected based on role
5. Middleware checks if user is authenticated on protected routes

Role-based access control:
- Organizers see different dashboard than attendees
- RoleMiddleware checks user role before allowing access
- Different controllers handle organizer vs attendee functionality

How Event Management Works

Creating Events (Organizers):
1. Organizer goes to /events/create
2. EventController@create shows the form
3. Form submits to EventController@store
4. Validation checks all required fields
5. Event gets saved to database with organizer_id
6. User gets redirected to events list

Editing Events:
1. Organizer clicks edit on their event
2. EventController@edit loads the form with current data
3. Form submits to EventController@update
4. Only the organizer who created the event can edit it
5. Validation runs again before saving changes

Deleting Events:
1. Organizer clicks delete on their event
2. System checks if event has any bookings
3. If no bookings, event gets soft deleted
4. If bookings exist, deletion is blocked

How Booking System Works

Making a Booking:
1. Attendee clicks "Book Now" on an event
2. BookingController@store handles the request
3. System checks if user is already booked for this event
4. System checks if event has available capacity
5. System checks if event is still accepting registrations
6. If all checks pass, booking gets created
7. Booking reference number gets generated
8. User gets redirected to their bookings page

Booking Validation Logic:
- Can't book same event twice (unique user_id + event_id)
- Can't book if event is full (capacity reached)
- Can't book if registration deadline has passed
- Can't book if event has already started

How Recommendation System Works

My custom recommendation engine analyzes user behavior to suggest events:

1. EventRecommendationService class handles all recommendation logic
2. When attendee visits events page, system checks their booking history
3. For new users: Shows popular featured events
4. For returning users: Analyzes past bookings to find patterns

Recommendation Algorithm:
- Looks at categories of events user has booked before
- Considers cities where user has attended events
- Checks price ranges user has paid
- Calculates scores based on these factors
- Returns top 6 recommendations with explanations

How Categories Work

Categories help organize events:
1. Events can belong to multiple categories
2. Many-to-many relationship via event_categories table
3. Users can filter events by category
4. Recommendation system uses categories to find similar events

Adding categories to events:
1. When creating/editing event, organizer selects categories
2. System saves relationships in event_categories table
3. Events can be found through multiple category searches

How AJAX Filtering Works

Real-time event filtering without page reloads:
1. JavaScript listens for input changes in search/filter forms
2. AJAX request sent to EventController@index with filter parameters
3. Controller queries database based on filters
4. Returns filtered events as JSON
5. JavaScript updates the page content
6. Debouncing prevents too many requests while typing

Filter options:
- Search by title or description
- Filter by category
- Filter by date range
- Filter by price range
- Filter by location

File Structure and What Each File Does

Models (app/Models/):
- User.php: Handles user data, relationships to events and bookings
- Event.php: Event data, relationships, helper methods like isSoldOut()
- Booking.php: Booking data and validation
- Category.php: Category data and relationships
- Review.php: Review system (optional feature)

Controllers (app/Http/Controllers/):
- Web/AuthController.php: Handles login/logout
- Web/EventController.php: Event CRUD operations, filtering, recommendations
- Web/BookingController.php: Booking creation and management
- Web/DashboardController.php: Different dashboards for organizers/attendees
- Web/HomeController.php: Home page with recommendations

Services (app/Services/):
- EventRecommendationService.php: Core recommendation algorithm

Middleware (app/Http/Middleware/):
- RoleMiddleware.php: Checks user roles for access control

Views (resources/views/):
- layouts/app.blade.php: Main layout with navigation
- home.blade.php: Home page with featured events
- events/index.blade.php: Event listing with filters
- events/show.blade.php: Event details and booking form
- events/create.blade.php: Event creation form
- events/edit.blade.php: Event editing form
- dashboard/organizer.blade.php: Organizer dashboard
- dashboard/attendee.blade.php: Attendee bookings page

Routes (routes/web.php):
- Public routes: home, events listing, event details
- Auth routes: login, logout, register
- Protected routes: dashboards, event management, bookings
- Role-based route groups for organizers and attendees

Database Migrations (database/migrations/):
- create_users_table.php: Users table structure
- create_events_table.php: Events table with all fields
- create_bookings_table.php: Bookings table structure
- create_categories_table.php: Categories table
- create_event_categories_table.php: Junction table

Factories (database/factories/):
- UserFactory.php: Creates test users (organizers and attendees)
- EventFactory.php: Creates test events with realistic data
- BookingFactory.php: Creates test bookings
- CategoryFactory.php: Creates test categories

Seeders (database/seeders/):
- UserSeeder.php: Seeds organizer and attendee users
- EventSeeder.php: Seeds sample events
- CategorySeeder.php: Seeds event categories
- DatabaseSeeder.php: Runs all seeders

How Security Works

Input Validation:
- All forms have server-side validation rules
- Required fields are checked
- Data types and formats are validated
- SQL injection prevented by using Eloquent ORM

Authorization:
- Users can only edit/delete their own events
- Attendees can only see their own bookings
- Role-based access to different features
- Middleware protects sensitive routes

Data Protection:
- Passwords are hashed using Laravel's bcrypt
- CSRF tokens protect against cross-site attacks
- User input is sanitized before database storage

How Testing Works

My test suite covers:
- User authentication and registration
- Event creation, editing, and deletion
- Booking system with validation
- Recommendation system functionality
- Database relationships and constraints

Test files:
- Feature/AuthTest.php: Tests login/logout functionality
- Feature/EventTest.php: Tests event CRUD operations
- Feature/BookingTest.php: Tests booking creation and validation
- Feature/RecommendationTest.php: Tests recommendation algorithm
- Unit tests for individual models and services

How the Application Starts

1. User visits the website
2. Laravel loads routes from web.php
3. HomeController@index shows home page
4. If user is logged in, shows personalized recommendations
5. If not logged in, shows featured events
6. Navigation changes based on user role and login status

User Experience Flow:
- New users can browse events and register
- Registered users can book events and manage bookings
- Organizers can create/manage events and view reports
- System provides personalized recommendations for better discovery

Performance Optimizations:
- Database queries are optimized with proper indexing
- AJAX prevents unnecessary page reloads
- Recommendations are cached during user session
- Images and assets are optimized for fast loading

This is how my entire event management system works from the database level up to the user interface, with all the logic, security, and features integrated together.

Hitesh Sharma
