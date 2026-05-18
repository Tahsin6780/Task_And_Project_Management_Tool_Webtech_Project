<?php

header("Content-Type: application/json");

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../controllers/task_board/TaskBoardController.php";

// INIT CONTROLLER
$controller = new TaskBoardController($pdo);

// READ JSON INPUT
$data = json_decode(file_get_contents("php://input"), true);

// VALIDATION
if (!isset($data['task_id']) || !isset($data['status'])) {
    echo json_encode([
        "ok" => false,
        "message" => "task_id and status required"
    ]);
    exit;
}

// CALL CONTROLLER
$response = $controller->updateStatus($data);

// RETURN RESPONSE SAFELY
echo json_encode([
    "ok" => $response['success'],
    "new_status" => $response['new_status']
]);