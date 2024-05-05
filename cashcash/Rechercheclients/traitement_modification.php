<?php
// Inclusion du fichier de connexion à la base de données
include_once("../bdd.php");
// Connexion à la base de données
$conn = connectToDatabase();

// Vérification si le formulaire a été soumis en méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Échappement des données reçues du formulaire pour éviter les injections SQL
    $clientId = mysqli_real_escape_string($conn, $_POST['client_id']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $raison_sociale = mysqli_real_escape_string($conn, $_POST['raison_sociale']);
    $siren = mysqli_real_escape_string($conn, $_POST['siren']);
    $ape = mysqli_real_escape_string($conn, $_POST['ape']);
    $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Construction de la requête SQL pour mettre à jour les données du client
    $query = "UPDATE client SET Nom='$nom', raison_sociale='$raison_sociale', SIREN='$siren', APE='$ape', Adresse='$adresse', Telephone='$telephone', email='$email' WHERE id='$clientId'";
    
    // Exécution de la requête SQL
    $result = mysqli_query($conn, $query);

    // Vérification si la requête a été exécutée avec succès
    if ($result) {
        // Démarrage de la session pour stocker le message de succès
        session_start();
        // Stockage du message de succès dans la session
        $_SESSION['success_message'] = "La modification a été effectuée avec succès.";

        // Redirection vers la page de recherche des clients après la modification
        header("Location: Rechercherdesclients.php");
        exit(); // Arrêt de l'exécution du script pour éviter toute sortie supplémentaire
    } else {
        // Affichage d'un message d'erreur en cas d'échec de la mise à jour
        echo "La mise à jour a échoué : " . mysqli_error($conn);
    }
} else {
    // Affichage d'un message d'erreur si la méthode de requête n'est pas autorisée
    echo "Méthode de requête non autorisée.";
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>
