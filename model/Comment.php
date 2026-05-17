<?php
// models/Comment.php
require_once __DIR__ . '/../config/db.php';

class CommentModel {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function listForTask(int $task_id): array {
        $stmt = $this->pdo->prepare(
            'SELECT c.*, u.name AS author_name
             FROM comments c
             JOIN users u ON u.id = c.user_id
             WHERE c.task_id = ?
             ORDER BY c.created_at ASC'
        );
        $stmt->execute([$task_id]);
        return $stmt->fetchAll();
    }

    public function create(int $task_id, int $user_id, string $body): array {
        $stmt = $this->pdo->prepare(
            'INSERT INTO comments (task_id, user_id, body) VALUES (?, ?, ?)'
        );
        $stmt->execute([$task_id, $user_id, $body]);
        $id = (int)$this->pdo->lastInsertId();
        $stmt = $this->pdo->prepare(
            'SELECT c.*, u.name AS author_name FROM comments c JOIN users u ON u.id = c.user_id WHERE c.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function find(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM comments WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM comments WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
