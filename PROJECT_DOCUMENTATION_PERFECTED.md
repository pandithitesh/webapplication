Event Management System Project

Hitesh Sharma
Web Application Development
September 2025

Database Stuff

So I built this event management system and it uses MySQL to store all the data. I had to create 5 different tables to make everything work properly.

The users table stores both organizers and attendees. Then there's events table with all the event details like when they happen, where, how much they cost. Bookings table tracks when people actually sign up for events. I also added categories for different types of events like Tech or Business stuff. Finally there's event_categories that connects events to their categories.

For my advanced feature I made two new tables. The categories table just has category names like Technology, Business, Arts. Each category has a description and I can turn them on or off. I also made it so I can change what order they appear in.

The event_categories table is the important one - it links events to categories. One event can be in multiple categories, and one category can have tons of events.

I didn't need new tables for the recommendation system. I just made the existing booking data work smarter.

Why did I go with many-to-many for categories? Well, events are complicated. Like think about a startup pitch competition - is it Technology? Business? Education? It's probably all three. That's why I picked many-to-many instead of forcing everything into just one category. This way people can find events through different searches and my recommendation thing works better.

How I Built This Thing

When I first got this assignment I was pretty overwhelmed. I had to build an entire event management system and honestly didn't know where to start. I grabbed some paper and wrote down all the tables I thought I'd need - users, events, bookings. The tricky part was making sure organizers and attendees could both use the system without messing with each other.

Building the core features took way longer than I expected. The login system was annoying because organizers and attendees needed to see completely different stuff when they logged in. Then I had this weird bug where people could book the same event multiple times. I spent forever trying to figure out why until I realized I wasn't checking the database right.

Dates were such a pain. I couldn't figure out the timezone stuff and kept wondering if someone should be allowed to book an event that starts tomorrow or next week. I had to read through tons of Laravel documentation to get it sorted out.

Working on the advanced features was actually kind of fun. Building the recommendation system was cool because I wanted it to help people find events they'd actually want to go to, not just throw random events at them. I spent a lot of time thinking about how to make it smart but not creepy.

Problems I Had to Deal With

My recommendation algorithm would crash if someone hadn't booked any events yet. I fixed this by showing popular events first, then switching to personalized suggestions once they had some history.

The filtering was super slow because every time someone searched, the whole page reloaded and it felt terrible. I learned about AJAX and got it working so searches happen instantly. I also added a delay so it doesn't trigger on every single keystroke.

I was worried people might find ways to hack the system and book events they shouldn't. I added checks on both the frontend and backend, plus database rules to stop weird stuff from happening.

What I Learned

The main thing I figured out is that working code isn't enough - it has to actually be useful for people. My recommendation system works technically, but what matters more is that it helps people find events they want to attend.

Planning the database structure carefully at the start saved me so much time later. When I wanted to add categories, it was easy because I had already set up the relationships properly.

This project made me realize how complicated "simple" web apps actually are. So many things can go wrong, but when everything works together, it feels pretty good.

Advanced Feature

I went with Categories/Tags System with Smart Event Recommendations

I picked this because it actually helps users find events they want to attend. Categories make browsing easier, and the recommendation system takes it a step further by learning what people like.

Here's what I added to the database:

Categories Table
categories (
    id (Primary Key),
    name (Unique - like "Technology", "Business"),
    description (What this category includes),
    is_active (Can be turned on/off),
    sort_order (Display order),
    created_at,
    updated_at
)

Event-Categories Junction Table
event_categories (
    event_id (Foreign Key to events table),
    category_id (Foreign Key to categories table),
    created_at,
    Primary Key: (event_id, category_id)
)

Why I picked many-to-many

Real events are messy - they don't fit into just one category. For example:
A "Digital Marketing Workshop" is both "Business" and "Technology"
A "Food & Wine Festival" could be "Entertainment", "Food & Drink", and "Lifestyle"
A "Startup Pitch Competition" is "Business", "Technology", and "Education"

I thought about making it one-to-many (one category per event) but that would be too limiting. People would miss relevant events because they were categorized differently. With many-to-many, users can find events through different searches and the recommendations work better.

Custom Feature: Intelligent Event Recommendation Engine

This system looks at what users have booked before and suggests events they might actually want to attend. It's like having someone who knows your taste in events.

How it meets "excellent" criteria:

Improves User Experience
Before: Users had to scroll through tons of events and often missed good ones
After: Personalized suggestions show up right at the top with clear explanations
Result: People find events they wouldn't have discovered otherwise

Automates a Process
The system automatically figures out what users like based on their booking history
Recommendations update in real-time as people book more events
No manual work needed - it learns by itself

Complex Business Logic
Uses multiple factors: categories, location, price, timing
Handles new users by showing popular events first
Gives explanations like "Similar to events you've booked" or "In a city you've visited"

Advanced Technical Skills
Clean service layer architecture
Fast database queries with proper indexing
AJAX integration for smooth user experience

How It Works

For new users:
1. See "Featured Events for You" section with popular events
2. Clear labeling explains these are special recommendations
3. Gets them started with good events

For returning users:
1. See "Recommended for You" based on their booking history
2. Each recommendation explains why it was suggested
3. Recommendations get smarter as they book more events

Technical details:

Key components:
- EventRecommendationService - does all the recommendation logic
- EventController - handles showing recommendations on pages
- Category system - powers the recommendation algorithms
- AJAX integration - makes everything load instantly

User interface:
- Home page shows personalized recommendations prominently
- Events page has dedicated "Recommended for You" section
- Purple/indigo design with clear "Recommended" badges
- Smooth animations when recommendations load

How the code works:
1. User visits events page → system checks if they're logged in
2. If they're an attendee → RecommendationService looks at their booking history
3. Service calculates scores based on categories, location, price preferences
4. Returns top 6 recommendations with explanations
5. Frontend shows recommendations with visual indicators

Performance stuff:
- Recommendations are cached during the session
- Database queries are optimized with proper indexing
- AJAX prevents page refresh delays
- System learns without slowing things down

The end result:
This turns a basic event listing into a smart discovery tool. Instead of users searching through hundreds of events, the system proactively suggests relevant ones. It's like having a friend who knows your preferences and helps you find events you'll actually enjoy.

The recommendation system works technically, but more importantly, it genuinely helps people discover and engage with events they want to attend.

That's pretty much everything about my project. I'm happy with how it turned out and learned a lot building it.