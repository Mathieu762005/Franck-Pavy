<?php

// On indique que cette classe appartient au namespace "Controllers"
namespace App\Controllers;

// On importe le modèle Contact pour pouvoir l'utiliser ici
use App\Models\Contact;

// Définition de la classe ContactController
class ContactController
{
    private Contact $contactModel; // Objet pour gérer les messages de contact

    // Constructeur
    public function __construct()
    {
        // Instancie le modèle Contact
        $this->contactModel = new Contact();
    }

    /**
     * Action pour envoyer un message depuis le formulaire de contact
     */
    public function send()
    {
        $errors = [];        // Tableau pour stocker les erreurs
        $messageSent = false; // Indicateur pour la vue

        // Récupère l'ID de l'utilisateur connecté depuis la session
        $userId = $_SESSION['user']['id'] ?? null;
        if (!is_numeric($userId)) {
            // Si pas connecté, on ajoute une erreur
            $errors['auth'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i>Utilisateur non connecté.';
        } else {
            $userId = (int) $userId;
        }

        // Vérifie que le formulaire est soumis en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Vérifie que le champ "subject" n'est pas vide
            if (empty($_POST["subject"])) {
                $errors['subject'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Objet obligatoire';
            }

            // Vérifie que le champ "body" n'est pas vide
            if (empty($_POST["body"])) {
                $errors['body'] = '<i class="bi bi-exclamation-circle-fill fs-6"></i> Message obligatoire';
            }

            // Si aucune erreur, on crée le message via le modèle
            if (empty($errors)) {
                $success = $this->contactModel->createMessage(
                    $_POST["subject"],
                    $_POST["body"],
                    $userId
                );

                // Si succès, on marque le message comme envoyé
                if ($success) {
                    $messageSent = true;
                } else {
                    $errors['server'] = "Une erreur s'est produite, veuillez réessayer ultérieurement.";
                }
            }
        }

        // Affiche la vue du formulaire de contact
        require __DIR__ . "/../Views/05_contact.php";
    }
}
