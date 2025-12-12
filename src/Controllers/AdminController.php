<?php
namespace App\Controllers;

use App\Models\AdminUser;
use App\Models\AdminProduct;
use App\Models\AdminMessage;
use App\Models\AdminCommande;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use PDO;

class AdminController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function users()
    {
        $userModel = new AdminUser();
        $users = $userModel->findAll();
        require __DIR__ . '/../Views/admin/adminUsers.php';
    }

    public function messages()
    {
        $messageModel = new AdminMessage();
        $messages = $messageModel->findAll();
        require __DIR__ . '/../Views/admin/adminMessages.php';
    }

    public function produits()
    {
        $produitModel = new AdminProduct();
        $produits = $produitModel->findAll();
        require __DIR__ . '/../Views/admin/adminProducts.php';
    }

    public function commandes()
    {
        $commandeModel = new AdminCommande();
        $commandes = $commandeModel->findAll(); // retourne un array de toutes les commandes
        require __DIR__ . '/../Views/admin/adminCommandes.php';
    }

    // Met à jour le statut d'une commande et applique les effets si terminée
    public function updateOrderStatus(int $orderId, string $status): bool
    {
        $orderModel = new Order($this->db);
        $orderItemModel = new OrderItem($this->db);

        // Récupération des informations de la commande
        $order = $orderModel->getById($orderId);
        if (!$order)
            return false;

        // Mettre à jour le statut
        $orderModel->updateStatus($orderId, $status);

        // Si la commande est terminée
        if ($status === 'terminée') {
            $userId = $order['user_id'] ?? null;
            if ($userId) {
                // Calculer le total dépensé via order_items
                $items = $orderItemModel->getByOrder($orderId);
                $totalSpent = 0;
                foreach ($items as $item) {
                    $totalSpent += (float) $item['total_price'];
                }

                // Mettre à jour les stats de l'utilisateur
                $user = new User($this->db);

                // Hydrater correctement l'objet User avec ses propriétés
                $userData = $user->getUserInfosById($userId);
                if ($userData) {
                    $user->id = (int) $userData['user_id'];
                    $user->role = $userData['user_role'];
                    $user->username = $userData['user_name'];
                    $user->firstname = $userData['user_first_name'];
                    $user->email = $userData['user_email'];
                    $user->password = $userData['user_password'];
                    $user->user_total_spent = (float) $userData['user_total_spent'];
                    $user->user_orders_count = (int) $userData['user_orders_count'];

                    // Incrémenter les stats
                    $user->incrementStats($totalSpent);
                }
            }

            // Supprimer les éléments et la commande
            $orderItemModel->deleteByOrder($orderId);
            $orderModel->delete($orderId);
        }

        return true;
    }

    // Traite le POST du formulaire dans la vue adminCommandes
    public function handleStatusUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = (int) ($_POST['order_id'] ?? 0);
            $status = $_POST['status'] ?? '';

            if ($orderId && in_array($status, ['brouillon', 'confirmée', 'en préparation', 'prête', 'terminée', 'annulée'])) {
                $this->updateOrderStatus($orderId, $status);
            }
        }

        header('Location: ?url=adminCommandes');
        exit;
    }
}