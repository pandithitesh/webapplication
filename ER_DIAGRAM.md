# Event Management System - ER Diagram

## Database Schema Overview

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     USERS       │    │     EVENTS      │    │    BOOKINGS     │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ id (PK)         │    │ id (PK)         │    │ id (PK)         │
│ name            │    │ organizer_id    │◄───┤ user_id         │
│ email (unique)  │    │ title           │    │ event_id        │
│ password        │    │ description     │    │ booking_ref     │
│ role            │    │ venue           │    │ ticket_qty      │
│ created_at      │    │ address         │    │ total_amount    │
│ updated_at      │    │ city            │    │ status          │
│ deleted_at      │    │ state           │    │ payment_status  │
└─────────────────┘    │ country         │    │ created_at      │
                       │ postal_code     │    │ updated_at      │
                       │ latitude        │    └─────────────────┘
                       │ longitude       │             │
                       │ start_date      │             │
                       │ end_date        │             │
                       │ reg_deadline    │             │
                       │ capacity        │             │
                       │ price           │             │
                       │ currency        │             │
                       │ status          │             │
                       │ is_featured     │             │
                       │ requires_approval│            │
                       │ tags            │             │
                       │ slug            │             │
                       │ created_at      │             │
                       │ updated_at      │             │
                       │ deleted_at      │             │
                       └─────────────────┘             │
                                │                      │
                                │                      │
                       ┌────────▼─────────┐            │
                       │ EVENT_CATEGORIES │            │
                       ├──────────────────┤            │
                       │ event_id (FK)    │◄───────────┘
                       │ category_id (FK) │
                       │ created_at       │
                       │ PRIMARY KEY      │
                       │ (event_id,       │
                       │  category_id)    │
                       └──────────────────┘
                                │
                                │
                       ┌────────▼─────────┐
                       │    CATEGORIES    │
                       ├──────────────────┤
                       │ id (PK)          │
                       │ name (unique)    │
                       │ description      │
                       │ is_active        │
                       │ sort_order       │
                       │ created_at       │
                       │ updated_at       │
                       └──────────────────┘

┌─────────────────┐
│     REVIEWS     │
├─────────────────┤
│ id (PK)         │
│ user_id (FK)    │
│ event_id (FK)   │
│ rating          │
│ comment         │
│ created_at      │
│ updated_at      │
└─────────────────┘
```

## Key Relationships

### 1. Users → Events (One-to-Many)
- **Organizers** can create multiple events
- **Foreign Key**: `events.organizer_id` → `users.id`
- **Constraint**: Only users with `role = 'organizer'` can create events

### 2. Users → Bookings (One-to-Many)
- **Attendees** can make multiple bookings
- **Foreign Key**: `bookings.user_id` → `users.id`
- **Constraint**: Unique combination of `user_id` + `event_id` per booking

### 3. Events → Bookings (One-to-Many)
- **Events** can have multiple bookings
- **Foreign Key**: `bookings.event_id` → `events.id`
- **Business Logic**: Total bookings cannot exceed event capacity

### 4. Events ↔ Categories (Many-to-Many)
- **Events** can belong to multiple categories
- **Categories** can contain multiple events
- **Junction Table**: `event_categories`
- **Primary Key**: Composite of `(event_id, category_id)`

### 5. Users → Reviews (One-to-Many)
- **Users** can write multiple reviews
- **Events** can have multiple reviews
- **Foreign Keys**: `reviews.user_id` → `users.id`, `reviews.event_id` → `events.id`

## Advanced Feature Implementation

### Categories System (Feature B)
**Database Changes:**
- **NEW TABLE**: `categories` - stores category information
- **NEW TABLE**: `event_categories` - junction table for many-to-many relationship

**Why Many-to-Many Relationship:**
1. **Flexibility**: Events can belong to multiple categories (e.g., "Startup Pitch Competition" can be both Technology AND Business)
2. **Better Search**: Users can filter by any category an event belongs to
3. **Recommendation System**: More categories per event = better recommendation algorithms
4. **Scalability**: Easy to add new categories without restructuring existing data

### Recommendation System (Student-Designed Feature)
**Database Usage:**
- **No new tables required** - leverages existing `bookings`, `events`, and `categories` tables
- **Smart queries** analyze user booking history, event categories, and preferences
- **Efficient**: Uses existing data relationships for personalized recommendations

## Key Constraints & Business Rules

1. **Soft Deletes**: Events and Users use `deleted_at` for data retention
2. **Capacity Validation**: Bookings cannot exceed event capacity
3. **Date Validation**: Registration deadline must be before event start date
4. **Status Management**: Events and bookings have status fields for workflow management
5. **Unique Constraints**: Email addresses, event slugs, and booking references are unique

## Indexing Strategy

- **Primary Keys**: All tables have auto-incrementing primary keys
- **Foreign Keys**: Indexed for join performance
- **Unique Fields**: Email, slug, booking_reference indexed for uniqueness
- **Search Fields**: Event title, description indexed for full-text search
- **Date Fields**: start_date, end_date indexed for date range queries
