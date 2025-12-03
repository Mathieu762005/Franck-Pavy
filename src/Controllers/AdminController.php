<?php
namespace App\Controllers;

use App\Models\AdminUser;
use App\Models\AdminProduct;
use App\Models\AdminMessage;
use App\Models\AdminCommande;
use App\Models\Order;
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
        $order = new Order($this->db);
        return $order->updateStatus($orderId, $status);
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