<?php
// Inclusion du fichier de connexion à la base de données
include_once("../bdd.php");

// Démarrage de la session PHP
session_start();

// Fonction pour afficher les messages flash
function displayFlashMessage() {
    // Vérification de l'existence d'un message flash dans la session
    if (isset($_SESSION['flash_message'])) {
        // Affichage du message flash avec un style vert
        echo "<p style='color: green;'>" . $_SESSION['flash_message'] . "</p>";

        // Suppression du message flash de la session après l'affichage
        unset($_SESSION['flash_message']);
    }
}

// Connexion à la base de données
$conn = connectToDatabase();

// Vérification de la présence de l'identifiant du client dans l'URL
if (isset($_GET['id'])) {
    $clientId = $_GET['id'];

    // Requête SQL pour récupérer les détails du client en fonction de son identifiant
    $query = "SELECT * FROM client WHERE id = '$clientId'";
    
    // Exécution de la requête SQL
    $result = mysqli_query($conn, $query);

    // Vérification de la réussite de l'exécution de la requête SQL
    if ($result !== false) {
        // Récupération de la première ligne de résultat sous forme de tableau associatif
        $row = mysqli_fetch_assoc($result);

        // Vérification de l'existence de données pour le client avec l'identifiant donné
        if ($row !== null) {
            ?>
            <!-- Début de la structure HTML -->
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="../style.css"> 
                <title>Modifier Client</title>
                <!-- Styles CSS intégrés -->
                <style>
                    /* Styles CSS pour la mise en page */
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f4f4f4;
                    }
                    .container {
                        max-width: 600px;
                        margin: 20px auto;
                        padding: 20px;
                        background-color: #fff;
                        border-radius: 5px;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    }
                    h1 {
                        text-align: center;
                        color: #333;
                        margin-bottom: 20px;
                    }
                    form {
                        margin-top: 20px;
                    }
                    label {
                        display: block;
                        margin-bottom: 5px;
                        color: #333;
                    }
                    input[type="text"] {
                        width: calc(100% - 22px);
                        padding: 10px;
                        margin-bottom: 15px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        box-sizing: border-box;
                    }
                    button[type="submit"] {
                        background-color: #007bff;
                        color: #fff;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        cursor: pointer;
                        transition: background-color 0.3s ease;
                        margin-top: 15px;
                        width: 100%;
                    }
                    button[type="submit"]:hover {
                        background-color: #0056b3;
                    }
                    p.error-message {
                        color: red;
                        margin-top: 10px;
                    }
                </style>
            </head>
            <body>

                <div class="container">
                    <h1>Modifier Client</h1>

                    <?php displayFlashMessage(); ?>

                    <!-- Formulaire de modification des données client avec des champs pré-remplis -->
                    <form method="POST" action="traitement_modification.php">
                        <!-- Champs du formulaire avec les valeurs pré-remplies -->
                    </form>
                </div>

            </body>
            </html>
            <!-- Fin de la structure HTML -->
            <?php
        } else {
            // Message affiché si aucun client n'est trouvé avec l'identifiant donné
            echo "Aucun client trouvé avec cet identifiant.";
        }
    } else {
        // Affichage d'une erreur si la requête SQL échoue
        die("La requête a échoué : " . mysqli_error($conn));
    }
} else {
    // Message affiché si l'identifiant du client n'est pas spécifié dans l'URL
    echo "Identifiant du client non spécifié.";
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>
