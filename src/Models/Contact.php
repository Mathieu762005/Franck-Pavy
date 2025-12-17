<?php

namespace App\Models;

use App\Models\DataBase;
use PDO;
use PDOException;

class Contact
{
    // Propriétés correspondant aux colonnes de la table messages
    public int $id;       // ID du message
    public string $subject; // Sujet du message
    public string $body;    // Contenu du message
    public string $sentAt;  // Date d'envoi

    /**
     * Crée un nouveau message dans la base
     * @param string $subject Sujet du message
     * @param string $body Contenu du message
     * @param int|null $userId ID de l'utilisateur qui envoie le message (optionnel)
     * @return bool True si succès, false sinon
     */
    public function createMessage(string $subject, string $body, ?int $userId = null): bool
    {
        try {
            // Crée une connexion PDO via le modèle Database
            $pdo = Database::createInstancePDO();
            if (!$pdo)
                return false;

            // Préparation de la requête pour insérer un nouveau message
            $sql = "INSERT INTO messages (message_subject, message_body, message_sent_at, user_id)
                    VALUES (:subject, :body, NOW(), :user_id)";
            $stmt = $pdo->prepare($sql);

            // Liaison des valeurs aux paramètres SQL
            $stmt->bindValue(':subject', $subject, PDO::PARAM_STR);
            $stmt->bindValue(':body', $body, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

            // Exécution de la requête
            return $stmt->execute();
        } catch (PDOException $e) {
            // En cas d'erreur, retourne false (on pourrait logger $e->getMessage() pour debug)
            return false;
        }
    }
}