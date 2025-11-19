<?php
require_once 'connect.php';

class mOrders
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Test kết nối
    public function testConnection()
    {
        try {
            $stmt = $this->conn->query("SELECT 1");
            return "Kết nối database thành công!";
        } catch (PDOException $e) {
            return "Lỗi kết nối: " . $e->getMessage();
        }
    }

    // Lấy danh sách đơn hàng theo khách hàng
    public function getOrdersByCustomer($maKH)
    {
        $sql = "
        SELECT 
            ddv.maDon,
            ddv.ngayDat,
            ddv.diemhen,
            ddv.ghiChu,
            ddv.trangThai,
            ddv.noiSuaChua,
            ddv.maKhungGio,
            ddv.maKTV,
            kg.gioBatDau,
            kg.gioKetThuc,
            COUNT(ctddv.maCTDon) as so_luong_thiet_bi,
            GROUP_CONCAT(DISTINCT tb.tenThietBi SEPARATOR ', ') as danh_sach_thiet_bi
        FROM dondichvu ddv
        LEFT JOIN chitietdondichvu ctddv ON ddv.maDon = ctddv.maDon
        LEFT JOIN thietbi tb ON ctddv.maThietBi = tb.maThietBi
        LEFT JOIN bangkhunggio kg ON ddv.maKhungGio = kg.maKhungGio
        WHERE ddv.maKH = ?
        GROUP BY ddv.maDon
        ORDER BY ddv.ngayDat DESC, ddv.maDon DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$maKH]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết đơn hàng
    public function getOrderDetail($maDon, $maKH = null)
    {
        $sql = "
        SELECT 
            ddv.*,
            kg.gioBatDau,
            kg.gioKetThuc,
            nv.hoTen as tenKTV,
            nv.sdt as sdtKTV,
            nv.email as emailKTV,
            kh.hoTen,
            kh.sdt,
            kh.email
        FROM dondichvu ddv
        LEFT JOIN bangkhunggio kg ON ddv.maKhungGio = kg.maKhungGio
        LEFT JOIN nguoidung nv ON ddv.maKTV = nv.maND AND nv.maVaiTro = 3
        LEFT JOIN nguoidung kh ON ddv.maKH = kh.maND AND kh.maVaiTro = 1
        WHERE ddv.maDon = ?";

        $params = [$maDon];
        if ($maKH) {
            $sql .= " AND ddv.maKH = ?";
            $params[] = $maKH;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy thiết bị trong đơn hàng
    public function getOrderDevices($maDon)
    {
        $sql = "
            SELECT 
                ctddv.*,
                tb.tenThietBi,
                tb.loaiThietBi
            FROM chitietdondichvu ctddv
            LEFT JOIN thietbi tb ON ctddv.maThietBi = tb.maThietBi
            WHERE ctddv.maDon = ?
            ORDER BY ctddv.maCTDon";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$maDon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hủy đơn hàng
    public function cancelOrder($maDon, $maKH)
    {
        $sqlCheck = "SELECT trangThai FROM dondichvu WHERE maDon = ? AND maKH = ?";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute([$maDon, $maKH]);
        $order = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            throw new Exception("Đơn hàng không tồn tại hoặc không thuộc quyền sở hữu");
        }

        if ($order['trangThai'] != 1) {
            throw new Exception("Chỉ có thể hủy đơn hàng ở trạng thái 'Đã đặt'");
        }

        $sqlUpdate = "UPDATE dondichvu SET trangThai = 0 WHERE maDon = ? AND maKH = ?";
        $stmtUpdate = $this->conn->prepare($sqlUpdate);
        $success = $stmtUpdate->execute([$maDon, $maKH]);

        if ($success) {
            $sqlUpdateDetail = "UPDATE chitietdondichvu SET trangThai = 0 WHERE maDon = ?";
            $stmtUpdateDetail = $this->conn->prepare($sqlUpdateDetail);
            $stmtUpdateDetail->execute([$maDon]);
            return true;
        }

        return false;
    }

    // Lấy thông tin khách hàng
    public function getCustomerInfo($maKH)
    {
        $sql = "SELECT maND, hoTen, sdt, email FROM nguoidung WHERE maND = ? and maVaiTro=1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$maKH]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin KTV
    public function getTechnicianInfo($maKTV)
    {
        if (!$maKTV) return null;

        $sql = "SELECT maND as maNV, hoTen, sdt, email
                FROM nguoidung 
                WHERE maND = ? AND maVaiTro = 3";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$maKTV]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết thiết bị đơn hàng
    public function getOrderDevicesDetail($order_id)
    {
        $sql = "SELECT 
                ctddv.*,
                tb.tenThietBi,
                tb.maThietBi
            FROM chitietdondichvu ctddv
            JOIN thietbi tb ON ctddv.maThietBi = tb.maThietBi
            WHERE ctddv.maDon = ?
            ORDER BY ctddv.maCTDon";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết sửa chữa theo thiết bị
    public function getDeviceRepairDetails($maDon, $maCTDon)
    {
        $sql = "SELECT * FROM chitietsuachua 
                WHERE maDon = ? AND maCTDon = ?  
                ORDER BY created_at ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$maDon, $maCTDon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy điểm tích lũy
    public function getDiemTichLuy($maKH)
    {
        $sql = "SELECT diemTichLuy FROM nguoidung WHERE maND = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$maKH]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int) $result['diemTichLuy'] : 0;
    }

    // Cập nhật điểm khách hàng
    public function updateCustomerPoints($maKH, $diemThayDoi)
    {
        if ($diemThayDoi >= 0) {
            $sql = "UPDATE nguoidung SET diemTichLuy = diemTichLuy + ? WHERE maND = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([abs($diemThayDoi), $maKH]);
        } else {
            $sql = "UPDATE nguoidung SET diemTichLuy = diemTichLuy - ? WHERE maND = ? AND diemTichLuy >= ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([abs($diemThayDoi), $maKH, abs($diemThayDoi)]);
        }
    }

    // Cập nhật trạng thái thanh toán đơn hàng
    public function updateOrderPaymentStatus($maDon, $thanhToan, $tongTien, $diemSuDung)
    {
        $sql = "UPDATE dondichvu 
                SET thanhToan = ?, 
                    tongTien = ?,
                    diemSuDung = ?,
                    ngayThanhToan = NOW() 
                WHERE maDon = ?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$thanhToan, $tongTien, $diemSuDung, $maDon]);
    }
}
?>