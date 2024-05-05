<?php
// Inclusion du fichier de connexion à la base de données
include_once("../bdd.php");

// Définition de la fonction pour rechercher les fiches d'intervention
function rechercherFichesIntervention($searchDate, $searchNumIntervention) {
    // Connexion à la base de données
    $conn = connectToDatabase();

    try {
        // Requête SQL pour sélectionner les fiches d'intervention en fonction de la date et du numéro d'intervention
        $query = "SELECT * FROM intervention WHERE (Date_intervention LIKE :searchDate OR :searchDate = '') AND (Num_intervention LIKE :searchNumIntervention OR :searchNumIntervention = '')";

        // Préparation de la requête SQL
        $stmt = $conn->prepare($query);

        // Vérification si la préparation de la requête a réussi
        if ($stmt) {
            // Liaison des valeurs des paramètres avec les variables
            $stmt->bindParam(':searchDate', $searchDate, PDO::PARAM_STR);
            $stmt->bindParam(':searchNumIntervention', $searchNumIntervention, PDO::PARAM_STR);

            // Exécution de la requête SQL
            $stmt->execute();

            // Récupération des résultats de la requête sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Retourne faux si la préparation de la requête a échoué
            return false;
        }
    } catch (PDOException $e) {
        // Retourne faux en cas d'erreur PDO
        return false;
    } finally {
        // Fermeture de la connexion à la base de données dans tous les cas
        $conn = null;
    }
}
?>
