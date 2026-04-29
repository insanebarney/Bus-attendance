<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name !== '') {
        $pdo->prepare('INSERT INTO buses (name) VALUES (?)')->execute([$name]);
    }
}
$buses = $pdo->query('SELECT * FROM buses ORDER BY name')->fetchAll();
?>
<h3>Buses</h3><a href="/public/index.php">Back</a>
<form method="post"><input name="name" placeholder="Bus name" required><button>Add</button></form>
<ul><?php foreach($buses as $b): ?><li><?= h($b['name']) ?></li><?php endforeach; ?></ul>
