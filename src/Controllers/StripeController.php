<?php
namespace App\Controllers;

// On importe le contrôleur des commandes
use App\Controllers\OrderController;

// Librairie Stripe pour créer les sessions de paiement
use Stripe\Checkout\Session;
use Stripe\Stripe;
use PDO;

class StripeController
{
    // Propriété pour manipuler les commandes via OrderController
    private OrderController $orderController;

    // Constructeur : reçoit la connexion PDO et instancie OrderController
    public function __construct(PDO $db)
    {
        $this->orderController = new OrderController($db);
    }

    // Méthode privée pour rediriger avec un message d'erreur
    private function redirectWithError(string $msg, string $url)
    {
        $_SESSION['error'] = $msg;       // On stocke le message d'erreur dans la session
        header("Location: $url");        // On redirige vers l'URL donnée
        exit;                             // On arrête l'exécution du script
    }

    // Crée une session Stripe pour payer une commande
    public function checkoutStripe()
    {
        // On récupère l'ID de l'utilisateur connecté depuis la session
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId)
            $this->redirectWithError("Vous devez être connecté.", "index.php?url=login");

        // On récupère le créneau choisi par l'utilisateur
        $pickupTime = $_POST['pickup_time'] ?? null;
        if (!$pickupTime)
            $this->redirectWithError("Aucun créneau choisi.", "index.php?url=04_click_and_collect");

        // On crée la commande via OrderController
        $orderId = $this->orderController->createOrder($userId, $pickupTime);
        if (!$orderId)
            $this->redirectWithError("Impossible de créer la commande.", "index.php?url=04_click_and_collect");

        // On configure Stripe avec la clé secrète
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        // On récupère les détails de la commande pour calculer le montant total
        $orderDetails = $this->orderController->getOrderDetails($orderId);
        $totalCents = ($orderDetails['order']['order_total_price'] ?? 0) * 100; // Stripe attend le prix en centimes

        // Création de la session Stripe
        $session = Session::create([
            'payment_method_types' => ['card'],       // On accepte seulement les cartes
            'line_items' => [                          // Les produits/commandes à payer
                [
                    'price_data' => [
                        'currency' => 'eur',        // Devise
                        'product_data' => ['name' => 'Commande Click & Collect'], // Nom du produit
                        'unit_amount' => $totalCents, // Prix en centimes
                    ],
                    'quantity' => 1, // Toute la commande est considérée comme un article unique
                ]
            ],
            'mode' => 'payment',                        // Mode de paiement Stripe
            'client_reference_id' => $orderId,          // Permet d'identifier la commande
            'success_url' => "http://localhost:8000/index.php?url=checkout_success&order_id=$orderId", // URL de succès
            'cancel_url' => "http://localhost:8000/index.php?url=04_click_and_collect",                // URL d'annulation
        ]);

        // Redirection vers Stripe pour effectuer le paiement
        header("Location: " . $session->url);
        exit;
    }

    // Webhook Stripe : appelé automatiquement par Stripe après un paiement
    public function stripeWebhook()
    {
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $endpointSecret = $_ENV['STRIPE_WEBHOOK_SECRET'] ?? null;

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            http_response_code(400);
            exit();
        }

        // Si le paiement est complété
        if ($event->type === 'checkout.session.completed') {
            $orderId = $event->data->object->client_reference_id ?? null;
            if ($orderId) {
                // On met à jour le statut de la commande à "payée"
                $this->orderController->getOrderModel()->updateStatus((int) $orderId, 'payée');
            }
        }

        http_response_code(200);
    }

    // Affiche la page de succès après paiement
    public function handleCheckoutSuccess(int $orderId): array
    {
        $order = $this->orderController->getOrderForDisplay($orderId); // Récupération de la commande

        if (!$order) { // Si commande introuvable
            $_SESSION['error'] = "Commande introuvable.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        // On retourne les informations pour la vue : commande + utilisateur connecté
        return [
            'order' => $order,
            'user' => $_SESSION['user'] ?? null
        ];
    }
}