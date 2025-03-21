<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';

if (!$con) {
    $error = mysqli_connect_error();
} else {
    $sql = "SELECT name FROM projects p WHERE user_id = $user_id ORDER BY p.id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $projects = [];
        while ($prj = mysqli_fetch_column($result)) {
            $projects[] = $prj;
        }
    } else {
        $error = mysqli_error($con);
    }
}

$sql = get_query_list_tasks($user_id);
$res = mysqli_query($con, $sql);
if ($res) {
    $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($con);
}

$page_content = include_template('main.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'projects' => $projects,
    'tasks' => $tasks
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'user_name' => $user_name
]);

print($layout_content);
