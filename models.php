<?php
function get_query_tasks($user_id, $id = null) {
    $sql = "SELECT t.id, t.name, t.file, t.completed, t.date_end, p.id proj_id, p.name project 
    FROM tasks t 
    JOIN projects p ON t.project_id = p.id
    WHERE t.user_id = $user_id";
    if ($id) {
        $sql .= " AND p.id = $id";
    }
    return $sql;
}

function get_query_create_task($user_id) {
    return "INSERT INTO tasks (name, file, date_end, project_id, user_id)
    VALUES(?, ?, ?, ?, $user_id)";
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