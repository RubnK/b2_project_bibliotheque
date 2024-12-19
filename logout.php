<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php?error=1');
    exit();
}

session_unset();
header("Location: index.php");
exit();
?>