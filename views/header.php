<?php
session_start();
// views/header.php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'TechCare'; ?></title>

    <!-- Bootstrap 5 CSS Local -->
    <link href="<?php echo asset('css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Bootstrap Icons Local -->
    <link href="<?php echo asset('css/bootstrap-icons.css'); ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Custom CSS cho header */
        .navbar-techcare {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo-image {
            height: 50px;
            width: auto;
        }

        /* Main Menu Styles */
        /* Main Menu Styles - SỬA LẠI */
        .main-nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
            margin-left: auto;
            /* QUAN TRỌNG: Đẩy menu sang phải */
            margin-right: 2rem;
            /* Khoảng cách với user info */
        }


        .main-nav-item {
            font-size: 1rem;
            font-weight: 600;
            color: #333 !important;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.3rem;
            transition: all 0.3s ease;
            text-align: center;
            white-space: nowrap;
        }

        .main-nav-item:hover {
            color: #1e40af !important;
            background-color: transparent;
        }

        .main-nav-item.active {
            color: #1e40af !important;
            background-color: transparent;
        }

        .btn-primary-custom {
            background-color: #1e40af;
            border-color: #1e40af;
            color: white;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .user-dropdown .dropdown-toggle {
            color: #374151 !important;
            background: transparent;
            border: none;
            font-weight: 500;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        .user-dropdown .dropdown-toggle:hover {
            background-color: #f3f4f6;
        }

        .user-dropdown .dropdown-menu {
            min-width: 200px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .user-name {
            font-size: 18px;
            display: inline-block;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Notification System */
        .notification-container {
            position: fixed;
            top: 10px;
            left: 0;
            right: 0;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            pointer-events: none;
        }

        .notification {
            padding: 12px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.4s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 300px;
            max-width: 500px;
            width: auto;
            pointer-events: auto;
            border: none;
        }

        .notification.success {
            background: #10b981;
        }

        .notification.error {
            background: #ef4444;
        }

        .notification.info {
            background: #3b82f6;
        }

        .notification.warning {
            background: #f59e0b;
        }

        .notification-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            margin-left: 15px;
            opacity: 0.8;
            padding: 0;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .notification-close:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.2);
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateY(0);
                opacity: 1;
            }

            to {
                transform: translateY(-20px);
                opacity: 0;
            }
        }

        .notification.hiding {
            animation: slideOut 0.3s ease forwards;
        }

        /* Mobile menu styles */
        .navbar-toggler {
            border: 1px solid #d1d5db;
            padding: 0.3rem 0.6rem;
            /* Tăng padding */
            margin-left: auto;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23333' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            width: 1.4em;
            /* Tăng kích thước */
            height: 1.4em;
        }

        /* FIX RESPONSIVE ISSUES - TĂNG KÍCH THƯỚC HEADER TRÊN MOBILE */
        @media (max-width: 991.98px) {
            .navbar-techcare {
                padding: 0.6rem 0 !important;
                /* Tăng padding */
                min-height: 70px;
                /* Đảm bảo chiều cao tối thiểu */
            }

            .container {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            .navbar-collapse {
                background: white;
                padding: 1.2rem !important;
                /* Tăng padding */
                border-radius: 0.5rem;
                margin-top: 0.8rem !important;
                /* Tăng margin top */
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                border: 1px solid #e5e7eb;
                max-height: 80vh;
                overflow-y: auto;
            }

            .main-nav {
                flex-direction: column;
                gap: 0.6rem !important;
                /* Tăng khoảng cách */
                width: 100%;
                align-items: stretch;
                margin-left: 0 !important;
            }

            .main-nav-item {
                width: 100%;
                padding: 0.9rem 1rem !important;
                /* Tăng padding */
                margin: 0.1rem 0;
                text-align: left;
                border: 1px solid #f3f4f6;
                border-radius: 0.5rem;
                font-size: 1rem !important;
                /* Tăng font size */
            }

            .logo-image {
                height: 50px !important;
                /* Tăng kích thước logo */
            }

            .notification-container {
                top: 85px !important;
                /* Điều chỉnh vị trí thông báo */
                padding: 0 10px;
            }

            .notification {
                min-width: unset;
                width: 100%;
                max-width: 100%;
                font-size: 0.95rem !important;
                padding: 12px 16px !important;
                /* Tăng padding */
            }

            /* Fix user dropdown on mobile */
            .user-dropdown {
                width: 100%;
                margin-top: 0.6rem !important;
                /* Tăng margin */
            }

            .user-dropdown .dropdown-toggle {
                width: 100%;
                justify-content: flex-start;
                background-color: #f8fafc;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 0.9rem 1rem !important;
                /* Tăng padding */
                font-size: 1rem !important;
                /* Tăng font size */
            }

            .user-dropdown .dropdown-menu {
                width: 100%;
                position: static !important;
                transform: none !important;
                border: 1px solid #e5e7eb;
                margin-top: 0.5rem !important;
                /* Tăng margin */
            }

            /* Hiển thị tên trên mobile */
            .user-name {
                display: inline !important;
                max-width: none;
                margin-left: 8px !important;
                font-size: 1rem !important;
                /* Tăng font size */
            }

            /* Fix login button on mobile */
            .btn-primary-custom {
                width: 100%;
                justify-content: center;
                padding: 0.9rem 1rem !important;
                /* Tăng padding */
                margin-top: 0.6rem !important;
                /* Tăng margin */
                font-size: 1rem !important;
                /* Tăng font size */
            }

            /* Tăng kích thước navbar toggler */
            .navbar-toggler {
                padding: 0.3rem 0.5rem !important;
                /* Tăng padding */
            }
        }

        @media (max-width: 768px) {
            .logo-image {
                height: 45px !important;
                /* Giữ logo lớn */
            }

            .navbar-techcare {
                padding: 0.5rem 0 !important;
            }

            .container {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .main-nav-item {
                font-size: 0.95rem !important;
                padding: 0.8rem 0.9rem !important;
            }

            .main-nav-item i {
                margin-right: 0.5rem;
                width: 18px;
                /* Tăng kích thước icon */
                font-size: 1.1rem;
            }

            .user-name {
                font-size: 0.95rem !important;
            }
        }

        @media (max-width: 576px) {
            .logo-image {
                height: 42px !important;
                /* Vẫn giữ lớn */
            }

            .navbar-brand {
                margin-right: 0;
            }

            .navbar-toggler {
                padding: 0.25rem 0.45rem !important;
            }

            .main-nav-item {
                font-size: 0.92rem !important;
                padding: 0.75rem 0.85rem !important;
            }

            .dropdown-item {
                padding: 0.6rem 0.8rem !important;
                /* Tăng padding */
                font-size: 0.92rem !important;
            }

            .user-name {
                font-size: 1rem !important;
                max-width: 120px;
                /* Cho phép tên dài hơn */
            }

            /* Đảm bảo header không bị cắt */
            .navbar {
                min-height: 65px;
            }
        }

        /* Fix cho dropdown không bị cắt xén */
        .navbar-collapse {
            overflow: visible !important;
        }

        .dropdown-menu {
            z-index: 10000;
        }

        /* Modal styles */
        .booking-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .booking-modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
            overflow: hidden;
            transform: translateY(-20px);
            border: 2px solid #3498db;
        }

        .booking-modal-header {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .booking-modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .booking-modal-close {
            font-size: 2rem;
            cursor: pointer;
            line-height: 1;
        }

        .booking-modal-body {
            padding: 30px 25px;
            font-size: 1.1rem;
            line-height: 1.6;
            color: #333;
            max-height: 60vh;
            overflow-y: auto;
        }

        .booking-modal-footer {
            padding: 20px 25px;
            text-align: center;
            border-top: 1px solid #eee;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .booking-modal-btn {
            padding: 12px 35px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            min-width: 120px;
            letter-spacing: 0.5px;
            border: 2px solid transparent;
        }

        .booking-modal-confirm {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
        }

        .booking-modal-cancel {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .booking-modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .booking-modal-btn:active {
            transform: translateY(0);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .display-5 {
                font-size: 2rem;
            }

            /* CÁCH NÀY GIỮ LẠI MARGIN-TOP NHƯNG FIX LỖI */
            .booking-modal {
                margin-top: 0;
                /* QUAN TRỌNG: đặt lại về 0 */
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .booking-modal-content {
                margin-top: 50%;
                /* Thay vì dùng margin-top cho modal, dùng cho content */
                width: 85%;
                max-width: 320px;
                transform: none;
                border-radius: 12px;
            }

            .card-body {
                padding: 1.5rem !important;
            }

            .btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }

            .section-border {
                padding: 15px;
            }

            .booking-modal-footer {
                flex-direction: column;
            }

            .booking-modal-btn {
                width: 100%;
            }

            /* THÊM PHẦN NÀY ĐỂ FIX CHECKBOX */
            .booking-modal-checkbox {
                margin: 20px 0;
            }

            .booking-modal-checkbox label {
                display: flex;
                align-items: center;
                cursor: pointer;
                font-size: 1rem;
                color: #333;
                margin-bottom: 10px;
            }

            .booking-modal-checkbox input[type="checkbox"] {
                width: 18px;
                height: 18px;
                margin-right: 10px;
                cursor: pointer;
                accent-color: #000;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .card-body {
                padding: 1rem !important;
            }
        }
    </style>
</head>

<body>
    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-techcare sticky-top">
        <div class="container">
            <!-- Brand/Logo với hình ảnh -->
            <a class="navbar-brand" href="<?php echo url('home'); ?>">
                <img src="<?php echo asset('images/logo.jpg'); ?>" alt="TechCare Logo" class="logo-image"> </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <div class="main-nav">
                    <?php if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] == 1)): ?>
                        <!-- Menu cho người chưa đăng nhập hoặc role = 1 (Khách hàng) -->
                        <a href="<?php echo url('home'); ?>" class="main-nav-item">
                            <i class="fas fa-home me-2"></i>TRANG CHỦ
                        </a>
                        <a href="<?php echo url('dat-dich-vu'); ?>" class="main-nav-item">
                            <i class="fas fa-tools me-2"></i>DỊCH VỤ
                        </a>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 1): ?>
                            <a href="<?php echo url('don-cua-toi'); ?>" class="main-nav-item">
                                <i class="fas fa-list me-2"></i>ĐƠN CỦA TÔI
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo url('bang-gia'); ?>" class="main-nav-item">
                            <i class="fas fa-tags me-2"></i>BẢNG GIÁ
                        </a>
                        <a href="<?php echo url('lien-he'); ?>" class="main-nav-item">
                            <i class="fas fa-phone me-2"></i>LIÊN HỆ
                        </a>

                        

                    <?php else: ?>

                        <!-- Menu cho các role khác (Nhân viên, Kỹ thuật viên, Quản lý) -->
                        <?php if ($_SESSION['role'] == 2): ?>
                            <!-- NHÂN VIÊN -->
                            <a href="<?php echo url('employee/dashboard'); ?>" class="main-nav-item">
                                <i class="fas fa-tachometer-alt me-2"></i>DASHBOARD
                            </a>
                            <a href="<?php echo url('employee/orders'); ?>" class="main-nav-item">
                                <i class="fas fa-tasks me-2"></i>QUẢN LÝ ĐƠN
                            </a>
                            <a href="<?php echo url('employee/ql_customer'); ?>" class="main-nav-item">
                                <i class="fas fa-users me-2"></i>KHÁCH HÀNG
                            </a>
                        <?php elseif ($_SESSION['role'] == 3): ?>
                            <!-- KỸ THUẬT VIÊN -->
                            <a href="<?php echo url('technician/dashboard'); ?>" class="main-nav-item">
                                <i class="fas fa-tachometer-alt me-2"></i>DASHBOARD
                            </a>
                            <a href="<?php echo url('technician/repairs'); ?>" class="main-nav-item">
                                <i class="fas fa-tools me-2"></i>SỬA CHỮA
                            </a>
                        <?php elseif ($_SESSION['role'] == 4): ?>
                            <!-- QUẢN LÝ -->
                            <a href="<?php echo url('admin/dashboard'); ?>" class="main-nav-item">
                                <i class="fas fa-tachometer-alt me-2"></i>DASHBOARD
                            </a>
                            <a href="<?php echo url('admin/orders'); ?>" class="main-nav-item">
                                <i class="fas fa-shopping-cart me-2"></i>ĐƠN HÀNG
                            </a>
                            <a href="<?php echo url('admin/staff'); ?>" class="main-nav-item">
                                <i class="fas fa-user-tie me-2"></i>NHÂN SỰ
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Right Side - User Info -->
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- USER DROPDOWN -->
                        <div class="dropdown user-dropdown">
                            <button class="btn dropdown-toggle d-flex align-items-center" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2 text-primary"></i>
                                <span class="user-name">
                                    <?php
                                    if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
                                        echo htmlspecialchars($_SESSION['user_name']);
                                    } else if (isset($_SESSION['email'])) {
                                        // Lấy phần trước @ của email
                                        $emailParts = explode('@', $_SESSION['email']);
                                        echo htmlspecialchars($emailParts[0]);
                                    } else {
                                        echo 'Tài khoản';
                                    }
                                    ?>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="<?php echo getProfileUrl($_SESSION['role']); ?>">
                                        <i class="fas fa-user me-2"></i>Thông tin cá nhân
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?php echo url('logout'); ?>">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <!-- LOGIN BUTTON -->
                        <a href="<?php echo url('dang-nhap'); ?>" class="btn btn-primary-custom">
                            <i class="fas fa-sign-in-alt me-2"></i>ĐĂNG NHẬP
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>



    <?php
    function getProfileUrl($role)
    {
        switch ($role) {
            case 1:
                return url('profile');
            case 2:
                return url('employee/profile');
            case 3:
                return url('technician/profile');
            case 4:
                return url('admin/profile');
            default:
                return url('profile');
        }
    }
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Hệ thống thông báo
        class NotificationSystem {
            constructor() {
                this.container = document.getElementById('notificationContainer');
                this.notificationCount = 0;
                this.setupEventListeners();
            }

            show(message, type = 'info', duration = 4000) {
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                this.notificationCount++;

                const icons = {
                    success: '✓',
                    error: '✕',
                    warning: '⚠',
                    info: 'ℹ'
                };

                notification.innerHTML = `
                    <span>${icons[type] || icons.info} ${message}</span>
                    <button class="notification-close">&times;</button>
                `;

                this.container.appendChild(notification);

                const autoRemove = setTimeout(() => {
                    this.remove(notification);
                }, duration);

                const closeBtn = notification.querySelector('.notification-close');
                closeBtn.addEventListener('click', () => {
                    clearTimeout(autoRemove);
                    this.remove(notification);
                });

                return notification;
            }

            remove(notification) {
                notification.classList.add('hiding');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                        this.notificationCount--;
                    }
                }, 300);
            }

            success(message, duration = 4000) {
                return this.show(message, 'success', duration);
            }

            error(message, duration = 5000) {
                return this.show(message, 'error', duration);
            }

            info(message, duration = 4000) {
                return this.show(message, 'info', duration);
            }

            warning(message, duration = 4500) {
                return this.show(message, 'warning', duration);
            }

            setupEventListeners() {
                // Active menu item
                const currentPath = window.location.pathname;
                const menuItems = document.querySelectorAll('.main-nav-item');

                menuItems.forEach(item => {
                    if (item.getAttribute('href') === currentPath) {
                        item.classList.add('active');
                    }
                });
            }
        }

        // Khởi tạo hệ thống thông báo
        const notification = new NotificationSystem();

        document.addEventListener('DOMContentLoaded', function () {
            // Xử lý thông báo từ session
            <?php if (isset($_SESSION['success_message'])): ?>
                setTimeout(() => {
                    notification.success('<?php echo addslashes($_SESSION['success_message']); ?>');
                }, 500);
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                setTimeout(() => {
                    notification.error('<?php echo addslashes($_SESSION['error']); ?>');
                }, 500);
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['warning_message'])): ?>
                setTimeout(() => {
                    notification.warning('<?php echo addslashes($_SESSION['warning_message']); ?>');
                }, 500);
                <?php unset($_SESSION['warning_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['info_message'])): ?>
                setTimeout(() => {
                    notification.info('<?php echo addslashes($_SESSION['info_message']); ?>');
                }, 500);
                <?php unset($_SESSION['info_message']); ?>
            <?php endif; ?>
        });

        window.showNotification = notification;


        // MODAL SYSTEM - Tạo modal động
        class ModalSystem {
            constructor() {
                this.modal = null;
                this.isInitialized = false;
            }

            init() {
                if (this.isInitialized) return;

                // Tạo modal HTML động
                this.createModal();
                this.setupEventListeners();
                this.isInitialized = true;
            }

            createModal() {
                const modalHTML = `
            <div id="dynamicModal" class="booking-modal">
                <div class="booking-modal-content">
                    <div class="booking-modal-header">
                        <h3 id="dynamicModalTitle">Thông báo</h3>
                        <span class="booking-modal-close">&times;</span>
                    </div>
                    <div class="booking-modal-body">
                        <div id="dynamicModalMessage"></div>
                    </div>
                    <div class="booking-modal-footer">
                        <button type="button" class="booking-modal-btn booking-modal-confirm" id="dynamicModalConfirmBtn">Xác nhận</button>
                        <button type="button" class="booking-modal-btn booking-modal-cancel" id="dynamicModalCancelBtn">Hủy</button>
                    </div>
                </div>
            </div>
        `;

                document.body.insertAdjacentHTML('beforeend', modalHTML);

                this.modal = document.getElementById('dynamicModal');
                this.modalTitle = document.getElementById('dynamicModalTitle');
                this.modalMessage = document.getElementById('dynamicModalMessage');
                this.confirmBtn = document.getElementById('dynamicModalConfirmBtn');
                this.cancelBtn = document.getElementById('dynamicModalCancelBtn');
                this.closeBtn = document.querySelector('.booking-modal-close');
            }

            setupEventListeners() {
                if (!this.modal) return;

                // Đóng modal khi click bên ngoài
                this.modal.addEventListener('click', (e) => {
                    if (e.target === this.modal) {
                        this.hide();
                    }
                });

                // Đóng modal khi click nút close
                if (this.closeBtn) {
                    this.closeBtn.addEventListener('click', () => {
                        this.hide();
                    });
                }

                // Đóng modal khi click cancel
                if (this.cancelBtn) {
                    this.cancelBtn.addEventListener('click', () => {
                        this.hide();
                        if (this.cancelCallback) {
                            this.cancelCallback();
                        }
                    });
                }

                // Xử lý phím ESC
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.modal && this.modal.style.display === 'block') {
                        this.hide();
                        if (this.cancelCallback) {
                            this.cancelCallback();
                        }
                    }
                });
            }

            show(title, message, confirmCallback = null, cancelCallback = null) {
                // Đảm bảo modal đã được khởi tạo
                if (!this.isInitialized) {
                    this.init();
                }

                this.modalTitle.textContent = title;
                this.modalMessage.innerHTML = message;
                this.modal.style.display = 'block';

                this.confirmCallback = confirmCallback;
                this.cancelCallback = cancelCallback;

                // Xử lý nút xác nhận
                if (this.confirmBtn) {
                    this.confirmBtn.onclick = () => {
                        if (this.confirmCallback) {
                            this.confirmCallback();
                        }
                        this.hide();
                    };
                }

                // Hiển thị/ẩn nút hủy dựa trên callback
                if (this.cancelBtn) {
                    if (cancelCallback) {
                        this.cancelBtn.style.display = 'block';
                    } else {
                        this.cancelBtn.style.display = 'none';
                    }
                }

                // Thêm hiệu ứng và khóa scroll body
                document.body.style.overflow = 'hidden';
                document.documentElement.style.overflow = 'hidden';
            }

            hide() {
                if (!this.modal) return;

                this.modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                document.documentElement.style.overflow = 'auto';

                // Reset callbacks
                this.confirmCallback = null;
                this.cancelCallback = null;
            }
        }

        // Global modal instance
        let modalSystem = null;

        function getModalSystem() {
            if (!modalSystem) {
                modalSystem = new ModalSystem();
            }
            return modalSystem;
        }

        // Hàm tiện ích để gọi modal
        function showAlert(message, title = 'Thông báo') {
            const modal = getModalSystem();
            modal.show(title, message, () => {
                // Callback mặc định khi click xác nhận
            });
        }

        function showConfirm(message, title = 'Xác nhận', confirmCallback = null, cancelCallback = null) {
            const modal = getModalSystem();
            modal.show(title, message, confirmCallback, cancelCallback);
        }

    </script>