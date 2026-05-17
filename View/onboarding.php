<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$workspaceError = $_SESSION['workspaceError'] ?? '';
$joinError      = $_SESSION['joinError']      ?? '';
unset($_SESSION['workspaceError'], $_SESSION['joinError']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Get Started</title>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
<p>You are not part of any workspace yet. Create a new one or join an existing one.</p>

<h3>Create a Workspace</h3>

<?php if ($workspaceError): ?>
    <p style="color:red;"><?php echo $workspaceError; ?></p>
<?php endif; ?>

<form action="../Controller/WorkspaceController.php" method="POST">
    <input type="text" name="name" placeholder="Workspace Name" required>
    <input type="text" name="description" placeholder="Description">
    <button type="submit" name="create_workspace">Create</button>
</form>

<hr>

<h3>Join a Workspace</h3>

<?php if ($joinError): ?>
    <p style="color:red;"><?php echo $joinError; ?></p>
<?php endif; ?>

<form action="../Controller/WorkspaceController.php" method="POST">
    <input type="text" name="invite_code" placeholder="6-character Invite Code" required>
    <button type="submit" name="join_workspace">Join</button>
</form>

</body>
</html>
