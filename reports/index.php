<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
?>
<h3>Reports</h3><a href="/public/index.php">Back</a>
<form action="export.php" method="get">
<label>Report Type
<select name="type"><option value="student">Student attendance</option><option value="bus">Bus attendance</option><option value="class">Class attendance</option></select></label>
<label>From <input type="date" name="from" required></label>
<label>To <input type="date" name="to" required></label>
<label>Format <select name="format"><option value="csv">CSV</option><option value="xlsx">XLSX</option><option value="pdf">PDF</option></select></label>
<button>Download</button>
</form>
