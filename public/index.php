<?php require_once __DIR__ . '/../includes/config.php'; require_once __DIR__ . '/../includes/auth.php'; require_once __DIR__ . '/../includes/layout.php'; require_login();
$aRows=$pdo->query("SELECT attendance_date, status, COUNT(*) total FROM attendance GROUP BY attendance_date,status ORDER BY attendance_date")->fetchAll();
$cRows=$pdo->query("SELECT complaint_date d, COUNT(*) total FROM bus_complaints GROUP BY complaint_date ORDER BY complaint_date")->fetchAll();
ui_start('Dashboard'); ?>
<div class="card"><h1>Dashboard</h1><p>Welcome, <?=h($_SESSION['admin_username'])?></p></div>
<div class="card"><h3>Attendance Status Per Day</h3><canvas id="attendanceChart"></canvas></div>
<div class="card"><h3>Complaints Per Day</h3><canvas id="complaintChart"></canvas></div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const attendance=<?=json_encode($aRows)?>; const complaints=<?=json_encode($cRows)?>;
const days=[...new Set(attendance.map(x=>x.attendance_date))]; const statuses=[...new Set(attendance.map(x=>x.status))];
const datasets=statuses.map((s,i)=>({label:s,data:days.map(d=>{const f=attendance.find(x=>x.attendance_date===d&&x.status===s);return f?Number(f.total):0;}),borderColor:['#2563eb','#16a34a','#dc2626','#9333ea'][i%4],fill:false}));
new Chart(document.getElementById('attendanceChart'),{type:'line',data:{labels:days,datasets}});
new Chart(document.getElementById('complaintChart'),{type:'line',data:{labels:complaints.map(x=>x.d),datasets:[{label:'Complaints',data:complaints.map(x=>Number(x.total)),borderColor:'#f59e0b',fill:false}]}});
</script><?php ui_end(); ?>
