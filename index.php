<?php

require_once "config/database.php";
require_once "controllers/TaskController.php";

$controller = new TaskController($pdo);
$controller->board();

?>