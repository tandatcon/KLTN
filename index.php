<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'config.php';
require_once 'helpers.php';

// Khởi tạo session
session_start();

$url = $_GET['url'] ?? '';
$parts = explode('/', trim($url, '/'));

// XỬ LÝ NESTED ROUTES (employee/dashboard, KTV/dashboard, quanly/dashboard, etc.)
if (count($parts) >= 2 && in_array($parts[0], ['employee', 'KTV', 'quanly'])) {
    $page = $parts[0] . '/' . $parts[1]; // employee/dashboard, KTV/dashboard, quanly/dashboard
} else {
    $page = $parts[0] ?: 'home'; // Mặc định là home cho tất cả
}

// CHỈ KHI ĐÃ ĐĂNG NHẬP mới phân trang theo role
if ($page === 'home' && isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 2: // Employee
            $page = 'employee/dashboard';
            break;
        case 3: // KTV
            $page = 'KTV/dashboard';
            break;
        case 4: // Quản lý
            $page = 'quanly/dashboard';
            break;
        // Customer (role 1) vẫn giữ nguyên trang home
    }
}

$viewFile = __DIR__ . "/views/$page.php";

// DEBUG: Hiển thị thông tin routing (có thể xóa sau khi test)
// echo "URL: " . ($_GET['url'] ?? 'empty') . "<br>";
// echo "Page: $page<br>";
// echo "View File: $viewFile<br>";
// echo "Exists: " . (file_exists($viewFile) ? 'YES' : 'NO') . "<br>";

// Kiểm tra quyền truy cập các trang employee
if (strpos($page, 'employee/') === 0) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
        header('Location: ' . url('home'));
        exit();
    }
}

// Kiểm tra quyền truy cập các trang KTV
if (strpos($page, 'KTV/') === 0) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
        header('Location: ' . url('home'));
        exit();
    }
}

// Kiểm tra quyền truy cập các trang quanly
if (strpos($page, 'quanly/') === 0) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 4) {
        header('Location: ' . url('home'));
        exit();
    }
}

// Kiểm tra quyền truy cập các trang customer
$protectedCustomerPages = ['my_orders', 'datdichvu', 'my_order_detail'];
if (in_array($page, $protectedCustomerPages)) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['warning_message']  = "Vui lòng đăng nhập để thực hiện thao tác!.";

        header('Location: ' . url('dang-nhap'));
        exit();
    }
    // Nếu là employee/KTV/quanly cố truy cập trang customer -> về dashboard tương ứng
    if (isset($_SESSION['role']) && $_SESSION['role'] >= 2) {
        switch ($_SESSION['role']) {
            case 2:
                header('Location: ' . url('employee/dashboard'));
                break;
            case 3:
                header('Location: ' . url('KTV/dashboard'));
                break;
            case 4:
                header('Location: ' . url('quanly/dashboard'));
                break;
        }
        exit();
    }
}

if (file_exists($viewFile)) {
    $pageTitle = ucfirst($page);
    include $viewFile;
} else {
    http_response_code(404);
    $pageTitle = '404 - Page Not Found';
    include __DIR__ . "/views/404.php";
}
?>