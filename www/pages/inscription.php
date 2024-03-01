<?php

$titre = 'WeTransfer';
$titreFormulaire = "Inscription";
require_once '../composants/header.php';

require_once '../composants/formulaireInscriptionConnexion.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $motDePasse = filter_input(INPUT_POST, "mot_de_passe");
    $motDePasseConfirme = filter_input(INPUT_POST, "mot_de_passe_confirme");
    
    $jsonData = file_get_contents("../../donnees/utilisateurs.json");
    $utilisateurs = json_decode($jsonData, true);

    // Variable d'attirbut pour le mail existant
    $mailExistant = "false"; 

    $compteur = count($utilisateurs);

    // Condition de taille de tableau 
    if(count($utilisateurs) >= 1){

        // Boucle pour parcourir le tableau et retrouver les mails 
        foreach($utilisateurs as $utilisateur){
        
        // Condition pour l'email déjà existant et attribuation à la variable mailExistant
            if($utilisateur["email"] === $email){
                $mailExistant = "true";
                echo "mail déjà existant";
            } 
        }  
    } 

    // Vérifie si l'email n'existe pas déjà et si le mot de passe correspond à sa confirmation
    if ($mailExistant === "false" && $motDePasse === $motDePasseConfirme){

        // Hashage du mot de passe pour la sécurité
        $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

        // Création d'un nouvel utilisateur sous forme de tableau
        $nouvelUtilisateur = array(
            "id" => $compteur,
            "email" => $email,
            "mot_de_passe" => $motDePasseHash
        );
        
        // Ajouter le nouvel utilisateur aux données existantes
        $utilisateurs[] = $nouvelUtilisateur;
        
        // Réécrire le fichier JSON avec les nouvelles données
        file_put_contents("../../donnees/utilisateurs.json", json_encode($utilisateurs));
        
        // Utilisé pour l'id unique de l'utilisateur ajouté

        
        // Création d'une session lors de l'inscription
        $_SESSION['connecte'] = true;   
        $_SESSION['id'] = $compteur;  
        
        $compteur++;
        // Redirection vers l'historique
        header('Location: ./historique.php');
        
        // Vérification de la similitude entre les mot de passe
    } else if ($motDePasse !== $motDePasseConfirme) {
        echo "Mot de passe différent vérifie bien";
    }
} 

require_once '../composants/footer.php';

?>




