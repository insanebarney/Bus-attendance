<?php
require_once __DIR__ . '/../includes/config.php'; require_once __DIR__ . '/../includes/auth.php'; require_once __DIR__ . '/../includes/layout.php'; require_login();
$type=$_GET['type']??'student'; $from=$_GET['from']??date('Y-m-d'); $to=$_GET['to']??date('Y-m-d');
$base="FROM attendance a JOIN students s ON s.id=a.student_id LEFT JOIN classes c ON c.id=s.class_id LEFT JOIN buses b ON b.id=s.bus_id WHERE a.attendance_date BETWEEN ? AND ?";
if($type==='bus') $sql="SELECT a.attendance_date, COALESCE(b.name,'No bus') bus, a.status, COUNT(*) total $base GROUP BY a.attendance_date,bus,a.status ORDER BY a.attendance_date,bus";
elseif($type==='class') $sql="SELECT a.attendance_date, c.name class, a.status, COUNT(*) total $base GROUP BY a.attendance_date,class,a.status ORDER BY a.attendance_date,class";
else $sql="SELECT a.attendance_date,s.code,s.name,c.name class,COALESCE(b.name,'No bus') bus,a.status $base ORDER BY a.attendance_date,s.name";
$st=$pdo->prepare($sql); $st->execute([$from,$to]); $rows=$st->fetchAll();
$ss=$pdo->prepare("SELECT status,COUNT(*) total FROM attendance WHERE attendance_date BETWEEN ? AND ? GROUP BY status");$ss->execute([$from,$to]);$sum=$ss->fetchAll();
ui_start('Reports'); ?>
<div class="card"><h2>Reports</h2><form method="get" class="grid"><select name="type"><option value="student" <?=$type==='student'?'selected':''?>>Student</option><option value="bus" <?=$type==='bus'?'selected':''?>>Bus</option><option value="class" <?=$type==='class'?'selected':''?>>Class</option></select><input type="date" name="from" value="<?=h($from)?>"><input type="date" name="to" value="<?=h($to)?>"><button class="btn">View Online</button></form><div class="actions"><a class="btn" href="/reports/export.php?type=<?=$type?>&from=<?=$from?>&to=<?=$to?>&format=pdf">Download PDF</a><a class="btn" href="/reports/export.php?type=<?=$type?>&from=<?=$from?>&to=<?=$to?>&format=csv">Download CSV</a></div></div>
<div class="card"><h3>Online Preview</h3><table><?php if($rows):?><tr><?php foreach(array_keys($rows[0]) as $h):?><th><?=h($h)?></th><?php endforeach;?></tr><?php foreach($rows as $r):?><tr><?php foreach($r as $v):?><td><?=h((string)$v)?></td><?php endforeach;?></tr><?php endforeach; else:?><tr><td>No data</td></tr><?php endif;?></table></div>
<div class="card charts"><canvas id="c1"></canvas><canvas id="c2"></canvas></div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script><script>const d=<?=json_encode($sum)?>;const l=d.map(x=>x.status),v=d.map(x=>Number(x.total));new Chart(document.getElementById('c1'),{type:'pie',data:{labels:l,datasets:[{data:v}]}});new Chart(document.getElementById('c2'),{type:'bar',data:{labels:l,datasets:[{data:v,backgroundColor:'#2563eb'}]}});</script>
<?php ui_end(); ?>
