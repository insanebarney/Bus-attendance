<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
?>
<!doctype html>
<html><body>
<h2>Bus Attendance Admin Panel</h2>
<p>Welcome, <?= h($_SESSION['admin_username']) ?></p>
<ul>
    <li><a href="/admin/buses.php">Manage Buses</a></li>
    <li><a href="/admin/classes.php">Manage Classes</a></li>
    <li><a href="/admin/students.php">Manage Students</a></li>
    <li><a href="/admin/attendance.php">Record Attendance</a></li>
    <li><a href="/reports/index.php">Reports</a></li>
    <li><a href="/public/logout.php">Logout</a></li>
</ul>
</body></html>
