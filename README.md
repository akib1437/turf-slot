# TurfSlot — Turf Booking Management System (PHP + MySQL)

TurfSlot is a simple web-based turf booking management system built with core PHP, MySQL, HTML, CSS, and JavaScript. It supports two user roles: **Customer (Player)** and **Turf Manager**, allowing customers to request bookings for available time slots and managers to manage slots, approve/reject booking requests, and view daily schedules.

---

## Short Summary
- **Customers** can register/login, view slots by date, request booking (Pending), view booking status, and cancel if still Pending.
- **Managers** can create/edit/delete time slots, approve/reject booking requests, and view approved bookings (daily schedule).
- Built using a clean MVC-style structure: `models/`, `controllers/`, `views/`.

---

## Features

### Common
- Register / Login / Logout  
- Profile Update (name, phone, optional password change)

### Customer (Player)
- View available slots by date
- Request booking (team name + phone)
- My Bookings: see statuses (Pending / Approved / Rejected / Cancelled)
- Cancel booking request if status is Pending

### Turf Manager
- Create / Edit / Delete time slots by date
- View pending booking requests
- Approve / Reject booking requests
- Daily Schedule: view all Approved bookings by date

---

## Tech Stack
- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** Core PHP  
- **Database:** MySQL (XAMPP)  

---

## Project Structure
```
TurfSlot/
├─ index.php
├─ controllers/
│  ├─ authControl.php
│  ├─ profileControl.php
│  ├─ customerControl.php
│  ├─ managerControl.php
│  └─ bookingControl.php
│
├─ models/
│  ├─ dbConnect.php
│  ├─ userModel.php
│  ├─ slotModel.php
│  └─ bookingModel.php
│
└─ views/
   ├─ common_views/
   │  ├─ css/styles.css
   │  ├─ js/script.js
   │  ├─ login.php
   │  └─ register.php
   │
   ├─ customer_views/
   │  ├─ css/styles.css
   │  ├─ js/script.js
   │  ├─ home.php
   │  ├─ viewSlots.php
   │  ├─ requestBooking.php
   │  ├─ myBookings.php
   │  └─ profile.php
   │
   └─ manager_views/
      ├─ css/styles.css
      ├─ js/script.js
      ├─ home.php
      ├─ manageSlots.php
      ├─ editSlot.php
      ├─ bookingRequests.php
      ├─ dailySchedule.php
      └─ profile.php
```

---

## Installation & Setup (XAMPP + MySQL)

### 1) Put the project in XAMPP htdocs
Copy the `TurfSlot` folder to:

- **Windows:** `C:\xampp\htdocs\TurfSlot\`
- Then your root entry becomes:  
  `http://localhost/TurfSlot/`

### 2) Start services
Open **XAMPP Control Panel** and start:
- **Apache**
- **MySQL**

### 3) Create Database (phpMyAdmin)
Open:
- `http://localhost/phpmyadmin`

Create a database:
- Database name: **turfslot_db**

### 4) Create Tables (run SQL)
Select `turfslot_db` → click **SQL** → run:

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer','manager') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE slots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slot_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE slots ADD UNIQUE KEY uniq_slot (slot_date, start_time, end_time);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  slot_id INT NOT NULL,
  team_name VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  status ENUM('Pending','Approved','Rejected','Cancelled') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (slot_id) REFERENCES slots(id) ON DELETE CASCADE
);

CREATE INDEX idx_bookings_user ON bookings(user_id);
CREATE INDEX idx_bookings_slot ON bookings(slot_id);
CREATE INDEX idx_bookings_status ON bookings(status);
```

### 5) Check DB config in `models/dbConnect.php`
Make sure it matches your environment:
```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "turfslot_db";
```

---

## How to Run
Open your browser:
- `http://localhost/TurfSlot/`

### Routing behavior
- If not logged in → redirects to Login
- If logged in as Customer → Customer Home
- If logged in as Manager → Manager Home

---

## User Manual

### A) Customer (Player) Guide
1. **Register** from the Register page (choose role = customer).
2. **Login** using email & password.
3. From Customer Home:
   - **View Available Slots**
     - Select date → see slot list
     - Available slots show “Request Booking”
   - **Request Booking**
     - Enter Team Name and Phone → submit
     - Booking status becomes **Pending**
   - **My Bookings**
     - View all bookings with statuses
     - If status is **Pending**, you can **Cancel**
   - **Profile**
     - Update name/phone
     - Optional: set a new password

### B) Manager Guide
1. **Register** (choose role = manager) and **Login**.
2. From Manager Home:
   - **Manage Time Slots**
     - Select date
     - Add slot (start time, end time)
     - Edit slot or Delete slot
   - **Booking Requests**
     - View all **Pending** booking requests
     - Approve or Reject
     - Rule: A slot can only have **one Approved** booking
   - **Daily Schedule**
     - Select date
     - View all **Approved** bookings for that date
   - **Profile**
     - Update name/phone
     - Optional: set a new password

---

## Notes / Rules
- A slot is **Available** if there is no **Approved** booking for that slot.
- Customers can cancel only when booking status is **Pending**.
- Managers can approve/reject only when booking status is **Pending**.
- One slot cannot be approved for multiple customers.

---

## Authors
- Akib Tanzim  
- Ramisha Binte Helal

