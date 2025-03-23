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
    ];

    $page_content = include_template('auth.php', [
        'user' => $user_info,
        'errors' => null
    ]);
} else {
    $required = ["email", "password"];
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
        $page_content = include_template('auth.php', [
            'user' => $user_info,
            'errors' => $errors
        ]);
    } else {
        $sql = get_query_login($user_info["email"]);
        $res = mysqli_query($con, $sql);
        if (!$res) exit_error(mysqli_error($con));
        $row = mysqli_num_rows($res);
        $users_data = $row == 1 ? mysqli_fetch_assoc($res) : null;
        if ($users_data) {
            if (password_verify($user_info["password"], $users_data["password"])) {
                $_SESSION['user'] = $users_data;

                header("Location: /index.php");
                exit;
            } else {
                $errors["password"] = "Вы ввели неверный пароль";
            }
        } else {
            $errors["email"] = "Пользователь с таким е-mail не зарегестрирован";
        }

        $page_content = include_template('auth.php', [
            'user' => $user_info,
            'errors' => $errors
        ]);
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user' => $user
]);

print($layout_content);
