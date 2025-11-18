# Car E-Commerce Website

A complete car sales platform where users can browse cars, submit contact/order requests, and admins can manage products and orders.

## Features

- **User Side:**
  - Browse available cars
  - View detailed car information
  - Submit contact/order requests for specific cars
  - View all contacts submitted

- **Admin Side:**
  - Receive and process order requests
  - Upload new car products
  - Manage car inventory
  - View customer contact information

## Setup Instructions

### 1. Database Setup

1. Open your MySQL client (phpMyAdmin or command line)
2. Create a new database:
   ```sql
   CREATE DATABASE car_store;
   USE car_store;
   ```
3. Import the `database.sql` file:
   - In phpMyAdmin: Import tab → Select `database.sql` → Execute
   - Or run: `mysql -u root -p car_store < database.sql`

### 2. Configuration

1. Update database credentials in `config/config.php`:
   ```php
   $db_host = 'localhost';
   $db_user = 'root';
   $db_password = ''; // Your MySQL password
   $db_name = 'car_store';
   ```

### 3. Directory Structure

Ensure these directories exist and are writable:
```
thegame/
├── uploads/          (for car images - must be writable)
├── config/           (database configuration)
├── includes/         (shared PHP files)
├── css/              (stylesheets)
├── js/               (JavaScript files)
└── admin/            (admin panel)
```

### 4. Running the Website

1. Make sure you have a local server running (XAMPP, WAMP, LAMP, etc.)
2. Place the project in your server root:
   - XAMPP: `C:\xampp\htdocs\thegame`
   - WAMP: `C:\wamp\www\thegame`

3. Access the website:
   - User Site: `http://localhost/thegame/`
   - Admin Panel: `http://localhost/thegame/admin/`
   - Admin Login: `http://localhost/thegame/admin/login.php`

### 5. Admin Login Credentials

- **Username:** admin
- **Password:** admin123

(Change these in the admin settings after first login)

### 6. File Uploads

The `uploads/` directory must be writable by the web server:
- Right-click `uploads/` folder → Properties → Security → Give Full Control to `IUSR` (IIS) or your web server user

## Project Structure

```
thegame/
├── index.php                 (Homepage - Car listing)
├── car-details.php           (Individual car details)
├── contacts.php              (View all contacts)
├── config/
│   └── config.php           (Database configuration)
├── includes/
│   ├── db.php               (Database connection)
│   ├── header.php           (Navigation bar)
│   └── footer.php           (Footer)
├── admin/
│   ├── index.php            (Admin dashboard)
│   ├── login.php            (Admin login)
│   ├── logout.php           (Logout handler)
│   ├── add-car.php          (Add new car)
│   ├── manage-cars.php      (Edit/Delete cars)
│   └── manage-contacts.php  (View orders/contacts)
├── css/
│   └── style.css            (Main stylesheet)
├── js/
│   └── script.js            (JavaScript functions)
├── uploads/                 (Car images folder)
└── database.sql             (Database schema)
```

## Database Tables

1. **cars** - Car inventory
2. **contacts** - Customer order/contact requests
3. **admin** - Admin user credentials

## Technologies Used

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript (ES6)
- **Server:** Apache (XAMPP/WAMP)

## Security Notes

- Passwords are hashed using `password_hash()` and `password_verify()`
- File uploads are validated
- SQL injection prevention through prepared statements
- Session-based authentication for admin
