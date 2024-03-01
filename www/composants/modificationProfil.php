<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire
    $email = filter_input(INPUT_POST, "email");
    $motDePasse = filter_input(INPUT_POST, "mot_de_passe");
    $nouveauMotDePasse = filter_input(INPUT_POST, "nouveau_mot_de_passe");
    $motDePasseConfirme = filter_input(INPUT_POST, "mot_de_passe_confirme");


    // Condition de vérification de mot de passe
    if (password_verify($motDePasse, $utilisateurs[$_SESSION['id']]["mot_de_passe"])) {   

        if($email !== $utilisateurs[$_SESSION['id']]["email"]){

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
        } 
    

        if($mailExistant === "false" && $nouveauMotDePasse === $motDePasseConfirme) {
            $motDePasseHash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
            
            $utilisateurs[$_SESSION['id']]["email"] = $email;
            $utilisateurs[$_SESSION['id']]["mot_de_passe"] = $motDePasseHash;
            
            file_put_contents("../../donnees/utilisateurs.json", json_encode($utilisateurs));

            echo "Les informations ont été modifié avec succès.";
        } else {

            http_response_code(400);

            echo "Les nouveaux mots de passe ne correspondent pas.";
        }
    } else {

        http_response_code(401);
        
        echo "Mot de passe incorrect.";
    }
}

?>