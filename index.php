<?php
global $con, $title, $is_auth, $user, $show_complete_tasks;
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';

if (!$con) exit_error(mysqli_connect_error());

if (!$is_auth) {
    $layout_content = include_template('layout.php', [
        'content' => include_template('guest.php'),
        'title' => $title,
        'is_auth' => $is_auth,
        'user' => $user
    ]);

    print($layout_content);
    exit;
}

$show_complete_tasks = filter_input(INPUT_GET, 'show_completed', FILTER_SANITIZE_NUMBER_INT);

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if ($id) {
    $sql = get_query_exists_project($id);
    $res = mysqli_query($con, $sql);
    $exists_prj = mysqli_fetch_column($res);
    if (!$exists_prj) exit_http_code(404);
}

$sql = get_query_projects($user['id']);
$res = mysqli_query($con, $sql);
if (!$res) exit_error(mysqli_error($con));
$projects = mysqli_fetch_all($res, MYSQLI_ASSOC);

$task_id = filter_input(INPUT_GET, 'task_id', FILTER_SANITIZE_NUMBER_INT);
$check = filter_input(INPUT_GET, 'check', FILTER_SANITIZE_NUMBER_INT);
if (isset($task_id)) {
    $sql = get_query_task($task_id);
    $res = mysqli_query($con, $sql);
    if (!$res) exit_error(mysqli_error($con));
    $task = mysqli_fetch_assoc($res);
    if ($task) {
        $completed = +$task['completed'];
        $sql = get_query_setcompleted_task($task_id, $completed ? 0 : 1);
        $res = mysqli_query($con, $sql);
        if (!$res) exit_error(mysqli_error($con));

        $redirect_url = "/index.php";
        if ($id) $redirect_url .= "?id=$id";
        header("Location: $redirect_url");
        exit;
    }
}

$search = filter_input(INPUT_GET, 'search');
if (isset($search)) $search = trim($search);

$filter = normalize_filter(filter_input(INPUT_GET, 'filter'));

$sql = get_query_tasks($user['id'], $filter, $search, $con);
$res = mysqli_query($con, $sql);
if (!$res) exit_error(mysqli_error($con));
$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

$page_content = include_template('main.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'id' => $id,
    'search' => $search,
    'filter' => $filter,
    'projects' => $projects,
    'tasks' => $tasks
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user' => $user
]);

print($layout_content);
