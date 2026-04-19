<?php

namespace App\Middlewares;

class AuthMiddleware
{
    /**
     * Ensures the user is authenticated.
     */
    public static function isAuthenticated()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            $_SESSION['auth_error'] = 'Vui lòng đăng nhập để tiếp tục.';
            header('Location: ' . url('/login'));
            exit;
        }
    }

    /**
     * Restricts access to specific roles.
     * @param array $allowedRoles
     */
    public static function requireRole(array $allowedRoles)
    {
        // First, ensure session is started and user is logged in
        self::isAuthenticated();

        $userRole = $_SESSION['user']['role'] ?? '';

        if (!in_array($userRole, $allowedRoles)) {
            // Smart Redirect Rule
            if ($userRole === 'kitchen') {
                header('Location: ' . url('/admin/kitchen'));
            } else {
                $_SESSION['error'] = 'Bạn không có quyền truy cập trang này.';
                header('Location: ' . url('/admin/tables'));
            }
            exit;
        }
    }
}
