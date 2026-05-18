<?php

header("Content-Type: application/json");

require_once "../../config/database.php";
require_once "../../models/TaskModel.php";

$model = new TaskModel($pdo);

/*
IMPORTANT:
PUT request data is NOT in $_POST
We must read raw input stream
*/
$input = json_decode(file_get_contents("php://input"), true);

$task_id = $input['task_id'] ?? null;
$status  = $input['status'] ?? null;

if (!$task_id || !$status) {
    echo json_encode([
        "ok" => false,
        "message" => "Invalid input"
    ]);
    exit;
}

// update via model
$result = $model->changeStatus($task_id, $status);

if ($result) {
    echo json_encode([
        "ok" => true,
        "new_status" => $status
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "message" => "Update failed"
    ]);
}