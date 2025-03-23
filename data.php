<?php
session_start();
$title = 'Дела в порядке';
$is_auth = isset($_SESSION["user"]);
$user = $is_auth ? $_SESSION["user"] : null;
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
