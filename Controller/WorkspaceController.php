<?php
require_once('../Config/db.php');

function createWorkspace($name, $description, $owner_id, $invite_code) {
    $conn = openConnection();

    $sql = "INSERT INTO workspaces (name, description, owner_id, invite_code) VALUES ('$name', '$description', $owner_id, '$invite_code')";
    $res = mysqli_query($conn, $sql);
    $workspace_id = mysqli_insert_id($conn);

    if ($res) {
        $sql2 = "INSERT INTO workspace_members (workspace_id, user_id) VALUES ($workspace_id, $owner_id)";
        mysqli_query($conn, $sql2);
    }

    mysqli_close($conn);
    return $res ? $workspace_id : false;
}

function joinWorkspace($code, $user_id) {
    $conn = openConnection();

    $sql       = "SELECT id FROM workspaces WHERE invite_code='$code'";
    $result    = mysqli_query($conn, $sql);
    $workspace = mysqli_fetch_assoc($result);

    if (!$workspace) {
        mysqli_close($conn);
        return false;
    }

    $workspace_id = $workspace['id'];

    // Prevent duplicate membership
    $checkSql = "SELECT id FROM workspace_members WHERE workspace_id=$workspace_id AND user_id=$user_id";
    $checkRes = mysqli_query($conn, $checkSql);
    if (mysqli_num_rows($checkRes) > 0) {
        mysqli_close($conn);
        return $workspace_id;
    }

    $sql2 = "INSERT INTO workspace_members (workspace_id, user_id) VALUES ($workspace_id, $user_id)";
    mysqli_query($conn, $sql2);
    mysqli_close($conn);
    return $workspace_id;
}

function getUserWorkspaces($user_id) {
    $conn = openConnection();

    $sql    = "SELECT workspaces.* FROM workspaces
               JOIN workspace_members ON workspaces.id = workspace_members.workspace_id
               WHERE workspace_members.user_id=$user_id";
    $result = mysqli_query($conn, $sql);
    $workspaces = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $workspaces;
}

function getWorkspaceById($workspace_id) {
    $conn = openConnection();

    $sql       = "SELECT * FROM workspaces WHERE id=$workspace_id";
    $result    = mysqli_query($conn, $sql);
    $workspace = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $workspace;
}

function getWorkspaceMembers($workspace_id) {
    $conn = openConnection();

    $sql    = "SELECT workspace_members.id AS member_id,
                      users.id AS user_id,
                      users.name,
                      workspace_members.joined_at
               FROM workspace_members
               JOIN users ON users.id = workspace_members.user_id
               WHERE workspace_members.workspace_id=$workspace_id
               ORDER BY workspace_members.joined_at ASC";
    $result  = mysqli_query($conn, $sql);
    $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $members;
}

function removeMember($member_row_id) {
    $conn = openConnection();

    $sql = "DELETE FROM workspace_members WHERE id=$member_row_id";
    $res = mysqli_query($conn, $sql);
    mysqli_close($conn);
    return $res;
}

function isMember($workspace_id, $user_id) {
    $conn = openConnection();

    $sql    = "SELECT id FROM workspace_members WHERE workspace_id=$workspace_id AND user_id=$user_id";
    $result = mysqli_query($conn, $sql);
    $found  = mysqli_num_rows($result) > 0;
    mysqli_close($conn);
    return $found;
}
?>
