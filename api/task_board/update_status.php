<?php

header("Content-Type: application/json");

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../controllers/task_board/TaskBoardController.php";

$controller = new TaskBoardController($pdo);

$data = json_decode(file_get_contents("php://input"), true);

$response = $controller->updateStatus($data);

// return controller response directly
echo json_encode($response);
?>