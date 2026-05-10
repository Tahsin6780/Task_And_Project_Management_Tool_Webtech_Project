<!DOCTYPE html>
<html>
<head>
	<title>Project Details</title>
	<link rel="stylesheet" href="view/style.css">
</head>
<body>

<div class="navbar">
	<a href="index.php?page=projects">Projects</a>
	<a href="index.php?page=archived_projects">Archived</a>
</div>

<div class="container">
	<div class="page-header">
		<h1><?= e($project['name']) ?></h1>
		<a class="btn" href="index.php?page=edit_project&id=<?= e($project['id']) ?>">Edit Project</a>
	</div>

	<div class="card">
		<p><?= e($project['description']) ?></p>
		<p><strong>Deadline:</strong> <?= e($project['deadline'] ?? 'No deadline') ?></p>

		<h3>Members</h3>
		<div class="avatars">
			<?php foreach ($members as $member): ?>
				<span class="avatar" title="<?= e($member['name']) ?>">
					<?= e(initials($member['name'])) ?>
				</span>
			<?php endforeach; ?>
		</div>

		<h3>Task Summary</h3>
		<?php if (empty($summary)): ?>
			<p>No tasks yet.</p>
		<?php else: ?>
			<ul>
				<?php foreach ($summary as $row): ?>
					<li><?= e($row['status']) ?>: <?= e($row['total']) ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>

</body>
</html>