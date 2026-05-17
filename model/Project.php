<?php

class Project {
	private $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function getActiveProjects($workspace_id) {
		$stmt = $this->pdo->prepare("
			SELECT
				p.*,
				(
					SELECT COUNT(*)
					FROM tasks t
					WHERE t.project_id = p.id
				) AS total_tasks,
				(
					SELECT COUNT(*)
					FROM tasks t
					WHERE t.project_id = p.id AND t.status = 'done'
				) AS done_tasks,
				CASE
					WHEN (
						SELECT COUNT(*)
						FROM tasks t
						WHERE t.project_id = p.id
					) = 0 THEN NULL
					ELSE ROUND(
						(
							(
								SELECT COUNT(*)
								FROM tasks t
								WHERE t.project_id = p.id AND t.status = 'done'
							) /
							(
								SELECT COUNT(*)
								FROM tasks t
								WHERE t.project_id = p.id
							)
						) * 100
					)
				END AS progress_percent
			FROM projects p
			WHERE p.workspace_id = ? AND p.is_archived = 0
			ORDER BY p.created_at DESC
		");

		$stmt->execute([$workspace_id]);
		return $stmt->fetchAll();
	}

	public function getArchivedProjects($workspace_id) {
		$stmt = $this->pdo->prepare("
			SELECT *
			FROM projects
			WHERE workspace_id = ? AND is_archived = 1
			ORDER BY created_at DESC
		");

		$stmt->execute([$workspace_id]);
		return $stmt->fetchAll();
	}

	public function getWorkspaceMembers($workspace_id) {
		$stmt = $this->pdo->prepare("
			SELECT u.id, u.name, u.email
			FROM workspace_members wm
			JOIN users u ON u.id = wm.user_id
			WHERE wm.workspace_id = ?
			ORDER BY u.name ASC
		");

		$stmt->execute([$workspace_id]);
		return $stmt->fetchAll();
	}

	public function createProject($data, $member_ids) {
		try {
			$this->pdo->beginTransaction();

			$stmt = $this->pdo->prepare("
				INSERT INTO projects (workspace_id, name, description, deadline, color_label)
				VALUES (?, ?, ?, ?, ?)
			");

			$stmt->execute([
				$data['workspace_id'],
				$data['name'],
				$data['description'],
				$data['deadline'],
				$data['color_label']
			]);

			$project_id = $this->pdo->lastInsertId();

			$insert = $this->pdo->prepare("
				INSERT INTO project_members (project_id, user_id)
				VALUES (?, ?)
			");

			foreach ($member_ids as $user_id) {
				$insert->execute([$project_id, $user_id]);
			}

			$this->pdo->commit();

			return $project_id;
		} catch (Exception $e) {
			$this->pdo->rollBack();
			throw $e;
		}
	}

	public function findProject($project_id, $workspace_id) {
		$stmt = $this->pdo->prepare("
			SELECT *
			FROM projects
			WHERE id = ? AND workspace_id = ?
			LIMIT 1
		");

		$stmt->execute([$project_id, $workspace_id]);
		return $stmt->fetch();
	}

	public function getProjectMembers($project_id) {
		$stmt = $this->pdo->prepare("
			SELECT u.id, u.name, u.email
			FROM project_members pm
			JOIN users u ON u.id = pm.user_id
			WHERE pm.project_id = ?
			ORDER BY u.name ASC
		");

		$stmt->execute([$project_id]);
		return $stmt->fetchAll();
	}

	public function updateProject($project_id, $data, $member_ids) {
		try {
			$this->pdo->beginTransaction();

			$stmt = $this->pdo->prepare("
				UPDATE projects
				SET name = ?, description = ?, deadline = ?, color_label = ?
				WHERE id = ? AND workspace_id = ?
			");

			$stmt->execute([
				$data['name'],
				$data['description'],
				$data['deadline'],
				$data['color_label'],
				$project_id,
				$data['workspace_id']
			]);

			$delete = $this->pdo->prepare("
				DELETE FROM project_members
				WHERE project_id = ?
			");

			$delete->execute([$project_id]);

			$insert = $this->pdo->prepare("
				INSERT INTO project_members (project_id, user_id)
				VALUES (?, ?)
			");

			foreach ($member_ids as $user_id) {
				$insert->execute([$project_id, $user_id]);
			}

			$this->pdo->commit();
		} catch (Exception $e) {
			$this->pdo->rollBack();
			throw $e;
		}
	}

	public function archiveProject($project_id, $workspace_id) {
		$stmt = $this->pdo->prepare("
			UPDATE projects
			SET is_archived = 1
			WHERE id = ? AND workspace_id = ?
		");

		return $stmt->execute([$project_id, $workspace_id]);
	}

    public function unarchiveProject($project_id, $workspace_id) {
	$stmt = $this->pdo->prepare("
		UPDATE projects
		SET is_archived = 0
		WHERE id = ? AND workspace_id = ?
	");

	return $stmt->execute([$project_id, $workspace_id]);
}

public function deleteProject($project_id, $workspace_id) {
	$stmt = $this->pdo->prepare("
		DELETE FROM projects
		WHERE id = ? AND workspace_id = ?
	");

	return $stmt->execute([$project_id, $workspace_id]);
}

	public function getTaskSummary($project_id) {
		$stmt = $this->pdo->prepare("
			SELECT status, COUNT(*) AS total
			FROM tasks
			WHERE project_id = ?
			GROUP BY status
		");

		$stmt->execute([$project_id]);
		return $stmt->fetchAll();
	}
}