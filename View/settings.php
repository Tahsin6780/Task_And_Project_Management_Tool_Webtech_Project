<?php
session_start();

require_once('../Config/db.php');

if(!isset($_SESSION['user_id']))
{
    header("location: login.php");
}

$conn = openConnection();

$workspace_id = $_SESSION['workspace_id'];

$sql = "SELECT workspace_members.id,users.name,workspace_members.joined_at,workspaces.owner_id,users.id as user_id

        FROM workspace_members

        JOIN users
        ON workspace_members.user_id = users.id

        JOIN workspaces
        ON workspace_members.workspace_id = workspaces.id

        WHERE workspace_members.workspace_id='$workspace_id'";

$result = mysqli_query($conn, $sql);

$members = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Workspace Settings</title>

    <script src="../Asset/Script.js"></script>

</head>

<body>

<h2>Workspace Members</h2>

<table border="1" cellpadding="10">

    <tr>
        <th>Name</th>
        <th>Join Date</th>
        <th>Action</th>
    </tr>

<?php
foreach($members as $member)
{
?>

<tr id="row<?php echo $member['id']; ?>">

    <td>
        <?php echo $member['name']; ?>
    </td>

    <td>
        <?php echo $member['joined_at']; ?>
    </td>

    <td>

<?php
if($_SESSION['user_id'] == $member['owner_id']&& $_SESSION['user_id'] != $member['user_id'])
{
?>

<button onclick="removeMember(<?php echo $member['id']; ?>)">
    Remove
</button>

<?php
}
?>

    </td>

</tr>

<?php
}
?>

</table>

</body>
</html>