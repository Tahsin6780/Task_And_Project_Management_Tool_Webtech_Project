<?php
session_start();

// Redirect already-logged-in users
if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    header("Location: navbar.php");
    exit();
}


$nameError = $_SESSION["nameError"] ?? "";
$emailError = $_SESSION["emailError"] ?? "";
$passwordError = $_SESSION["passwordError"] ?? "";

$name = $_SESSION["name"] ?? "";
$email = $_SESSION["email"] ?? "";

unset($_SESSION["nameError"]);
unset($_SESSION["emailError"]);
unset($_SESSION["passwordError"]);

unset($_SESSION["name"]);
unset($_SESSION["email"]);

?>

<html>

<head>
    <title>Registration</title>
</head>

<body>

    <h2>Registration Form</h2>

    <form method="post" action="../Controller/AuthController.php">

        <table>

            <tr>
            <td>Name</td>
            <td>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
            </td>
            <td style="color:red;"><?php echo $nameError; ?></td>
        </tr>

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
            <td>
                <!-- name="register" so AuthController can tell this from login -->
                <input type="submit" name="register" value="Register">
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                Already have an account?
                <a href="login.php">Login</a>
            </td>
        </tr>

    </table>

</form>

</body>
</html>
