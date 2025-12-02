<?php
// $cartItems et $total calculé depuis le contrôleur
?>

<h1>Validation de la commande</h1>

<?php if (!empty($cartItems)): ?>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= $item['cart_items_quantity'] ?></td>
                    <td><?= number_format($item['cart_items_total_price'], 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Total : <?= number_format($total, 2) ?> €</h3>

    <form method="POST" action="checkout">
        <label for="pickup_time">Heure de retrait :</label>
        <input type="time" name="pickup_time" required>
        <input type="hidden" name="total_price" value="<?= $total ?>">
        <button type="submit">Valider la commande</button>
    </form>

<?php else: ?>
    <p>Votre panier est vide.</p>
<?php endif; ?>