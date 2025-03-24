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
$project_names = array_column($projects, "name");

$sql = get_query_tasks($user['id']);
$res = mysqli_query($con, $sql);
if (!$res) exit_error(mysqli_error($con));
$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $project = [
        'name' => '',
    ];

    $page_content = include_template('add-project.php', [
        'show_complete_tasks' => $show_complete_tasks,
        'projects' => $projects,
        'tasks' => $tasks,
        'id' => $id,
        'project' => $project,
        'errors' => null
    ]);
} else {
    $required = ["name"];
    $errors = [];

    $rules = [
        "name" => function ($value) use ($project_names) {
            return validate_project_name($value, $project_names);
        },
    ];

    $project = filter_input_array(INPUT_POST,
        [
            "name" => FILTER_DEFAULT,
        ], true);

    foreach ($project as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $errors[$field] = $rule($value);
        }
        if (in_array($field, $required) && empty($value)) {
            $errors[$field] = "Поле $field нужно заполнить";
        }
    }

    $errors = array_filter($errors);

    if (count($errors)) {
        $page_content = include_template('add-project.php', [
            'show_complete_tasks' => $show_complete_tasks,
            'projects' => $projects,
            'tasks' => $tasks,
            'id' => $id,
            'project' => $project,
            'errors' => $errors
        ]);
    } else {
        $sql = get_query_create_project($user['id']);
        $stmt = db_get_prepare_stmt($con, $sql, [
            $project['name']]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $project_id = mysqli_insert_id($con);
            header("Location: /index.php?id={$project_id}");
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
