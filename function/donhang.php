<?php
class DonHangService
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Lấy thông tin đơn hàng của khách hàng
     */
    public function getOrdersByCustomer($maKH)
    {
        try {
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
        ORDER BY ddv.ngayDat DESC, ddv.maDon DESC
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maKH]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Lỗi khi lấy đơn hàng: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thông tin chi tiết một đơn hàng
     */
    public function getOrderDetail($maDon, $maKH = null)
    {
        try {
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
            LEFT JOIN nguoidung nv ON ddv.maKTV = nv.maND AND nv.maVaiTro = 3  -- KTV
            LEFT JOIN nguoidung kh ON ddv.maKH = kh.maND AND kh.maVaiTro = 1    -- Khách hàng
            WHERE ddv.maDon = ?
        ";

            $params = [$maDon];
            if ($maKH) {
                $sql .= " AND ddv.maKH = ?";
                $params[] = $maKH;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Lỗi khi lấy chi tiết đơn hàng: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy danh sách thiết bị trong đơn hàng
     */
    public function getOrderDevices($maDon)
    {
        try {
            $sql = "
                SELECT 
                    ctddv.*,
                    tb.tenThietBi,
                    tb.loaiThietBi
                FROM chitietdondichvu ctddv
                LEFT JOIN thietbi tb ON ctddv.maThietBi = tb.maThietBi
                WHERE ctddv.maDon = ?
                ORDER BY ctddv.maCTDon
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Lỗi khi lấy thiết bị đơn hàng: " . $e->getMessage());
            return [];
        }
    }


    /**
     * Hủy đơn hàng
     */
    public function cancelOrder($maDon, $maKH)
    {
        try {
            // Kiểm tra đơn hàng thuộc về khách hàng
            $sqlCheck = "SELECT trangThai FROM dondichvu WHERE maDon = ? AND maKH = ?";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute([$maDon, $maKH]);
            $order = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                throw new Exception("Đơn hàng không tồn tại hoặc không thuộc quyền sở hữu");
            }

            // Chỉ cho phép hủy đơn có trạng thái "Đã đặt" (1)
            if ($order['trangThai'] != 1) {
                throw new Exception("Chỉ có thể hủy đơn hàng ở trạng thái 'Đã đặt'");
            }

            // Cập nhật trạng thái đơn hàng về "Đã hủy" (0)
            $sqlUpdate = "UPDATE dondichvu SET trangThai = 0 WHERE maDon = ? AND maKH = ?";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $success = $stmtUpdate->execute([$maDon, $maKH]);

            if ($success) {
                // Cập nhật trạng thái chi tiết đơn hàng
                $sqlUpdateDetail = "UPDATE chitietdondichvu SET trangThai = 0 WHERE maDon = ?";
                $stmtUpdateDetail = $this->db->prepare($sqlUpdateDetail);
                $stmtUpdateDetail->execute([$maDon]);

                return true;
            }

            return false;

        } catch (Exception $e) {
            error_log("Lỗi khi hủy đơn hàng: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Lấy thông tin khách hàng
     */
    public function getCustomerInfo($maKH)
    {
        try {
            $sql = "SELECT maND, hoTen, sdt, email FROM nguoidung WHERE maND = ? and maVaiTro=1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maKH]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Lỗi khi lấy thông tin khách hàng: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy tên thiết bị từ danh sách mã
     */
    public function getDeviceNames($deviceIds)
    {
        try {
            if (empty($deviceIds))
                return [];

            $placeholders = str_repeat('?,', count($deviceIds) - 1) . '?';
            $sql = "SELECT maThietBi, tenThietBi FROM thietbi WHERE maThietBi IN ($placeholders)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($deviceIds);

            $result = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[$row['maThietBi']] = $row['tenThietBi'];
            }

            return $result;

        } catch (Exception $e) {
            error_log("Lỗi khi lấy tên thiết bị: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy tất cả dữ liệu cần thiết cho trang my_orders
     */
    public function getOrdersData($maKH)
    {
        $data = [];

        // Thông tin khách hàng
        $data['userInfo'] = $this->getCustomerInfo($maKH);

        // Danh sách đơn hàng
        $data['orders'] = $this->getOrdersByCustomer($maKH);

        // Lấy tất cả mã thiết bị để lấy tên
        $allDeviceIds = [];
        foreach ($data['orders'] as $order) {
            if (!empty($order['danh_sach_thiet_bi'])) {
                $deviceIds = array_filter(explode(', ', $order['danh_sach_thiet_bi']));
                $allDeviceIds = array_merge($allDeviceIds, $deviceIds);
            }
        }

        // Tên thiết bị
        $data['deviceNames'] = $this->getDeviceNames(array_unique($allDeviceIds));

        return $data;
    }

    public function getOrderTechnician($orderId)
    {
        try {
            $query = "SELECT u.hoTen, u.sdt, u.email
                      FROM nguoidung u 
                      INNER JOIN dondichvu ddv ON u.maND = ddv.maKTV
                      WHERE u.maND = ? AND u.maVaiTro = 3";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$orderId]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting order technician: " . $e->getMessage());
            return null;
        }
    }


    //----------------------------------------------------------------------------


    ///--------------------------------------------------------------------------
    /**
     * Lấy thông tin KTV theo mã KTV
     */
    public function getTechnicianInfo($maKTV)
    {
        try {
            if (!$maKTV)
                return null;

            $sql = "
                SELECT maND as maNV, hoTen, sdt, email
                FROM nguoidung 
                WHERE maND = ? AND maVaiTro = 3
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maKTV]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Lỗi khi lấy thông tin KTV: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy chi tiết thiết bị với thông tin đầy đủ
     */
    public function getOrderDevicesDetail($order_id)
    {
        try {
            $sql = "SELECT 
                    ctddv.*,
                    tb.tenThietBi,
                    tb.maThietBi
                FROM chitietdondichvu ctddv
                JOIN thietbi tb ON ctddv.maThietBi = tb.maThietBi
                WHERE ctddv.maDon = ?
                ORDER BY ctddv.maCTDon";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$order_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Lỗi khi lấy chi tiết thiết bị: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy tất cả chi tiết sửa chữa của đơn hàng
     */
    public function getAllRepairDetails($maDon)
    {
        try {
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

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Lỗi khi lấy chi tiết sửa chữa: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy chẩn đoán của thiết bị
     */
    public function getDeviceDiagnosis($maDon, $maCTDon)
    {
        try {
            $sql = "SELECT 
                        chuandoanKTV as tinh_trang_thuc_te,
                        baoGiaSC as chi_phi,
                        trangThai,
                        quyetDinhSC,
                        lyDoTC
                    FROM chitietdondichvu 
                    WHERE maDon = ? AND maCTDon = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon, $maCTDon]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Lỗi khi lấy chẩn đoán thiết bị: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy chi tiết sửa chữa theo thiết bị
     */
    public function getDeviceRepairDetails($maDon, $maCTDon)
    {
        try {
            $sql = "SELECT * FROM chitietsuachua 
                    WHERE maDon = ? AND maCTDon = ?  
                    ORDER BY created_at ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon, $maCTDon]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Lỗi khi lấy chi tiết sửa chữa: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy lịch sử thao tác của đơn hàng
     */
    public function getServiceActions($maDon)
    {
        try {
            $sql = "SELECT lstd.*, nd.hoTen as technician_name 
                    FROM lich_su_thaotac lstd 
                    LEFT JOIN nguoidung nd ON lstd.maKTV = nd.maND 
                    WHERE lstd.maDon = ? 
                    ORDER BY lstd.thoi_gian_tao DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Lỗi khi lấy lịch sử thao tác: " . $e->getMessage());
            return [];
        }
    }

    // Lấy đơn hôm nay được phân công cho KTV
    public function getDonHomNayByKTV($ktvId)
    {
        try {
            $sql = "SELECT DISTINCT  dd.*, 
            (SELECT COUNT(*) FROM chitietdondichvu WHERE maDon = dd.maDon) as loai_thietbi,
            kh.hoTen as customer_name, kh.sdt
            FROM dondichvu dd
            JOIN nguoidung kh ON dd.maKH = kh.maND
            WHERE
            dd.MaKTV = ? and
            DATE(dd.ngayDat) = CURDATE()
            AND dd.trangThai = 1
            ORDER BY dd.ngayDat DESC
            LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ktvId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("getDonHomNayByKTV Error: " . $e->getMessage());
            return [];
        }
    }
    // Lấy tất cả đơn được phân công cho KTV
    public function getDonPhanCongByKTV($ktvId)
    {
        try {
            $sql = "SELECT dd.*, dd.MaKTV, kh.hoTen as customer_name ,kh.sdt,tb.tenThietBi
                FROM dondichvu dd
                JOIN chitietdondichvu ctdd ON dd.maDon = ctdd.maDon
                JOIN nguoidung kh ON dd.MaKH = kh.maND
                JOIN thietbi tb on tb.maThietBi=ctdd.MaThietBi
                WHERE dd.MaKTV = ? 
                AND dd.trangThai IN ('1', '2', '3')
                ORDER BY dd.ngayDat DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ktvId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("getDonPhanCongByKTV Error: " . $e->getMessage());
            return [];
        }
    }
    public function layTatCaChiTietDonDichVu($maDon)
    {
        try {
            $sql = "SELECT DISTINCT
            ctdd.maCTDon,
            ctdd.maDon,
            ctdd.MaThietBi,
            ctdd.phienban,
            ctdd.gioBatDau,
            ctdd.gioKetThuc,
            ctdd.motaTinhTrang,
            ctdd.quyetDinhSC,
            ctdd.trangThai,
            ctdd.minhchung_den,
            ctdd.minhchung_thietbi,
            tb.maThietBi,
            tb.tenThietBi,
            b.hoTen as tenKTV
        FROM chitietdondichvu ctdd 
        JOIN thietbi tb ON tb.maThietBi = ctdd.MaThietBi
        JOIN dondichvu dd on dd.maDon=ctdd.maDon
        JOIN nguoidung b ON dd.MaKTV = b.maND
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
                       FROM dondichvu a
                       JOIN nguoidung b ON a.MaKTV = b.maND
                       WHERE a.maDon = ? AND a.MaKTV = ?";
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
                       JOIN nguoidung kh ON dd.MaKH = kh.maND
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














    public function getDiemTichLuy($maKH)
    {
        try {
            $sql = "SELECT diemTichLuy FROM khachhang WHERE maND = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maKH]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? (int) $result['diemTichLuy'] : 0;
        } catch (PDOException $e) {
            error_log("Lỗi lấy điểm tích lũy: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Thực hiện thanh toán đơn hàng
     */
    public function thucHienThanhToan($maDon, $tienThanhToan, $diemSuDung, $maKH)
    {
        $this->db->beginTransaction();

        try {
            // 1. Tính điểm tích lũy nhận được (1.5% tổng tiền thanh toán thực tế)
            $diemNhanDuoc = $this->tinhDiemTichLuy($tienThanhToan);

            // 2. Cập nhật điểm tích lũy cho khách hàng
            $this->capNhatDiemTichLuy($maKH, $diemSuDung, $diemNhanDuoc);

            // 3. Cập nhật trạng thái thanh toán cho đơn hàng
            $sqlUpdateDon = "UPDATE dondichvu 
                            SET thanhToan = 1, 
                                tongTien = ?,
                                diemSuDung = ?,
                                diemTichLuy = ?,
                                ngayThanhToan = NOW() 
                            WHERE maDon = ?";

            $stmt = $this->db->prepare($sqlUpdateDon);
            $stmt->execute([$tienThanhToan, $diemSuDung, $diemNhanDuoc, $maDon]);

            // 4. Ghi log lịch sử thanh toán
            $this->ghiLichSuThanhToan($maDon, $tienThanhToan, $diemSuDung, $diemNhanDuoc);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Lỗi thanh toán: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tính điểm tích lũy nhận được (1.5% tổng thanh toán)
     */
    private function tinhDiemTichLuy($tienThanhToan)
    {
        // 1.5% tổng thanh toán, chia cho 1000 để quy đổi sang điểm
        // 1 điểm = 1,000 VND giá trị thực
        $diem = ($tienThanhToan * 0.015) / 1000;
        return round($diem); // Làm tròn đến điểm nguyên
    }

    /**
     * Cập nhật điểm tích lũy cho khách hàng
     */
    private function capNhatDiemTichLuy($maKH, $diemSuDung, $diemNhanDuoc)
    {
        // Điểm mới = Điểm hiện tại - Điểm sử dụng + Điểm nhận được
        $sql = "UPDATE khachhang 
                SET diemTichLuy = diemTichLuy - ? + ? 
                WHERE maND = ? AND diemTichLuy >= ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$diemSuDung, $diemNhanDuoc, $maKH, $diemSuDung]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("Không đủ điểm tích lũy để thực hiện thanh toán");
        }
    }

    /**
     * Ghi lịch sử thanh toán
     */
    private function ghiLichSuThanhToan($maDon, $tienThanhToan, $diemSuDung, $diemNhanDuoc)
    {
        $sql = "INSERT INTO lichsuthanhtoan 
                (maDon, soTien, diemSuDung, diemNhanDuoc, ngayThanhToan) 
                VALUES (?, ?, ?, ?, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maDon, $tienThanhToan, $diemSuDung, $diemNhanDuoc]);
    }

    /**
     * Lấy lịch sử thanh toán của đơn hàng
     */
    public function getLichSuThanhToan($maDon)
    {
        try {
            $sql = "SELECT * FROM lichsuthanhtoan WHERE maDon = ? ORDER BY ngayThanhToan DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy lịch sử thanh toán: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thông tin thanh toán chi tiết của đơn hàng
     */
    public function getThongTinThanhToan($maDon)
    {
        try {
            $sql = "SELECT ddv.maDon, ddv.tongTien, ddv.thanhToan, ddv.diemSuDung, ddv.diemTichLuy,
                           ddv.ngayThanhToan, kh.diemTichLuy as diemHienCo
                    FROM dondichvu ddv
                    LEFT JOIN khachhang kh ON ddv.maKH = kh.maND
                    WHERE ddv.maDon = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Lỗi lấy thông tin thanh toán: " . $e->getMessage());
            return null;
        }
    }
}
?>