<?php
require_once('../Config/db.php');

function createWorkspace($name, $description, $owner_id, $invite_code)
{
    $conn = openConnection();

    $sql = "INSERT INTO workspaces(name,description,owner_id,invite_code)
            VALUES('$name','$description','$owner_id','$invite_code')";

    $result = mysqli_query($conn, $sql);

    $workspace_id = mysqli_insert_id($conn);

    $sql2 = "INSERT INTO workspace_members(workspace_id,user_id)
             VALUES('$workspace_id','$owner_id')";

    mysqli_query($conn, $sql2);

    mysqli_close($conn);

    return $result;
}

function joinWorkspace($code, $user_id)
{
    $conn = openConnection();

    $sql = "SELECT * FROM workspaces
            WHERE invite_code='$code'";

    $result = mysqli_query($conn, $sql);

    $workspace = mysqli_fetch_assoc($result);

    if($workspace){

        $workspace_id = $workspace['id'];

        $sql2 = "INSERT INTO workspace_members(workspace_id,user_id)
                 VALUES('$workspace_id','$user_id')";

        mysqli_query($conn, $sql2);

        mysqli_close($conn);

        return true;
    }

    mysqli_close($conn);

    return false;
}

function getUserWorkspaces($user_id)
{
    $conn = openConnection();

    $sql = "SELECT workspaces.*
            FROM workspaces
            JOIN workspace_members
            ON workspaces.id = workspace_members.workspace_id
            WHERE workspace_members.user_id='$user_id'";

    $result = mysqli_query($conn, $sql);

    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_close($conn);

    return $data;
}

function removeMember($id)
{
    $conn = openConnection();

    $sql = "DELETE FROM workspace_members WHERE id='$id'";

    $result = mysqli_query($conn, $sql);

    mysqli_close($conn);

    return $result;
}
?>