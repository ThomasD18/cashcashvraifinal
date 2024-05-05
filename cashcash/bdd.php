<?php

// Définition de la fonction pour établir une connexion à la base de données
function connectToDatabase() {
    // Définition des informations de connexion à la base de données
    $serveur = "localhost"; // Nom du serveur de base de données
    $utilisateur = "root"; // Nom d'utilisateur de la base de données
    $motDePasse = "root"; // Mot de passe de la base de données
    $nomBaseDeDonnees = "cashcash"; // Nom de la base de données à laquelle se connecter

    // Établissement de la connexion à la base de données en utilisant les informations de connexion
    $conn = mysqli_connect($serveur, $utilisateur, $motDePasse, $nomBaseDeDonnees);

    // Vérification si la connexion a réussi
    if (!$conn) {
        // Affichage d'un message d'erreur et arrêt du script si la connexion a échoué
        die("Échec de la connexion à la base de données : " . mysqli_connect_error());
    }

    // Renvoi de l'objet de connexion à la base de données
    return $conn;
}
?>

