<?php
session_start();

require_once('../Model/Workspace.php');

if(isset($_POST['create_workspace']))
{
    $name = $_POST['name'];
    $description = $_POST['description'];

    $owner_id = $_SESSION['user_id'];

    $invite_code =
    substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"),0,6);

    createWorkspace(
        $name,
        $description,
        $owner_id,
        $invite_code
    );

    header("location: ../View/navbar.php");
}

elseif(isset($_POST['join_workspace']))
{
    $code = $_POST['invite_code'];

    $user_id = $_SESSION['user_id'];

    if(joinWorkspace($code,$user_id))
    {
        header("location: ../View/navbar.php");
    }
    else
    {
        echo "Invalid Invite Code";
    }
}

elseif(isset($_POST['action']) && $_POST['action'] == 'delete')
{
    $id = $_POST['id'];

    if(removeMember($id))
    {
        echo json_encode(
            ['status'=>'success']
        );
    }
    else
    {
        echo json_encode(
            ['status'=>'error']
        );
    }
}
?>