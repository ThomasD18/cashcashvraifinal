<?php
include_once("../bdd.php");
session_start();
include_once("../index.php");

$conn = connectToDatabase();

if (isset($_SESSION['success_message'])) {
    echo '<div class="flash-message">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche Clients</title>
    <style>
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
        .flash-message {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php include_once("../index.php"); ?>

<h1>Recherche Clients</h1>

<form method="POST" action="">
    <label for="searchByName">Rechercher par nom :</label>
    <input type="text" name="searchByName" placeholder="Nom">

    <label for="searchById">Rechercher par ID :</label>
    <input type="text" name="searchById" placeholder="ID">

    <button type="submit">Rechercher</button>
</form>

<?php

$searchPerformed = false;
$numRows = 0; // Initialisation du nombre de lignes à 0

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchByName = isset($_POST['searchByName']) ? mysqli_real_escape_string($conn, $_POST['searchByName']) : '';
    $searchById = isset($_POST['searchById']) ? mysqli_real_escape_string($conn, $_POST['searchById']) : '';

    $whereClause = '';
    if (!empty($searchByName)) {
        $whereClause .= "Nom LIKE '%$searchByName%'";
    }
    if (!empty($searchById)) {
        $whereClause .= ($whereClause != '' ? ' AND ' : '') . "id = '$searchById'";
    }

    if ($whereClause != '') {
        $searchPerformed = true;

        $searchQuery = "SELECT * FROM client WHERE $whereClause";
        $result = mysqli_query($conn, $searchQuery);

        if ($result !== false) {
            $numRows = mysqli_num_rows($result);

            if ($numRows > 0) {
                echo "<h2>Résultats de la recherche :</h2>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Nom</th><th>Raison Sociale</th><th>SIREN</th><th>APE</th><th>Adresse</th><th>Téléphone</th><th>Email</th><th>Action</th></tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['Nom'] . "</td>";
                    echo "<td>" . $row['raison_sociale'] . "</td>";
                    echo "<td>" . $row['SIREN'] . "</td>";
                    echo "<td>" . $row['APE'] . "</td>";
                    echo "<td>" . $row['Adresse'] . "</td>";
                    echo "<td>" . $row['Telephone'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td><a href='modifierclient.php?id=" . $row['id'] . "'>Modifier</a></td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<p>Aucun résultat trouvé.</p>";
            }
        } else {
            die("La requête a échoué : " . mysqli_error($conn));
        }
    }
}

// Affiche la liste complète si aucune recherche n'a été effectuée ou si la recherche n'a pas donné de résultats
if (!$searchPerformed || ($searchPerformed && $numRows === 0)) {
    $displayAllQuery = "SELECT * FROM client";
    $result = mysqli_query($conn, $displayAllQuery);

    if ($result !== false) {
        $numRows = mysqli_num_rows($result);

        if ($numRows > 0) {
            echo "<h2>Liste des clients :</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Nom</th><th>Raison Sociale</th><th>SIREN</th><th>APE</th><th>Adresse</th><th>Téléphone</th><th>Email</th><th>Action</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['Nom'] . "</td>";
                echo "<td>" . $row['raison_sociale'] . "</td>";
                echo "<td>" . $row['SIREN'] . "</td>";
                echo "<td>" . $row['APE'] . "</td>";
                echo "<td>" . $row['Adresse'] . "</td>";
                echo "<td>" . $row['Telephone'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td><a href='modifierclient.php?id=" . $row['id'] . "'>Modifier</a></td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Aucun client trouvé.</p>";
        }
    } else {
        die("La requête a échoué : " . mysqli_error($conn));
    }
}

mysqli_close($conn);
?>

</body>
</html>

