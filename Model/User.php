<?php
require_once('../Config/db.php');


class User{
    private $connection;

    public function __construct() {
        $this->connection = openConnection();
    }

    function register(
            $name,
            $email,
            $password
        ){
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password_hash)VALUES('".$name."','".$email."','".$hashedPassword."')";
    
            return $this->connection->query($sql);
        }

        
    function Login( $email, $password) {
        
    $sql = "SELECT * FROM users WHERE email='".$email."'";

    $result = $this->connection->query($sql);
    $user = $result->fetch_assoc();
    if($user && password_verify($password, $user['password_hash'])){
        return $user;
    }
    return null;
}
}
?>