<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../function/dichvu.php';

session_start();

try {
    // Khởi tạo đối tượng
    $dichVuService = new DichVuService($db);
    
    // Dữ liệu test
    $a = '2025-11-14';
    $b = 1;
    
    echo "<h2>🧪 Test Tìm KTV Phù Hợp</h2>";
    echo "<p><strong>Ngày:</strong> $a</p>";
    echo "<p><strong>Khung giờ:</strong> $b (08:00-10:00)</p>";
    echo "<hr>";
    
    // Gọi hàm
    $maKTV = $dichVuService->timKTVPhuHop($a, $b);
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
    echo "<h3>✅ KẾT QUẢ THÀNH CÔNG</h3>";
    echo "<p><strong>Mã KTV được chọn:</strong> #$maKTV</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "<h3>❌ LỖI</h3>";
    echo "<p><strong>Lỗi:</strong> " . $e->getMessage() . "</p>";
    echo "</div>";
    
    // Debug thêm
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin-top: 10px;'>";
    echo "<h4>🔧 Debug Info:</h4>";
    echo "<pre>Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "</pre>";
    echo "</div>";
}

// Hiển thị thông tin thêm
echo "<hr>";
echo "<h4>📊 Thông tin test:</h4>";
echo "<ul>";
echo "<li>Ngày test: " . date('d/m/Y H:i:s') . "</li>";
echo "<li>PHP Version: " . PHP_VERSION . "</li>";
echo "<li>Database: " . ($db ? "Connected" : "Not connected") . "</li>";
echo "</ul>";
?>