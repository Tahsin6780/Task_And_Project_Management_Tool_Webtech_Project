<?php
header("Content-Type: application/json");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Requirement 3: Enforce PUT request verification
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode([
        "ok" => false,
        "message" => "Method Not Allowed. Use PUT request."
    ]);
    exit;
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../models/task_board/TaskBoardModel.php";

$model = new TaskBoardModel($pdo);

// Read raw stream payload data
$input = json_decode(file_get_contents("php://input"), true);

$task_id = $input['task_id'] ?? null;
$status  = $input['status'] ?? null;

if (!$task_id || !$status) {
    echo json_encode([
        "ok" => false,
        "message" => "Invalid parameters supplied"
    ]);
    exit;
}

// Attempting status adjustments triggering underlying model rules validation
$result = $model->changeStatus($task_id, $status);

if ($result) {
    // Explicit format layout mandatory requirement check output matching strings
    echo json_encode([
        "ok" => true,
        "new_status" => $status
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "message" => "Transition rejected or task mismatch encountered."
    ]);
}
?>