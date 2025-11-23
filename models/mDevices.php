<?php
require_once 'connect.php';

class mDevices
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Test kết nối
    public function mTestConnection()
    {
        try {
            $stmt = $this->conn->query("SELECT 1");
            return "Kết nối database thành công!";
        } catch (PDOException $e) {
            return "Lỗi kết nối: " . $e->getMessage();
        }
    }

    // Lấy danh sách thiết bị
    public function mGetDevices()
    {
        try {
            $sql = "SELECT * FROM thietbi ORDER BY maThietBi ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Devices Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy thiết bị theo ID
    public function mGetDevice($id)
    {
        try {
            $sql = "SELECT * FROM thietbi WHERE maThietBi = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Device Error: " . $e->getMessage());
            return null;
        }
    }

    // Lấy tất cả bảng giá
    public function mGetAllPrice()
    {
        try {
            $sql = "SELECT 
                        t.maThietBi,
                        t.tenThietBi,
                        h.maHang,
                        h.tenHang,
                        m.maMau,
                        m.tenMau,
                        g.maGia,
                        g.tenLoi,
                        g.moTa,
                        g.gia,
                        g.thoiGianSua
                    FROM banggiasuachua g
                    JOIN mausanpham m ON g.maMau = m.maMau
                    JOIN hangsanxuat h ON m.maHang = h.maHang
                    JOIN thietbi t ON h.maThietBi = t.maThietBi
                    ORDER BY t.maThietBi, h.tenHang, 
                             CASE WHEN m.tenMau = 'Mẫu khác' THEN 1 ELSE 0 END,
                             m.tenMau, g.tenLoi";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get All Price Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy giá theo thiết bị
    public function mGetPriceByDevice($deviceId)
    {
        try {
            $sql = "SELECT 
                        t.maThietBi,
                        t.tenThietBi,
                        h.maHang,
                        h.tenHang,
                        m.maMau,
                        m.tenMau,
                        g.maGia,
                        g.tenLoi,
                        g.moTa,
                        g.gia,
                        g.thoiGianSua
                    FROM banggiasuachua g
                    JOIN mausanpham m ON g.maMau = m.maMau
                    JOIN hangsanxuat h ON m.maHang = h.maHang
                    JOIN thietbi t ON h.maThietBi = t.maThietBi
                    WHERE t.maThietBi = ?
                    ORDER BY h.tenHang, 
                             CASE WHEN m.tenMau = 'Mẫu khác' THEN 1 ELSE 0 END,
                             m.tenMau, g.tenLoi";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$deviceId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Price By Device Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy hãng theo thiết bị
    public function mGetBrands($deviceId)
    {
        try {
            $sql = "SELECT maHang, tenHang FROM hangsanxuat 
                    WHERE maThietBi = ? ORDER BY tenHang";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$deviceId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Brands Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy mẫu theo hãng
    public function mGetModels($brandId)
    {
        try {
            $sql = "SELECT maMau, tenMau FROM mausanpham 
                    WHERE maHang = ? 
                    ORDER BY CASE WHEN tenMau = 'Mẫu khác' THEN 1 ELSE 0 END, tenMau";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$brandId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Models Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy giá theo mẫu
    public function mGetPriceByModel($modelId)
    {
        try {
            // Query đơn giản hơn - chỉ lấy từ banggiasuachua
            echo $modelId;
            echo $sql = "SELECT 
                        maGia,
                        tenLoi,
                        moTa,
                        gia,
                        thoiGianSua,
                        CONCAT(FORMAT(gia, 0), ' VND') as khoangGia
                    FROM banggiasuachua 
                    WHERE maMau = ?
                    ORDER BY tenLoi";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$modelId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("Get Price By Model Error: " . $e->getMessage());
            return [];
        }
    }

    // Thêm thiết bị
    public function mAddDevice($name)
    {
        try {
            $sql = "INSERT INTO thietbi (tenThietBi) VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$name]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Add Device Error: " . $e->getMessage());
            return false;
        }
    }

    // Sửa thiết bị
    public function mUpdateDevice($id, $name)
    {
        try {
            $sql = "UPDATE thietbi SET tenThietBi = ? WHERE maThietBi = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$name, $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Update Device Error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa thiết bị
    public function mDeleteDevice($id)
    {
        try {
            $sql = "DELETE FROM thietbi WHERE maThietBi = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Delete Device Error: " . $e->getMessage());
            return false;
        }
    }

    // Lấy thống kê
    public function mGetStats()
    {
        try {
            $sql = "SELECT 
                        t.maThietBi,
                        t.tenThietBi,
                        COUNT(DISTINCT h.maHang) as so_hang,
                        COUNT(DISTINCT m.maMau) as so_mau,
                        COUNT(DISTINCT g.maGia) as so_dich_vu
                    FROM thietbi t
                    LEFT JOIN hangsanxuat h ON t.maThietBi = h.maThietBi
                    LEFT JOIN mausanpham m ON h.maHang = m.maHang
                    LEFT JOIN banggiasuachua g ON m.maMau = g.maMau
                    GROUP BY t.maThietBi, t.tenThietBi
                    ORDER BY t.maThietBi";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Stats Error: " . $e->getMessage());
            return [];
        }
    }
}
?>