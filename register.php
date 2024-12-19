<?php
session_start();
include_once 'classes/Utilisateur.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}

$user = new Utilisateur();

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password2'])) {
    if ($_POST['password'] !== $_POST['password2']) {
        $error = 'Les mots de passe ne correspondent pas.';
    } else if (strlen($_POST['password']) < 8) {
        $error = 'Le mot de passe doit contenir au moins 8 caractères.';
    }
    else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'L\'adresse email n\'est pas valide.';
    } else if (isset($user->getUserByUsername($_POST['username'])['nom'])) {
        $error = 'Ce nom d\'utilisateur est déjà pris.';
    } 
    else {
        $user = $user->register($_POST['username'], $_POST['email'], $_POST['password']);
        if ($user) {
            header('Location: login.php');
            exit();
        } else {
            $error = 'Erreur lors de l\'inscription.';
        }
    }
} else if ($_SERVER['REQUEST_METHOD']=='POST') {
    $error = 'Veuillez remplir tous les champs.';
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form action="register.php" method="post">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" name="username" id="username" <?php if(isset($_POST['username'])){echo "value=".$_POST['username'];} ?> required><br>
        <label for="email">Email</label>
        <input type="text" name="email" id="email" <?php if(isset($_POST['email'])){echo "value=".$_POST['email'];} ?> required><br>
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" required><br>
        <label for="password2">Confirmer le mot de passe</label>
        <input type="password" name="password2" id="password2" required><br>
        <button type="submit">Inscription</button>
    </form>
    <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous</a> ! </p>
    <a href="index.php">Accueil</a>
</body>
