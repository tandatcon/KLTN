<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Bảng Giá Sửa Chữa Thiết Bị - TechCare";

// Include Controller
require_once __DIR__ . '/../controllers/cDevices.php';

// Tạo instance controller và gọi phương thức
$controller = new cDevices();

// Lấy dữ liệu từ controller
$data = $controller->cGetPriceData();

// Extract dữ liệu để sử dụng trong view
$devices = $data['devices'];
$priceList = $data['priceList'];
$deviceId = $data['deviceId'];
$device = $data['device'];

include VIEWS_PATH . '/header.php';
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-primary mb-3">Bảng Giá Sửa Chữa</h1>
            <p class="lead text-muted">Giá minh bạch – Cam kết không phát sinh</p>
        </div>

        <!-- Bộ lọc thiết bị -->
        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <form method="GET" id="filterForm">
                    <div class="row align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Chọn loại thiết bị</label>
                            <select class="form-select form-select-lg" name="device_id" onchange="this.form.submit()">
                                <option value="">Tất cả thiết bị</option>
                                <?php foreach ($devices as $d): ?>
                                    <option value="<?= $d['maThietBi'] ?>" <?= $deviceId == $d['maThietBi'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($d['tenThietBi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Nội dung bảng giá -->
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white py-4">
                <h3 class="mb-0">
                    <?= $device ? htmlspecialchars($device['tenThietBi']) : 'Tất cả thiết bị' ?>
                </h3>
            </div>

            <div class="card-body p-0">
                <?php if (empty($priceList)): ?>
                    <div class="p-5 text-center">
                        <h5 class="text-muted">Chưa có bảng giá cho thiết bị này</h5>
                    </div>
                <?php else: ?>
                    <?php
                    // Nhóm dữ liệu: Thiết bị → Hãng → Mẫu
                    $grouped = [];
                    foreach ($priceList as $item) {
                        $grouped[$item['tenThietBi']][$item['tenHang']][$item['tenMau']][] = $item;
                    }
                    ?>

                    <?php foreach ($grouped as $tenThietBi => $hangList): ?>
                        <?php if ($deviceId && $tenThietBi !== $device['tenThietBi']) continue; ?>

                        <!-- Tên thiết bị (chỉ hiện khi xem tất cả) -->
                        <?php if (!$deviceId): ?>
                            <div class="bg-gradient-primary text-white px-4 py-3 border-bottom">
                                <h4 class="mb-0 fw-bold">
                                    <?= htmlspecialchars($tenThietBi) ?>
                                </h4>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($hangList as $tenHang => $mauList): ?>
                            <!-- Card cho mỗi hãng -->
                            <div class="card m-4 border shadow-sm">
                                <!-- Header hãng -->
                                <div class="card-header bg-light border-bottom">
                                    <h5 class="mb-0 fw-bold text-dark">
                                        <?= htmlspecialchars($tenHang) ?>
                                    </h5>
                                </div>

                                <div class="card-body p-0">
                                    <?php foreach ($mauList as $tenMau => $loiList): ?>
                                        <!-- Mỗi mẫu là một table riêng -->
                                        <div class="border-bottom <?= $tenMau === 'Mẫu khác' ? 'bg-light' : 'bg-white' ?>">
                                            <div class="p-3 border-bottom bg-light">
                                                <h6 class="mb-0 <?= $tenMau === 'Mẫu khác' ? 'text-dark fw-bold' : 'text-primary fw-bold' ?>">
                                                    <?= $tenMau === 'Mẫu khác' ? 'Giá áp dụng chung cho các model máy thấp hơn' : htmlspecialchars($tenMau) ?>
                                                </h6>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover align-middle mb-0">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th width="30%" class="text-center">Lỗi hư hỏng</th>
                                                            <th width="40%" class="text-center">Mô tả chi tiết</th>
                                                            <th width="15%" class="text-center">Thời gian sửa</th>
                                                            <th width="15%" class="text-center">Giá sửa chữa</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($loiList as $index => $loi): ?>
                                                            <tr class="<?= $index % 2 === 0 ? 'table-light' : '' ?>">
                                                                <td class="fw-semibold border-end">
                                                                    <?= htmlspecialchars($loi['tenLoi']) ?>
                                                                </td>
                                                                <td class="border-end">
                                                                    <small class="text-muted">
                                                                        <?= nl2br(htmlspecialchars($loi['moTa'])) ?>
                                                                    </small>
                                                                </td>
                                                                <td class="text-center border-end">
                                                                    <span class="fw-bold text-primary">
                                                                        <?= $loi['thoiGianSua'] ?> phút
                                                                    </span>
                                                                </td>
                                                                <td class="text-center fw-bold">
                                                                    <span class="text-success fs-5">
                                                                        <?= number_format($loi['gia'], 0, ',', '.') ?> ₫
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Thống kê -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <h3 class="text-primary mb-1"><?= count($devices) ?></h3>
                        <p class="text-muted mb-0">Loại thiết bị</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <h3 class="text-success mb-1"><?= count($priceList) ?></h3>
                        <p class="text-muted mb-0">Dịch vụ sửa chữa</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <h3 class="text-warning mb-1">3-12 tháng</h3>
                        <p class="text-muted mb-0">Bảo hành dịch vụ</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ghi chú -->
        <div class="alert alert-info mt-5 border-start border-primary border-5">
            <div>
                <h5 class="alert-heading mb-3">Lưu ý quan trọng</h5>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="mb-3 mb-md-0">
                            <li><strong>Giá cố định:</strong> Đã bao gồm công + vật tư thông thường</li>
                            <li><strong>Mẫu khác:</strong> Giá áp dụng chung cho các model máy thấp hơn</li>
                            <li><strong>Hư hỏng nặng:</strong> Giá có thể thay đổi (thay block, board chính...)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="mb-0">
                            <li><strong>Miễn phí:</strong> Kiểm tra & tư vấn tận nơi nội thành</li>
                            <li><strong>Bảo hành:</strong> Từ 3–12 tháng tùy loại lỗi</li>
                            <li><strong>Cam kết:</strong> Không phát sinh chi phí ngoài báo giá</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include VIEWS_PATH . '/footer.php'; ?>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
}
.border-5 { 
    border-width: 5px !important; 
}
.table th { 
    font-size: 0.95rem;
    font-weight: 600;
}
.table td { 
    vertical-align: middle;
    padding: 12px 8px;
}
.fs-5 { 
    font-size: 1.1rem !important; 
}
.card {
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
}
.card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
.border-end {
    border-right: 2px solid #dee2e6 !important;
}
</style>