<?php require_once __DIR__ . '/../includes/config.php'; require_once __DIR__ . '/../includes/auth.php'; require_once __DIR__ . '/../includes/layout.php'; require_login();
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(isset($_POST['save_lists'])){ $pdo->prepare("UPDATE settings SET setting_value=? WHERE setting_key='routes'")->execute([trim($_POST['routes'])]); $pdo->prepare("UPDATE settings SET setting_value=? WHERE setting_key='attendance_statuses'")->execute([trim($_POST['attendance_statuses'])]); }
 if(isset($_POST['create_user'])){ $pdo->prepare('INSERT INTO admins (username,password_hash) VALUES (?,?)')->execute([trim($_POST['new_username']), password_hash($_POST['new_password'], PASSWORD_DEFAULT)]); }
 if(isset($_POST['change_password'])){ $pdo->prepare('UPDATE admins SET password_hash=? WHERE id=?')->execute([password_hash($_POST['new_user_password'], PASSWORD_DEFAULT),(int)$_POST['admin_id']]); }
 if(isset($_POST['upload_logo']) && !empty($_FILES['logo']['tmp_name'])){ @mkdir(__DIR__.'/../uploads',0777,true); $ext=pathinfo($_FILES['logo']['name'],PATHINFO_EXTENSION)?:'png'; $name='logo_'.time().'.'.$ext; move_uploaded_file($_FILES['logo']['tmp_name'],__DIR__.'/../uploads/'.$name); $pdo->prepare("INSERT INTO settings(setting_key,setting_value) VALUES('login_logo',?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)")->execute(['/uploads/'.$name]); }
}
$routes=(string)$pdo->query("SELECT setting_value FROM settings WHERE setting_key='routes'")->fetchColumn();
$statuses=(string)$pdo->query("SELECT setting_value FROM settings WHERE setting_key='attendance_statuses'")->fetchColumn();
$admins=$pdo->query('SELECT id,username FROM admins ORDER BY username')->fetchAll();
ui_start('Settings');?>
<div class="card"><h2>Attendance Settings</h2><form method="post" class="grid"><input type="hidden" name="save_lists" value="1"><input name="routes" value="<?=h($routes)?>" placeholder="routes"><input name="attendance_statuses" value="<?=h($statuses)?>" placeholder="statuses"><button class="btn">Save</button></form></div>
<div class="card"><h2>Login Logo</h2><form method="post" enctype="multipart/form-data" class="grid"><input type="hidden" name="upload_logo" value="1"><input type="file" name="logo" accept="image/*" required><button class="btn">Upload Logo</button></form></div>
<div class="card"><h2>Create User</h2><form method="post" class="grid"><input type="hidden" name="create_user" value="1"><input name="new_username" required placeholder="username"><input type="password" name="new_password" required placeholder="password"><button class="btn">Create User</button></form></div>
<div class="card"><h2>Change User Password</h2><form method="post" class="grid"><input type="hidden" name="change_password" value="1"><select name="admin_id"><?php foreach($admins as $a):?><option value="<?=$a['id']?>"><?=h($a['username'])?></option><?php endforeach;?></select><input type="password" name="new_user_password" required placeholder="new password"><button class="btn">Change Password</button></form></div>
<?php ui_end(); ?>
