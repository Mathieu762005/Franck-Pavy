<?php

// On indique que cette classe appartient au dossier logique "Models"
namespace App\Models;

// On importe la classe Database pour se connecter à la base
use App\Models\DataBase;

// On importe les classes PDO pour exécuter des requêtes SQL
use PDO;
use PDOException;

// Définition de la classe User
class Contact
{
    // Propriétés du User (correspondent aux colonnes de la table "users")
    public int $id;
    public string $subject;
    public string $body;
    public string $sentAt;

    // Créer un nouveau message
    public function createMessage(string $subject, string $body, ?int $userId = null): bool
    {
        try {
            $pdo = Database::createInstancePDO();

            if (!$pdo) {
                return false;
            }

            $sql = "INSERT INTO messages (message_subject, message_body, message_sent_at, user_id)
                    VALUES (:subject, :body, NOW(), :user_id)";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':subject', $subject, PDO::PARAM_STR);
            $stmt->bindValue(':body', $body, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

}