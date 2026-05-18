<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/task_board/TaskBoardModel.php";

$model = new TaskBoardModel($pdo);

$data = $model->getTasksByStatus(1, "todo");

echo "<pre>";
print_r($data);
echo "</pre>";

?>