<!DOCTYPE html>
<html>
<head>
	<title>Create Project</title>
	<link rel="stylesheet" href="view/style.css">
</head>
<body>

<div class="navbar">
	<a href="index.php?page=projects">Projects</a>
	<a href="index.php?page=archived_projects">Archived</a>
</div>

<div class="container">
	<h1>Create Project</h1>

	<form method="POST" action="index.php?page=store_project" class="card form">
		<label>Project Name</label>
		<input type="text" name="name" value="<?= e($old['name'] ?? '') ?>">
		<?php if (!empty($errors['name'])): ?>
			<small class="error"><?= e($errors['name']) ?></small>
		<?php endif; ?>

		<label>Description</label>
		<textarea name="description"><?= e($old['description'] ?? '') ?></textarea>

		<label>Deadline</label>
		<input type="date" name="deadline" value="<?= e($old['deadline'] ?? '') ?>">
		<?php if (!empty($errors['deadline'])): ?>
			<small class="error"><?= e($errors['deadline']) ?></small>
		<?php endif; ?>

		<label>Color</label>
		<div class="color-options">
			<?php foreach ($colors as $color): ?>
				<label>
					<input type="radio" name="color_label" value="<?= e($color) ?>" <?= (($old['color_label'] ?? '#3B82F6') === $color) ? 'checked' : '' ?>>
					<span class="color-dot" style="background: <?= e($color) ?>"></span>
				</label>
			<?php endforeach; ?>
		</div>

		<label>Members</label>
		<?php foreach ($members as $member): ?>
			<label class="checkbox">
				<input type="checkbox" name="member_ids[]" value="<?= e($member['id']) ?>">
				<?= e($member['name']) ?> — <?= e($member['email']) ?>
			</label>
		<?php endforeach; ?>

		<?php if (!empty($errors['member_ids'])): ?>
			<small class="error"><?= e($errors['member_ids']) ?></small>
		<?php endif; ?>

		<button type="submit" class="btn">Create Project</button>
	</form>
</div>

</body>
</html>