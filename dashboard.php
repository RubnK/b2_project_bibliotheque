<?php
session_start();
include_once 'classes/Utilisateur.php';
include_once 'classes/Favoris.php';
include_once 'classes/Livre.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php?error=1');
    exit();
}
$userModel = new Utilisateur();
$favorisModel = new Favoris();
$livreModel = new Livre();

$user = $userModel->getUserByUsername($_SESSION['user']['username']);
if(!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'L\'adresse email n\'est pas valide.';
    } else if (isset($userModel->getUserByUsername($_POST['username'])['nom']) && $user['nom'] !== $_POST['username']) {
        $error = 'Ce nom d\'utilisateur est déjà pris.';
    } else if ($userModel->checkPassword($_SESSION['user']['id'], $_POST['password']) === false) {
        $error = 'Mot de passe incorrect.';
    } else if ($_POST['newpass'] !== $_POST['newpass2']) {
        $error = 'Les mots de passe ne correspondent pas.';
    } else { 
        if (empty($_POST['newpass']) && empty($_POST['newpass2'])) {
            $_POST['newpass'] = $_POST['password'];
        } else if (strlen($_POST['newpass']) < 8) {
            $error = 'Le mot de passe doit contenir au moins 8 caractères.';
        }
        $userModel->updateUser($_SESSION['user']['id'], $_POST['username'], $_POST['email'], $_POST['newpass']);
        $_SESSION['user'] = array('id' => $user['id'], 'username' => $_POST['username'], 'email' => $_POST['email']);
    }
} else if ($_SERVER['REQUEST_METHOD']=='POST') {
    $error = 'Veuillez remplir tous les champs.';
}

if(isset($_GET['search'])){
    $livres = $livreModel->getLivres($_GET['search']);
} else {
    $livres = $livreModel->getLivres();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
</head>
<body>
    <h1>Tableau de Bord</h1>
    <p>Bienvenue, <?= $_SESSION['user']['username'] ?> !</p>
    <p>Votre email : <?= $_SESSION['user']['email'] ?></p>
    <a href="index.php">Accueil</a>
    <a href="logout.php">Déconnexion</a>
    <h2 style="cursor: pointer;" onclick="showHide('favoris')">Mes favoris ></h2>
    <div id="favoris" style="display: none;">
        <?php if(empty($favorisModel->getFavoris($_SESSION['user']['id']))): ?>
            <p><i>Aucun favori disponible.</i></p>
        <?php else: ?>
        <p>J'ai <?= count($favorisModel->getFavoris()) ?> livres en favoris.</p>
        <ul>
            <?php foreach ($favorisModel->getFavoris() as $favori): ?>
                <li>
                    <h2><?= $livreModel->getLivre($favori['livre_id'])['titre'] ?></h2>
                    <p><?= $livreModel->getLivre($favori['livre_id'])['auteur'] ?></p>
                    <a href="deleteFavori.php?id=<?= $favori['livre_id'] ?>">Supprimer des favoris</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
    <h2 style="cursor: pointer;" onclick="showHide('addBook')">Ajouter un livre ></h2>
    <div id="addBook" style="display: none;">
        <form action="addBook.php" method="post">
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" required><br>
            <label for="auteur">Auteur</label>
            <input type="text" name="auteur" id="auteur" required><br>
            <button type="submit">Ajouter</button>
        </form>
    </div>
    <h2 style="cursor: pointer;" onclick="showHide('availableBooks')">Tous les livres disponibles ></h2>
    <div id="availableBooks" style="display: none;">
        <form action="dashboard.php" method="get">
            <input type="text" name="search" <?php if(isset($_GET['search'])){echo("value='".$_GET['search']."'");} ?> placeholder="Rechercher un livre">
            <button type="submit">Rechercher</button>
        </form>
        <ul>
            <?php if(empty($livres)): ?>
                <p><i>Aucun livre disponible.</i></p>
            <?php else: ?>
                <?php foreach ($livres as $livre): ?>
                    <li>
                        <h2><?= $livre['titre'] ?></h2>
                        <p><?= $livre['auteur'] ?></p>
                        <?php if (in_array($livre['id'], array_column($favorisModel->getFavoris(), 'livre_id'))): ?>
                            <p style="color: green;">Déjà en favoris</p>
                        <?php else: ?>
                            <a href="addFavori.php?id=<?= $livre['id'] ?>">Ajouter aux favoris</a>
                        <?php endif; ?>
                        <?php if ($livre['utilisateur_id'] == $_SESSION['user']['id']): ?>
                            <a href="deleteBook.php?id=<?= $livre['id'] ?>">Supprimer</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>  
    <h2 style="cursor: pointer;" onclick="showHide('accountGestion')">Gestion de compte ></h2>
    <div id="accountGestion" style="display: none;">
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form action="dashboard.php" method="post">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" name="username" id="username" value=<?= $_SESSION['user']['username'] ?> required><br>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value=<?= $_SESSION['user']['email'] ?> required><br>
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" required><br>
        <label for="newpass">Nouveau mot de passe</label>
        <input type="password" name="newpass" id="newpass"><br>
        <label for="newpass2">Confirmer le nouveau mot de passe</label>
        <input type="password" name="newpass2" id="newpass2"><br>
        <button type="submit">Modifier</button>
    </form>
    <br>
    <a href="deleteAccount.php">⚠️ Supprimer mon compte ⚠️</a>
    </div>
</body>
<script>
    function showHide(id) {
        const element = document.getElementById(id);
        if (element.style.display === "none" || element.style.display === "") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }
</script>