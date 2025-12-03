<?php

// On indique que cette classe appartient au dossier logique "Models"
namespace App\Models;

// On importe la classe Database pour se connecter à la base
use App\Models\DataBase;

// On importe les classes PDO pour exécuter des requêtes SQL
use PDO;
use PDOException;
use App\Models\Product;

// Définition de la classe User
class User
{

    private PDO $db;
    // Propriétés du User (correspondent aux colonnes de la table "users")
    public int $id;
    public string $role;
    public string $username;
    public string $firstname;
    public string $email;
    public string $password;
    public float $totalSpent = 0.0;
    public int $ordersCount = 0;

    public function __construct(PDO $db)
    {
        $this->db = $db;

        // Initialisation obligatoire pour éviter l'erreur
        $this->totalSpent = 0.0;
        $this->ordersCount = 0;
    }

    /**
     * Méthode pour créer un nouvel utilisateur dans la base
     */
    public function createUser(string $username, string $firstname, string $email, string $password): bool
    {
        try {
            // Connexion à la base via notre classe Database
            $pdo = Database::createInstancePDO();

            // Si la connexion échoue, on retourne false
            if (!$pdo) {
                return false;
            }

            // Requête SQL pour insérer un nouvel utilisateur
            $sql = 'INSERT INTO `users` (`user_role`, `user_name`, `user_first_name`, `user_email`, `user_password`) 
                    VALUES (:role, :username, :firstname, :email, :password)';

            // Préparation de la requête pour éviter les injections SQL
            $stmt = $pdo->prepare($sql);

            // On lie les valeurs aux paramètres SQL
            $stmt->bindValue(':role', "user", PDO::PARAM_STR); // rôle par défaut
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR); // On hash le mot de passe

            // On exécute la requête et on retourne le résultat
            return $stmt->execute();
        } catch (PDOException $e) {
            // En cas d'erreur SQL, on affiche le message et on retourne false
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Méthode pour vérifier si un email existe déjà dans la base
     */
    public static function checkMail(string $email): bool
    {
        try {
            $pdo = Database::createInstancePDO();

            if (!$pdo) {
                return false;
            }

            // Requête pour vérifier si l'email existe
            $sql = 'SELECT 1 FROM `users` 
                    WHERE `user_email` = :email 
                    LIMIT 1';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            // Si on trouve une ligne, l'email existe
            $result = $stmt->fetchColumn();
            return $result !== false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Méthode pour récupérer les infos d'un utilisateur via son email
     */
    public function getUserInfosByEmail(string $email): bool
    {
        try {
            $pdo = Database::createInstancePDO();

            if (!$pdo) {
                return false;
            }

            // Requête pour récupérer toutes les infos du user
            $sql = "SELECT * FROM `users` WHERE `user_email` = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            // On récupère les données sous forme d'objet
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            // On hydrate notre objet User avec les données récupérées
            $this->id = $user->user_id;
            $this->role = $user->user_role;
            $this->username = $user->user_name;
            $this->firstname = $user->user_first_name;
            $this->email = $user->user_email;
            $this->password = $user->user_password;
            $this->total_spent = $user->user_total_spent ?? 0;
            $this->orders_count = $user->user_orders_count ?? 0;

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function loadById(int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user)
            return false;

        $this->id = $user['user_id'];
        $this->role = $user['user_role'];
        $this->username = $user['user_name'];
        $this->firstname = $user['user_first_name'];
        $this->email = $user['user_email'];
        $this->password = $user['user_password'];
        $this->totalSpent = (float) ($user['user_total_spent'] ?? 0);
        $this->ordersCount = (int) ($user['user_orders_count'] ?? 0);

        return true;
    }

    public function incrementStats(float $amount): bool
    {
        if (!isset($this->id))
            return false;

        $stmt = $this->db->prepare("
            UPDATE users
            SET user_total_spent = user_total_spent + :amount,
                user_orders_count = user_orders_count + 1
            WHERE user_id = :id
        ");
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $result = $stmt->execute();

        if ($result) {
            // Ces propriétés sont maintenant correctement initialisées
            $this->totalSpent += $amount;
            $this->ordersCount += 1;
        }

        return $result;
    }

    // Récupérer un utilisateur par ID — retourne array|null
    public function getByUserId(int $id)
    {
        try {

            $pdo = Database::createInstancePDO();

            if (!$pdo) {
                return false;
            }
            $sql = ("SELECT * FROM users WHERE user_id = ?");
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ?: null;
        } catch (PDOException $e) {
            // tu peux logger $e->getMessage()
            return null;
        }
    }
    public function getUserInfosById(int $userId)
    {
        $pdo = Database::createInstancePDO();

        if (!$pdo) {
            return false;
        }
        $sql = ("SELECT * FROM users WHERE user_id = :id");
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}