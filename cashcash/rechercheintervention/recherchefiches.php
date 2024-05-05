<?php 
// Inclusion du fichier index.php
include_once("../index.php"); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css"> <!-- Lien vers le fichier CSS -->
    <title>Recherche Interventions</title>
    <style>
        /* Styles CSS spécifiques à cette page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
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
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Recherche Interventions</h1>

    <!-- Formulaire de recherche d'interventions -->
    <form method="POST" action="">
        <label for="searchByDate">Rechercher par date :</label>
        <input type="text" name="searchByDate" placeholder="Date (Format : YYYY-MM-DD)">

        <label for="searchByNumIntervention">Rechercher par numéro d'intervention :</label>
        <input type="text" name="searchByNumIntervention" placeholder="Numéro d'intervention">

        <button type="submit">Rechercher</button>
    </form>

    <?php
    // Inclusion du fichier de connexion à la base de données
    include_once("../bdd.php");
    
    // Connexion à la base de données
    $conn = connectToDatabase();

    // Affichage du message de succès s'il existe dans la session
    if (isset($_SESSION['success_message'])) {
        echo '<div style="color: green; font-weight: bold;">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']); // Suppression du message de succès de la session
    }

    // Traitement du formulaire de recherche en méthode POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des valeurs des champs de recherche et échappement pour éviter les injections SQL
        $searchByDate = isset($_POST['searchByDate']) ? mysqli_real_escape_string($conn, $_POST['searchByDate']) : '';
        $searchByNumIntervention = isset($_POST['searchByNumIntervention']) ? mysqli_real_escape_string($conn, $_POST['searchByNumIntervention']) : '';

        // Construction de la clause WHERE en fonction des valeurs des champs de recherche
        $whereClause = '';
        if (!empty($searchByDate)) {
            $whereClause .= "Date_intervention LIKE '%$searchByDate%'";
        }
        if (!empty($searchByNumIntervention)) {
            $whereClause .= ($whereClause != '' ? ' AND ' : '') . "Num_intervention = '$searchByNumIntervention'";
        }

        // Construction de la requête SQL avec la clause WHERE si nécessaire
        $query = "SELECT * FROM intervention";
        if ($whereClause != '') {
            $query .= " WHERE $whereClause";
        }

        // Exécution de la requête SQL
        $result = mysqli_query($conn, $query);

        // Vérification si la requête a réussi
        if ($result !== false) {
            $numRows = mysqli_num_rows($result);

            // Affichage des résultats de la recherche s'il y en a
            if ($numRows > 0) {
                echo "<h2>Résultats de la recherche :</h2>";
                echo "<table border='1'>";
                echo "<tr><th>Numéro d'intervention</th><th>Date d'intervention</th><th>Temps d'intervention</th><th>Commentaire</th><th>ID Technicien</th></tr>";

                // Affichage des données de chaque intervention dans un tableau HTML
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['Num_intervention'] . "</td>";
                    echo "<td>" . $row['Date_intervention'] . "</td>";
                    echo "<td>" . $row['Temps_intervention'] . "</td>";
                    echo "<td>" . $row['Commentaire'] . "</td>";
                    echo "<td>" . $row['Id_Technicien'] . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "Aucun résultat trouvé.";
            }
        } else {
            // Affichage d'une erreur si la requête SQL échoue
            die("La requête a échoué : " . mysqli_error($conn));
        }
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
    ?>

</body>
</html>
