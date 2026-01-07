<?php

namespace App\Controllers;

/**
 * Contrôleur pour la page d'accueil
 */
class HomeController
{
    /**
     * Méthode affichant la page d'accueil
     *
     * @return void
     */
    public function index(): void
    {
        // On inclut la vue de la page d'accueil
        require_once __DIR__ . "/../Views/01_home.php";
    }
}
