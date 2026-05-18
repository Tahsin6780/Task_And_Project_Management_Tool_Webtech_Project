<!DOCTYPE html>
<html>
<head>
	<title>Project Details</title>
	<link rel="stylesheet" href="view/style.css?v=10">
</head>
<body>

<div class="navbar">
	<a href="index.php?page=projects">Projects</a>
	<a href="index.php?page=archived_projects">Archived</a>
</div>

<div class="container">
	<div class="page-header">
		<div>
			<p class="project-id">Project ID: <?= e($project['id']) ?></p>
			<h1><?= e($project['name']) ?></h1>
		</div>

		<a class="btn" href="index.php?page=edit_project&id=<?= e($project['id']) ?>">Project Settings</a>
	</div>

	<div class="card">
		<p><?= e($project['description']) ?></p>
		<p><strong>Deadline:</strong> <?= e($project['deadline'] ?? 'No deadline') ?></p>

		<h3>Task Summary</h3>
		<div class="badge-row">
			<span class="badge badge-todo">
				To Do: <?= e($summary['todo']) ?>
			</span>

			<span class="badge badge-progress">
				In Progress: <?= e($summary['in-progress']) ?>
			</span>

			<span class="badge badge-done">
				Done: <?= e($summary['done']) ?>
			</span>
		</div>

		<h3>Members & Assigned Tasks</h3>

		<?php if (empty($members)): ?>
			<p>No members assigned.</p>
		<?php else: ?>
			<div class="member-list">
				<?php foreach ($members as $member): ?>
					<div class="member-row">
						<span class="avatar" title="<?= e($member['name']) ?>">
							<?= e(initials($member['name'])) ?>
						</span>

						<div>
							<strong><?= e($member['name']) ?></strong>
							<p><?= e($member['assigned_task_count']) ?> assigned tasks</p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

</body>
</html>