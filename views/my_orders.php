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
$technicianInfo = $orderController->getTechnicianInfo($order['maKTV']);

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
?>


<main class="bg-light min-vh-100 py-4">
    <div class="container">
        <!-- Header Section -->
        <div class="card bg-gradient-primary text-white shadow-lg mb-4 border-0">
            <div class="card-body p-4 p-md-5 text-center position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25">
                    <div class="position-absolute" style="top: -50%; right: -50%; width: 100%; height: 200%; 
                         background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px); 
                         background-size: 20px 20px; transform: rotate(30deg);"></div>
                </div>
                <div class="position-relative z-2">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="fas fa-clipboard-list me-3"></i>Đơn Dịch Vụ Của Tôi
                    </h1>
                    <p class="lead mb-4 opacity-75">Quản lý và theo dõi tất cả đơn dịch vụ bạn đã đặt</p>
                    <div class="d-flex justify-content-center flex-wrap gap-3">
                        <div
                            class="d-flex align-items-center bg-black bg-opacity-20 px-3 py-2 rounded-pill backdrop-blur">
                            <i class="fas fa-clock me-2"></i>
                            <span><?php echo count(array_filter($orders, function ($order) {
                                return (int) $order['trangThai'] === 1;
                            })); ?> Đã đặt</span>
                        </div>
                        <div
                            class="d-flex align-items-center bg-black bg-opacity-20 px-3 py-2 rounded-pill backdrop-blur">
                            <i class="fas fa-tasks me-2"></i>
                            <span><?php echo count(array_filter($orders, function ($order) {
                                return (int) $order['trangThai'] === 2;
                            })); ?> Đã nhận</span>
                        </div>
                        <div
                            class="d-flex align-items-center bg-black bg-opacity-20 px-3 py-2 rounded-pill backdrop-blur">
                            <i class="fas fa-check-circle me-2"></i>
                            <span><?php echo count(array_filter($orders, function ($order) {
                                return (int) $order['trangThai'] === 3;
                            })); ?> Hoàn thành</span>
                        </div>
                        <div
                            class="d-flex align-items-center bg-black bg-opacity-20 px-3 py-2 rounded-pill backdrop-blur">
                            <i class="fas fa-times-circle me-2"></i>
                            <span><?php echo count(array_filter($orders, function ($order) {
                                return (int) $order['trangThai'] === 0;
                            })); ?> Đã hủy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông báo -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="row g-4 mb-4">
            <!-- User Info Card -->
            <div class="col-lg-8">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0 me-4">
                            <i class="fas fa-user-circle text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="h4 mb-2 text-dark">
                                <?php echo !empty($userInfo['hoTen']) ? htmlspecialchars($userInfo['hoTen']) : 'Khách hàng'; ?>
                            </h3>
                            <div class="d-flex flex-column gap-2">
                                <span class="text-muted">
                                    <i class="fas fa-phone me-2"></i>
                                    <?php echo !empty($userInfo['sdt']) ? htmlspecialchars($userInfo['sdt']) : '<span class="fst-italic">Chưa có SĐT</span>'; ?>
                                </span>
                                <span class="text-muted">
                                    <i class="fas fa-envelope me-2"></i>
                                    <?php echo !empty($userInfo['email']) ? htmlspecialchars($userInfo['email']) : '<span class="fst-italic">Chưa có email</span>'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-gradient-primary text-white text-center p-3 rounded-3">
                                <i class="fas fa-clipboard-check d-block mb-2" style="font-size: 1.5rem;"></i>
                                <span class="fw-semibold"><?php echo count($orders); ?> Đơn</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="h5 mb-3 text-dark">
                            <i class="fas fa-bolt me-2 text-warning"></i>Thao tác nhanh
                        </h4>
                        <div class="d-grid gap-2">
                            <a href="<?php echo url('datdichvu'); ?>" class="btn btn-success btn-lg">
                                <i class="fas fa-calendar-plus me-2"></i>
                                <span>Đặt dịch vụ mới</span>
                            </a>
                            <a href="<?php echo url('home#contact'); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-headset me-2"></i>
                                <span>Hỗ trợ</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách đơn hàng -->
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <h2 class="h3 mb-0 text-dark">
                        <i class="fas fa-history me-2 text-primary"></i>Lịch Sử Đơn Hàng
                    </h2>
                    <div class="d-flex align-items-center gap-2">
                        <select id="statusFilter" class="form-select" style="width: auto;">
                            <option value="all">Tất cả trạng thái</option>
                            <option value="pending">Đã đặt</option>
                            <option value="processing">Đã nhận</option>
                            <option value="completed">Hoàn thành</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                </div>

                <?php if (empty($orders)): ?>
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-clipboard-list text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="h4 text-muted mb-3">Chưa có đơn dịch vụ nào</h3>
                        <p class="text-muted mb-4">Hãy bắt đầu với dịch vụ sửa chữa đầu tiên của bạn!</p>
                        <a href="<?php echo url('datdichvu'); ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-calendar-plus me-2"></i>Đặt Dịch Vụ Ngay
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($orders as $order): ?>
                            <?php
                            $orderSafe = [
                                'maDon' => $order['maDon'] ?? 'N/A',
                                'ngayDat' => $order['ngayDat'] ?? date('Y-m-d'),
                                'gioDat' => $order['gioDat'] ?? 'sang',
                                'diemhen' => $order['diemhen'] ?? 'Chưa có địa chỉ',
                                'so_luong_thiet_bi' => $order['so_luong_thiet_bi'] ?? 0,
                                'id_nhanvien' => $order['id_nhanvien'] ?? null,
                                'ghiChu' => $order['ghiChu'] ?? null,
                                'danh_sach_thiet_bi' => $order['danh_sach_thiet_bi'] ?? '',
                                'trangThai' => $order['trangThai'] ?? 1,
                                'noiSuaChua' => $order['noiSuaChua'] ?? null,
                                'ktv' => $order['maKTV'] ?? null
                            ];

                            $statusClass = '';
                            $statusText = '';
                            $statusIcon = '';

                            switch ((int) $orderSafe['trangThai']) {
                                case 0:
                                    $statusClass = 'bg-danger text-white';
                                    $statusText = 'Đã hủy';
                                    $statusIcon = 'fas fa-times-circle';
                                    $statusValue = 'cancelled';
                                    break;
                                case 1:
                                    $statusClass = 'bg-warning text-dark';
                                    $statusText = 'Đã đặt';
                                    $statusIcon = 'fas fa-clock';
                                    $statusValue = 'pending';
                                    break;
                                case 2:
                                    $statusClass = 'bg-info text-white';
                                    $statusText = 'Đã nhận';
                                    $statusIcon = 'fas fa-tasks';
                                    $statusValue = 'processing';
                                    break;
                                case 3:
                                    $statusClass = 'bg-success text-white';
                                    $statusText = 'Hoàn thành';
                                    $statusIcon = 'fas fa-check-circle';
                                    $statusValue = 'completed';
                                    break;
                                default:
                                    $statusClass = 'bg-warning text-dark';
                                    $statusText = 'Đã đặt';
                                    $statusIcon = 'fas fa-clock';
                                    $statusValue = 'pending';
                            }
                            ?>

                            <div class="col-12 order-card" data-status="<?php echo $statusValue; ?>">
                                <div class="card h-100 shadow-sm border-start border-1 border-black">
                                    <div
                                        class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div class="d-flex align-items-center gap-3 flex-wrap">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fas fa-hashtag text-primary"></i>
                                                <strong class="text-dark">Đơn
                                                    <?php echo htmlspecialchars($orderSafe['maDon']); ?></strong>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 text-muted">
                                                <i class="fas fa-calendar"></i>
                                                <span><?php echo date('d/m/Y', strtotime($orderSafe['ngayDat'])); ?></span>
                                            </div>
                                        </div>
                                        <span class="badge <?php echo $statusClass; ?> px-3 py-2">
                                            <i class="<?php echo $statusIcon; ?> me-1"></i>
                                            <?php echo $statusText; ?>
                                        </span>
                                    </div>

                                    <div class="card-body">
                                        <!-- Thông tin cơ bản -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start gap-3">
                                                    <i class="fas fa-clock text-primary mt-1"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Khung giờ hẹn</small>
                                                        <strong class="text-dark">
                                                            <?php
                                                            $time_slots = [
                                                                'sang' => 'Sáng (8:00 - 11:00)',
                                                                'chieu' => 'Chiều (13:00 - 17:00)',
                                                                'toi' => 'Tối (18:00 - 21:00)'
                                                            ];
                                                            echo $time_slots[$orderSafe['gioDat']] ?? $orderSafe['gioDat'];
                                                            ?>
                                                        </strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start gap-3">
                                                    <i class="fas fa-map-marker-alt text-primary mt-1"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Địa điểm hẹn</small>
                                                        <strong class="text-dark">
                                                            <?php echo !empty($orderSafe['diemhen']) ? htmlspecialchars($orderSafe['diemhen']) : '<span class="fst-italic text-muted">Chưa có địa chỉ</span>'; ?>
                                                        </strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start gap-3">
                                                    <i class="fas fa-tools text-primary mt-1"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Số thiết bị</small>
                                                        <strong
                                                            class="text-dark"><?php echo (int) $orderSafe['so_luong_thiet_bi']; ?>
                                                            thiết bị</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start gap-3">
                                                    <i class="fas fa-user-cog text-primary mt-1"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Nơi sửa chữa</small>
                                                        <strong class="text-dark">
                                                            <?php if (isset($orderSafe['noiSuaChua'])): ?>
                                                                <?php
                                                                if ($orderSafe['noiSuaChua'] == 0) {
                                                                    echo "🏠 Tại nhà";
                                                                } else if ($orderSafe['noiSuaChua'] == 1) {
                                                                    echo "🏪 Tại cửa hàng";
                                                                } else {
                                                                    echo '<span class="fst-italic text-muted">Chưa xác định</span>';
                                                                }
                                                                ?>
                                                            <?php else: ?>
                                                                <span class="fst-italic text-muted">Chưa có thông tin</span>
                                                            <?php endif; ?>
                                                        </strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start gap-3">
                                                    <i class="fas fa-tools text-primary mt-1"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Trạng thái kỹ thuật viên</small>
                                                        <strong class="text-dark"><?php
                                                        if ($orderSafe['ktv']) {
                                                            echo "Đã tiếp nhận (Xem tại chi tiết)";
                                                        } else {
                                                            echo "Đơn của bạn chưa được tiếp nhận";

                                                        }
                                                        ;
                                                        ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ghi chú -->


                                        <!-- Thiết bị -->
                                        <div class="border-top pt-3">
                                            <h6 class="text-dark mb-3">
                                                <i class="fas fa-laptop-house me-2 text-primary"></i>Thiết bị cần sửa
                                            </h6>
                                            <div class="row g-2">
                                                <?php
                                                $devices = !empty($orderSafe['danh_sach_thiet_bi']) ?
                                                    explode(', ', $orderSafe['danh_sach_thiet_bi']) : [];

                                                if (!empty($devices) && $devices[0] !== ''):
                                                    foreach ($devices as $device):
                                                        $device_name = $deviceNames[$device] ?? $device;
                                                        ?>
                                                        <div class="col-sm-6 col-lg-4">
                                                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                                                <i class="fas fa-wrench text-primary"></i>
                                                                <span
                                                                    class="text-dark"><?php echo htmlspecialchars($device_name); ?></span>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="col-12">
                                                        <span class="fst-italic text-muted">Chưa có thông tin thiết bị</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-transparent">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <?php if ((int) $orderSafe['trangThai'] === 1): ?>
                                                <!-- NÚT HỦY ĐƠN ĐƠN GIẢN -->
                                                <button type="button" class="btn btn-outline-danger cancel-btn"
                                                    onclick="huyDon('<?php echo htmlspecialchars($orderSafe['maDon']); ?>')">
                                                    <i class="fas fa-times me-1"></i>
                                                    Hủy đơn
                                                </button>
                                            <?php endif; ?>
                                            <a href="<?php echo url('my_order_detail?id=' . $orderSafe['maDon']); ?>"
                                                class="btn btn-primary ms-auto">
                                                <i class="fas fa-eye me-1"></i>
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Loading Spinner Modal -->
<div class="modal fade" id="loadingSpinner" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
                <p class="text-muted mb-0">Đang xử lý...</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Minimal custom CSS chỉ cho các hiệu ứng đặc biệt */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .backdrop-blur {
        backdrop-filter: blur(10px);
    }

    .border-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .order-card {
        transition: all 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-3px);
    }

    /* Animation cho filter */
    .order-card {
        opacity: 1;
        transform: translateY(0);
        transition: all 0.6s ease;
    }

    .order-card.hidden {
        opacity: 0;
        transform: translateY(-10px);
        display: none !important;
    }
</style>

<script>
    // HÀM HỦY ĐƠN ĐƠN GIẢN
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

    document.addEventListener('DOMContentLoaded', function () {
        // Filter orders by status
        const statusFilter = document.getElementById('statusFilter');
        const orderCards = document.querySelectorAll('.order-card');

        if (statusFilter) {
            statusFilter.addEventListener('change', function () {
                const filterValue = this.value;

                orderCards.forEach(card => {
                    const cardStatus = card.getAttribute('data-status');

                    if (filterValue === 'all' || cardStatus === filterValue) {
                        card.classList.remove('hidden');
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 50);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            card.classList.add('hidden');
                        }, 300);
                    }
                });

                // Update URL parameter
                const url = new URL(window.location);
                if (filterValue === 'all') {
                    url.searchParams.delete('filter');
                } else {
                    url.searchParams.set('filter', filterValue);
                }
                window.history.replaceState({}, '', url);
            });

            // Check URL for filter parameter on page load
            const urlParams = new URLSearchParams(window.location.search);
            const filterParam = urlParams.get('filter');
            if (filterParam && ['pending', 'processing', 'completed', 'cancelled'].includes(filterParam)) {
                statusFilter.value = filterParam;
                statusFilter.dispatchEvent(new Event('change'));
            }
        }

        // Add intersection observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe order cards for animation
        orderCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>

<?php
include VIEWS_PATH . '/footer.php';
?>