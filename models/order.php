<?php
class Order
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Lấy danh sách đơn hàng theo user_id
     */
    public function getOrdersByUserId($user_id)
    {
        try {
            $sql = "SELECT ddv.*, 
                           COUNT(ctddv.maCTDon) as so_luong_thiet_bi,
                           GROUP_CONCAT(ctddv.loai_thietbi SEPARATOR ', ') as danh_sach_thiet_bi,
                           noiSuaChua, ddv.maDon
                    FROM DonDichVu ddv
                    LEFT JOIN ChiTietDonDichVu ctddv ON ddv.maDon = ctddv.maDon
                    WHERE ddv.user_id = :user_id
                    GROUP BY ddv.maDon
                    ORDER BY  ddv.maDon DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Order Model Error: " . $e->getMessage());
            return [];
        }
    }

    public function getOrdersByKHID($user_id)
    {
        try {
            $sql = "SELECT *
                    FROM DonDichVu ddv
                    
                    WHERE ddv.user_id = :user_id"
            ;

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Order Model Error: " . $e->getMessage());
            return [];
        }
    }

    public function getOrdersByStatus($status = null)
    {
        try {
            if ($status === null) {
                $sql = "SELECT 
                            ddv.*,
                            COUNT(ctddv.maCTDon) as tong_so_thiet_bi,
                            COUNT(ctddv.id_nhanvien) as so_ktv_da_phan_cong
                        FROM DonDichVu ddv
                        LEFT JOIN chitietdondichvu ctddv ON ddv.maDon = ctddv.maDon
                        GROUP BY ddv.maDon
                        ORDER BY ddv.created_at DESC";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
            } else {
                $sql = "SELECT 
                            ddv.*,
                            COUNT(ctddv.maCTDon) as tong_so_thiet_bi,
                            COUNT(ctddv.id_nhanvien) as so_ktv_da_phan_cong
                        FROM DonDichVu ddv
                        LEFT JOIN chitietdondichvu ctddv ON ddv.maDon = ctddv.maDon
                        WHERE ddv.trangThai = ? 
                        GROUP BY ddv.maDon
                        ORDER BY ddv.created_at DESC";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$status]);
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Order Model Error: " . $e->getMessage());
            return [];
        }
    }


    /**
     * Lấy tên thiết bị từ CSDL
     */
    public function getDeviceNamesFromDB()
    {
        try {
            $sql = "SELECT maThietBi, tenThietBi FROM thietbi ORDER BY tenThietBi ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $deviceNames = [];
            foreach ($devices as $device) {
                $deviceNames[$device['maThietBi']] = $device['tenThietBi'];
            }
            return $deviceNames;

        } catch (Exception $e) {
            error_log("Get Device Names Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Xác định trạng thái đơn hàng
     */
    public function getOrderStatus($order_date)
    {
        $order_date_obj = new DateTime($order_date);
        $today = new DateTime();

        if ($order_date_obj < $today) {
            return [
                'class' => 'status-completed',
                'text' => 'Đã hoàn thành',
                'icon' => 'fas fa-check-circle'
            ];
        } else {
            return [
                'class' => 'status-pending',
                'text' => 'Chờ xác nhận',
                'icon' => 'fas fa-clock'
            ];
        }
    }

    public function getOrderDetail($order_id, $user_id = null)
    {
        try {
            $sql = "SELECT ddv.*, 
                           u.hoTen as user_name, 
                           u.sdt as user_phone,
                           u.email as user_email

                    FROM DonDichVu ddv
                    LEFT JOIN nguoidung u ON ddv.user_id = u.maND
                    WHERE ddv.maDon = :order_id";

            // Nếu có user_id, kiểm tra quyền truy cập
            if ($user_id) {
                $sql .= " AND ddv.user_id = :user_id";
            }

            $stmt = $this->db->prepare($sql);
            $params = [':order_id' => $order_id];
            if ($user_id) {
                $params[':user_id'] = $user_id;
            }

            $stmt->execute($params);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                return null;
            }

            // Lấy chi tiết thiết bị
            $order['devices'] = $this->getOrderDevicesDetail($order_id);

            return $order;

        } catch (Exception $e) {
            error_log("Order Detail Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy chi tiết thiết bị của đơn hàng
     */
    public function getOrderDevicesDetail($order_id)
    {
        try {
            $sql = "SELECT *
                    FROM ChiTietDonDichVu ctddv
                    JOIN thietbi tb ON ctddv.loai_thietbi = tb.maThietBi
                    WHERE ctddv.maDon = :order_id";
            //echo $sql.$order_id;
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':order_id' => $order_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Order Devices Detail Error: " . $e->getMessage());
            return [];
        }
    }



    public function getOrderByID($order_id)
    {
        try {
            $sql = "SELECT *
                    FROM dondichvu a JOIN  chitietdondichvu b on a.maDon=b.maDon where a.maDon=:order_id";
            //echo $sql;
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':order_id' => $order_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Order Devices Detail Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy lịch sử cập nhật đơn hàng
     */
    public function getOrderHistory($order_id)
    {
        try {
            $sql = "SELECT * FROM order_history 
                    WHERE order_id = :order_id 
                    ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':order_id' => $order_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            // Bảng order_history có thể chưa tồn tại, trả về mảng rỗng
            return [];
        }
    }



    public function getOrderByIDorIDNV($order_id)
    {
        try {
            $sql = "SELECT *
                    FROM ChiTietDonDichVu ctddv
                    LEFT JOIN thietbi tb ON ctddv.loai_thietbi = tb.maThietBi
                    WHERE ctddv.maDon = :order_id AND id_nhanvien is null";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':order_id' => $order_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Order Devices Detail Error: " . $e->getMessage());
            return [];
        }
    }


    //----------
    public function getOrdersByStatusAndLocation($status, $locationFilter = 'all')
    {
        try {
            $sql = "SELECT 
                        ddv.*,
                        COUNT(ctddv.maCTDon) as tong_so_thiet_bi,
                        COUNT(ctddv.id_nhanvien) as so_ktv_da_phan_cong,
                        ddv.noiSuaChua
                    FROM DonDichVu ddv
                    LEFT JOIN chitietdondichvu ctddv ON ddv.maDon = ctddv.maDon
                    WHERE ddv.trangThai = ?";

            // Thêm điều kiện lọc nơi sửa chữa
            if ($locationFilter !== 'all') {
                $sql .= " AND ddv.noiSuaChua = ?";
            }

            $sql .= " GROUP BY ddv.maDon
                      ORDER BY ddv.created_at DESC";

            $stmt = $this->db->prepare($sql);

            if ($locationFilter !== 'all') {
                $stmt->execute([$status, $locationFilter]);
            } else {
                $stmt->execute([$status]);
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("getOrdersByStatusAndLocation Error: " . $e->getMessage());
            return [];
        }
    }
    //---

    //Phan cong don 
    public function PCByKTV($idKTV, $maCTDon)
    {
        try {
            $this->db->beginTransaction();

            // 1. Cập nhật phân công KTV
            $updateResult = $this->updateKTVAssignment($idKTV, $maCTDon);
            if (!$updateResult) {
                $this->db->rollBack();
                return false;
            }

            // 2. Thêm vào lịch phân công
            $insertResult = $this->insertToWorkSchedule($idKTV, $maCTDon);
            if (!$insertResult) {
                $this->db->rollBack();
                return false;
            }

            // 3. Kiểm tra và cập nhật trạng thái đơn hàng
            $statusResult = $this->checkAndUpdateOrderStatus($maCTDon);
            if (!$statusResult) {
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("PCByKTV Error: " . $e->getMessage());
            return false;
        }
    }

    private function updateKTVAssignment($idKTV, $maCTDon)
    {
        $sql = "UPDATE chitietdondichvu 
            SET id_nhanvien = ?
            WHERE maCTDon = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idKTV, $maCTDon]);
    }

    private function insertToWorkSchedule($idKTV, $maCTDon)
    {
        // Lấy thông tin đơn hàng
        $sql = "SELECT ctddv.maDon, ddv.ngayDat, ddv.gioDat 
            FROM chitietdondichvu ctddv 
            JOIN DonDichVu ddv ON ctddv.maDon = ddv.maDon 
            WHERE ctddv.maCTDon = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maCTDon]);
        $orderInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$orderInfo)
            return false;

        // Insert vào lịch phân công
        $sql2 = "INSERT INTO lichphancong (maKTV, maDon, maCTDon, ngayLamViec, khungGio, trangThai) 
            VALUES (?, ?, ?, ?, ?, 'assigned')";
        $stmt2 = $this->db->prepare($sql2);
        return $stmt2->execute([
            $idKTV,
            $orderInfo['maDon'],
            $maCTDon,
            $orderInfo['ngayDat'],
            $orderInfo['gioDat']
        ]);
    }

    private function checkAndUpdateOrderStatus($maCTDon)
    {
        // Lấy mã đơn hàng
        $sql = "SELECT maDon FROM chitietdondichvu WHERE maCTDon = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maCTDon]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order)
            return false;

        $maDon = $order['maDon'];

        // Kiểm tra xem còn chi tiết đơn nào chưa phân công không
        $sql2 = "SELECT COUNT(*) as unassigned_count 
             FROM chitietdondichvu 
             WHERE maDon = ? AND id_nhanvien IS NULL";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->execute([$maDon]);
        $check = $stmt2->fetch(PDO::FETCH_ASSOC);

        // Nếu không còn chi tiết nào chưa phân công, cập nhật trạng thái đơn hàng
        if ($check && $check['unassigned_count'] == 0) {
            $sql3 = "UPDATE DonDichVu 
                 SET trangThai = 1 
                 WHERE maDon = ?";
            $stmt3 = $this->db->prepare($sql3);
            return $stmt3->execute([$maDon]);
        }

        return true;
    }


    public function getKTVWeeklySchedule($ktvId, $weekStartDate)
    {
        try {
            $weekEndDate = date('Y-m-d', strtotime($weekStartDate . ' +6 days'));

            $sql = "SELECT 
                    lp.*,
                    ddv.maDon,
                    tb.tenThietBi,
                    ctddv.loai_thietbi,
                    ddv.noiSuaChua,
                    c.hoTen as customer_name,
                    c.sdt as customer_phone
                FROM lichphancong lp
                JOIN DonDichVu ddv ON lp.maDon = ddv.maDon
                JOIN chitietdondichvu ctddv ON lp.maCTDon = ctddv.maCTDon
                JOIN thietbi tb ON tb.maThietBi = ctddv.loai_thietbi
                JOIN nguoidung c ON ddv.maKH = c.maND
                WHERE lp.maKTV = ?
                AND lp.ngayLamViec BETWEEN ? AND ?
                AND lp.trangThai != 'cancelled'
                ORDER BY lp.ngayLamViec, 
                         CASE lp.khungGio 
                             WHEN 'sang' THEN 1 
                             WHEN 'chieu' THEN 2 
                             WHEN 'toi' THEN 3 
                         END";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ktvId, $weekStartDate, $weekEndDate]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("getKTVWeeklySchedule Error: " . $e->getMessage());
            return [];
        }
    }




    // Lấy đơn hôm nay được phân công cho KTV
    public function getDonHomNayByKTV($ktvId)
    {
        try {
            $sql = "SELECT DISTINCT  dd.*, 
        (SELECT COUNT(*) FROM chitietdondichvu WHERE maDon = dd.maDon AND id_nhanvien = 4) as loai_thietbi,
        kh.hoTen as customer_name, kh.sdt
 FROM dondichvu dd
 JOIN nguoidung kh ON dd.user_id = kh.maND
 WHERE EXISTS (
     SELECT 1 FROM chitietdondichvu ctdd 
     WHERE ctdd.maDon = dd.maDon AND ctdd.id_nhanvien = ?
 )
 AND DATE(dd.ngayDat) = CURDATE()
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
            $sql = "SELECT dd.*, ctdd.id_nhanvien, kh.hoTen as customer_name ,kh.sdt,tb.tenThietBi
                FROM dondichvu dd
                JOIN chitietdondichvu ctdd ON dd.maDon = ctdd.maDon
                JOIN nguoidung kh ON dd.user_id = kh.maND
                JOIN thietbi tb on tb.maThietBi=ctdd.loai_ThietBi
                WHERE ctdd.id_nhanvien = ? 
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
    /**
     * Kiểm tra KTV có quyền truy cập đơn hàng không
     */
    public function kiemTraQuyenTruyCapKTV($maDon, $idKTV)
    {
        try {
            $sql = "SELECT 1 FROM chitietdondichvu 
                WHERE maDon = ? AND id_nhanvien = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon, $idKTV]);
            return $stmt->fetch() !== false;

        } catch (Exception $e) {
            error_log("kiemTraQuyenTruyCapKTV Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thông tin đơn hàng đơn giản cho KTV
     */
    public function layThongTinDonChoKTV($maDon, $idKTV)
    {
        try {
            // Kiểm tra quyền truy cập
            if (!$this->kiemTraQuyenTruyCapKTV($maDon, $idKTV)) {
                return false;
            }

            $sql = "SELECT dd.*, kh.name as customer_name, kh.phone, kh.email,
                       nv.name as technician_name, nv.phone as technician_phone
                FROM dondichvu dd
                JOIN khachhang kh ON dd.id_khachhang = kh.id_khachhang
                LEFT JOIN chitietdondichvu ctdd ON dd.maDon = ctdd.maDon
                LEFT JOIN nhanvien nv ON ctdd.id_nhanvien = nv.id_nhanvien
                WHERE dd.maDon = ? AND ctdd.id_nhanvien = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon, $idKTV]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("layThongTinDonChoKTV Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách thiết bị trong đơn hàng
     */
    public function layDanhSachThietBiTrongDon($maDon)
    {
        try {
            $sql = "SELECT ctb.*, tb.tenThietBi, tb.mota as thong_tin_thiet_bi
                FROM chitietthietbi ctb
                LEFT JOIN thietbi tb ON ctb.loai_thietbi = tb.maThietBi
                WHERE ctb.maDon = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("layDanhSachThietBiTrongDon Error: " . $e->getMessage());
            return [];
        }
    }




}


?>