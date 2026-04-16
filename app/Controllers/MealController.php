<?php

namespace App\Controllers;

use App\Models\Meal;
use App\Models\Category;

class MealController extends BaseController
{
    public function index()
    {
        $mealModel = new Meal();
        $categoryModel = new Category();

        $search = isset($_GET['search']) ? trim((string) $_GET['search']) : '';
        $categoryId = isset($_GET['category_id']) ? trim((string) $_GET['category_id']) : '';

        $menuItems = $mealModel->getAllAvailable($search, $categoryId);
        $categories = $categoryModel->all();

        // Calculate Cart Totals for Floating Bar
        $cartCount = 0;
        $cartTotal = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $cartItem) {
                $item = $mealModel->find($cartItem['meal_id']);
                if ($item) {
                    $cartCount += $cartItem['quantity'];
                    $cartTotal += $item['price'] * $cartItem['quantity'];
                }
            }
        }

        $this->render('meals/index', array(
            'title' => 'Thực đơn',
            'menuItems' => $menuItems,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
            'cartCount' => $cartCount,
            'cartTotal' => $cartTotal
        ));
    }
}
