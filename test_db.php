<?php

require_once "config/database.php";

if ($pdo) {
    echo "Database Connected Successfully ✅";
} else {
    echo "Database Connection Failed ❌";
}

?>