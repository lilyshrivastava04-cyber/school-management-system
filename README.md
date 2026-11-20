# School Management System

A web app to manage students, teachers, attendance, examinations and fees.

**Tech stack:** PHP 8 (PDO) · MySQL · HTML5 · CSS3 · JavaScript

## Run locally (XAMPP)
1. Copy this folder to `C:\xampp\htdocs\school-management-system`
2. Start **Apache** and **MySQL** in XAMPP Control Panel
3. Open http://localhost/phpmyadmin → Import → choose `database.sql`
4. Open http://localhost/school-management-system/login.php
5. Login: **admin / password**

DB settings are in `config.php` (default: root, no password).

## Modules
- Login / Logout (hashed passwords, sessions)
- Dashboard (totals, averages, fees summary, class-wise table)
- Students: add, edit, delete, search, filter by class
- Teachers: add, delete
- Attendance: mark Present/Absent by date and class
- Examinations: add / delete results
- Fees: add fee, mark Paid/Pending

## Security
Prepared statements (SQL injection safe), output escaping (XSS safe), password_hash / password_verify.
