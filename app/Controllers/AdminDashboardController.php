<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class AdminDashboardController extends AdminBaseController
{
    public function index()
    {
        $db = Database::connection();

        // 1. Total Revenue
        $revenueStmt = $db->query("SELECT SUM(total_amount) as total FROM orders WHERE order_status = 'completed'");
        $totalRevenue = $revenueStmt->fetchColumn() ?: 0;

        // 2. Number of Pending Reservations Today
        $reservationsStmt = $db->query("SELECT COUNT(*) as total FROM reservations WHERE status = 'pending' AND DATE(reservation_time) = CURRENT_DATE");
        $pendingReservations = $reservationsStmt->fetchColumn() ?: 0;

        // 3. Total Number of Menu Items
        $menuItemsStmt = $db->query("SELECT COUNT(*) as total FROM menu_items");
        $totalMenuItems = $menuItemsStmt->fetchColumn() ?: 0;

        // 4. Revenue for the Last 7 Days
        $chartStmt = $db->query("
            SELECT DATE(created_at) as date, SUM(total_amount) as total
            FROM orders
            WHERE order_status = 'completed'
            AND created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 6 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ");
        $chartData = $chartStmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/dashboard/index', array(
            'title' => 'Bảng điều khiển',
            'totalRevenue' => $totalRevenue,
            'pendingReservations' => $pendingReservations,
            'totalMenuItems' => $totalMenuItems,
            'chartData' => $chartData
        ), 'layouts/admin');
    }
}
