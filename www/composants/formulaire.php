<h2><?= $titreFormulaire ?></h2>
<form action="<?= ($titreFormulaire === 'Connexion') ? 'connexion.php' : 'inscription.php' ?>" method="post">

    <label for="email">Adresse email :</label>
    <input type="email" id="email" name="email" required>

    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required>

    <?php if ($titreFormulaire === 'Inscription') : ?>
        <label for="mot_de_passe_confirme">Confirmer le mot de passe :</label>
        <input type="password" id="mot_de_passe_confirme" name="mot_de_passe_confirme" required>
    <?php endif; ?>

    <input type="submit" value="<?= ($titreFormulaire === 'Connexion') ? 'Se connecter' : 'S\'inscrire' ?>">
</form>


<?php 

// Voir pour trouver quelle condition fonctionnerait afin de sortir le bon formulaire

?>