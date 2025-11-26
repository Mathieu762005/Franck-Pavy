<?php
// Appelle t'es controlleurs
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\CategoryProductController;

// Appelle t'as logique de connexion a la base de donnés
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

    case 'login': /* le nom de t'as page */
        $objController = new UserController(); /* appelle ton bon controlleur */
        $objController->login();  /* appelle la method de ton controlleur */
        break;

    case '06_profil': /* le nom de t'as page */
        $objController = new UserController(); /* appelle ton bon controlleur */
        $objController->profil();  /* appelle la method de ton controlleur */
        break;

    case 'register_success': /* le nom de t'as page */
        require_once __DIR__ . "/../src/Views/register_success.php";
        break;

    case 'register': /* le nom de t'as page */
        $objController = new UserController(); /* appelle ton bon controlleur */
        $objController->register();  /* appelle la method de ton controlleur */
        break;

    case '02_produits': /* le nom de t'as page */
        $objController = new CategoryProductController();
        $id = $_GET['id'] ?? null;
        $objController->showCategoryWithProducts((int) $id);
        break;

    default:
        // aucun cas reconnu = on charge la 404
        require_once __DIR__ . "/../src/Views/page404.php";
}