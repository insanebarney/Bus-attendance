<?php 
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/layout.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    $s = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username=? LIMIT 1');
    $s->execute([$u]);
    $a = $s->fetch();
    if ($a && password_verify($p, $a['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $a['id'];
        $_SESSION['admin_username'] = $a['username'];
        header('Location: /public/index.php');
        exit;
    }
    $error = 'Invalid username or password.';
}
ui_start('Login');
?>
<div class="card"><div class="brand"><img class="logo" src="<?=h(app_logo())?>"><h2>School Login</h2></div><?php if($error):?><p><?=h($error)?></p><?php endif;?><form method="post" class="grid"><div><label>Username</label><input name="username" required></div><div><label>Password</label><input type="password" name="password" required></div><button class="btn">Login</button></form></div>
<?php ui_end(); ?>
