<?php
session_start();

require_once('../Model/Workspace.php');

$data = getUserWorkspaces($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Navbar</title>
</head>
<body>

<h2>Welcome <?php echo $_SESSION['name']; ?></h2>

<select>

<?php
foreach($data as $workspace){
?>

<option>
    <?php echo $workspace['name']; ?>
</option>

<?php
}
?>

</select>

</body>
</html>