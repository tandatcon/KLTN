<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Đơn Dịch Vụ Của Tôi - TechCare";


// Khởi tạo controller
require_once __DIR__ . '/../controllers/OrderController.php';
$orderController = new OrderController($db);
$data = $orderController->showOrders();

// Extract data
$userInfo = $data['userInfo'];
$orders = $data['orders'];
$deviceNames = $data['deviceNames'];
//Huy don
if (isset($_GET['huydon'])) {
    $huy = $orderController->huyDonHang($_GET['huydon']);
    // Sau khi hủy, reload trang để cập nhật trạng thái
    header('Location: ' . url('my_orders'));
    exit();
}
include VIEWS_PATH . '/header.php';
$a = 1;
?>
<button type="button" class="btn btn-outline-danger cancel-btn" onclick="huyDon('<?php echo htmlspecialchars($a); ?>')">
    <i class="fas fa-times me-1"></i>
    Hủy đơn
</button>

<script>
    function huyDon(orderId) {
        // Gọi modal thay vì confirm
        showConfirm(
            'Bạn có chắc chắn muốn hủy đơn hàng #' + orderId + '?',
            'Xác nhận hủy đơn',
            function () {
                // Xác nhận hủy - gọi PHP xử lý
                window.location.href = '<?php echo url("my_orders"); ?>?huydon=' + orderId;
            },
            function () {
                // Hủy bỏ - không làm gì
                console.log('Người dùng đã hủy thao tác');
            }
        );
    }
</script>