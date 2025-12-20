<?php

// Namespace du contrôleur (organisation du projet)
namespace App\Controllers;

// Import des modèles utilisés par le contrôleur admin
use App\Models\AdminUser;
use App\Models\AdminProduct;
use App\Models\AdminMessage;
use App\Models\AdminCommande;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Category;
use PDO;

// Déclaration du contrôleur Admin
class AdminController
{
    // Variable qui va contenir la connexion à la base de données
    private PDO $db;

    // Constructeur appelé automatiquement quand on crée le contrôleur
    public function __construct(PDO $db)
    {
        // On stocke la connexion PDO dans la propriété $db
        $this->db = $db;
    }

    // ===================== USERS =====================
    public function users()
    {
        // On crée une instance du modèle AdminUser
        $userModel = new AdminUser();

        // ----- SUPPRESSION UTILISATEUR -----
        // Si le formulaire est envoyé en POST et qu'un ID utilisateur est présent
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
            // On force l'ID en entier pour la sécurité
            $userId = (int) $_POST['delete_user_id'];

            // On supprime l'utilisateur
            $userModel->deleteUser($userId);

            // Redirection pour éviter une double suppression
            header('Location: ?url=adminUsers');
            exit;
        }

        // ----- RECHERCHE UTILISATEUR -----
        // On récupère le texte de recherche (ou vide si absent)
        $search = trim($_GET['search'] ?? '');

        // Si une recherche est saisie
        if ($search !== '') {
            // Recherche par nom ou prénom
            $users = $userModel->searchByNameOrFirstname($search);
        } else {
            // Sinon on récupère tous les utilisateurs
            $users = $userModel->findAll();
        }

        // ----- TRI DES UTILISATEURS -----
        // On affiche les admins en premier
        usort($users, function ($a, $b) {

            // Si A est admin et B ne l'est pas → A avant
            if ($a['user_role'] === 'admin' && $b['user_role'] !== 'admin') return -1;

            // Si B est admin et A ne l'est pas → B avant
            if ($a['user_role'] !== 'admin' && $b['user_role'] === 'admin') return 1;

            // Sinon on ne change rien
            return 0;
        });

        // Chargement de la vue admin users
        require __DIR__ . '/../Views/admin/adminUsers.php';
    }

    // ===================== SEARCH USERS (AJAX) =====================
    public function searchUsers()
    {
        // Récupération de la recherche
        $query = trim($_GET['query'] ?? '');

        // Si la recherche est trop courte, on retourne un tableau vide
        if (strlen($query) < 1) {
            echo json_encode([]);
            exit;
        }

        // Recherche utilisateur
        $userModel = new AdminUser();
        $users = $userModel->searchByNameOrFirstname($query);

        // Réponse JSON
        header('Content-Type: application/json');
        echo json_encode($users);
        exit;
    }

    // ===================== MESSAGES =====================
    public function messages()
    {
        // Modèle des messages
        $messageModel = new AdminMessage();

        // ----- SUPPRESSION MESSAGE -----
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message_id'])) {
            $messageId = (int) $_POST['delete_message_id'];
            $messageModel->deleteMessage($messageId);

            // Redirection après suppression
            header('Location: ?url=adminMessages');
            exit;
        }

        // Récupération de tous les messages
        $messages = $messageModel->findAll();

        // Tri des messages par date d'envoi
        usort($messages, function ($a, $b) {
            return strtotime($a['message_sent_at']) <=> strtotime($b['message_sent_at']);
        });

        // Chargement de la vue
        require __DIR__ . '/../Views/admin/adminMessages.php';
    }

    // ===================== PRODUITS =====================
    public function produits($db)
    {
        // Modèles produits et catégories
        $produitModel = new AdminProduct();
        $categoryModel = new Category($db);

        // ----- ACTIONS POST -----
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ----- SUPPRESSION PRODUIT -----
            if (isset($_POST['delete_product_id'])) {
                $produitModel->deleteProduit((int)$_POST['delete_product_id']);
                header('Location: ?url=adminProducts');
                exit;
            }

            // ----- MODIFICATION PRODUIT -----
            if (isset($_POST['edit_product'])) {

                // Image actuelle conservée par défaut
                $imagePath = $_POST['current_image'] ?? '';

                // Si une nouvelle image est envoyée
                if (!empty($_FILES['product_image']['name'])) {

                    // Dossier de stockage
                    $targetDir = __DIR__ . '/../../public/assets/image/';

                    // Nom original sans extension
                    $originalName = pathinfo($_FILES['product_image']['name'], PATHINFO_FILENAME);

                    // Extension du fichier
                    $extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);

                    // Nom sécurisé et unique
                    $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName) . '.' . $extension;

                    // Chemin final
                    $targetFile = $targetDir . $filename;

                    // Déplacement du fichier
                    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {

                        // Suppression de l'ancienne image
                        if (!empty($_POST['current_image']) && file_exists($targetDir . $_POST['current_image'])) {
                            unlink($targetDir . $_POST['current_image']);
                        }

                        // Nouvelle image enregistrée
                        $imagePath = $filename;
                    }
                }

                // Mise à jour du produit en base
                $produitModel->updateProduit(
                    (int)$_POST['product_id'],
                    trim($_POST['product_name']),
                    trim($_POST['product_subtitle']),
                    trim($_POST['product_description']),
                    (float)$_POST['product_price'],
                    $imagePath,
                    (int)$_POST['category_id'],
                    (int)$_POST['product_available']
                );

                // Redirection après modification
                header('Location: ?url=adminProducts');
                exit;
            }
        }

        // ----- AFFICHAGE -----
        $produits = $produitModel->findAll();
        $categories = $categoryModel->getAll();

        // Création d'un tableau de catégories sans doublons
        $uniqueCategories = [];
        foreach ($categories as $cat) {
            $uniqueCategories[$cat['category_id']] = $cat['category_name'];
        }

        // Chargement de la vue
        require __DIR__ . '/../Views/admin/adminProducts.php';
    }

    // ===================== STOCK PRODUIT (AJAX) =====================
    public function toggleProductStock()
    {
        // Lecture du JSON envoyé par fetch
        $data = json_decode(file_get_contents('php://input'), true);

        // Vérification des données
        if (!isset($data['product_id'], $data['product_available'])) {
            http_response_code(400);
            return;
        }

        // Mise à jour du stock
        $produitModel = new AdminProduct();
        $produitModel->updateStock(
            (int)$data['product_id'],
            (int)$data['product_available']
        );

        http_response_code(200);
    }

    // ===================== COMMANDES =====================
    // ---------- HANDLE COMMANDES ----------
    // Combine affichage et mise à jour des commandes dans un seul appel
    public function commandes()
    {
        $commandeModel = new AdminCommande();

        // ----- GESTION DU POST -----
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['order_id'], $_POST['status'])) {

                $orderId = (int) $_POST['order_id'];
                $status  = $_POST['status'];

                // ✅ UTILISER LA LOGIQUE MÉTIER
                $this->updateOrderStatus($orderId, $status);

                // Redirection POST/REDIRECT/GET
                header('Location: ?url=adminCommandes');
                exit;
            }
        }

        // ----- AFFICHAGE -----
        $commandes = $commandeModel->findAllWithDetails();
        require __DIR__ . '/../Views/admin/adminCommandes.php';
    }

    // ===================== UPDATE STATUS =====================
    public function updateOrderStatus(int $orderId, string $status): bool
    {
        $orderModel = new Order($this->db);
        $orderItemModel = new OrderItem($this->db);

        // Récupération commande
        $order = $orderModel->getById($orderId);
        if (!$order) {
            return false;
        }

        // Mise à jour du statut
        $orderModel->updateStatus($orderId, $status);

        /*
    |--------------------------------------------------------------------------
    | STATUT : TERMINÉE
    |--------------------------------------------------------------------------
    */
        if ($status === 'terminée') {

            $userId = $order['user_id'] ?? null;

            if ($userId) {
                $items = $orderItemModel->getByOrder($orderId);
                $totalSpent = 0;

                foreach ($items as $item) {
                    $totalSpent += (float) $item['total_price'];
                }

                $user = new User($this->db);
                if ($user->loadById($userId)) {
                    $user->incrementStats($totalSpent);
                }
            }

            // Suppression items + commande
            $orderItemModel->deleteByOrder($orderId);
            $orderModel->delete($orderId);
        }

        /*
    |--------------------------------------------------------------------------
    | STATUT : ANNULÉE
    |--------------------------------------------------------------------------
    */
        if ($status === 'annulée') {

            // ❌ PAS de stats
            // ❌ PAS de calcul

            // Suppression items + commande
            $orderItemModel->deleteByOrder($orderId);
            $orderModel->delete($orderId);
        }

        return true;
    }
}
