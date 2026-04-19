<?php

namespace App\Controllers;

use App\Models\Category;
use Throwable;

class AdminCategoryController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        \App\Middlewares\AuthMiddleware::requireRole(['admin']);
    }

    public function index()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $this->render('admin/categories/index', array(
            'title' => 'Quản lý Danh mục',
            'categories' => $categories,
            'flashSuccess' => $_SESSION['category_success'] ?? '',
            'flashError' => $_SESSION['category_error'] ?? ''
        ), 'layouts/admin');
        
        unset($_SESSION['category_success'], $_SESSION['category_error']);
    }

    public function store()
    {
        $data = $this->getFormData();
        $errors = $this->validate($data);

        if (empty($errors)) {
            try {
                $categoryModel = new Category();
                $data['id'] = generate_uuid();
                
                if ($categoryModel->create($data)) {
                    $_SESSION['category_success'] = 'Thêm danh mục thành công.';
                    header('Location: ' . url('/admin/categories'));
                    exit;
                }
                $errors[] = 'Không thể lưu danh mục vào cơ sở dữ liệu.';
            } catch (Throwable $e) {
                $errors[] = 'Lỗi hệ thống: ' . $e->getMessage();
            }
        }

        $_SESSION['category_error'] = implode('<br>', $errors);
        header('Location: ' . url('/admin/categories'));
        exit;
    }

    public function update()
    {
        $id = trim((string) ($_POST['id'] ?? ''));
        if (!$this->isValidUuid($id)) {
            $_SESSION['category_error'] = 'ID không hợp lệ.';
            header('Location: ' . url('/admin/categories'));
            exit;
        }

        $data = $this->getFormData();
        $errors = $this->validate($data, $id);

        if (empty($errors)) {
            try {
                $categoryModel = new Category();
                if ($categoryModel->update($id, $data)) {
                    $_SESSION['category_success'] = 'Cập nhật danh mục thành công.';
                    header('Location: ' . url('/admin/categories'));
                    exit;
                }
                $errors[] = 'Không thể cập nhật danh mục.';
            } catch (Throwable $e) {
                $errors[] = 'Lỗi hệ thống: ' . $e->getMessage();
            }
        }

        $_SESSION['category_error'] = implode('<br>', $errors);
        header('Location: ' . url('/admin/categories'));
        exit;
    }

    public function destroy()
    {
        $id = trim((string) ($_POST['id'] ?? ''));
        
        if (!$this->isValidUuid($id)) {
            $_SESSION['category_error'] = 'ID không hợp lệ.';
            header('Location: ' . url('/admin/categories'));
            exit;
        }

        try {
            $categoryModel = new Category();
            
            if ($categoryModel->hasMenuItems($id)) {
                $_SESSION['category_error'] = 'Không thể xóa danh mục này vì vẫn còn món ăn liên kết. Vui lòng chuyển hoặc xóa các món ăn trước.';
            } else {
                if ($categoryModel->delete($id)) {
                    $_SESSION['category_success'] = 'Xóa danh mục thành công.';
                } else {
                    $_SESSION['category_error'] = 'Không thể xóa danh mục.';
                }
            }
        } catch (Throwable $e) {
            $_SESSION['category_error'] = 'Lỗi hệ thống: ' . $e->getMessage();
        }

        header('Location: ' . url('/admin/categories'));
        exit;
    }

    private function getFormData()
    {
        return array(
            'name' => trim((string)($_POST['name'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
            'image_url' => trim((string)($_POST['image_url'] ?? ''))
        );
    }

    private function validate($data, $excludeId = null)
    {
        $errors = array();

        if ($data['name'] === '') {
            $errors[] = 'Tên danh mục không được để trống.';
        } elseif (mb_strlen($data['name']) > 255) {
            $errors[] = 'Tên danh mục không được vượt quá 255 ký tự.';
        }

        if (mb_strlen($data['description']) > 2000) {
            $errors[] = 'Mô tả danh mục không được vượt quá 2000 ký tự.';
        }

        if ($data['image_url'] !== '') {
            if (mb_strlen($data['image_url']) > 500) {
                $errors[] = 'URL hình ảnh không được vượt quá 500 ký tự.';
            } elseif (!filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
                $errors[] = 'URL hình ảnh không hợp lệ.';
            }
        }

        if ($data['name'] !== '') {
            $categoryModel = new Category();
            if ($categoryModel->existsByName($data['name'], $excludeId)) {
                $errors[] = 'Tên danh mục đã tồn tại, vui lòng chọn tên khác.';
            }
        }

        return $errors;
    }
}
