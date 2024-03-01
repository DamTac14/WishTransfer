<?php

$titreFormulaire = 'Historique';
$titre = 'WeTransfer';

require_once '../composants/header.php';


require_once '../composants/donneesRecup.php';

$fichiersTableau = [];
$fichiersIdTableau = [];


foreach ($tmpFichierTableau as $fichier) {
    if ($_SESSION['id'] === $fichier['id_utilisateur']) {
        $fichiersTableau[] = $fichier; 
    }

    if (in_array($_SESSION['id'], $fichier['id_reservation'])) {
        $fichiersTableau[] = $fichier; // Ajoute l'ensemble des informations sur le fichier
    }

}

// Code utile à la réservation
require_once '../composants/reservation.php';

// Code utile à la suppression
require_once '../composants/suppression.php';

// Code utile au téléchargement
require_once '../composants/telechargement.php';


?>

<h2>Historique</h2>

<table>
    <thead>
        <tr>
            <th>Nom du fichier</th>
            <th>Téléchargement effectués</th>
            <th>Re-Télécharger</th>
            <th>Réservation téléchargement</th>
            <th>Supprimer</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($fichiersTableau as $fichier) : ?>
            <?php if ($fichier['nom_original'] !== 'Fichier supprimé'): ?>
                <tr>
                        <td>
                            <?= $fichier['nom_original'] ?>
                        </td>
                        <td>
                            <?= $fichier['compteur_telechargement']  ?>
                        </td>
                        <td>    
                            <form action="" method="POST">
                                <input type="hidden" name="id_fichier_retelecharger" value="<?= $fichier['id'] ?>">
                                <button type="submit" name="telecharger">Télécharger le fichier</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST">
                                <label for="reservation">Reservation</label>
                                <input type="email" name="reservation" id="reservation" placeholder="Entrez le mail de la personne">
                                <input type="hidden" name="nomFichier" id="nomFichier" value="<?= $fichier['nom_original'] ?>">
                                <input type="submit" value="Autoriser utilisateur">
                            </form>
                        </td>
                    <?php if (!in_array($_SESSION['id'], $fichier['id_reservation'])) : ?>
                        <td>
                            <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?');">
                                <input type="hidden" name="id_fichier" value="<?= $fichier['id'] ?>">
                                <button type="submit" name="supprimer_fichier">Supprimer</button>
                            </form>
                        </td>
                    <?php else : ?>
                        <td></td>
                    <?php endif; ?>
                </tr>
            <?php endif; ?>
        <?php endforeach ?>
    </tbody>
</table>

<?php

require_once '../composants/footer.php';

?>