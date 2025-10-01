Event Management System - Technical Documentation

Student: Hitesh Sharma
Course: Web Application Development

Code File Structure and Logic Implementation

This document explains exactly which files contain which code and logic in my event management system.

Database Structure Files

database/migrations/2024_01_01_000000_create_events_table.php
- Contains the events table schema
- Fields: id, organizer_id, title, description, venue, start_date, end_date, capacity, price, status
- Logic: Soft deletes with deleted_at column

database/migrations/2024_01_01_000001_create_bookings_table.php
- Contains the bookings table schema
- Fields: id, user_id, event_id, booking_reference, ticket_quantity, total_amount, status
- Logic: Tracks booking status (pending, confirmed, cancelled)

database/migrations/2024_01_01_000002_create_categories_table.php
- Contains categories table schema
- Fields: id, name, description, is_active
- Logic: Stores event categories like Technology, Business, Arts

database/migrations/2024_01_01_000003_create_event_categories_table.php
- Contains junction table for many-to-many relationship
- Fields: event_id, category_id
- Logic: Links events to multiple categories

Model Files and Their Logic

app/Models/User.php
- Contains user authentication and role logic
- Methods: isOrganizer(), isAttendee(), organizedEvents(), bookings()
- Logic: Role-based user types (organizer/attendee)

app/Models/Event.php
- Contains event business logic
- Methods: isRegistrationOpen(), isSoldOut(), getAvailableSpotsAttribute()
- Logic: Event status checking, capacity validation, soft deletes
- Relationships: belongsTo(User), hasMany(Booking), belongsToMany(Category)

app/Models/Booking.php
- Contains booking validation logic
- Methods: scopeConfirmed(), scopePending()
- Logic: Booking status management, payment tracking
- Relationships: belongsTo(User), belongsTo(Event)

app/Models/Category.php
- Contains category management logic
- Methods: scopeActive()
- Logic: Category filtering and organization
- Relationships: belongsToMany(Event)

Controller Files and Their Logic

app/Http/Controllers/Web/EventController.php
- Contains event CRUD operations
- Methods: index(), show(), create(), store(), edit(), update(), destroy()
- Logic: Event filtering with AJAX, recommendation system integration
- Lines 1-50: Event listing with pagination
- Lines 51-100: Event creation form handling
- Lines 101-150: Event editing and validation
- Lines 151-200: Event deletion with booking checks

app/Http/Controllers/Web/HomeController.php
- Contains home page logic
- Methods: index()
- Logic: Featured events display, recommendation system for logged-in users

app/Http/Controllers/Web/AuthController.php
- Contains authentication logic
- Methods: showLoginForm(), login(), logout()
- Logic: User login/logout, role-based redirects

app/Http/Controllers/BookingController.php
- Contains booking creation logic
- Methods: store(), index()
- Logic: Booking validation, capacity checking, duplicate prevention
- Lines 1-50: Booking creation with validation
- Lines 51-100: Booking status management

app/Http/Controllers/DashboardController.php
- Contains dashboard logic
- Methods: organizer(), attendee(), upcomingEvents()
- Logic: Role-based dashboards, event statistics, booking reports

Service Files and Their Logic

app/Services/EventRecommendationService.php
- Contains recommendation algorithm logic
- Methods: getRecommendationsForUser(), calculateCategoryScore(), getUserBookingHistory()
- Logic: Multi-factor scoring system (categories, location, price, timing)
- Lines 1-50: User preference analysis
- Lines 51-100: Category-based scoring
- Lines 101-150: Location and price matching
- Lines 151-200: Recommendation ranking and filtering

Middleware Files and Their Logic

app/Http/Middleware/RoleMiddleware.php
- Contains role-based access control logic
- Methods: handle()
- Logic: Checks user role (organizer/attendee) before allowing access to protected routes

View Files and Their Logic

resources/views/events/index.blade.php
- Contains event listing page with AJAX filtering
- Logic: Real-time search, category filtering, recommendation display
- JavaScript: AJAX calls for filtering without page reload

resources/views/events/show.blade.php
- Contains event details and booking form
- Logic: Booking form display, capacity checking, organizer action buttons

resources/views/events/create.blade.php
- Contains event creation form
- Logic: Form validation, category selection, date validation

resources/views/events/edit.blade.php
- Contains event editing form
- Logic: Pre-filled form data, validation, update handling

resources/views/dashboard/organizer.blade.php
- Contains organizer dashboard
- Logic: Event statistics, booking reports, event management links

resources/views/dashboard/attendee.blade.php
- Contains attendee dashboard
- Logic: Booking history, upcoming events, profile management

resources/views/layouts/app.blade.php
- Contains main layout template
- Logic: Navigation menu, role-based menu items, user authentication display

Route Files and Their Logic

routes/web.php
- Contains all web routes
- Logic: Route grouping by authentication, role-based route protection
- Lines 1-30: Public routes (home, events listing)
- Lines 31-60: Authentication routes (login, register, logout)
- Lines 61-90: Organizer protected routes
- Lines 91-120: Attendee protected routes

Factory Files and Their Logic

database/factories/UserFactory.php
- Contains user data generation logic
- Methods: organizer(), attendee()
- Logic: Creates test users with different roles

database/factories/EventFactory.php
- Contains event data generation logic
- Methods: definition(), upcoming()
- Logic: Generates realistic event data with proper date relationships

database/factories/BookingFactory.php
- Contains booking data generation logic
- Methods: definition()
- Logic: Creates test bookings with proper relationships

Seeder Files and Their Logic

database/seeders/UserSeeder.php
- Contains user seeding logic
- Logic: Creates organizer and attendee users for testing

database/seeders/EventSeeder.php
- Contains event seeding logic
- Logic: Creates sample events with categories and realistic data

database/seeders/CategorySeeder.php
- Contains category seeding logic
- Logic: Creates event categories (Technology, Business, Arts, etc.)

Test Files and Their Logic

tests/Feature/EventTest.php
- Contains event functionality tests
- Logic: Tests event creation, editing, deletion, booking validation

tests/Feature/BookingTest.php
- Contains booking functionality tests
- Logic: Tests booking creation, capacity validation, duplicate prevention

tests/Feature/AuthTest.php
- Contains authentication tests
- Logic: Tests user login, logout, role-based access

tests/Unit/EventTest.php
- Contains event model unit tests
- Logic: Tests model methods, relationships, validation rules

tests/Unit/RecommendationServiceTest.php
- Contains recommendation system tests
- Logic: Tests recommendation algorithm, scoring system, user preferences

Specific Code Logic Implementation

Event Creation Logic (EventController@store):
- Validation rules in lines 45-65
- Event saving with organizer_id in lines 66-75
- Category attachment in lines 76-85

Booking Validation Logic (BookingController@store):
- Duplicate booking check in lines 25-35
- Capacity validation in lines 36-45
- Registration deadline check in lines 46-55

Recommendation Algorithm Logic (EventRecommendationService@getRecommendationsForUser):
- User booking history analysis in lines 20-40
- Category scoring in lines 41-70
- Location matching in lines 71-90
- Final ranking in lines 91-120

AJAX Filtering Logic (events/index.blade.php):
- JavaScript filtering in lines 200-250
- Real-time search in lines 251-300
- Category filtering in lines 301-350

Database Query Logic:
- Event filtering with categories in EventController@index lines 30-50
- Booking statistics in DashboardController@organizer lines 25-45
- User recommendations in HomeController@index lines 15-35

Security Logic:
- Role-based access in RoleMiddleware@handle lines 15-30
- CSRF protection in all forms
- Input validation in all controllers
- SQL injection prevention through Eloquent ORM

This documentation shows exactly which files contain which specific code and logic implementations in the event management system.

Hitesh Sharma
