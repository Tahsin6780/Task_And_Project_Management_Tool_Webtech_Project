<?php

require_once __DIR__ . "/../../models/task_board/TaskBoardModel.php";

class TaskBoardController {

    private $model;

    public function __construct($pdo) {
        // Safe context initialisation handling global sessions
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new TaskBoardModel($pdo);
    }

    // =========================
    // TASK BOARD (MAIN VIEW)
    // =========================
    public function board() {
        $project_id = $_SESSION['project_id'] ?? 1; 

        // Load members for creation form dropdown
        $members = $this->model->getWorkspaceMembers($project_id);

        // Load columns separately
        $todo = $this->model->getTasksByStatus($project_id, "todo");
        $inprogress = $this->model->getTasksByStatus($project_id, "in-progress");
        $done = $this->model->getTasksByStatus($project_id, "done");

        // Send workspace data mapping onto the views template
        require __DIR__ . "/../../views/task_board/board.php";
    }

    // =========================
    // POST /tasks/create ROUTE HANDLER
    // =========================
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /index.php");
            exit;
        }

        $project_id = $_SESSION['project_id'] ?? 1;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $assigned_to = $_POST['assigned_to'] ?? '';
        $priority = $_POST['priority'] ?? 'low';
        $due_date = $_POST['due_date'] ?? '';

        // Server-Side Validation Layer
        $errors = [];
        if (empty($title)) $errors[] = "Title is required.";
        if (empty($assigned_to)) $errors[] = "Please assign a team member.";
        if (empty($due_date)) $errors[] = "Due date is required.";
        if (!in_array($priority, ['low', 'medium', 'high'])) $priority = 'low';

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            header("Location: /index.php");
            exit;
        }

        $result = $this->model->createTask($project_id, $title, $description, $assigned_to, $priority, $due_date);
        
        if ($result) {
            unset($_SESSION['form_errors']);
        }
        
        header("Location: /index.php");
        exit;
    }

    // =========================
    // UPDATE TASK STATUS (AJAX/API)
    // =========================
    public function updateStatus($data) {
        $task_id = $data['task_id'] ?? null;
        $status  = $data['status'] ?? null;

        if (!$task_id || !$status) {
            return ["success" => false, "message" => "Missing arguments"];
        }

        $result = $this->model->changeStatus($task_id, $status);

        return [
            "success" => $result,
            "task_id" => $task_id,
            "new_status" => $status
        ];
    }
}
?>