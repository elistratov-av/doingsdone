<?php
global $con, $title, $is_auth, $user, $show_complete_tasks;
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

$sql = get_query_projects($user['id']);
$res = mysqli_query($con, $sql);
if (!$res) exit_error(mysqli_error($con));
$projects = mysqli_fetch_all($res, MYSQLI_ASSOC);
$projects_id = array_column($projects, "id");

$sql = get_query_tasks($user['id']);
$res = mysqli_query($con, $sql);
if (!$res) exit_error(mysqli_error($con));
$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $task = [
        'name' => '',
        'proj_id' => '',
        'date_end' => '',
    ];

    $page_content = include_template('add-task.php', [
        'show_complete_tasks' => $show_complete_tasks,
        'projects' => $projects,
        'tasks' => $tasks,
        'id' => $id,
        'task' => $task,
        'errors' => null
    ]);
} else {
    $required = ["name", "proj_id"];
    $errors = [];

    $rules = [
        "proj_id" => function ($value) use ($projects_id) {
            return validate_project($value, $projects_id);
        },
        "date_end" => function ($value) {
            return validate_date($value);
        }
    ];

    $task = filter_input_array(INPUT_POST,
        [
            "name" => FILTER_DEFAULT,
            "proj_id" => FILTER_DEFAULT,
            "date_end" => FILTER_DEFAULT,
        ], true);

    foreach ($task as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $errors[$field] = $rule($value);
        }
        if (in_array($field, $required) && empty($value)) {
            $errors[$field] = "Поле $field нужно заполнить";
        }
    }

    $errors = array_filter($errors);

    if (!empty($_FILES['file']['name'])) {
        $tmp_name = $_FILES['file']['tmp_name'];
        $path = $_FILES['file']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if ($ext) $ext = '.' . $ext;

        $filename = uniqid() . $ext;
        $task['path'] = 'uploads/'. $filename;
        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/'. $filename);
    }

    if (count($errors)) {
        $page_content = include_template('add-task.php', [
            'show_complete_tasks' => $show_complete_tasks,
            'projects' => $projects,
            'tasks' => $tasks,
            'id' => $id,
            'task' => $task,
            'errors' => $errors
        ]);
    } else {
        $sql = get_query_create_task($user['id']);
        $stmt = db_get_prepare_stmt($con, $sql, [
            $task['name'], $task['path'] ?? '', $task['date_end'] ?: null, $task['proj_id']]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $task_id = mysqli_insert_id($con);
            header("Location: /index.php");
            exit;
        } else {
            exit_error(mysqli_error($con));
        }
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user' => $user
]);

print($layout_content);
