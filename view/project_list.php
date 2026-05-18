<!DOCTYPE html>
<html>
<head>
	<title>Projects</title>
	<link rel="stylesheet" href="view/style.css?v=10">
</head>
<body>

<div class="navbar">
	<a href="index.php?page=projects">Projects</a>
	<a href="index.php?page=archived_projects">Archived</a>
</div>

<div class="container">
	<div class="page-header">
		<h1>Projects</h1>
		<a class="btn" href="index.php?page=create_project">Create Project</a>
	</div>

	<div id="ajax-message"></div>

	<?php if (empty($projects)): ?>
		<div class="card" id="empty-state">
			<p>No active projects yet.</p>
		</div>
	<?php endif; ?>

	<div class="project-grid" id="project-grid">
		<?php foreach ($projects as $project): ?>
			<div class="card project-card" id="project-card-<?= e($project['id']) ?>" style="border-left-color: <?= e($project['color_label']) ?>">
				<p class="project-id">Project ID: <?= e($project['id']) ?></p>

				<h2><?= e($project['name']) ?></h2>

				<p><?= e($project['description']) ?></p>

				<?php if ($project['deadline']): ?>
					<p class="<?= strtotime($project['deadline']) < strtotime(date('Y-m-d')) ? 'text-red' : '' ?>">
						Deadline: <?= e($project['deadline']) ?>
					</p>
				<?php else: ?>
					<p class="muted">No deadline</p>
				<?php endif; ?>

				<div class="avatars">
					<?php if (empty($project['members'])): ?>
						<span class="muted">No members</span>
					<?php else: ?>
						<?php foreach ($project['members'] as $member): ?>
							<span class="avatar" title="<?= e($member['name']) ?>">
								<?= e(initials($member['name'])) ?>
							</span>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<?php if ($project['progress_percent'] === null): ?>
					<p>No tasks yet</p>
					<div class="progress">
						<div class="progress-bar gray" style="width: 100%"></div>
					</div>
				<?php else: ?>
					<p>Progress: <?= e($project['progress_percent']) ?>%</p>
					<div class="progress">
						<div class="progress-bar" style="width: <?= e($project['progress_percent']) ?>%"></div>
					</div>
				<?php endif; ?>

				<div class="actions">
					<a href="index.php?page=show_project&id=<?= e($project['id']) ?>">View</a>
					<a href="index.php?page=edit_project&id=<?= e($project['id']) ?>">Edit</a>

					<button
						type="button"
						class="archive-btn"
						data-project-id="<?= e($project['id']) ?>"
					>
						Archive
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