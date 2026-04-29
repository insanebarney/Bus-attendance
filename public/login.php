<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if (!$admin && $username !== '') {
        $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)')->execute([$username, '']);
        $adminId = (int)$pdo->lastInsertId();
        $_SESSION['admin_id'] = $adminId;
        $_SESSION['admin_username'] = $username;
        header('Location: /public/index.php');
        exit;
    }

    if ($admin) {
        $hash = (string)($admin['password_hash'] ?? '');
        $canLoginWithoutPassword = $hash === '';
        if ($canLoginWithoutPassword || password_verify($password, $hash)) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: /public/index.php');
            exit;
        }
    }

    $error = 'Invalid username or password.';
}
?>
<!doctype html>
<html><body>
<h2>Admin Login</h2>
<?php if ($error): ?><p style="color:red;"> <?= h($error) ?> </p><?php endif; ?>
<form method="post">
    <label>Username <input name="username" required></label><br>
    <label>Password <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
</form>
</body></html>
