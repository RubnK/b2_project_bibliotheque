<?php
session_start();
include_once 'classes/Utilisateur.php';
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $error = 'Vous devez être connecté pour effectuer cette action.';
}
if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = new Utilisateur();
    $user = $user->login($_POST['username'], $_POST['password']);
    if ($user) {
        $_SESSION['user'] = array('id' => $user['id'], 'username' => $user['nom'], 'email' => $user['email']);
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Identifiants incorrects.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <label for="username">Nom d'utilisateur ou email</label>
        <input type="text" name="username" id="username" required><br>
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" required><br>
        <button type="submit">Connexion</button>
    </form>
    <p>Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous</a> ! </p>
    <a href="index.php">Accueil</a>
</body>