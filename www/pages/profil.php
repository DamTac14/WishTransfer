<?php 

// Titres de manière factorisée
$titreFormulaire = 'Profil';
$titre = 'WeTransfer';

require_once '../composants/header.php';


require_once '../composants/donneesRecup.php';

$mailExistant = "false";

?>


<h2><?= $titreFormulaire ?></h2>

<form action="" method="POST">

    <label for="email">Nouvelle adresse email :</label>
    <input type="email" id="email" name="email" value="<?= $utilisateurs[$_SESSION['id']]["email"] ?>" required>

    <label for="mot_de_passe">Mot de passe actuel :</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required>

    <label for="nouveau_mot_de_passe">Nouveau mot de passe :</label>
    <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required>

    <label for="mot_de_passe_confirme">Confirmer le nouveau mot de passe :</label>
    <input type="password" id="mot_de_passe_confirme" name="mot_de_passe_confirme" required>

    <input type="submit" value="Modifier">
</form>



<?php

require_once '../composants/modificationProfil.php';

require_once '../composants/footer.php';
?>