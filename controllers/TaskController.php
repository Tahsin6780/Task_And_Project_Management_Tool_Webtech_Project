<?php

require_once "models/TaskModel.php";

class TaskController {

    private $model;

    public function __construct($pdo) {
        $this->model = new TaskModel($pdo);
    }

    // =========================
    // TASK BOARD (MAIN VIEW)
    // =========================
    public function board() {

        // ✅ FIX: define FIRST
        $project_id = 1; // later replace with $_GET or session

        // ✅ load members for dropdown
        $members = $this->model->getWorkspaceMembers($project_id);

        // ✅ load tasks by status
        $todo = $this->model->getTasksByStatus($project_id, "todo");
        $inprogress = $this->model->getTasksByStatus($project_id, "in-progress");
        $done = $this->model->getTasksByStatus($project_id, "done");

        // send to view
        require "views/tasks/board.php";
    }

    // =========================
    // UPDATE TASK STATUS (AJAX/API)
    // =========================
    public function updateStatus($data) {

        $task_id = $data['task_id'];
        $status  = $data['status'];

        $result = $this->model->changeStatus($task_id, $status);

        return [
            "success" => $result,
            "task_id" => $task_id,
            "new_status" => $status
        ];
    }
}

?>