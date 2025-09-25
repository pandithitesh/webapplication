# Event Management System

A comprehensive web application for managing and booking events built with Laravel. This system allows organizers to create and manage events while enabling attendees to discover, register, and book spots for these events.

## Features

### For Event Organizers
- **Event Management**: Create, edit, and manage events with detailed information
- **Dashboard**: Comprehensive analytics and insights about events and bookings
- **Booking Management**: View and manage event registrations
- **Event Categories**: Organize events with customizable categories
- **Image Upload**: Support for event images and galleries
- **Location Services**: Full address and GPS coordinates support
- **Pricing Control**: Set event prices and currency
- **Approval System**: Optional approval workflow for bookings

### For Event Attendees
- **Event Discovery**: Browse and search events with advanced filters
- **Event Booking**: Easy registration and booking system
- **User Dashboard**: Manage bookings and view event history
- **Reviews & Ratings**: Rate and review attended events
- **Profile Management**: Update personal information and preferences
- **Booking History**: Track all past and upcoming bookings

### General Features
- **Responsive Design**: Modern, mobile-friendly interface
- **Authentication**: Secure user registration and login
- **Role-based Access**: Separate interfaces for organizers and attendees
- **Search & Filtering**: Advanced search with multiple filter options
- **Real-time Updates**: Live booking status and availability
- **Email Notifications**: Automated booking confirmations
- **API Support**: RESTful API for mobile app integration

## Technology Stack

- **Backend**: Laravel 10.x
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage
- **Icons**: Font Awesome
- **JavaScript**: Alpine.js

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js and NPM (for frontend assets)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd event-management-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup**
   - Create a MySQL database
   - Update `.env` file with database credentials
   - Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

6. **Storage Setup**
   ```bash
   php artisan storage:link
   ```

7. **Start the application**
   ```bash
   php artisan serve
   ```

8. **Build frontend assets (optional)**
   ```bash
   npm run dev
   ```

## Database Schema

### Core Tables
- **users**: User accounts (organizers and attendees)
- **events**: Event information and details
- **bookings**: Event registrations and bookings
- **categories**: Event categories
- **reviews**: User reviews and ratings
- **event_categories**: Many-to-many relationship between events and categories

### Key Relationships
- Users can organize multiple events
- Users can book multiple events
- Events can have multiple categories
- Events can have multiple bookings
- Bookings can have reviews

## API Endpoints

### Authentication
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `GET /api/auth/me` - Get current user

### Events
- `GET /api/events` - List all events
- `GET /api/events/{slug}` - Get event details
- `POST /api/events` - Create event (organizer only)
- `PUT /api/events/{id}` - Update event (organizer only)
- `DELETE /api/events/{id}` - Delete event (organizer only)

### Bookings
- `GET /api/bookings` - List user bookings
- `POST /api/bookings` - Create booking
- `GET /api/bookings/{id}` - Get booking details
- `PUT /api/bookings/{id}/cancel` - Cancel booking

### Categories
- `GET /api/categories` - List all categories

## Usage

### For Organizers

1. **Register as an Organizer**
   - Sign up with organizer role
   - Complete profile information

2. **Create Events**
   - Navigate to "Manage Events"
   - Click "Create New Event"
   - Fill in event details, location, pricing, and categories
   - Set registration deadline and capacity
   - Publish the event

3. **Manage Bookings**
   - View all bookings for your events
   - Approve or reject pending bookings
   - Track revenue and attendance

### For Attendees

1. **Register as an Attendee**
   - Sign up with attendee role
   - Complete profile information

2. **Discover Events**
   - Browse events on the homepage
   - Use search and filters to find relevant events
   - View event details and organizer information

3. **Book Events**
   - Select number of tickets
   - Add special requirements if needed
   - Complete booking process
   - Receive confirmation

## Configuration

### Environment Variables

Key environment variables to configure:

```env
APP_NAME="Event Management System"
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_management
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### File Storage

The application uses Laravel's storage system for file uploads:
- Event images are stored in `storage/app/public/events/`
- User avatars are stored in `storage/app/public/avatars/`
- Make sure to run `php artisan storage:link` to create symbolic links

## Testing

Run the test suite:

```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please contact the development team or create an issue in the repository.

## Changelog

### Version 1.0.0
- Initial release
- Basic event management functionality
- User authentication and authorization
- Booking system
- Review and rating system
- Responsive web interface
- API endpoints for mobile integration

## Future Enhancements

- Mobile application
- Payment gateway integration
- Advanced analytics and reporting
- Email marketing integration
- Social media integration
- Multi-language support
- Advanced search with AI
- Event recommendations
- Calendar integration
- QR code check-in system
