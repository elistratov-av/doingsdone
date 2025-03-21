<?php
function get_query_list_tasks($user_id) {
    return "SELECT t.id, t.title, t.file, t.completed, t.date_end, p.name category 
FROM tasks t 
JOIN projects p on t.project_id = p.id
WHERE t.user_id = $user_id";
}