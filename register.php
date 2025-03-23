<?php
global $con, $title, $is_auth, $user, $show_complete_tasks;
require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';

if (!$con) exit_error(mysqli_connect_error());

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $user_info = [
        'email' => '',
        'password' => '',
        'name' => '',
    ];

    $page_content = include_template('register.php', [
        'user' => $user_info,
        'errors' => null
    ]);
} else {
    $required = ["email", "password", "name"];
    $errors = [];

    $rules = [
        "email" => function ($value) {
            return validate_email($value);
        }
    ];

    $user_info = filter_input_array(INPUT_POST,
        [
            "email" => FILTER_DEFAULT,
            "password" => FILTER_DEFAULT,
            "name" => FILTER_DEFAULT,
        ], true);

    foreach ($user_info as $field => $value) {
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
        $page_content = include_template('register.php', [
            'user' => $user_info,
            'errors' => $errors
        ]);
    } else {
        $sql = get_query_users();
        $res = mysqli_query($con, $sql);
        if (!$res) exit_error(mysqli_error($con));
        $users_data = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $emails = array_column($users_data, "email");
        $names = array_column($users_data, "name");
        if (in_array($user_info["email"], $emails)) {
            $errors["email"] = "Пользователь с таким е-mail уже зарегистрирован";
        }
        if (in_array($user_info["name"], $names)) {
            $errors["name"] = "Пользователь с таким именем уже зарегистрирован";
        }

        if (count($errors)) {
            $page_content = include_template('auth.php', [
                'user' => $user_info,
                'errors' => $errors
            ]);
        } else {
            $sql = get_query_create_user();
            $user_info["password"] = password_hash($user_info["password"], PASSWORD_DEFAULT);
            $stmt = db_get_prepare_stmt($con, $sql, [$user_info['email'], $user_info['password'], $user_info['name']]);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                header("Location: /index.php");
                exit;
            } else {
                exit_error(mysqli_error($con));
            }
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
