<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Chi tiết khách hàng - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../models/Customer.php';
require_once __DIR__ . '/../../models/Order.php';

$customerModel = new Customer($db);
$orderModel = new Order($db);

// Kiểm tra role - chỉ cho phép nhân viên (role 2,3,4) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 1) {
    header('Location: ' . url('home'));
    exit();
}

// Lấy ID khách hàng từ URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($customerId === 0) {
    header('Location: ' . url('employee/ql_customer'));
    exit();
}

// Lấy thông tin khách hàng
$customer = $customerModel->findByID($customerId);

if (!$customer) {
    echo "<div class='container'><div class='alert alert-danger'>Khách hàng không tồn tại!</div></div>";
    include __DIR__ . '/../footer.php';
    exit();
}

// Lấy danh sách đơn hàng của khách hàng
$orders = $orderModel->getOrdersByKHID($customerId);

// Hàm helper để tránh lỗi deprecated
function safe_htmlspecialchars($value) {
    return $value !== null ? htmlspecialchars($value) : '';
}

// Hàm chuyển đổi trạng thái đơn hàng
function getStatusBadge($status) {
    $statuses = [
        1 => ['label' => 'Chờ xác nhận', 'class' => 'status-pending'],
        2 => ['label' => 'Đã xác nhận', 'class' => 'status-confirmed'],
        3 => ['label' => 'Đang xử lý', 'class' => 'status-processing'],
        4 => ['label' => 'Hoàn thành', 'class' => 'status-completed'],
        5 => ['label' => 'Đã hủy', 'class' => 'status-cancelled']
    ];
    
    if (isset($statuses[$status])) {
        return "<span class='status-badge {$statuses[$status]['class']}'>{$statuses[$status]['label']}</span>";
    }
    
    return "<span class='status-badge status-unknown'>Không xác định</span>";
}
?>

<section class="customer-detail-section">
    <div class="container">
        <!-- HEADER -->
        <div class="customer-detail-header">
            <div class="header-actions">
                <a href="<?php echo url('employee/ql_customer'); ?>" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <div class="action-buttons">
                    <a href="<?php echo url('employee/suakhachhang?id=' . $customerId); ?>" class="btn-edit">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="<?php echo url('employee/themdichvu?customer_id=' . $customerId); ?>" class="btn-primary">
                        <i class="fas fa-plus"></i> Tạo đơn dịch vụ
                    </a>
                </div>
            </div>
            
            <div class="customer-profile">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="profile-info">
                    <h1 class="customer-name"><?php echo safe_htmlspecialchars($customer['hoTen']); ?></h1>
                    <p class="customer-id">Mã KH: <?php echo str_pad($customer['maND'], 4, '0', STR_PAD_LEFT); ?></p>
                </div>
            </div>
        </div>

        <div class="customer-detail-content">
            <!-- THÔNG TIN CÁ NHÂN -->
            <div class="info-card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Thông tin cá nhân</h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label><i class="fas fa-phone"></i> Số điện thoại</label>
                            <span class="info-value"><?php echo safe_htmlspecialchars($customer['sdt']); ?></span>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-envelope"></i> Email</label>
                            <span class="info-value">
                                <?php echo !empty($customer['email']) ? safe_htmlspecialchars($customer['email']) : '<span class="text-muted">Chưa có email</span>'; ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-map-marker-alt"></i> Địa chỉ</label>
                            <span class="info-value">
                                <?php echo !empty($customer['address']) ? safe_htmlspecialchars($customer['address']) : '<span class="text-muted">Chưa có địa chỉ</span>'; ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-calendar"></i> Ngày tạo</label>
                            <span class="info-value">
                                <?php echo !empty($customer['created_at']) ? date('d/m/Y H:i', strtotime($customer['created_at'])) : 'N/A'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LỊCH SỬ ĐƠN HÀNG -->
            <div class="info-card">
                <div class="card-header">
                    <h3><i class="fas fa-history"></i> Lịch sử đơn hàng</h3>
                    <span class="badge-count"><?php echo count($orders); ?> đơn</span>
                </div>
                <div class="card-body">
                    <?php if (!empty($orders)): ?>
                        <div class="orders-table-container">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Dịch vụ</th>
                                        <th>Ngày tạo</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td class="order-id">DH<?php echo ' '. $order['maDon']; ?></td>
                                        <td class="order-service">
                                            <?php 
                                            // Hiển thị tên dịch vụ chính
                                             if (isset($order['noiSuaChua'])): ?>
                                                <?php
                                                if ($order['noiSuaChua'] == 0) {
                                                    echo "🏪 Cửa hàng";
                                                } else if ($order['noiSuaChua'] == 1) {
                                                    echo "🏠 Tại nhà";
                                                } else {
                                                    echo '<span class="text-muted">Chưa xác định</span>';
                                                }
                                                ?>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa có thông tin</span>
                                            <?php endif; ?>                                            
                                        </td>
                                        <td class="order-date"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                        <td class="order-total">
                                            <?php echo !empty($order['total_amount']) ? number_format($order['total_amount'], 0, ',', '.') . ' ₫' : 'Đang tính'; ?>
                                        </td>
                                        <td class="order-status">
                                            <?php echo getStatusBadge($order['trangThai']); ?>
                                        </td>
                                        <td class="order-actions">
                                            <a href="<?php echo url('employee/chitietdonhang?id=' . $order['maDon']); ?>" class="btn-action btn-view">
                                                <i class="fas fa-eye"></i> Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-orders">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Khách hàng chưa có đơn hàng nào</p>
                            <a href="<?php echo url('employee/themdichvu?customer_id=' . $customerId); ?>" class="btn-primary">
                                <i class="fas fa-plus"></i> Tạo đơn đầu tiên
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- THỐNG KÊ NHANH -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon total-orders">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-info">
                        <h4><?php echo count($orders); ?></h4>
                        <p>Tổng đơn hàng</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon completed-orders">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h4>
                            <?php 
                            $completed = array_filter($orders, function($order) {
                                return $order['status'] == 4; // Hoàn thành
                            });
                            echo count($completed);
                            ?>
                        </h4>
                        <p>Đơn hoàn thành</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon pending-orders">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h4>
                            <?php 
                            $pending = array_filter($orders, function($order) {
                                return in_array($order['status'], [1, 2, 3]); // Chờ xác nhận, đã xác nhận, đang xử lý
                            });
                            echo count($pending);
                            ?>
                        </h4>
                        <p>Đơn đang xử lý</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon total-revenue">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info">
                        <h4>
                            <?php 
                            $revenue = 0;
                            foreach ($orders as $order) {
                                if ($order['status'] == 4 && !empty($order['total_amount'])) { // Chỉ tính đơn hoàn thành
                                    $revenue += $order['total_amount'];
                                }
                            }
                            echo number_format($revenue, 0, ',', '.') . ' ₫';
                            ?>
                        </h4>
                        <p>Tổng doanh thu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../footer.php'; ?>

<style>
.customer-detail-section {
    padding: 30px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* HEADER */
.customer-detail-header {
    margin-bottom: 30px;
}

.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.btn-back {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-back:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
}

.action-buttons {
    display: flex;
    gap: 15px;
}

.btn-edit, .btn-primary {
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    font-weight: 500;
}

.btn-edit {
    background: #ffc107;
    color: #212529;
}

.btn-edit:hover {
    background: #e0a800;
    color: #212529;
    text-decoration: none;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
    color: white;
    text-decoration: none;
}

.customer-profile {
    display: flex;
    align-items: center;
    gap: 20px;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3498db, #2980b9);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.profile-info h1 {
    margin: 0;
    color: #2c3e50;
    font-size: 2rem;
}

.customer-id {
    margin: 5px 0 0 0;
    color: #6c757d;
    font-size: 1rem;
}

/* CONTENT */
.customer-detail-content {
    display: grid;
    gap: 25px;
}

.info-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.card-header {
    background: #f8f9fa;
    padding: 20px 30px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.3rem;
}

.badge-count {
    background: #3498db;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.card-body {
    padding: 30px;
}

/* THÔNG TIN CÁ NHÂN */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-item label {
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95rem;
}

.info-value {
    color: #2c3e50;
    font-size: 1.1rem;
    font-weight: 500;
}

.text-muted {
    color: #6c757d !important;
    font-style: italic;
}

/* BẢNG ĐƠN HÀNG */
.orders-table-container {
    overflow-x: auto;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table th {
    background: #3498db;
    color: white;
    padding: 15px 12px;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
}

.orders-table td {
    padding: 12px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.orders-table tr:hover {
    background: #f8f9fa;
}

.order-id {
    font-weight: 600;
    color: #2c3e50;
    font-family: 'Courier New', monospace;
}

.order-service {
    font-weight: 500;
    color: #2c3e50;
}

.order-date {
    color: #495057;
}

.order-total {
    font-weight: 600;
    color: #27ae60;
    font-family: 'Courier New', monospace;
}

/* TRẠNG THÁI ĐƠN HÀNG */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #cce7ff;
    color: #004085;
}

.status-processing {
    background: #fff3cd;
    color: #856404;
}

.status-completed {
    background: #d4edda;
    color: #155724;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

/* NÚT THAO TÁC */
.order-actions {
    white-space: nowrap;
}

.btn-action {
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: #3498db;
    color: white;
}

.btn-view:hover {
    background: #2980b9;
    transform: translateY(-1px);
    text-decoration: none;
    color: white;
}

/* KHÔNG CÓ ĐƠN HÀNG */
.no-orders {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.no-orders i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

.no-orders p {
    margin-bottom: 20px;
    font-size: 1.1rem;
}

/* THỐNG KÊ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.total-orders {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.completed-orders {
    background: linear-gradient(135deg, #27ae60, #219653);
}

.pending-orders {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.total-revenue {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
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
    font-size: 0.9rem;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .customer-detail-section {
        padding: 20px 0;
    }
    
    .header-actions {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .action-buttons {
        width: 100%;
        justify-content: flex-start;
    }
    
    .customer-profile {
        flex-direction: column;
        text-align: center;
        padding: 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .orders-table {
        min-width: 600px;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .profile-info h1 {
        font-size: 1.5rem;
    }
}
</style>