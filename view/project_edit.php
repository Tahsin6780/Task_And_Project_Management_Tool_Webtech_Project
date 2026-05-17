<!DOCTYPE html>
<html>
<head>
	<title>Edit Project</title>
	<link rel="stylesheet" href="view/style.css">
</head>
<body>

<div class="navbar">
	<a href="index.php?page=projects">Projects</a>
	<a href="index.php?page=archived_projects">Archived</a>
</div>

<div class="container">
	<h1>Edit Project</h1>

	<form method="POST" action="index.php?page=update_project" class="card form project-form">
		<input type="hidden" name="project_id" value="<?= e($project['id']) ?>">

		<label>Project Name</label>
		<input type="text" name="name" value="<?= e($_POST['name'] ?? $project['name']) ?>">
		<?php if (!empty($errors['name'])): ?>
			<small class="error"><?= e($errors['name']) ?></small>
		<?php endif; ?>

		<label>Description</label>
		<textarea name="description"><?= e($_POST['description'] ?? $project['description']) ?></textarea>

		<label>Deadline</label>
		<input type="date" name="deadline" value="<?= e($_POST['deadline'] ?? $project['deadline']) ?>">
		<?php if (!empty($errors['deadline'])): ?>
			<small class="error"><?= e($errors['deadline']) ?></small>
		<?php endif; ?>

		<label>Color</label>
		<div class="color-options">
			<?php foreach ($colors as $color): ?>
				<label>
					<input type="radio" name="color_label" value="<?= e($color) ?>" <?= (($_POST['color_label'] ?? $project['color_label']) === $color) ? 'checked' : '' ?>>
					<span class="color-dot" style="background: <?= e($color) ?>"></span>
				</label>
			<?php endforeach; ?>
		</div>

		<label>Members</label>

		<?php if (empty($members)): ?>
			<p class="error">No workspace members found.</p>
		<?php else: ?>
			<?php foreach ($members as $member): ?>
				<label class="checkbox">
					<input
						type="checkbox"
						name="member_ids[]"
						value="<?= e($member['id']) ?>"
						<?= in_array($member['id'], array_map('intval', $selected_member_ids)) ? 'checked' : '' ?>
					>
					<?= e($member['name']) ?> — <?= e($member['email']) ?>
				</label>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if (!empty($errors['member_ids'])): ?>
			<small class="error"><?= e($errors['member_ids']) ?></small>
		<?php endif; ?>

		<button type="submit" class="btn">Save Changes</button>
	</form>
</div>

<script src="controller/projects.js"></script>
</body>
</html>