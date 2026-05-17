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
}
?>