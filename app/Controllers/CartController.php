<?php

namespace App\Controllers;

use App\Models\Meal;
use App\Models\Order;
use App\Models\Table;
use Throwable;

class CartController extends BaseController
{
    private $maxCartItemQuantity = 50;
    private $maxItemNoteLength = 500;
    private $flashErrorSessionKey = 'cart_flash_error';

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

        $tableModel = new Table();
        $tables = $tableModel->getAllAvailable();
        $currentTableId = isset($_SESSION['table_id']) ? $_SESSION['table_id'] : null;

        $this->render('cart/index', array(
            'title' => 'Giỏ hàng',
            'cartItems' => $cartItems,
            'total' => $total,
            'tables' => $tables,
            'currentTableId' => $currentTableId
        ));
    }

    public function setTable()
    {
        $tableId = isset($_GET['table_id']) ? trim((string) $_GET['table_id']) : '';

        if ($tableId === '' || !$this->isValidUuid($tableId)) {
            return $this->json(array('success' => false, 'message' => 'Mã bàn không hợp lệ.'));
        }

        $tableModel = new Table();
        $table = $tableModel->find($tableId);

        if (!$table || ($table['status'] ?? '') !== 'available') {
            return $this->json(array('success' => false, 'message' => 'Bàn được chọn hiện không khả dụng.'));
        }

        $_SESSION['table_id'] = $tableId;
        return $this->json(array('success' => true, 'table_id' => $tableId));
    }

    public function add()
    {
        // Support both POST (new modal) and GET (legacy/simple add)
        $mealId = isset($_REQUEST['id']) ? trim((string) $_REQUEST['id']) : '';
        $quantity = $this->parseIntInRange($_REQUEST['quantity'] ?? 1, 1, $this->maxCartItemQuantity);
        $notes = isset($_REQUEST['notes']) ? trim((string) $_REQUEST['notes']) : '';

        if ($mealId === '' || !$this->isValidUuid($mealId)) {
            return $this->handleInvalidCartRequest('Mã món ăn không hợp lệ.');
        }

        if ($quantity === false) {
            return $this->handleInvalidCartRequest('Số lượng món phải từ 1 đến 50.');
        }

        if (mb_strlen($notes) > $this->maxItemNoteLength) {
            return $this->handleInvalidCartRequest('Ghi chú món ăn không được vượt quá 500 ký tự.');
        }

        $mealModel = new Meal();
        $meal = $mealModel->find($mealId);
        if (!$meal || (int) ($meal['is_available'] ?? 0) !== 1) {
            return $this->handleInvalidCartRequest('Món ăn không tồn tại hoặc đã ngưng phục vụ.');
        }

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
            $updatedQuantity = (int) $_SESSION['cart'][$foundIndex]['quantity'] + $quantity;
            $_SESSION['cart'][$foundIndex]['quantity'] = min($updatedQuantity, $this->maxCartItemQuantity);
        } else {
            $_SESSION['cart'][] = array(
                'cart_item_id' => uniqid('ci_'),
                'meal_id' => $mealId,
                'quantity' => $quantity,
                'notes' => $notes
            );
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
        if ($this->isAjax()) {
            return $this->json($this->getCartStatus());
        }
        $this->redirectBack();
    }

    public function update()
    {
        $cartItemId = isset($_GET['cart_item_id']) ? trim((string) $_GET['cart_item_id']) : '';
        $quantity = $this->parseIntInRange($_GET['quantity'] ?? null, 0, $this->maxCartItemQuantity);

        if ($quantity === false) {
            return $this->handleInvalidCartRequest('Số lượng món phải từ 0 đến 50.');
        }

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
        if ($this->isAjax()) {
            return $this->json($this->getCartStatus());
        }
        $this->redirectBack();
    }

    private function redirectBack()
    {
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/menu';
        header('Location: ' . $url);
        exit;
    }

    public function placeOrder()
    {
        $cart = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : array();
        
        if (empty($cart)) {
            $this->setCartFlashError('Giỏ hàng đang trống, không thể đặt món.');
            header('Location: ' . url('/menu'));
            exit;
        }

        $tableModel = new Table();

        // Simulate Table ID (usually from QR scan)
        // For demo, we check session or default to a demo table
        $tableId = isset($_SESSION['table_id']) ? trim((string) $_SESSION['table_id']) : null;

        if ($tableId !== null && $tableId !== '') {
            if (!$this->isValidUuid($tableId)) {
                $tableId = null;
                unset($_SESSION['table_id']);
            } else {
                $table = $tableModel->find($tableId);
                if (!$table || ($table['status'] ?? '') !== 'available') {
                    $tableId = null;
                    unset($_SESSION['table_id']);
                }
            }
        }

        if (!$tableId) {
            // Check if there are any tables available
            $tables = $tableModel->getAllAvailable();
            if (!empty($tables)) {
                $tableId = $tables[0]['id']; // Assign first available table for demo
                $_SESSION['table_id'] = $tableId;
            } else {
                // Fallback or error
                $this->setCartFlashError('Hiện không có bàn trống để tiếp nhận đơn hàng.');
                header('Location: ' . url('/cart'));
                exit;
            }
        }

        $mealModel = new Meal();
        $orderModel = new Order();
        
        $cartItems = array();
        $grandTotal = 0;
        
        foreach ($cart as $cartItem) {
            $mealId = trim((string) ($cartItem['meal_id'] ?? ''));
            $quantity = $this->parseIntInRange($cartItem['quantity'] ?? null, 1, $this->maxCartItemQuantity);
            $notes = trim((string) ($cartItem['notes'] ?? ''));

            if (!$this->isValidUuid($mealId) || $quantity === false || mb_strlen($notes) > $this->maxItemNoteLength) {
                continue;
            }

            $item = $mealModel->find($mealId);
            if ($item && (int) ($item['is_available'] ?? 0) === 1) {
                $item['quantity'] = $quantity;
                $item['notes'] = ($notes === '') ? null : $notes;
                $item['subtotal'] = $item['price'] * $quantity;
                $cartItems[] = $item;
                $grandTotal += $item['subtotal'];
            }
        }

        if (empty($cartItems) || $grandTotal <= 0) {
            $this->setCartFlashError('Giỏ hàng có dữ liệu không hợp lệ. Vui lòng kiểm tra lại trước khi đặt món.');
            header('Location: ' . url('/cart'));
            exit;
        }

        try {
            $orderId = $this->uuid();
            $orderModel->create(array(
                'id' => $orderId,
                'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
                'table_id' => $tableId,
                'total_amount' => $grandTotal,
                'order_status' => 'pending',
                'payment_status' => 'unpaid'
            ));

            $orderModel->addItems($orderId, $cartItems);

            // Clear cart
            unset($_SESSION['cart']);

            $this->render('cart/success', array(
                'title' => 'Đặt món thành công',
                'orderId' => $orderId,
                'total' => $grandTotal
            ));
        } catch (Throwable $e) {
            error_log('Order error: ' . $e->getMessage());
            $this->setCartFlashError('Không thể tạo đơn hàng lúc này. Vui lòng thử lại sau.');
            header('Location: ' . url('/cart'));
            exit;
        }
    }

    private function handleInvalidCartRequest($message)
    {
        if ($this->isAjax()) {
            return $this->json(array('success' => false, 'message' => $message));
        }

        $this->setCartFlashError($message);

        $this->redirectBack();
    }

    private function setCartFlashError($message)
    {
        $_SESSION[$this->flashErrorSessionKey] = trim((string) $message);
    }

    private function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
