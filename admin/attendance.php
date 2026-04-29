<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['attendance_date'];
    foreach ($_POST['status'] as $student_id => $status) {
        $pdo->prepare('REPLACE INTO attendance (attendance_date, student_id, status) VALUES (?,?,?)')
            ->execute([$date, (int)$student_id, $status]);
    }
}
$students = $pdo->query('SELECT s.id, s.code, s.name, c.name class_name, b.name bus_name FROM students s LEFT JOIN classes c ON c.id=s.class_id LEFT JOIN buses b ON b.id=s.bus_id ORDER BY c.name, s.name')->fetchAll();
?>
<h3>Record Attendance</h3><a href="/public/index.php">Back</a>
<form method="post">
<input type="date" name="attendance_date" required value="<?= date('Y-m-d') ?>">
<table border="1"><tr><th>Code</th><th>Name</th><th>Class</th><th>Bus</th><th>Status</th></tr>
<?php foreach($students as $s): ?>
<tr>
<td><?= h($s['code']) ?></td><td><?= h($s['name']) ?></td><td><?= h($s['class_name']) ?></td><td><?= h($s['bus_name'] ?? 'No bus') ?></td>
<td><select name="status[<?= $s['id'] ?>]"><option>attending</option><option>absent</option><option>attending without bus</option></select></td>
</tr>
<?php endforeach; ?></table>
<button>Save Attendance</button></form>
