<?php
require_once 'models/mOrders.php';

class cOrders
{
    private $model;

    public function __construct()
    {
        $this->model = new mOrders();
    }

    // Test kết nối
    public function testConnection()
    {
        return $this->model->testConnection();
    }

    // Lấy danh sách đơn hàng của khách hàng
    public function getOrdersByCustomer($maKH)
    {
        return $this->model->getOrdersByCustomer($maKH);
    }

    // Lấy chi tiết đơn hàng
    public function getOrderDetail($maDon, $maKH = null)
    {
        return $this->model->getOrderDetail($maDon, $maKH);
    }

    // Lấy thiết bị trong đơn hàng
    public function getOrderDevices($maDon)
    {
        return $this->model->getOrderDevices($maDon);
    }

    // Hủy đơn hàng
    public function cancelOrder($maDon, $maKH)
    {
        return $this->model->cancelOrder($maDon, $maKH);
    }

    // Lấy thông tin khách hàng
    public function getCustomerInfo($maKH)
    {
        return $this->model->getCustomerInfo($maKH);
    }

    // Lấy thông tin KTV
    public function getTechnicianInfo($maKTV)
    {
        return $this->model->getTechnicianInfo($maKTV);
    }

    // Lấy chi tiết thiết bị đơn hàng
    public function getOrderDevicesDetail($order_id)
    {
        return $this->model->getOrderDevicesDetail($order_id);
    }

    // Lấy chi tiết sửa chữa theo thiết bị
    public function getDeviceRepairDetails($maDon, $maCTDon)
    {
        return $this->model->getDeviceRepairDetails($maDon, $maCTDon);
    }

    // Lấy điểm tích lũy
    public function getDiemTichLuy($maKH)
    {
        return $this->model->getDiemTichLuy($maKH);
    }

    // Cập nhật điểm khách hàng
    public function updateCustomerPoints($maKH, $diemThayDoi)
    {
        return $this->model->updateCustomerPoints($maKH, $diemThayDoi);
    }

    // Cập nhật trạng thái thanh toán đơn hàng
    public function updateOrderPaymentStatus($maDon, $thanhToan, $tongTien, $diemSuDung)
    {
        return $this->model->updateOrderPaymentStatus($maDon, $thanhToan, $tongTien, $diemSuDung);
    }

    // Lấy tất cả dữ liệu cho trang my_orders
    public function getOrdersData($maKH)
    {
        $data = [];

        // Thông tin khách hàng
        $data['userInfo'] = $this->model->getCustomerInfo($maKH);

        // Danh sách đơn hàng
        $data['orders'] = $this->model->getOrdersByCustomer($maKH);

        return $data;
    }

    // Lấy đơn hàng hôm nay của KTV
    public function getDonHomNayByKTV($ktvId)
    {
        return $this->model->getDonHomNayByKTV($ktvId);
    }

    // Lấy tất cả đơn phân công cho KTV
    public function getDonPhanCongByKTV($ktvId)
    {
        return $this->model->getDonPhanCongByKTV($ktvId);
    }

    // Lấy chi tiết đơn dịch vụ
    public function layTatCaChiTietDonDichVu($maDon)
    {
        return $this->model->layTatCaChiTietDonDichVu($maDon);
    }

    // Lấy chi tiết đơn hàng cho KTV
    public function layChiTietDonChoKTV($maDon, $idKTV)
    {
        return $this->model->layChiTietDonChoKTV($maDon, $idKTV);
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

    // Lấy thông tin thanh toán chi tiết
    public function getThongTinThanhToan($maDon)
    {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "SELECT ddv.maDon, ddv.tongTien, ddv.thanhToan, ddv.diemSuDung, ddv.diemTichLuy,
                       ddv.ngayThanhToan, kh.diemTichLuy as diemHienCo
                FROM dondichvu ddv
                LEFT JOIN khachhang kh ON ddv.maKH = kh.maND
                WHERE ddv.maDon = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$maDon]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tính điểm tích lũy nhận được
    public function tinhDiemTichLuy($tienThanhToan)
    {
        // 1.5% tổng thanh toán, chia cho 1000 để quy đổi sang điểm
        $diem = ($tienThanhToan * 0.015) / 1000;
        return round($diem);
    }

    // Thực hiện thanh toán (giữ lại để tương thích)
    public function thucHienThanhToan($maDon, $tienThanhToan, $diemSuDung, $maKH)
    {
        try {
            // 1. Trừ điểm tích lũy
            if ($diemSuDung > 0) {
                $this->model->updateCustomerPoints($maKH, -$diemSuDung);
            }

            // 2. Cộng điểm mới
            $diemNhanDuoc = $this->tinhDiemTichLuy($tienThanhToan);
            if ($diemNhanDuoc > 0) {
                $this->model->updateCustomerPoints($maKH, $diemNhanDuoc);
            }

            // 3. Cập nhật trạng thái thanh toán
            $result = $this->model->updateOrderPaymentStatus($maDon, 1, $tienThanhToan, $diemSuDung);

            // 4. Ghi log lịch sử thanh toán
            if ($result) {
                $this->ghiLichSuThanhToan($maDon, $tienThanhToan, $diemSuDung, $diemNhanDuoc);
            }

            return $result;

        } catch (Exception $e) {
            error_log("Lỗi thanh toán: " . $e->getMessage());
            return false;
        }
    }

    // Ghi lịch sử thanh toán
    private function ghiLichSuThanhToan($maDon, $soTien, $diemSuDung, $diemNhanDuoc)
    {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "INSERT INTO lichsuthanhtoan 
                (maDon, soTien, diemSuDung, diemNhanDuoc, ngayThanhToan) 
                VALUES (?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([$maDon, $soTien, $diemSuDung, $diemNhanDuoc]);
    }

    // Lấy lịch sử thanh toán của đơn hàng
    public function getLichSuThanhToan($maDon)
    {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "SELECT * FROM lichsuthanhtoan WHERE maDon = ? ORDER BY ngayThanhToan DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$maDon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách giá sửa chữa
    public function getPriceDetail($maThietBi)
    {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT maGia, maThietBi, chitietloi, khoangGia, ghiChu
                 FROM banggiaSC 
                 WHERE maThietBi = ? 
                 ORDER BY maGia ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute([$maThietBi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chẩn đoán thiết bị
    public function getDeviceDiagnosis($maDon, $maCTDon)
    {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "SELECT 
                    chuandoanKTV as tinh_trang_thuc_te,
                    baoGiaSC as chi_phi,
                    trangThai,
                    quyetDinhSC,
                    lyDoTC
                FROM chitietdondichvu 
                WHERE maDon = ? AND maCTDon = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$maDon, $maCTDon]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả chi tiết sửa chữa của đơn hàng
    public function getAllRepairDetails($maDon)
    {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "SELECT 
                ctsc.*,
                ctddv.maCTDon,
                ctddv.maThietBi,
                tb.tenThietBi
            FROM chitietsuachua ctsc
            JOIN chitietdondichvu ctddv ON ctsc.maCTDon = ctddv.maCTDon
            LEFT JOIN thietbi tb ON ctddv.maThietBi = tb.maThietBi
            WHERE ctsc.maDon = ?
            ORDER BY ctddv.maCTDon, ctsc.created_at ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$maDon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy lịch sử thao tác của đơn hàng
    public function getServiceActions($maDon)
    {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "SELECT lstd.*, nd.hoTen as technician_name 
                FROM lich_su_thaotac lstd 
                LEFT JOIN nguoidung nd ON lstd.maKTV = nd.maND 
                WHERE lstd.maDon = ? 
                ORDER BY lstd.thoi_gian_tao DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$maDon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>