<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/config.php';
}

/**
 * Sinh đường dẫn tĩnh (CSS, JS, hình ảnh)
 */
function asset($path) {
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Sinh đường dẫn tới route (URL động)
 */
function url($path = '') {
    // Xử lý trường hợp path rỗng
    if (empty($path)) {
        return BASE_URL;
    }
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Hàm debug để kiểm tra URL
 */
function debug_url($path) {
    $result = url($path);
    echo "DEBUG: url('$path') = '$result'<br>";
    return $result;
}