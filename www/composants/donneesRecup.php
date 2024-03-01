<?php

    // Charger les données des utilisateurs depuis le fichier JSON
    $jsonUtilisateurs = file_get_contents("../../donnees/utilisateurs.json");
    $utilisateurs = json_decode($jsonUtilisateurs, true);

    // Charger les données des fichiers depuis le fichier JSON
    $jsonFichiers = file_get_contents("../../donnees/fichiers.json");
    $tmpFichierTableau = json_decode($jsonFichiers, true);

?>