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
    public function users()
    {
        $userModel = new AdminUser();

        // Suppression si POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
            $userId = (int) $_POST['delete_user_id'];
            $userModel->deleteUser($userId);
            header('Location: ?url=adminUsers');
            exit;
        }

        // Recherche
        $search = trim($_GET['search'] ?? '');

        if ($search !== '') {
            $users = $userModel->searchByNameOrFirstname($search);
        } else {
            $users = $userModel->findAll();
        }

        // Trier les utilisateurs de façon à afficher les admins en premier
        usort($users, function ($a, $b) {
            // Vérifie si $a est un admin et $b n'est pas admin
            // Si c'est le cas, on renvoie -1 pour placer $a avant $b dans le tableau
            if ($a['user_role'] === 'admin' && $b['user_role'] !== 'admin') return -1;

            // Vérifie si $b est un admin et $a n'est pas admin
            // Si c'est le cas, on renvoie 1 pour placer $b avant $a dans le tableau
            if ($a['user_role'] !== 'admin' && $b['user_role'] === 'admin') return 1;

            // Si les deux sont admins ou les deux ne le sont pas, on garde l’ordre original
            return 0;
        });

        require __DIR__ . '/../Views/admin/adminUsers.php';
    }

    public function searchUsers()
    {
        $query = trim($_GET['query'] ?? '');

        if (strlen($query) < 1) {
            echo json_encode([]);
            exit;
        }

        $userModel = new AdminUser();
        $users = $userModel->searchByNameOrFirstname($query);

        header('Content-Type: application/json');
        echo json_encode($users);
        exit;
    }

    public function messages()
    {
        $messageModel = new AdminMessage();

        // Vérifie si un message doit être supprimé
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message_id'])) {
            $messageId = (int) $_POST['delete_message_id'];
            $messageModel->deleteMessage($messageId);

            // Redirection pour éviter la suppression multiple si l'utilisateur rafraîchit
            header('Location: ?url=adminMessages');
            exit;
        }

        // Sinon, affiche tous les messages
        $messages = $messageModel->findAll();

        // Trier les messages dans l'ordre croissante
        usort($messages, function ($a, $b) {
            return strtotime($a['message_sent_at']) <=> strtotime($b['message_sent_at']);
        });
        require __DIR__ . '/../Views/admin/adminMessages.php';
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

        if ($status === 'annulée') {

            $userId = $order['user_id'] ?? null;
            if ($userId) {

                // Récupère les articles
                $items = $orderItemModel->getByOrder($orderId);
                $totalSpent = 0.0;
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

        // Trier les commandes par order_pickup_time décroissante
        usort($commandes, function ($a, $b) {
            return strtotime($a['order_pickup_time']) <=> strtotime($b['order_pickup_time']);
        });


        require __DIR__ . '/../Views/admin/adminCommandes.php';
    }
}
