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

        $this->render('auth/login', array(
            'title' => 'Đăng nhập',
            'identity' => '',
            'errorMessage' => ''
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

        $userModel = new User();
        $user = $userModel->findByIdentity($identity);

        if (!$user) {
            $this->render('auth/login', array(
                'title' => 'Đăng nhập',
                'identity' => $identity,
                'errorMessage' => 'Thông tin đăng nhập không chính xác.'
            ));
            return;
        }

        $storedHash = (string) ($user['password_hash'] ?? '');
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
            $displayName = (string) $user['email'];
        }

        $_SESSION['user'] = array(
            'user_id' => (string) $user['id'],
            'username' => $displayName,
            'role' => (string) $user['role'],
            'email' => (string) $user['email'],
        );

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
        header('Location: /login');
        exit;
    }

    private function redirectByRole($role)
    {
        $normalizedRole = strtolower(trim((string) $role));
        if ($normalizedRole === 'admin' || $normalizedRole === 'staff') {
            header('Location: /admin/orders');
            exit;
        }
        header('Location: /');
        exit;
    }
}
