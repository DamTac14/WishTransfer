<?php 


// Titres de manière factorisée
$titreFormulaire = 'Téléchargement';
$titre = 'WeTransfer';

require_once '../composants/header.php';


// Charger les données des utilisateurs depuis le fichier JSON
$jsonUtilisateurs = file_get_contents("../../donnees/utilisateurs.json");
$utilisateurs = json_decode($jsonUtilisateurs, true);

$jsonFichiers = file_get_contents("../../donnees/fichiers.json");
$fichiersTableau = json_decode($jsonFichiers, true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Déclarations de nos variables qui seront utilisées dans nos conditions et le reste de notre code

    $fichier = $_FILES['fichier'];
    $erreur = "";
    $parties = explode('.', $fichier['name']); 
    $extension = strtolower(end($parties)); 
    $extensionsValides = array('pdf', 'jpg', 'jpeg', 'png');
    $compteur = count($fichiersTableau);
    

    if($fichier['error'] == UPLOAD_ERR_OK) { 

        // Vérification de l'extension qui est dans le tableau en la comparant à celle ajoutée
        if(in_array($extension,$extensionsValides)) {

            $finfo = finfo_open(FILEINFO_MIME);

            $info = finfo_file($finfo, $fichier['tmp_name']);

            if(str_contains($info, 'application/pdf') || strpos($info, 'image/') === 0) {

                $taille = filesize($fichier['tmp_name']);
                
                if($taille < 20000000) {

                    $emailHashe = hash('sha256', $utilisateurs[$_SESSION['id']]["email"]);
                    
                    $nomOrigine = $fichier['name']; 
                    $nomDuDossier = $utilisateurs[$_SESSION['id']]['id'] . '_' . $emailHashe;

                    $cheminDossier = '../../img/' .  $nomDuDossier;
                    $idUtilisateurARecuperer = explode('_',$nomDuDossier); 

                    if (!file_exists($cheminDossier) && !is_dir($cheminDossier)) {
                        
                        if (mkdir($cheminDossier, 0777, true)) { 
                            echo "Le dossier a été créé avec succès.";
                        } else {
                            echo "Erreur lors de la création du dossier.";
                        }
                    }
                        $cheminDestination = $cheminDossier  .'/' . uniqid() . '.' . $extension; 

                        // Déplacer le fichier téléchargé vers la destination
                        move_uploaded_file($fichier['tmp_name'], $cheminDestination);

                        // Création de la structure de données pour les informations du fichier
                        $infosFichier = array(
                            'id' => $compteur,
                            'nom_original' => $nomOrigine,
                            'id_utilisateur' => $utilisateurs[$_SESSION['id']]['id'],
                            'chemin_destination' => $cheminDestination
                        );

                        // Charger le fichier JSON existant s'il existe, sinon initialiser un tableau vide
                        $fichiersEnregistres = json_decode(file_get_contents('../../donnees/fichiers.json'), true);

                        // Ajouter les informations du fichier actuel à la liste des fichiers enregistrés
                        $fichiersEnregistres[] = $infosFichier;

                        // Enregistrer la liste mise à jour dans le fichier JSON
                        file_put_contents('../../donnees/fichiers.json', json_encode($fichiersEnregistres));

                        // Utilisé pour l'id unique du fichier téléchargé
                        $compteur++;

                        $erreur = "Fichier envoyé correctement";
                        
                    } else {
                        $erreur = "Aie ! Le fichier est trop volumineux";
                    }
                } else {
                    $erreur = "Je t'ai vu, pirate !";
                }

            } else {
                $erreur = "Ce n'est pas un fichier PDF";
            }

        } else {
            $erreur = "Oups";
        }

        
}
?>


    <h2>Envoyer un fichier</h2>

    <form method="POST" enctype="multipart/form-data">

        <label for="fichier">Choisir un fichier</label>
        <input type="file" name="fichier" id="fichier">

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