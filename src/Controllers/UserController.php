<?php

// On indique que cette classe appartient au namespace "Controllers"
namespace App\Controllers;

// On importe la classe User pour pouvoir l'utiliser ici
use App\Models\User;
// On importe la classe Database pour créer la connexion PDO
use App\Models\Database;

// Définition de la classe UserController
class UserController
{
    private User $userModel; // Objet User pour manipuler la base
    private $db;            // Connexion à la base de données

    // Constructeur de la classe
    public function __construct()
    {
        // On crée une instance PDO pour se connecter à la base
        $this->db = Database::createInstancePDO();
        // On instancie le modèle User avec la connexion PDO
        $this->userModel = new User($this->db);
    }

    // Méthode pour gérer l'inscription d'un utilisateur
    public function register()
    {
        // Si le formulaire est soumis en POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $errors = []; // Tableau pour stocker les erreurs

            // Vérification du reCAPTCHA
            $recaptcha_secret = "6LdDRD8sAAAAAJ75tbPjiTqdK_mvocvNw1cdRjji";
            $response = $_POST['g-recaptcha-response'] ?? '';
            $remoteip = $_SERVER['REMOTE_ADDR'];

            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$response}&remoteip={$remoteip}");
            $captcha_success = json_decode($verify);

            if (!$captcha_success->success) {
                $errors['captcha'] = 'Erreur : vérification reCAPTCHA échouée.';
            }

            // Vérification du champ "username"
            if (isset($_POST["username"]) && empty($_POST["username"])) {
                $errors['username'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Nom obligatoire';
            }

            // Vérification du champ "firstname"
            if (isset($_POST["firstname"]) && empty($_POST["firstname"])) {
                $errors['firstname'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Prénom obligatoire';
            }

            // Vérification du champ "email"
            if (isset($_POST["email"])) {
                if (empty($_POST["email"])) {
                    $errors['email'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Mail obligatoire';
                } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Mail non valide';
                } else if (User::checkMail($_POST["email"])) { // Vérifie si l'email existe déjà
                    $errors['email'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Mail déjà utilisé';
                }
            }

            // Vérification du mot de passe
            if (isset($_POST["password"])) {
                if (empty($_POST["password"])) {
                    $errors['password'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Mot de passe obligatoire';
                } else if (strlen($_POST["password"]) < 8) {
                    $errors['password'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Mot de passe trop court (minimum 8 caractères)';
                }
            }

            // Vérification de la confirmation du mot de passe
            if (isset($_POST["confirmPassword"])) {
                if (empty($_POST["confirmPassword"])) {
                    $errors['confirmPassword'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Confirmation du mot de passe obligatoire';
                } else if ($_POST["confirmPassword"] !== $_POST["password"]) {
                    $errors['confirmPassword'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Les mots de passe ne sont pas identiques';
                }
            }

            // Vérification que l'utilisateur accepte les CGU
            if (!isset($_POST["cgu"])) {
                $errors['cgu'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Vous devez accepter les CGU';
            }

            // -------------------------------
            // 3️⃣ Création de l'utilisateur
            // -------------------------------
            if (empty($errors)) {
                $objetUser = new User($this->db);
                if ($objetUser->createUser($_POST["username"], $_POST["firstname"], $_POST["email"], $_POST["password"])) {
                    // Redirection vers la page de succès
                    header('Location: index.php?url=register_success');
                    exit;
                } else {
                    $errors['server'] = "Une erreur s'est produite, veuillez réessayer ultérieurement";
                }
            }
        }

        // Affiche la vue du formulaire d'inscription
        require_once __DIR__ . "/../Views/register.php";
    }

    // Méthode pour gérer la connexion d'un utilisateur
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $errors = []; // Tableau pour stocker les erreurs

            // Vérification du reCAPTCHA
            $recaptcha_secret = "6LdDRD8sAAAAAJ75tbPjiTqdK_mvocvNw1cdRjji";
            $response = $_POST['g-recaptcha-response'] ?? '';
            $remoteip = $_SERVER['REMOTE_ADDR'];

            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$response}&remoteip={$remoteip}");
            $captcha_success = json_decode($verify);

            if (!$captcha_success->success) {
                $errors['captcha'] = 'Erreur : vérification reCAPTCHA échouée.';
            }

            // Vérification du champ email
            if (isset($_POST["email"]) && empty($_POST["email"])) {
                $errors['email'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Mail obligatoire';
            }

            // Vérification du mot de passe
            if (isset($_POST["password"]) && empty($_POST["password"])) {
                $errors['password'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Mot de passe obligatoire';
            }

            // Si aucune erreur, on tente la connexion
            if (empty($errors)) {

                // Vérifie que l'email existe
                // Vérifie que l'email existe
                if (User::checkMail($_POST["email"])) {

                    // Récupère les infos de l'utilisateur
                    $userInfos = new User($this->db);
                    if ($userInfos->loadByEmail($_POST["email"])) {

                        // Vérifie le mot de passe
                        if (password_verify($_POST["password"], $userInfos->password)) {

                            // Stocke les infos utilisateur dans la session
                            $_SESSION["user"]["id"] = $userInfos->id;
                            $_SESSION["user"]["role"] = $userInfos->role;
                            $_SESSION["user"]["username"] = $userInfos->username;
                            $_SESSION["user"]["firstname"] = $userInfos->firstname;
                            $_SESSION["user"]["email"] = $userInfos->email;
                            $_SESSION["user"]["orders_count"] = $userInfos->user_orders_count;

                            // Redirection
                            header("Location: index.php?url=06_profil");
                            exit;
                        } else {
                            $errors['connexion'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Mail ou Mot de passe incorrect';
                        }
                    }
                } else {
                    $errors['connexion'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Mail ou Mot de passe incorrect';
                }
            }
        }

        // Affiche la vue du formulaire de connexion
        require_once __DIR__ . "/../Views/login.php";
    }

    // Méthode pour déconnecter l'utilisateur
    public function logout()
    {
        // Supprime toutes les données de session
        unset($_SESSION['user']);
        unset($_SESSION['cart']);
        session_destroy();

        // Redirection vers la page de connexion
        header('Location: index.php?url=login');
    }

    // Méthode pour afficher la page profil
    public function profil()
    {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user']['id'])) {
            header('Location: index.php?url=login');
            exit;
        }

        $userId = $_SESSION['user']['id'];

        // Récupère les informations utilisateur
        $user = $this->userModel; // on utilise l'objet déjà instancié
        $user->loadById($userId); // hydrate l'objet

        // Récupère les commandes de l'utilisateur
        $orderController = new \App\Controllers\OrderController($this->db);
        $userOrders = $orderController->getUserOrders($userId);

        // Si un ID de commande est passé dans l'URL, on récupère les détails
        $orderId = $_GET['order_id'] ?? null;
        $orderDetails = $orderId ? $orderController->getOrderDetails((int) $orderId) : null;

        // Affichage de la vue profil
        require_once __DIR__ . "/../Views/06_profil.php";
    }
}
