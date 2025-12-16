<?php
// On déclare le namespace pour organiser le code
namespace App\Controllers;

// On importe les modèles utilisés par le contrôleur
use App\Models\Order;       // Modèle pour gérer les commandes
use App\Models\OrderItem;   // Modèle pour gérer les articles des commandes
use App\Models\Cart;        // Modèle pour gérer le panier
use DateTime;               // Classe pour manipuler les dates et heures
use DateTimeZone;           // Classe pour gérer les fuseaux horaires
use PDO;                    // Classe pour la connexion à la base de données

class OrderController
{
    // Définition des propriétés pour stocker les modèles
    private Order $order;           // Pour accéder aux commandes
    private OrderItem $orderItem;   // Pour accéder aux articles des commandes
    private Cart $cart;             // Pour accéder aux articles du panier

    // Constructeur du contrôleur
    public function __construct(PDO $db)
    {
        $this->order = new Order($db);          // Instancie le modèle Order avec la connexion PDO
        $this->orderItem = new OrderItem($db);  // Instancie le modèle OrderItem
        $this->cart = new Cart($db);            // Instancie le modèle Cart
    }


    /**
     * Crée une commande à partir du panier
     * @param int $userId : ID de l'utilisateur
     * @param string $pickupTime : créneau choisi
     * @return int|null : ID de la commande ou null si panier vide
     */
    public function createOrder(int $userId, string $pickupTime): ?int
    {
        // Récupère tous les articles du panier de l'utilisateur
        $items = $this->cart->getAllItems($userId);

        // Si le panier est vide, retourne null (aucune commande)
        if (empty($items))
            return null;

        // Calcul du prix total du panier
        $total = array_sum(array_column($items, 'cart_items_total_price'));

        // Crée la commande dans la base et récupère son ID
        $orderId = $this->order->create($userId, $total, $pickupTime);

        // Si la commande a bien été créée (ID > 0)
        if ($orderId > 0) {
            $this->orderItem->copyCartToOrder($orderId, $userId); // Copie les articles du panier dans la commande
            $this->cart->clearUserCart($userId);                  // Vide le panier
        }

        // Retourne l'ID de la commande créée
        return $orderId;
    }


    /**
     * Traite le formulaire Click & Collect
     */
    public function submitPickupTime()
    {
        // Vérifie que la requête est en POST et que le créneau est défini
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['pickup_time']))
            return;

        $userId = $_SESSION['user']['id'] ?? null;   // Récupère l'ID utilisateur depuis la session
        $pickupTime = $_POST['pickup_time'] ?? '';   // Récupère le créneau choisi

        // Vérifie que l'utilisateur est connecté
        if (!$userId) {
            $_SESSION['error'] = "You must be logged in to place an order."; // message d'erreur
            header("Location: index.php?url=login"); // redirige vers la page login
            exit; // stop le script
        }

        // Récupère l'heure actuelle dans le fuseau de Paris
        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));

        // Convertit le créneau choisi en objet DateTime
        $pickup = DateTime::createFromFormat('H:i', $pickupTime, new DateTimeZone('Europe/Paris'));

        // Définit la date du créneau comme aujourd'hui
        $pickup->setDate((int) $now->format('Y'), (int) $now->format('m'), (int) $now->format('d'));

        // Vérifie si le créneau est déjà passé (marge de 15 minutes)
        if ((clone $pickup)->modify('-15 minutes') <= $now) {
            $_SESSION['error'] = "This time slot has already passed or is too close."; // message erreur
            header("Location: index.php?url=04_click_and_collect"); // redirection
            exit;
        }

        // Vérifie si le créneau est complet (10 commandes max)
        if ($this->order->getReservationCount($pickupTime) >= 10) {
            $_SESSION['error'] = "This time slot is full.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        // Crée la commande avec la méthode createOrder
        $orderId = $this->createOrder($userId, $pickupTime);

        // Si la commande a été créée avec succès
        if ($orderId) {
            $_SESSION['success'] = "Order successfully placed."; // message succès
            header("Location: index.php?url=order_confirmation&order_id=$orderId"); // redirection
            exit;
        }

        // Si erreur lors de la création
        $_SESSION['error'] = "Error creating order.";
        header("Location: index.php?url=04_click_and_collect");
        exit;
    }


    /**
     * Récupère toutes les commandes d'un utilisateur
     */
    public function getUserOrders(int $userId): array
    {
        return $this->order->getByUser($userId); // appelle le modèle Order
    }


    /**
     * Récupère les détails d'une commande avec ses articles
     */
    public function getOrderDetails(int $orderId): array
    {
        $orderData = $this->order->getById($orderId);       // infos de la commande
        $items = $this->orderItem->getByOrder($orderId);    // articles de la commande

        return ['order' => $orderData, 'items' => $items];  // retourne les deux ensemble
    }


    /**
     * Formate l'heure de retrait pour l'affichage
     */
    public function formatPickupTime(array $order): string
    {
        $pickupTime = $order['order_pickup_time'] ?? ''; // récupère l'heure de retrait
        if (!$pickupTime)
            return 'Not set';             // si vide, retourne "non défini"

        $displayTime = substr($pickupTime, 0, 5); // garde seulement HH:MM

        $now = new DateTime('now', new DateTimeZone('Europe/Paris')); // date actuelle
        $orderDate = new DateTime($order['order_date'], new DateTimeZone('Europe/Paris')); // date commande

        // Crée un objet DateTime pour le créneau exact
        $pickupDate = clone $orderDate;
        $pickupDate->setTime((int) substr($pickupTime, 0, 2), (int) substr($pickupTime, 3, 2));

        // Si l'heure est passée, ajoute "pour demain"
        if ($pickupDate <= $now)
            $displayTime .= ' (for tomorrow)';

        return $displayTime; // retourne la chaîne formatée
    }


    /**
     * Récupère les commandes avec numérotation CMD_x par jour
     */
    public function getUserOrdersWithNumber(int $userId): array
    {
        $allOrders = $this->order->getAllOrders(); // toutes les commandes
        $resetHour = 19;   // à partir de 19h, le compteur change de jour
        $counter = 1;      // compteur CMD_x
        $currentDay = null; // jour courant

        foreach ($allOrders as &$order) {
            $orderDate = new DateTime($order['order_date']); // date de la commande
            if ((int) $orderDate->format('H') >= $resetHour)
                $orderDate->modify('+1 day'); // si après 19h → lendemain

            $day = $orderDate->format('Y-m-d'); // extrait le jour

            if ($currentDay !== $day) { // nouveau jour
                $counter = 1;           // réinitialise le compteur
                $currentDay = $day;
            }

            $order['cmd_number'] = 'CMD_' . $counter++;                    // ajoute CMD_x
            $order['order_time_formatted'] = $orderDate->format('H:i');   // heure formatée
        }

        // Filtre uniquement les commandes de l'utilisateur connecté
        return array_values(array_filter($allOrders, fn($order) => $order['user_id'] === $userId));
    }


    /**
     * Récupère les commandes avec leurs articles
     */
    public function getUserOrdersWithItems(int $userId): array
    {
        $orders = $this->getUserOrders($userId); // récupère toutes les commandes de l'utilisateur

        foreach ($orders as &$order) {
            $details = $this->getOrderDetails($order['order_id']); // récupère les articles
            $order['items'] = $details['items'] ?? [];             // ajoute les articles à la commande
        }

        return $orders; // retourne toutes les commandes avec leurs items
    }

    /**
     * Récupère une commande et prépare l'heure pour affichage
     * @param int $orderId
     * @return array|null : retourne la commande prête à afficher ou null si introuvable
     */
    public function getOrderForDisplay(int $orderId): ?array
    {
        $order = $this->getOrderDetails($orderId); // récupère la commande avec ses items

        if (!$order || empty($order['order'])) {
            return null; // commande introuvable
        }

        // Prépare l'heure pour affichage
        $order['display_pickup_time'] = $this->formatPickupTime($order['order']);

        return $order;
    }


    /**
     * Génère les créneaux disponibles pour le Click & Collect
     */
    public function generateAvailableTimeSlots(): array
    {
        $now = new DateTime('now', new DateTimeZone('Europe/Paris')); // date actuelle

        // Fonction interne pour générer les créneaux pour un jour donné
        $generate = function (string $day, bool $ignorePast = false) use ($now) {
            $slots = [];
            $start = strtotime('07:00');
            $end = strtotime('19:00'); // horaires d'ouverture

            while ($start <= $end) {
                $slot = date('H:i', $start); // format HH:MM
                $slotTime = new DateTime("$day $slot", new DateTimeZone('Europe/Paris'));
                $slotTime->modify('-15 minutes'); // marge de sécurité

                $count = $this->order->getReservationCount($slot); // nombre de commandes pour ce créneau
                $isPast = !$ignorePast && $slotTime <= $now;       // indique si le créneau est passé

                if ($ignorePast || !$isPast) { // si on ignore le passé ou si le créneau est futur
                    $slots[] = ['time' => $slot, 'full' => $count >= 10, 'past' => $isPast];
                }

                $start = strtotime('+30 minutes', $start); // passe au créneau suivant
            }

            return $slots; // retourne les créneaux du jour
        };

        $day = $now->format('Y-m-d');
        $timeslots = $generate($day); // génère les créneaux pour aujourd'hui

        // Si tous les créneaux sont passés, génère ceux de demain
        if (array_reduce($timeslots, fn($c, $s) => $c && $s['past'], true)) {
            $tomorrow = (clone $now)->modify('+1 day')->format('Y-m-d');
            $timeslots = $generate($tomorrow, true);
        }

        return $timeslots; // retourne tous les créneaux disponibles
    }

    public function getOrderModel(): Order
    {
        return $this->order;
    }
}