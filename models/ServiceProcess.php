<?php
class ServiceProcess
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Bắt đầu dịch vụ sửa chữa - cập nhật trạng thái đơn hàng thành "Đang sửa chữa"
     */
    public function startService($orderId, $ktvId)
    {
        try {
            $sql = "UPDATE chitietdondichvu SET trangThai = 2 WHERE maDon = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$orderId]);

            if ($result) {
                $this->logAction($orderId, $ktvId, 'Bắt đầu sửa chữa', 'Bắt đầu quá trình sửa chữa');
            }

            return $result;
        } catch (Exception $e) {
            error_log("Start Service Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật quyết định của khách hàng (đồng ý/không đồng ý sửa chữa)
     */

    /**
     * Thêm chi tiết sửa chữa (công việc đã thực hiện)
     */
    public function addRepairDetail($orderId, $ctdonId, $description, $cost, $ktvId)
    {
        try {
            $sql = "INSERT INTO chitietsuachua (order_id, ctdon_id, description, cost, created_by) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$orderId, $ctdonId, $description, $cost, $ktvId]);

            if ($result) {
                $this->logAction($orderId, $ktvId, 'Thêm chi tiết sửa chữa', $description . " - " . number_format($cost) . " VND");
            }

            return $result;
        } catch (Exception $e) {
            error_log("Add Repair Detail Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tạm dừng dịch vụ sửa chữa
     */
    public function pauseService($orderId, $reason, $ktvId)
    {
        try {
            $sql = "UPDATE service_process SET is_paused = 1, pause_reason = ?, updated_at = NOW() WHERE order_id = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$reason, $orderId]);

            if ($result) {
                $this->logAction($orderId, $ktvId, 'Tạm dừng dịch vụ', $reason);
            }

            return $result;
        } catch (Exception $e) {
            error_log("Pause Service Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tiếp tục dịch vụ sau khi tạm dừng
     */
    public function resumeService($orderId, $ktvId)
    {
        try {
            $sql = "UPDATE service_process SET is_paused = 0, pause_reason = NULL, updated_at = NOW() WHERE order_id = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$orderId]);

            if ($result) {
                $this->logAction($orderId, $ktvId, 'Tiếp tục dịch vụ', 'Tiếp tục quá trình sửa chữa');
            }

            return $result;
        } catch (Exception $e) {
            error_log("Resume Service Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Hoàn thành dịch vụ - cập nhật tổng chi phí và trạng thái đơn hàng
     */
    public function completeService($orderId, $totalCost, $finalNote, $ktvId)
    {
        try {
            // Cập nhật service_process
            $sql = "UPDATE service_process SET total_cost = ?, final_note = ?, updated_at = NOW() WHERE order_id = ?";
            $stmt = $this->db->prepare($sql);
            $result1 = $stmt->execute([$totalCost, $finalNote, $orderId]);

            // Cập nhật trạng thái đơn hàng
            $sql2 = "UPDATE chitietdondichvu SET trangThai = 3 WHERE maCTDon = ?";
            $stmt2 = $this->db->prepare($sql2);
            $result2 = $stmt2->execute([$orderId]);

            if ($result1 && $result2) {
                $actionNotes = "Hoàn thành dịch vụ. Tổng chi phí: " . number_format($totalCost) . " VND";
                if ($finalNote)
                    $actionNotes .= ". Ghi chú: " . $finalNote;

                $this->logAction($orderId, $ktvId, 'Hoàn thành dịch vụ', $actionNotes);
            }

            return $result1 && $result2;
        } catch (Exception $e) {
            error_log("Complete Service Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lưu ảnh minh chứng (ảnh đến nhà, ảnh thiết bị)
     */
    public function saveEvidenceImage($orderId, $ctdonId, $evidenceType, $imagePath, $ktvId)
    {
        try {
            // XÁC ĐỊNH TRƯỜNG CẦN UPDATE
            $fieldToUpdate = '';
            if ($evidenceType == 'arrival') {
                $fieldToUpdate = 'minhchung_den';
            } elseif ($evidenceType == 'device') {
                $fieldToUpdate = 'minhchung_thietbi';
            }elseif ($evidenceType == 'completion') {
                $fieldToUpdate = 'minhchunghoanthanh';
            } else {
                error_log("Invalid evidence type: $evidenceType");
                return false;
            }

            // UPDATE TRỰC TIẾP
            $sql = "UPDATE chitietdondichvu SET 
                    $fieldToUpdate = ?
                    WHERE maCTDon = ? AND maDon = ?";

            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$imagePath, $ctdonId, $orderId]);

            // KIỂM TRA KẾT QUẢ
            if ($result) {
                $rowCount = $stmt->rowCount();
                error_log("DEBUG: Updated $rowCount row(s) - Field: $fieldToUpdate, Image: $imagePath");

                $evidenceTypeText = $evidenceType == 'arrival' ? 'đến nhà' : 'thiết bị';
                $this->logAction($orderId, $ktvId, 'Upload ảnh minh chứng', 'Upload ảnh ' . $evidenceTypeText);

                return true;
            }

            return false;
        } catch (Exception $e) {
            error_log("Save Evidence Image Error: " . $e->getMessage());
            return false;
        }
    }

    public function getEvidenceImages($orderId, $ctdonId)
    {
        try {
            $sql = "SELECT 
                    minhchung_den,
                    minhchung_thietbi,
                    minhchunghoanthanh
                    
                    FROM chitietdondichvu 
                    WHERE maDon = ? AND maCTDon = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId, $ctdonId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Evidence Images Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách chi tiết sửa chữa của đơn hàng
     */
    public function getRepairDetails($orderId)
    {
        try {
            $sql = "SELECT * FROM repair_details WHERE order_id = ? ORDER BY created_at ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Repair Details Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách ảnh minh chứng của đơn hàng
     */
    // public function getEvidenceImages($orderId) {
    //     try {
    //         $sql = "SELECT ei.*, nd.hoTen as uploaded_by_name 
    //                 FROM evidence_images ei 
    //                 LEFT JOIN nguoidung nd ON ei.uploaded_by = nd.maND 
    //                 WHERE ei.order_id = ? 
    //                 ORDER BY ei.created_at DESC";
    //         $stmt = $this->db->prepare($sql);
    //         $stmt->execute([$orderId]);
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (Exception $e) {
    //         error_log("Get Evidence Images Error: " . $e->getMessage());
    //         return [];
    //     }
    // }

    /**
     * Lấy lịch sử thao tác của đơn hàng
     */
    public function getServiceActions($orderId)
    {
        try {
            $sql = "SELECT sa.*, nd.hoTen as technician_name 
                    FROM service_actions sa 
                    LEFT JOIN nguoidung nd ON sa.technician_id = nd.maND 
                    WHERE sa.order_id = ? 
                    ORDER BY sa.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Service Actions Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tính tổng chi phí sửa chữa của đơn hàng
     */
    public function getTotalRepairCost($orderId)
    {
        try {
            $sql = "SELECT SUM(cost) as total_cost FROM repair_details WHERE order_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_cost'] ?? 0;
        } catch (Exception $e) {
            error_log("Get Total Repair Cost Error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Ghi log các hành động trong quá trình sửa chữa
     */
    public function logAction($maDon, $maKTV, $actionType, $notes = '')
    {
        try {
            $sql = "INSERT INTO lich_su_thaotac (maDon, maKTV, loai_hanh_dong, mo_ta, thoi_gian_tao) 
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$maDon, $maKTV, $actionType, $notes]);
        } catch (Exception $e) {
            error_log("Log Action Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy chi tiết sửa chữa theo từng thiết bị (chi tiết đơn)
     */
    public function getDeviceRepairDetails($orderId, $ctdonId)
    {
        try {
            $sql = "SELECT * FROM chitietsuachua WHERE maDon = ? AND maCTDon = ?  ORDER BY created_at ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId, $ctdonId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Device Repair Details Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thông tin quá trình sửa chữa từ bảng chitietsuachua
     */
    public function getServiceProcess($orderId)
    {
        try {
            $sql = "SELECT ctsc.* FROM chitietsuachua ctsc WHERE ctsc.maDon = ? ORDER BY ctsc.ngay_bat_dau DESC LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Service Process Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Thêm chẩn đoán và báo giá dự kiến cho thiết bị
     */
    public function addDiagnosis($maDon, $maCTDon, $chuanDoan, $baoGia, $ktvId)
    {
        try {
            $sql = "UPDATE `chitietdondichvu` SET `chuandoanKTV`=?,`baoGiaSC`=? WHERE `maCTDon`=? ";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$chuanDoan, $baoGia, $maCTDon]);

            if ($result) {
                $this->logAction($maDon, $ktvId, 'Chẩn đoán', $chuanDoan);
            }

            return $result;
        } catch (Exception $e) {
            error_log("Add Diagnosis Error: " . $e->getMessage());
            return false;
        }
    }

    public function addDiagnosisWithJobs($maDon, $maCTDon, $chanDoan, $baoGia, $maKTV, $danhSachCongViec, $quyetdinh, $lydo)
    {
        $this->db->beginTransaction();

        try {
            // 1. Cập nhật chẩn đoán tổng
            if ($quyetdinh == 1) {
                $sql = "UPDATE chitietdondichvu SET chuandoanKTV=?, baoGiaSC=?,trangThai=1,quyetDinhSC=?,lyDoTC=? WHERE maCTDon=?";
            } else {
                $sql = "UPDATE chitietdondichvu SET chuandoanKTV=?, baoGiaSC=?,trangThai=4,quyetDinhSC=?,lyDoTC=? WHERE maCTDon=?";

            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$chanDoan, $baoGia, $quyetdinh, $lydo, $maCTDon]);

            // 2. Lưu danh sách công việc chi tiết
            if (!empty($danhSachCongViec)) {
                $sqlJob = "INSERT INTO chitietsuachua 
                          (maCTDon, maDon, loiSuaCHua, chiPhi,loai,thoigian) 
                          VALUES (?, ?, ?, ?,'Báo giá',?)";

                $stmtJob = $this->db->prepare($sqlJob);

                foreach ($danhSachCongViec as $congViec) {
                    $stmtJob->execute([
                        $maCTDon,
                        $maDon,
                        $congViec['name'],
                        $congViec['cost'],
                        $congViec['time']
                    ]);
                }
            } else {
                echo 'Loi ne';
            }

            // 2. Kiểm tra xem tất cả chi tiết đơn dịch vụ đã hoàn thành chưa
            $sql = "SELECT COUNT(*) as pending_devices
            FROM chitietdondichvu 
            WHERE maDon = ?
            AND trangThai NOT IN (3, 4)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3. Nếu tất cả đã hoàn thành, cập nhật trạng thái đơn dịch vụ = 4
            if ($result) {
                $sql = "UPDATE dondichvu SET trangThai = 4 WHERE maDon = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$maDon]);
            }

            $this->db->commit();

            // 3. Log hành động
            $this->logAction($maDon, $maKTV, 'Chẩn đoán', "Chẩn đoán: $chanDoan - Báo giá: " . number_format($baoGia));

            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Add Diagnosis Error: " . $e->getMessage());
            return false;
        }
    }
    //Thêm công việc sửa chữa phát sinh
    public function themCVSuaChua($maDon, $maCTDon, $tenCongViec, $chiPhi, $maKTV,$thoiGianGio)
    {
        $this->db->beginTransaction();

        try {
            // 1. Thêm công việc sửa chữa phát sinh
            $sql = "INSERT INTO chitietsuachua 
                    (maCTDon, maDon, loiSuaCHua, chiPhi,loai,thoigian) 
                    VALUES (?, ?, ?, ?,'Phát sinh',?)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maCTDon, $maDon, $tenCongViec, $chiPhi,$thoiGianGio]);

            // 2. Cập nhật tổng chi phí trong chitietsuachua (nếu cần)
            // Lấy tổng chi phí hiện tại
            $sqlTongChiPhi = "SELECT SUM(chiPhi) as tongChiPhi 
                             FROM chitietsuachua 
                             WHERE maCTDon = ? AND maDon = ?";
            $stmtTong = $this->db->prepare($sqlTongChiPhi);
            $stmtTong->execute([$maCTDon, $maDon]);
            $tongChiPhi = $stmtTong->fetch(PDO::FETCH_ASSOC)['tongChiPhi'];

            // 3. Cập nhật tổng chi phí vào chitietdondichvu
            $sqlUpdate = "UPDATE chitietdondichvu 
                         SET baoGiaSC = ? 
                         WHERE maCTDon = ? AND maDon = ?";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $stmtUpdate->execute([$tongChiPhi, $maCTDon, $maDon]);

            $this->db->commit();

            // 4. Log hành động
            $this->logAction(
                $maDon,
                $maKTV,
                'Thêm công việc phát sinh',
                "Thêm công việc: $tenCongViec - Chi phí: " . number_format($chiPhi) . " VND"
            );

            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Thêm công việc sửa chữa Error: " . $e->getMessage());
            return false;
        }
    }


    // Hàm hỗ trợ lấy maThietBi
    private function getMaThietBiFromCTDon($maCTDon)
    {
        $sql = "SELECT loai_thietbi FROM chitietdondichvu WHERE maCTDon = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maCTDon]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['loai_thietbi'] : null;
    }

    /**
     * Lấy chẩn đoán của một thiết bị cụ thể
     */
    public function getDeviceDiagnosis($maDon, $maCTDon)
    {
        //echo $maDon, $maCTDon;
        $sql = "SELECT 
                    chuandoanKTV as tinh_trang_thuc_te,
                    baoGiaSC as chi_phi,
                    trangThai
                FROM chitietdondichvu 
                WHERE maDon = ? AND maCTDon = ? 
                AND (trangThai != 0 )  -- Chỉ lấy khi đã chẩn đoán
                AND chuandoanKTV IS NOT NULL 
                AND chuandoanKTV != ''";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maDon, $maCTDon]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách chi tiết lỗi và khoảng giá từ bảng giá sửa chữa
     */
    public function getPriceDetail($maThietBi)
    {
        try {
            echo 'hi'. $maThietBi;
             $query = "SELECT 
            pl.maGia,
                        pl.maThietBi,
                        pl.chitietloi,
                        pl.khoangGia
                     FROM banggiaSC pl 
                     WHERE pl.maThietBi = ? 
                     ORDER BY pl.maGia ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$maThietBi]);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : [];
        } catch (PDOException $e) {
            error_log("Lỗi getPriceDetail: " . $e->getMessage());
            return [];
        }
    }

    public function startServiceForDevice($maDon, $maCTDon, $maKTV)
    {
        try {
            $this->db->beginTransaction();

            // 1. Cập nhật trạng thái chi tiết đơn dịch vụ = 2 (đang sửa chữa)
            $sql = "UPDATE chitietdondichvu SET trangThai = 2, gioBatDau = NOW() WHERE maCTDon = ? AND maDon = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maCTDon, $maDon]);

            // 2. Cập nhật trạng thái đơn dịch vụ = 3 (đang thực hiện)
            $sql = "UPDATE dondichvu SET trangThai = 3 WHERE maDon = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Lỗi bắt đầu sửa chữa: " . $e->getMessage());
            return false;
        }
    }

    public function completeServiceForDevice($maDon, $maCTDon, $maKTV)
    {
        try {
            $this->db->beginTransaction();

            // 1. Cập nhật trạng thái chi tiết đơn dịch vụ = 3 (đã hoàn thành)
            $sql = "UPDATE chitietdondichvu SET trangThai = 3, gioKetThuc = NOW() WHERE maCTDon = ? AND maDon = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maCTDon, $maDon]);

            // 2. Kiểm tra xem tất cả chi tiết đơn dịch vụ đã hoàn thành chưa
            $sql = "SELECT COUNT(*) as total, SUM(CASE WHEN trangThai = 3 THEN 1 ELSE 0 END) as completed 
                    FROM chitietdondichvu 
                    WHERE maDon = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3. Nếu tất cả đã hoàn thành, cập nhật trạng thái đơn dịch vụ = 4
            if ($result && $result['total'] > 0 && $result['completed'] == $result['total']) {
                $sql = "UPDATE dondichvu SET trangThai = 4 WHERE maDon = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$maDon]);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Lỗi kết thúc sửa chữa: " . $e->getMessage());
            return false;
        }
    }

    // Phương thức kiểm tra trạng thái đơn hàng
    public function getOrderStatus($maDon)
    {
        $sql = "SELECT trangThai FROM dondichvu WHERE maDon = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maDon]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Phương thức lấy thông tin chi tiết đơn dịch vụ
    public function getOrderDeviceStatus($maDon)
    {
        $sql = "SELECT maCTDon, trangThai, tenThietBi FROM chitietdondichvu WHERE maDon = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maDon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerDecision($maDon, $maCTDon)
    {
        $sql = "SELECT * FROM chitietdondichvu WHERE maDon = ? AND maCTDon = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maDon, $maCTDon]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCustomerDecision($maDon, $maCTDon, $quyetDinh, $lyDo, $maKTV)
    {
        if ($quyetDinh == 1) {
            $sql = "UPDATE chitietdondichvu SET quyetDinhSC =?, lyDoTC =? Where maCTDon =?";
        } else {
            $sql = "UPDATE chitietdondichvu SET quyetDinhSC =?, lyDoTC =?, trangThai=4 Where maCTDon =?";

        }

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$quyetDinh, $lyDo, $maCTDon]);



        return $result;
    }
}
?>