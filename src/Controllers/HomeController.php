<?php

namespace App\Controllers;

class HomeController
{

    /**
     * Méthode affichant la page d'accueil
     *
     * @return void
     */
    public function index(): void
    {
        require_once __DIR__ . "/../Views/01_home.php";
    }
}