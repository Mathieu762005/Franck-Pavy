<?php

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use Stripe\Stripe;
use Stripe\Webhook;

// Charger .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Stripe key
Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

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

// 🔥 Paiement confirmé
if ($event->type === 'checkout.session.completed') {
    $session = $event->data->object;
    $orderId = $session->client_reference_id;

    require __DIR__ . '/../../config/database.php';
    $order = new \App\Models\Order($db);
    $order->markAsPaid((int)$orderId);
}

http_response_code(200);