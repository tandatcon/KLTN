<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Quản lý khách hàng - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../models/Customer.php';

$customerModel = new Customer($db);

// Kiểm tra role - chỉ cho phép nhân viên (role 2,3,4) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 1) {
    header('Location: ' . url('home'));
    exit();
}
?>

<section class="customer-management-section">
    <div class="container">
        <!-- CÁC Ô CHỨC NĂNG -->
        <div class="function-grid-section">
            <h3><i class="fas fa-th-large"></i> Chức năng quản lý</h3>
            
            <div class="function-grid">
                <!-- Thêm khách hàng -->
                <a href="<?php echo url('employee/themkhachhang'); ?>" class="function-card">
                    <div class="function-icon add-customer">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="function-info">
                        <h4>Thêm khách hàng</h4>
                        <p>Thêm khách hàng mới vào hệ thống</p>
                    </div>
                </a>

                <!-- Lập dịch vụ -->
                <a href="<?php echo url('employee/themdichvu'); ?>" class="function-card">
                    <div class="function-icon create-service">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="function-info">
                        <h4>Lập dịch vụ</h4>
                        <p>Đăng ký dịch vụ sửa chữa mới</p>
                    </div>
                </a>

                <!-- Xử lý khiếu nại -->
                <a href="<?php echo url('employee/khieunai'); ?>" class="function-card">
                    <div class="function-icon complaint">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="function-info">
                        <h4>Xử lý khiếu nại</h4>
                        <p>Tiếp nhận và xử lý khiếu nại</p>
                    </div>
                </a>

                <!-- Quản lý đơn hàng -->
                <a href="<?php echo url('employee/donhang'); ?>" class="function-card">
                    <div class="function-icon orders">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="function-info">
                        <h4>Quản lý đơn hàng</h4>
                        <p>Theo dõi và quản lý đơn dịch vụ</p>
                    </div>
                </a>

                <!-- Thống kê -->
                <a href="<?php echo url('employee/thongke'); ?>" class="function-card">
                    <div class="function-icon statistics">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="function-info">
                        <h4>Thống kê</h4>
                        <p>Báo cáo và thống kê dịch vụ</p>
                    </div>
                </a>

                <!-- Lịch hẹn -->
                <a href="<?php echo url('employee/lichhen'); ?>" class="function-card">
                    <div class="function-icon schedule">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="function-info">
                        <h4>Lịch hẹn</h4>
                        <p>Quản lý lịch hẹn với khách hàng</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../footer.php'; ?>

<style>
.customer-management-section {
    padding: 40px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

/* CÁC Ô CHỨC NĂNG */
.function-grid-section {
    margin-bottom: 40px;
}

.function-grid-section h3 {
    color: #2c3e50;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.3rem;
}

.function-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
}

.function-card {
    background: white;
    border-radius: 15px;
    padding: 30px 25px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s;
    border: 2px solid transparent;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 20px;
}

.function-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
    border-color: #3498db;
}

.function-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
}

.add-customer {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
}

.create-service {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.complaint {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.orders {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
}

.statistics {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.schedule {
    background: linear-gradient(135deg, #1abc9c, #16a085);
}

.function-info h4 {
    color: #2c3e50;
    margin-bottom: 8px;
    font-size: 1.1rem;
    font-weight: 600;
}

.function-info p {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
    line-height: 1.4;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .customer-management-section {
        padding: 20px 0;
    }
    
    .function-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .function-card {
        padding: 20px;
    }
    
    .function-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .function-card {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
}
</style>