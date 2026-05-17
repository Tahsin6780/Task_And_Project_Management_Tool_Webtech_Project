<!DOCTYPE html>
<html>
<head>
	<title>Archived Projects</title>
	<link rel="stylesheet" href="view/style.css">
</head>
<body>

<div class="navbar">
	<a href="index.php?page=projects">Projects</a>
	<a href="index.php?page=archived_projects">Archived</a>
</div>

<div class="container">
	<h1>Archived Projects</h1>

	<div id="ajax-message"></div>

	<?php if (empty($projects)): ?>
		<div class="card" id="empty-state">
			<p>No archived projects.</p>
		</div>
	<?php endif; ?>

	<div class="project-grid" id="project-grid">
		<?php foreach ($projects as $project): ?>
			<div class="card project-card" id="project-card-<?= e($project['id']) ?>" style="border-left-color: <?= e($project['color_label']) ?>">
				<h2><?= e($project['name']) ?></h2>
				<p><?= e($project['description']) ?></p>
				<p><strong>Status:</strong> Archived</p>

				<div class="actions">
					<button
						type="button"
						class="unarchive-btn"
						data-project-id="<?= e($project['id']) ?>"
					>
						Unarchive
					</button>

					<button
						type="button"
						class="delete-btn"
						data-project-id="<?= e($project['id']) ?>"
					>
						Delete
					</button>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<script src="controller/projects.js"></script>
</body>
</html>