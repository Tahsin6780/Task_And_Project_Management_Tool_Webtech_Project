<?php

require_once "models/TaskModel.php";

class TaskController {

    private $model;

    public function __construct($pdo) {
        $this->model = new TaskModel($pdo);
    }

    public function board() {

        $project_id = 1; // temporary (later session integration)

        $todo = $this->model->getTasksByStatus($project_id, "todo");
        $inprogress = $this->model->getTasksByStatus($project_id, "in-progress");
        $done = $this->model->getTasksByStatus($project_id, "done");

        require "views/tasks/board.php";
    }
}
?>