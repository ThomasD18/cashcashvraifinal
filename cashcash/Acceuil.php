<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "cashcash";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $utilisateur = $_POST['utilisateur'];
    $mdp = $_POST['mdp'];

    // Requête préparée pour récupérer l'utilisateur correspondant aux informations de connexion
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE util = ? AND mdp = ?");
    $stmt->bind_param("ss", $utilisateur, $mdp);

    // Exécution de la requête
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Utilisateur trouvé
        $row = $result->fetch_assoc();
        $role = $row['role'];
        
        // Redirection en fonction du rôle
        if ($role == "assistant") {
            header("Location: index.php");
        } elseif ($role == "technicien") {
            header("Location: Technicien/technicien.php");
        } else {
            echo "Rôle non valide.";
        }
    } else {
        echo "Identifiants incorrects.";
    }

    // Fermeture de la requête
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="utilisateur">Utilisateur:</label><br>
        <input type="text" id="utilisateur" name="utilisateur"><br>
        <label for="mdp">Mot de passe:</label><br>
        <input type="password" id="mdp" name="mdp"><br><br>
        <input type="submit" value="Connexion">
    </form>
</body>
</html>
