# Bus Attendance PHP + MySQL

Admin-only bus attendance system ready for shared hosting (PHP + phpMyAdmin/MySQL).

## Features
- Login page as homepage for non-authenticated users.
- Admin panel after login.
- Manage buses, classes, students, and student-to-bus relation.
- Daily attendance entry with status:
  - attending
  - absent
  - attending without bus
- Reports (date or date range):
  1. Student attendance
  2. Bus attendance
  3. Class attendance
- Export reports as CSV, XLS (Excel-readable), and PDF.

## Setup
1. Create MySQL database (example: `bus_attendance`).
2. Import `schema.sql` in phpMyAdmin.
3. Update DB credentials in `includes/config.php`.
4. Set web root to project root (or configure virtual host).
5. Open `/public/login.php`.

Default admin:
- username: `admin`
- password: `admin123`
