<?php

namespace App\Controllers;

use App\Models\Meal;
use App\Models\Category;
use Throwable;

class AdminMenuController extends AdminBaseController
{
    private $uploadDir = BASE_PATH . '/assets/uploads';
    private $webPrefix = 'assets/uploads/';
    private $maxSize = 2097152; // 2MB

    public function index()
    {
        $mealModel = new Meal();
        $items = $mealModel->all();

        $this->render('admin/menu/index', array(
            'title' => 'Quản lý thực đơn',
            'items' => $items,
            'flashSuccess' => $_SESSION['admin_menu_success'] ?? '',
            'flashError' => $_SESSION['admin_menu_error'] ?? ''
        ), 'layouts/admin');
        unset($_SESSION['admin_menu_success'], $_SESSION['admin_menu_error']);
    }

    public function create()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $this->render('admin/menu/form', array(
            'title' => 'Thêm món ăn mới',
            'categories' => $categories,
            'item' => null,
            'isEdit' => false
        ), 'layouts/admin');
    }

    public function store()
    {
        $data = $this->getFormData();
        $errors = $this->validate($data);

        $imageUrl = $this->handleUpload($errors);
        if ($imageUrl) {
            $data['image_url'] = $imageUrl;
        }

        if (empty($errors)) {
            $mealModel = new Meal();
            $data['id'] = $this->uuid();
            if ($mealModel->create($data)) {
                $_SESSION['admin_menu_success'] = 'Thêm món ăn thành công.';
                header('Location: ' . url('/admin/menu'));
                exit;
            }
            $errors[] = 'Không thể lưu món ăn vào cơ sở dữ liệu.';
        }

        $categoryModel = new Category();
        $this->render('admin/menu/form', array(
            'title' => 'Thêm món ăn mới',
            'categories' => $categoryModel->all(),
            'item' => (object)$data,
            'isEdit' => false,
            'errors' => $errors
        ), 'layouts/admin');
    }

    public function edit()
    {
        $id = $_GET['id'] ?? '';
        $mealModel = new Meal();
        $item = $mealModel->find($id);

        if (!$item) {
            $_SESSION['admin_menu_error'] = 'Không tìm thấy món ăn.';
            header('Location: ' . url('/admin/menu'));
            exit;
        }

        $categoryModel = new Category();
        $this->render('admin/menu/form', array(
            'title' => 'Chỉnh sửa món ăn',
            'categories' => $categoryModel->all(),
            'item' => (object)$item,
            'isEdit' => true
        ), 'layouts/admin');
    }

    public function update()
    {
        $id = $_POST['id'] ?? '';
        $mealModel = new Meal();
        $item = $mealModel->find($id);

        if (!$item) {
            $_SESSION['admin_menu_error'] = 'Không tìm thấy món ăn.';
            header('Location: ' . url('/admin/menu'));
            exit;
        }

        $data = $this->getFormData();
        $errors = $this->validate($data);

        $imageUrl = $this->handleUpload($errors);
        if ($imageUrl) {
            // Delete old image if exists
            $this->deleteImage($item['image_url']);
            $data['image_url'] = $imageUrl;
        }

        if (empty($errors)) {
            if ($mealModel->update($id, $data)) {
                $_SESSION['admin_menu_success'] = 'Cập nhật món ăn thành công.';
                header('Location: ' . url('/admin/menu'));
                exit;
            }
            $errors[] = 'Không thể cập nhật món ăn.';
        }

        $categoryModel = new Category();
        $this->render('admin/menu/form', array(
            'title' => 'Chỉnh sửa món ăn',
            'categories' => $categoryModel->all(),
            'item' => (object)MargeArray($item, $data),
            'isEdit' => true,
            'errors' => $errors
        ), 'layouts/admin');
    }

    public function delete()
    {
        $id = $_POST['id'] ?? $_GET['id'] ?? '';
        $mealModel = new Meal();
        $item = $mealModel->find($id);

        if ($item) {
            $this->deleteImage($item['image_url']);
            $mealModel->delete($id);
            $_SESSION['admin_menu_success'] = 'Đã xóa món ăn thành công.';
        }

        header('Location: ' . url('/admin/menu'));
        exit;
    }

    private function getFormData()
    {
        return array(
            'name' => trim((string)($_POST['name'] ?? '')),
            'category_id' => trim((string)($_POST['category_id'] ?? '')),
            'price' => (float)($_POST['price'] ?? 0),
            'description' => trim((string)($_POST['description'] ?? '')),
            'is_available' => isset($_POST['is_available']) ? 1 : 0
        );
    }

    private function validate($data)
    {
        $errors = array();
        if ($data['name'] === '') $errors[] = 'Tên món ăn không được để trống.';
        if ($data['category_id'] === '') $errors[] = 'Vui lòng chọn danh mục.';
        if ($data['price'] <= 0) $errors[] = 'Giá món phải lớn hơn 0.';
        return $errors;
    }

    private function handleUpload(&$errors)
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['image'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Lỗi khi tải ảnh lên.';
            return null;
        }

        if ($file['size'] > $this->maxSize) {
            $errors[] = 'Kích thước ảnh không được vượt quá 2MB.';
            return null;
        }

        $allowedTypes = array('image/jpeg', 'image/png', 'image/webp', 'image/gif');
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $errors[] = 'Chỉ chấp nhận định dạng JPG, PNG, WEBP hoặc GIF.';
            return null;
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'menu-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
        $dest = $this->uploadDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $this->webPrefix . $fileName;
        }

        $errors[] = 'Không thể lưu tệp ảnh.';
        return null;
    }

    private function deleteImage($url)
    {
        if (empty($url)) return;
        $path = BASE_PATH . '/' . $url;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

function MargeArray($old, $new) {
    foreach($new as $k => $v) {
        $old[$k] = $v;
    }
    return $old;
}
