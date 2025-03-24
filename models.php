<?php
function get_query_tasks($user_id, $filter = null, $search = null, $con = null) {
    $sql = "SELECT t.id, t.name, t.file, t.completed, t.date_end, p.id proj_id, p.name project 
    FROM tasks t 
    JOIN projects p ON t.project_id = p.id
    WHERE t.user_id = $user_id";
    if (isset($filter)) {
        switch ($filter) {
            case "today":
                $sql .= " AND t.date_end = CURDATE()";
                break;
            case "tomorrow":
                $sql .= " AND t.date_end = ADDDATE(CURDATE(), 1)";
                break;
            case "expired":
                $sql .= " AND !t.completed AND t.date_end < CURDATE()";
                break;
        }
    }
    if ($search) {
        $search = mysqli_real_escape_string($con, $search);
        $sql .= " AND MATCH (t.name) AGAINST ('$search')";
    }
    return $sql;
}

function get_query_task($task_id) {
    return "SELECT t.id, t.name, t.file, t.completed, t.date_end FROM tasks t WHERE t.id = $task_id";
}

function get_query_create_task($user_id) {
    return "INSERT INTO tasks (name, file, date_end, project_id, user_id)
    VALUES(?, ?, ?, ?, $user_id)";
}

function get_query_setcompleted_task($task_id, $completed) {
    return "UPDATE tasks SET completed = $completed WHERE id = $task_id";
}

function get_query_projects($user_id) {
    return "SELECT id, name FROM projects p WHERE user_id = $user_id ORDER BY p.id";
}

function get_query_exists_project($id) {
    return "SELECT
    CASE
        WHEN EXISTS (SELECT 1 FROM projects WHERE id = $id)
            THEN 1
        ELSE 0
        END AS r";
}

function get_query_create_project($user_id) {
    return "INSERT INTO projects (name, user_id)
    VALUES(?, $user_id)";
}

function get_query_users() {
    return "SELECT email, name FROM users";
}

function get_query_create_user() {
    return "INSERT INTO users (email, password, name) VALUES (?, ?, ?)";
}

function get_query_login($email) {
    return "SELECT id, email, name, password FROM users WHERE email = '$email'";
}
