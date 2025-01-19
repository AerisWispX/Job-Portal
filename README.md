# 🎯 CareerConnect - Job Portal Website

![CareerConnect Banner](https://capsule-render.vercel.app/api?type=waving&color=gradient&customColorList=24&height=300&section=header&text=CareerConnect&fontSize=90&animation=fadeIn&desc=Your%20Gateway%20to%20Career%20Opportunities&descAlignY=65)

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

</div>

## 📋 Table of Contents
- [Features](#-features)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Database Schema](#-database-schema)
- [Directory Structure](#-directory-structure)
- [User Guide](#-user-guide)
- [Admin Panel](#-admin-panel)
- [Screenshots](#-screenshots)
- [Security Features](#-security-features)
- [Contributing](#-contributing)

## ✨ Features

### 👤 For Job Seekers
- User registration and profile management
- Resume builder and upload
- Job search with advanced filters
- Job application tracking
- Saved jobs functionality
- Email notifications for application updates
- Profile visibility settings

### 💼 For Companies
- Company profile creation and management
- Job posting with detailed descriptions
- Applicant tracking system
- Candidate shortlisting
- Interview scheduling
- Company analytics dashboard
- Email notifications for new applications

### 👨‍💼 For Admin
- User management system
- Job post approval workflow
- Company verification system
- Analytics and reporting
- Content management
- Email template customization
- System settings management

## 💻 System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Modern web browser
- SMTP server for email notifications

## 🚀 Installation

1. Clone the repository
```bash
git clone https://github.com/your-username/career-connect.git
```

2. Configure database
```bash
# Import database schema
mysql -u username -p database_name < database/schema.sql
```

3. Update configuration
```php
// config/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'career_connect');
```

4. Set up email configuration
```php
// config/mail.php
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_USER', 'your_email');
define('SMTP_PASS', 'your_password');
define('SMTP_PORT', 587);
```

5. Set proper permissions
```bash
chmod 755 uploads/
chmod 644 config/config.php
```

## 📊 Database Schema

```sql
-- Main tables structure
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    user_type ENUM('jobseeker', 'company', 'admin'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE jobs (
    job_id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT,
    title VARCHAR(100),
    description TEXT,
    requirements TEXT,
    salary_range VARCHAR(50),
    location VARCHAR(100),
    status ENUM('pending', 'approved', 'rejected'),
    FOREIGN KEY (company_id) REFERENCES users(user_id)
);

CREATE TABLE applications (
    application_id INT PRIMARY KEY AUTO_INCREMENT,
    job_id INT,
    user_id INT,
    status ENUM('pending', 'shortlisted', 'rejected'),
    FOREIGN KEY (job_id) REFERENCES jobs(job_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
```

## 📁 Directory Structure

```
career-connect/
├── admin/                 # Admin panel files
├── assets/               # CSS, JS, images
├── config/               # Configuration files
├── database/             # Database scripts
├── includes/             # PHP includes
├── uploads/              # User uploads
├── vendor/               # Dependencies
├── index.php            # Main entry point
├── register.php         # Registration page
├── login.php            # Login page
└── README.md            # Documentation
```

## 📘 User Guide

### Job Seeker Registration
1. Click "Register" on the homepage
2. Select "Job Seeker" account type
3. Fill in required information
4. Upload resume (optional)
5. Verify email address

### Company Registration
1. Click "Register" on the homepage
2. Select "Company" account type
3. Provide company details
4. Upload company logo
5. Await admin verification

### Posting a Job (Companies)
1. Log in to company dashboard
2. Click "Post New Job"
3. Fill in job details
4. Set requirements and preferences
5. Submit for admin approval

### Applying for Jobs (Job Seekers)
1. Search for jobs using filters
2. View job details
3. Click "Apply Now"
4. Review application
5. Submit application

## 👑 Admin Panel

### Features
- Dashboard with analytics
- User management
- Job post moderation
- Company verification
- System settings
- Email template management
- Reports generation

### Access
```
URL: your-domain.com/admin
Default credentials:
Username: admin
Password: admin123
```

## 📸 Screenshots

```
Add your application screenshots here
```

## 🔒 Security Features

- Password hashing using bcrypt
- CSRF protection
- SQL injection prevention
- XSS protection
- Rate limiting
- Input validation
- File upload validation
- Session security
- SSL/TLS support

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/YourFeature`)
3. Commit changes (`git commit -m 'Add YourFeature'`)
4. Push to branch (`git push origin feature/YourFeature`)
5. Open Pull Request

## 🐛 Known Issues

- [Issue 1]: Description
- [Issue 2]: Description

## 📝 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## 🔄 Updates and Maintenance

- Regular security patches
- Feature updates
- Bug fixes
- Performance optimization

## 🌟 Upcoming Features

- [ ] Advanced resume parsing
- [ ] AI-powered job matching
- [ ] Mobile application
- [ ] Integration with popular job boards
- [ ] Video interview platform
- [ ] Skill assessment tests

---

<div align="center">
  
### 💡 Need Help?
Contact us at: support@your-domain.com

[![GitHub Issues](https://img.shields.io/github/issues/your-username/career-connect)](https://github.com/your-username/career-connect/issues)
[![GitHub Stars](https://img.shields.io/github/stars/your-username/career-connect)](https://github.com/your-username/career-connect/stargazers)
[![GitHub License](https://img.shields.io/github/license/your-username/career-connect)](https://github.com/your-username/career-connect/blob/main/LICENSE)

</div>
