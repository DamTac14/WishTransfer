<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Déclarations de nos variables qui seront utilisées dans nos conditions et le reste de notre code

    $fichier = $_FILES['fichier'];
    $erreur = "";

    // Les deux lignes ci-dessous permettent d'aller chercher le tout dernier point dans le nom du fichier
    // au cas où qu'il y en ait d'autres, et ainsi trouver l'extension
    $parties = explode('.', $fichier['name']); 
    $extension = strtolower(end($parties)); 

    // Tableau des extensions que l'on valide lors du téléchargement
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

                    // Chemin du dossier récupéré et utilisation d'explode qui permet de stocker l'id utilisateur pour avoir une meilleure gestion
                    // Des dossiers générés de manière hashé avec les mails
                    $cheminDossier = '../../img/' .  $nomDuDossier;
                    $idUtilisateurARecuperer = explode('_',$nomDuDossier);

                        // Vérifie si le dossier n'existe pas déjà et n'est pas un fichier
                        if (!file_exists($cheminDossier) && !is_dir($cheminDossier)) {
                            // Crée le dossier avec les permissions 0777 et en créant les répertoires parents si nécessaire
                            if (mkdir($cheminDossier, 0777, true)) { 
                                echo "Le dossier a été créé avec succès.";
                            } else {
                                echo "Erreur lors de la création du dossier.";
                            }
                        }
                    
                    // Conception du fichier avec le chemin, du nom et de son extension
                    $cheminDestination = $cheminDossier  .'/' . uniqid() . '.' . $extension; 

                    // Déplacer le fichier téléchargé vers la destination
                    move_uploaded_file($fichier['tmp_name'], $cheminDestination);

                    // Création de la structure de données pour les informations du fichier
                    $infosFichier = array(
                        'id' => $compteur,
                        'nom_original' => $nomOrigine,
                        'chemin_destination' => $cheminDestination,
                        'id_utilisateur' => $utilisateurs[$_SESSION['id']]['id'],
                        'compteur_telechargement' => 0,
                        'id_reservation' => []
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