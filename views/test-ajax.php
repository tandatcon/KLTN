<?php
// test-ajax.php - FILE TEST ĐƠN GIẢN
error_reporting(E_ALL);
ini_set('display_errors', 1);

// XÓA BUFFER
while (ob_get_level()) ob_end_clean();

header('Content-Type: application/json; charset=utf-8');

error_log("=== TEST AJAX BẮT ĐẦU ===");

// TRẢ VỀ JSON ĐƠN GIẢN
$response = [
    'success' => true,
    'message' => 'Test thành công',
    'timestamp' => date('Y-m-d H:i:s'),
    'slots' => [
        ['maKhungGio' => 'KG1', 'pham_vi' => '8-10', 'kha_dung' => 3],
        ['maKhungGio' => 'KG2', 'pham_vi' => '10-12', 'kha_dung' => 0]
    ]
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);

error_log("=== TEST AJAX KẾT THÚC ===");
exit;