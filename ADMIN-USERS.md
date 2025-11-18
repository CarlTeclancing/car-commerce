# How to Create a New Admin User

## Method 1: Through Admin Dashboard (Recommended)

### Steps:

1. **Login to Admin Panel**
   - Go to: `http://localhost/thegame/admin/`
   - Username: `admin`
   - Password: `admin123`

2. **Navigate to Manage Admins**
   - Click "Manage Admins" in the navigation menu
   - Or click the "Manage Admin Users" quick action on the dashboard

3. **Fill the Form**
   - **Username:** Enter a unique username (minimum 3 characters)
   - **Email:** Enter a unique email address
   - **Password:** Enter a strong password (minimum 6 characters)
   - **Confirm Password:** Re-enter the password to confirm

4. **Create Admin User**
   - Click "Create Admin User" button
   - You'll see a success message

5. **New Admin Can Login**
   - The new admin user can now login with their username and password

---

## Method 2: Direct Database Insert (MySQL)

If you need to create an admin user directly through phpMyAdmin:

### SQL Query:

```sql
INSERT INTO admin (username, password, email) VALUES 
('newadmin', '$2y$10$N0W52cAA.JJ0SJ0VqJ7CXe6QU7e.HB0DL8l8J7y8KWh5x8Q5m7KXi', 'newadmin@carstore.com');
```

**Note:** The hashed password above is for password `admin123`. To create a different password:

1. Go to: https://www.php.net/manual/en/function.password-hash.php (or use an online PHP hash generator)
2. Hash your desired password with `password_hash()` function
3. Replace the hash in the SQL query
4. Execute the query

### Example PHP Script (create-admin.php):

Create a file `create-admin.php` in your root directory temporarily:

```php
<?php
require_once('includes/db.php');

$username = 'newadmin';
$password = 'mypassword123';
$email = 'newadmin@example.com';

$hashed = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (username, password, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $hashed, $email);

if ($stmt->execute()) {
    echo "Admin user created successfully!";
} else {
    echo "Error: " . $conn->error;
}
?>
```

**After running it, DELETE this file immediately for security!**

---

## Admin User Requirements

- **Username:** 
  - Minimum 3 characters
  - Must be unique
  - Cannot contain spaces

- **Password:**
  - Minimum 6 characters
  - Should be strong (mix of letters, numbers, special characters)
  - Must be confirmed (entered twice)

- **Email:**
  - Must be a valid email format
  - Must be unique
  - Used for account recovery (optional)

---

## View All Admin Users

On the "Manage Admins" page, you'll see a table showing:
- Username
- Email
- Date created

---

## Security Best Practices

✅ **DO:**
- Create strong passwords (12+ characters with mix of types)
- Use unique usernames and emails
- Change default password immediately
- Regularly review admin users
- Delete unused admin accounts

❌ **DON'T:**
- Share admin passwords
- Use weak passwords like "123456" or "password"
- Reuse passwords across accounts
- Leave default credentials unchanged
- Create unnecessary admin accounts

---

## Troubleshooting

### "Username already exists"
- The username you entered is already in use
- Choose a different username

### "Email already exists"
- The email address is already associated with another admin account
- Use a different email address

### "Passwords do not match"
- The password and confirm password fields don't match
- Make sure you entered them correctly

### "Invalid email address"
- The email format is invalid
- Use a proper email format (example@domain.com)

---

## Access Control

Only **logged-in admin users** can:
- Create new admin users
- View the list of admin users
- Access the manage admins page

Users cannot create admin accounts from the public site.

---

## Example Admin User

To test with a new admin account:

**Username:** manager  
**Password:** CarStore2025!  
**Email:** manager@carstore.com  

(These are examples - create with your own credentials)

---

For more information, see the main README.md file.
