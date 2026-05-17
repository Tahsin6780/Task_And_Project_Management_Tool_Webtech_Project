<?php
session_start();

// Auth guard — every workspace action requires login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

require_once('../Model/Workspace.php');

$user_id = $_SESSION['user_id'];

// CREATE WORKSPACE
if (isset($_POST['create_workspace'])) {
    $name        = trim($_POST['name']        ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '') {
        $_SESSION['workspaceError'] = "Workspace name is required";
        header("Location: ../View/onbroading.php");
        exit();
    }

    $invite_code  = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 6));
    $workspace_id = createWorkspace($name, $description, $user_id, $invite_code);

    if ($workspace_id) {
        $_SESSION['workspace_id'] = $workspace_id;
        header("Location: ../View/navbar.php");
    } else {
        $_SESSION['workspaceError'] = "Could not create workspace, please try again";
        header("Location: ../View/onbroading.php");
    }
    exit();
}

// JOIN WORKSPACE 
if (isset($_POST['join_workspace'])) {
    $code = trim($_POST['invite_code'] ?? '');

    if ($code === '') {
        $_SESSION['joinError'] = "Please enter an invite code";
        header("Location: ../View/onbroading.php");
        exit();
    }

    $workspace_id = joinWorkspace($code, $user_id);

    if ($workspace_id) {
        $_SESSION['workspace_id'] = $workspace_id;
        header("Location: ../View/navbar.php");
    } else {
        $_SESSION['joinError'] = "Invalid invite code";
        header("Location: ../View/onbroading.php");
    }
    exit();
}

//WORKSPACE SWITCHER  GET /workspace/switch/{id}
// 
if (isset($_GET['switch'])) {
    $new_workspace_id = (int) $_GET['switch'];

    if ($new_workspace_id > 0 && isMember($new_workspace_id, $user_id)) {
        $_SESSION['workspace_id'] = $new_workspace_id;
    }
    // Redirect back to the project list (navbar is the project list for now)
    header("Location: ../View/navbar.php");
    exit();
}

// ── AJAX: DELETE MEMBER 
// DELETE  (sent as POST with action=delete for simplicity)
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    header('Content-Type: application/json');

    $member_row_id    = (int) ($_POST['id'] ?? 0);
    $workspace_id     = (int) ($_SESSION['workspace_id'] ?? 0);

    // Only the workspace owner can remove members
    $workspace = getWorkspaceById($workspace_id);
    if (!$workspace || $workspace['owner_id'] != $user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Not authorised']);
        exit();
    }

    // Prevent the owner from removing themselves
    $members = getWorkspaceMembers($workspace_id);
    foreach ($members as $m) {
        if ($m['member_id'] == $member_row_id && $m['user_id'] == $user_id) {
            echo json_encode(['status' => 'error', 'message' => 'Cannot remove yourself']);
            exit();
        }
    }

    if (removeMember($member_row_id)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
    }
    exit();
}
?>
