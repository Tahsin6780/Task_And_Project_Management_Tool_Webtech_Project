<?php

header("Content-Type: application/json");

require_once "config/database.php";
require_once "controllers/TaskController.php";

$controller = new TaskController($pdo);

$controller->updateStatus();

?>