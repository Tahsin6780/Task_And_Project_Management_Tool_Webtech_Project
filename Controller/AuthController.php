<?php

session_start();
include_once "../Config/db.php";
include_once "../Model/User.php";


if(isset($_POST['register'])){
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if(strlen($password) < 8) {  
        $_SESSION["passwordError"] = "Password must be 8 characters";
        header("Location: ../View/register.php");
        exit();
    }
    
    $user = new User();
    $result = $user->register($name, $email, $password);

    if($result){
        header("Location: ../View/login.php");
    } else {
        header("Location: ../View/register.php");
    }
    exit();
}

if (isset($_POST['login'])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $userModel = new User();
    $user = $userModel->Login($email, $password);

    if($user){
        $_SESSION["user_id"] = $user["id"]; 
        $_SESSION["name"] = $user["name"];  
        $_SESSION["isLoggedIn"] = true;
        $_SESSION["workspace_id"] = null; 

        if ($_SESSION["workspace_id"]) {
            header("Location: ../View/dashboard.php");
        } else {
            header("Location: ../View/workspace.php"); 
        }
    } else {
        $_SESSION["loginError"] = "Invalid email or password";
        header("Location: ../View/login.php");
    }
    exit();
}
?>