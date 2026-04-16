<?php

namespace App\Controllers;

use App\Models\Meal;

class CartController extends BaseController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $mealModel = new Meal();
        $cartItems = array();
        $total = 0;

        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $cartItem) {
                // Find by meal_id since our structure changed
                $item = $mealModel->find($cartItem['meal_id']);
                if ($item) {
                    $item['cart_item_id'] = $cartItem['cart_item_id'];
                    $item['quantity'] = $cartItem['quantity'];
                    $item['notes'] = $cartItem['notes'];
                    $item['subtotal'] = $item['price'] * $cartItem['quantity'];
                    $cartItems[] = $item;
                    $total += $item['subtotal'];
                }
            }
        }

        $this->render('cart/index', array(
            'title' => 'Giỏ hàng',
            'cartItems' => $cartItems,
            'total' => $total
        ));
    }

    public function add()
    {
        // Support both POST (new modal) and GET (legacy/simple add)
        $mealId = isset($_REQUEST['id']) ? trim((string) $_REQUEST['id']) : '';
        $quantity = isset($_REQUEST['quantity']) ? (int) $_REQUEST['quantity'] : 1;
        $notes = isset($_REQUEST['notes']) ? trim((string) $_REQUEST['notes']) : '';

        if ($mealId !== '') {
            $mealModel = new Meal();
            if ($mealModel->find($mealId)) {
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = array();
                }
                
                // Find if identical item (meal_id + notes) exists
                $foundIndex = -1;
                foreach ($_SESSION['cart'] as $index => $cartItem) {
                    if ($cartItem['meal_id'] === $mealId && $cartItem['notes'] === $notes) {
                        $foundIndex = $index;
                        break;
                    }
                }

                if ($foundIndex !== -1) {
                    $_SESSION['cart'][$foundIndex]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][] = array(
                        'cart_item_id' => uniqid('ci_'),
                        'meal_id' => $mealId,
                        'quantity' => $quantity,
                        'notes' => $notes
                    );
                }
            }
        }

        // Return JSON for AJAX, otherwise redirect back
        if ($this->isAjax()) {
            return $this->json($this->getCartStatus());
        }

        $this->redirectBack();
    }

    public function status()
    {
        return $this->json($this->getCartStatus());
    }

    public function statusBar()
    {
        $status = $this->getCartStatus();
        $this->render('meals/floating_cart_bar', array(
            'cartCount' => $status['cartCount'],
            'cartTotal' => $status['cartTotal']
        ), null);
    }

    private function getCartStatus()
    {
        $count = 0;
        $total = 0;
        $mealModel = new Meal();

        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $cartItem) {
                $count += $cartItem['quantity'];
                $item = $mealModel->find($cartItem['meal_id']);
                if ($item) {
                    $total += $item['price'] * $cartItem['quantity'];
                }
            }
        }

        return array(
            'success' => true,
            'cartCount' => $count,
            'cartTotal' => $total,
            'cartTotalFormatted' => number_format($total, 0, ',', '.')
        );
    }

    private function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function remove()
    {
        $cartItemId = isset($_GET['cart_item_id']) ? trim((string) $_GET['cart_item_id']) : '';
        if ($cartItemId !== '' && isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $index => $cartItem) {
                if ($cartItem['cart_item_id'] === $cartItemId) {
                    unset($_SESSION['cart'][$index]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index
                    break;
                }
            }
        }
        $this->redirectBack();
    }

    public function update()
    {
        $cartItemId = isset($_GET['cart_item_id']) ? trim((string) $_GET['cart_item_id']) : '';
        $quantity = isset($_GET['quantity']) ? (int) $_GET['quantity'] : 1;

        if ($cartItemId !== '' && isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $index => $cartItem) {
                if ($cartItem['cart_item_id'] === $cartItemId) {
                    if ($quantity <= 0) {
                        unset($_SESSION['cart'][$index]);
                        $_SESSION['cart'] = array_values($_SESSION['cart']);
                    } else {
                        $_SESSION['cart'][$index]['quantity'] = $quantity;
                    }
                    break;
                }
            }
        }
        $this->redirectBack();
    }

    private function redirectBack()
    {
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/menu';
        header('Location: ' . $url);
        exit;
    }
}
