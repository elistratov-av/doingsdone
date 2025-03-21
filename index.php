<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';

if (!$con) exit_error(mysqli_connect_error());

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if ($id) {
    $sql = get_query_exists_project($id);
    $res = mysqli_query($con, $sql);
    $exists_prj = mysqli_fetch_column($res);
    if (!$exists_prj) exit_http_code(404);
}

$sql = get_query_projects($user_id);
$res = mysqli_query($con, $sql);
if (!$res) exit_error(mysqli_error($con));
$projects = mysqli_fetch_all($res, MYSQLI_ASSOC);

$sql = get_query_tasks($user_id);
$res = mysqli_query($con, $sql);
if (!$res) exit_error(mysqli_error($con));
$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

$page_content = include_template('main.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'id' => $id,
    'projects' => $projects,
    'tasks' => $tasks
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'user_name' => $user_name
]);

print($layout_content);
