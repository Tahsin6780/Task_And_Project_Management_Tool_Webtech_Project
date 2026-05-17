<?php
require_once('../Config/db.php');

function registerUser($name, $email, $password) {
    $conn = openConnection();

    // Check duplicate email
    $checkSql = "SELECT id FROM users WHERE email='$email'";
    $checkRes = mysqli_query($conn, $checkSql);
    if (mysqli_num_rows($checkRes) > 0) {
        mysqli_close($conn);
        return 'duplicate';
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password_hash) VALUES ('$name', '$email', '$password_hash')";
    $res = mysqli_query($conn, $sql);
    mysqli_close($conn);
    return $res;
}

function loginUser($email, $password) {
    $conn = openConnection();

    $sql    = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $user   = mysqli_fetch_assoc($result);
    mysqli_close($conn);

    if ($user && password_verify($password, $user['password_hash'])) {
        return $user;
    }
    return null;
}

function getFirstWorkspace($user_id) {
    $conn = openConnection();

    $sql    = "SELECT workspace_id FROM workspace_members WHERE user_id=$user_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row    = mysqli_fetch_assoc($result);
    mysqli_close($conn);

    return $row ? $row['workspace_id'] : null;
}
?>
