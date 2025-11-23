<?php
// ajax-device.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';

if (!isset($_POST['action'])) die(json_encode(['success' => false]));

$action = $_POST['action'];

header('Content-Type: application/json');

if ($action === 'get_brands') {
    $maThietBi = intval($_POST['maThietBi'] ?? 0);
    if ($maThietBi <= 0) die(json_encode(['success' => false]));

    $sql = "SELECT maHang, tenHang FROM hangsanxuat WHERE maThietBi = ? ORDER BY tenHang";
    $stmt = $db->prepare($sql);
    $stmt->execute([$maThietBi]);
    $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'brands' => $brands]);
    exit;
}

if ($action === 'get_models') {
    $maHang = intval($_POST['maHang'] ?? 0);
    if ($maHang <= 0) die(json_encode(['success' => false]));

    $sql = "SELECT maMau, tenMau FROM mausanpham WHERE maHang = ? ORDER BY 
            CASE WHEN tenMau = 'Mẫu khác' THEN 1 ELSE 0 END, tenMau";
    $stmt = $db->prepare($sql);
    $stmt->execute([$maHang]);
    $models = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'models' => $models]);
    exit;
}