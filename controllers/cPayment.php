<?php
require_once 'models/mOrders.php';

class cPayment
{
    private $ordersModel;

    public function __construct()
    {
        $this->ordersModel = new mOrders();
    }

    // Xử lý thanh toán VNPay
    public function processVNPayPayment($orderId, $amount, $maKH, $diemSuDung = 0)
{
    try {
        // 1. Kiểm tra đơn hàng
        $order = $this->ordersModel->getOrderDetail($orderId, $maKH);
        if (!$order || $order['thanhToan'] != 0) {
            throw new Exception("Đơn hàng không hợp lệ hoặc đã thanh toán");
        }

        // 2. Xử lý điểm tích lũy nếu có
        if ($diemSuDung > 0) {
            $this->processPointsForPayment($maKH, $diemSuDung, $amount);
        }

        // 3. Tính toán số tiền thực tế sau khi dùng điểm
        $tienGiamGia = $diemSuDung * 1000;
        $tienThanhToan = max(0, $amount - $tienGiamGia);

        if ($tienThanhToan <= 0) {
            throw new Exception("Không thể thanh toán hoàn toàn bằng điểm. Vui lòng chọn thanh toán tiền mặt!");
        }

        // 4. Tạo URL thanh toán VNPay
        $vnpUrl = $this->createVNPayPaymentUrl($orderId, $tienThanhToan);

        // 5. Lưu thông tin thanh toán tạm thời (bao gồm điểm đã dùng)
        $this->saveTempPayment($orderId, $maKH, $tienThanhToan, $diemSuDung, $amount);

        return ['success' => true, 'payment_url' => $vnpUrl];

    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// Cập nhật phương thức lưu tạm thanh toán
private function saveTempPayment($orderId, $maKH, $amount, $diemSuDung,$totalCost)
{
    $database = new Database();
    $conn = $database->getConnection();
    
    $sql = "INSERT INTO temp_payments (order_id, customer_id, amount, points_used, created_at) 
            VALUES (?, ?, ?, ?, NOW()) 
            ON DUPLICATE KEY UPDATE 
            amount = ?, points_used = ?, created_at = NOW()";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$orderId, $maKH, $amount, $diemSuDung, $amount, $diemSuDung]);
}

    // Tạo URL thanh toán VNPay
    private function createVNPayPaymentUrl($orderId, $amount)
{
    require_once __DIR__ . '/../config.php';

    $vnp_TxnRef = $orderId . '_' . time();
    $vnp_OrderInfo = 'Thanh toan don hang #' . $orderId;
    $vnp_CreateDate = date('YmdHis');

    $inputData = array(
        "vnp_Version" => VNP_VERSION,
        "vnp_Command" => VNP_COMMAND,
        "vnp_TmnCode" => VNP_TMNCODE,
        "vnp_Amount" => $amount * 100,
        "vnp_CurrCode" => VNP_CURRENCY,
        "vnp_TxnRef" => $vnp_TxnRef,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => "billpayment",
        "vnp_Locale" => "vn",
        "vnp_ReturnUrl" => VNP_RETURN_URL,
        "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
        "vnp_CreateDate" => $vnp_CreateDate,
    );

    // === CHUẨN VNPAY: DÙNG & THƯỜNG, KHÔNG DÙNG &amp; ===
    ksort($inputData);
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        if ($hashdata != "") $hashdata .= '&';
        $hashdata .= urlencode($key) . "=" . urlencode($value);
    }

    $vnpSecureHash = hash_hmac('sha512', $hashdata, VNP_HASHSECRET);
    $vnp_Url = VNP_URL . "?" . http_build_query($inputData) . "&vnp_SecureHash=" . $vnpSecureHash;

    return $vnp_Url;
}
    // Xử lý thanh toán không cần tiền (dùng điểm)
    private function processFreePayment($orderId, $maKH, $diemSuDung, $tienGiamGia)
    {
        // 1. Trừ điểm tích lũy
        if ($diemSuDung > 0) {
            $this->ordersModel->updateCustomerPoints($maKH, -$diemSuDung);
        }

        // 2. Cộng điểm mới (1.5% tổng giá trị)
        $diemNhanDuoc = round($tienGiamGia * 0.015 / 1000);
        if ($diemNhanDuoc > 0) {
            $this->ordersModel->updateCustomerPoints($maKH, $diemNhanDuoc);
        }

        // 3. Cập nhật trạng thái thanh toán
        $this->ordersModel->updateOrderPaymentStatus($orderId, 1, $tienGiamGia, $diemSuDung);

        return ['success' => true, 'free_payment' => true];
    }
    

    // Verify VNPay hash
    

    // Lấy thông tin thanh toán tạm
    private function getTempPayment($orderId)
    {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "SELECT * FROM temp_payments WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa thông tin thanh toán tạm
    private function clearTempPayment($orderId)
    {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "DELETE FROM temp_payments WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$orderId]);
    }

    // Lấy lịch sử thanh toán
    public function getPaymentHistory($maKH)
    {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "SELECT dh.maDon, dh.ngayDat, dh.tongTien, dh.thanhToan, 
                       dh.ngayThanhToan, dh.phuongThucTT
                FROM dondichvu dh
                WHERE dh.maKH = ? AND dh.thanhToan = 1
                ORDER BY dh.ngayThanhToan DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$maKH]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Sửa phương thức verifyVNPayHash
    
// Sửa phương thức createVNPayPaymentUrl

// Sửa phương thức handleVNPayReturn để debug chi tiết


// Thêm phương thức debug chi tiết
private function debugHashVerification($vnpData, $receivedHash)
{
    require_once '../config.php';
    
    $dataForHash = $vnpData;
    unset($dataForHash['vnp_SecureHash']);
    
    ksort($dataForHash);
    
    $i = 0;
    $hashData = "";
    foreach ($dataForHash as $key => $value) {
        if ($i == 1) {
            $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashData .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
    }

    $computedHash = hash_hmac('sha512', $hashData, VNP_HASHSECRET);
    
    return [
        'received_hash' => $receivedHash,
        'computed_hash' => $computedHash,
        'hash_data' => $hashData,
        'vnp_data_for_hash' => $dataForHash,
        'vnp_secret_length' => strlen(VNP_HASHSECRET),
        'hash_match' => (strtoupper($computedHash) === strtoupper($receivedHash))
    ];
}

    // ================== THÊM HÀM handleVNPayReturn HOÀN CHỈNH ==================
    public function handleVNPayReturn($vnpData)
{
    $vnp_SecureHash = $vnpData['vnp_SecureHash'] ?? '';
    unset($vnpData['vnp_SecureHash']);
    if (isset($vnpData['vnp_SecureHashType'])) unset($vnpData['vnp_SecureHashType']);

    // Verify chữ ký
    if (!$this->verifyVNPayHash($vnpData, $vnp_SecureHash)) {
        return ['success' => false, 'error' => 'Sai chữ ký bảo mật'];
    }

    $orderId = explode('_', $vnpData['vnp_TxnRef'])[0] ?? 0;
    $vnp_ResponseCode = $vnpData['vnp_ResponseCode'] ?? '';

    // Chỉ xử lý khi thanh toán thành công
    if ($vnp_ResponseCode !== '00') {
        $this->clearTempPayment($orderId);
        return ['success' => false, 'error' => 'Giao dịch không thành công (Mã: ' . $vnp_ResponseCode . ')'];
    }

    try {
        $tempPayment = $this->getTempPayment($orderId);
        if (!$tempPayment) {
            throw new Exception("Không tìm thấy thông tin thanh toán tạm");
        }

        // 1. Cộng điểm thưởng 1.5% dựa trên tổng chi phí gốc
        $diemNhanDuoc = round($tempPayment['total_cost'] * 0.015 / 1000);
        if ($diemNhanDuoc > 0) {
            $this->ordersModel->updateCustomerPoints($tempPayment['customer_id'], $diemNhanDuoc);
        }

        // 2. Cập nhật đơn hàng thành công
        $this->updateVNPayPaymentStatus($orderId, $tempPayment);

        // 3. Xóa temp
        $this->clearTempPayment($orderId);

        return ['success' => true, 'order_id' => $orderId];

    } catch (Exception $e) {
        $this->clearTempPayment($orderId);
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

private function updateVNPayPaymentStatus($orderId, $tempPayment)
{
    $database = new Database();
    $conn = $database->getConnection();
    
    $tienGiamGia = $tempPayment['points_used'] * 1000;
    $tienConLai = $tempPayment['amount'] - $tienGiamGia;
    
    $sql = "UPDATE dondichvu 
            SET thanhToan = 1, 
                ngayThanhToan = NOW(),
                diemSuDung = ?,
                tienGiamGia = ?,
                tongTien = ?,
                thanhToan = '1'
            WHERE maDon = ?";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt->execute([$tempPayment['points_used'], $tienGiamGia, $tienConLai, $orderId])) {
        throw new Exception("Lỗi khi cập nhật trạng thái thanh toán!");
    }
}

    // ================== ĐẢM BẢO HÀM verifyVNPayHash DÙNG ĐÚNG & THƯỜNG ==================
    private function verifyVNPayHash($inputData, $secureHash)
    {
        require_once __DIR__ . '/../config.php';

        unset($inputData['vnp_SecureHash']);
        if (isset($inputData['vnp_SecureHashType'])) unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($hashData != "") $hashData .= '&';
            $hashData .= urlencode($key) . "=" . urlencode($value);
        }

        $vnpSecureHash = hash_hmac('sha512', $hashData, VNP_HASHSECRET);
        return strtoupper($vnpSecureHash) === strtoupper($secureHash);
    }


    // ================== THANH TOÁN TIỀN MẶT ==================
    public function processCashPayment($orderId, $maKH, $diemSuDung = 0)
    {
        try {
            // 1. Kiểm tra đơn hàng
            $order = $this->ordersModel->getOrderDetail($orderId, $maKH);
            if (!$order) {
                throw new Exception("Đơn hàng không tồn tại!");
            }
    
            // 2. Kiểm tra nếu đã thanh toán rồi
            if ($order['thanhToan'] != 0) {
                throw new Exception("Đơn hàng đã được thanh toán trước đó!");
            }
    
            // 3. Tính tổng chi phí
            $totalCost = $this->calculateTotalCost($orderId);
            
            // 4. Xử lý điểm tích lũy nếu có (chỉ để giảm giá)
            if ($diemSuDung > 0) {
                $this->processPointsForPayment($maKH, $diemSuDung, $totalCost);
            }
    
            // 5. Cập nhật trạng thái thanh toán = 2 (Tiền mặt - chờ xử lý)
            $this->updateCashPaymentStatus($orderId, $diemSuDung, $totalCost);
    
            // 6. Ghi log thanh toán
            $this->logPayment($orderId, $maKH, 'cash', $totalCost, 2, "Thanh toán tiền mặt - chờ xử lý");
    
            return [
                'success' => true, 
                'message' => 'Đã xác nhận thanh toán tiền mặt thành công! Đơn hàng đang chờ xử lý.',
                'cash_payment' => true
            ];
    
        } catch (Exception $e) {
            error_log("Lỗi processCashPayment: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    private function processPointsForPayment($maKH, $diemSuDung, $totalCost)
    {
        // Kiểm tra điểm hiện có
        $diemHienCo = $this->ordersModel->getDiemTichLuy($maKH);
        
        if ($diemSuDung > $diemHienCo) {
            throw new Exception("Số điểm sử dụng vượt quá điểm tích lũy hiện có!");
        }
    
        // Kiểm tra điểm tối đa có thể sử dụng (không được vượt quá tổng tiền)
        $diemToiDa = floor($totalCost / 1000);
        if ($diemSuDung > $diemToiDa) {
            throw new Exception("Chỉ có thể sử dụng tối đa " . $diemToiDa . " điểm cho đơn hàng này!");
        }
    
        // Trừ điểm tích lũy
        $this->ordersModel->updateCustomerPoints($maKH, -$diemSuDung);
    
        return true;
    }
// Tính tổng chi phí đơn hàng
private function calculateTotalCost($orderId)
{
    $devices = $this->ordersModel->getOrderDevicesDetail($orderId);
    $totalCost = 0;

    foreach ($devices as $device) {
        $maCTDon = $device['maCTDon'] ?? null;
        if ($maCTDon) {
            $repairJobs = $this->ordersModel->getDeviceRepairDetails($orderId, $maCTDon);
            foreach ($repairJobs as $job) {
                $totalCost += $job['chiPhi'] ?? 0;
            }
        }
    }

    return $totalCost;
}

// Xử lý điểm tích lũy cho thanh toán tiền mặt
private function processPointsForCashPayment($maKH, $diemSuDung, $totalCost)
{
    // Kiểm tra điểm hiện có
    $diemHienCo = $this->ordersModel->getDiemTichLuy($maKH);
    
    if ($diemSuDung > $diemHienCo) {
        throw new Exception("Số điểm sử dụng vượt quá điểm tích lũy hiện có!");
    }

    // Kiểm tra điểm tối đa có thể sử dụng
    $diemToiDa = floor($totalCost / 1000);
    if ($diemSuDung > $diemToiDa) {
        throw new Exception("Chỉ có thể sử dụng tối đa " . $diemToiDa . " điểm cho đơn hàng này!");
    }

    // Trừ điểm tích lũy
    $this->ordersModel->updateCustomerPoints($maKH, -$diemSuDung);

    return true;
}

// Cập nhật trạng thái thanh toán tiền mặt
private function updateCashPaymentStatus($orderId, $diemSuDung, $totalCost)
{
    $database = new Database();
    $conn = $database->getConnection();
    
    $tienGiamGia = $diemSuDung * 1000;
    $tienConLai = $totalCost - $tienGiamGia;
    
    $sql = "UPDATE dondichvu 
            SET thanhToan = 2, 
                ngayThanhToan = NOW(),
                diemSuDung = ?,
                tienGiamGia = ?,
                tongTien = ?,
                thanhToan = '2'
            WHERE maDon = ?";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt->execute([$diemSuDung, $tienGiamGia, $tienConLai, $orderId])) {
        throw new Exception("Lỗi khi cập nhật trạng thái thanh toán!");
    }
}

// Ghi log thanh toán
private function logPayment($orderId, $maKH, $method, $amount, $status, $note = '')
{
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "INSERT INTO payment_logs (maDon, maKH, phuongThuc, soTien, trangThai, ghiChu, thoiGian) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$orderId, $maKH, $method, $amount, $status, $note]);
    } catch (Exception $e) {
        error_log("Lỗi logPayment: " . $e->getMessage());
    }
}

// ================== CẬP NHẬT TRẠNG THÁI HOÀN THÀNH (CHO KTV) ==================
public function completeCashPayment($orderId, $maKTV)
{
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        // Kiểm tra đơn hàng tồn tại và đang ở trạng thái chờ xử lý
        $sql = "SELECT maDon, thanhToan FROM dondichvu WHERE maDon = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            throw new Exception("Đơn hàng không tồn tại!");
        }
        
        if ($order['thanhToan'] != 2) {
            throw new Exception("Đơn hàng không ở trạng thái chờ xử lý thanh toán tiền mặt!");
        }
        
        // Cập nhật lên trạng thái 3 (Đã hoàn thành)
        $sql = "UPDATE dondichvu 
                SET thanhToan = 3,
                    maKTV = ?,
                    ngayHoanThanh = NOW()
                WHERE maDon = ?";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$maKTV, $orderId])) {
            // Ghi log
            $this->logPayment($orderId, $maKTV, 'cash_completed', 0, 3, "KTV xác nhận đã nhận tiền mặt");
            
            return [
                'success' => true, 
                'message' => 'Đã xác nhận hoàn thành thanh toán tiền mặt!'
            ];
        } else {
            throw new Exception("Lỗi khi cập nhật trạng thái!");
        }
        
    } catch (Exception $e) {
        error_log("Lỗi completeCashPayment: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// ================== LẤY THÔNG TIN THANH TOÁN TIỀN MẶT ==================
public function getCashPaymentInfo($orderId)
{
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "SELECT 
                    dh.maDon,
                    dh.maKH,
                    kh.hoTen AS tenKH,
                    kh.sdt AS sdtKH,
                    dh.tongTien,
                    dh.diemSuDung,
                    dh.tienGiamGia,
                    dh.thanhToan,
                    dh.ngayThanhToan,
                    dh.thanhToan,
                    dh.maKTV,
                    ktv.hoTen AS tenKTV
                FROM dondichvu dh
                LEFT JOIN khachhang kh ON dh.maKH = kh.maKH
                LEFT JOIN nhanvien ktv ON dh.maKTV = ktv.maNV
                WHERE dh.maDon = ? AND dh.thanhToan IN (2, 3)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$orderId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Lỗi getCashPaymentInfo: " . $e->getMessage());
        return null;
    }
}

// Thêm vào class cPayment
public function processPointsPayment($orderId, $totalCost, $maKH)
{
    try {
        $order = $this->ordersModel->getOrderDetail($orderId, $maKH);
        
        if (!$order) {
            return ['success' => false, 'error' => 'Đơn hàng không tồn tại!'];
        }
        
        // Tính số điểm cần dùng
        $pointsNeeded = ceil($totalCost / 1000);
        $currentPoints = $this->ordersModel->getDiemTichLuy($maKH);
        
        if ($currentPoints < $pointsNeeded) {
            return ['success' => false, 'error' => 'Không đủ điểm để thanh toán!'];
        }
        
        // Cập nhật trạng thái thanh toán = 1 (Đã thanh toán bằng điểm)
        $this->updatePointsPaymentStatus($orderId, $pointsNeeded);
        
        // Trừ điểm tích lũy
        $this->ordersModel->updateCustomerPoints($maKH, -$pointsNeeded);
        
        // Ghi log
        $this->logPayment($orderId, $maKH, 'points', $totalCost, 1, "Thanh toán bằng $pointsNeeded điểm");
        
        return ['success' => true, 'message' => 'Thanh toán bằng điểm thành công!'];
        
    } catch (Exception $e) {
        error_log("Lỗi processPointsPayment: " . $e->getMessage());
        return ['success' => false, 'error' => 'Lỗi hệ thống: ' . $e->getMessage()];
    }
}

private function updatePointsPaymentStatus($orderId, $diemSuDung)
{
    $database = new Database();
    $conn = $database->getConnection();
    
    $sql = "UPDATE dondichvu 
            SET thanhToan = 1, 
                ngayThanhToan = NOW(),
                diemSuDung = ?,
                tienGiamGia = ?,
                thanhToan = '1'
            WHERE maDon = ?";
    
    $tienGiamGia = $diemSuDung * 1000;
    $stmt = $conn->prepare($sql);
    
    if (!$stmt->execute([$diemSuDung, $tienGiamGia, $orderId])) {
        throw new Exception("Lỗi khi cập nhật trạng thái thanh toán!");
    }
}
}
?>