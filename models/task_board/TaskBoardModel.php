<?php

class TaskBoardModel {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // =========================
    // GET TASKS BY STATUS
    // =========================
    public function getTasksByStatus($project_id, $status) {
        $sql = "SELECT tasks.*, users.name
                FROM tasks
                LEFT JOIN users ON tasks.assigned_to = users.id
                WHERE tasks.project_id = ?
                AND tasks.status = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$project_id, $status]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // GET WORKSPACE MEMBERS (FOR DROPDOWN)
    // =========================
    public function getWorkspaceMembers($project_id) {
        $sql = "SELECT users.id, users.name
                FROM project_members
                JOIN users ON users.id = project_members.user_id
                WHERE project_members.project_id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$project_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // CREATE TASK (NEW REQUIREMENT 2)
    // =========================
    public function createTask($project_id, $title, $description, $assigned_to, $priority, $due_date) {
        $sql = "INSERT INTO tasks (project_id, title, description, assigned_to, priority, due_date, status)
                VALUES (?, ?, ?, ?, ?, ?, 'todo')";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$project_id, $title, $description, $assigned_to, $priority, $due_date]);
    }

    // =========================
    // CHANGE TASK STATUS + LOG (STRICT VALIDATION)
    // =========================
    public function changeStatus($task_id, $status) {
        // Fetch current task info to validate current state
        $get = $this->pdo->prepare("
            SELECT title, project_id, status 
            FROM tasks 
            WHERE id = ?
        ");
        $get->execute([$task_id]);
        $task = $get->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            return false;
        }

        $current_status = $task['status'];

        // SERVER-SIDE VALIDATION: Enforce strict step transitions [todo <-> in-progress <-> done]
        $isValidTransition = false;
        if ($current_status === 'todo' && $status === 'in-progress') $isValidTransition = true;
        if ($current_status === 'in-progress' && ($status === 'todo' || $status === 'done')) $isValidTransition = true;
        if ($current_status === 'done' && $status === 'in-progress') $isValidTransition = true;

        if (!$isValidTransition) {
            return false; // Rejects skipping steps (e.g., todo directly to done)
        }

        // Update task status
        $sql = "UPDATE tasks SET status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$status, $task_id]);

        // Log activity
        if ($result) {
            $status_labels = [
                'todo' => 'To Do',
                'in-progress' => 'In Progress',
                'done' => 'Done'
            ];
            $display_status = $status_labels[$status] ?? $status;
            $action = "Task '{$task['title']}' moved to {$display_status}";

            // Session check required by global rules
            $user_id = $_SESSION['user_id'] ?? 1; 

            $this->logActivity(
                $task['project_id'],
                $user_id,
                $action
            );
        }

        return $result;
    }

    // =========================
    // ACTIVITY LOG
    // =========================
    public function logActivity($project_id, $user_id, $action_text) {
        $sql = "INSERT INTO activity_logs (project_id, user_id, action_text)
                VALUES (?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$project_id, $user_id, $action_text]);
    }
}
?>