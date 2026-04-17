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
        $categoryModel = new Category();
        
        $search = trim((string)($_GET['search'] ?? ''));
        $categoryId = trim((string)($_GET['category_id'] ?? ''));
        $status = trim((string)($_GET['status'] ?? ''));

        $items = $mealModel->all($search, $categoryId, $status);
        $categories = $categoryModel->all();

        $this->render('admin/menu/index', array(
            'title' => 'Quản lý thực đơn',
            'items' => $items,
            'categories' => $categories,
            'filters' => array(
                'search' => $search,
                'category_id' => $categoryId,
                'status' => $status
            ),
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
        
        if (empty($errors)) {
            $data['image_url'] = $imageUrl; // Will be null if no upload, which is fine if DB allows
            $mealModel = new Meal();
            $data['id'] = $this->uuid();
            
            try {
                if ($mealModel->create($data)) {
                    $_SESSION['admin_menu_success'] = 'Thêm món ăn thành công.';
                    header('Location: ' . url('/admin/menu'));
                    exit;
                }
                $errors[] = 'Không thể lưu món ăn vào cơ sở dữ liệu.';
            } catch (Throwable $e) {
                $errors[] = 'Lỗi cơ sở dữ liệu: ' . $e->getMessage();
            }
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
        $id = trim((string) ($_GET['id'] ?? ''));
        if (!$this->isValidUuid($id)) {
            $_SESSION['admin_menu_error'] = 'Mã món ăn không hợp lệ.';
            header('Location: ' . url('/admin/menu'));
            exit;
        }

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
        $id = trim((string) ($_POST['id'] ?? ''));
        if (!$this->isValidUuid($id)) {
            $_SESSION['admin_menu_error'] = 'Mã món ăn không hợp lệ.';
            header('Location: ' . url('/admin/menu'));
            exit;
        }

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
            $this->deleteImage($item['image_url']);
            $data['image_url'] = $imageUrl;
        }

        if (empty($errors)) {
            try {
                if ($mealModel->update($id, $data)) {
                    $_SESSION['admin_menu_success'] = 'Cập nhật món ăn thành công.';
                    header('Location: ' . url('/admin/menu'));
                    exit;
                }
                $errors[] = 'Không thể cập nhật món ăn.';
            } catch (Throwable $e) {
                $errors[] = 'Lỗi cơ sở dữ liệu: ' . $e->getMessage();
            }
        }

        $categoryModel = new Category();
        $this->render('admin/menu/form', array(
            'title' => 'Chỉnh sửa món ăn',
            'categories' => $categoryModel->all(),
            'item' => (object)$this->mergeArray($item, $data),
            'isEdit' => true,
            'errors' => $errors
        ), 'layouts/admin');
    }

    public function delete()
    {
        $id = trim((string) ($_POST['id'] ?? $_GET['id'] ?? ''));
        if (!$this->isValidUuid($id)) {
            $_SESSION['admin_menu_error'] = 'Mã món ăn không hợp lệ.';
            header('Location: ' . url('/admin/menu'));
            exit;
        }

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
            'price' => trim((string)($_POST['price'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
            'area' => trim((string)($_POST['area'] ?? '')),
            'is_available' => isset($_POST['is_available']) ? 1 : 0,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0
        );
    }

    private function validate(&$data)
    {
        $errors = array();
        
        // Name validation
        if ($data['name'] === '') {
            $errors[] = 'Tên món ăn không được để trống.';
        } elseif (mb_strlen($data['name']) > 255) {
            $errors[] = 'Tên món ăn không được vượt quá 255 ký tự.';
        }

        // Category validation
        if ($data['category_id'] === '') {
            $errors[] = 'Vui lòng chọn danh mục thực đơn.';
        } elseif (!$this->isValidUuid($data['category_id'])) {
            $errors[] = 'Danh mục được chọn không hợp lệ.';
        } else {
            $categoryModel = new Category();
            if (!$categoryModel->find($data['category_id'])) {
                $errors[] = 'Danh mục được chọn không tồn tại.';
            }
        }

        // Price validation
        $price = $this->parsePrice($data['price']);
        if ($price === false) {
            $errors[] = 'Giá món ăn phải là một con số.';
        } else {
            $data['price'] = $price;
        }

        // Area validation
        if (mb_strlen($data['area']) > 100) {
            $errors[] = 'Khu vực/phong cách không được vượt quá 100 ký tự.';
        }

        // Description validation
        if (mb_strlen($data['description']) > 2000) {
            $errors[] = 'Mô tả không được vượt quá 2000 ký tự.';
        }

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

        $allowedTypes = array(
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif'
        );
        $mimeType = $this->detectMimeType($file['tmp_name']);

        if ($mimeType === null || !isset($allowedTypes[$mimeType])) {
            $errors[] = 'Chỉ chấp nhận định dạng JPG, PNG, WEBP hoặc GIF.';
            return null;
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }

        $extension = $allowedTypes[$mimeType];
        $fileName = 'menu-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
        $dest = $this->uploadDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $this->webPrefix . $fileName;
        }

        $errors[] = 'Không thể lưu tệp ảnh.';
        return null;
    }

    private function detectMimeType($tmpPath)
    {
        if (function_exists('finfo_open') && function_exists('finfo_file')) {
            $finfo = @finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $mimeType = @finfo_file($finfo, $tmpPath);
                finfo_close($finfo);
                if (is_string($mimeType) && $mimeType !== '') {
                    return strtolower($mimeType);
                }
            }
        }

        if (function_exists('getimagesize')) {
            $imageInfo = @getimagesize($tmpPath);
            if (is_array($imageInfo) && !empty($imageInfo['mime'])) {
                return strtolower($imageInfo['mime']);
            }
        }

        if (function_exists('mime_content_type')) {
            $mimeType = @mime_content_type($tmpPath);
            if (is_string($mimeType) && $mimeType !== '') {
                return strtolower($mimeType);
            }
        }

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
    private function mergeArray($old, $new) {
        foreach($new as $k => $v) {
            $old[$k] = $v;
        }
        return $old;
    }
}
