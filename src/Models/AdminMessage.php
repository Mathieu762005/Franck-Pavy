<?php

// On indique que cette classe appartient au dossier logique "Models"
namespace App\Models;

// On importe la classe Database pour se connecter à la base
use App\Models\DataBase;

// On importe les classes PDO pour exécuter des requêtes SQL
use PDO;
use PDOException;

// Définition de la classe User
class AdminMessage
{
    private PDO $db;

    public function __construct()
    {
        // Connexion à la base via ta classe DataBase
        $this->db = DataBase::createInstancePDO();
    }


    // Récupérer tous les utilisateurs
    public function findAll()
    {
        try {
            $sql = "SELECT messages.message_id, messages.message_subject, messages.message_body, messages.message_sent_at, users.user_name, users.user_first_name, users.user_email
                    FROM messages
                    JOIN users ON messages.user_id = users.user_id;";
            $stmt = $this->db->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

}