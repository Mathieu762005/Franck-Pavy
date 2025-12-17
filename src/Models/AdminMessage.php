<?php

namespace App\Models;

use App\Models\DataBase;
use PDO;
use PDOException;

class AdminMessage
{
    private PDO $db; // Connexion à la base de données

    public function __construct()
    {
        // On utilise la classe Database pour créer une instance PDO
        $this->db = DataBase::createInstancePDO();
    }

    /**
     * Récupère tous les messages avec les informations de l'utilisateur
     * @return array Tableau associatif des messages ou vide en cas d'erreur
     */
    public function findAll(): array
    {
        try {
            // Requête SQL pour récupérer les messages et les infos utilisateur
            $sql = "
                SELECT 
                    messages.message_id, 
                    messages.message_subject, 
                    messages.message_body, 
                    messages.message_sent_at, 
                    users.user_name, 
                    users.user_first_name, 
                    users.user_email
                FROM messages
                JOIN users ON messages.user_id = users.user_id;
            ";
            $stmt = $this->db->query($sql); // Exécution de la requête
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif
        } catch (PDOException $e) {
            // Si une erreur SQL survient, on retourne un tableau vide
            return [];
        }
    }
}