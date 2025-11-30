<?php
namespace App\Controllers;

use App\Models\AdminUser;
use App\Models\AdminProduct;
use App\Models\AdminMessage;
use App\Models\AdminCommande;


class AdminController {
    public function users() {
        $userModel = new AdminUser();
        $users = $userModel->findAll();
        require __DIR__ . '/../Views/admin/adminUsers.php';
    }

    public function messages() {
        $messageModel = new AdminMessage();
        $messages = $messageModel->findAll();
        require __DIR__ . '/../Views/admin/adminMessages.php';
    }

    public function produits() {
        $produitModel = new AdminProduct();
        $produits = $produitModel->findAll();
        require __DIR__ . '/../Views/admin/adminProducts.php';
    }

    public function commandes() {
        $commandeModel = new AdminCommande();
        $commandes = $commandeModel->findAll();
        require __DIR__ . '/../Views/admin/adminCommandes.php';
    }
}
