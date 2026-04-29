<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$type = $_GET['type'] ?? 'student';
$from = $_GET['from'] ?? date('Y-m-d');
$to = $_GET['to'] ?? date('Y-m-d');
$format = $_GET['format'] ?? 'csv';

$base = "FROM attendance a JOIN students s ON s.id=a.student_id LEFT JOIN classes c ON c.id=s.class_id LEFT JOIN buses b ON b.id=s.bus_id WHERE a.attendance_date BETWEEN ? AND ?";
if ($type === 'bus') {
    $sql = "SELECT a.attendance_date, COALESCE(b.name,'No bus') as bus, a.status, COUNT(*) as total $base GROUP BY a.attendance_date, bus, a.status ORDER BY a.attendance_date, bus";
} elseif ($type === 'class') {
    $sql = "SELECT a.attendance_date, c.name as class, a.status, COUNT(*) as total $base GROUP BY a.attendance_date, class, a.status ORDER BY a.attendance_date, class";
} else {
    $sql = "SELECT a.attendance_date, s.code, s.name, c.name as class, COALESCE(b.name,'No bus') as bus, a.status $base ORDER BY a.attendance_date, s.name";
}
$stmt = $pdo->prepare($sql);
$stmt->execute([$from, $to]);
$rows = $stmt->fetchAll();

if ($format === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report.csv"');
    $out = fopen('php://output', 'w');
    if (!empty($rows)) fputcsv($out, array_keys($rows[0]));
    foreach ($rows as $row) fputcsv($out, $row);
    fclose($out); exit;
}

if ($format === 'xlsx') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="report.xls"');
    echo "<table border='1'>";
    if (!empty($rows)) {
        echo '<tr>'; foreach(array_keys($rows[0]) as $h) echo '<th>'.h($h).'</th>'; echo '</tr>';
        foreach ($rows as $r) { echo '<tr>'; foreach($r as $v) echo '<td>'.h((string)$v).'</td>'; echo '</tr>'; }
    }
    echo "</table>"; exit;
}

function simple_pdf(array $rows): string {
    $lines = [];
    if (!empty($rows)) $lines[] = implode(' | ', array_keys($rows[0]));
    foreach ($rows as $r) $lines[] = implode(' | ', array_map(fn($v)=>(string)$v,$r));
    $y = 780;
    $content = "BT /F1 10 Tf 40 $y Td ";
    foreach ($lines as $i => $line) {
        $line = str_replace(['(',')'], ['\\(', '\\)'], substr($line,0,110));
        if ($i>0) $content .= " T* ";
        $content .= "($line) Tj";
    }
    $content .= " ET";
    $objects = [];
    $objects[] = "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj";
    $objects[] = "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj";
    $objects[] = "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj";
    $objects[] = "4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj";
    $objects[] = "5 0 obj << /Length " . strlen($content) . " >> stream\n$content\nendstream endobj";
    $pdf = "%PDF-1.4\n";
    $offsets = [0];
    foreach ($objects as $obj) { $offsets[] = strlen($pdf); $pdf .= $obj . "\n"; }
    $xref = strlen($pdf);
    $pdf .= "xref\n0 " . (count($objects)+1) . "\n0000000000 65535 f \n";
    for ($i=1;$i<=count($objects);$i++) $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
    $pdf .= "trailer << /Size " . (count($objects)+1) . " /Root 1 0 R >>\nstartxref\n$xref\n%%EOF";
    return $pdf;
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="report.pdf"');
echo simple_pdf($rows);
