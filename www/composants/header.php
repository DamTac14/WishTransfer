<?php 

session_start();

require_once '../composants/deconnexion.php';

    if (isset($_POST['deconnexion'])) {
        // Appeler la fonction de déconnexion
        deconnexion();
    }

?>


<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="../style/style.css" rel="stylesheet">
            <title><?= $titre ?></title>
        </head>
<body>

<h1>WishTransfer</h1>

<?php if(isset($_SESSION["connecte"])): ?>
    <nav>
        <form method="post" action="">
            <button type="submit" name="deconnexion">Déconnexion</button>
        </form>
        <ul>
            <li><a href="./depot.php">Dépôt</a></li>
            <li><a href="./profil.php">Profil</a></li>
            <li><a href="./historique.php">Historique</a></li>
        </ul>
    </nav>

<?php else : ?>

<nav>
    <ul>
        <li><a href="./connexion.php">Connexion</a></li>
        <li><a href="./inscription.php">Inscription</a></li>
    </ul>
</nav>

<?php endif; ?>