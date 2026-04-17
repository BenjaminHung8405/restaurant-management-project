<?php

namespace App\Controllers;

class BaseController
{
    protected function render($view, $data = array(), $layout = 'layouts/main')
    {
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'View not found: ' . htmlspecialchars($view, ENT_QUOTES, 'UTF-8');
            return;
        }

        extract($data);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = VIEW_PATH . '/' . str_replace('.', '/', $layout) . '.php';

        if (file_exists($layoutFile)) {
            require $layoutFile;
            return;
        }

        echo $content;
    }

    protected function isValidUuid($value)
    {
        return (bool) preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            trim((string) $value)
        );
    }

    protected function normalizePhone($phone)
    {
        $normalized = preg_replace('/\s+/', '', trim((string) $phone));
        $normalized = str_replace('.', '', $normalized);

        if (strpos($normalized, '+84') === 0) {
            return '0' . substr($normalized, 3);
        }

        if (strpos($normalized, '84') === 0 && strlen($normalized) > 9) {
            return '0' . substr($normalized, 2);
        }

        return $normalized;
    }

    protected function isValidVietnamesePhone($phone)
    {
        return (bool) preg_match('/^0\d{9,10}$/', $this->normalizePhone($phone));
    }

    protected function parseIntInRange($value, $min, $max)
    {
        $number = filter_var($value, FILTER_VALIDATE_INT);
        if ($number === false) {
            return false;
        }

        $number = (int) $number;
        if ($number < $min || $number > $max) {
            return false;
        }

        return $number;
    }

    protected function parsePrice($value, $min = 0.01, $max = 1000000000)
    {
        $normalized = str_replace(',', '.', trim((string) $value));
        if ($normalized === '' || !is_numeric($normalized)) {
            return false;
        }

        $price = (float) $normalized;
        if ($price < $min || $price > $max) {
            return false;
        }

        return round($price, 2);
    }

    protected function parseReservationDateTime($date, $time, &$errorMessage = null)
    {
        $date = trim((string) $date);
        $time = trim((string) $time);

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $errorMessage = 'Ngày đặt bàn không hợp lệ.';
            return null;
        }

        if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
            $errorMessage = 'Giờ đặt bàn không hợp lệ.';
            return null;
        }

        // Dùng DateTime::createFromFormat để chặn dữ liệu ngày/giờ giả mạo trước khi lưu DB.
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        $dateTimeErrors = \DateTime::getLastErrors();

        if (
            $dateTime === false ||
            (($dateTimeErrors['warning_count'] ?? 0) > 0) ||
            (($dateTimeErrors['error_count'] ?? 0) > 0)
        ) {
            $errorMessage = 'Thời gian đặt bàn không hợp lệ.';
            return null;
        }

        return $dateTime->format('Y-m-d H:i:s');
    }
}
