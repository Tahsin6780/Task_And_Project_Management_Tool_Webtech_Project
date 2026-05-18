<?php

class TaskModel {

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
    public function getWorkspaceMembers($project_id)
    {
        $sql = "SELECT users.id, users.name
                FROM project_members
                JOIN users ON users.id = project_members.user_id
                WHERE project_members.project_id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$project_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // CHANGE TASK STATUS + LOG
    // =========================
    public function changeStatus($task_id, $status) {

        // get task info
        $get = $this->pdo->prepare("
            SELECT title, project_id 
            FROM tasks 
            WHERE id = ?
        ");

        $get->execute([$task_id]);
        $task = $get->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            return false;
        }

        // update task status
        $sql = "UPDATE tasks SET status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$status, $task_id]);

        // log activity
        if ($result) {

            $action = "Task '{$task['title']}' moved to {$status}";

            $user_id = 1; // TODO: replace with $_SESSION['user_id']

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