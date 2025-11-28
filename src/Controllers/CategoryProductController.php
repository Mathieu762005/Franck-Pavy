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
}
