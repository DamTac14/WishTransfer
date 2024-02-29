<?php

$titreFormulaire = 'Connexion';

$titre = 'WeTransfer';



require_once '../composants/header.php';

require_once '../composants/formulaire.php';


// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupérer les données du formulaire
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $motDePasse = filter_input(INPUT_POST, "mot_de_passe");

    // Charger les données des utilisateurs depuis le fichier JSON
    $jsonData = file_get_contents("../../donnees/utilisateurs.json");
    $utilisateurs = json_decode($jsonData, true);

    // Rechercher l'utilisateur correspondant dans la liste des utilisateurs
    foreach ($utilisateurs as $utilisateur) {
    
        if ($utilisateur["email"] === $email) {

            // L'utilisateur a été trouvé, vérifier si le mot de passe correspond
            if (password_verify($motDePasse, $utilisateur["mot_de_passe"])) {
                
                $_SESSION['connecte'] = true;     
                $_SESSION['id'] = $utilisateur['id'];   
  
                header('Location: ./historique.php');

                break; 

                // Condition de si le mot de passe est différent
            } else if (!password_verify($motDePasse, $utilisateur["mot_de_passe"])) {

                http_response_code(401);
                // Le mot de passe ne correspond pas
                echo "Mot de passe incorrect.";
            }
        } else {

            http_response_code(401);
            // Le mail est inexistant
            echo "Mail incorrect.";

        }
    }
}

?>
