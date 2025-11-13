<?php
// ajax-booking.php - CLEAN VERSION (NO SAMPLE DATA)
date_default_timezone_set('Asia/Ho_Chi_Minh');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// XÓA BUFFER HOÀN TOÀN
while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/json; charset=utf-8');

error_log("=== AJAX-BOOKING CLEAN START ===");

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

if ($action === 'get_slots') {
    $date = $_POST['date'] ?? ($_GET['date'] ?? date('Y-m-d'));
    
    error_log("📅 Date requested: " . $date);
    
    try {
        // KIỂM TRA FILE TỒN TẠI
        $configPath = __DIR__ . '/../config.php';
        $dichvuPath = __DIR__ . '/../function/dichvu.php';
        
        if (!file_exists($configPath) || !file_exists($dichvuPath)) {
            throw new Exception("File config hoặc dichvu không tồn tại");
        }
        
        require_once $configPath;
        require_once $dichvuPath;
        
        error_log("✅ Files included successfully");
        
        $dichVuService = new DichVuService($db);
        $gioHienTai = (int) date('H');
        
        // LẤY DỮ LIỆU THỰC TẾ TỪ DATABASE
        $slotsData = $dichVuService->tinhSlotKhaDung($date, $gioHienTai);
        error_log("🎯 Slot calculation successful, data count: " . count($slotsData));
        
        $danhSachKhungGio = $dichVuService->layDanhSachKhungGio();
        error_log("📊 Number of timeframes: " . count($danhSachKhungGio));
        
        $formattedSlots = [];
        
        foreach ($danhSachKhungGio as $khungGio) {
            $maKhungGio = $khungGio['maKhungGio'];
            $slotInfo = $slotsData[$maKhungGio] ?? null;
            
            if ($slotInfo && is_array($slotInfo)) {
                $formattedSlots[] = [
                    'maKhungGio' => $maKhungGio,
                    'pham_vi' => $slotInfo['pham_vi'] ?? $khungGio['khoangGio'],
                    'toi_da' => $slotInfo['toi_da'] ?? 0,
                    'da_dat' => $slotInfo['da_dat'] ?? 0,
                    'kha_dung' => $slotInfo['kha_dung'] ?? 0,
                    'tong_ktv' => $slotInfo['tong_ktv'] ?? 0,
                    'slot_phan_bo' => $slotInfo['ktv_phan_bo'] ?? 0,
                    'gioBatDau' => $slotInfo['gio_bat_dau'] ?? $khungGio['gioBatDau'],
                    'gioKetThuc' => $slotInfo['gio_ket_thuc'] ?? $khungGio['gioKetThuc'],
                    'ly_do' => $slotInfo['ly_do'] ?? 'Không xác định',
                    'kha_dung_bool' => ($slotInfo['kha_dung'] ?? 0) > 0 && !($slotInfo['vo_hieu_hoa'] ?? true)
                ];
                
                error_log("⏰ Khung {$khungGio['khoangGio']}: {$slotInfo['kha_dung']} slot khả dụng");
            } else {
                // TRẢ VỀ DỮ LIỆU RỖNG NẾU KHÔNG CÓ THÔNG TIN
                $formattedSlots[] = [
                    'maKhungGio' => $maKhungGio,
                    'pham_vi' => $khungGio['khoangGio'],
                    'toi_da' => 0,
                    'da_dat' => 0,
                    'kha_dung' => 0,
                    'tong_ktv' => 0,
                    'slot_phan_bo' => 0,
                    'gioBatDau' => $khungGio['gioBatDau'],
                    'gioKetThuc' => $khungGio['gioKetThuc'],
                    'ly_do' => 'Không có dữ liệu',
                    'kha_dung_bool' => false
                ];
                
                error_log("⚠️ Khung {$khungGio['khoangGio']}: Không có dữ liệu slot");
            }
        }
        
        $response = [
            'success' => true,
            'date' => $date,
            'slots' => $formattedSlots,
            'debug' => [
                'total_timeframes' => count($danhSachKhungGio),
                'total_slots_data' => count($slotsData),
                'current_hour' => $gioHienTai
            ]
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        
    } catch (Exception $e) {
        error_log("❌ Error: " . $e->getMessage());
        
        // ❌ KHÔNG CÓ DỮ LIỆU MẪU - CHỈ TRẢ VỀ LỖI
        echo json_encode([
            'success' => false,
            'error' => 'Lỗi hệ thống: ' . $e->getMessage(),
            'slots' => []
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Action không hợp lệ: ' . $action
    ]);
}

error_log("=== AJAX-BOOKING CLEAN END ===");
exit;