<?php

require_once "config/database.php";
require_once "controllers/task_board/TaskBoardController.php";

$controller = new TaskBoardController($pdo);

// Basic query string path structural routing handling the POST form lifecycle
$action = $_GET['action'] ?? 'board';

if ($action === 'create') {
    $controller->create();
} else {
    $controller->board();
}
?>