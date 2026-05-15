<?php

session_start();

require_once 'model/Database.php';
require_once 'controller/ProjectController.php';

function e($value) {
	return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function initials($name) {
	$parts = preg_split('/\s+/', trim($name));
	$letters = '';

	foreach ($parts as $part) {
		if ($part !== '') {
			$letters .= strtoupper(substr($part, 0, 1));
		}
	}

	return substr($letters, 0, 2);
}

/*
	Temporary session for testing Member 2 independently.
	When Member 1 login is merged, remove this block.
*/
if (empty($_SESSION['user_id'])) {
	header('Location: index.php?page=login');
	exit;
}

if (empty($_SESSION['workspace_id'])) {
	header('Location: index.php?page=workspace_setup');
	exit;
}

$database = new Database();
$pdo = $database->connect();

$controller = new ProjectController($pdo);

$page = $_GET['page'] ?? 'projects';

if ($page === 'projects') {
	$controller->index();
} elseif ($page === 'create_project') {
	$controller->create();
} elseif ($page === 'store_project' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller->store();
} elseif ($page === 'show_project') {
	$controller->show($_GET['id'] ?? null);
} elseif ($page === 'edit_project') {
	$controller->edit($_GET['id'] ?? null);
} elseif ($page === 'update_project' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller->update();
} elseif ($page === 'archive_project') {
	$controller->archive($_GET['id'] ?? null);
} elseif ($page === 'ajax_archive_project' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller->archiveAjax($_POST['id'] ?? null);
} elseif ($page === 'archived_projects') {
	$controller->archived();
} else {
	echo "Page not found.";
}