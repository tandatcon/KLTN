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
            if (!$order || $order['thanhToan'] == 1) {
                throw new Exception("Đơn hàng không hợp lệ hoặc đã thanh toán");
            }

            // 2. Tính toán số tiền thực tế sau khi dùng điểm
            $tienGiamGia = $diemSuDung * 1000;
            $tienThanhToan = max(0, $amount - $tienGiamGia);

            if ($tienThanhToan <= 0) {
                // Nếu không cần thanh toán tiền mặt (dùng điểm đủ)
                return $this->processFreePayment($orderId, $maKH, $diemSuDung, $tienGiamGia);
            }

            // 3. Tạo URL thanh toán VNPay
            $vnpUrl = $this->createVNPayPaymentUrl($orderId, $tienThanhToan);

            // 4. Lưu thông tin thanh toán tạm thời
            $this->saveTempPayment($orderId, $maKH, $tienThanhToan, $diemSuDung);

            return ['success' => true, 'payment_url' => $vnpUrl];

        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
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

    // Lưu thông tin thanh toán tạm thời
    private function saveTempPayment($orderId, $maKH, $amount, $diemSuDung)
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

    // Xử lý kết quả return từ VNPay
    

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
        $vnp_TransactionStatus = $vnpData['vnp_TransactionStatus'] ?? '';

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

            // 1. Trừ điểm (nếu có dùng)
            if ($tempPayment['points_used'] > 0) {
                $this->ordersModel->updateCustomerPoints($tempPayment['customer_id'], -$tempPayment['points_used']);
            }

            // 2. Cộng điểm thưởng 1.5%
            $totalAmount = $tempPayment['amount'] + ($tempPayment['points_used'] * 1000);
            $diemNhanDuoc = round($totalAmount * 0.015 / 1000);
            if ($diemNhanDuoc > 0) {
                $this->ordersModel->updateCustomerPoints($tempPayment['customer_id'], $diemNhanDuoc);
            }

            // 3. Cập nhật đơn hàng thành công
            $this->ordersModel->updateOrderPaymentStatus(
                $orderId,
                1,
                $tempPayment['amount'],
                $tempPayment['points_used']
            );

            // 4. Xóa temp
            $this->clearTempPayment($orderId);

            return ['success' => true, 'order_id' => $orderId];

        } catch (Exception $e) {
            $this->clearTempPayment($orderId);
            return ['success' => false, 'error' => $e->getMessage()];
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
}
?>