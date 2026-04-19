<?php

namespace App\Controllers;

use App\Core\Database;
use App\Models\Table;
use PDO;
use Throwable;

class AdminTableController extends AdminBaseController
{
    protected $tableModel;

    public function __construct()
    {
        parent::__construct();
        $this->tableModel = new Table();
    }
    /**
     * Display the Interactive Table Map
     */
    public function index()
    {
        try {
            $db = Database::connection();
            $sql = "SELECT 
                        t.id, 
                        t.table_number, 
                        t.capacity,
                        t.status as table_status,
                        MAX(CASE WHEN o.order_status IN ('pending', 'preparing', 'serving') THEN o.order_status END) as active_status,
                        MAX(o.id) as order_id,
                        (SELECT r.guest_name FROM reservations r 
                         WHERE r.table_id = t.id AND r.status = 'confirmed' 
                         AND r.reservation_time BETWEEN DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                         LIMIT 1) as reservation_guest,
                        (SELECT r.id FROM reservations r 
                         WHERE r.table_id = t.id AND r.status = 'confirmed' 
                         AND r.reservation_time BETWEEN DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                         LIMIT 1) as reservation_id,
                        (SELECT DATE_FORMAT(r.reservation_time, '%H:%h') FROM reservations r 
                         WHERE r.table_id = t.id AND r.status = 'confirmed' 
                         AND r.reservation_time BETWEEN DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                         LIMIT 1) as reservation_time
                    FROM tables t
                    LEFT JOIN orders o ON t.id = o.table_id
                    GROUP BY t.id
                    ORDER BY t.table_number ASC";
            
            $statement = $db->query($sql);
            $tables = $statement->fetchAll(PDO::FETCH_ASSOC);

            $this->render('admin/tables/index', [
                'title' => 'Sơ đồ Bàn trực tuyến',
                'tables' => $tables
            ], 'layouts/admin');
        } catch (Throwable $e) {
            // Fallback to empty if error
            $this->render('admin/tables/index', [
                'title' => 'Sơ đồ Bàn trực tuyến',
                'tables' => []
            ], 'layouts/admin');
        }
    }

    /**
     * API Endpoint to fetch current table status
     */
    public function getTablesAjax()
    {
        header('Content-Type: application/json');

        try {
            // Priority 4 (UX): Auto-release Ghost Cleaning Tables
            $this->tableModel->autoReleaseCleaningTables();

            $db = Database::connection();
            
            // SQL updated to include reservation status and table status
            $sql = "SELECT 
                        t.id, 
                        t.table_number, 
                        t.capacity,
                        t.status as table_status,
                        MAX(CASE WHEN o.order_status IN ('pending', 'preparing', 'serving') THEN o.order_status END) as active_status,
                        MAX(o.id) as order_id,
                        (SELECT r.guest_name FROM reservations r 
                         WHERE r.table_id = t.id AND r.status = 'confirmed' 
                         AND r.reservation_time BETWEEN DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                         LIMIT 1) as reservation_guest,
                        (SELECT r.id FROM reservations r 
                         WHERE r.table_id = t.id AND r.status = 'confirmed' 
                         AND r.reservation_time BETWEEN DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                         LIMIT 1) as reservation_id,
                        (SELECT DATE_FORMAT(r.reservation_time, '%H:%h') FROM reservations r 
                         WHERE r.table_id = t.id AND r.status = 'confirmed' 
                         AND r.reservation_time BETWEEN DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                         LIMIT 1) as reservation_time
                    FROM tables t
                    LEFT JOIN orders o ON t.id = o.table_id
                    GROUP BY t.id
                    ORDER BY t.table_number ASC";
            
            $statement = $db->query($sql);
            $tables = $statement->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($tables);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
