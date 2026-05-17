<?php
session_start();
require_once('../Model/Workspace.php');
// ── CREATE WORKSPACE ──
if (isset($_POST['create_workspace'])) {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $owner_id    = $_SESSION['user_id'];

    if ($name === '') {
        $_SESSION['workspaceError'] = "Workspace name is required";
        header("Location: ../View/onboarding.php");
        exit();
    }

    $invite_code  = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
    $workspace_id = createWorkspace($name, $description, $owner_id, $invite_code);

    if ($workspace_id) {
        $_SESSION['workspace_id'] = $workspace_id;
        header("Location: ../View/navbar.php");
    } else {
        $_SESSION['workspaceError'] = "Failed to create workspace";
        header("Location: ../View/onboarding.php");
    }
    exit();
}

// ── JOIN WORKSPACE ──
if (isset($_POST['join_workspace'])) {
    $code    = trim($_POST['invite_code'] ?? '');
    $user_id = $_SESSION['user_id'];

    $workspace_id = joinWorkspace($code, $user_id);

    if ($workspace_id) {
        $_SESSION['workspace_id'] = $workspace_id;
        header("Location: ../View/navbar.php");
    } else {
        $_SESSION['joinError'] = "Invalid invite code";
        header("Location: ../View/onboarding.php");
    }
    exit();
}

// ── SWITCH WORKSPACE ──
if (isset($_GET['switch'])) {
    $_SESSION['workspace_id'] = intval($_GET['switch']);
    header("Location: ../View/navbar.php");
    exit();
}

// ── DELETE MEMBER (AJAX) ──
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id  = intval($_POST['id']);
    $res = removeMember($id);
    header('Content-Type: application/json');
    echo json_encode($res ? ['status'=>'success'] : ['status'=>'error','message'=>'Could not remove']);
    exit();
}
?>