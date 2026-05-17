<?php

    function openConnection(){
        $db_host = "127.0.0.1"; 
        $db_user = "root";
        $db_password = "";
        $db_name = "project_management_db";

        $connection = new mysqli($db_host,$db_user, $db_password, $db_name);
        if($connection->connect_error){
            die("Could not connect to the database- ". $connection->connect_error);
        }

    return $connection;
    }
?>