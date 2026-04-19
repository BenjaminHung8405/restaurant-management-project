<?php

use App\Controllers\HomeController;
use App\Controllers\MealController;
use App\Controllers\CartController;
use App\Controllers\ReservationController;
use App\Controllers\AuthController;
use App\Controllers\AdminOrderController;
use App\Controllers\AdminMenuController;
use App\Controllers\AdminDashboardController;
use App\Controllers\KitchenController;
use App\Controllers\AdminPosController;
use App\Core\Router;

return function (Router $router) {
    $router->get('/', array(HomeController::class, 'index'));
    
    // Auth Routes
    $router->get('/login', array(AuthController::class, 'loginForm'));
    $router->post('/login', array(AuthController::class, 'login'));
    $router->get('/logout', array(AuthController::class, 'logout'));

    // Admin Routes
    $router->get('/admin', array(AdminDashboardController::class, 'index'));
    $router->get('/admin/dashboard', array(AdminDashboardController::class, 'index'));
    $router->get('/admin/orders', array(AdminOrderController::class, 'index'));
    $router->get('/admin/orders/api/list', array(AdminOrderController::class, 'getOrdersAjax'));
    $router->get('/admin/orders/api/items', array(AdminOrderController::class, 'getOrderItemsAjax'));
    $router->post('/admin/orders/update-status', array(AdminOrderController::class, 'updateStatus'));
    $router->post('/admin/orders/cleanup', array(AdminOrderController::class, 'cleanup'));
    
    $router->get('/admin/menu', array(AdminMenuController::class, 'index'));
    $router->get('/admin/menu/create', array(AdminMenuController::class, 'create'));
    $router->post('/admin/menu/store', array(AdminMenuController::class, 'store'));
    $router->get('/admin/menu/edit', array(AdminMenuController::class, 'edit'));
    $router->post('/admin/menu/update', array(AdminMenuController::class, 'update'));
    $router->post('/admin/menu/delete', array(AdminMenuController::class, 'delete'));
    $router->get('/admin/menu/delete', array(AdminMenuController::class, 'delete')); // Fallback for simple link delete
    
    // Admin Categories
    $router->get('/admin/categories', array(\App\Controllers\AdminCategoryController::class, 'index'));
    $router->post('/admin/categories/store', array(\App\Controllers\AdminCategoryController::class, 'store'));
    $router->post('/admin/categories/update', array(\App\Controllers\AdminCategoryController::class, 'update'));
    $router->post('/admin/categories/delete', array(\App\Controllers\AdminCategoryController::class, 'destroy'));

    // Admin Kitchen
    $router->get('/admin/kitchen', array(KitchenController::class, 'index'));
    $router->get('/admin/kitchen/api/pending-orders', array(KitchenController::class, 'getPendingOrders'));
    $router->post('/admin/kitchen/api/mark-item-cooking', array(KitchenController::class, 'markItemCooking'));
    $router->post('/admin/kitchen/api/mark-item-done', array(KitchenController::class, 'markItemDone'));

    // Admin Reservations
    $router->get('/admin/reservations', array(\App\Controllers\AdminReservationController::class, 'index'));
    $router->get('/admin/reservations/create', array(\App\Controllers\AdminReservationController::class, 'create'));
    $router->post('/admin/reservations/store', array(\App\Controllers\AdminReservationController::class, 'store'));
    $router->post('/admin/reservations/update-status', array(\App\Controllers\AdminReservationController::class, 'updateStatus'));

    // Admin Table Map
    $router->get('/admin/tables', array(\App\Controllers\AdminTableController::class, 'index'));
    $router->get('/admin/tables/api/status', array(\App\Controllers\AdminTableController::class, 'getTablesAjax'));
    $router->post('/admin/tables/api/store', array(\App\Controllers\AdminTableController::class, 'store'));
    $router->post('/admin/api/tables/clean', array(AdminPosController::class, 'cleanTable'));

    // POS APIs
    $router->get('/admin/api/pos/menu', array(AdminPosController::class, 'menu'));
    $router->get('/admin/api/pos/order', array(AdminPosController::class, 'getOrder'));
    $router->post('/admin/api/pos/order/save', array(AdminPosController::class, 'saveOrder'));
    $router->post('/admin/api/pos/order/checkout', array(AdminPosController::class, 'checkout'));
    
    // Meal Routes
    $router->get('/menu', array(MealController::class, 'index'));
    $router->get('/meals', array(MealController::class, 'index'));
    
    // Cart Routes
    $router->get('/cart', array(CartController::class, 'index'));
    $router->get('/cart/add', array(CartController::class, 'add'));
    $router->post('/cart/add', array(CartController::class, 'add'));
    $router->get('/cart/status', array(CartController::class, 'status'));
    $router->get('/cart/status_bar', array(CartController::class, 'statusBar'));
    $router->get('/cart/remove', array(CartController::class, 'remove'));
    $router->get('/cart/update', array(CartController::class, 'update'));
    $router->get('/cart/set-table', array(CartController::class, 'setTable'));
    $router->post('/cart/place-order', array(CartController::class, 'placeOrder'));
    
    // Reservation Routes
    $router->get('/reservation', array(ReservationController::class, 'index'));
    $router->post('/reservation', array(ReservationController::class, 'store'));
    $router->post('/api/reservation', array(ReservationController::class, 'apiStore'));
};
