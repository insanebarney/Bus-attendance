<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name !== '') {
        $pdo->prepare('INSERT INTO classes (name) VALUES (?)')->execute([$name]);
    }
}
$classes = $pdo->query('SELECT * FROM classes ORDER BY name')->fetchAll();
?>
<h3>Classes</h3><a href="/public/index.php">Back</a>
<form method="post"><input name="name" placeholder="Class name" required><button>Add</button></form>
<ul><?php foreach($classes as $c): ?><li><?= h($c['name']) ?></li><?php endforeach; ?></ul>
