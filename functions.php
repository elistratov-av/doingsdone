<?php
function esc($text) {
    return isset($text) ? htmlspecialchars($text) : null;
}

function exit_http_code($http_code) {
    http_response_code($http_code);
    exit;
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
    exit;
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

function normalize_filter($filter) {
    if (isset($filter)) {
        switch ($filter) {
            case 'today':case 'tomorrow':case 'expired':
                return $filter;
        }
    }
    return '';
}

function validate_project ($id, $allowed_list) {
    if (!in_array($id, $allowed_list)) {
        return "Указан несуществующий проект";
    }
}

function validate_project_name ($name, $existing_list) {
    if (in_array($name, $existing_list)) {
        return "Проект с таким именем уже существует";
    }
}

function validate_date($date) {
    if (!empty($date)) {
        if (is_date_valid($date)) {
            $now = date_create("now");
            $d = date_create($date);
            $diff = date_diff($d, $now);
            $interval = date_interval_format($diff, "%d");

            if ($interval < 1) {
                return "Дата должна быть больше текущей не менее чем на один день";
            };
        } else {
            return "Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»";
        }
    }
}

function validate_email ($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "E-mail должен быть корректным";
    }
}
