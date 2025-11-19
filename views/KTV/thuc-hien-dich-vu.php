<?php
ob_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Thực Hiện Dịch Vụ - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../function/quytrinh.php';
require_once __DIR__ . '/../../function/donhang.php';

$orderController = new DonHangService($db);
$orderModel = new Order($db);
$serviceProcessModel = new QuyTrinhService($db);

// Kiểm tra role - chỉ cho phép KTV (role 3) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header('Location: ' . url('home'));
    exit();
}

$maKTV = $_SESSION['user_id'];
$maDon = $_GET['id'] ?? 0;

// Validate maDon
if (!$maDon || !is_numeric($maDon)) {
    echo "<script>alert('ID đơn hàng không hợp lệ!'); window.location.href = '" . url('KTV/donPhanCong') . "';</script>";
    exit();
}

// Lấy thông tin đơn hàng
$data = $orderController->layChiTietDonChoKTV($maDon, $maKTV);

if (!$data) {
    echo "<script>alert('Đơn hàng không tồn tại hoặc bạn không có quyền truy cập!'); window.location.href = '" . url('KTV/donPhanCong') . "';</script>";
    exit();
}

// Extract data
$donHang = $data['donHang'];
$chiTietDonDichVu = $data['chiTietDonDichVu'];
$thongTinKhachHang = $data['thongTinKhachHang'];

// Lấy thông tin các công việc đã lưu
$tatCaCongViec = [];
$tongBaoGia = 0;
foreach ($chiTietDonDichVu as $ctdd) {
    $congViecThietBi = $serviceProcessModel->getDeviceRepairDetails($maDon, $ctdd['maCTDon']);
    $tatCaCongViec = array_merge($tatCaCongViec, $congViecThietBi);

    foreach ($congViecThietBi as $congViec) {
        $tongBaoGia += $congViec['chiPhi'];
    }
}
?>

<!-- PHẦN HTML -->
<section class="py-3">
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h4 mb-1">
                            <i class="fas fa-tools text-primary me-2"></i>
                            Thực Hiện Dịch Vụ
                        </h1>
                        <p class="text-muted mb-0">
                            Mã đơn: <strong>#<?php echo $maDon; ?></strong> |
                            Khách hàng: <strong><?php echo htmlspecialchars($thongTinKhachHang['hoTen']); ?></strong>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end gap-2">
                            <a href="<?php echo url('KTV/donPhanCong'); ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- THÔNG BÁO AJAX -->
        <div id="ajax-alert-container"></div>

        <!-- THÔNG TIN ĐƠN HÀNG -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông Tin Đơn Hàng</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Khách hàng:</strong></td>
                                <td><?php echo htmlspecialchars($thongTinKhachHang['hoTen']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>SĐT:</strong></td>
                                <td><?php echo htmlspecialchars($thongTinKhachHang['sdt']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td> <?php if ($thongTinKhachHang['email']) {
                                    echo htmlspecialchars($thongTinKhachHang['email']);
                                } else {
                                    echo 'Chưa có thông tin!';
                                } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Địa chỉ:</strong></td>
                                <td><?php echo htmlspecialchars($donHang['diemhen']); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Ngày hẹn:</strong></td>
                                <td><?php echo date('d/m/Y', strtotime($donHang['ngayDat'])); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Khung giờ:</strong></td>
                                <td>
                                    <?php
                                    $khungGio = [
                                        '1' => '8:00 - 10:00 giờ',
                                        '2' => '10:00 - 12:00 giờ',
                                        '3' => '12:00 - 14:00 giờ',
                                        '4' => '14:00 - 16:00 giờ',
                                        '5' => '16:00 - 18:00 giờ'
                                    ];
                                    echo $khungGio[$donHang['maKhungGio']] ?? $donHang['maKhungGio'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Nơi sửa chữa:</strong></td>
                                <td><?php echo $donHang['noiSuaChua'] == 0 ? "🏠 Tại nhà" : "🏪 Tại cửa hàng"; ?></td>
                            </tr>
                            <!-- Trong phần thông tin đơn hàng -->
                            <tr>
                                <td><strong>Trạng thái:</strong></td>
                                <td>
                                    <span class="badge bg-<?php
                                    echo $donHang['trangThai'] == 1 ? 'warning' :
                                        ($donHang['trangThai'] == 2 ? 'info' :
                                            ($donHang['trangThai'] == 3 ? 'primary' : 'success'));
                                    ?>">
                                        <?php
                                        echo $donHang['trangThai'] == 1 ? 'Đã tiếp nhận' :
                                            ($donHang['trangThai'] == 2 ? 'Đang sửa chữa' :
                                                ($donHang['trangThai'] == 3 ? 'Đang thực hiện' : 'Hoàn thành'));
                                        ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TỔNG BÁO GIÁ -->
        <?php if ($tongBaoGia > 0): ?>
            <div class="card mb-4">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center text-md-start">
                            <strong class="fs-5">Tổng chi phí sửa chữa: <?php echo number_format($tongBaoGia); ?>
                                VND</strong>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <?php if ($donHang['trangThai'] == 4): ?>
                                <div class="d-flex justify-content-center justify-content-md-end gap-2 flex-wrap">
                                    <!-- Nút In Hóa Đơn -->
                                    <button type="button" class="btn btn-outline-primary btn-print-invoice"
                                        onclick="printInvoice()">
                                        <i class="fas fa-print me-2"></i>In Hóa Đơn
                                    </button>

                                    <!-- Nút Thanh Toán -->
                                    <button type="button" class="btn btn-success btn-payment" onclick="processPayment()">
                                        <i class="fas fa-credit-card me-2"></i>Thanh Toán
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- DANH SÁCH THIẾT BỊ CẦN SỬA -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh Sách Thiết Bị Cần Sửa</h5>
            </div>
            <div class="card-body">
                <?php foreach ($chiTietDonDichVu as $index => $ctdd): ?>
                    <?php
                    $chanDoanThietBi = $serviceProcessModel->getDeviceDiagnosis($maDon, $ctdd['maCTDon']);
                    $chiTietSuaChuaThietBi = $serviceProcessModel->getDeviceRepairDetails($maDon, $ctdd['maCTDon']);
                    $chiTietGia = $serviceProcessModel->getPriceDetail($ctdd['maThietBi']);
                    $minhChungThietBi = $serviceProcessModel->getEvidenceImages($maDon, $ctdd['maCTDon']);
                    $daCoMinhChungDen = !empty($minhChungThietBi['minhchung_den']);
                    $daCoMinhChungThietBi = !empty($minhChungThietBi['minhchung_thietbi']);
                    $daUploadHoanThanh = !empty($minhChungThietBi["minhchunghoanthanh"]);

                    $trangThaiThietBi = $ctdd['trangThai'] ?? 1;
                    $gioBatDau = $ctdd['gioBatDau'] ?? null;
                    $gioKetThuc = $ctdd['gioKetThuc'] ?? null;
                    $quyetDinhSC = $ctdd['quyetDinhSC'] ?? null;
                    ?>

                    <div class="card mb-4 border device-card" data-mactdon="<?php echo $ctdd['maCTDon']; ?>">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-cog me-2"></i>
                                    Thiết bị <?php echo $index + 1; ?>: <?php echo htmlspecialchars($ctdd['tenThietBi']); ?>
                                    <span class="badge bg-secondary ms-2">Mã CTĐơn: <?php echo $ctdd['maCTDon']; ?></span>
                                </h6>
                                <div class="device-status">
                                    <?php if ($quyetDinhSC == 1 && $trangThaiThietBi == 1): ?>
                                        <span class="badge bg-warning">Chờ bắt đầu</span>
                                    <?php elseif ($trangThaiThietBi == 2): ?>
                                        <span class="badge bg-info">Đang sửa chữa</span>
                                    <?php elseif ($trangThaiThietBi == 3): ?>
                                        <span class="badge bg-success">Đã hoàn thành</span>
                                    <?php elseif ($trangThaiThietBi == 4): ?>
                                        <span class="badge bg-danger">Đã bị hủy</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Chưa chẩn đoán</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            <!-- THÔNG TIN THỜI GIAN -->
                            <div class="row mb-3">
                                <div class="col-12 col-md-4 mb-2">
                                    <strong>Mã CTĐơn:</strong>
                                    <span><?php echo $ctdd['maCTDon']; ?></span>
                                </div>
                                <div class="col-12 col-md-4 mb-2">
                                    <strong>Giờ bắt đầu:</strong>
                                    <span id="gioBatDau_<?php echo $ctdd['maCTDon']; ?>">
                                        <?php echo $gioBatDau ? date('H:i d/m/Y', strtotime($gioBatDau)) : 'Chưa bắt đầu'; ?>
                                    </span>
                                </div>
                                <div class="col-12 col-md-4 mb-2">
                                    <strong>Giờ kết thúc:</strong>
                                    <span id="gioKetThuc_<?php echo $ctdd['maCTDon']; ?>">
                                        <?php echo $gioKetThuc ? date('H:i d/m/Y', strtotime($gioKetThuc)) : 'Chưa kết thúc'; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-4 mb-2">
                                    <strong>Tên thiết bị:</strong>
                                    <?php echo htmlspecialchars($ctdd['tenThietBi']); ?>
                                </div>
                                <div class="col-12 col-md-4 mb-2">
                                    <strong>Trạng thái:</strong>
                                    <span class="badge bg-<?php
                                    echo $trangThaiThietBi == 1 ? 'warning' :
                                        ($trangThaiThietBi == 2 ? 'info' : 'success');
                                    ?>" id="trangThaiThietBi_<?php echo $ctdd['maCTDon']; ?>">
                                        <?php echo $trangThaiThietBi == 1 ? 'Chờ bắt đầu' :
                                            ($trangThaiThietBi == 2 ? 'Đang sửa' : 'Hoàn thành'); ?>
                                    </span>
                                </div>
                            </div>

                            <!-- THÔNG TIN MÔ TẢ TÌNH TRẠNG -->
                            <?php if (!empty($ctdd['mota_tinhtrang'])): ?>
                                <div class="mb-3">
                                    <strong>Mô tả tình trạng (Bên khách hàng):</strong>
                                    <p class="mb-0"><?php echo htmlspecialchars($ctdd['mota_tinhtrang']); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- MINH CHỨNG CHO TỪNG THIẾT BỊ -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-camera me-2"></i>Minh Chứng
                                </h6>

                                <div class="row">
                                    <!-- MINH CHỨNG ĐẾN NHÀ -->
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-2 h-100">
                                            <h6 class="text-info mb-2 d-flex justify-content-between align-items-center">
                                                <span>
                                                    <i class="fas fa-home me-2"></i>Đến nhà
                                                </span>
                                                <?php if ($daCoMinhChungDen): ?>
                                                    <span class="badge bg-success">Đã upload</span>
                                                <?php endif; ?>
                                            </h6>

                                            <?php if ($daCoMinhChungDen): ?>
                                                <!-- HIỂN THỊ ẢNH ĐÃ UPLOAD -->
                                                <div class="text-center">
                                                    <img src="<?php echo url('assets/images/' . $minhChungThietBi['minhchung_den']); ?>"
                                                        class="img-fluid rounded cursor-pointer evidence-image"
                                                        style="max-height: 120px; cursor: pointer;" data-bs-toggle="modal"
                                                        data-bs-target="#imageModal"
                                                        data-image-src="<?php echo url('assets/images/' . $minhChungThietBi['minhchung_den']); ?>"
                                                        onerror="this.src='<?php echo url('assets/images/no-image.jpg'); ?>'"
                                                        alt="Minh chứng đến nhà">
                                                    <div class="mt-1">
                                                        <small class="text-muted">Click để phóng to</small>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <!-- FORM UPLOAD MỚI ĐƠN GIẢN -->
                                                <form method="POST" enctype="multipart/form-data" class="ajax-upload-form">
                                                    <input type="hidden" name="ctdon_id"
                                                        value="<?php echo $ctdd['maCTDon']; ?>">
                                                    <input type="hidden" name="evidence_type" value="arrival">

                                                    <div class="upload-area-simple"
                                                        id="uploadAreaArrival_<?php echo $ctdd['maCTDon']; ?>">
                                                        <div class="upload-icon">
                                                            <i class="fas fa-cloud-upload-alt"></i>
                                                        </div>
                                                        <div class="upload-text-simple">Chọn hình ảnh</div>
                                                        <div class="upload-subtext-simple">PNG, JPG, GIF up to 5MB</div>
                                                    </div>

                                                    <input type="file" id="fileInputArrival_<?php echo $ctdd['maCTDon']; ?>"
                                                        name="evidence_image" accept="image/*" style="display: none;">

                                                    <div class="preview-container-simple"
                                                        id="previewContainerArrival_<?php echo $ctdd['maCTDon']; ?>"
                                                        style="display: none;">
                                                        <div class="preview-title-simple">Preview:</div>
                                                        <img id="previewImageArrival_<?php echo $ctdd['maCTDon']; ?>"
                                                            class="preview-image-simple" src="" alt="Preview">
                                                        <div class="preview-actions">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                                id="changeBtnArrival_<?php echo $ctdd['maCTDon']; ?>">
                                                                <i class="fas fa-redo me-1"></i>Change
                                                            </button>
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                onclick="uploadEvidence('<?php echo $ctdd['maCTDon']; ?>', 'arrival')">
                                                                <i class="fas fa-upload me-1"></i>Upload
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- MINH CHỨNG THIẾT BỊ -->
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-2 h-100">
                                            <h6 class="text-info mb-2 d-flex justify-content-between align-items-center">
                                                <span>
                                                    <i class="fas fa-laptop me-2"></i>Thiết bị
                                                </span>
                                                <?php if ($daCoMinhChungThietBi): ?>
                                                    <span class="badge bg-success">Đã upload</span>
                                                <?php endif; ?>
                                            </h6>

                                            <?php if ($daCoMinhChungThietBi): ?>
                                                <!-- HIỂN THỊ ẢNH ĐÃ UPLOAD -->
                                                <div class="text-center">
                                                    <img src="<?php echo url('assets/images/' . $minhChungThietBi['minhchung_thietbi']); ?>"
                                                        class="img-fluid rounded cursor-pointer evidence-image"
                                                        style="max-height: 120px; cursor: pointer;" data-bs-toggle="modal"
                                                        data-bs-target="#imageModal"
                                                        data-image-src="<?php echo url('assets/images/' . $minhChungThietBi['minhchung_thietbi']); ?>"
                                                        onerror="this.src='<?php echo url('assets/images/no-image.jpg'); ?>'"
                                                        alt="Minh chứng thiết bị">
                                                    <div class="mt-1">
                                                        <small class="text-muted">Click để phóng to</small>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <!-- FORM UPLOAD MỚI ĐƠN GIẢN -->
                                                <form method="POST" enctype="multipart/form-data" class="ajax-upload-form">
                                                    <input type="hidden" name="ctdon_id"
                                                        value="<?php echo $ctdd['maCTDon']; ?>">
                                                    <input type="hidden" name="evidence_type" value="device">

                                                    <div class="upload-area-simple"
                                                        id="uploadAreaDevice_<?php echo $ctdd['maCTDon']; ?>">
                                                        <div class="upload-icon">
                                                            <i class="fas fa-cloud-upload-alt"></i>
                                                        </div>
                                                        <div class="upload-text-simple">Chọn hình ảnh</div>
                                                        <div class="upload-subtext-simple">PNG, JPG, GIF up to 5MB</div>
                                                    </div>

                                                    <input type="file" id="fileInputDevice_<?php echo $ctdd['maCTDon']; ?>"
                                                        name="evidence_image" accept="image/*" style="display: none;">

                                                    <div class="preview-container-simple"
                                                        id="previewContainerDevice_<?php echo $ctdd['maCTDon']; ?>"
                                                        style="display: none;">
                                                        <div class="preview-title-simple">Preview:</div>
                                                        <img id="previewImageDevice_<?php echo $ctdd['maCTDon']; ?>"
                                                            class="preview-image-simple" src="" alt="Preview">
                                                        <div class="preview-actions">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                                id="changeBtnDevice_<?php echo $ctdd['maCTDon']; ?>">
                                                                <i class="fas fa-redo me-1"></i>Change
                                                            </button>
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                onclick="uploadEvidence('<?php echo $ctdd['maCTDon']; ?>', 'device')">
                                                                <i class="fas fa-upload me-1"></i>Upload
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CHẨN ĐOÁN & BÁO GIÁ -->
                            <?php if (!$chanDoanThietBi): ?>
                                <!-- FORM CHẨN ĐOÁN (Sẽ được cập nhật bằng AJAX) -->
                                <div id="diagnosis-form-<?php echo $ctdd['maCTDon']; ?>">
                                    <?php include 'partials/diagnosis_form.php'; ?>
                                </div>
                            <?php else: ?>
                                <!-- HIỂN THỊ SAU KHI ĐÃ CHẨN ĐOÁN -->
                                <div id="diagnosis-info-<?php echo $ctdd['maCTDon']; ?>">
                                    <div class="card border-info mb-4">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0"><i class="fas fa-stethoscope me-2"></i>Chẩn Đoán & Báo Giá</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Chẩn đoán:</strong>
                                                    <p class="text-muted">
                                                        <?php echo htmlspecialchars($chanDoanThietBi['tinh_trang_thuc_te']); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Báo giá dự kiến:</strong>
                                                    <p class="text-success fw-bold">
                                                        <?php echo number_format($chanDoanThietBi['chi_phi']); ?> VND
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- QUYẾT ĐỊNH KHÁCH HÀNG -->
                                    <?php if ($quyetDinhSC): ?>
                                        <div class="alert alert-info mt-3">
                                            <strong>Quyết định khách hàng:</strong>
                                            <p class="mb-0">
                                                <?php if ($quyetDinhSC == 1): ?>
                                                    <span class="text-success"><i class="fas fa-check me-1"></i>Đã đồng ý sửa
                                                        chữa</span>
                                                <?php elseif ($quyetDinhSC == 2): ?>
                                                    <span class="text-danger"><i class="fas fa-times me-1"></i>Không đồng ý sửa
                                                        chữa</span>
                                                    <?php if (!empty($ctdd['lyDoHuy'])): ?>
                                                        <br><strong>Lý do:</strong> <?php echo htmlspecialchars($ctdd['lyDoHuy']); ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>



                                    <!-- DANH SÁCH CÔNG VIỆC SỬA CHỮA -->
                                    <?php if (!empty($chiTietSuaChuaThietBi)): ?>
                                        <div class="card border-success mb-4">
                                            <div class="card-header bg-success text-white py-2">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-list-check me-2"></i>Danh Sách Công Việc Sửa Chữa
                                                </h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th width="10%">STT</th>
                                                                <th width="55%">Công việc</th>
                                                                <th width="20%">Chi phí (VND)</th>
                                                                <th width="15%">Loại</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $tongThietBi = 0;
                                                            if (!empty($chiTietSuaChuaThietBi)) {
                                                                foreach ($chiTietSuaChuaThietBi as $idx => $chiTiet):
                                                                    $tongThietBi += $chiTiet['chiPhi'];
                                                                    ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo $idx + 1; ?></td>
                                                                        <td><?php echo htmlspecialchars($chiTiet['loiSuaChua']); ?></td>
                                                                        <td class="text-end">
                                                                            <?php echo number_format($chiTiet['chiPhi']); ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo $chiTiet['loai']; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach;
                                                            } ?>
                                                        </tbody>
                                                        <?php if (!empty($chiTietSuaChuaThietBi)): ?>
                                                            <tfoot>
                                                                <tr class="table-secondary">
                                                                    <td colspan="2" class="text-end fw-bold">Tổng cộng:</td>
                                                                    <td class="text-end fw-bold">
                                                                        <?php echo number_format($tongThietBi); ?>
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                            </tfoot>
                                                        <?php endif; ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <!-- THÊM CÔNG VIỆC PHÁT SINH (Nếu đang sửa chữa) -->
                                    <?php if ($quyetDinhSC == 1 && $trangThaiThietBi == 2): ?>
                                        <div id="additional-jobs-<?php echo $ctdd['maCTDon']; ?>">
                                            <?php include 'partials/additional_jobs_form.php'; ?>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Nút bắt đầu vaf kết thúc -->
                                    <?php if ($quyetDinhSC == 1): ?>
                                        <div id="service-buttons-<?php echo $ctdd['maCTDon']; ?>" align="center">
                                            <?php if ($trangThaiThietBi == 1): ?>
                                                <button type="button" class="btn btn-action-large btn-start"
                                                    onclick="handleServiceAction('start_service', '<?php echo $ctdd['maCTDon']; ?>', '<?php echo htmlspecialchars($ctdd['tenThietBi']); ?>')">
                                                    <i class="fas fa-play-circle me-2"></i>Bắt Đầu Sửa Chữa
                                                </button>
                                            <?php elseif ($trangThaiThietBi == 2): ?>
                                                <button type="button" class="btn btn-action-large btn-stop "
                                                    onclick="handleServiceAction('complete_service', '<?php echo $ctdd['maCTDon']; ?>', '<?php echo htmlspecialchars($ctdd['tenThietBi']); ?>')">
                                                    <i class="fas fa-stop-circle me-2"></i>Kết Thúc Sửa Chữa
                                                </button>
                                            <?php elseif ($trangThaiThietBi == 3): ?>
                                                <div class="container">
                                                    <div class="row justify-content-center">
                                                        <div class="col-12 col-md-6 col-lg-5">
                                                            <div class="alert alert-success mb-0 text-center">
                                                                <i class="fas fa-check-circle me-2"></i>
                                                                <strong>Đã hoàn thành sửa chữa</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div id="end-page">

    </div>
</section>

<!-- MODAL PHÓNG TO ẢNH -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xem ảnh minh chứng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Ảnh minh chứng">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<!-- Nút cuộn lên đầu trang và cuối trang -->
<button class="btn btn-primary position-fixed rounded-circle p-2 scroll-btn" id="scrollToTop"
    style="bottom: 80px; right: 20px; z-index: 1050; display: none; width: 50px; height: 50px;">
    <i class="fas fa-arrow-up"></i>
</button>

<button class="btn btn-success position-fixed rounded-circle p-2 scroll-btn" id="scrollToBottom"
    style="bottom: 20px; right: 20px; z-index: 1050; width: 50px; height: 50px;">
    <i class="fas fa-arrow-down"></i>
</button>

<script>
    // Cuộn lên đầu trang
    document.getElementById('scrollToTop').addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Cuộn xuống cuối trang
    document.getElementById('scrollToBottom').addEventListener('click', function () {
        window.scrollTo({
            top: document.body.scrollHeight - window.innerHeight - 200,
            behavior: 'smooth'
        });
    });

    // Hiển thị nút cuộn lên khi cuộn xuống
    window.addEventListener('scroll', function () {
        const scrollToTopBtn = document.getElementById('scrollToTop');
        if (window.pageYOffset > 300) {
            scrollToTopBtn.style.display = 'block';
        } else {
            scrollToTopBtn.style.display = 'none';
        }
    });
</script>

<style>
    .scroll-btn {
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .scroll-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }
</style>
<?php
ob_end_flush();
include __DIR__ . '/../footer.php';
?>

<!-- STYLES -->
<style>
    .upload-area-simple {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 30px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background-color: #f8f9fa;
    }

    .upload-area-simple:hover {
        border-color: #3498db;
        background-color: #e8f4fc;
    }

    .upload-icon {
        font-size: 48px;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .upload-text-simple {
        font-size: 16px;
        color: #495057;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .upload-subtext-simple {
        color: #6c757d;
        font-size: 12px;
    }

    .preview-container-simple {
        margin-top: 15px;
        text-align: center;
    }

    .preview-image-simple {
        max-width: 100%;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
        max-height: 200px;
        border: 1px solid #dee2e6;
    }

    .preview-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
    }

    .service-action-buttons {
        padding: 20px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        margin: 20px 0;
        border: 2px dashed #dee2e6;
    }

    .btn-action-large {
        padding: 15px 30px;
        font-size: 18px;
        font-weight: 600;
        border-radius: 10px;
        min-width: 200px;
        margin: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .btn-action-large:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-start {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
    }

    .btn-stop {
        background: linear-gradient(135deg, #dc3545 0%, #e35d6a 100%);
        border: none;
        color: white;
    }

    .device-card {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 25px;
    }

    .device-card:hover {
        border-color: #3498db;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    }

    .btn-loading {
        position: relative;
        color: transparent !important;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin-left: -10px;
        margin-top: -10px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 576px) {
        .btn-action-large {
            padding: 12px 20px;
            font-size: 16px;
            min-width: 160px;
            margin: 5px;
        }
    }
</style>

<!-- JAVASCRIPT -->
<script>
    // Biến toàn cục
    let danhSachCongViec = {};
    let danhSachCongViecPhatSinh = {};

    // Khởi tạo mảng công việc cho từng thiết bị
    <?php foreach ($chiTietDonDichVu as $ctdd): ?>
        danhSachCongViec['<?php echo $ctdd['maCTDon']; ?>'] = [];
        danhSachCongViecPhatSinh['<?php echo $ctdd['maCTDon']; ?>'] = [];
    <?php endforeach; ?>

    // Hàm xử lý lỗi
    function handleError(error) {
        console.error('Lỗi:', error);
        showConfirm('Có lỗi xảy ra khi xử lý', 'Lỗi');
    }

    // AJAX Diagnosis Handler
    async function saveDiagnosis(maCTDon) {
        const chanDoan = document.querySelector(`#diagnosis_${maCTDon}`)?.value;
        const chiPhiDuKien = document.querySelector(`#total_estimated_cost_${maCTDon}`)?.value;
        const quyetDinh = document.querySelector(`input[name="decision_${maCTDon}"]:checked`)?.value;
        const lyDo = document.querySelector(`#reason_${maCTDon}`)?.value || '';
        const danhSachCongViecJSON = document.querySelector(`#danh_sach_cong_viec_json_${maCTDon}`)?.value;

        // Validation
        if (!chanDoan || !chanDoan.trim()) {
            showConfirm('Vui lòng nhập chẩn đoán!', 'Thông báo');
            return;
        }

        if (!chiPhiDuKien || parseFloat(chiPhiDuKien) <= 0) {
            showConfirm('Vui lòng thêm ít nhất một công việc sửa chữa!', 'Thông báo');
            return;
        }

        if (!quyetDinh) {
            showConfirm('Vui lòng chọn quyết định sửa chữa!', 'Thông báo');
            return;
        }

        const button = document.querySelector(`[onclick="saveDiagnosis('${maCTDon}')"]`);
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang lưu...';
        button.disabled = true;

        try {
            const formData = new FormData();
            formData.append('action', 'save_diagnosis');
            formData.append('maDon', '<?php echo $maDon; ?>');
            formData.append('maCTDon', maCTDon);
            formData.append('diagnosis', chanDoan);
            formData.append('estimated_cost', chiPhiDuKien);
            formData.append('decision', quyetDinh);
            formData.append('reason', lyDo);
            formData.append('danh_sach_cong_viec_json', danhSachCongViecJSON);

            const response = await fetch('<?php echo url("controllers/ajax_service.php"); ?>', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showConfirm(result.message, 'Thành công');
                updateUIAfterDiagnosis(maCTDon, result.quyetDinhSC);
            } else {
                showConfirm(result.message, 'Lỗi');
                button.innerHTML = originalText;
                button.disabled = false;
            }

        } catch (error) {
            handleError(error);
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }

    // Cập nhật giao diện sau khi lưu chẩn đoán
    function updateUIAfterDiagnosis(maCTDon, quyetDinhSC) {
        const deviceCard = document.querySelector(`[data-mactdon="${maCTDon}"]`);
        const diagnosisForm = document.getElementById(`diagnosis-form-${maCTDon}`);

        if (diagnosisForm) {
            diagnosisForm.style.opacity = '0.5';

            setTimeout(() => {
                diagnosisForm.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Đang cập nhật thông tin...
                    </div>
                `;

                // Reload phần thiết bị sau 2 giây
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }, 500);
        }
    }

    // AJAX Service Action Handler
    async function handleServiceAction(action, maCTDon, deviceName) {
        let actionText = '';
        let confirmMessage = '';

        switch (action) {
            case 'start_service':
                actionText = 'bắt đầu sửa chữa';
                confirmMessage = `Bạn có chắc chắn muốn bắt đầu sửa chữa thiết bị "${deviceName}"?`;
                break;
            case 'complete_service':
                actionText = 'kết thúc sửa chữa';
                confirmMessage = `Bạn có chắc chắn muốn kết thúc sửa chữa thiết bị "${deviceName}"?`;
                break;
            default:
                return;
        }

        // Sử dụng showConfirm với 2 callback
        showConfirm(
            confirmMessage,
            'Xác nhận',
            function () {
                // Xác nhận - thực hiện AJAX call
                performServiceAction(action, maCTDon, deviceName);
            },
            function () {
                // Hủy bỏ - không làm gì
                console.log('Người dùng đã hủy thao tác ' + actionText);
            }
        );
    }

    // Hàm thực hiện AJAX call
    async function performServiceAction(action, maCTDon, deviceName) {
        const button = document.querySelector(`[onclick="handleServiceAction('${action}', '${maCTDon}', '${deviceName}')"]`);
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
        button.disabled = true;

        try {
            const formData = new FormData();
            formData.append('action', action);
            formData.append('maDon', '<?php echo $maDon; ?>');
            formData.append('maCTDon', maCTDon);

            const response = await fetch('<?php echo url("controllers/ajax_service.php"); ?>', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showConfirm(
                    result.message,
                    'Thành công',
                    function () {
                        updateUIAfterServiceAction(maCTDon, action);
                    }
                );
            } else {
                showConfirm(result.message, 'Lỗi');
                button.innerHTML = originalText;
                button.disabled = false;
            }

        } catch (error) {
            handleError(error);
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }

    // Cập nhật giao diện sau khi thực hiện hành động dịch vụ
    function updateUIAfterServiceAction(maCTDon, action) {
        const serviceButtons = document.getElementById(`service-buttons-${maCTDon}`);

        if (serviceButtons) {
            serviceButtons.style.opacity = '0.5';
            serviceButtons.innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-spinner fa-spin me-2"></i>
                Đang cập nhật trạng thái...
            </div>
        `;

            // Reload phần thiết bị sau 2 giây
            setTimeout(() => {
                location.reload();
            }, 2000);
        }
    }

    // Cập nhật giao diện sau khi thực hiện hành động dịch vụ
    function updateUIAfterServiceAction(maCTDon, action) {
        const serviceButtons = document.getElementById(`service-buttons-${maCTDon}`);

        if (serviceButtons) {
            serviceButtons.style.opacity = '0.5';
            serviceButtons.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-spinner fa-spin me-2"></i>
                    Đang cập nhật trạng thái...
                </div>
            `;

            // Reload phần thiết bị sau 2 giây
            setTimeout(() => {
                location.reload();
            }, 2000);
        }
    }

    // AJAX Upload Evidence
    // AJAX Upload Evidence - ĐÃ SỬA ĐỂ HỖ TRỢ COMPLETION
    async function uploadEvidence(maCTDon, evidenceType) {
        // Xác định fileInput dựa trên evidenceType
        let fileInputId = '';
        switch (evidenceType) {
            case 'arrival':
                fileInputId = `fileInputArrival_${maCTDon}`;
                break;
            case 'device':
                fileInputId = `fileInputDevice_${maCTDon}`;
                break;
            case 'completion':
                fileInputId = `fileInputCompletion_${maCTDon}`;
                break;
            default:
                showConfirm('Loại minh chứng không hợp lệ!', 'Lỗi');
                return;
        }

        const fileInput = document.getElementById(fileInputId);
        const file = fileInput.files[0];

        if (!file) {
            showConfirm('Vui lòng chọn file ảnh!', 'Thông báo');
            return;
        }

        // Kiểm tra kích thước file
        if (file.size > 5 * 1024 * 1024) {
            showConfirm('Kích thước file vượt quá 5MB. Vui lòng chọn file nhỏ hơn.', 'Thông báo');
            return;
        }

        // Kiểm tra loại file
        if (!file.type.match('image.*')) {
            showConfirm('Vui lòng chọn file ảnh hợp lệ (PNG, JPG, GIF).', 'Thông báo');
            return;
        }

        const button = document.querySelector(`[onclick="uploadEvidence('${maCTDon}', '${evidenceType}')"]`);
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Uploading...';
        button.disabled = true;

        try {
            const formData = new FormData();
            formData.append('action', 'upload_evidence');
            formData.append('maDon', '<?php echo $maDon; ?>');
            formData.append('maCTDon', maCTDon);
            formData.append('evidence_type', evidenceType);
            formData.append('evidence_image', file);

            const response = await fetch('<?php echo url("controllers/ajax_service.php"); ?>', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showConfirm(result.message, 'Thành công');
                // Reload phần upload sau 1.5 giây
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showConfirm(result.message, 'Lỗi');
                button.innerHTML = originalText;
                button.disabled = false;
            }

        } catch (error) {
            handleError(error);
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }

    // Các hàm hỗ trợ cho công việc sửa chữa
    function toggleCustomJobInput(select, maThietBi) {
    const divLoiKhac = document.getElementById('custom_job_name_' + maThietBi);
    const inputChiPhi = document.getElementById('job_cost_' + maThietBi);
    const inputThoiGian = document.getElementById('job_time_' + maThietBi);
    const divThoiGian = document.getElementById('time_input_div_' + maThietBi); // THÊM DÒNG NÀY
    const hintChiPhi = document.getElementById('cost_hint_' + maThietBi);
    const luaChon = select.options[select.selectedIndex];

    if (luaChon.value === 'custom') {
        divLoiKhac.style.display = 'block';
        if (divThoiGian) divThoiGian.style.display = 'block'; // HIỆN INPUT THỜI GIAN
        inputChiPhi.placeholder = 'Nhập chi phí...';
        if (inputThoiGian) inputThoiGian.value = '';
        hintChiPhi.innerHTML = 'Lỗi khác - nhập chi phí và thời gian sửa chữa';
        inputChiPhi.value = '';
    } else if (luaChon.value) {
        divLoiKhac.style.display = 'none';
        if (divThoiGian) divThoiGian.style.display = 'none'; // ẨN INPUT THỜI GIAN

        const khoangGia = luaChon.getAttribute('data-range');
        const thoiGian = luaChon.getAttribute('data-time') || '0';
        inputChiPhi.value = '';
        
        // TỰ ĐỘNG ĐIỀN THỜI GIAN TỪ CSDL
        if (inputThoiGian) inputThoiGian.value = thoiGian;

        if (khoangGia) {
            inputChiPhi.placeholder = khoangGia;
            hintChiPhi.innerHTML = `<i class="fas fa-info-circle me-1"></i>Khoảng giá tham khảo: ${khoangGia} | Thời gian: ${thoiGian} phút`;
        } else {
            inputChiPhi.placeholder = 'Nhập chi phí...';
            hintChiPhi.innerHTML = `Nhập chi phí sửa chữa | Thời gian: ${thoiGian} phút`;
        }
    } else {
        divLoiKhac.style.display = 'none';
        if (divThoiGian) divThoiGian.style.display = 'none'; // ẨN INPUT THỜI GIAN
        inputChiPhi.value = '';
        if (inputThoiGian) inputThoiGian.value = '';
        inputChiPhi.placeholder = 'Nhập chi phí...';
        hintChiPhi.innerHTML = 'Chọn lỗi để xem thông tin';
    }
}

    // Hàm kiểm tra chi phí có nằm trong khoảng giá hay không
    function validateCostInRange(cost, rangeString) {
        if (!rangeString || rangeString === 'Không có' || rangeString === 'Tự nhập') {
            return true;
        }

        // Phân tích chuỗi khoảng giá (ví dụ: "100.000 - 500.000 VND")
        const rangeMatch = rangeString.match(/(\d+(?:\.\d+)*)\s*-\s*(\d+(?:\.\d+)*)/);
        if (!rangeMatch) return true;

        const minCost = parseInt(rangeMatch[1].replace(/\./g, ''));
        const maxCost = parseInt(rangeMatch[2].replace(/\./g, ''));

        return cost >= minCost && cost <= maxCost;
    }

    function addRepairJob(maThietBi) {
    const select = document.getElementById('job_select_' + maThietBi);
    const inputLoiKhac = document.getElementById('custom_job_input_' + maThietBi);
    const inputChiPhi = document.getElementById('job_cost_' + maThietBi);
    const inputThoiGian = document.getElementById('job_time_' + maThietBi);
    const luaChon = select.options[select.selectedIndex];

    let tenCongViec = '';
    let chiPhiCongViec = inputChiPhi.value;
    let thoiGianCongViec = inputThoiGian ? inputThoiGian.value : 0;
    let khoangGia = '';

    // Kiểm tra chi phí
    if (!chiPhiCongViec || isNaN(chiPhiCongViec) || parseFloat(chiPhiCongViec) <= 0) {
        showConfirm('Vui lòng nhập chi phí hợp lệ!', 'Thông báo');
        inputChiPhi.focus();
        return;
    }

    chiPhiCongViec = parseFloat(chiPhiCongViec);
    thoiGianCongViec = parseFloat(thoiGianCongViec) || 0;

    // KIỂM TRA THỜI GIAN CHO LỖI KHÁC
    if (luaChon.value === 'custom') {
        tenCongViec = inputLoiKhac.value.trim();
        khoangGia = 'Tự nhập';
        
        if (!tenCongViec) {
            showConfirm('Vui lòng nhập tên lỗi!', 'Thông báo');
            inputLoiKhac.focus();
            return;
        }
        
        // BẮT BUỘC NHẬP THỜI GIAN CHO LỖI KHÁC
        if (!inputThoiGian || !inputThoiGian.value || parseFloat(inputThoiGian.value) <= 0) {
            showConfirm('Vui lòng nhập thời gian sửa chữa cho lỗi khác!', 'Thông báo');
            if (inputThoiGian) inputThoiGian.focus();
            return;
        }
    } else if (luaChon.value) {
        tenCongViec = luaChon.text.split('(')[0].trim();
        khoangGia = luaChon.getAttribute('data-range') || 'Không có';

        // KIỂM TRA KHOẢNG GIÁ
        if (khoangGia && khoangGia !== 'Không có' && khoangGia !== 'Tự nhập') {
            if (!validateCostInRange(chiPhiCongViec, khoangGia)) {
                showConfirm(
                    'Chi phí nhập không nằm trong khoảng giá ' + khoangGia,
                    'Thông báo',
                );
                return;
            }
        }
    } else {
        showConfirm('Vui lòng chọn lỗi!', 'Thông báo');
        return;
    }

    const maCongViec = 'congviec_' + Date.now();
    danhSachCongViec[maThietBi].push({
        id: maCongViec,
        name: tenCongViec,
        cost: chiPhiCongViec,
        time: thoiGianCongViec,
        priceRange: khoangGia
    });

    hienThiDanhSachCongViec(maThietBi);

    // Reset
    select.value = '';
    inputLoiKhac.value = '';
    inputChiPhi.value = '';
    if (inputThoiGian) inputThoiGian.value = '';
    document.getElementById('custom_job_name_' + maThietBi).style.display = 'none';
    document.getElementById('time_input_div_' + maThietBi).style.display = 'none'; // ẨN INPUT THỜI GIAN
    document.getElementById('cost_hint_' + maThietBi).innerHTML = 'Chọn lỗi để xem thông tin';

    showConfirm('Đã thêm công việc vào danh sách', 'Thành công');
}

function hienThiDanhSachCongViec(maThietBi) {
    const container = document.getElementById('repair_jobs_table_' + maThietBi);
    const footer = document.getElementById('repair_jobs_footer_' + maThietBi);
    const cacCongViec = danhSachCongViec[maThietBi];
    const inputJSON = document.getElementById('danh_sach_cong_viec_json_' + maThietBi);

    if (inputJSON) {
        inputJSON.value = JSON.stringify(cacCongViec);
    }

    if (cacCongViec.length === 0) {
        container.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-3 text-muted"> <!-- ĐỔI colspan thành 6 -->
                    <i class="fas fa-info-circle me-2"></i>
                    Chưa có công việc nào được thêm
                </td>
            </tr>
        `;
        footer.style.display = 'none';
        return;
    }

    let html = '';
    let tongChiPhi = 0;
    let tongThoiGian = 0; // THÊM DÒNG NÀY

    cacCongViec.forEach((congViec, index) => {
        tongChiPhi += congViec.cost;
        tongThoiGian += congViec.time; // THÊM DÒNG NÀY
        html += `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>${congViec.name}</td>
                <td class="text-center">${congViec.priceRange || 'Không có'}</td>
                <td class="text-center text-info fw-bold">${congViec.time} phút</td> <!-- THÊM CỘT NÀY -->
                <td class="text-end">${dinhDangSo(congViec.cost)}</td>
                <td class="text-center">
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger" 
                            onclick="xoaCongViec('${maThietBi}', '${congViec.id}')">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    container.innerHTML = html;
    footer.style.display = '';
    document.getElementById('total_table_' + maThietBi).textContent = dinhDangSo(tongChiPhi);
    document.getElementById('total_time_table_' + maThietBi).textContent = tongThoiGian + 'phút'; // THÊM DÒNG NÀY

    capNhatTongBaoGiaDuKien(maThietBi);
}

    function xoaCongViec(maThietBi, maCongViec) {
        if (confirm('Bạn có chắc chắn muốn xóa công việc này?')) {
            danhSachCongViec[maThietBi] = danhSachCongViec[maThietBi].filter(congViec => congViec.id !== maCongViec);
            hienThiDanhSachCongViec(maThietBi);
            showConfirm('Đã xóa công việc', 'Thông báo');
        }
    }

    function capNhatTongBaoGiaDuKien(maThietBi) {
    const inputTong = document.getElementById('total_estimated_cost_' + maThietBi);
    const inputTongThoiGian = document.getElementById('total_estimated_time_' + maThietBi); // THÊM DÒNG NÀY
    const hienThiTong = document.getElementById('total_display_' + maThietBi);
    const hienThiTongThoiGian = document.getElementById('total_time_display_' + maThietBi); // THÊM DÒNG NÀY
    const cacCongViec = danhSachCongViec[maThietBi];

    let tong = 0;
    let tongThoiGian = 0; // THÊM DÒNG NÀY
    
    cacCongViec.forEach(congViec => {
        tong += congViec.cost;
        tongThoiGian += congViec.time; // THÊM DÒNG NÀY
    });

    inputTong.value = tong;
    if (inputTongThoiGian) inputTongThoiGian.value = tongThoiGian; // THÊM DÒNG NÀY
    hienThiTong.textContent = dinhDangSo(tong) + ' VND';
    if (hienThiTongThoiGian) hienThiTongThoiGian.textContent = tongThoiGian + ' phút'; // THÊM DÒNG NÀY
}
    function dinhDangSo(so) {
        return new Intl.NumberFormat('vi-VN').format(so);
    }

    // Khởi tạo upload area
    document.addEventListener('DOMContentLoaded', function () {
        // Xử lý click vào ảnh để phóng to
        const evidenceImages = document.querySelectorAll('.evidence-image');
        evidenceImages.forEach(img => {
            img.addEventListener('click', function () {
                const imageSrc = this.getAttribute('data-image-src');
                document.getElementById('modalImage').src = imageSrc;
            });
        });

        // Khởi tạo upload area cho từng thiết bị
        <?php foreach ($chiTietDonDichVu as $ctdd): ?>
            <?php if (!$daCoMinhChungDen): ?>
                initUploadArea('Arrival', '<?php echo $ctdd['maCTDon']; ?>');
            <?php endif; ?>
            <?php if (!$daCoMinhChungThietBi): ?>
                initUploadArea('Device', '<?php echo $ctdd['maCTDon']; ?>');
            <?php endif; ?>
            <?php if (!$daUploadHoanThanh): ?>
                initUploadArea('Completion', '<?php echo $ctdd['maCTDon']; ?>');
            <?php endif; ?>
        <?php endforeach; ?>

        // Hiển thị danh sách công việc ban đầu
        <?php foreach ($chiTietDonDichVu as $ctdd): ?>
            hienThiDanhSachCongViec('<?php echo $ctdd['maCTDon']; ?>');
        <?php endforeach; ?>
    });

    function initUploadArea(type, maCTDon) {
        const uploadArea = document.getElementById(`uploadArea${type}_${maCTDon}`);
        const fileInput = document.getElementById(`fileInput${type}_${maCTDon}`);
        const previewContainer = document.getElementById(`previewContainer${type}_${maCTDon}`);
        const previewImage = document.getElementById(`previewImage${type}_${maCTDon}`);
        const changeBtn = document.getElementById(`changeBtn${type}_${maCTDon}`);

        if (!uploadArea || !fileInput) return;

        uploadArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const file = this.files[0];

                if (file.size > 5 * 1024 * 1024) {
                    showConfirm('Kích thước file vượt quá 5MB. Vui lòng chọn file nhỏ hơn.', 'Thông báo');
                    return;
                }

                if (!file.type.match('image.*')) {
                    showConfirm('Vui lòng chọn file ảnh hợp lệ (PNG, JPG, GIF).', 'Thông báo');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                    uploadArea.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });

        if (changeBtn) {
            changeBtn.addEventListener('click', function () {
                uploadArea.style.display = 'block';
                previewContainer.style.display = 'none';
                fileInput.value = '';
            });
        }
    }
    function toggleCustomJobInputPhatSinh(select, maThietBi) {
    const divLoiKhac = document.getElementById('custom_job_name_phatsinh_' + maThietBi);
    const inputChiPhi = document.getElementById('job_cost_phatsinh_' + maThietBi);
    const inputThoiGian = document.getElementById('job_time_phatsinh_' + maThietBi);
    const divThoiGian = document.getElementById('time_input_div_phatsinh_' + maThietBi);
    const hintChiPhi = document.getElementById('cost_hint_phatsinh_' + maThietBi);
    const luaChon = select.options[select.selectedIndex];

    if (luaChon.value === 'custom') {
        divLoiKhac.style.display = 'block';
        if (divThoiGian) divThoiGian.style.display = 'block';
        inputChiPhi.placeholder = 'Nhập chi phí...';
        if (inputThoiGian) inputThoiGian.value = '';
        hintChiPhi.innerHTML = 'Lỗi phát sinh khác - nhập chi phí và thời gian sửa chữa (phút)';
        inputChiPhi.value = '';
    } else if (luaChon.value) {
        divLoiKhac.style.display = 'none';
        if (divThoiGian) divThoiGian.style.display = 'none';

        const khoangGia = luaChon.getAttribute('data-range');
        const thoiGianPhut = luaChon.getAttribute('data-time') || '0';
        
        inputChiPhi.value = '';
        
        // HIỆN SỐ PHÚT LUÔN, KHÔNG CHUYỂN ĐỔI
        if (inputThoiGian) inputThoiGian.value = thoiGianPhut;

        if (khoangGia) {
            inputChiPhi.placeholder = khoangGia;
            hintChiPhi.innerHTML = `<i class="fas fa-info-circle me-1"></i>Khoảng giá tham khảo: ${khoangGia} | Thời gian: ${thoiGianPhut} phút`;
        } else {
            inputChiPhi.placeholder = 'Nhập chi phí...';
            hintChiPhi.innerHTML = `Nhập chi phí sửa chữa | Thời gian: ${thoiGianPhut} phút`;
        }
    } else {
        divLoiKhac.style.display = 'none';
        if (divThoiGian) divThoiGian.style.display = 'none';
        inputChiPhi.value = '';
        if (inputThoiGian) inputThoiGian.value = '';
        inputChiPhi.placeholder = 'Nhập chi phí...';
        hintChiPhi.innerHTML = 'Chọn lỗi để xem thông tin';
    }
}function addRepairJobPhatSinh(maThietBi) {
    const select = document.getElementById('job_select_phatsinh_' + maThietBi);
    const inputLoiKhac = document.getElementById('custom_job_input_phatsinh_' + maThietBi);
    const inputChiPhi = document.getElementById('job_cost_phatsinh_' + maThietBi);
    const inputThoiGian = document.getElementById('job_time_phatsinh_' + maThietBi);
    const luaChon = select.options[select.selectedIndex];

    let tenCongViec = '';
    let chiPhiCongViec = inputChiPhi.value;
    let thoiGianCongViec = 0;
    let khoangGia = '';

    if (!chiPhiCongViec || isNaN(chiPhiCongViec) || parseFloat(chiPhiCongViec) <= 0) {
        showConfirm('Vui lòng nhập chi phí hợp lệ!', 'Thông báo');
        inputChiPhi.focus();
        return;
    }

    chiPhiCongViec = parseFloat(chiPhiCongViec);

    if (luaChon.value === 'custom') {
        tenCongViec = inputLoiKhac.value.trim();
        khoangGia = 'Tự nhập';
        
        if (!tenCongViec) {
            showConfirm('Vui lòng nhập tên lỗi!', 'Thông báo');
            inputLoiKhac.focus();
            return;
        }
        
        // BÂY GIỜ NHẬP THEO PHÚT
        if (!inputThoiGian || !inputThoiGian.value || parseFloat(inputThoiGian.value) <= 0) {
            showConfirm('Vui lòng nhập thời gian sửa chữa cho lỗi phát sinh khác!', 'Thông báo');
            if (inputThoiGian) inputThoiGian.focus();
            return;
        }
        
        // GIỮ NGUYÊN PHÚT, KHÔNG CHUYỂN ĐỔI
        thoiGianCongViec = parseFloat(inputThoiGian.value);
    } else if (luaChon.value) {
        tenCongViec = luaChon.text.split('(')[0].trim();
        khoangGia = luaChon.getAttribute('data-range') || 'Không có';
        
        // LẤY TRỰC TIẾP PHÚT TỪ DATABASE
        thoiGianCongViec = parseFloat(luaChon.getAttribute('data-time')) || 0;

        if (khoangGia && khoangGia !== 'Không có' && khoangGia !== 'Tự nhập') {
            if (!validateCostInRange(chiPhiCongViec, khoangGia)) {
                showConfirm(
                    'Chi phí nhập không nằm trong khoảng giá ' + khoangGia,
                    'Thông báo',
                );
                return;
            }
        }
    } else {
        showConfirm('Vui lòng chọn lỗi!', 'Thông báo');
        return;
    }

    const maCongViec = 'congviec_phatsinh_' + Date.now();
    danhSachCongViecPhatSinh[maThietBi].push({
        id: maCongViec,
        name: tenCongViec,
        cost: chiPhiCongViec,
        time: thoiGianCongViec, // LƯU THEO PHÚT
        priceRange: khoangGia
    });

    hienThiDanhSachCongViecPhatSinh(maThietBi);

    // Reset
    select.value = '';
    inputLoiKhac.value = '';
    inputChiPhi.value = '';
    if (inputThoiGian) inputThoiGian.value = '';
    document.getElementById('custom_job_name_phatsinh_' + maThietBi).style.display = 'none';
    document.getElementById('time_input_div_phatsinh_' + maThietBi).style.display = 'none';
    document.getElementById('cost_hint_phatsinh_' + maThietBi).innerHTML = 'Nhập chi phí sửa chữa';

    showConfirm('Đã thêm công việc vào danh sách phát sinh', 'Thành công');
}function hienThiDanhSachCongViecPhatSinh(maThietBi) {
    const container = document.getElementById('repair_jobs_phatsinh_table_' + maThietBi);
    const footer = document.getElementById('repair_jobs_phatsinh_footer_' + maThietBi);
    const cacCongViec = danhSachCongViecPhatSinh[maThietBi];

    if (cacCongViec.length === 0) {
        container.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                    <i class="fas fa-inbox me-2"></i>Chưa có công việc nào
                </td>
            </tr>
        `;
        if (footer) footer.style.display = 'none';
        return;
    }

    let html = '';
    let tongChiPhi = 0;
    let tongThoiGianPhut = 0;

    cacCongViec.forEach((congViec, index) => {
        tongChiPhi += congViec.cost;
        tongThoiGianPhut += congViec.time;
        
        html += `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>${congViec.name}</td>
                <td class="text-center">${congViec.priceRange || 'Không có'}</td>
                <td class="text-center text-info fw-bold">${congViec.time} phút</td>
                <td class="text-end text-danger fw-bold">${dinhDangSo(congViec.cost)} ₫</td>
                <td class="text-center">
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger" 
                            onclick="xoaCongViecPhatSinh('${maThietBi}', '${congViec.id}')">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    container.innerHTML = html;
    if (footer) {
        footer.style.display = 'table-footer-group';
        document.getElementById('total_phatsinh_table_' + maThietBi).textContent = dinhDangSo(tongChiPhi) + ' VND';
        document.getElementById('total_time_phatsinh_table_' + maThietBi).textContent = tongThoiGianPhut + ' phút';
    }

    capNhatTongBaoGiaPhatSinh(maThietBi);
}

    function xoaCongViecPhatSinh(maThietBi, maCongViec) {
        if (confirm('Bạn có chắc chắn muốn xóa công việc phát sinh này?')) {
            danhSachCongViecPhatSinh[maThietBi] = danhSachCongViecPhatSinh[maThietBi].filter(congViec => congViec.id !== maCongViec);
            hienThiDanhSachCongViecPhatSinh(maThietBi);
            showConfirm('Đã xóa công việc phát sinh', 'Thông báo');
        }
    }

    // Hàm lưu công việc phát sinh bằng AJAX
    async function saveAdditionalJobs(maCTDon) {
    const danhSachCongViec = danhSachCongViecPhatSinh[maCTDon] || [];
    const danhSachCongViecJSON = JSON.stringify(danhSachCongViec);

    // DEBUG: Kiểm tra dữ liệu có thời gian không
    console.log('DANH SÁCH CÔNG VIỆC PHÁT SINH:');
    danhSachCongViec.forEach((congViec, index) => {
        console.log(`Công việc ${index + 1}:`, congViec);
        console.log(`- Tên: ${congViec.name}`);
        console.log(`- Chi phí: ${congViec.cost}`);
        console.log(`- Thời gian: ${congViec.time} phút`);
        console.log(`- Có trường time: ${'time' in congViec}`);
    });

    if (danhSachCongViec.length === 0) {
        showConfirm('Vui lòng thêm ít nhất một công việc phát sinh!', 'Thông báo');
        return;
    }

    if (!confirm(`Bạn có chắc chắn muốn lưu ${danhSachCongViec.length} công việc phát sinh?`)) {
        return;
    }

    const button = document.querySelector(`[onclick="saveAdditionalJobs('${maCTDon}')"]`);
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang lưu...';
    button.disabled = true;

    try {
        const formData = new FormData();
        formData.append('action', 'save_additional_jobs');
        formData.append('maDon', '<?php echo $maDon; ?>');
        formData.append('maCTDon', maCTDon);
        formData.append('danh_sach_cong_viec_phat_sinh_json', danhSachCongViecJSON);

        const response = await fetch('<?php echo url("controllers/ajax_service.php"); ?>', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        console.log('Kết quả từ server:', result); // DEBUG

        if (result.success) {
            showConfirm(result.message, 'Thành công');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showConfirm(result.message, 'Lỗi');
            button.innerHTML = originalText;
            button.disabled = false;
        }

    } catch (error) {
        handleError(error);
        button.innerHTML = originalText;
        button.disabled = false;
    }
}
</script>