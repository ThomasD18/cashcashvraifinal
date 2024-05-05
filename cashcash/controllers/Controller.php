<?php
// controllers/Controller.php

// Inclure le modèle et la vue
require_once 'models/Model.php';
require_once 'views/View.php';

class Controller {
    public function action() {
        // Instancier le modèle
        $model = new Model();

        // Appeler une méthode du modèle pour obtenir des données
        $data = $model->getData();

        // Instancier la vue
        $view = new View();

        // Rendre les données à la vue
        $view->render($data);
    }
}
?>
