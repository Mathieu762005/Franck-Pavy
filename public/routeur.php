<?php
// Appelle t'es controlleurs
use App\Controllers\HomeController;

// Appelle t'as ogique de connexion a la base de donnés
use App\Models\Database;

// si le param url est présent on prend sa valeur, sinon on donne la valeur home
$url = $_GET['url'] ?? 'home';

// je transforme $url en un tableau à l'aide de explode()
$arrayUrl = explode('/', $url);

// je récupère la page demandée index 0
$page = $arrayUrl[0];

switch ($page) {
    case 'home': /* le nom de t'as page */
        $objController = new HomeController(); /* appelle ton bon controlleur */
        $objController->index();  /* appelle la method de ton controlleur */
        break;

    default:
        // aucun cas reconnu = on charge la 404
        require_once __DIR__ . "/../src/Views/page404.php";
}