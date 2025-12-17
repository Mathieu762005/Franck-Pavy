<?php

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use Stripe\Stripe;
use Stripe\Webhook;

// Charger les variables depuis .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Clé secrète Stripe
Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

// Contenu du webhook et signature
$payload = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

try {
    $event = Webhook::constructEvent(
        $payload,
        $sigHeader,
        getenv('STRIPE_WEBHOOK_SECRET')
    );
} catch (Exception $e) {
    http_response_code(400);
    exit;
}

// Si le paiement est complété
if ($event->type === 'checkout.session.completed') {
    $session = $event->data->object;
    $orderId = $session->client_reference_id;

    if ($orderId) {
        require __DIR__ . '/../../config/database.php';
        $order = new \App\Models\Order($db);

        // Mettre à jour le statut à "payée" au lieu de markAsPaid()
        $order->updateStatus((int) $orderId, 'payée');
    }
}

// Réponse 200 à Stripe
http_response_code(200);