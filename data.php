<?php
session_start();
$title = 'Дела в порядке';
$is_auth = isset($_SESSION["user"]);
$user = $is_auth ? $_SESSION["user"] : null;
