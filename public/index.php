<?php require_once __DIR__ . '/../includes/config.php'; require_once __DIR__ . '/../includes/auth.php'; require_once __DIR__ . '/../includes/layout.php'; require_login(); ui_start('Dashboard'); ?>
<div class="card"><h1>Bus Attendance Dashboard</h1><p>Welcome, <?=h($_SESSION['admin_username'])?>.</p><p>Use the side menu to manage buses, classes, students, attendance, and reports.</p></div>
<?php ui_end(); ?>
