<?php

class Comment {
	private $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function create($task_id, $user_id, $body) {
		$stmt = $this->pdo->prepare("
			INSERT INTO comments (task_id, user_id, body)
			VALUES (?, ?, ?)
		");
		$stmt->execute([$task_id, $user_id, $body]);
		return (int) $this->pdo->lastInsertId();
	}

	public function findById($id) {
		$stmt = $this->pdo->prepare("
			SELECT c.*, u.name
			FROM comments c
			JOIN users u ON u.id = c.user_id
			WHERE c.id = ?
			LIMIT 1
		");
		$stmt->execute([$id]);
		return $stmt->fetch();
	}

	public function listByTask($task_id) {
		$stmt = $this->pdo->prepare("
			SELECT c.*, u.name
			FROM comments c
			JOIN users u ON u.id = c.user_id
			WHERE c.task_id = ?
			ORDER BY c.created_at ASC
		");
		$stmt->execute([$task_id]);
		return $stmt->fetchAll();
	}

	public function deleteOwn($comment_id, $user_id) {
		$stmt = $this->pdo->prepare("
			DELETE FROM comments
			WHERE id = ? AND user_id = ?
		");
		$stmt->execute([$comment_id, $user_id]);
		return $stmt->rowCount() > 0;
	}
}
