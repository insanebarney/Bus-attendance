<?php
function ui_start(string $title): void {
    $logo='/assets/default-logo.svg';
    echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.h($title).'</title><link rel="stylesheet" href="/assets/style.css"></head><body>';
    if (is_logged_in()) {
        echo '<div class="app-shell"><aside class="sidebar"><div class="brand"><img class="logo" src="'.$logo.'"><strong>Bus System</strong></div><nav class="nav">';
        echo '<a href="/public/index.php">Dashboard</a><a href="/admin/buses.php">Buses</a><a href="/admin/classes.php">Classes</a><a href="/admin/students.php">Students</a><a href="/admin/attendance.php">Attendance</a><a href="/reports/index.php">Reports</a><a href="/public/logout.php">Logout</a>';
        echo '</nav></aside><main class="main"><div class="container">';
    } else {
        echo '<main class="main"><div class="container">';
    }
}
function ui_end(): void { echo '</div></main>'; if (is_logged_in()) echo '</div>'; echo '</body></html>'; }
