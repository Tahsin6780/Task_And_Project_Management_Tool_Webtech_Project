<?php
session_start();
?>
<html>
<head>
    <title>Workspace</title>
</head>
<body>

<h2>Create Workspace</h2>

<form action="../Controller/WorkspaceController.php" method="POST">

    <input type="text" name="name" placeholder="Workspace Name">

    <input type="text" name="description" placeholder="Description">

    <button type="submit" name="create_workspace">
        Create
    </button>

</form>

<hr>

<h2>Join Workspace</h2>

<form action="../Controller/WorkspaceController.php" method="POST">

    <input type="text" name="invite_code" placeholder="Invite Code">

    <button type="submit" name="join_workspace">
        Join
    </button>

</form>

</body>
</html>