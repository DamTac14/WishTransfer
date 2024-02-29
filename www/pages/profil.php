<?php 

// Titres de manière factorisée
$titreFormulaire = 'Profil';
$titre = 'WeTransfer';

require_once '../composants/header.php';


// Charger les données des utilisateurs depuis le fichier JSON
$jsonData = file_get_contents("../../donnees/utilisateurs.json");
$utilisateurs = json_decode($jsonData, true);
$mailExistant = "false";

?>



<form action="" method="POST">
    <h2><?= $titreFormulaire ?></h2>

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
        } } 
    

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