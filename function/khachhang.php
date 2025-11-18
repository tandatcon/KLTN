<?php
require_once __DIR__ . '/ketnoi.php';

class KhachHang {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function layKHByID($maKH) {
        $sql = "SELECT * FROM nguoidung WHERE maND = ? AND maVaiTro = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maKH]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function layKHBySDT($sdt) {
        $sql = "SELECT * FROM nguoidung WHERE sdt = ? AND maVaiTro = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$sdt]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function capNhatKH($id, $hoTen, $sdt, $email, $diaChi) {
        $sql = "UPDATE nguoidung SET 
                    hoTen = ?, 
                    sdt = ?, 
                    email = ?, 
                    diaChi = ? 
                WHERE maND = ? AND maVaiTro = '1'";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$hoTen, $sdt, $email, $diaChi, $id]);
    }

    public function kiemTraSDTTonTai($sdt, $maKHHienTai = null) {
        try {
            $sql = "SELECT maND FROM nguoidung WHERE sdt = ? AND maVaiTro = 1";
            $params = [$sdt];
            
            if ($maKHHienTai) {
                $sql .= " AND maND != ?";
                $params[] = $maKHHienTai;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (PDOException $e) {
            error_log("Lỗi kiểm tra số điện thoại: " . $e->getMessage());
            return false;
        }
    }

}




