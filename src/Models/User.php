<?php

namespace App\Models;

use PDO;
use PDOException;

class User
{
    private PDO $db; // On garde la connexion PDO ici pour toutes les requêtes

    // Propriétés qui correspondent aux colonnes de la table "users"
    public ?int $id = null;
    public string $role = '';
    public string $username = '';
    public string $firstname = '';
    public string $email = '';
    public string $password = '';
    public float $user_total_spent = 0.0;
    public int $user_orders_count = 0;

    // Constructeur : on passe la connexion PDO depuis le controller
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Crée un nouvel utilisateur
     * @param string $username
     * @param string $firstname
     * @param string $email
     * @param string $password
     * @return bool succès ou échec
     */
    public function createUser(string $username, string $firstname, string $email, string $password): bool
    {
        try {
            $sql = 'INSERT INTO users (user_role, user_name, user_first_name, user_email, user_password)
                    VALUES (:role, :username, :firstname, :email, :password)';
            $stmt = $this->db->prepare($sql);

            // On lie les valeurs aux paramètres SQL pour éviter les injections
            $stmt->bindValue(':role', 'user', PDO::PARAM_STR); // rôle par défaut
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR); // on hash le mot de passe

            return $stmt->execute(); // retourne true si insertion OK
        } catch (PDOException $e) {
            // Ici tu pourrais logger $e->getMessage() pour debug
            return false;
        }
    }

    /**
     * Méthode statique pour vérifier si un email existe déjà
     * On peut l'appeler sans créer un objet User
     */
    public static function checkMail(string $email): bool
    {
        $pdo = Database::createInstancePDO(); // nouvelle connexion PDO juste pour ça
        if (!$pdo)
            return false;

        $stmt = $pdo->prepare('SELECT 1 FROM users WHERE user_email = :email LIMIT 1');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() !== false; // true si trouvé, false sinon
    }

    /**
     * Charge un utilisateur via son email et hydrate l'objet
     * @return bool true si trouvé, false sinon
     */
    public function loadByEmail(string $email): bool
    {
        try {
            $sql = 'SELECT * FROM users WHERE user_email = :email';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user)
                return false;

            $this->hydrate($user); // rempli toutes les propriétés de l'objet
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Charge un utilisateur via son ID
     * @return bool true si trouvé, false sinon
     */
    public function loadById(int $userId): bool
    {
        try {
            $sql = 'SELECT * FROM users WHERE user_id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user)
                return false;

            $this->hydrate($user);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Incrémente le total dépensé et le nombre de commandes
     * @param float $amount
     * @return bool succès ou échec
     */
    public function incrementStats(float $amount): bool
    {
        if ($this->id === null)
            return false; // vérifie que l'objet est hydraté

        try {
            $sql = 'UPDATE users
                    SET user_total_spent = user_total_spent + :amount,
                        user_orders_count = user_orders_count + 1
                    WHERE user_id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $result = $stmt->execute();

            // Mets à jour l'objet pour garder les infos synchronisées
            if ($result) {
                $this->user_total_spent += $amount;
                $this->user_orders_count += 1;
            }

            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Hydrate l'objet avec un tableau de données
     * @param array $data
     */
    private function hydrate(array $data): void
    {
        $this->id = (int) ($data['user_id'] ?? null);
        $this->role = $data['user_role'] ?? '';
        $this->username = $data['user_name'] ?? '';
        $this->firstname = $data['user_first_name'] ?? '';
        $this->email = $data['user_email'] ?? '';
        $this->password = $data['user_password'] ?? '';
        $this->user_total_spent = (float) ($data['user_total_spent'] ?? 0);
        $this->user_orders_count = (int) ($data['user_orders_count'] ?? 0);
    }
}