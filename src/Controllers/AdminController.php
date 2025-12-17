<?php
namespace App\Controllers;

// Import des modèles utilisés par le contrôleur admin
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
    // On stocke la connexion PDO pour la base de données
    private PDO $db;

    // Constructeur : on passe la connexion PDO
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ---------- USERS ----------
    // Affiche tous les utilisateurs dans le panneau admin
    public function users()
    {
        $userModel = new AdminUser();     // On instancie le modèle AdminUser
        $users = $userModel->findAll();   // On récupère tous les utilisateurs
        require __DIR__ . '/../Views/admin/adminUsers.php'; // On inclut la vue correspondante
    }

    // ---------- MESSAGES ----------
    // Affiche tous les messages reçus dans le panneau admin
    public function messages()
    {
        $messageModel = new AdminMessage(); // On instancie le modèle AdminMessage
        $messages = $messageModel->findAll(); // On récupère tous les messages
        require __DIR__ . '/../Views/admin/adminMessages.php'; // On inclut la vue correspondante
    }

    // ---------- PRODUITS ----------
    // Affiche tous les produits dans le panneau admin
    public function produits()
    {
        $produitModel = new AdminProduct(); // Instancie le modèle AdminProduct
        $produits = $produitModel->findAll(); // Récupère tous les produits
        require __DIR__ . '/../Views/admin/adminProducts.php'; // Inclut la vue
    }

    // ---------- COMMANDES ----------
    // Affiche toutes les commandes dans le panneau admin
    public function commandes()
    {
        $commandeModel = new AdminCommande(); // Instancie le modèle AdminCommande
        $commandes = $commandeModel->findAll(); // Récupère toutes les commandes
        require __DIR__ . '/../Views/admin/adminCommandes.php'; // Inclut la vue
    }

    // ---------- COMMANDES ----------
    // Affiche toutes les commandes dans le panneau admin
    public function details()
    {
        $commandeModel = new AdminCommande(); // Instancie le modèle AdminCommande
        $detail = $commandeModel->detailModel(); // Récupère toutes les commandes
        require __DIR__ . '/../Views/admin/adminCommandes.php'; // Inclut la vue
    }

    // ---------- UPDATE STATUS ----------
    // Met à jour le statut d'une commande et applique les effets si terminée
    public function updateOrderStatus(int $orderId, string $status): bool
    {
        $orderModel = new Order($this->db);
        $orderItemModel = new OrderItem($this->db);

        // Récupère la commande
        $order = $orderModel->getById($orderId);
        if (!$order) {
            return false;
        }

        // Met à jour le statut
        $orderModel->updateStatus($orderId, $status);

        if ($status === 'terminée') {

            $userId = $order['user_id'] ?? null;
            if ($userId) {

                // Récupère les articles
                $items = $orderItemModel->getByOrder($orderId);
                $totalSpent = 0.0;

                foreach ($items as $item) {
                    $totalSpent += (float) $item['total_price'];
                }

                // 👉 LOGIQUE CORRECTE
                $user = new User($this->db);
                if ($user->loadById((int) $userId)) {
                    $user->incrementStats($totalSpent);
                }
            }

            // Nettoyage
            $orderItemModel->deleteByOrder($orderId);
            $orderModel->delete($orderId);
        }

        return true;
    }

    // ---------- HANDLE STATUS UPDATE ----------
    // Traite le POST du formulaire pour changer le statut d'une commande
    public function handleStatusUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = (int) ($_POST['order_id'] ?? 0); // Récupère l'ID de la commande
            $status = $_POST['status'] ?? '';           // Récupère le nouveau statut

            // Vérifie que le statut est valide
            if ($orderId && in_array($status, ['brouillon', 'confirmée', 'en préparation', 'prête', 'terminée', 'annulée'])) {
                $this->updateOrderStatus($orderId, $status); // Met à jour la commande
            }
        }

        // Redirection après POST pour éviter le double envoi
        header('Location: ?url=adminCommandes');
        exit;
    }

    // ---------- HANDLE COMMANDES ----------
    // Combine affichage et mise à jour des commandes dans un seul appel
    public function handleCommandes(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleStatusUpdate();
            return;
        }

        $commandeModel = new AdminCommande();
        $commandes = $commandeModel->findAll();

        // Récupérer les détails pour chaque commande
        foreach ($commandes as &$commande) {
            $commande['details'] = $commandeModel->findDetailsByOrder($commande['order_id']);
        }
        unset($commande); // bonne pratique après avoir utilisé une référence

        require __DIR__ . '/../Views/admin/adminCommandes.php';
    }
}