<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare('INSERT INTO students (code,name,class_id,bus_id) VALUES (?,?,?,?)')
        ->execute([
            trim($_POST['code'] ?? ''),
            trim($_POST['name'] ?? ''),
            (int)($_POST['class_id'] ?? 0),
            (int)($_POST['bus_id'] ?? 0) ?: null,
        ]);
}
$classes = $pdo->query('SELECT * FROM classes ORDER BY name')->fetchAll();
$buses = $pdo->query('SELECT * FROM buses ORDER BY name')->fetchAll();
$students = $pdo->query('SELECT s.*, c.name class_name, b.name bus_name FROM students s LEFT JOIN classes c ON c.id=s.class_id LEFT JOIN buses b ON b.id=s.bus_id ORDER BY s.name')->fetchAll();
?>
<h3>Students</h3><a href="/public/index.php">Back</a>
<form method="post">
<input name="code" placeholder="Code" required>
<input name="name" placeholder="Name" required>
<select name="class_id" required><?php foreach($classes as $c): ?><option value="<?= $c['id'] ?>"><?= h($c['name']) ?></option><?php endforeach; ?></select>
<select name="bus_id"><option value="">No bus</option><?php foreach($buses as $b): ?><option value="<?= $b['id'] ?>"><?= h($b['name']) ?></option><?php endforeach; ?></select>
<button>Add</button>
</form>
<ul><?php foreach($students as $s): ?><li><?= h($s['code']) ?> - <?= h($s['name']) ?> - <?= h($s['class_name']) ?> - <?= h($s['bus_name'] ?? 'No bus') ?></li><?php endforeach; ?></ul>
