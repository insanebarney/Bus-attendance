<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/layout.php';

require_login();

$types = array_map('trim', explode(',', (string)$pdo->query("SELECT setting_value FROM settings WHERE setting_key='complaint_types'")->fetchColumn()));
$statuses = array_map('trim', explode(',', (string)$pdo->query("SELECT setting_value FROM settings WHERE setting_key='complaint_statuses'")->fetchColumn()));
$buses = $pdo->query('SELECT id,name FROM buses ORDER BY name')->fetchAll();
$students = $pdo->query('SELECT id,code,name FROM students ORDER BY name')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = (int)($_POST['student_id'] ?: 0) ?: null;
    $adminName = $studentId ? null : ($_SESSION['admin_username'] ?? 'administrator');

    $pdo->prepare('INSERT INTO bus_complaints (
        complaint_date,
        complainer_name,
        student_id,
        complaint,
        another_students_involved,
        administrator_name,
        complaint_type,
        bus_id,
        driver_name,
        matron_name,
        status,
        procedure_note
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)')->execute([
        $_POST['complaint_date'],
        trim($_POST['complainer_name']),
        $studentId,
        trim($_POST['complaint']),
        trim($_POST['another_students_involved']),
        $adminName,
        $_POST['complaint_type'],
        (int)$_POST['bus_id'],
        trim($_POST['driver_name']),
        trim($_POST['matron_name']),
        $_POST['status'],
        trim($_POST['procedure_note'])
    ]);
}

$rows = $pdo->query('SELECT bc.*, b.name bus_name, s.name student_name 
    FROM bus_complaints bc 
    JOIN buses b ON b.id = bc.bus_id 
    LEFT JOIN students s ON s.id = bc.student_id 
    ORDER BY bc.id DESC')->fetchAll();

ui_start('Complain System');
?>
<div class="card">
  <h2>Bus Complain</h2>
  <div class="actions">
    <a class="btn" href="/reports/complaints_export.php?format=pdf">Print / PDF</a>
    <a class="btn" href="/reports/complaints_export.php?format=csv">Download CSV</a>
  </div>
  <form method="post" class="grid">
    <input type="date" name="complaint_date" value="<?=date('Y-m-d')?>">
    <input name="complainer_name" placeholder="Who is the complainer?" required>
    
    <select name="student_id">
      <option value="">Student (optional)</option>
      <?php foreach($students as $s): ?>
        <option value="<?=$s['id']?>"><?=h($s['code'].' - '.$s['name'])?></option>
      <?php endforeach; ?>
    </select>
    
    <input name="complaint" placeholder="Complaint details" required>
    <input name="another_students_involved" placeholder="Other students involved (optional)">
    
    <select name="complaint_type">
      <?php foreach($types as $t): ?>
        <option><?=h($t)?></option>
      <?php endforeach; ?>
    </select>
    
    <select name="bus_id">
      <?php foreach($buses as $b): ?>
        <option value="<?=$b['id']?>"><?=h($b['name'])?></option>
      <?php endforeach; ?>
    </select>
    
    <input name="driver_name" placeholder="Driver" required>
    <input name="matron_name" placeholder="Matron" required>
    
    <select name="status">
      <?php foreach($statuses as $s): ?>
        <option><?=h($s)?></option>
      <?php endforeach; ?>
    </select>
    
    <input name="procedure_note" placeholder="Procedure" required>
    <button class="btn">Save Complain</button>
  </form>
</div>

<div class="card">
  <table>
    <tr>
      <th>ID</th>
      <th>Date</th>
      <th>Complainer</th>
      <th>Student</th>
      <th>Complaint</th>
      <th>Other Students</th>
      <th>Administrator</th>
      <th>Type</th>
      <th>Bus</th>
      <th>Driver</th>
      <th>Matron</th>
      <th>Status</th>
      <th>Procedure</th>
    </tr>
    <?php foreach($rows as $r): ?>
    <tr>
      <td><?=$r['id']?></td>
      <td><?=h($r['complaint_date'])?></td>
      <td><?=h($r['complainer_name'])?></td>
      <td><?=h($r['student_name']??'')?></td>
      <td><?=h($r['complaint'])?></td>
      <td><?=h($r['another_students_involved'])?></td>
      <td><?=h($r['administrator_name']??'')?></td>
      <td><?=h($r['complaint_type'])?></td>
      <td><?=h($r['bus_name'])?></td>
      <td><?=h($r['driver_name'])?></td>
      <td><?=h($r['matron_name'])?></td>
      <td><?=h($r['status'])?></td>
      <td><?=h($r['procedure_note'])?></td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php ui_end(); ?>
