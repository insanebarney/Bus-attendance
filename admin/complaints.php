<?php require_once __DIR__ . '/../includes/config.php'; require_once __DIR__ . '/../includes/auth.php'; require_once __DIR__ . '/../includes/layout.php'; require_login();
$types=array_map('trim',explode(',',(string)$pdo->query("SELECT setting_value FROM settings WHERE setting_key='complaint_types'")->fetchColumn()));
$statuses=array_map('trim',explode(',',(string)$pdo->query("SELECT setting_value FROM settings WHERE setting_key='complaint_statuses'")->fetchColumn()));
$routes=array_map('trim',explode(',',(string)$pdo->query("SELECT setting_value FROM settings WHERE setting_key='routes'")->fetchColumn()));
$buses=$pdo->query('SELECT id,name FROM buses ORDER BY name')->fetchAll(); $students=$pdo->query('SELECT s.id,s.code,s.name,c.name class_name FROM students s LEFT JOIN classes c ON c.id=s.class_id ORDER BY s.name')->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $studentId=(int)($_POST['student_id']?:0)?:null; $adminName = $studentId ? null : ($_SESSION['admin_username'] ?? 'administrator');
  $className=''; if($studentId){ foreach($students as $st){ if((int)$st['id']===$studentId){ $className=$st['class_name']; break; } }}
  $pdo->prepare('INSERT INTO bus_complaints (complaint_date,complainer_name,student_id,administrator_name,relationship_text,complaint_text,complaint_main_type,complaint_type,bus_id,route,class_name,driver_name,matron_name,status,procedure_note,communication_type,responsible_name,students_involved_count) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
      ->execute([$_POST['complaint_date'],trim($_POST['complainer_name']),$studentId,$adminName,trim($_POST['relationship_text']),trim($_POST['complaint_text']),$_POST['complaint_main_type'],$_POST['complaint_type'],(int)$_POST['bus_id'],$_POST['route'],$className,trim($_POST['driver_name']),trim($_POST['matron_name']),$_POST['status'],trim($_POST['procedure_note']),trim($_POST['communication_type']),trim($_POST['responsible_name']),(int)($_POST['students_involved_count']?:0)]);
}
$rows=$pdo->query('SELECT bc.*,b.name bus_name,s.name student_name FROM bus_complaints bc JOIN buses b ON b.id=bc.bus_id LEFT JOIN students s ON s.id=bc.student_id ORDER BY bc.id DESC')->fetchAll();
ui_start('Complain System');?>
<div class="card"><h2>Bus Complain</h2><div class="actions"><a class="btn" href="/reports/complaints_export.php?format=pdf">Print / PDF</a><a class="btn" href="/reports/complaints_export.php?format=csv">Download CSV</a></div><form method="post" class="grid">
<div><label>Who is the complainer</label><input name="complainer_name" required></div>
<div><label>Date</label><input type="date" name="complaint_date" value="<?=date('Y-m-d')?>"></div>
<div><label>Complainer relationship</label><input name="relationship_text"></div>
<div><label>Complaint main type</label><select name="complaint_main_type"><option value="administrative complain">Administrative Complain</option><option value="student behaviour">Student Behaviour</option></select></div>
<div><label>Type of complain</label><select name="complaint_type"><?php foreach($types as $t):?><option><?=h($t)?></option><?php endforeach;?></select></div>
<div><label>Student (optional)</label><select name="student_id"><option value="">None</option><?php foreach($students as $s):?><option value="<?=$s['id']?>"><?=h($s['code'].' - '.$s['name'].' - '.$s['class_name'])?></option><?php endforeach;?></select></div>
<div><label>Bus</label><select name="bus_id"><?php foreach($buses as $b):?><option value="<?=$b['id']?>"><?=h($b['name'])?></option><?php endforeach;?></select></div>
<div><label>Route</label><select name="route"><?php foreach($routes as $r):?><option><?=h($r)?></option><?php endforeach;?></select></div>
<div><label>Driver</label><input name="driver_name" required></div><div><label>Matron</label><input name="matron_name" required></div>
<div><label>Status</label><select name="status"><?php foreach($statuses as $s):?><option><?=h($s)?></option><?php endforeach;?></select></div>
<div><label>Procedure</label><textarea name="procedure_note" class="bigtext" required></textarea></div>
<div><label>The complain / incident</label><textarea name="complaint_text" class="bigtext" required></textarea></div>
<div><label>Communication type</label><input name="communication_type"></div>
<div><label>Responsible</label><input name="responsible_name"></div>
<div><label>Number of students involved</label><input type="number" name="students_involved_count" min="0" value="0"></div>
<button class="btn">Save Complain</button></form></div>
<div class="card"><table><tr><th>ID</th><th>Date</th><th>Complainer</th><th>Relation</th><th>Main Type</th><th>Type</th><th>Student</th><th>Class</th><th>Bus</th><th>Route</th><th>Status</th><th>Procedure</th></tr><?php foreach($rows as $r):?><tr><td><?=$r['id']?></td><td><?=h($r['complaint_date'])?></td><td><?=h($r['complainer_name'])?></td><td><?=h($r['relationship_text']??'')?></td><td><?=h($r['complaint_main_type']??'')?></td><td><?=h($r['complaint_type'])?></td><td><?=h($r['student_name']??'')?></td><td><?=h($r['class_name']??'')?></td><td><?=h($r['bus_name'])?></td><td><?=h($r['route']??'')?></td><td><?=h($r['status'])?></td><td><?=h($r['procedure_note'])?></td></tr><?php endforeach;?></table></div>
<?php ui_end(); ?>
