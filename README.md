# Cinema Al Rahma - Ticket Booking System

A web-based cinema ticket booking system that allows users to browse movies, book tickets, and manage their bookings. The system includes both user and admin interfaces.

## Features

- User Authentication (Login/Register)
- Movie Browsing with Details
- Ticket Booking System
- Booking Management
- Admin Panel for Movie Management
- Dark/Light Theme Support
- Responsive Design

## Technical Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web Server (Apache/Nginx)
- Modern Web Browser

## Installation

1. Clone the repository:


2. Set up the database:
   - Create a new MySQL database named `cinema_ticket_booking`
   - Import the `cinema_ticket_booking.sql` file to set up the database schema

3. Configure the database connection:
   - Open `db_config.php`
   - Update the database credentials:
     ```php
     $host = 'localhost';
     $db_name = 'cinema_ticket_booking';
     $username = 'your_username';
     $password = 'your_password';
     ```

4. Set up the web server:
   - Point your web server to the project directory
   - Ensure the web server has write permissions for any upload directories

## Project Structure

```
Cinema_Tickets_Booking_System/
├── add_movie.php          # Admin interface for adding movies
├── booking.php           # Ticket booking interface
├── db_config.php         # Database configuration
├── index.php            # Login page
├── movies.php           # Movie listing page
├── register.php         # User registration
├── styles.css           # Main stylesheet
└── FPDF-master/         # PDF generation library
```

## Usage

### User Interface
1. Register a new account or login with existing credentials
2. Browse available movies
3. Select a movie to view details and available showtimes
4. Book tickets for desired showtime
5. View and manage your bookings

### Admin Interface
1. Login with admin credentials
2. Access the admin panel through the navigation
3. Add new movies with details
4. Manage existing movies and showtimes

## Security Features

- Password hashing
- SQL injection prevention using prepared statements
- XSS protection with output escaping
- Session management
- Input validation and sanitization

## Theme Support

The system includes both light and dark themes:
- Toggle between themes using the theme switch button
- Theme preference is saved in local storage
- Responsive design for all screen sizes ( works perfectly on mobile devices )

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please open an issue in the GitHub repository or contact me