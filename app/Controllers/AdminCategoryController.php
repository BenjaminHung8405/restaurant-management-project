<?php

namespace App\Controllers;

use App\Models\Category;
use Throwable;

class AdminCategoryController extends AdminBaseController
{
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
        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            $_SESSION['category_error'] = 'ID không hợp lệ.';
            header('Location: ' . url('/admin/categories'));
            exit;
        }

        $data = $this->getFormData();
        $errors = $this->validate($data);

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
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
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

    private function validate($data)
    {
        $errors = array();
        if ($data['name'] === '') {
            $errors[] = 'Tên danh mục không được để trống.';
        }
        return $errors;
    }
}
