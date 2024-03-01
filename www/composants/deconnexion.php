<?php 

function deconnexion() {
    // Destruction des donnees $_SESSION et redirection vers la page connexion
    session_unset();
    session_destroy();
    header("Location: ./connexion.php");
    exit;
};

?>