<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/User.php';

class OrderController
{
    private $db;
    private $orderModel;
    private $userModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->orderModel = new Order($db);
        $this->userModel = new User($db);
    }

    /**
     * Hiển thị trang danh sách đơn hàng
     */
    public function showOrders()
    {
        session_start();

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để xem đơn hàng!";
            header("Location: " . url('login'));
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Lấy thông tin user
        $userInfo = $this->userModel->getUserById($user_id);

        // Lấy danh sách đơn hàng
        $orders = $this->orderModel->getOrdersByUserId($user_id);

        // Lấy tên thiết bị từ CSDL
        $deviceNames = $this->orderModel->getDeviceNamesFromDB();

        return [
            'userInfo' => $userInfo,
            'orders' => $orders,
            'deviceNames' => $deviceNames
        ];
    }


    //Hiển thị trang chi tiết đơn hàng

    public function showOrderDetail($order_id)
    {


        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để xem chi tiết đơn hàng!";
            header("Location: " . url('login'));
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Lấy chi tiết đơn hàng (có kiểm tra quyền truy cập)
        $order = $this->orderModel->getOrderDetail($order_id, $user_id);

        if (!$order) {
            $_SESSION['error'] = "Đơn hàng không tồn tại hoặc bạn không có quyền truy cập!";
            header("Location: " . url('my_orders'));
            exit;
        }

        // Lấy thông tin user
        $userInfo = $this->userModel->getUserById($user_id);

        // Lấy lịch sử đơn hàng
        $orderHistory = $this->orderModel->getOrderHistory($order_id);

        // Lấy tên thiết bị từ CSDL
        $deviceNames = $this->orderModel->getDeviceNamesFromDB();

        // Lấy thông tin chi tiết theo từng thiết bị (gộp tất cả)
        $deviceDetails = $this->getDeviceDetails($order_id);

        return [
            'order' => $order,
            'userInfo' => $userInfo,
            'orderHistory' => $orderHistory,
            'deviceNames' => $deviceNames,
            'deviceDetails' => $deviceDetails // Đảm bảo có key này
        ];
    }

    /**
     * Lấy thông tin chi tiết theo từng thiết bị (gộp tất cả thông tin)
     */
    /**
     * Lấy thông tin chi tiết theo từng thiết bị (gộp tất cả thông tin)
     */
    public function getDeviceDetails($orderId)
    {
        try {
            $query = "SELECT 
            ctddv.*,
            tb.tenThietBi as ten_thiet_bi,
            
            u.hoTen as technician_name,
            u.sdt as technician_phone,
            cts.loiSuaChua,
            ctddv.loai_thietbi, tb.maThietBi,
            cts.chiPhi,
            ctddv.chuandoanKTV
    
          FROM chitietdondichvu ctddv
          LEFT JOIN thietbi tb ON ctddv.loai_thietbi = tb.maThietBi
          LEFT JOIN DonDichVu ddv ON ctddv.maDon = ddv.maDon
          LEFT JOIN nguoidung u ON ddv.id_nhanvien = u.maND
          LEFT JOIN chitietsuachua cts ON cts.maCTDon = ctddv.maCTDon AND cts.maThietBi = ctddv.loai_thietbi
                  WHERE ctddv.maDon = ?
                  ORDER BY ctddv.maCTDon";

            $stmt = $this->db->prepare($query);
            $stmt->execute([$orderId]);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (PDOException $e) {
            error_log("Error getting device details: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy chi tiết sửa chữa cho đơn hàng
     */
    public function getRepairDetails($orderId)
    {
        try {
            $query = "SELECT 
                        cts.*, 
                        tb.tenThietBi as ten_thiet_bi,
                        tb.mota as thong_tin_thiet_bi
                      FROM chitietsuachua cts 
                      LEFT JOIN thietbi tb ON cts.ma_thiet_bi = tb.maThietBi 
                      WHERE cts.ma_don = ? 
                      ORDER BY cts.ma_chi_tiet";

            $stmt = $this->db->prepare($query);
            $stmt->execute([$orderId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error getting repair details: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thông tin kỹ thuật viên của đơn hàng
     */
    public function getOrderTechnician($orderId)
    {
        try {
            $query = "SELECT u.hoTen, u.sdt, u.email
                      FROM nguoidung u 
                      INNER JOIN chitietdondichvu ddv ON u.maND = ddv.id_nhanvien 
                      WHERE ddv.maDon = ? AND u.maVaiTro = 3";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$orderId]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting order technician: " . $e->getMessage());
            return null;
        }
    }


    // Xem chi tiet don cho KTV
// Thêm vào OrderController.php

    /**
     * Lấy tất cả chi tiết đơn dịch vụ và thông tin sửa chữa
     */
    public function layTatCaChiTietDonDichVu($maDon)
    {
        try {
            $sql = "SELECT DISTINCT
            ctdd.maCTDon,
            ctdd.maDon,
            ctdd.id_nhanvien, 
            ctdd.loai_thietbi,
            ctdd.phienban,
            ctdd.gioBatDau,
            ctdd.gioKetThuc,
            ctdd.mota_tinhtrang,
            ctdd.quyetDinhSC,
            ctdd.trangThai,
            ctdd.minhchung_den,
            ctdd.minhchung_thietbi,
            tb.maThietBi,
            tb.tenThietBi,
            b.hoTen as tenKTV
        FROM chitietdondichvu ctdd 
        JOIN thietbi tb ON tb.maThietBi = ctdd.loai_thietbi
        JOIN nguoidung b ON ctdd.id_nhanvien = b.maND
        WHERE ctdd.maDon = ?";
                 

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("layTatCaChiTietDonDichVu Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy chi tiết đơn hàng cho KTV (cập nhật hoàn toàn)
     */
    public function layChiTietDonChoKTV($maDon, $idKTV)
    {
        try {
            // Kiểm tra KTV có được phân công đơn này không và lấy tên KTV
            $sqlKiemTra = "SELECT 1, b.hoTen as tenKTV 
                       FROM chitietdondichvu a
                       JOIN nguoidung b ON a.id_nhanvien = b.maND
                       WHERE a.maDon = ? AND a.id_nhanvien = ?";
            $stmtKiemTra = $this->db->prepare($sqlKiemTra);
            $stmtKiemTra->execute([$maDon, $idKTV]);

            $ktvInfo = $stmtKiemTra->fetch(PDO::FETCH_ASSOC);

            if (!$ktvInfo) {
                return false; // KTV không có quyền xem đơn này
            }

            $tenKTV = $ktvInfo['tenKTV'];

            // Lấy thông tin cơ bản đơn hàng
            $sqlDonHang = "SELECT dd.*, kh.hoTen as customer_name, kh.sdt, kh.email
                       FROM dondichvu dd
                       JOIN nguoidung kh ON dd.user_id = kh.maND
                       WHERE dd.maDon = ?";

            $stmtDonHang = $this->db->prepare($sqlDonHang);
            $stmtDonHang->execute([$maDon]);
            $donHang = $stmtDonHang->fetch(PDO::FETCH_ASSOC);

            if (!$donHang) {
                return false;
            }

            // Lấy tất cả chi tiết đơn dịch vụ và thông tin sửa chữa
            $chiTietDonDichVu = $this->layTatCaChiTietDonDichVu($maDon);

            return [
                'donHang' => $donHang,
                'chiTietDonDichVu' => $chiTietDonDichVu,
                'thongTinKhachHang' => [
                    'hoTen' => $donHang['customer_name'],
                    'sdt' => $donHang['sdt'],
                    'email' => $donHang['email']
                ],
                'thongTinKTV' => [
                    'tenKTV' => $tenKTV
                ]
            ];

        } catch (Exception $e) {
            error_log("layChiTietDonChoKTV Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy chi tiết sửa chữa theo đơn hàng
     */
    public function layChiTietSuaChuaTheoDon($maDon)
    {
        try {
            $sql = "SELECT rd.*, tb.tenThietBi
                FROM repair_details rd
                LEFT JOIN thietbi tb ON rd.maThietBi = tb.maThietBi
                WHERE rd.maDon = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("layChiTietSuaChuaTheoDon Error: " . $e->getMessage());
            return [];
        }
    }
    //Cap nhat trang thai huy don
    // Thêm vào OrderController.php
public function huyDonHang($orderId) {
    try {
        // Kiểm tra đơn hàng có thuộc user hiện tại không
        $userId = $_SESSION['user_id'];
        $checkSql = "SELECT trangThai FROM dondichvu WHERE maDon = ? AND user_id = ?";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([$orderId, $userId]);
        $order = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng!';
            return false;
        }
        
        // Chỉ cho hủy đơn trạng thái "Đã đặt" (1)
        if ((int)$order['trangThai'] !== 1) {
            $_SESSION['error'] = 'Chỉ có thể hủy đơn hàng đang ở trạng thái "Đã đặt"!';
            return false;
        }
        
        // Cập nhật trạng thái thành "Đã hủy" (0)
        $updateSql = "UPDATE dondichvu SET trangThai = 0 WHERE maDon = ?";
        
        $updateStmt = $this->db->prepare($updateSql);
        $result = $updateStmt->execute([$orderId]);
        
        if ($result) {
            $_SESSION['success'] = 'Đã hủy đơn hàng #' . $orderId . ' thành công!';
            return true;
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi hủy đơn hàng ne!';
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Lỗi hủy đơn: " . $e->getMessage());
        $_SESSION['error'] = 'Có lỗi xảy ra khi hủy đơn hàng!';
        return false;
    }
}
public function getTechnicianInfo($employeeId) {
    try {
        $sql = "SELECT * FROM nguoidung WHERE maND = ? and maVaiTro='3'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$employeeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("getEmployeeById Error: " . $e->getMessage());
        return null;
    }
}



}
?>