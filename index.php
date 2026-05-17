
<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: View/onboarding.php');
} else {
    header('Location: View/login.php');
}
exit();
?>