<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phân Công Chi Tiết - TechCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    
    </style>
</head>
<body></body>
<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/Customer.php';
require_once __DIR__ . '/../../models/Employee.php';

$orderModel = new Order($db);
$customerModel = new Customer($db);
$employeeModel = new Employee($db);

// Lấy order_id từ URL
$orderId = $_GET['order_id'] ?? '';

if (empty($orderId)) {
    echo '<div class="error">Không tìm thấy đơn hàng</div>';
    exit();
}

// Lấy chi tiết đơn hàng và tên thiết bị
$orderDevices = $orderModel->getOrderByIDorIDNV($orderId);



// Lấy thông tin đơn hàng chính
$orderInfo = $orderModel->getOrderByID($orderId);
$customer = $customerModel->findByID($orderInfo['user_id']);

// Lấy KTV có lịch trống
$availableKTVs = $employeeModel->findAvailableKTV($orderInfo['ngayDat'], $orderInfo['gioDat']);

// Debug: Kiểm tra dữ liệu
error_log("Order Date: " . $orderInfo['ngayDat']);
error_log("Time Slot: " . $orderInfo['gioDat']);
error_log("Available KTVs: " . count($availableKTVs));

// Hàm helper


// Xử lý phân công KTV ngẫu nhiên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_random'])) {
    $deviceId = $_POST['device_id'];
    
    error_log("Phân công ngẫu nhiên: deviceId=$deviceId"); // Debug
    
    // Chọn ngẫu nhiên 1 KTV từ danh sách có sẵn
    if (!empty($availableKTVs)) {
        $randomKTV = $availableKTVs[array_rand($availableKTVs)];
        $ktvId = $randomKTV['id'];
        $ktvName = $randomKTV['name'];
        
        error_log("KTV ngẫu nhiên: id=$ktvId, name=$ktvName"); // Debug
        
        // Thực hiện lưu phân công vào database
        $kq = $orderModel->PCByKTV($ktvId,$deviceId );
        
        if ($kq) {
            echo "<div class='success-message'>Đã phân công ngẫu nhiên KTV <strong>{$ktvName}</strong> cho thiết bị thành công!</div>";
        } else {
            echo '<div class="error-message">Lỗi khi phân công KTV ngẫu nhiên!</div>';
        }
    } else {
        echo '<div class="error-message">Không có KTV nào khả dụng!</div>';
    }
}

// Xử lý phân công KTV cụ thể
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_specific'])) {
    $deviceId = $_POST['device_id'];
    $ktvId = $_POST['ktv_id'];
    
    error_log("Phân công cụ thể: deviceId=$deviceId, ktvId=$ktvId"); // Debug
    
    // Thực hiện lưu phân công vào database
    $kq = $orderModel->PCByKTV($ktvId,$deviceId );
    
    if ($kq) {
        echo '<div class="success-message">Đã phân công KTV thành công!</div>';
    } else {
        echo '<div class="error-message">Lỗi khi phân công KTV!</div>';
    }
}
?>

<!-- Phần HTML giữ nguyên -->
<div class="assignment-detail-page">
    <!-- HEADER -->
    <div class="detail-header">
        <button class="btn-back" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Quay lại
        </button>
        <h2>Phân công KTV - Đơn #<?php echo $orderId; ?> Sửa chữa
        <?php if ($orderInfo['noiSuaChua'] == 0) {
        echo "Tại nhà khách";
            } else {
        echo "Tại cửa hàng";
        }
        ?> </h2>
    </div>

    

    <!-- DANH SÁCH THIẾT BỊ -->
    <div class="devices-section">
        <h3><i class="fas fa-tools"></i> Danh sách thiết bị</h3>
        
        <div class="devices-table-container">
            <table class="devices-table">
                <thead>
                    <tr>
                        <th>Mã CTĐH</th>
                        <th>Tên thiết bị</th>
                        <th>Mô tả tình trạng</th>
                        <th>Phân công KTV</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderDevices as $device): ?>
                        
                        
                    <tr>
                        <td class="device-id">#<?php echo $device['maCTDon']; ?></td>
                        <td class="device-name">
                            <strong><?php echo safe_htmlspecialchars($device['tenThietBi']); ?></strong>
                            <div class="device-type"><?php echo 'Mã thiết bị: '. safe_htmlspecialchars($device['loai_thietbi']); ?></div>
                        </td>
                        <td class="device-description">
                            <?php echo safe_htmlspecialchars($device['mota_tinhtrang']); ?>
                        </td>
                        <td class="assignment-actions">
                            <!-- FORM PHÂN CÔNG NGẪU NHIÊN -->
                            <form method="POST" class="assignment-form random-form">
                                <input type="hidden" name="device_id" value="<?php echo $device['maCTDon']; ?>">
                                <button type="submit" name="assign_random" class="btn-random" 
                                        title="Phân công ngẫu nhiên">
                                    <i class="fas fa-random"></i> Ngẫu nhiên
                                </button>
                            </form>
                            
                            <!-- FORM CHỌN KTV CỤ THỂ -->
                            <form method="POST" class="assignment-form specific-form">
                                <input type="hidden" name="device_id" value="<?php echo $device['maCTDon']; ?>">
                                <div class="ktv-selection">
                                    <select name="ktv_id" class="form-select" required>
                                        <option value="">-- Chọn KTV --</option>
                                        <?php foreach ($availableKTVs as $ktv): ?>
                                            <option value="<?php echo $ktv['maND']; ?>">
                                                <?php echo safe_htmlspecialchars($ktv['hoTen']) .  ' + Số ngày làm việc trong tháng: '.$ktv['so_ngay_lam_viec']; ?> 
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="assign_specific" class="btn-specific">
                                        <i class="fas fa-check"></i> Phân công
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- THÔNG TIN KTV CÓ SẴN -->
    <!-- <?php if (!empty($availableKTVs)): ?>
    <div class="ktv-info-section">
        <h3><i class="fas fa-users"></i> KTV có lịch trống (<?php echo count($availableKTVs); ?> người)</h3>
        <div class="ktv-grid">
            <?php foreach ($availableKTVs as $ktv): ?>
            <div class="ktv-card">
                <div class="ktv-name"><?php echo safe_htmlspecialchars($ktv['hoTen']); ?></div>
                <div class="ktv-phone"><?php echo safe_htmlspecialchars($ktv['sdt']); ?></div>
                <div class="ktv-specialty"><?php echo safe_htmlspecialchars($ktv['chuyenMon']); ?></div>
                <div class="ktv-rating">
                    <i class="fas fa-star"></i>
                    <?php echo safe_htmlspecialchars($ktv['danhGia']); ?>/5
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?> -->
</div>

<style>
.assignment-detail-page {
    padding: 20px;
}

.detail-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.detail-header h2 {
    color: #2c3e50;
    margin: 0;
    font-size: 1.5rem;
}

.btn-back {
    background: #6c757d;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-back:hover {
    background: #5a6268;
}

/* ORDER INFO */
.order-info-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 10px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
}

.info-item label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.info-item span {
    color: #2c3e50;
}

/* DEVICES TABLE */
.devices-section h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 1.2rem;
}

.devices-table-container {
    overflow-x: auto;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.devices-table {
    width: 100%;
    border-collapse: collapse;
}

.devices-table th {
    background: #3498db;
    color: white;
    padding: 12px 10px;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
}

.devices-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: top;
}

.devices-table tr:hover {
    background: #f8f9fa;
}

.device-id {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #2c3e50;
}

.device-name strong {
    color: #2c3e50;
    display: block;
    margin-bottom: 3px;
}

.device-type {
    color: #6c757d;
    font-size: 0.8rem;
    background: #e9ecef;
    padding: 2px 6px;
    border-radius: 4px;
    display: inline-block;
}

.device-description {
    color: #495057;
    font-size: 0.9rem;
    line-height: 1.4;
}

/* ASSIGNMENT ACTIONS */
.assignment-actions {
    min-width: 200px;
}

.assignment-form {
    margin-bottom: 8px;
}

.assignment-form:last-child {
    margin-bottom: 0;
}

.random-form {
    text-align: center;
}

.btn-random {
    background: #f39c12;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 5px;
    width: 100%;
    justify-content: center;
    transition: all 0.3s;
}

.btn-random:hover {
    background: #e67e22;
}

.ktv-selection {
    display: flex;
    gap: 5px;
}

.form-select {
    flex: 1;
    padding: 6px 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.8rem;
}

.btn-specific {
    background: #27ae60;
    color: white;
    border: none;
    padding: 6px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 3px;
    transition: all 0.3s;
}

.btn-specific:hover {
    background: #219653;
}

/* KTV INFO */
.ktv-info-section {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 2px solid #e9ecef;
}

.ktv-info-section h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 1.2rem;
}

.ktv-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

.ktv-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.ktv-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 3px;
}

.ktv-phone {
    color: #6c757d;
    font-size: 0.8rem;
    margin-bottom: 3px;
}

.ktv-specialty {
    color: #3498db;
    font-size: 0.8rem;
    font-weight: 500;
}

.ktv-rating {
    color: #f39c12;
    font-size: 0.8rem;
    margin-top: 5px;
}

/* MESSAGES */
.success-message {
    background: #d4edda;
    color: #155724;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #c3e6cb;
    margin-bottom: 15px;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #f5c6cb;
    margin-bottom: 15px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .devices-table {
        min-width: 600px;
    }
    
    .ktv-selection {
        flex-direction: column;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function hideAssignmentPage() {
    // Hàm này sẽ được định nghĩa trong trang chính
    if (typeof window.parent.hideAssignmentPage === 'function') {
        window.parent.hideAssignmentPage();
    }
}
</script>