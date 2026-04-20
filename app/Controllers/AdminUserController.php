<?php

namespace App\Controllers;

use App\Models\User;
use Throwable;

class AdminUserController extends AdminBaseController
{
    private $userModel;
    private $rolePriority = [
        'admin' => 10,
        'cashier' => 5,
        'waiter' => 3,
        'kitchen' => 1
    ];

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
            'title' => 'Quản lý Nhân sự',
            'rolePriority' => $this->rolePriority,
            'currentUser' => $_SESSION['user'] ?? null
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

            // Check if current user has permission to create a user with this role
            $currentUserRole = $_SESSION['user']['role'] ?? '';
            if (($this->rolePriority[$role] ?? 0) >= ($this->rolePriority[$currentUserRole] ?? 0)) {
                throw new \Exception('Bạn không có quyền cấp vai trò cao hơn hoặc bằng vai trò của mình.');
            }

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

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy nhân viên.';
            header('Location: ' . url('/admin/users'));
            exit;
        }

        // Permission check
        if (!$this->canManage($_SESSION['user'], $user)) {
            $_SESSION['error'] = 'Bạn không có quyền chỉnh sửa nhân viên này.';
            header('Location: ' . url('/admin/users'));
            exit;
        }

        $this->render('admin/users/form', array(
            'title' => 'Chỉnh sửa Nhân viên',
            'user' => $user
        ), 'layouts/admin');
    }

    public function update()
    {
        try {
            $id = $_GET['id'] ?? null;
            $fullName = trim($_POST['full_name'] ?? '');
            $role = $_POST['role'] ?? 'cashier';
            $password = $_POST['password'] ?? '';

            if (empty($fullName)) {
                throw new \Exception('Vui lòng điền họ tên.');
            }

            // Permission check for target user
            $targetUser = $this->userModel->find($id);
            if (!$targetUser || !$this->canManage($_SESSION['user'], $targetUser)) {
                throw new \Exception('Bạn không có quyền cập nhật nhân viên này.');
            }

            // Permission check for new role
            $currentUserRole = $_SESSION['user']['role'] ?? '';
            if (($this->rolePriority[$role] ?? 0) >= ($this->rolePriority[$currentUserRole] ?? 0)) {
                throw new \Exception('Bạn không có quyền cấp vai trò cao hơn hoặc bằng vai trò của mình.');
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

    public function toggleStatus()
    {
        $id = $_GET['id'] ?? null;
        $targetUser = $this->userModel->find($id);
        
        if (!$targetUser || !$this->canManage($_SESSION['user'], $targetUser)) {
            $_SESSION['error'] = 'Bạn không có quyền thay đổi trạng thái nhân viên này.';
            header('Location: ' . url('/admin/users'));
            exit;
        }

        if ($this->userModel->toggleStatus($id)) {
            $_SESSION['success'] = 'Đã thay đổi trạng thái nhân viên.';
        } else {
            $_SESSION['error'] = 'Không thể thay đổi trạng thái.';
        }
        header('Location: ' . url('/admin/users'));
        exit;
    }

    private function canManage($currentUser, $targetUser)
    {
        if (!$currentUser || !$targetUser) return false;

        // Cannot edit self
        if ($currentUser['id'] === $targetUser['id']) {
            return false;
        }

        $currentPriority = $this->rolePriority[$currentUser['role']] ?? 0;
        $targetPriority = $this->rolePriority[$targetUser['role']] ?? 0;

        // Can only edit users with lower priority
        return $currentPriority > $targetPriority;
    }
}
