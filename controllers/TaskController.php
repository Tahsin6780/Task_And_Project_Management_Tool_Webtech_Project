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




public function updateStatus($data) {

    $task_id = $data['task_id'];
    $status = $data['status'];

    $result = $this->model->changeStatus($task_id, $status);

    return [
        "success" => $result,
        "task_id" => $task_id,
        "new_status" => $status
    ];
}






}
?>