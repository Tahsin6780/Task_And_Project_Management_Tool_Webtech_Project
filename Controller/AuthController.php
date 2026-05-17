<?php
session_start();
include_once "../Config/db.php";
include_once "../Model/User.php";

// REGISTER 
if (isset($_POST['register'])) {
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']       ?? '';

    $errors = false;

    if ($name === '') {
        $_SESSION['nameError'] = "Name is required";
        $errors = true;
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['emailError'] = "Valid email is required";
        $errors = true;
    }
    if (strlen($password) < 8) {
        $_SESSION['passwordError'] = "Password must be at least 8 characters";
        $errors = true;
    }

    // Keep entered values so the form re-fills on error
    $_SESSION['name']  = $name;
    $_SESSION['email'] = $email;

    if ($errors) {
        header("Location: ../View/register.php");
        exit();
    }

    $user   = new User();
    $result = $user->register($name, $email, $password);

    if ($result === 'duplicate') {
        $_SESSION['emailError'] = "An account with this email already exists";
        $_SESSION['email']      = $email;
        header("Location: ../View/register.php");
        exit();
    }

    if ($result) {
        header("Location: ../View/login.php");
    } else {
        $_SESSION['emailError'] = "Registration failed, please try again";
        header("Location: ../View/register.php");
    }
    exit();
}

// LOGIN 
if (isset($_POST['login'])) {
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']       ?? '';

    $errors = false;

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['emailError'] = "Valid email is required";
        $errors = true;
    }
    if ($password === '') {
        $_SESSION['passwordError'] = "Password is required";
        $errors = true;
    }

    $_SESSION['email'] = $email;

    if ($errors) {
        header("Location: ../View/login.php");
        exit();
    }

    $userModel = new User();
    $user      = $userModel->login($email, $password);

    if ($user) {
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['name']       = $user['name'];
        $_SESSION['isLoggedIn'] = true;

        // Set workspace_id to the user's first workspace (or null if none)
        $workspace_id = $userModel->getFirstWorkspace($user['id']);
        $_SESSION['workspace_id'] = $workspace_id;

        if ($workspace_id) {
            header("Location: ../View/navbar.php");
        } else {
            header("Location: ../View/onbroading.php");
        }
    } else {
        $_SESSION['loginError'] = "Invalid email or password";
        $_SESSION['email']      = $email;
        header("Location: ../View/login.php");
    }
    exit();
}

// ── LOGOUT 
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../View/login.php");
    exit();
}
?>
