<?php

namespace App\Controllers;

use App\Models\User;
use Throwable;

class AdminUserController extends AdminBaseController
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        \App\Middlewares\AuthMiddleware::requireRole(['admin']);
        $this->userModel = new User();
    }

    public function index()
    {
        $users = $this->userModel->all();
        $this->render('admin/users/index', array(
            'users' => $users,
            'title' => 'Quản lý Nhân sự'
        ), 'layouts/admin');
    }

    public function create()
    {
        $this->render('admin/users/form', array(
            'title' => 'Thêm Nhân viên Mới',
            'user' => null
        ), 'layouts/admin');
    }

    public function store()
    {
        try {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $fullName = trim($_POST['full_name'] ?? '');
            $role = $_POST['role'] ?? 'cashier';

            if (empty($username) || empty($password) || empty($fullName)) {
                throw new \Exception('Vui lòng điền đầy đủ thông tin.');
            }

            // Check unique username
            if ($this->userModel->findByUsername($username)) {
                throw new \Exception('Tên đăng nhập đã tồn tại.');
            }

            $data = array(
                'id' => $this->userModel->uuid(),
                'username' => $username,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'full_name' => $fullName,
                'role' => $role,
                'status' => 'active'
            );

            if ($this->userModel->create($data)) {
                $_SESSION['success'] = 'Thêm nhân viên thành công.';
                header('Location: ' . url('/admin/users'));
                exit;
            } else {
                throw new \Exception('Không thể thêm nhân viên.');
            }
        } catch (Throwable $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . url('/admin/users/create'));
            exit;
        }
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy nhân viên.';
            header('Location: ' . url('/admin/users'));
            exit;
        }

        $this->render('admin/users/form', array(
            'title' => 'Chỉnh sửa Nhân viên',
            'user' => $user
        ), 'layouts/admin');
    }

    public function update($id)
    {
        try {
            $fullName = trim($_POST['full_name'] ?? '');
            $role = $_POST['role'] ?? 'cashier';
            $password = $_POST['password'] ?? '';

            if (empty($fullName)) {
                throw new \Exception('Vui lòng điền họ tên.');
            }

            $data = array(
                'full_name' => $fullName,
                'role' => $role
            );

            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_BCRYPT);
            }

            if ($this->userModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật nhân viên thành công.';
                header('Location: ' . url('/admin/users'));
                exit;
            } else {
                throw new \Exception('Không thể cập nhật nhân viên.');
            }
        } catch (Throwable $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . url('/admin/users/edit?id=' . $id));
            exit;
        }
    }

    public function toggleStatus($id)
    {
        if ($this->userModel->toggleStatus($id)) {
            $_SESSION['success'] = 'Đã thay đổi trạng thái nhân viên.';
        } else {
            $_SESSION['error'] = 'Không thể thay đổi trạng thái.';
        }
        header('Location: ' . url('/admin/users'));
        exit;
    }
}
