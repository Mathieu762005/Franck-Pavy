<?php
namespace App\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Database;

class CategoryProductController
{
    private Category $categoryModel;
    private Product $productModel;

    public function __construct()
    {
        $db = Database::createInstancePDO(); // récupérer l'instance PDO
        $this->categoryModel = new Category();
        $this->productModel = new Product($db); // passer $db au modèle Product
    }

    /**
     * Afficher une catégorie avec ses produits
     */
    public function showCategoryWithProducts(int $id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        $products = $this->productModel->getByCategory($id);

        if (!$category) {
            echo "Catégorie introuvable";
            return;
        }

        // Bannière selon la catégorie
        $bannerImages = [
            1 => "/assets/image/C&C/pain-4.png",
            2 => "/assets/image/C&C/croissant.jpg",
            3 => "/assets/image/C&C/baguetteApéro.png",
            4 => "/assets/image/C&C/belleImage.jpg"
        ];

        $banner = $bannerImages[$category['category_id']] ?? "/assets/image/C&C/default-banner.jpg";

        require __DIR__ . "/../Views/02_produits.php";
    }

    /**
     * Afficher la page Click & Collect avec toutes les catégories et leurs produits
     */
    public function showClickAndCollect(): array
    {
        $categories = [
            [
                'category_id' => 1,
                'category_name' => 'Les Pains',
                'image' => '/assets/image/C&C/pain-4.png',
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

        return $categories; // <-- ici
    }
}