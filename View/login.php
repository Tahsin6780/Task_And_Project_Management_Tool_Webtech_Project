<?php

session_start();

$emailError = $_SESSION["emailError"] ?? "";
$passwordError = $_SESSION["passwordError"] ?? "";
$loginError = $_SESSION["loginError"] ?? "";

$email = $_SESSION["email"] ?? "";

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;

if ($isLoggedIn) {

    //header("Location: dashboard.php");
    exit();
}

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
                    <input
                        type="text"
                        name="email"
                        value="<?php echo $email; ?>">
                </td>

                <td style="color:red;">
                    <?php echo $emailError; ?>
                </td>

            </tr>

            <tr>

                <td>Password</td>

                <td>
                    <input type="password" name="password">
                </td>

                <td style="color:red;">
                    <?php echo $passwordError; ?>
                </td>

            </tr>

            <tr>

                <td></td>

                <td style="color:red;">
                    <?php echo $loginError; ?>
                </td>

            </tr>

            <tr>

                <td></td>

                <td>
                    <input
                        type="submit"
                        name="login"
                        value="Login">
                </td>

            </tr>

            <tr>

                <td></td>

                <td>
                    <a href="register.php">
                        Create New Account
                    </a>
                </td>

            </tr>

        </table>

    </form>

</body>

</html>