<?php
session_start();
include_once 'classes/Livre.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php?error=1');
    exit();
}
if (isset($_GET['id'])) {
    $livre = new Livre();
    $livre->deleteLivre($_GET['id']);
    header('Location: dashboard.php');
    exit();
} else {
    header('Location: dashboard.php');
    exit();
}