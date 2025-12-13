<?php

namespace App\Controllers;

use App\Models\Contact;

class ContactController
{
    private Contact $contactModel;

    public function __construct()
    {
        $this->contactModel = new Contact();
    }

    // Action pour envoyer un message depuis le formulaire de contact
    public function send()
    {
        $errors = [];
        $messageSent = false; // pour la vue

        // On récupère l'ID de l'utilisateur connecté depuis la session
        $userId = $_SESSION['user']['id'] ?? null;
        if (!is_numeric($userId)) {
            $errors['auth'] = `<i class="bi bi-exclamation-circle-fill fs-6"></i>Utilisateur non connecté.`;
        } else {
            $userId = (int) $userId;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Vérification du champ "subject"
            if (empty($_POST["subject"])) {
                $errors['subject'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Objet obligatoire';
            }

            // Vérification du champ "body"
            if (empty($_POST["body"])) {
                $errors['body'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Message obligatoire';
            }

            // Si aucune erreur, on peut créer le message
            if (empty($errors)) {
                $success = $this->contactModel->createMessage(
                    $_POST["subject"],
                    $_POST["body"],
                    $userId
                );

                if ($success) {
                    $messageSent = true;
                } else {
                    $errors['server'] = "Une erreur s'est produite, veuillez réessayer ultérieurement.";
                }
            }
        }
        require __DIR__ . "/../Views/05_contact.php";
    }
}