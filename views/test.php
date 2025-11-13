<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}
date_default_timezone_set('Asia/Ho_Chi_Minh');

$pageTitle = "Test Slot Nâng Cao - TechCare";
include VIEWS_PATH . '/header.php';

// Include class TestSlotService
require_once __DIR__ . '/../function/dichvu.php';

// Khởi tạo service
$testService  = new DichVuService($db);


// Xử lý form
$ngayChon = $_POST['ngay_chon'] ?? date('Y-m-d');
$gioHienTai = $_POST['gio_hien_tai'] ?? (int) date('H');

// 🔥 THÊM DEBUG TRỰC TIẾP TẠI ĐÂY
echo "<div style='background: #ffeb3b; padding: 15px; margin: 10px; border: 2px solid red;'>";
echo "<h3>🐛 DEBUG TRỰC TIẾP - KIỂM TRA ĐƠN HÀNG</h3>";

$debugInfo = $testService->debugThongTin($ngayChon, $gioHienTai);

echo "<strong>Chi tiết đơn còn lại:</strong><br>";
echo "<pre>";
print_r($debugInfo['don_con_lai']);
echo "</pre>";

echo "<strong>Kiểm tra keys trong chi_tiet:</strong><br>";
if (!empty($debugInfo['don_con_lai']['chi_tiet'])) {
    foreach ($debugInfo['don_con_lai']['chi_tiet'] as $key => $value) {
        echo "Key: <span style='color: red;'>'$key'</span> (kiểu: " . gettype($key) . ")<br>";
    }
} else {
    echo "KHÔNG CÓ DỮ LIỆU TRONG chi_tiet<br>";
}

echo "</div>";
// 🔥 HẾT DEBUG

// Tính slot
$slots = $testService->tinhSlotKhaDung($ngayChon, $gioHienTai);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Slot Nâng Cao</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1400px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        
        .slots-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 15px; margin-top: 20px; }
        .slot-card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; }
        .slot-card.co-the-dat { background: #e6f7ff; border-color: #91d5ff; }
        .slot-card.ktv-ban { background: #fff2e8; border-color: #ffbb96; }
        .slot-card.da-qua-gio { background: #f0f0f0; border-color: #d9d9d9; }
        .slot-card.het-slot { background: #fff1f0; border-color: #ffa39e; }
        
        .slot-info { margin: 5px 0; font-size: 14px; }
        .slot-info-small { font-size: 12px; color: #666; }
        .slot-trang-thai { font-weight: bold; margin-top: 10px; padding: 5px; border-radius: 4px; text-align: center; }
        .trang-thai-co-the-dat { background: #d4edda; color: #155724; }
        .trang-thai-ktv-ban { background: #f8d7da; color: #721c24; }
        .trang-thai-da-qua-gio { background: #e2e3e5; color: #383d41; }
        .trang-thai-het-slot { background: #fff2e8; color: #d46b08; }
        
        .thong-ke { background: #e9ecef; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .thong-ke-item { display: inline-block; margin-right: 20px; padding: 10px; background: white; border-radius: 4px; }
        
        .debug-section { margin-top: 30px; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; }
        .debug-info { background: white; padding: 10px; border-radius: 4px; margin-top: 10px; font-family: monospace; font-size: 12px; }
        
        .progress-bar { background: #e9ecef; border-radius: 10px; height: 10px; margin: 5px 0; }
        .progress-fill { background: #28a745; height: 100%; border-radius: 10px; }
        .progress-fill-warning { background: #ffc107; }
        .progress-fill-danger { background: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Test Slot Nâng Cao - Phân Bổ KTV Thông Minh</h1>
        
        <!-- Form chọn ngày và giờ -->
        <div class="form-group">
            <form method="POST">
                <label for="ngay_chon">Chọn ngày:</label>
                <input type="date" id="ngay_chon" name="ngay_chon" value="<?= htmlspecialchars($ngayChon) ?>" 
                       min="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                
                <label for="gio_hien_tai" style="margin-top: 10px;">Giờ hiện tại (0-23):</label>
                <select id="gio_hien_tai" name="gio_hien_tai">
                    <?php for ($i = 0; $i <= 23; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $gioHienTai ? 'selected' : '' ?>>
                            <?= sprintf('%02d:00', $i) ?>
                        </option>
                    <?php endfor; ?>
                </select>
                
                <button type="submit" style="display: block; margin-top: 10px;">Kiểm tra Slot Thông Minh</button>
            </form>
        </div>

        <!-- Thống kê -->
        <div class="thong-ke">
            <h3>📈 Thống kê thông minh - <?= htmlspecialchars($ngayChon) ?> - <?= sprintf('%02d:00', $gioHienTai) ?></h3>
            <?php
            $tongKhungGio = count($slots);
            $coTheDat = 0;
            $ktvBan = 0;
            $daQuaGio = 0;
            $hetSlot = 0;
            $tongKTVDu = 0;
            
            foreach ($slots as $slot) {
                if ($slot['da_qua_gio']) {
                    $daQuaGio++;
                } elseif ($slot['tong_ktv_thuc_te'] === 0) {
                    $ktvBan++;
                } elseif ($slot['kha_dung'] <= 0) {
                    $hetSlot++;
                } else {
                    $coTheDat++;
                }
                $tongKTVDu += $slot['ktv_du_phan_bo'] ?? 0;
            }
            ?>
            <div class="thong-ke-item">Tổng khung giờ: <strong><?= $tongKhungGio ?></strong></div>
            <div class="thong-ke-item" style="color: green;">Có thể đặt: <strong><?= $coTheDat ?></strong></div>
            <div class="thong-ke-item" style="color: orange;">KTV bận: <strong><?= $ktvBan ?></strong></div>
            <div class="thong-ke-item" style="color: red;">Hết slot: <strong><?= $hetSlot ?></strong></div>
            <div class="thong-ke-item" style="color: gray;">Đã qua giờ: <strong><?= $daQuaGio ?></strong></div>
            <div class="thong-ke-item">Tổng KTV: <strong><?= $debugInfo['tong_ktv'] ?? 0 ?></strong></div>
            <div class="thong-ke-item">KTV dư tái sử dụng: <strong><?= $tongKTVDu ?></strong></div>
            <div class="thong-ke-item">Đơn hoàn thành: <strong><?= $debugInfo['don_hoan_thanh']['tong'] ?? 0 ?></strong></div>
        </div>

        <!-- Danh sách slot -->
        <div class="slots-grid">
            <?php foreach ($slots as $maKhungGio => $slot): ?>
                <div class="slot-card <?= 
                    $slot['da_qua_gio'] ? 'da-qua-gio' : 
                    ($slot['tong_ktv_thuc_te'] === 0 ? 'ktv-ban' : 
                    ($slot['kha_dung'] <= 0 ? 'het-slot' : 'co-the-dat')) 
                ?>">
                    
                    <h3><?= htmlspecialchars($slot['pham_vi']) ?></h3>
                    <div class="slot-info">⏰ <?= $slot['gio_bat_dau'] ?>:00 - <?= $slot['gio_ket_thuc'] ?>:00</div>
                    
                    <!-- Thông tin KTV -->
                    <div class="slot-info">👥 KTV ban đầu: <?= $slot['ktv_phan_bo'] ?>/<?= $slot['tong_ktv'] ?></div>
                    <?php if ($slot['ktv_du_phan_bo'] > 0): ?>
                        <div class="slot-info" style="color: #28a745;">➕ KTV dư thêm: <?= $slot['ktv_du_phan_bo'] ?></div>
                    <?php endif; ?>
                    <div class="slot-info">📊 Tổng KTV thực tế: <strong><?= $slot['tong_ktv_thuc_te'] ?></strong></div>
                    
                    <!-- Thông tin slot -->
                    <?php if ($slot['slot_tu_don_hoan_thanh'] > 0): ?>
                        <div class="slot-info" style="color: #17a2b8;">🔄 Slot từ đơn HT: +<?= $slot['slot_tu_don_hoan_thanh'] ?></div>
                    <?php endif; ?>
                    <div class="slot-info">📦 Slot tối đa: <strong><?= $slot['toi_da'] ?></strong></div>
                    <div class="slot-info">✅ Đã đặt: <?= $slot['da_dat'] ?></div>
                    <div class="slot-info">🎯 Khả dụng: <strong><?= $slot['kha_dung'] ?></strong></div>
                    
                    <!-- Progress bar -->
                    <?php if ($slot['toi_da'] > 0): ?>
                        <div class="slot-info">
                            Tỷ lệ sử dụng: 
                            <div class="progress-bar">
                                <?php 
                                $tyLe = min(100, ($slot['da_dat'] / $slot['toi_da']) * 100);
                                $progressClass = $tyLe < 70 ? 'progress-fill' : ($tyLe < 90 ? 'progress-fill-warning' : 'progress-fill-danger');
                                ?>
                                <div class="<?= $progressClass ?>" style="width: <?= $tyLe ?>%"></div>
                            </div>
                            <?= number_format($tyLe, 1) ?>%
                        </div>
                    <?php endif; ?>
                    
                    <!-- Debug info nhỏ -->
                    <div class="slot-info-small">
                        <?php if ($slot['da_qua_gio']): ?>
                            ⚠️ Khung giờ đã kết thúc
                        <?php elseif ($slot['tong_don_hoan_thanh'] > 0): ?>
                            📋 Có <?= $slot['tong_don_hoan_thanh'] ?> đơn hoàn thành trong ngày
                        <?php endif; ?>
                    </div>
                    
                    <div class="slot-trang-thai <?= 
                        $slot['da_qua_gio'] ? 'trang-thai-da-qua-gio' : 
                        ($slot['tong_ktv_thuc_te'] === 0 ? 'trang-thai-ktv-ban' : 
                        ($slot['kha_dung'] <= 0 ? 'trang-thai-het-slot' : 'trang-thai-co-the-dat'))
                    ?>">
                        <?= $slot['ly_do'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Giải thích -->
        <div class="thong-ke" style="background: #d1ecf1; border-color: #bee5eb;">
            <h3>💡 Giải thích Logic Phân Bổ</h3>
            <div class="slot-info">
                <strong>KTV ban đầu:</strong> Phân bổ đều từ tổng số KTV làm việc<br>
                <strong>KTV dư thêm:</strong> KTV thừa từ các khung giờ đã qua được tái sử dụng<br>
                <strong>Slot từ đơn HT:</strong> Đơn hoàn thành tạo thêm slot cho khung giờ còn lại<br>
                <strong>Tổng KTV thực tế:</strong> KTV ban đầu + KTV dư thêm
            </div>
        </div>

        <!-- Debug info -->
        <div class="debug-section">
            <h3>🐛 Debug Thông Tin Chi Tiết</h3>
            <button onclick="toggleDebug()">Hiện/Ẩn Debug Info</button>
            
            <div id="debugInfo" class="debug-info" style="display: none;">
                <h4>Thông tin tổng quan:</h4>
                <pre><?= print_r([
                    'ngay' => $debugInfo['ngay'] ?? '',
                    'gio_hien_tai' => $debugInfo['gio_hien_tai'] ?? '',
                    'tong_ktv' => $debugInfo['tong_ktv'] ?? 0,
                    'don_hoan_thanh' => $debugInfo['don_hoan_thanh']['tong'] ?? 0,
                    'don_con_lai' => $debugInfo['don_con_lai']['tong'] ?? 0,
                    'so_khung_gio' => count($debugInfo['khung_gio'] ?? [])
                ], true) ?></pre>
                
                <h4>Chi tiết đơn hoàn thành:</h4>
                <pre><?= print_r($debugInfo['don_hoan_thanh']['chi_tiet'] ?? [], true) ?></pre>
                
                <h4>Chi tiết đơn còn lại:</h4>
                <pre><?= print_r($debugInfo['don_con_lai']['chi_tiet'] ?? [], true) ?></pre>
                
                <h4>Dữ liệu slot tính toán:</h4>
                <pre><?= print_r($slots, true) ?></pre>
            </div>
        </div>
    </div>

    <script>
        function toggleDebug() {
            const debugInfo = document.getElementById('debugInfo');
            debugInfo.style.display = debugInfo.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>

<?php include VIEWS_PATH . '/footer.php'; ?>