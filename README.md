# SportBooking - Beginner PHP/MySQL Starter Project

This is a beginner-friendly starter project for a **sport field reservation platform**.
It allows users to register, login, view sport fields, reserve a field, cancel reservations, and allows an administrator to manage fields and reservations.

## 1. Project goal

Create a simple web application for online reservation of:

- Football fields
- Basket courts
- Tennis courts

The project uses:

- PHP 8+
- MySQL
- PDO prepared statements
- Sessions for login
- HTML/CSS
- No framework, so it is easier for a beginner

## 2. Features included

### User features

- Register
- Login / logout
- View available fields
- Filter fields by sport
- Reserve a field by date and time
- View personal reservations
- Cancel a reservation

### Admin features

- View all users
- Add fields
- Delete fields
- View all reservations
- Change reservation status: pending, confirmed, cancelled

## 3. Folder structure

```text
booking-platform-starter/
│
├── config/
│   ├── db.php
│   └── helpers.php
│
├── controllers/
│   ├── bootstrap.php
│   ├── AuthController.php
│   ├── FieldController.php
│   └── ReservationController.php
│
├── models/
│   ├── User.php
│   ├── Field.php
│   └── Reservation.php
│
├── views/
│   └── layout/
│       ├── header.php
│       └── footer.php
│
├── public/
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   ├── fields.php
│   ├── reserve.php
│   ├── my_reservations.php
│   ├── admin_fields.php
│   ├── admin_reservations.php
│   └── admin_users.php
│
├── assets/
│   ├── css/style.css
│   └── js/main.js
│
└── database/
    └── booking_sports.sql
```

## 4. How to run locally with XAMPP

### Step 1 - Install XAMPP

Download and install XAMPP:

```text
https://www.apachefriends.org
```

Start:

- Apache
- MySQL

### Step 2 - Copy the project

Copy the project folder into:

```text
xampp/htdocs/
```

Example:

```text
xampp/htdocs/booking-platform-starter
```

### Step 3 - Import the database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Then:

1. Click **Import**
2. Choose the file:

```text
database/booking_sports.sql
```

3. Click **Go**

This creates the database, tables, demo admin account, and demo fields.

### Step 4 - Check database connection

Open:

```text
config/db.php
```

Default XAMPP configuration:

```php
private string $host = 'localhost';
private string $dbName = 'booking_sports';
private string $username = 'root';
private string $password = '';
```

For XAMPP, this usually works without changes.

### Step 5 - Open the website

Go to:

```text
http://localhost/booking-platform-starter/public/
```

## 5. Demo accounts

### Admin account

```text
Email: admin@sport.test
Password: admin123
```

### User account

Create a new account from the Register page.

## 6. Important beginner notes

### What is `public/`?

The `public` folder contains the pages that the visitor opens in the browser.

Example:

```text
public/index.php
public/login.php
public/register.php
```

### What is `models/`?

The `models` folder contains PHP classes that communicate with the database.

Example:

- `User.php` handles register and login
- `Field.php` handles sport fields
- `Reservation.php` handles reservations

### What is `controllers/`?

The `controllers` folder receives form submissions and decides what to do.

Example:

- Login form submits to `AuthController.php`
- Reservation form submits to `ReservationController.php`

### What is `config/db.php`?

This file contains the database connection using PDO.

## 7. Reservation rule

The same field cannot be reserved twice for the same date and overlapping time.

Example:

If someone reserves:

```text
Football Field A - 2026-07-10 - 10:00 to 11:00
```

Another user cannot reserve the same field between `10:00` and `11:00`.

## 8. Git commands for the student

Open a terminal inside the project folder.

### Initialize Git

```bash
git init
```

### Add files

```bash
git add .
```

### First commit

```bash
git commit -m "Initial commit"
```

### Push to Azure DevOps

After creating a repository in Azure DevOps, connect it:

```bash
git remote add origin YOUR_AZURE_DEVOPS_REPOSITORY_URL
```

Then push:

```bash
git push -u origin main
```

## 9. Possible next improvements

A beginner can improve this project step by step:

- Add edit field page
- Add field image
- Add availability calendar
- Add email notification
- Add better admin dashboard
- Add payment later with Stripe

## 10. Common errors

### Database connection failed

Check:

- MySQL is started in XAMPP
- Database `booking_sports` exists
- Username/password in `config/db.php` are correct

### Page not found

Check that the folder is inside:

```text
xampp/htdocs/
```

Correct URL example:

```text
http://localhost/booking-platform-starter/public/
```

### Login admin does not work

Import the SQL file again from:

```text
database/booking_sports.sql
```

## 11. Deployment note

For free hosting such as InfinityFree:

1. Create a MySQL database in the hosting panel
2. Import `database/booking_sports.sql` using phpMyAdmin
3. Upload project files to `htdocs/`
4. Update `config/db.php` with hosting database credentials

## 12. Final result

At the end, the student has a simple but complete PHP/MySQL project with:

- Login system
- Sport fields
- Reservations
- Admin management
- Database script
- Beginner documentation
