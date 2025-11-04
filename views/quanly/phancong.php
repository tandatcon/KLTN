<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Phân Công KTV - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/Customer.php';

$orderModel = new Order($db);
$customerModel = new Customer($db);

// Kiểm tra role - chỉ cho phép quản lý (role 4) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 4) {
    header('Location: ' . url('home'));
    exit();
}

// Kiểm tra nếu có order_id thì hiển thị chi tiết, ngược lại hiển thị danh sách
$showDetail = isset($_GET['order_id']) && !empty($_GET['order_id']);

// Lấy tham số lọc
$locationFilter = $_GET['location'] ?? 'all';

// Lấy danh sách đơn hàng chờ phân công (trạng thái 0)
$pendingOrders = $orderModel->getOrdersByStatusAndLocation(1, $locationFilter);

// Hàm helper
function safe_htmlspecialchars($value)
{
    return $value !== null ? htmlspecialchars($value) : '';
}

// Hàm chuyển đổi trạng thái
function getStatusText($status)
{
    $statuses = [
        0 => 'Chờ phân công',
        1 => 'Đã đặt',
        2 => 'Đã nhận',
        3 => 'Hoàn thành',
        4 => 'Đã hủy'
    ];
    return $statuses[$status] ?? 'Không xác định';
}

// Hàm lấy tên khung giờ
function getTimeSlotText($timeSlot)
{
    $slots = [
        'sang' => 'Sáng (8:00-12:00)',
        'chieu' => 'Chiều (13:00-17:00)',
        'toi' => 'Tối (18:00-21:00)'
    ];
    return $slots[$timeSlot] ?? $timeSlot;
}

// Hàm lấy tên nơi sửa chữa
function getRepairLocationText($noiSuaChua)
{
    if ($noiSuaChua === null) {
        return 'Chưa xác định';
    }
    return $noiSuaChua == 1 ? 'Tại cửa hàng' : 'Tại nhà KH';
}

// Hàm lấy badge class cho nơi sửa chữa
function getRepairLocationBadge($noiSuaChua)
{
    if ($noiSuaChua === null) {
        return 'location-unknown';
    }
    return $noiSuaChua == 1 ? 'location-store' : 'location-home';
}
?>

<section class="assignment-section">
    <div class="container">
        <!-- Nút quay lại (chỉ hiển thị khi đang xem chi tiết) -->
        <?php if ($showDetail): ?>
            <div class="navigation-header">
                <button class="btn-back" onclick="window.location.href='<?php echo url('quanly/phancong'); ?>'">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </button>
            </div>
        <?php endif; ?>

        <!-- HEADER -->
        <div class="assignment-header">
            <div class="header-content">
                <h1><i class="fas fa-tasks"></i>
                    <?php echo $showDetail ? 'Phân Công Chi Tiết' : 'Phân Công Kỹ Thuật Viên'; ?>
                </h1>
                <p>
                    <?php echo $showDetail ? 'Phân công KTV cho đơn hàng cụ thể' : 'Quản lý và phân công đơn hàng cho kỹ thuật viên'; ?>
                </p>
            </div>
            <div class="header-stats">
                <div class="stat-card">
                    <i class="fas fa-clock"></i>
                    <div class="stat-info">
                        <h3><?php echo count($pendingOrders); ?></h3>
                        <p>Đơn chờ phân công</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($showDetail): ?>
            <!-- HIỂN THỊ TRANG CHI TIẾT PHÂN CÔNG -->
            <div class="assignment-detail-container">
                <?php
                // Include trang chi tiết phân công
                include __DIR__ . '/chitietphancong.php';
                ?>
            </div>
        <?php else: ?>
            <!-- BỘ LỌC NƠI SỬA CHỮA -->
            <div class="location-filter-section">
                <div class="filter-container">
                    <h4><i class="fas fa-filter"></i> Lọc theo nơi sửa chữa:</h4>
                    <div class="filter-buttons">
                        <a href="?location=all" class="filter-btn <?php echo $locationFilter === 'all' ? 'active' : ''; ?>">
                            <i class="fas fa-globe"></i> Tất cả
                        </a>
                        <a href="?location=1" class="filter-btn <?php echo $locationFilter === '1' ? 'active' : ''; ?>">
                            <i class="fas fa-store"></i> Tại cửa hàng
                        </a>
                        <a href="?location=0" class="filter-btn <?php echo $locationFilter === '0' ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i> Tại nhà KH
                        </a>
                    </div>
                </div>
            </div>

            <!-- DANH SÁCH ĐƠN CHỜ PHÂN CÔNG -->
            <div class="assignment-content">
                <div class="content-card">
                    <div class="card-header">
                        <h3><i class="fas fa-list"></i> Danh sách đơn chờ phân công 
                            <span class="result-count">(<?php echo count($pendingOrders); ?> kết quả)</span>
                        </h3>
                        <div class="card-actions">
                            <button class="btn-refresh" onclick="location.reload()">
                                <i class="fas fa-sync-alt"></i> Làm mới
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <?php if ($pendingOrders): ?>
                            <div class="orders-table-container">
                                <table class="orders-table">
                                    <thead>
                                        <tr>
                                            <th>Mã Đơn</th>
                                            <th>Khách Hàng</th>
                                            <th>Ngày & Giờ Hẹn</th>
                                            <th>Nơi sửa chữa</th>
                                            <th>Số thiết bị/KTV</th>
                                            <th>Trạng Thái</th>
                                            <th>Thao Tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pendingOrders as $order): ?>
                                            <?php
                                            // Lấy thông tin khách hàng
                                            $customer = $customerModel->findByID($order['user_id']);
                                            ?>
                                            <tr>
                                                <td class="order-id">
                                                    <strong>#<?php echo $order['maDon']; ?></strong>
                                                </td>
                                                <td class="customer-info">
                                                    <div class="customer-name">
                                                        <?php echo safe_htmlspecialchars($customer['name'] ?? 'N/A'); ?>
                                                    </div>
                                                    <div class="customer-phone">
                                                        <?php echo safe_htmlspecialchars($customer['phone'] ?? 'Chưa có SĐT'); ?>
                                                    </div>
                                                </td>
                                                <td class="order-date">
                                                    <div class="order-date">
                                                        <?php echo date('d/m/Y', strtotime($order['ngayDat'])); ?>
                                                    </div>
                                                    <div class="order-time">
                                                        <?php echo getTimeSlotText($order['gioDat']); ?>
                                                    </div>
                                                </td>
                                                <td class="repair-location">
                                                    <span class="location-badge <?php echo getRepairLocationBadge($order['noiSuaChua']); ?>">
                                                        <i class="fas <?php echo $order['noiSuaChua'] == 1 ? 'fa-store' : 'fa-home'; ?>"></i>
                                                        <?php echo getRepairLocationText($order['noiSuaChua']); ?>
                                                    </span>
                                                </td>
                                                <td class="order-status">
                                                    <span class="assignment-count">
                                                        <?php
                                                        $soThietBi = $order['tong_so_thiet_bi'] ?? 0;
                                                        $soKTV = $order['so_ktv_da_phan_cong'] ?? 0;
                                                        echo "{$soThietBi}/{$soKTV}";
                                                        ?>
                                                    </span>
                                                </td>
                                                <td class="order-status">
                                                    <span class="status-badge status-pending">
                                                        <?php echo getStatusText($order['trangThai']); ?>
                                                    </span>
                                                </td>
                                                <td class="order-actions">
                                                    <a href="<?php echo url('quanly/phancong'); ?>?order_id=<?php echo $order['maDon']; ?>"
                                                        class="btn-assign">
                                                        <i class="fas fa-user-cog"></i> Phân công
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <h3>Không có đơn hàng chờ phân công</h3>
                                <p>
                                    <?php if ($locationFilter !== 'all'): ?>
                                        Không có đơn hàng <?php echo $locationFilter === '1' ? 'tại cửa hàng' : 'tại nhà KH'; ?> chờ phân công
                                    <?php else: ?>
                                        Tất cả đơn hàng đã được phân công cho KTV
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../footer.php'; ?>

<style>
    .assignment-section {
        padding: 30px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Navigation Header */
    .navigation-header {
        margin-bottom: 20px;
    }

    .btn-back {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
        text-decoration: none;
    }

    /* HEADER */
    .assignment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-content h1 {
        color: #2c3e50;
        margin-bottom: 5px;
        font-size: 2rem;
    }

    .header-content p {
        color: #6c757d;
        margin: 0;
    }

    .header-stats {
        display: flex;
        gap: 20px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 200px;
    }

    .stat-card i {
        font-size: 2rem;
        color: #3498db;
    }

    .stat-info h3 {
        margin: 0;
        color: #2c3e50;
        font-size: 1.8rem;
    }

    .stat-info p {
        margin: 5px 0 0 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* BỘ LỌC NƠI SỬA CHỮA */
    .location-filter-section {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .filter-container h4 {
        margin: 0 0 15px 0;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 10px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        color: #6c757d;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        font-weight: 500;
    }

    .filter-btn:hover {
        border-color: #3498db;
        color: #3498db;
        text-decoration: none;
    }

    .filter-btn.active {
        background: #3498db;
        color: white;
        border-color: #3498db;
    }

    /* CONTENT CARD */
    .content-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
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

    .result-count {
        color: #6c757d;
        font-size: 1rem;
        font-weight: normal;
    }

    .card-actions {
        display: flex;
        gap: 10px;
    }

    .btn-refresh {
        background: #6c757d;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .btn-refresh:hover {
        background: #5a6268;
    }

    .card-body {
        padding: 30px;
    }

    /* TABLE */
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
        padding: 15px 12px;
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

    .customer-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .customer-phone {
        color: #6c757d;
        font-size: 0.85rem;
    }

    .order-date {
        color: #495057;
    }

    .order-time {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 3px;
    }

    /* NƠI SỬA CHỮA */
    .repair-location {
        text-align: center;
    }

    .location-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .location-store {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .location-home {
        background: #cce7ff;
        color: #004085;
        border: 1px solid #b3d7ff;
    }

    .location-unknown {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    /* SỐ THIẾT BỊ/KTV */
    .assignment-count {
        background: #e9ecef;
        color: #495057;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* STATUS BADGE */
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
        border: 1px solid #ffeaa7;
    }

    /* BUTTONS */
    .order-actions {
        display: flex;
        gap: 8px;
        white-space: nowrap;
    }

    .btn-assign {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s;
        background: #27ae60;
        color: white;
        text-decoration: none;
    }

    .btn-assign:hover {
        background: #219653;
        color: white;
        text-decoration: none;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-icon {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        margin-bottom: 10px;
        color: #495057;
    }

    .empty-state p {
        margin: 0;
        font-size: 1.1rem;
    }

    /* ASSIGNMENT DETAIL CONTAINER */
    .assignment-detail-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .filter-buttons {
            flex-direction: column;
        }
        
        .filter-btn {
            justify-content: center;
        }
        
        .card-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        
        .orders-table {
            font-size: 0.9rem;
        }
    }
</style>