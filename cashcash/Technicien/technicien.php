<?php
// Définition de la classe InterventionModel
class InterventionModel {
    private $db; // Propriété pour stocker l'objet de connexion à la base de données

    // Constructeur de la classe pour initialiser la connexion à la base de données
    public function __construct() {
        // Connexion à la base de données MySQL en utilisant PDO
        $this->db = new PDO('mysql:host=localhost;dbname=cashcash', 'root', 'root');
    }

    // Méthode pour récupérer toutes les interventions de la base de données
    public function getInterv() {
        $statement = $this->db->prepare("SELECT * FROM intervention"); // Préparation de la requête SQL
        $statement->execute(); // Exécution de la requête SQL
        return $statement->fetchAll(PDO::FETCH_ASSOC); // Renvoie des résultats sous forme de tableau associatif
    }

    // Méthode pour afficher les interventions dans un tableau HTML
    public function displayInterventions() {
        $interventions = $this->getInterv(); // Appel de la méthode getInterv() pour récupérer les interventions
        echo "<table>"; // Début du tableau HTML
        foreach ($interventions as $intervention) {
            echo "<tr>"; // Début d'une ligne du tableau
            foreach ($intervention as $key => $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>"; // Affichage de chaque valeur dans une cellule du tableau
            }
            echo "</tr>"; // Fin de la ligne du tableau
        }
        echo "</table>"; // Fin du tableau HTML
    }

    // Méthode pour mettre à jour une intervention dans la base de données
    public function updateIntervention($num, $temps, $commentaire) {
        $statement = $this->db->prepare("UPDATE intervention SET Temps_intervention = :temps, Commentaire = :commentaire WHERE Num_intervention = :num");
        $statement->bindParam(':temps', $temps); // Liaison des paramètres avec les valeurs fournies
        $statement->bindParam(':commentaire', $commentaire);
        $statement->bindParam(':num', $num);
        $statement->execute(); // Exécution de la requête SQL
    }
}

// Vérification si la requête HTTP est de type POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $model = new InterventionModel(); // Instanciation de la classe InterventionModel
    if (isset($_POST['update'])) { // Vérification si le formulaire a été soumis pour mettre à jour une intervention
        $model->updateIntervention($_POST['num'], $_POST['temps'], $_POST['commentaire']); // Appel de la méthode pour mettre à jour l'intervention
    }
}

$model = new InterventionModel(); // Instanciation de la classe InterventionModel
$model->displayInterventions(); // Appel de la méthode pour afficher les interventions actuelles dans un tableau HTML

?>
<!-- Début du style CSS pour le tableau et le formulaire -->
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }

    .update-form {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        padding: 20px;
        margin: 20px 0;
        width: 300px;
    }

    .update-form input[type="text"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ced4da;
    }

    .update-form input[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
    }

    .update-form input[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>

<!-- Formulaire HTML pour la mise à jour d'une intervention -->
<form class="update-form" method="post" action="">
    Num Intervention: <input type="text" name="num"><br>
    Nouveau Temps Intervention: <input type="text" name="temps"><br>
    Nouveau Commentaire: <input type="text" name="commentaire"><br>
    <input type="submit" name="update" value="Modifier Intervention">
</form>
