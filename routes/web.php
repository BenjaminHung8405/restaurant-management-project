<?php

use App\Controllers\HomeController;
use App\Controllers\MealController;
use App\Controllers\CartController;
use App\Controllers\ReservationController;
use App\Controllers\AuthController;
use App\Controllers\AdminOrderController;
use App\Controllers\AdminMenuController;
use App\Core\Router;

return function (Router $router) {
    $router->get('/', array(HomeController::class, 'index'));
    
    // Auth Routes
    $router->get('/login', array(AuthController::class, 'loginForm'));
    $router->post('/login', array(AuthController::class, 'login'));
    $router->get('/logout', array(AuthController::class, 'logout'));

    // Admin Routes
    $router->get('/admin', array(AdminOrderController::class, 'index'));
    $router->get('/admin/orders', array(AdminOrderController::class, 'index'));
    $router->post('/admin/orders/update-status', array(AdminOrderController::class, 'updateStatus'));
    
    $router->get('/admin/menu', array(AdminMenuController::class, 'index'));
    $router->get('/admin/menu/create', array(AdminMenuController::class, 'create'));
    $router->post('/admin/menu/store', array(AdminMenuController::class, 'store'));
    $router->get('/admin/menu/edit', array(AdminMenuController::class, 'edit'));
    $router->post('/admin/menu/update', array(AdminMenuController::class, 'update'));
    $router->post('/admin/menu/delete', array(AdminMenuController::class, 'delete'));
    $router->get('/admin/menu/delete', array(AdminMenuController::class, 'delete')); // Fallback for simple link delete
    
    // Meal Routes
    $router->get('/menu', array(MealController::class, 'index'));
    $router->get('/meals', array(MealController::class, 'index'));
    
    // Cart Routes
    $router->get('/cart', array(CartController::class, 'index'));
    $router->get('/cart/add', array(CartController::class, 'add'));
    $router->post('/cart/add', array(CartController::class, 'add'));
    $router->get('/cart/status_bar', array(CartController::class, 'statusBar'));
    $router->get('/cart/remove', array(CartController::class, 'remove'));
    $router->get('/cart/update', array(CartController::class, 'update'));
    
    // Reservation Routes
    $router->get('/reservation', array(ReservationController::class, 'index'));
    $router->post('/reservation', array(ReservationController::class, 'store'));
    $router->post('/api/reservation', array(ReservationController::class, 'apiStore'));
};
