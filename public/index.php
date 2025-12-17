<?php
// On inclut l'autoload de Composer pour charger automatiquement toutes les classes
require_once __DIR__ . '/../vendor/autoload.php';

// On utilise la librairie Dotenv pour charger les variables d'environnement
use Dotenv\Dotenv;

// Création de l'objet Dotenv et chargement des variables depuis le fichier .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// On démarre la session PHP pour gérer les connexions, le panier, etc.
session_start();

// Ensuite, on inclut le routeur pour gérer les différentes pages de l'application
require_once __DIR__ . '/routeur.php';