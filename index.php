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
	Temporary testing session.
	Remove this after Member 1 auth/workspace module is merged.
*/
if (empty($_SESSION['user_id'])) {
	$_SESSION['user_id'] = 1;
	$_SESSION['name'] = 'Pikachu';
	$_SESSION['workspace_id'] = 1;
}

$database = new Database();
$pdo = $database->connect();

$controller = new ProjectController($pdo);

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method === 'POST' && preg_match('#/api/projects/([0-9]+)/archive$#', $request_uri, $matches)) {
	$controller->archive($matches[1]);
	exit;
}

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
} elseif ($page === 'archive_project' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller->archive($_POST['id'] ?? null);
} elseif ($page === 'ajax_archive_project' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller->archiveAjax($_POST['id'] ?? null);
} elseif ($page === 'ajax_unarchive_project' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller->unarchiveAjax($_POST['id'] ?? null);
} elseif ($page === 'ajax_delete_project' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller->deleteAjax($_POST['id'] ?? null);
} elseif ($page === 'archived_projects') {
	$controller->archived();
} else {
	echo "Page not found.";
}