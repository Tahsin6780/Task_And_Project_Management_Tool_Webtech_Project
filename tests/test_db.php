<?php

require_once __DIR__ . "/../config/database.php";

if ($pdo) {
    echo "Database Connected Successfully ✅";
} else {
    echo "Database Connection Failed ❌";
}

?>