<?php require_once __DIR__ . '/../includes/config.php'; require_once __DIR__ . '/../includes/auth.php'; require_once __DIR__ . '/../includes/layout.php'; require_login();
if($_SERVER['REQUEST_METHOD']==='POST'){ $routes=trim($_POST['routes']??'arriving,departure'); $statuses=trim($_POST['attendance_statuses']??'attending,absent,attending without bus');
$pdo->prepare("UPDATE settings SET setting_value=? WHERE setting_key='routes'")->execute([$routes]);
$pdo->prepare("UPDATE settings SET setting_value=? WHERE setting_key='attendance_statuses'")->execute([$statuses]); }
$routes=$pdo->query("SELECT setting_value FROM settings WHERE setting_key='routes'")->fetchColumn();
$statuses=$pdo->query("SELECT setting_value FROM settings WHERE setting_key='attendance_statuses'")->fetchColumn();
ui_start('Settings'); ?>
<div class="card"><h2>Attendance Settings</h2><form method="post" class="grid"><div><label>Routes (comma separated)</label><input name="routes" value="<?=h($routes)?>"></div><div><label>Statuses (comma separated)</label><input name="attendance_statuses" value="<?=h($statuses)?>"></div><button class="btn">Save Settings</button></form></div>
<?php ui_end(); ?>
