<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_fichier'])) {

    $idFichierASupprimer = filter_input(INPUT_POST, "id_fichier");

    // Mettre à jour les données du fichier pour marquer comme supprimé
    foreach ($tmpFichierTableau as &$fichier) {

        // Vérification des id pour être sûr de choisir le bon fichier à supprimer

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