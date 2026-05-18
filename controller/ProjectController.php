<?php

require_once 'model/Project.php';

class ProjectController {
	private $projectModel;

	public function __construct($pdo) {
		$this->projectModel = new Project($pdo);
	}

	private function workspaceId() {
		if (empty($_SESSION['workspace_id'])) {
			$_SESSION['workspace_id'] = 1;
		}

		return $_SESSION['workspace_id'];
	}

	private function colors() {
		return [
			'#3B82F6',
			'#10B981',
			'#F59E0B',
			'#EF4444',
			'#8B5CF6'
		];
	}

	private function validateProjectForm($workspace_id) {
		$errors = [];

		$name = trim($_POST['name'] ?? '');
		$description = trim($_POST['description'] ?? '');
		$deadline = $_POST['deadline'] ?? '';
		$color_label = $_POST['color_label'] ?? '#3B82F6';
		$member_ids = $_POST['member_ids'] ?? [];

		if ($name === '') {
			$errors['name'] = 'Project name is required.';
		}

		if ($deadline === '') {
			$deadline = null;
		}

		if ($deadline !== null && !strtotime($deadline)) {
			$errors['deadline'] = 'Deadline must be a valid date.';
		}

		if (!in_array($color_label, $this->colors())) {
			$errors['color_label'] = 'Invalid color selected.';
		}

		if (empty($member_ids)) {
			$errors['member_ids'] = 'Select at least one project member.';
		}

		$workspace_members = $this->projectModel->getWorkspaceMembers($workspace_id);
		$allowed_ids = array_map('intval', array_column($workspace_members, 'id'));

		foreach ($member_ids as $member_id) {
			if (!in_array((int)$member_id, $allowed_ids)) {
				$errors['member_ids'] = 'Selected member does not belong to this workspace.';
				break;
			}
		}

		return [
			'errors' => $errors,
			'data' => [
				'workspace_id' => $workspace_id,
				'name' => $name,
				'description' => $description,
				'deadline' => $deadline,
				'color_label' => $color_label
			],
			'member_ids' => array_map('intval', $member_ids)
		];
	}

	public function index() {
		$workspace_id = $this->workspaceId();
		$projects = $this->projectModel->getActiveProjects($workspace_id);

		foreach ($projects as $key => $project) {
			$projects[$key]['members'] = $this->projectModel->getProjectMembers($project['id']);
		}

		require 'view/project_list.php';
	}

	public function create() {
		$workspace_id = $this->workspaceId();

		$members = $this->projectModel->getWorkspaceMembers($workspace_id);
		$colors = $this->colors();
		$errors = [];
		$old = [];

		require 'view/project_create.php';
	}

	public function store() {
		$workspace_id = $this->workspaceId();

		$result = $this->validateProjectForm($workspace_id);

		if (!empty($result['errors'])) {
			$members = $this->projectModel->getWorkspaceMembers($workspace_id);
			$colors = $this->colors();
			$errors = $result['errors'];
			$old = $_POST;

			require 'view/project_create.php';
			return;
		}

		$project_id = $this->projectModel->createProject($result['data'], $result['member_ids']);

		header("Location: index.php?page=show_project&id=" . $project_id);
		exit;
	}

	public function show($id) {
		$workspace_id = $this->workspaceId();

		$project = $this->projectModel->findProject($id, $workspace_id);

		if (!$project) {
			echo "Project not found.";
			return;
		}

		$members = $this->projectModel->getMemberAssignedTaskCounts($id);
		$summary = $this->projectModel->getTaskSummary($id);

		require 'view/project_show.php';
	}

	public function edit($id) {
		$workspace_id = $this->workspaceId();

		$project = $this->projectModel->findProject($id, $workspace_id);

		if (!$project) {
			echo "Project not found.";
			return;
		}

		$members = $this->projectModel->getWorkspaceMembers($workspace_id);
		$project_members = $this->projectModel->getProjectMembers($id);
		$selected_member_ids = array_column($project_members, 'id');

		$colors = $this->colors();
		$errors = [];

		require 'view/project_edit.php';
	}

	public function update() {
		$workspace_id = $this->workspaceId();
		$project_id = $_POST['project_id'] ?? null;

		$project = $this->projectModel->findProject($project_id, $workspace_id);

		if (!$project) {
			echo "Project not found.";
			return;
		}

		$result = $this->validateProjectForm($workspace_id);

		if (!empty($result['errors'])) {
			$members = $this->projectModel->getWorkspaceMembers($workspace_id);
			$selected_member_ids = $_POST['member_ids'] ?? [];
			$colors = $this->colors();
			$errors = $result['errors'];

			require 'view/project_edit.php';
			return;
		}

		$this->projectModel->updateProject($project_id, $result['data'], $result['member_ids']);

		header("Location: index.php?page=show_project&id=" . $project_id);
		exit;
	}

	public function archive($id) {
		$workspace_id = $this->workspaceId();

		if (!$id) {
			echo "Project ID is missing.";
			return;
		}

		$project = $this->projectModel->findProject($id, $workspace_id);

		if (!$project) {
			echo "Project not found.";
			return;
		}

		$this->projectModel->archiveProject($id, $workspace_id);

		header("Location: index.php?page=projects");
		exit;
	}

	public function archiveAjax($id) {
		header('Content-Type: application/json');

		$workspace_id = $this->workspaceId();

		if (!$id) {
			echo json_encode([
				'ok' => false,
				'message' => 'Project ID is missing.'
			]);
			exit;
		}

		$project = $this->projectModel->findProject($id, $workspace_id);

		if (!$project) {
			http_response_code(404);
			echo json_encode([
				'ok' => false,
				'message' => 'Project not found.'
			]);
			exit;
		}

		$ok = $this->projectModel->archiveProject($id, $workspace_id);

		echo json_encode([
			'ok' => $ok,
			'message' => $ok ? 'Project archived successfully.' : 'Could not archive project.',
			'project_id' => $id
		]);
		exit;
	}

	public function unarchiveAjax($id) {
		header('Content-Type: application/json');

		$workspace_id = $this->workspaceId();

		if (!$id) {
			echo json_encode([
				'ok' => false,
				'message' => 'Project ID is missing.'
			]);
			exit;
		}

		$project = $this->projectModel->findProject($id, $workspace_id);

		if (!$project) {
			http_response_code(404);
			echo json_encode([
				'ok' => false,
				'message' => 'Project not found.'
			]);
			exit;
		}

		$ok = $this->projectModel->unarchiveProject($id, $workspace_id);

		echo json_encode([
			'ok' => $ok,
			'message' => $ok ? 'Project restored to active projects.' : 'Could not unarchive project.',
			'project_id' => $id
		]);
		exit;
	}

	public function deleteAjax($id) {
		header('Content-Type: application/json');

		$workspace_id = $this->workspaceId();

		if (!$id) {
			echo json_encode([
				'ok' => false,
				'message' => 'Project ID is missing.'
			]);
			exit;
		}

		$project = $this->projectModel->findProject($id, $workspace_id);

		if (!$project) {
			http_response_code(404);
			echo json_encode([
				'ok' => false,
				'message' => 'Project not found.'
			]);
			exit;
		}

		$ok = $this->projectModel->deleteProject($id, $workspace_id);

		echo json_encode([
			'ok' => $ok,
			'message' => $ok ? 'Project deleted permanently.' : 'Could not delete project.',
			'project_id' => $id
		]);
		exit;
	}

	public function archived() {
		$workspace_id = $this->workspaceId();
		$projects = $this->projectModel->getArchivedProjects($workspace_id);

		require 'view/project_archived.php';
	}
}