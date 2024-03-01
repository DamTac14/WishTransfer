<?php

// Suppression du fichier ci-dessous avec une méthode POST afin d'éviter les contraintes et autres, 
// garder en mémoire les id et autres informations et afficher que le fichier a été supprimé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation'])) {

    $email = filter_input(INPUT_POST, "reservation", FILTER_VALIDATE_EMAIL);
    $nomFichier = filter_input(INPUT_POST, "nomFichier");

    foreach ($utilisateurs as $utilisateur) {

        if (in_array($email, $utilisateur)) {

            foreach ($tmpFichierTableau as &$fichier) { // Utilisation de & pour passer par référence

                if ($nomFichier === $fichier['nom_original']) {

                    if(!in_array($utilisateur['id'], $fichier['id_reservation']) && $utilisateur['id'] != $_SESSION['id']){

                    // On attribut l'id de l'utilisateur auquel on autorise la réservation et l'accès d'un fichier
                    $fichier['id_reservation'][] = $utilisateur['id']; 
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
    
    // Redirection pour mettre à jour les valeurs
    header("Location: ./historique.php");

     exit;
}
?>