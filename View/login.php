<?php

session_start();

if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    header("Location: navbar.php");
    exit();
}

$emailError = $_SESSION["emailError"] ?? "";
$passwordError = $_SESSION["passwordError"] ?? "";
$loginError = $_SESSION["loginError"] ?? "";
$email = $_SESSION["email"] ?? "";


unset($_SESSION["emailError"]);
unset($_SESSION["passwordError"]);
unset($_SESSION["loginError"]);
unset($_SESSION["email"]);

?>

<html>

<head>
    <title>Login</title>
</head>

<body>

    <h2>Login Form</h2>

    <form method="post" action="../Controller/AuthController.php">

        <table>
            <tr>
            <td>Email</td>
            <td>
                <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </td>
            <td style="color:red;"><?php echo $emailError; ?></td>
        </tr>

        <tr>
            <td>Password</td>
            <td>
                <input type="password" name="password">
            </td>
            <td style="color:red;"><?php echo $passwordError; ?></td>
        </tr>

        <tr>
            <td></td>
            <td style="color:red;"><?php echo $loginError; ?></td>
        </tr>

        <tr>
            <td></td>
            <td>
                <!-- name="login" so AuthController can tell this from register -->
                <input type="submit" name="login" value="Login">
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <a href="register.php">Create New Account</a>
            </td>
        </tr>

    </table>

</form>

</body>
</html>
