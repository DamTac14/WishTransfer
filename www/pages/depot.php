<?php 


// Titres de manière factorisée
$titreFormulaire = 'Dépôt';
$titre = 'WeTransfer';

require_once '../composants/header.php';

require_once '../composants/donneesRecup.php';

$extensionsValides = array('pdf', 'jpg', 'jpeg', 'png');

require_once '../composants/traitementDepot.php';

?>

    <h2>Envoyer un fichier</h2>

    <form method="POST" enctype="multipart/form-data">

        <label for="fichier">Choisir un fichier</label>
        <input type="file" name="fichier" id="fichier">

        <!-- Ajout des informations pour les utilisateurs sur les fichiers acceptés ainsi que la taille -->
        <p>
            Seuls les fichiers 
                <?php foreach($extensionsValides as $extensions){
                    // Au cas où que des extensions sont supprimées/ajoutées/modifiées, nous ne les notons pas en dur dans le HTML
                    // mais nous les récupérons, faisons une boucle pour les afficher et ajouter une virgule ainsi qu'un espace pour 
                    // une meilleure mise en page
                    echo $extensions.', ';
                }; ?> 
            sont autorisées <br>
            <i>(Taille maximale : 20Mo)</i>
        </p>

        <input type="submit" value="Envoyer le fichier">

        <?php if(isset($erreur)): ?>
            <p>
                Retour serveur : <?= $erreur ?>
            </p>
        <?php endif; ?>

    </form>

<?php

require_once '../composants/footer.php';

?>