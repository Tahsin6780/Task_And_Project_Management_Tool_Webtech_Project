<?php

require_once "models/TaskModel.php";

class TaskController {

    private $model;

    public function __construct($pdo) {
        $this->model = new TaskModel($pdo);
    }

    public function board() {

        $project_id = 1;

        $todo = $this->model->getTasksByStatus($project_id, "todo");
        $inprogress = $this->model->getTasksByStatus($project_id, "in-progress");
        $done = $this->model->getTasksByStatus($project_id, "done");

        require "views/tasks/board.php";
    }

    public function updateStatus() {

        $data = json_decode(file_get_contents("php://input"), true);

        $task_id = $data['task_id'];
        $status = $data['status'];

        $result = $this->model->changeStatus($task_id, $status);

        echo json_encode([
            "success" => $result,
            "new_status" => $status
        ]);
    }
}
?>