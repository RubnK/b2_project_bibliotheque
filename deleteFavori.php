<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php?error=1');
    exit();
}

include_once 'classes/Favoris.php';
if (isset($_GET['id'])) {
    $favori = new Favoris();
    $favori->deleteFavoris($_GET['id']);
    header('Location: dashboard.php');
    exit();
}else{
    header('Location: index.php');
    exit();
}