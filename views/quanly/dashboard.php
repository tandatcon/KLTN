<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Quản Lý - TechCare";
include __DIR__ . '/../header.php';

// Kiểm tra role - chỉ cho phép quản lý (role 3,4) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] < 3) {
    header('Location: ' . url('home'));
    exit();
}

// Lấy thông tin quản lý
require_once __DIR__ . '/../../models/QL.php';
$employeeModel = new QL($db);
$managerInfo = $employeeModel->finQLdByID($_SESSION['user_id']);

// Giả lập dữ liệu thống kê
$totalEmployees = 0;
$activeKTV = 0;
$pendingAssignments = 0;
$totalRevenue = 0; // triệu
?>

<section class="manager-dashboard-section">
    <div class="container">
        <!-- THÔNG TIN QUẢN LÝ -->
        <div class="manager-profile-section">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar-container">
                        <div class="avatar manager-avatar">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="online-status"></div>
                    </div>
                    <div class="profile-info">
                        <h1 class="employee-name"><?php echo htmlspecialchars($managerInfo['name'] ?? 'Quản Lý'); ?></h1>
                        <p class="employee-role">Quản Lý</p>
                        <div class="employee-stats">
                            <div class="stat-item">
                                <i class="fas fa-users"></i>
                                <span><?php echo $totalEmployees; ?> nhân viên</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-user-cog"></i>
                                <span><?php echo $activeKTV; ?> KTV đang hoạt động</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="quick-actions">
                    <a href="<?php echo url('manager/phancong'); ?>" class="btn-action primary">
                        <i class="fas fa-tasks"></i>
                        Phân công mới
                    </a>
                    <a href="<?php echo url('manager/baocao'); ?>" class="btn-action secondary">
                        <i class="fas fa-chart-bar"></i>
                        Báo cáo
                    </a>
                </div>
            </div>
        </div>

        <!-- THỐNG KÊ TỔNG QUAN -->
        <!-- <div class="overview-stats-section">
            <h3><i class="fas fa-chart-pie"></i> Tổng quan hệ thống</h3>
            <div class="stats-grid">
                <div class="stat-card total-revenue">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info">
                        <h4><?php echo number_format($totalRevenue, 1); ?>M ₫</h4>
                        <p>Doanh thu tháng</p>
                        <span class="trend up">+12.5%</span>
                    </div>
                </div>
                
                <div class="stat-card total-orders">
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-info">
                        <h4>0</h4>
                        <p>Đơn hàng tháng</p>
                        <span class="trend up">+8.2%</span>
                    </div>
                </div>
                
                <div class="stat-card pending-assignments">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h4><?php echo $pendingAssignments; ?></h4>
                        <p>Đơn chờ phân công</p>
                        <span class="trend warning">Cần xử lý</span>
                    </div>
                </div>
                
                <div class="stat-card employee-count">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h4><?php echo $totalEmployees; ?></h4>
                        <p>Tổng nhân viên</p>
                        <span class="trend"><?php echo $activeKTV; ?> đang hoạt động</span>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- CÁC Ô CHỨC NĂNG QUẢN LÝ -->
        <div class="function-grid-section">
            <h3><i class="fas fa-cogs"></i> Quản lý hệ thống</h3>
            
            <div class="function-grid">
                <!-- Phân công KTV -->
                <a href="<?php echo url('quanly/phancong'); ?>" class="function-card">
                    <div class="function-icon assignment">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="function-info">
                        <h4>Phân công KTV</h4>
                        <p>Phân công đơn hàng cho kỹ thuật viên</p>
                        <span class="badge"><?php echo $pendingAssignments; ?> đơn chờ</span>
                    </div>
                </a>

                <!-- Xem thống kê hiệu suất KTV -->
                <a href="<?php echo url('manager/hieusuatktv'); ?>" class="function-card">
                    <div class="function-icon performance">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="function-info">
                        <h4>Hiệu suất KTV</h4>
                        <p>Theo dõi và đánh giá hiệu suất làm việc</p>
                    </div>
                </a>

                <!-- Quản lý nhân viên & KTV -->
                <a href="<?php echo url('quanly/themNS'); ?>" class="function-card">
                    <div class="function-icon employees">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="function-info">
                        <h4>Quản trị nhân sự</h4>
                        <p>Quản lý thông tin nhân viên và KTV</p>
                    </div>
                </a>

                <!-- Quản lý người dùng -->
                <a href="<?php echo url('quanly/quantringuoidung'); ?>" class="function-card">
                    <div class="function-icon users">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="function-info">
                        <h4>Quản lý người dùng</h4>
                        <p>Quản lý tài khoản và phân quyền người dùng</p>
                    </div>
                </a>

                <!-- Quản lý lịch làm việc -->
                <a href="<?php echo url('manager/lichlamviec'); ?>" class="function-card">
                    <div class="function-icon schedule">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="function-info">
                        <h4>Lịch làm việc</h4>
                        <p>Quản lý lịch trình và ca làm việc</p>
                    </div>
                </a>

                <a href="<?php echo url('quanly/phanconglich'); ?>" class="function-card">
                    <div class="function-icon schedule">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="function-info">
                        <h4>Phân công lịch</h4>
                        <p>Quản lý lịch trình và ca làm việc</p>
                    </div>
                </a>

                <!-- Báo cáo & Thống kê -->
                <a href="<?php echo url('manager/baocaothongke'); ?>" class="function-card">
                    <div class="function-icon reports">
                        <i class="fas fa-file-chart-line"></i>
                    </div>
                    <div class="function-info">
                        <h4>Báo cáo & Thống kê</h4>
                        <p>Báo cáo doanh số và hiệu suất toàn hệ thống</p>
                    </div>
                </a>

                <!-- Quản lý dịch vụ -->
                <a href="<?php echo url('manager/dichvu'); ?>" class="function-card">
                    <div class="function-icon services">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <div class="function-info">
                        <h4>Quản lý dịch vụ</h4>
                        <p>Quản lý danh mục và giá dịch vụ</p>
                    </div>
                </a>

                <!-- Quản lý thiết bị -->
                <a href="<?php echo url('manager/thietbi'); ?>" class="function-card">
                    <div class="function-icon inventory">
                        <i class="fas fa-toolbox"></i>
                    </div>
                    <div class="function-info">
                        <h4>Quản lý thiết bị</h4>
                        <p>Quản lý kho và dụng cụ thiết bị</p>
                    </div>
                </a>

                <!-- Quản lý đánh giá -->
                <a href="<?php echo url('manager/danhgia'); ?>" class="function-card">
                    <div class="function-icon reviews">
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="function-info">
                        <h4>Quản lý đánh giá</h4>
                        <p>Theo dõi và phản hồi đánh giá khách hàng</p>
                    </div>
                </a>

                <!-- Cài đặt hệ thống -->
                <a href="<?php echo url('manager/caidat'); ?>" class="function-card">
                    <div class="function-icon settings">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div class="function-info">
                        <h4>Cài đặt hệ thống</h4>
                        <p>Cấu hình và cài đặt hệ thống</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- THÔNG BÁO & CÔNG VIỆC GẦN ĐÂY -->
        <div class="recent-activities-section">
            <div class="activities-grid">
                <!-- Thông báo gần đây -->
                <div class="activity-card notifications">
                    <div class="card-header">
                        <h4><i class="fas fa-bell"></i> Thông báo gần đây</h4>
                        <span class="badge">3 mới</span>
                    </div>
                    <div class="card-body">
                        <div class="notification-item new">
                            <div class="notification-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="notification-content">
                                <p><strong>5 đơn hàng mới</strong> cần phân công KTV</p>
                                <span class="time">10 phút trước</span>
                            </div>
                        </div>
                        <div class="notification-item new">
                            <div class="notification-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="notification-content">
                                <p><strong>3 đánh giá mới</strong> từ khách hàng</p>
                                <span class="time">25 phút trước</span>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div class="notification-content">
                                <p>KTV <strong>Nguyễn Văn A</strong> xin nghỉ phép</p>
                                <span class="time">1 giờ trước</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KTV hiệu suất cao -->
                <div class="activity-card top-performers">
                    <div class="card-header">
                        <h4><i class="fas fa-trophy"></i> KTV hiệu suất cao</h4>
                    </div>
                    <div class="card-body">
                        <div class="performer-item">
                            <div class="performer-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="performer-info">
                                <h5>Trần Văn B</h5>
                                <p>45 đơn/tháng • 4.8★</p>
                            </div>
                            <div class="performer-stats">
                                <span class="success-rate">98%</span>
                            </div>
                        </div>
                        <div class="performer-item">
                            <div class="performer-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="performer-info">
                                <h5>Lê Thị C</h5>
                                <p>38 đơn/tháng • 4.9★</p>
                            </div>
                            <div class="performer-stats">
                                <span class="success-rate">99%</span>
                            </div>
                        </div>
                        <div class="performer-item">
                            <div class="performer-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="performer-info">
                                <h5>Phạm Văn D</h5>
                                <p>42 đơn/tháng • 4.7★</p>
                            </div>
                            <div class="performer-stats">
                                <span class="success-rate">97%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../footer.php'; ?>

<style>
.manager-dashboard-section {
    padding: 30px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* PROFILE SECTION */
.manager-profile-section {
    margin-bottom: 30px;
}

.profile-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
}

.avatar-container {
    position: relative;
}

.manager-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    border: 4px solid white;
    box-shadow: 0 5px 15px rgba(155, 89, 182, 0.3);
}

.online-status {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #2ecc71;
    border: 3px solid white;
}

.profile-info h1 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.8rem;
    font-weight: 700;
}

.employee-role {
    margin: 5px 0;
    color: #9b59b6;
    font-weight: 600;
    font-size: 1.1rem;
}

.employee-stats {
    display: flex;
    gap: 20px;
    margin-top: 10px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 0.9rem;
}

.quick-actions {
    display: flex;
    gap: 15px;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.btn-action.primary {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
}

.btn-action.secondary {
    background: white;
    color: #3498db;
    border-color: #3498db;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* THỐNG KÊ TỔNG QUAN */
.overview-stats-section {
    margin-bottom: 30px;
}

.overview-stats-section h3 {
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.3rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.3s;
    border-left: 4px solid;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.stat-card.total-revenue {
    border-left-color: #27ae60;
}

.stat-card.total-orders {
    border-left-color: #3498db;
}

.stat-card.pending-assignments {
    border-left-color: #e74c3c;
}

.stat-card.employee-count {
    border-left-color: #9b59b6;
}

.stat-card .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-card.total-revenue .stat-icon {
    background: #27ae60;
}

.stat-card.total-orders .stat-icon {
    background: #3498db;
}

.stat-card.pending-assignments .stat-icon {
    background: #e74c3c;
}

.stat-card.employee-count .stat-icon {
    background: #9b59b6;
}

.stat-info h4 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.8rem;
    font-weight: 700;
}

.stat-info p {
    margin: 5px 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.trend {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 10px;
}

.trend.up {
    background: #d4edda;
    color: #155724;
}

.trend.warning {
    background: #fff3cd;
    color: #856404;
}

.trend.down {
    background: #f8d7da;
    color: #721c24;
}

/* CÁC Ô CHỨC NĂNG */
.function-grid-section {
    margin-bottom: 30px;
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
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.function-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s;
    border: 2px solid transparent;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 15px;
    position: relative;
}

.function-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
    border-color: #3498db;
}

.function-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.assignment { background: linear-gradient(135deg, #e74c3c, #c0392b); }
.performance { background: linear-gradient(135deg, #3498db, #2980b9); }
.employees { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
.users { background: linear-gradient(135deg, #1abc9c, #16a085); }
.schedule { background: linear-gradient(135deg, #f39c12, #e67e22); }
.reports { background: linear-gradient(135deg, #34495e, #2c3e50); }
.services { background: linear-gradient(135deg, #2ecc71, #27ae60); }
.inventory { background: linear-gradient(135deg, #e67e22, #d35400); }
.reviews { background: linear-gradient(135deg, #f1c40f, #f39c12); }
.settings { background: linear-gradient(135deg, #7f8c8d, #95a5a6); }

.function-info {
    flex: 1;
}

.function-info h4 {
    color: #2c3e50;
    margin-bottom: 5px;
    font-size: 1.1rem;
    font-weight: 600;
}

.function-info p {
    color: #6c757d;
    font-size: 0.85rem;
    margin: 0;
    line-height: 1.4;
}

.badge {
    background: #e74c3c;
    color: white;
    padding: 3px 8px;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-top: 5px;
    display: inline-block;
}

/* HOẠT ĐỘNG GẦN ĐÂY */
.recent-activities-section {
    margin-bottom: 30px;
}

.activities-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.activity-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    overflow: hidden;
}

.card-header {
    background: #f8f9fa;
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h4 {
    margin: 0;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.1rem;
}

.card-body {
    padding: 20px;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f8f9fa;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.new {
    background: #f8f9ff;
    margin: 0 -20px;
    padding: 15px 20px;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #3498db;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.notification-item.new .notification-icon {
    background: #e74c3c;
}

.notification-content p {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 0.9rem;
}

.time {
    color: #6c757d;
    font-size: 0.8rem;
}

.performer-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f8f9fa;
}

.performer-item:last-child {
    border-bottom: none;
}

.performer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #3498db;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.performer-info h5 {
    margin: 0;
    color: #2c3e50;
    font-size: 0.95rem;
}

.performer-info p {
    margin: 2px 0 0 0;
    color: #6c757d;
    font-size: 0.8rem;
}

.performer-stats {
    margin-left: auto;
}

.success-rate {
    background: #27ae60;
    color: white;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* RESPONSIVE */
@media (max-width: 1024px) {
    .activities-grid {
        grid-template-columns: 1fr;
    }
    
    .function-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }
}

@media (max-width: 768px) {
    .manager-dashboard-section {
        padding: 20px 0;
    }
    
    .profile-card {
        flex-direction: column;
        text-align: center;
        padding: 20px;
    }
    
    .profile-header {
        flex-direction: column;
    }
    
    .employee-stats {
        justify-content: center;
    }
    
    .quick-actions {
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .function-grid {
        grid-template-columns: 1fr;
    }
    
    .manager-avatar {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .function-card {
        padding: 20px;
    }
    
    .function-icon {
        width: 50px;
        height: 50px;
        font-size: 1.3rem;
    }
    
    .quick-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-action {
        justify-content: center;
    }
}
</style>