<?php

class TaskModel {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getTasksByStatus($project_id, $status) {

        $sql = "SELECT * FROM tasks
                WHERE project_id = ?
                AND status = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$project_id, $status]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeStatus($task_id, $status) {

        $sql = "UPDATE tasks SET status = ? WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$status, $task_id]);
    }
}
?>