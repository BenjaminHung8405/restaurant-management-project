<?php

if (!function_exists('base_path')) {
    function base_path($path = '')
    {
        return BASE_PATH . ($path !== '' ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('app_path')) {
    function app_path($path = '')
    {
        return APP_PATH . ($path !== '' ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('view_path')) {
    function view_path($path = '')
    {
        return VIEW_PATH . ($path !== '' ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }

        return $default;
    }
}

if (!function_exists('loadEnv')) {
    function loadEnv($envPath)
    {
        if (!file_exists($envPath)) {
            return;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            if (strpos($line, '=') === false) {
                continue;
            }

            list($key, $value) = array_map('trim', explode('=', $line, 2));
            $value = trim($value, "\"'");

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv($key . '=' . $value);
        }
    }
}

if (!function_exists('url')) {
    function url($path = '')
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace('\\', '/', dirname($scriptName));
        
        // Normalize basePath: '.' or single slashes indicate root.
        if ($basePath === '/' || $basePath === '.' || $basePath === '\\') {
            $basePath = '';
        }

        // Ensure basePath is root-relative (starts with / if not empty)
        if ($basePath !== '' && $basePath[0] !== '/') {
            $basePath = '/' . $basePath;
        }

        // Clean up trailing slashes
        $basePath = rtrim($basePath, '/');

        // If the path is already an absolute URL, return it as is
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return $path;
        }

        return $basePath . '/' . ltrim($path, '/');
    }
}

/**
 * Handle image upload with validation and unique renaming.
 *
 * @param array $file The $_FILES['input_name'] array.
 * @param string $targetSubDir Subdirectory within the project root.
 * @return string|false New filename on success, false on failure.
 */
if (!function_exists('handleImageUpload')) {
    function handleImageUpload($file, $targetSubDir = 'assets/uploads/meals/')
    {
        // 1. Check for upload errors
        if (!isset($file['error']) || is_array($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // 2. Validate file size (Limit to 2MB as requested)
        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return false;
        }

        // 3. Validate file type (Images only)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($mimeType, $allowedTypes)) {
            return false;
        }

        // 4. Ensure directory exists
        $absoluteTargetDir = base_path($targetSubDir);
        if (!file_exists($absoluteTargetDir)) {
            mkdir($absoluteTargetDir, 0755, true);
        }

        // 5. Generate unique filename to avoid conflicts
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        // Sanitize original extension just in case
        $extension = strtolower(preg_replace('/[^a-zA-Z0-0]/', '', $extension));
        if (empty($extension)) {
             $extension = 'jpg'; // Fallback
        }
        
        $newFilename = uniqid('meal_', true) . '.' . $extension;
        $targetPath = rtrim($absoluteTargetDir, '/') . '/' . $newFilename;

        // 6. Move the uploaded file to the target directory
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $newFilename;
        }

        return false;
    }
}

/**
 * Generate a standard UUID v4 string.
 *
 * @return string
 */
if (!function_exists('generate_uuid')) {
    function generate_uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
