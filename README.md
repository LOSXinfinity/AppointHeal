# AppointHeal 🏥

> A web-based local clinic appointment system that lets patients register, search doctors by specialization, and book or cancel timeslots in real time.

---

## 📋 Table of Contents

- [About the Project](#about-the-project)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Database Design](#database-design)
- [Getting Started](#getting-started)
- [Usage](#usage)
- [Project Structure](#project-structure)

---

## About the Project

**AppointHeal** is an academic project designed to simplify healthcare access for local communities. Many small clinics still rely on manual paper logs for booking appointments, which causes scheduling errors and long wait times.

AppointHeal solves this by providing a digital booking platform that bridges the gap between patients and healthcare providers — allowing patients to find available doctors and book timeslots with ease.

---

## Features

| Feature | Description |
|---|---|
| 🔐 Login & Registration | Users can create secure accounts to manage personal medical bookings |
| 🔍 Doctor Search | Search and filter doctors by name or specialization |
| 📅 Real-Time Scheduling | View doctor availability and book open timeslots instantly |
| ❌ Appointment Cancellation | Cancel existing bookings with ease |
| 🩺 Doctor Dashboard | Medical staff can view their daily schedules and manage upcoming patient visits |
| 🚪 Logout | Secure session management |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3 |
| Backend | PHP |
| Database | MySQL |

---

## Database Design

- **ER/EER Diagram:** [View on Google Drive](https://drive.google.com/file/d/1ZvfH9Kwub5lDewBkMokFUYc9HOCd5d6f/view?usp=sharing)
- **Schema Diagram:** [View on Google Drive](https://drive.google.com/file/d/1hxpcNDdYhsUqcNhA-OvDnq-EqUit7blw/view?usp=sharing)

---

## Getting Started

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- A local server environment  [XAMPP](https://www.apachefriends.org/)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/appointheal.git
   ```

2. **Move the project to your server's root directory**
   ```bash
   # For XAMPP:
   cp -r appointheal/ /xampp/htdocs/
   ```

3. **Set up the database**
   - Open your browser and go to `http://localhost/phpmyadmin`
   - Create a new database named `appointheal`
   - Import `appointheal.sql` from the project root

4. **Configure database connection**
   - Open `db.php` and update the credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'appointheal');
   ```

5. **Run the project**
   - Start Apache and MySQL from XAMPP
   - Navigate to `http://localhost/appointheal`

---

## Usage

1. **Register** as a new patient or log in with existing credentials
2. **Browse** the doctor dashboard and filter by name or specialization
3. **Book** an available timeslot with your chosen doctor
4. **Cancel** appointments from your patient dashboard if needed
5. **Doctors** can log in to view their daily schedules

---

## Project Structure

```
AppointHeal/
├── appointheal.sql             # Database schema & seed data
│
├──── Core Pages ──
├── login.html                  # Login page (frontend)
├── login.php                   # Login logic (backend)
├── login.css                   # Login page styles
├── register.html               # Registration page (frontend)
├── register.php                # Registration logic (backend)
├── register.css                # Registration page styles
├── page1.html                  # Landing / home page
├── logout.php                  # Session logout handler
│
├── ── Appointments ──
├── appointment.php             # Appointment management
├── book_appointment.php        # Booking logic
├── cancel_appointment.php      # Cancellation logic
│
├── ── Doctor Dashboard ──
├── doctor_dashboard.php        # Doctor schedule view (backend)
├── doctor_dashboard.css        # Doctor dashboard styles
├── doctor_dashboard.js         # Doctor dashboard interactions
├── get_doctor_info.php         # Fetch doctor details (AJAX)
│
├── ── Shared ──
├── db.php                      # Database connection config
├── script.js                   # Shared JavaScript
└── styles.css                  # Global stylesheet
```

---

## Academic Disclaimer

This project was developed as an academic exercise to demonstrate practical skills in database management and web development. It is not intended for production use without further security hardening.

---

*Built with HTML, CSS, PHP & MySQL*
