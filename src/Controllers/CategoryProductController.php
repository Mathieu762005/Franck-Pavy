<?php
// On indique que cette classe appartient au namespace "Controllers"
namespace App\Controllers;

// On importe les modèles nécessaires
use App\Models\Category;
use App\Models\Product;
use App\Models\Database;

// Définition de la classe CategoryProductController
class CategoryProductController
{
    private Category $categoryModel; // Objet pour gérer les catégories
    private Product $productModel;   // Objet pour gérer les produits

    // Constructeur
    public function __construct()
    {
        // Crée l'instance PDO pour la base de données
        $db = Database::createInstancePDO();

        // Instancie les modèles
        $this->categoryModel = new Category($db);
        $this->productModel = new Product($db); // Product a besoin de la connexion
    }

    /**
     * Affiche une catégorie et tous ses produits
     * @param int $id ID de la catégorie
     */
    public function showCategoryWithProducts(int $id)
    {
        // Récupère la catégorie
        $category = $this->categoryModel->getCategoryById($id);

        // Récupère tous les produits de cette catégorie
        $products = $this->productModel->getByCategory($id);

        // Si la catégorie n'existe pas, afficher un message
        if (!$category) {
            echo "Catégorie introuvable";
            return;
        }

        // Images par défaut pour chaque catégorie
        $bannerImages = [
            1 => "/assets/image/Categories/belleimage-pain.jpg",
            2 => "/assets/image/Categories/croissant.jpg",
            3 => "/assets/image/Categories/burgerFrite.png",
            4 => "/assets/image/Categories/presentationPatisserie.jpg"
        ];

        // Image de fallback si aucune correspondance
        $defaultBanner = "/assets/image/Categories/default-banner.jpg";

        // Choisir l'image correspondant à la catégorie ou l'image par défaut
        $banner = $bannerImages[$category["category_id"]] ?? $defaultBanner;

        // Préparer les données à envoyer à la vue
        $categories = [
            [
                'category_id' => $category['category_id'],
                'category_name' => $category['category_name'],
                'category_description' => $category['category_description'] ?? '',
                'image' => $banner,
                'products' => $products ?: [] // si aucun produit, tableau vide
            ]
        ];

        // Affiche la vue
        require __DIR__ . "/../Views/02_produits.php";
    }

    /**
     * Affiche toutes les catégories avec leurs produits pour Click & Collect
     * @return array Tableau des catégories et produits
     */
    public function showClickAndCollect(): array
    {
        // Ici on crée manuellement un tableau avec toutes les catégories et leurs produits
        $categories = [
            [
                'category_id' => 1,
                'category_name' => 'Les Pains',
                'image' => '/assets/image/C&C/pain-6.png',
                'products' => $this->productModel->getByCategory(1) ?: []
            ],
            [
                'category_id' => 2,
                'category_name' => 'Les Viennoiseries',
                'image' => '/assets/image/C&C/croissant.jpg',
                'products' => $this->productModel->getByCategory(2) ?: []
            ],
            [
                'category_id' => 3,
                'category_name' => 'Pause Déjeuner',
                'image' => '/assets/image/C&C/baguetteApéro.png',
                'products' => $this->productModel->getByCategory(3) ?: []
            ],
            [
                'category_id' => 4,
                'category_name' => 'Les Pâtisseries',
                'image' => '/assets/image/C&C/belleImage.jpg',
                'products' => $this->productModel->getByCategory(4) ?: []
            ]
        ];

        // Retourne le tableau
        return $categories;
    }
}