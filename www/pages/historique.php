<?php

$titreFormulaire = 'Historique';
$titre = 'WeTransfer';
require_once '../composants/header.php';

// Charger les données des utilisateurs depuis le fichier JSON
$jsonUtilisateurs = file_get_contents("../../donnees/utilisateurs.json");
$utilisateurs = json_decode($jsonUtilisateurs, true);

$jsonFichiers = file_get_contents("../../donnees/fichiers.json");
$tmpFichierTableau = json_decode($jsonFichiers, true);
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


// Suppression du fichier ci-dessous avec une méthode POST afin d'éviter les contraintes et autres, 
// garder en mémoire les id et autres informations et afficher que le fichier a été supprimé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation'])) {

    $email = filter_input(INPUT_POST, "reservation", FILTER_VALIDATE_EMAIL);
    $nomFichier = filter_input(INPUT_POST, "nomFichier");

    // var_dump($fichiersIdTableau);
    foreach ($utilisateurs as $utilisateur) {

        if (in_array($email, $utilisateur)) {

            foreach ($tmpFichierTableau as &$fichier) { // Utilisation de & pour passer par référence

                if ($nomFichier === $fichier['nom_original']) {

                    if(!in_array($utilisateur['id'], $fichier['id_reservation'])){
                    $fichier['id_reservation'][] = $utilisateur['id']; // Utilisation de [] pour ajouter un élément dans le tableau
                    break;
                    }
                }
            }
            break;
        }
    }
    
    // Ne pas oublier de libérer la référence après utilisation
    unset($fichier);
    
    // Réécrire le fichier JSON avec les données mises à jour
    file_put_contents("../../donnees/fichiers.json", json_encode($tmpFichierTableau));
    
    header("Location: ./historique.php");

     exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_fichier'])) {

        $idFichierASupprimer = filter_input(INPUT_POST, "id_fichier");

        // Mettre à jour les données du fichier pour marquer comme supprimé
        foreach ($tmpFichierTableau as &$fichier) {

            if ($fichier['id'] === ($tmpFichierTableau[$idFichierASupprimer]['id'])) {

                $fichier['nom_original'] = "Fichier supprimé";

                break;
            }
            
        }

        // Réécrire le fichier JSON avec les données mises à jour
        file_put_contents("../../donnees/fichiers.json", json_encode($tmpFichierTableau));

        // Redirection pour éviter la réémission du formulaire lors du rechargement de la page
        header("Location: ./historique.php");
        exit;
    }



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
            <td><?php  ?></td>
            <td><?php  ?></td>
            <td>
                    <form method="POST">
                        <label for="reservation">Reservation</label>
                        <input type="email" name="reservation" id="reservation" placeholder="Entrez le mail de la personne">
                        <input type="hidden" name="nomFichier" id="nomFichier" value="<?= $fichier['nom_original'] ?>">
                        <input type="submit" value="ajouter">
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
