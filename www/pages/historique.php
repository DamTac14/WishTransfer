<?php 

$titreFormulaire = 'Historique';

$titre = 'WeTransfer';

require_once '../composants/header.php';

// // Charger les données des utilisateurs depuis le fichier JSON
// $jsonUtilisateurs = file_get_contents("../../donnees/utilisateurs.json");
// $utilisateurs = json_decode($jsonUtilisateurs, true);

$jsonFichiers = file_get_contents("../../donnees/fichiers.json");
$tmpFichierTableau = json_decode($jsonFichiers, true);
$fichiersTableau = [];

foreach($tmpFichierTableau as $fichier){

    if($_SESSION['id'] === $fichier['id_utilisateur']){
      array_push($fichiersTableau, $fichier['nom_original']) ;
    };

}
        
?>


<table>
    <thead>
        <tr>
            <th>Nom du fichier</th>
            <th>Téléchargement effectués</th>
            <th>Re-Télécharger</th>
            <th>Supprimer</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($fichiersTableau as $nomOriginal): ?>
        <tr>
            <td><?= $nomOriginal ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
