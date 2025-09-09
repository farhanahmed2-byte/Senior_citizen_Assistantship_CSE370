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

##  Features

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

### Dashboard

![Dashboard](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/8e0a0603662d26236baa33e59a614d92f72ff263/dashboard.jpg)


### User Registration
![Registration](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/ab852037d8ab2994862d636b608197a3acef5f2c/register.jpg)
### Login
![login](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/ab852037d8ab2994862d636b608197a3acef5f2c/login.jpg)

### Feedback
![Feedback](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/ab852037d8ab2994862d636b608197a3acef5f2c/feedback.jpg)

### Notification
![Notification](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/ab852037d8ab2994862d636b608197a3acef5f2c/notifications.jpg)

### Emergency Alerts
![Emergency Alerts](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/ab852037d8ab2994862d636b608197a3acef5f2c/emergency_alert.jpg)


### Multi-Language Support
![Language Selection](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/ab852037d8ab2994862d636b608197a3acef5f2c/language.jpg)

### Volunteer Management
![Volunteer Management](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/ab852037d8ab2994862d636b608197a3acef5f2c/volunteer_management.jpg)


### Schedule Requests
![Schedule Requests](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/ab852037d8ab2994862d636b608197a3acef5f2c/schedule_request.jpg)

### Volunteer Schedule
![Volunteer Schedule](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/ab852037d8ab2994862d636b608197a3acef5f2c/volunteer_schedule.jpg)

### Donation
![Donation](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/ab852037d8ab2994862d636b608197a3acef5f2c/donation.jpg)

### Location
![Location](https://github.com/farhanahmed2-byte/Senior_citizen_Assistantship_CSE370/blob/ab852037d8ab2994862d636b608197a3acef5f2c/location.jpg)


## Project Structure

```
senior_assistance/
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

##  Database Schema

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


### Multi-Language Support

- Use the language dropdown in the navigation bar
- Language preference persists via session
- Currently supports: English, Spanish, Bengali, Hindi, Japanese
- Easy to add new languages by creating additional `.php` files in the `lang/` directory






