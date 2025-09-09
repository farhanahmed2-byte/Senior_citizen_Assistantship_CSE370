# Senior Citizen Assistance Platform

A comprehensive web application designed to connect senior citizens with volunteers for assistance and support services. This platform facilitates volunteer management, emergency alerts, scheduling, feedback collection, and multi-language support.

## Project Overview

The Senior Citizen Assistance Platform is a PHP/MySQL-based web application that serves as a bridge between senior citizens in need of assistance and volunteers willing to help. The platform addresses the growing need for community support for elderly individuals by providing a centralized system for managing assistance requests, emergency alerts, and volunteer coordination.

### Motivation

This project was developed to address the critical need for community support systems for senior citizens, especially those living alone or with limited family support. The motivation stems from:

- **Community Building**: Creating a platform that fosters intergenerational connections
- **Emergency Response**: Providing quick access to help during emergencies
- **Resource Management**: Efficiently organizing volunteer resources and schedules
- **Accessibility**: Ensuring the platform is accessible to users with different language preferences
- **Digital Inclusion**: Helping seniors access digital services in a user-friendly manner

## ✨ Features

### Core Features

1. **User Management System**
   - Multi-role authentication (Admin, Volunteer, Senior Citizen)
   - Secure login/logout with session management
   - User profile management with detailed information

2. **Volunteer Management**
   - Volunteer registration and profile creation
   - Skills and expertise tracking
   - Availability and maximum service distance settings
   - Volunteer-senior matching system

3. **Schedule Request System**
   - Senior citizens can submit assistance requests
   - Admin approval workflow for requests
   - Status tracking (Pending, Approved, Rejected, Completed)
   - Request type categorization

4. **Emergency Alert System**
   - Real-time emergency alert submission
   - Location-based alert tracking
   - Alert status management (Active, Resolved)
   - Priority-based alert handling

5. **Feedback System**
   - User feedback collection and management
   - Rating system for services
   - Admin response capabilities
   - Feedback analytics

6. **Notification System**
   - System-wide notifications
   - User-specific message delivery
   - Real-time notification display
   - Notification history tracking

7. **Multi-Language Support**
   - English and Spanish language support
   - Additional language files for Bengali, Hindi, and Japanese
   - Dynamic language switching
   - Session-based language preference

8. **Volunteer Scheduling**
   - Shift assignment and management
   - Calendar-based scheduling interface
   - Volunteer availability tracking
   - Schedule conflict resolution

9. **Donation Tracking**
   - NGO donation management
   - Donation history and reporting
   - Financial tracking capabilities

10. **Location Services**
    - GPS location capture and storage
    - Location-based service matching
    - Geographic data management



##  Technical Stack

- **Backend**: PHP 8.0+
- **Database**: MySQL 5.7+/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Server**: Apache (XAMPP/WAMP)



## Screenshots

### Dashboard Overview

(Downloads/dashboard.jpg)
*Main dashboard showing quick access to all features and system statistics*

### User Registration
![Registration](screenshots/registration.png)
*Multi-role registration form supporting different user types*

### Emergency Alerts
![Emergency Alerts](screenshots/emergency-alerts.png)
*Emergency alert management interface with location tracking*

### Multi-Language Support
![Language Selection](screenshots/language-selection.png)
*Language switching dropdown with multiple language options*

### Volunteer Management
![Volunteer Management](screenshots/volunteer-management.png)
*Volunteer profile management and skills tracking*

### Schedule Requests
![Schedule Requests](screenshots/schedule-requests.png)
*Assistance request submission and approval workflow*

## 📁 Project Structure

```
senior_assistance/
├── 📁 api/                    # API endpoints
├── 📁 assets/                 # Static assets
│   ├── 📁 css/
│   │   └── styles.css         # Custom CSS styles
│   └── 📁 js/
│       └── app.js             # JavaScript functionality
├── 📁 config/
│   └── db.php                 # Database configuration
├── 📁 includes/               # PHP includes and utilities
│   ├── functions.php          # Core utility functions
│   ├── header.php             # Common header template
│   ├── footer.php             # Common footer template
│   ├── navbar.php             # Navigation bar template
│   └── i18n.php               # Internationalization functions
├── 📁 lang/                   # Language files
│   ├── en.php                 # English translations
│   ├── es.php                 # Spanish translations
│   ├── bn.php                 # Bengali translations
│   ├── hi.php                 # Hindi translations
│   └── ja.php                 # Japanese translations
├── 📄 index.php               # Main dashboard
├── 📄 login.php               # User authentication
├── 📄 register.php            # User registration
├── 📄 logout.php              # User logout
├── 📄 volunteers.php          # Volunteer management
├── 📄 schedules.php           # Schedule request management
├── 📄 alerts.php              # Emergency alert system
├── 📄 feedback.php            # Feedback system
├── 📄 notifications.php       # Notification management
├── 📄 volunteer_schedule.php  # Volunteer scheduling
├── 📄 donations.php           # Donation tracking
├── 📄 locations.php           # Location services
├── 📄 profile.php             # User profile management
├── 📄 schema.sql              # Database schema
└── 📄 README.md               # Project documentation
```

## 🗄️ Database Schema

The application uses a MySQL database with the following key tables:

- **`user`**: Base user information (ID, name, DOB, city, house number, gender, phone, password)
- **`senior`**: Senior citizen details (ID, preferred language, medical conditions, location, contact, user_id)
- **`volunteer`**: Volunteer details (ID, availability, max distance, skills, user_id)
- **`emergency_alert`**: Emergency alerts (ID, senior_id, alert_status, location, date, time)
- **`request`**: Assistance requests (req_id, date, status, type_assistance, senior_id)
- **`schedule`**: Scheduling (ID, name, date, volunteer_id, request_id)
- **`feedback`**: User feedback (serial_no, rating, comment, user_id)
- **`notification`**: Notifications (ID, time, message, date, user_id)
- **`matches_with`**: Volunteer-senior matching (volunteer_id, senior_id)

## 🔧 Configuration

### Database Configuration

Update `config/db.php` with your MySQL credentials:

```php
$DB_server = 'localhost';
$DB_NAME = 'senior_citizen_assistantship';
$DB_USER = 'root';
$DB_PASS = 'your_password_here';
```

### Multi-Language Support

- Use the language dropdown in the navigation bar
- Language preference persists via session
- Currently supports: English, Spanish, Bengali, Hindi, Japanese
- Easy to add new languages by creating additional `.php` files in the `lang/` directory

## 🔒 Security Features

- **Password Security**: All passwords are hashed using PHP's `password_hash()` function
- **CSRF Protection**: Cross-Site Request Forgery protection on all forms
- **Input Sanitization**: All user inputs are sanitized and validated
- **SQL Injection Prevention**: Prepared statements used for all database queries
- **Session Management**: Secure session handling with proper timeout
- **Role-Based Access Control**: Different access levels for admin, volunteer, and senior users

## 🐛 Troubleshooting

### Common Issues

1. **Blank Page Error**
   - Enable PHP error reporting in `php.ini`
   - Check `includes/header.php` for error display settings
   - Verify file permissions

2. **Database Connection Failed**
   - Verify MySQL service is running
   - Check credentials in `config/db.php`
   - Ensure database exists and schema is imported

3. **404 Not Found**
   - Ensure project is in correct directory (`htdocs/senior_assistance`)
   - Check Apache service is running
   - Verify URL path is correct

4. **Language Not Switching**
   - Clear browser cache and cookies
   - Check language files exist in `lang/` directory
   - Verify session is working properly

## 🚀 Future Enhancements

### Planned Features
- **Mobile App**: Native mobile application for better accessibility
- **Real-time Notifications**: Push notifications for emergency alerts
- **Advanced Matching**: AI-powered volunteer-senior matching algorithm
- **Video Calling**: Integrated video calling for remote assistance
- **Analytics Dashboard**: Comprehensive reporting and analytics
- **API Integration**: RESTful API for third-party integrations

### Technical Improvements
- **Performance Optimization**: Database indexing and query optimization
- **Caching System**: Redis/Memcached for improved performance
- **Microservices Architecture**: Breaking down into smaller, scalable services
- **Cloud Deployment**: AWS/Azure deployment with auto-scaling
- **Automated Testing**: Unit and integration test suite

## 📊 Project Statistics

- **Total Files**: 25+ PHP files
- **Database Tables**: 9 core tables
- **Supported Languages**: 5 languages
- **User Roles**: 3 distinct user types
- **Core Features**: 10 major features
- **Security Measures**: 6 security implementations

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Authors

- **Your Name** - *Initial work* - [YourGitHub](https://github.com/yourusername)

## 🙏 Acknowledgments

- Bootstrap team for the responsive framework
- PHP community for excellent documentation
- MySQL team for robust database support
- All contributors and testers who helped improve this project

---

**Note**: This project was developed as part of a university assignment focusing on community service applications for senior citizens. The platform demonstrates modern web development practices while addressing real-world social needs.


