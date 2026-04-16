<?php

namespace App\Controllers;

class AdminBaseController extends BaseController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $currentUser = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;
        $currentRole = strtolower(trim((string) ($currentUser['role'] ?? '')));

        if (!$currentUser || !in_array($currentRole, array('admin', 'staff'), true)) {
            $_SESSION['auth_error'] = 'Bạn không có quyền truy cập trang quản trị.';
            header('Location: ' . url('/login'));
            exit;
        }
    }
}
