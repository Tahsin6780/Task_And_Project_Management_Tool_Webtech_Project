<?php

header("Content-Type: application/json");

require_once "config/database.php";
require_once "controllers/TaskController.php";

$controller = new TaskController($pdo);

$data = json_decode(file_get_contents("php://input"), true);

$response = $controller->updateStatus($data);

echo json_encode($response);

?>