<?php
namespace App\Controllers;

use App\Models\Category;
use App\Models\Product;

class CategoryProductController
{
    private Category $categoryModel;
    private Product $productModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
        $this->productModel = new Product();
    }

    public function showCategoryWithProducts(int $id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        $products = $this->productModel->getProductsByCategory($id);

        // Associer une bannière selon la catégorie
        $bannerImages = [
            1 => "belleImage-pain.jpg",
            2 => "croissant.jpg",
            3 => "burger.png",
            4 => "presentation.jpg"
        ];

        $banner = $bannerImages[$category->category_id] ?? "default-banner.jpg";

        if (!$category) {
            echo "Catégorie introuvable";
            return;
        }

        require __DIR__ . "/../Views/02_produits.php";
    }

    public function showClickAndCollect()
    {
        $productModel = new Product();

        $categories = [
            'Pause Déjeuner' => [
                'image' => '/assets/image/C&C/baguetteApéro.png',
                'products' => $productModel->getProductsByCategory(1)
            ],
            'Les Pains' => [
                'image' => '/assets/image/C&C/pain-4.png',
                'products' => $productModel->getProductsByCategory(2)
            ],
            'Les Pâtisseries' => [
                'image' => '/assets/image/C&C/belleImage.jpg',
                'products' => $productModel->getProductsByCategory(3)
            ],
            'Les Viennoiseries' => [
                'image' => '/assets/image/C&C/croissant.jpg',
                'products' => $productModel->getProductsByCategory(4)
            ]
        ];

        require __DIR__ . '/../Views/04_click_and_collect.php';
    }
}
