<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once('../Model/Workspace.php');

$workspace_id = $_SESSION['workspace_id'] ?? 0;
$user_id      = $_SESSION['user_id'];

if (!$workspace_id) {
    header("Location: onbroading.php");
    exit();
}

$workspace = getWorkspaceById($workspace_id);

// Only the owner can access this page
if (!$workspace || $workspace['owner_id'] != $user_id) {
    header("Location: navbar.php");
    exit();
}

$members = getWorkspaceMembers($workspace_id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Workspace Settings</title>
</head>
<body>

<h2>Workspace Settings: <?php echo htmlspecialchars($workspace['name']); ?></h2>
<p>Invite Code: <strong><?php echo htmlspecialchars($workspace['invite_code']); ?></strong></p>

<a href="navbar.php">&larr; Back to Dashboard</a>

<h3>Members</h3>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Name</th>
            <th>Joined</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($members as $member): ?>
        <tr id="row<?php echo $member['member_id']; ?>">
            <td><?php echo htmlspecialchars($member['name']); ?></td>
            <td><?php echo htmlspecialchars($member['joined_at']); ?></td>
            <td>
                <?php if ($member['user_id'] != $user_id): ?>
                    <button onclick="removeMember(<?php echo $member['member_id']; ?>)">
                        Remove
                    </button>
                <?php else: ?>
                    (You — owner)
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script src="../Asset/Script.js"></script>

</body>
</html>
