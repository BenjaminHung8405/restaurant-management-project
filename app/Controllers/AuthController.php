<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends BaseController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function loginForm()
    {
        // Redirect if already logged in
        if (isset($_SESSION['user'])) {
            $this->redirectByRole($_SESSION['user']['role']);
        }

        $errorMessage = '';
        if (isset($_SESSION['auth_error'])) {
            $errorMessage = $_SESSION['auth_error'];
            unset($_SESSION['auth_error']);
        }

        $this->render('auth/login', array(
            'title' => 'Đăng nhập',
            'identity' => '',
            'errorMessage' => $errorMessage
        ));
    }

    public function login()
    {
        $identity = isset($_POST['identity']) ? trim((string) $_POST['identity']) : '';
        $password = isset($_POST['password']) ? (string) $_POST['password'] : '';

        if ($identity === '' || $password === '') {
            $this->render('auth/login', array(
                'title' => 'Đăng nhập',
                'identity' => $identity,
                'errorMessage' => 'Vui lòng nhập đầy đủ thông tin đăng nhập.'
            ));
            return;
        }

        if (mb_strlen($identity) > 255) {
            $this->render('auth/login', array(
                'title' => 'Đăng nhập',
                'identity' => '',
                'errorMessage' => 'Định danh đăng nhập không hợp lệ.'
            ));
            return;
        }

        if (mb_strlen($password) > 255) {
            $this->render('auth/login', array(
                'title' => 'Đăng nhập',
                'identity' => $identity,
                'errorMessage' => 'Mật khẩu không hợp lệ.'
            ));
            return;
        }

        if (strpos($identity, '@') !== false && !filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $this->render('auth/login', array(
                'title' => 'Đăng nhập',
                'identity' => $identity,
                'errorMessage' => 'Email đăng nhập không đúng định dạng.'
            ));
            return;
        }

        $userModel = new User();
        $user = $userModel->findByUsername($identity);

        if (!$user || ($user['status'] ?? 'inactive') !== 'active') {
            $this->render('auth/login', array(
                'title' => 'Đăng nhập',
                'identity' => $identity,
                'errorMessage' => 'Tài khoản không chính xác hoặc đã bị khóa.'
            ));
            return;
        }

        $storedHash = (string) ($user['password'] ?? '');
        $isPasswordValid = false;

        if ($storedHash !== '') {
            $isPasswordValid = password_verify($password, $storedHash);
            if (!$isPasswordValid) {
                // Fallback for plain-text legacy passwords
                $isPasswordValid = hash_equals($storedHash, $password);
            }
        }

        if (!$isPasswordValid) {
            $this->render('auth/login', array(
                'title' => 'Đăng nhập',
                'identity' => $identity,
                'errorMessage' => 'Thông tin đăng nhập không chính xác.'
            ));
            return;
        }

        // Login success
        session_regenerate_id(true);
        $displayName = trim((string) ($user['full_name'] ?? ''));
        if ($displayName === '') {
            $displayName = (string) ($user['username'] ?? 'User');
        }

        $_SESSION['user'] = array(
            'id' => (string) $user['id'],
            'full_name' => $displayName,
            'username' => (string) $user['username'],
            'role' => (string) $user['role'],
        );
        $_SESSION['user_id'] = (string) $user['id'];

        $this->redirectByRole((string) $user['role']);
    }

    public function logout()
    {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        session_write_close();
        header('Location: ' . url('/login'));
        exit;
    }

    private function redirectByRole($role)
    {
        $role = strtolower(trim((string) $role));
        
        switch ($role) {
            case 'kitchen':
                $target = '/admin/kitchen';
                break;
            case 'waiter':
            case 'cashier':
                $target = '/admin/tables';
                break;
            case 'admin':
                $target = '/admin'; // Redirect to dashboard
                break;
            default:
                $target = '/';
                break;
        }

        session_write_close();
        header('Location: ' . url($target));
        exit;
    }
}
