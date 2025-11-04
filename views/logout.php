<?php
// logout.php
session_start();

// Lưu tên user để hiển thị thông báo
$user_name = $_SESSION['user_name'] ?? '';

// Hủy session
session_destroy();

// Set thông báo đăng xuất thành công
session_start(); // Start lại session để set message
$_SESSION['success_message'] = "👋 Đã đăng xuất thành công! Hẹn gặp lại $user_name";

// Chuyển hướng về trang chủ
header("Location: index.php");
exit;
?>