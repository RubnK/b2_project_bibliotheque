<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php?error=1');
    exit();
}
include_once 'classes/Livre.php';
if (isset($_POST['titre']) && isset($_POST['auteur']) && $_SESSION['user']) {
    $livre = new Livre();
    $livre->addLivre($_POST['titre'], $_POST['auteur']);
    header('Location: index.php');
    exit();
}else{
    header('Location: dashboard.php');
    exit();
}