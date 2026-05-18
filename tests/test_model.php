<?php

require_once "config/database.php";
require_once "models/TaskModel.php";

$model = new TaskModel($pdo);

$data = $model->getTasksByStatus(1, "todo");

echo "<pre>";
print_r($data);
echo "</pre>";

?>