<?php
function esc($text) {
    return isset($text) ? htmlspecialchars($text) : null;
}

function task_count($tasks, $project) {
    $count = 0;
    foreach ($tasks as $task) {
        if ($task['category'] == $project) {
            $count++;
        }
    }
    return $count;
}

function less_than_day($datetime) {
    date_default_timezone_set('Europe/Moscow');
    $secs_in_hour = 3600;
    if (!isset($datetime) || !($date_ts = strtotime($datetime))) return false;
    $ts = time();
    return ($date_ts - $ts) / $secs_in_hour <= 24;
}
