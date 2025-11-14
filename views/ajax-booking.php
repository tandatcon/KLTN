<?php
// ajax-booking.php - SIÊU AN TOÀN, KHÔNG BAO GIỜ TRẢ HTML
date_default_timezone_set('Asia/Ho_Chi_Minh');

// XÓA TOÀN BỘ OUTPUT BUFFER
while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/json; charset=utf-8');

// BẮT TẤT CẢ LỖI → CHUYỂN THÀNH JSON
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function ($exception) {
    error_log("AJAX EXCEPTION: " . $exception->getMessage() . " in " . $exception->getFile() . ":" . $exception->getLine());
    echo json_encode([
        'success' => false,
        'error' => 'Lỗi hệ thống: ' . $exception->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
});

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

if ($action !== 'get_slots') {
    echo json_encode(['success' => false, 'error' => 'Action không hợp lệ']);
    exit;
}

$date = $_POST['date'] ?? ($_GET['date'] ?? date('Y-m-d'));
$gioHienTai = isset($_POST['current_hour']) ? (int)$_POST['current_hour'] : (int)date('H');

error_log("AJAX: date=$date, gioHienTai=$gioHienTai");

try {
    // ĐƯỜNG DẪN TUYỆT ĐỐI, TRÁNH LỖI
    $root = dirname(__DIR__); // lên 1 cấp từ ajax/
    $configPath = $root . '/config.php';
    $dichvuPath = $root . '/function/dichvu.php';

    if (!file_exists($configPath)) {
        throw new Exception("Không tìm thấy config.php tại: $configPath");
    }
    if (!file_exists($dichvuPath)) {
        throw new Exception("Không tìm thấy dichvu.php tại: $dichvuPath");
    }

    require_once $configPath;
    require_once $dichvuPath;

    // KIỂM TRA $db CÓ TỒN TẠI
    if (!isset($db) || !($db instanceof PDO)) {
        throw new Exception("Biến \$db không tồn tại hoặc không phải PDO");
    }

    $dichVuService = new DichVuService($db);
    $slotsData = $dichVuService->tinhSlotKhaDung($date, $gioHienTai);
    $danhSachKhungGio = $dichVuService->layDanhSachKhungGio();

    $formattedSlots = [];
    foreach ($danhSachKhungGio as $khungGio) {
        $ma = $khungGio['maKhungGio'];
        $info = $slotsData[$ma] ?? null;

        $formattedSlots[] = [
            'maKhungGio' => $ma,
            'pham_vi' => $khungGio['khoangGio'],
            'toi_da' => $info['toi_da'] ?? 0,
            'da_dat' => $info['da_dat'] ?? 0,
            'kha_dung' => $info['kha_dung'] ?? 0,
            'tong_ktv_thuc_te' => $info['tong_ktv_thuc_te'] ?? 0,
            'slot_tu_don_hoan_thanh' => $info['slot_tu_don_hoan_thanh'] ?? 0,
            'gioBatDau' => $khungGio['gioBatDau'],
            'gioKetThuc' => $khungGio['gioChan'],
            'ly_do' => $info['ly_do'] ?? 'Không xác định',
            'vo_hieu_hoa' => $info['vo_hieu_hoa'] ?? true,
            'da_qua_gio' => $info['da_qua_gio'] ?? false,
            'kha_dung_bool' => ($info['kha_dung'] ?? 0) > 0 && !($info['vo_hieu_hoa'] ?? true)
        ];
    }

    echo json_encode([
        'success' => true,
        'date' => $date,
        'slots' => $formattedSlots,
        'debug' => ['gioHienTai' => $gioHienTai]
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    error_log("AJAX ERROR: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

exit;