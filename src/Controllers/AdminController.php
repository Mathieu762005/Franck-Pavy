<?php
namespace App\Controllers;

use App\Models\AdminUser;
use App\Models\AdminProduct;
use App\Models\AdminMessage;
use App\Models\AdminCommande;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\user;
use PDO;

class AdminController
{
    private PDO $db;

    public function __construct($db)
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

    public function updateOrderStatus(int $orderId, string $status): bool
    {
        // Instanciation des modèles
        $orderModel = new Order($this->db);
        $orderItemModel = new OrderItem($this->db);

        // Récupération des informations de la commande
        $order = $orderModel->getById($orderId);
        if (!$order) {
            return false; // commande introuvable
        }

        // Mettre à jour le statut
        $updated = $orderModel->updateStatus($orderId, $status);
        if (!$updated) {
            return false;
        }

        // Si la commande est terminée, mettre à jour les stats du user et supprimer la commande
        if ($status === 'terminée') {
            $userId = $order['user_id'] ?? null;
            if ($userId) {
                // Récupérer les items de la commande pour calculer le total
                $items = $orderItemModel->getByOrder($orderId);
                $totalSpent = 0;
                foreach ($items as $item) {
                    $totalSpent += $item['total_price'];
                }

                // Mettre à jour les stats de l'utilisateur
                $userModel = new User($this->db);
                $userModel->getUserInfosById($userId); // hydrate l'objet User
                $userModel->incrementStats($totalSpent);
            }

            // Supprimer les éléments de la commande
            $orderItemModel->deleteByOrder($orderId);

            // Supprimer la commande elle-même
            $orderModel->delete($orderId);
        }

        return true;
    }

    // Méthode pour gérer le POST venant du formulaire de changement de statut
    public function handleStatusUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = (int) ($_POST['order_id'] ?? 0);
            $status = $_POST['order_status'] ?? '';

            if ($orderId && in_array($status, ['brouillon', 'confirmée', 'en préparation', 'prête', 'terminée', 'annulée'])) {
                $orderModel = new Order($this->db);
                $orderModel->updateStatus($orderId, $status);
            }
        }

        // Redirection vers la page adminCommandes
        header('Location: ?url=adminCommandes');
        exit;
    }
}