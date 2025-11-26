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

        if (!$category) {
            echo "Catégorie introuvable";
            return;
        }

        require __DIR__ . "/../Views/02_produits.php";
    }
}
