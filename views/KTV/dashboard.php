<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Kỹ Thuật Viên - TechCare";
include __DIR__ . '/../header.php';

// Kiểm tra role - chỉ cho phép nhân viên (role 2,3,4) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 1) {
    header('Location: ' . url('home'));
    exit();
}

// Lấy thông tin nhân viên (giả sử có hàm getEmployeeInfo)
require_once __DIR__ . '/../../models/KTV.php';
$employeeModel = new KTV($db);
$employeeInfo = $employeeModel->finKTVdByID($_SESSION['user_id']);

// Giả lập dữ liệu đánh giá (trong thực tế sẽ lấy từ database)
$rating = 4.5; // Điểm đánh giá trung bình
$totalReviews = 24; // Tổng số đánh giá
$completedOrders = 156; // Số đơn đã hoàn thành
?>

<section class="ktv-dashboard-section">
    <div class="container">
        <!-- THÔNG TIN KTV -->
        <div class="ktv-profile-section">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar-container">
                        <div class="avatar">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div class="online-status"></div>
                    </div>
                    <div class="profile-info">
                        <h1 class="employee-name"><?php echo htmlspecialchars($employeeInfo['name'] ?? 'Kỹ Thuật Viên'); ?></h1>
                        <p class="employee-role">Kỹ Thuật Viên</p>
                        <div class="employee-stats">
                            <div class="stat-item">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo $completedOrders; ?> đơn hoàn thành</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="rating-section">
                    <div class="rating-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo $i <= floor($rating) ? 'active' : ''; ?>"></i>
                        <?php endfor; ?>
                        <span class="rating-score"><?php echo number_format($rating, 1); ?></span>
                    </div>
                    <p class="rating-count"><?php echo $totalReviews; ?> đánh giá</p>
                </div>
            </div>
        </div>

        <!-- CÁC Ô CHỨC NĂNG KTV -->
        <div class="function-grid-section">
            <h3><i class="fas fa-th-large"></i> Chức năng làm việc</h3>
            
            <div class="function-grid">
                <!-- Xem đơn phân công -->
                <a href="<?php echo url('KTV/don-cua-toi'); ?>" class="function-card">
                    <div class="function-icon assigned-orders">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="function-info">
                        <h4>Đơn phân công</h4>
                        <p>Xem và xử lý đơn được phân công</p>
                        <!-- <span class="badge">12 đơn mới</span> -->
                    </div>
                </a>

                <!-- Xem lịch phân công -->
                <a href="<?php echo url('KTV/xemLPC'); ?>" class="function-card">
                    <div class="function-icon schedule">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="function-info">
                        <h4>Lịch làm việc</h4>
                        <p>Lịch làm việc và phân công nhiệm vụ</p>
                    </div>
                </a>

                <!-- Xem doanh số -->
                <a href="<?php echo url('employee/doanhso'); ?>" class="function-card">
                    <div class="function-icon revenue">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="function-info">
                        <h4>Doanh số</h4>
                        <p>Theo dõi doanh số và hiệu suất</p>
                        <span class="revenue-amount">15.2M ₫</span>
                    </div>
                </a>

                <!-- Xem đánh giá -->
                <a href="<?php echo url('employee/danhgia'); ?>" class="function-card">
                    <div class="function-icon reviews">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="function-info">
                        <h4>Đánh giá</h4>
                        <p>Xem đánh giá và phản hồi từ khách hàng</p>
                    </div>
                </a>

                <!-- Quản lý đơn hàng -->
                <a href="<?php echo url('employee/donhang'); ?>" class="function-card">
                    <div class="function-icon orders">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="function-info">
                        <h4>Xử lý đơn hàng</h4>
                        <p>Quản lý và cập nhật trạng thái đơn hàng</p>
                    </div>
                </a>

                <!-- Báo cáo công việc -->
                <a href="<?php echo url('employee/baocao'); ?>" class="function-card">
                    <div class="function-icon report">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="function-info">
                        <h4>Báo cáo</h4>
                        <p>Báo cáo công việc hàng ngày</p>
                    </div>
                </a>

                <!-- Thiết bị & dụng cụ -->
                <a href="<?php echo url('employee/thietbi'); ?>" class="function-card">
                    <div class="function-icon tools">
                        <i class="fas fa-toolbox"></i>
                    </div>
                    <div class="function-info">
                        <h4>Thiết bị</h4>
                        <p>Quản lý dụng cụ và thiết bị sửa chữa</p>
                    </div>
                </a>

                <!-- Hỗ trợ khách hàng -->
                <a href="<?php echo url('employee/hotro'); ?>" class="function-card">
                    <div class="function-icon support">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="function-info">
                        <h4>Hỗ trợ</h4>
                        <p>Hỗ trợ kỹ thuật và tư vấn khách hàng</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- THỐNG KÊ NHANH -->
        <div class="quick-stats-section">
            <h3><i class="fas fa-chart-bar"></i> Thống kê nhanh</h3>
            <div class="stats-grid">
                <div class="stat-card today">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-info">
                        <h4>8</h4>
                        <p>Đơn hôm nay</p>
                    </div>
                </div>
                
                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h4>5</h4>
                        <p>Đang chờ xử lý</p>
                    </div>
                </div>
                
                <div class="stat-card completed">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h4>3</h4>
                        <p>Đã hoàn thành</p>
                    </div>
                </div>
                
                <div class="stat-card rating">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h4><?php echo number_format($rating, 1); ?></h4>
                        <p>Điểm đánh giá</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../footer.php'; ?>

<style>
.ktv-dashboard-section {
    padding: 30px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* PROFILE SECTION */
.ktv-profile-section {
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

.avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3498db, #2980b9);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    border: 4px solid white;
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
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
    color: #3498db;
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

.stat-item i {
    color: #27ae60;
}

/* RATING SECTION */
.rating-section {
    text-align: center;
}

.rating-stars {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 5px;
    justify-content: center;
}

.rating-stars .fa-star {
    color: #ddd;
    font-size: 1.2rem;
}

.rating-stars .fa-star.active {
    color: #ffc107;
}

.rating-score {
    margin-left: 10px;
    font-weight: 700;
    color: #2c3e50;
    font-size: 1.1rem;
}

.rating-count {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
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
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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

.assigned-orders {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.schedule {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
}

.revenue {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
}

.reviews {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.orders {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.report {
    background: linear-gradient(135deg, #34495e, #2c3e50);
}

.tools {
    background: linear-gradient(135deg, #1abc9c, #16a085);
}

.support {
    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
}

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

.revenue-amount {
    color: #27ae60;
    font-weight: 700;
    font-size: 0.9rem;
    margin-top: 5px;
    display: block;
}

/* THỐNG KÊ NHANH */
.quick-stats-section {
    margin-bottom: 30px;
}

.quick-stats-section h3 {
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.3rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.3s;
    border-left: 4px solid;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.stat-card.today {
    border-left-color: #3498db;
}

.stat-card.pending {
    border-left-color: #f39c12;
}

.stat-card.completed {
    border-left-color: #27ae60;
}

.stat-card.rating {
    border-left-color: #ffc107;
}

.stat-card .stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: white;
}

.stat-card.today .stat-icon {
    background: #3498db;
}

.stat-card.pending .stat-icon {
    background: #f39c12;
}

.stat-card.completed .stat-icon {
    background: #27ae60;
}

.stat-card.rating .stat-icon {
    background: #ffc107;
}

.stat-info h4 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.5rem;
    font-weight: 700;
}

.stat-info p {
    margin: 5px 0 0 0;
    color: #6c757d;
    font-size: 0.85rem;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .ktv-dashboard-section {
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
    
    .function-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .avatar {
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
}
</style>