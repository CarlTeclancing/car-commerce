# Fix Admin Login Issues - Direct SQL Solution

## Problem
The default admin password (admin123) is not working for login.

## Solution - Update Database Directly

### Option 1: Using phpMyAdmin (Easiest)

1. **Open phpMyAdmin**
   - Go to: http://localhost/phpmyadmin
   - Login with your MySQL credentials

2. **Navigate to the admin table**
   - Select database: `car_store`
   - Click on the `admin` table

3. **Edit the admin user**
   - Click the pencil icon next to the `admin` user row
   - Find the `password` field

4. **Update the password**
   - Clear the current password value
   - Paste this hash:
   ```
   $2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lm
   ```
   - This hash is for password: **admin123**

5. **Save and Try Login**
   - Click "Go" to save
   - Now login with:
     - Username: `admin`
     - Password: `admin123`

---

### Option 2: Using SQL Query

In phpMyAdmin, go to the SQL tab and run this query:

```sql
UPDATE admin SET password = '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lm' WHERE username = 'admin';
```

---

### Option 3: Using MySQL Command Line

Open Command Prompt and run:

```bash
mysql -u root -p car_store -e "UPDATE admin SET password = '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lm' WHERE username = 'admin';"
```

When prompted, enter your MySQL password (or just press Enter if you have no password).

---

## Login After Fix

Once you've updated the password hash, you can login with:

- **URL:** http://localhost/thegame/admin/
- **Username:** `admin`
- **Password:** `admin123`

---

## If Still Not Working

### Check if database connection is working:

1. Create a test file `test-db.php` in your project root:

```php
<?php
require_once('includes/db.php');

$sql = "SELECT * FROM admin";
$result = $conn->query($sql);

if ($result) {
    echo "✅ Database connection successful!\n";
    echo "Admin users in database:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- Username: " . $row['username'] . "\n";
        echo "  Email: " . $row['email'] . "\n";
        echo "  Password Hash: " . substr($row['password'], 0, 20) . "...\n\n";
    }
} else {
    echo "❌ Error: " . $conn->error;
}
?>
```

2. Visit: http://localhost/thegame/test-db.php

3. This will show you what's in the database

---

## Password Hash Reference

The hash: `$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lm`

- This is a bcrypt hash of the password: `admin123`
- It's the same hash every time (consistent)
- It will work on any system

---

## Alternative: Create a New Admin User

If you want to create a completely new admin user instead:

1. Go to the `admin` table in phpMyAdmin
2. Click "Insert"
3. Fill in:
   - `username`: `newadmin` (or any username)
   - `password`: Paste the hash above
   - `email`: `admin@example.com`
4. Click "Go"
5. Login with your new username and password `admin123`

---

## Verify Login Works

After updating:
1. Go to: http://localhost/thegame/admin/login.php
2. Try logging in with:
   - Username: `admin`
   - Password: `admin123`

If successful, you'll be redirected to the dashboard.

---

## Security Note

After successfully logging in:
- Go to "Manage Admins" page
- Create a new admin user with a custom password
- Delete or update the default admin account
- Do not share passwords

