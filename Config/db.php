<?php
class mydb {

    public function createConObject() {

        $servername = "localhost";
        $username   = "root";
        $password   = "";
        $dbname     = "pet-daycare";   

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Database Connection Failed: " . $conn->connect_error);
        }

        return $conn;
    }
}

function getConnection() {
    $db = new mydb();
    return $db->createConObject();
}
?>

