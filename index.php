<?php 
session_start();
include_once 'classes/Livre.php';
include_once 'classes/Favoris.php';

$livreModel = new Livre();
$favorisModel = new Favoris();
if(isset($_GET['search'])){
    $livres = $livreModel->getLivres($_GET['search']);
} else {
    $livres = $livreModel->getLivres();
}

$livresParPage = 3;
$totalPages = ceil(count($livres) / $livresParPage);
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$indexDebut = ($pageActuelle - 1) * $livresParPage;

$livres = array_slice($livres, $indexDebut, $livresParPage);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <h1>Accueil</h1>
    <?php if (isset($_SESSION['user'])): ?>
        <p>Bienvenue, <?= $_SESSION['user']['username'] ?> !</p>
        <a href="dashboard.php">Tableau de Bord</a>
    <?php else: ?>
        <a href="login.php">Connexion</a>
        <a href="register.php">Inscription</a>
    <?php endif; ?>
    <h2>Livres</h2>
    <form action="index.php" method="get">
        <input type="text" name="search" <?php if(isset($_GET['search'])){echo("value='".$_GET['search']."'");} ?> placeholder="Rechercher un livre">
        <button type="submit">Rechercher</button>
    </form>
    <?php if(empty($livres)): ?>
        <p><i>Aucun livre disponible.</i></p>
    <?php else: ?>
    <ul>
        <?php foreach ($livres as $livre): ?>
            <li>
                <h2><?= $livre['titre'] ?></h2>
                <p><?= $livre['auteur'] ?></p>
                <?php if (isset($_SESSION['user']) && in_array($livre['id'], array_column($favorisModel->getFavoris(), 'livre_id'))): ?>
                    <p style="color: green;">Déjà en favoris</p>
                <?php else: ?>
                    <a href="addFavori.php?id=<?= $livre['id'] ?>">Ajouter aux favoris</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="pagination">
        <?php if ($pageActuelle > 1): ?>
            <a href="?page=<?= $pageActuelle - 1 ?>&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">&laquo; Précédent</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" <?= $i == $pageActuelle ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($pageActuelle < $totalPages): ?>
            <a href="?page=<?= $pageActuelle + 1 ?>&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">Suivant &raquo;</a>
        <?php endif; ?>
    </div>
    
    <?php endif; ?>
</body>