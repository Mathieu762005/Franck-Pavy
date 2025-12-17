<?php
namespace App\Models;

use PDO;
use PDOException;

class OrderItem
{
    private PDO $db;           // Connexion à la base de données
    private string $table = 'order_items'; // Nom de la table

    // Constructeur : on passe la connexion PDO depuis le controller
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Copier les articles du panier vers la table order_items
     * @param int $orderId ID de la commande
     * @param int $userId ID de l'utilisateur
     * @return bool Retourne true si succès, false sinon
     */
    public function copyCartToOrder(int $orderId, int $userId): bool
    {
        try {
            // On sélectionne tous les produits du panier de l'utilisateur qui ne sont pas encore associés à une commande
            // et on les insère dans order_items avec l'ID de la commande
            $stmt = $this->db->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, total_price)
                SELECT ?, product_id, product_name, cart_items_quantity, cart_items_unit_price, cart_items_total_price
                FROM cart_items
                WHERE order_id IS NULL AND user_id = ?
            ");
            return $stmt->execute([$orderId, $userId]); // Exécution avec paramètres pour sécuriser la requête
        } catch (PDOException $e) {
            return false; // En cas d'erreur SQL, retourne false
        }
    }

    /**
     * Récupère tous les articles d'une commande
     * @param int $orderId ID de la commande
     * @return array Tableau des items, vide si aucun ou erreur
     */
    public function getByOrder(int $orderId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmt->execute([$orderId]); // On sécurise la requête avec l'ID
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne tous les items sous forme de tableau associatif
        } catch (PDOException $e) {
            return []; // En cas d'erreur, retourne un tableau vide
        }
    }

    /**
     * Supprime tous les articles d'une commande
     * @param int $orderId ID de la commande
     * @return bool true si succès, false sinon
     */
    public function deleteByOrder(int $orderId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE order_id = :order_id");
        return $stmt->execute(['order_id' => $orderId]); // Exécution sécurisée avec bindValue implicite
    }
}