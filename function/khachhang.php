<?php
require_once __DIR__ . '/ketnoi.php';

class KhachHang {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function layKHByID($maKH) {
        // Sửa lỗi cú pháp: "form" -> "from"
        $sql = "SELECT * FROM nguoidung WHERE maND = ? AND maVaiTro = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maKH]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}


