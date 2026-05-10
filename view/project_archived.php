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

	<?php if (empty($projects)): ?>
		<div class="card">
			<p>No archived projects.</p>
		</div>
	<?php endif; ?>

	<div class="project-grid">
		<?php foreach ($projects as $project): ?>
			<div class="card project-card" style="border-left-color: <?= e($project['color_label']) ?>">
				<h2><?= e($project['name']) ?></h2>
				<p><?= e($project['description']) ?></p>
				<p><strong>Status:</strong> Archived</p>
			</div>
		<?php endforeach; ?>
	</div>
</div>

</body>
</html>