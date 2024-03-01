<?php 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['telecharger'])) {

    foreach ($tmpFichierTableau as &$fichier) {
        // Récupération des données pour le chemin et le nom
        $cheminDestination = $fichier['chemin_destination'];
        $nomDuFichierDl = $fichier['nom_original'];

        // Condition de vérification que les id correspondent
        if (file_exists($cheminDestination) && $fichier['id'] == filter_input(INPUT_POST, 'id_fichier_retelecharger')) {
            
            // Indique que le contenu est destiné à être transféré en tant que fichier
            header('Content-Description: File Transfer');

            // Définit le type de contenu à "application/octet-stream"
            header('Content-Type: application/octet-stream');

            // Définit le nom du fichier à utiliser lors du téléchargement, en utilisant le nom original du fichier
            header('Content-Disposition: attachment; filename='.basename($nomDuFichierDl));

            // Indique que le contenu est encodé en binaire
            header('Content-Transfer-Encoding: binary');

            // Indique que le contenu expire immédiatement après le téléchargement
            header('Expires: 0');

            // Indique que le cache doit être validé à chaque fois avant utilisation
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

            // Indique que la réponse peut être mise en cache de manière publique
            header('Pragma: public');

            // Définit la longueur du contenu à télécharger, en utilisant la taille du fichier
            header('Content-Length: ' . filesize($cheminDestination));

            
            // Les deux fonctions PHP utilisées ci-dessous aident au nettoyage et au filtrage des tampons afin de pouvoir
            // Récupérer le contenu de nos fichiers. Après plusieurs essais, sans ces deux fonctions nous pouvons télécharger le fichier
            // Mais nous n'avions pas de contenu 
            
            ob_clean();
            flush();
            readfile($cheminDestination);
        }
    }


        // Recherche du fichier dans le tableau et mise à jour du compteur de téléchargement
        foreach ($tmpFichierTableau as &$fichier) {

            if (file_exists($fichier['chemin_destination']) && $fichier['id'] == filter_input(INPUT_POST, 'id_fichier_retelecharger')) {
                
                $fichier['compteur_telechargement']++; // Incrémentation du compteur de téléchargement
                break; // Sortir de la boucle après avoir trouvé le fichier

            }
            
        }

        // Écriture des données mises à jour dans le fichier JSON
            $json_encoded = json_encode($tmpFichierTableau);

            file_put_contents("../../donnees/fichiers.json", $json_encoded);

}


?>