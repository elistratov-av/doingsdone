<?php
function esc($text) {
    return isset($text) ? htmlspecialchars($text) : null;
}

function exit_http_code($http_code) {
    http_response_code($http_code);
    exit();
}

function exit_error($error) {
    global $title, $user_name;
    $page_content = include_template('error.php', ['error' => $error]);

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'title' => $title,
        'user_name' => $user_name
    ]);

    print($layout_content);
    exit();
}

function task_count($tasks, $project) {
    $count = 0;
    foreach ($tasks as $task) {
        if ($task['project'] == $project) {
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
