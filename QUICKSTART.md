# Car E-Commerce Website - Quick Start Guide

## Complete Installation & Setup

### Step 1: Create the Database

Open **phpMyAdmin** (usually at `http://localhost/phpmyadmin`) or use MySQL command line:

```sql
CREATE DATABASE car_store;
USE car_store;
```

Then import the `database.sql` file to create all tables:
- Open phpMyAdmin → Select `car_store` database → Import tab
- Choose `database.sql` file and click Execute

**Or** from command line:
```bash
mysql -u root -p car_store < database.sql
```

### Step 2: Configure Database Connection

Edit `config/config.php` and update these lines with your database credentials:

```php
$db_host = 'localhost';      // Your database host
$db_user = 'root';           // Your MySQL username
$db_password = '';           // Your MySQL password (if any)
$db_name = 'car_store';      // Database name
```

### Step 3: Create Uploads Directory

Make sure the `uploads/` folder exists and is writable:
- Right-click `uploads/` folder → Properties → Security
- Ensure the web server user (IUSR for IIS, www-data for Apache) has Full Control

### Step 4: Start Your Local Server

**Option A - XAMPP:**
1. Open XAMPP Control Panel
2. Start Apache and MySQL services
3. Copy your project to: `C:\xampp\htdocs\thegame`

**Option B - WAMP:**
1. Open WAMP Control Panel
2. Start all services
3. Copy your project to: `C:\wamp\www\thegame`

**Option C - Built-in PHP Server:**
```bash
cd c:\Users\HP-360\Desktop\crestlancing\thegame
php -S localhost:8000
```

### Step 5: Access the Website

- **User Site:** http://localhost/thegame/
- **Admin Panel:** http://localhost/thegame/admin/
- **Admin Login:**
  - Username: `admin`
  - Password: `admin123`

---

## File Structure

```
thegame/
├── index.php                    # Homepage - Browse all cars
├── car-details.php              # Individual car details & contact form
├── contacts.php                 # View all contact requests
├── database.sql                 # Database schema & sample data
├── README.md                    # Full project documentation
├── QUICKSTART.md               # This file
│
├── config/
│   └── config.php              # Database configuration
│
├── includes/
│   ├── db.php                  # Database connection
│   ├── header.php              # Navigation & header
│   └── footer.php              # Footer
│
├── css/
│   └── style.css               # All styling (responsive design)
│
├── js/
│   └── script.js               # Form validation & utilities
│
├── uploads/                    # Car images folder (must be writable)
│
└── admin/
    ├── login.php               # Admin login page
    ├── logout.php              # Logout handler
    ├── index.php               # Admin dashboard
    ├── add-car.php             # Add new car form
    ├── edit-car.php            # Edit car form
    ├── manage-cars.php         # List all cars
    ├── delete-car.php          # Delete car (script)
    ├── manage-contacts.php     # List contact requests
    ├── view-contact.php        # View contact details
    └── delete-contact.php      # Delete contact (script)
```

---

## User Features

### 1. Browse Cars
- Visit homepage to see all available cars
- View car image, brand, model, year, fuel type, transmission, and price

### 2. View Car Details
- Click "View Details" on any car
- See complete specifications including mileage, color, and description

### 3. Submit Contact Request
- On car details page, fill the contact form with:
  - Your name
  - Email address
  - Phone number
  - Message
- Submit to send a contact/order request to admin

### 4. View All Contacts
- Go to "Contacts" page to see all submitted contact requests
- View customer information and inquiry details

---

## Admin Features

### 1. Dashboard
- See statistics: Total Cars, Total Contacts, Pending Requests
- View 5 most recent contact requests
- Quick access to manage cars and contacts

### 2. Add New Car
- Fill car details: name, brand, model, year, price
- Add optional specs: mileage, fuel type, transmission, color
- Add description and upload car image (JPG, PNG, GIF - Max 5MB)
- Car automatically appears on homepage

### 3. Manage Cars
- View all cars in a table
- Edit existing car information
- Delete cars (image automatically removed)

### 4. Manage Contact Requests
- View all customer inquiries
- Change status: Pending → Contacted → Completed → Cancelled
- View full customer details and message

### 5. View Contact Details
- See complete customer information
- Read full message
- Contact customer via email or phone
- Delete request if needed

---

## Database Schema

### cars table
- `id` - Auto-increment car ID
- `name` - Car name/title
- `brand` - Car brand (Honda, Toyota, etc.)
- `model` - Car model
- `year` - Year manufactured
- `price` - Price in dollars
- `mileage` - Kilometers driven
- `fuel_type` - Petrol, Diesel, Electric, Hybrid
- `transmission` - Manual or Automatic
- `color` - Car color
- `description` - Full description
- `image` - Image filename
- `created_at` / `updated_at` - Timestamps

### contacts table
- `id` - Auto-increment contact ID
- `car_id` - Foreign key to cars table
- `customer_name` - Customer name
- `customer_email` - Customer email
- `customer_phone` - Customer phone
- `message` - Inquiry message
- `status` - pending, contacted, completed, cancelled
- `created_at` / `updated_at` - Timestamps

### admin table
- `id` - Admin ID
- `username` - Login username
- `password` - Hashed password
- `email` - Admin email
- `created_at` - Account creation date

---

## Default Admin Credentials

**Username:** admin  
**Password:** admin123

⚠️ **IMPORTANT:** Change these credentials after first login!

---

## Security Features

✅ **Implemented:**
- Password hashing using PHP's `password_hash()` function
- Prepared statements to prevent SQL injection
- Session-based authentication for admin
- File type validation for uploads
- HTML escaping to prevent XSS attacks
- File size limits (max 5MB)

---

## Troubleshooting

### "Connection failed" error
- Check database credentials in `config/config.php`
- Ensure MySQL service is running
- Verify database name is `car_store`

### Cannot upload car images
- Check `uploads/` folder exists
- Ensure `uploads/` folder is writable
- Verify image file is JPG, PNG, or GIF
- Check file size is under 5MB

### Admin login fails
- Verify username is exactly: `admin`
- Verify password is exactly: `admin123`
- Check database has admin table with default user

### CSS/JS not loading
- Clear browser cache (Ctrl+Shift+Del)
- Verify web server is serving static files
- Check file paths in header.php

---

## Features Summary

✅ User Registration & Browsing  
✅ Car Search & Filtering (by viewing details)  
✅ Contact Form for Each Car  
✅ Admin Authentication  
✅ Car Management (CRUD)  
✅ Contact Request Management  
✅ Image Upload for Cars  
✅ Responsive Design  
✅ Form Validation  
✅ Status Tracking  

---

## Next Steps

1. Import `database.sql` to create tables
2. Configure `config/config.php` with your credentials
3. Start your local server
4. Login to admin panel with default credentials
5. Add some cars to the inventory
6. Test the user flow

Enjoy your Car E-Commerce Website! 🚗
