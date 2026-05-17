<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once('../Model/Workspace.php');

$workspaces   = getUserWorkspaces($_SESSION['user_id']);
$current_id   = $_SESSION['workspace_id'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h2>

<!-- Workspace switcher -->
<?php if (!empty($workspaces)): ?>
<label for="ws-switcher">Workspace:</label>
<select id="ws-switcher" onchange="switchWorkspace(this.value)">
    <?php foreach ($workspaces as $ws): ?>
        <option value="<?php echo $ws['id']; ?>"
            <?php echo ($ws['id'] == $current_id) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($ws['name']); ?>
        </option>
    <?php endforeach; ?>
</select>
<?php endif; ?>

&nbsp;|&nbsp;
<a href="settings.php">Workspace Settings</a>
&nbsp;|&nbsp;
<a href="../Controller/AuthController.php?logout=1">Logout</a>

<hr>
<p>Project list goes here (Task 2 area).</p>

<script>
function switchWorkspace(id) {
    window.location.href = '../Controller/WorkspaceController.php?switch=' + id;
}
</script>

</body>
</html>
