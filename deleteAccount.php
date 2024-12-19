<?php 
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php?error=1');
    exit();
}

include_once 'classes/Utilisateur.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php?error=1');
    exit();
}
$user = new Utilisateur();
$user = $user->deleteUser($_SESSION['user']['id']);
session_unset();
header("Location: index.php");