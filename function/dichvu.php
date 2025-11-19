<?php
/**
 * FILE: classes/DichVuService.php  
 * CLASS QUẢN LÝ DỊCH VỤ - TECH CARE (OOP VERSION) - ĐÃ TỐI ƯU THEO TestSlotService
 */

require_once __DIR__ . '/ketnoi.php';

class DichVuService
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    // ==================== CRUD CƠ BẢN (GIỮ NGUYÊN) ====================

    public function layDanhSachDon($maKhachHang = null, $trangThai = null)
    {
        try {
            $sql = "SELECT d.*, 
                           kh.hoTen as tenKhachHang, 
                           kh.sdt as sdtKhachHang,
                           nv.hoTen as tenNhanVien
                    FROM DonDichVu d
                    LEFT JOIN NguoiDung kh ON d.id_khachhang = kh.id
                    LEFT JOIN NguoiDung nv ON d.id_nhanvien = nv.id
                    WHERE 1=1";

            $params = [];

            if ($maKhachHang) {
                $sql .= " AND d.id_khachhang = ?";
                $params[] = $maKhachHang;
            }

            if ($trangThai !== null) {
                $sql .= " AND d.trangThai = ?";
                $params[] = $trangThai;
            }

            $sql .= " ORDER BY d.ngayTao DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy danh sách đơn: " . $e->getMessage());
            return [];
        }
    }

    public function layChiTietDon($maDon)
    {
        try {
            $sql = "SELECT d.*, 
                           kh.hoTen as tenKhachHang, 
                           kh.sdt as sdtKhachHang,
                           kh.email as emailKhachHang,
                           nv.hoTen as tenNhanVien,
                           nv.sdt as sdtNhanVien,
                           nv.email as emailNhanVien,
                           kg.khoangGio,
                           kg.gioBatDau,
                           kg.gioKetThuc
                    FROM DonDichVu d
                    LEFT JOIN NguoiDung kh ON d.id_khachhang = kh.id
                    LEFT JOIN NguoiDung nv ON d.id_nhanvien = nv.id
                    LEFT JOIN KhungGio kg ON d.maKhungGio = kg.maKhungGio
                    WHERE d.maDon = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy chi tiết đơn: " . $e->getMessage());
            return null;
        }
    }

    public function taoDonDichVu($donData)
    {
        try {
            $maDon = "TC" . date('Ymd') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

            $sql = "INSERT INTO DonDichVu (
                        maDon, id_khachhang, maKhungGio, ngayDat, diemhen, 
                        danh_sach_thiet_bi, so_luong_thiet_bi, ghiChu, 
                        noiSuaChua, trangThai, ngayTao
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())";

            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $maDon,
                $donData['id_khachhang'],
                $donData['maKhungGio'],
                $donData['ngayDat'],
                $donData['diemhen'],
                $donData['danh_sach_thiet_bi'],
                $donData['so_luong_thiet_bi'],
                $donData['ghiChu'] ?? '',
                $donData['noiSuaChua'] ?? 0
            ]);

            return $result ? $maDon : false;
        } catch (PDOException $e) {
            error_log("Lỗi tạo đơn dịch vụ: " . $e->getMessage());
            return false;
        }
    }

    public function huyDonDichVu($maDon)
    {
        try {
            $sql = "UPDATE DonDichVu 
                    SET trangThai = 0, ngayCapNhat = NOW() 
                    WHERE maDon = ? AND trangThai = 1";

            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$maDon]);

            return $result && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Lỗi hủy đơn dịch vụ: " . $e->getMessage());
            return false;
        }
    }

    public function capNhatTrangThaiDon($maDon, $trangThaiMoi, $idNhanVien = null)
    {
        try {
            $sql = "UPDATE DonDichVu 
                    SET trangThai = ?, maKTV = ?, ngayCapNhat = NOW() 
                    WHERE maDon = ?";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$trangThaiMoi, $idNhanVien, $maDon]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật trạng thái đơn: " . $e->getMessage());
            return false;
        }
    }

    public function layDanhSachThietBi()
    {
        try {
            $sql = "SELECT * FROM ThietBi ORDER BY tenThietBi";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy danh sách thiết bị: " . $e->getMessage());
            return [];
        }
    }

    public function layThongTinKTV($maDon)
    {
        try {
            $sql = "SELECT nv.hoTen, nv.sdt, nv.email, nv.chuyenMon
                    FROM NguoiDung nv
                    INNER JOIN DonDichVu d ON d.id_nhanvien = nv.id
                    WHERE d.maDon = ? AND nv.trangThai = 1";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maDon]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy thông tin KTV: " . $e->getMessage());
            return null;
        }
    }

    // ==================== TÍNH SLOT - THEO TestSlotService ====================

    public function layTongKTVLamViec($ngay)
{
    try {
        // Lấy thứ trong tuần từ ngày
        $thuTrongTuan = date('w', strtotime($ngay));
        $maLichLam = $thuTrongTuan == 0 ? 1 : $thuTrongTuan + 1;

        $sql = "SELECT COUNT(DISTINCT nv.maND) as soLuong 
                FROM NguoiDung nv
                INNER JOIN hosokythuatvien hsk ON nv.maND = hsk.maKTV
                INNER JOIN lichlamviec llv ON hsk.maLLV = llv.maLLV
                WHERE nv.maVaiTro = 3 
                AND nv.trangThaiHD = 1 
                AND nv.maND NOT IN (
                    SELECT maNV FROM lichxinnghi 
                    WHERE ngayNghi = ?
                )
                AND FIND_IN_SET(?, llv.ngayLamViec) > 0";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ngay, $maLichLam]); // 2 tham số: ngayNghi và maLichLam
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (int) $result['soLuong'] : 0;
    } catch (PDOException $e) {
        error_log("Lỗi lấy số lượng KTV: " . $e->getMessage());
        return 0;
    }
}

    public function layDonConLaiTaiKhungGio($ngay, $gioHienTai)
    {
        try {
            $sqlKhungGio = "SELECT * FROM bangKhungGio ORDER BY gioBatDau";
            $stmtKhungGio = $this->db->query($sqlKhungGio);
            $khungGios = $stmtKhungGio->fetchAll(PDO::FETCH_ASSOC);

            $mapping = [];
            foreach ($khungGios as $index => $khung) {
                $mapping[$index + 1] = $khung['maKhungGio'];
            }

            $sql = "SELECT dd.maKhungGio, kg.khoangGio, kg.gioChan, COUNT(*) as soDon
                    FROM DonDichVu dd
                    JOIN bangKhungGio kg ON dd.maKhungGio = kg.maKhungGio
                    WHERE dd.ngayDat = ? 
                    AND dd.trangThai IN (1,2,3)
                    GROUP BY dd.maKhungGio, kg.khoangGio, kg.gioChan";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ngay]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $tongDonConLai = 0;
            $donTheoKhungGio = [];

            foreach ($results as $row) {
                $maKhungGioSo = (int) $row['maKhungGio'];
                $maKhungGio = $mapping[$maKhungGioSo] ?? $row['maKhungGio'];

                $donTheoKhungGio[$maKhungGio] = [
                    'soDon' => (int) $row['soDon'],
                    'khoangGio' => $row['khoangGio'],
                    'gioChan' => (int) $row['gioChan']
                ];
                $tongDonConLai += (int) $row['soDon'];
            }

            return ['tong' => $tongDonConLai, 'chi_tiet' => $donTheoKhungGio];
        } catch (Exception $e) {
            error_log("Lỗi lấy đơn còn lại: " . $e->getMessage());
            return ['tong' => 0, 'chi_tiet' => []];
        }
    }

    public function layDonHoanThanhTaiKhungGioDaQua($ngay, $gioHienTai)
    {
        try {
            $sqlKhungGio = "SELECT * FROM bangKhungGio ORDER BY gioBatDau";
            $stmtKhungGio = $this->db->query($sqlKhungGio);
            $khungGios = $stmtKhungGio->fetchAll(PDO::FETCH_ASSOC);

            $mapping = [];
            foreach ($khungGios as $index => $khung) {
                $mapping[$index + 1] = $khung['maKhungGio'];
            }

            $sql = "SELECT dd.maKhungGio, kg.khoangGio, kg.gioChan, COUNT(*) as soDon
                    FROM DonDichVu dd
                    JOIN bangKhungGio kg ON dd.maKhungGio = kg.maKhungGio
                    WHERE dd.ngayDat = ? 
                    AND dd.trangThai = 3 
                    AND kg.gioChan < ?
                    GROUP BY dd.maKhungGio, kg.khoangGio, kg.gioChan";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ngay, $gioHienTai]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $tongDonHoanThanh = 0;
            $donTheoKhungGio = [];

            foreach ($results as $row) {
                $maKhungGioSo = (int) $row['maKhungGio'];
                $maKhungGio = $mapping[$maKhungGioSo] ?? $row['maKhungGio'];

                $donTheoKhungGio[$maKhungGio] = [
                    'soDon' => (int) $row['soDon'],
                    'khoangGio' => $row['khoangGio'],
                    'gioChan' => (int) $row['gioChan']
                ];
                $tongDonHoanThanh += (int) $row['soDon'];
            }

            return ['tong' => $tongDonHoanThanh, 'chi_tiet' => $donTheoKhungGio];
        } catch (Exception $e) {
            error_log("Lỗi lấy đơn hoàn thành: " . $e->getMessage());
            return ['tong' => 0, 'chi_tiet' => []];
        }
    }

    public function layDanhSachKhungGio()
    {
        try {
            $sql = "SELECT * FROM bangKhungGio ORDER BY gioBatDau";
            $stmt = $this->db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (Exception $e) {
            error_log("Lỗi lấy khung giờ: " . $e->getMessage());
            return [
                ['maKhungGio' => 'KG1', 'khoangGio' => '8-10', 'gioBatDau' => 8, 'gioChan' => 10],
                ['maKhungGio' => 'KG2', 'khoangGio' => '10-12', 'gioBatDau' => 10, 'gioChan' => 12],
                ['maKhungGio' => 'KG3', 'khoangGio' => '12-14', 'gioBatDau' => 12, 'gioChan' => 14],
                ['maKhungGio' => 'KG4', 'khoangGio' => '14-16', 'gioBatDau' => 14, 'gioChan' => 16],
                ['maKhungGio' => 'KG5', 'khoangGio' => '16-18', 'gioBatDau' => 16, 'gioChan' => 18]
            ];
        }
    }

    private function tinhKTVConLai($tongKTV, $gioHienTai, $danhSachKhungGio, $donConLai)
    {
        $ktvDaDung = 0;
        foreach ($danhSachKhungGio as $khung) {
            if ($gioHienTai >= $khung['gioChan']) {
                $ma = $khung['maKhungGio'];
                $soDon = $donConLai['chi_tiet'][$ma]['soDon'] ?? 0;
                $ktvDaDung += $soDon;
            }
        }
        return max(0, $tongKTV - $ktvDaDung);
    }

    private function phanBoKTV($ktvConLai, $danhSachKhungGio, $gioHienTai)
    {
        $phanBo = [];
        $khungChuaQua = [];

        foreach ($danhSachKhungGio as $khung) {
            if ($gioHienTai < $khung['gioChan']) {
                $ma = $khung['maKhungGio'];
                $khungChuaQua[] = $ma;
                $phanBo[$ma] = 0;
            }
        }

        //  không còn khung nào để chia
        if (empty($khungChuaQua) || $ktvConLai <= 0) {
            return $phanBo; // = 0
        }

        // Chia đều + dư
        $soKhung = count($khungChuaQua);
        $slotChiaDeu = floor($ktvConLai / $soKhung);
        $slotDu = $ktvConLai % $soKhung;

        foreach ($khungChuaQua as $i => $ma) {
            $phanBo[$ma] = $slotChiaDeu;
            if ($i < $slotDu) {
                $phanBo[$ma]++;
            }
        }

        error_log("Phân bổ KTV: " . json_encode($phanBo));
        return $phanBo;
    }

    private function tinhKTVDuTheoKhung($gioHienTai, $danhSachKhungGio, $phanBoKTV, $donConLai)
    {
        $ktvDu = [];
        foreach ($danhSachKhungGio as $khung) {
            $ma = $khung['maKhungGio'];
            if ($gioHienTai >= $khung['gioChan']) {
                $ktvDu[$ma] = max(0, ($phanBoKTV[$ma] ?? 0) - ($donConLai['chi_tiet'][$ma]['soDon'] ?? 0));
            } else {
                $ktvDu[$ma] = 0;
            }
        }
        return $ktvDu;
    }

    private function phanBoKTVDuThongMinh($ktvDuTheoKhung, $danhSachKhungGio, $gioHienTai)
    {
        $phanBo = array_fill_keys(array_column($danhSachKhungGio, 'maKhungGio'), 0);
        $tong = array_sum($ktvDuTheoKhung);
        if ($tong <= 0)
            return $phanBo;

        $khungChuaQua = array_filter($danhSachKhungGio, fn($k) => $gioHienTai < $k['gioChan']);
        if (empty($khungChuaQua))
            return $phanBo;

        $i = 0;
        $list = array_values($khungChuaQua);
        while ($tong > 0) {
            $ma = $list[$i++ % count($list)]['maKhungGio'];
            $phanBo[$ma]++;
            $tong--;
        }
        return $phanBo;
    }

    private function phanBoSlotTuDonHoanThanh($tongDonHoanThanh, $danhSachKhungGio, $gioHienTai, $phanBoKTV)
    {
        $phanBo = array_fill_keys(array_column($danhSachKhungGio, 'maKhungGio'), 0);
        if ($tongDonHoanThanh <= 0)
            return $phanBo;

        $khungChuaQua = array_filter($danhSachKhungGio, fn($k) => $gioHienTai < $k['gioChan']);
        if (empty($khungChuaQua))
            return $phanBo;

        $khungTrong = [];
        $khungDaCo = [];
        foreach ($khungChuaQua as $k) {
            $ma = $k['maKhungGio'];
            ($phanBoKTV[$ma] ?? 0) == 0 ? $khungTrong[] = $ma : $khungDaCo[] = $ma;
        }

        $slot = $tongDonHoanThanh;
        foreach ([$khungTrong, $khungDaCo] as $ds) {
            foreach ($ds as $ma) {
                if ($slot <= 0)
                    break 2;
                $phanBo[$ma]++;
                $slot--;
            }
        }

        if ($slot > 0) {
            $i = 0;
            $list = array_values($khungChuaQua);
            while ($slot > 0) {
                $ma = $list[$i++ % count($list)]['maKhungGio'];
                $phanBo[$ma]++;
                $slot--;
            }
        }
        return $phanBo;
    }

    public function tinhSlotKhaDung($ngay, $gioHienTai = null)
    {
        if ($gioHienTai === null) {
            $gioHienTai = (int) date('H');
        }

        $tongKTV = $this->layTongKTVLamViec($ngay);
        $donHoanThanh = $this->layDonHoanThanhTaiKhungGioDaQua($ngay, $gioHienTai);
        $donConLai = $this->layDonConLaiTaiKhungGio($ngay, $gioHienTai);
        $danhSachKhungGio = $this->layDanhSachKhungGio();

        $ktvConLai = $this->tinhKTVConLai($tongKTV, $gioHienTai, $danhSachKhungGio, $donConLai);
        $phanBoKTV = $this->phanBoKTV($ktvConLai, $danhSachKhungGio, $gioHienTai);
        $ktvDuTheoKhung = $this->tinhKTVDuTheoKhung($gioHienTai, $danhSachKhungGio, $phanBoKTV, $donConLai);
        $phanBoKTVDu = $this->phanBoKTVDuThongMinh($ktvDuTheoKhung, $danhSachKhungGio, $gioHienTai);
        $phanBoSlotTuDonHoanThanh = $this->phanBoSlotTuDonHoanThanh($donHoanThanh['tong'], $danhSachKhungGio, $gioHienTai, $phanBoKTV);

        $ketQua = [];
        foreach ($danhSachKhungGio as $khungGio) {
            $ma = $khungGio['maKhungGio'];
            $gioChan = (int) $khungGio['gioChan'];
            $daQuaGio = ($gioHienTai >= $gioChan);

            $soKTVPhanBo = $phanBoKTV[$ma] ?? 0;
            $soKTVDu = $phanBoKTVDu[$ma] ?? 0;
            $tongKTVThucTe = $soKTVPhanBo + $soKTVDu;
            $slotHT = $phanBoSlotTuDonHoanThanh[$ma] ?? 0;
            $slotToiDa = $tongKTVThucTe + $slotHT;
            $daDat = $donConLai['chi_tiet'][$ma]['soDon'] ?? 0;
            $khaDung = max(0, $slotToiDa - $daDat);

            $ketQua[$ma] = [
                'pham_vi' => $khungGio['khoangGio'],
                'toi_da' => $slotToiDa,
                'da_dat' => $daDat,
                'kha_dung' => $khaDung,
                'tong_ktv_thuc_te' => $tongKTVThucTe,
                'slot_tu_don_hoan_thanh' => $slotHT,
                'vo_hieu_hoa' => $daQuaGio || $khaDung <= 0,
                'da_qua_gio' => $daQuaGio,
                'ly_do' => $daQuaGio ? 'Đã qua giờ' : ($khaDung <= 0 ? 'Hết slot' : 'Có thể đặt')
            ];
        }

        return $ketQua;
    }

    public function debugThongTin($ngay, $gioHienTai)
    {
        return [
            'ngay' => $ngay,
            'gio_hien_tai' => $gioHienTai,
            'tong_ktv' => $this->layTongKTVLamViec($ngay),
            'don_hoan_thanh' => $this->layDonHoanThanhTaiKhungGioDaQua($ngay, $gioHienTai),
            'don_con_lai' => $this->layDonConLaiTaiKhungGio($ngay, $gioHienTai),
            'khung_gio' => $this->layDanhSachKhungGio()
        ];
    }


    public function themDonDichVu($maKH, $ngayDat, $maKhungGio, $noiSuaChua, $danhSachThietBi, $ghiChu = null)
    {
        try {
            // Bắt đầu transaction
            $this->db->beginTransaction();

            // Tìm KTV phù hợp trước khi tạo đơn
            $maKTV = $this->timKTVPhuHop($ngayDat, $maKhungGio);

            // 1. Insert vào bảng dondichvu với maKTV
            $sqlDonDichVu = "INSERT INTO dondichvu (
                                diemhen, maKH, ngayDat, ghiChu, trangThai, 
                                noiSuaChua, maKhungGio, maKTV
                             ) VALUES (?, ?, ?, ?, 1, 0, ?, ?)";

            $stmtDonDichVu = $this->db->prepare($sqlDonDichVu);

            // Tạo điểm hẹn
            $diemHen = $noiSuaChua;

            $stmtDonDichVu->execute([
                $diemHen,
                $maKH,
                $ngayDat,
                $ghiChu,
                $maKhungGio,
                $maKTV  // Thêm maKTV vào đây
            ]);

            // Lấy mã đơn vừa insert
            $maDon = $this->db->lastInsertId();

            // 2. Insert vào bảng chitietdondichvu cho từng thiết bị
            $sqlChiTiet = "INSERT INTO chitietdondichvu (
                              maDon, maThietBi, phienBan, motaTinhTrang, trangThai
                           ) VALUES (?, ?, ?, ?, 1)";

            $stmtChiTiet = $this->db->prepare($sqlChiTiet);

            foreach ($danhSachThietBi as $thietBi) {
                $stmtChiTiet->execute([
                    $maDon,
                    $thietBi['maThietBi'],
                    $thietBi['phienBan'],
                    $thietBi['motaTinhTrang']
                ]);
            }

            // Commit transaction
            $this->db->commit();

            // Ghi log phân công KTV
            error_log("Đơn #$maDon đã được phân công cho KTV #$maKTV");

            return $maDon;

        } catch (Exception $e) {
            // Rollback transaction nếu có lỗi
            $this->db->rollBack();

            error_log("Lỗi khi thêm đơn dịch vụ: " . $e->getMessage());
            throw new Exception("Không thể tạo đơn dịch vụ: " . $e->getMessage());
        }
    }

    public function timKTVPhuHop($ngayDat, $maKhungGio)
    {
        try {
            // Lấy thứ trong tuần của ngày đặt (0 = Chủ nhật, 1 = Thứ 2, ..., 6 = Thứ 7)
            $thuTrongTuan = date('w', strtotime($ngayDat));
            // Chuyển đổi sang định dạng lịch làm việc (1 = CN, 2 = T2, 3 = T3, 4 = T4, 5 = T5, 6 = T6, 7 = T7)
            //$maLichLam = $thuTrongTuan == 0 ? 1 : $thuTrongTuan + 1;

            // 1. Tìm KTV không có lịch nghỉ phép và có lịch làm việc trong ngày đặt
            $sqlKTVKhongNghi = "
            SELECT nv.maND, nv.hoTen 
            FROM nguoidung nv 
            INNER JOIN hosokythuatvien hsk ON nv.maND = hsk.maKTV
            INNER JOIN lichlamviec llv ON hsk.maLLV = llv.maLLV
            LEFT JOIN lichxinnghi lxn ON nv.maND = lxn.maNV AND lxn.ngayNghi = ?
            WHERE nv.maVaiTro = 3
            AND nv.trangThaiHD = 1
            AND lxn.maNV IS NULL 
            AND FIND_IN_SET(?, llv.ngayLamViec) > 0
            ";

            $stmtKTV = $this->db->prepare($sqlKTVKhongNghi);
            $stmtKTV->execute([$ngayDat, $thuTrongTuan]);
            $danhSachKTV = $stmtKTV->fetchAll(PDO::FETCH_ASSOC);

            if (empty($danhSachKTV)) {
                // KHÔNG ném exception nữa, mà trả về null
                error_log("Không tìm thấy KTV phù hợp cho ngày $ngayDat, khung giờ $maKhungGio");
                return null;
            }

            // 2. Đếm số đơn ĐƠN GIẢN - chỉ cần ngày đặt
        $thang = date('Y-m', strtotime($ngayDat));
        $ngay = $ngayDat;

        $sqlDemDon = "
            SELECT 
                ddv.maKTV,
                COUNT(CASE WHEN DATE(ddv.ngayDat) = ? THEN 1 END) as soDonTrongNgay,
                COUNT(CASE WHEN DATE_FORMAT(ddv.ngayDat, '%Y-%m') = ? THEN 1 END) as soDonTrongThang
            FROM dondichvu ddv
            WHERE ddv.maKTV IN (" . implode(',', array_fill(0, count($danhSachKTV), '?')) . ")
            AND ddv.trangThai != 0
            AND ddv.maKhungGio != ?
            GROUP BY ddv.maKTV
        ";

        $params = [$ngay, $thang];
        $maKTVs = array_column($danhSachKTV, 'maND');
        $params = array_merge($params, $maKTVs);
        $params[] = $maKhungGio;

        $stmtDemDon = $this->db->prepare($sqlDemDon);
        $stmtDemDon->execute($params);
        $thongKeDon = $stmtDemDon->fetchAll(PDO::FETCH_ASSOC);

            // 3. Tạo mảng thông tin đầy đủ cho KTV
            $ktvThongTin = [];
            foreach ($danhSachKTV as $ktv) {
                $soDonTrongNgay = 0;
                $soDonTrongThang = 0;

                // Tìm thông tin thống kê cho KTV này
                foreach ($thongKeDon as $thongKe) {
                    if ($thongKe['maKTV'] == $ktv['maND']) {
                        $soDonTrongNgay = $thongKe['soDonTrongNgay'];
                        $soDonTrongThang = $thongKe['soDonTrongThang'];
                        break;
                    }
                }

                $ktvThongTin[] = [
                    'maND' => $ktv['maND'],
                    'tenNV' => $ktv['hoTen'],
                    'soDonTrongNgay' => $soDonTrongNgay,
                    'soDonTrongThang' => $soDonTrongThang,
                    'diemUuTien' => $soDonTrongNgay * 2 + $soDonTrongThang // Điểm ưu tiên: ít đơn hơn = tốt hơn
                ];
            }

            // 4. Sắp xếp KTV theo điểm ưu tiên (ít đơn hơn = điểm cao hơn)
            usort($ktvThongTin, function ($a, $b) {
                return $a['diemUuTien'] - $b['diemUuTien'];
            });

            // 5. Chọn KTV có ít đơn nhất
            $ktvPhuHop = $ktvThongTin[0];

            return $ktvPhuHop['maND'];

        } catch (Exception $e) {
            error_log("Lỗi khi tìm KTV phù hợp: " . $e->getMessage());
            // Trả về null khi có lỗi
            return null;
        }
    }
    public function themDonDichVuTaiCuaHang($maKH, $ngayDat, $noiSuaChua, $danhSachThietBi, $ghiChu = null, $maNhanVienTaoDon = null)
{
    try {
        // Bắt đầu transaction
        $this->db->beginTransaction();

        // Tìm KTV tại cửa hàng (trạng thái 6) không có đơn đang thực hiện
        $maKTV = $this->timKTVTaiCuaHang($ngayDat);

        // 1. Insert vào bảng dondichvu với maKTV và thông tin tại cửa hàng
        $sqlDonDichVu = "INSERT INTO dondichvu (
                             maKH, ngayDat, ghiChu, trangThai, 
                            noiSuaChua, maKTV, nhanVienTaoDon
                         ) VALUES (?, ?, ?, 1, 1, ?, ?)";

        $stmtDonDichVu = $this->db->prepare($sqlDonDichVu);

        $stmtDonDichVu->execute([
            $maKH,
            $ngayDat,
            $ghiChu,
            $maKTV ?? '', // Có thể là null nếu không tìm thấy KTV
            $maNhanVienTaoDon
        ]);

        // Lấy mã đơn vừa insert
        $maDon = $this->db->lastInsertId();
        
        error_log("Đã tạo đơn #$maDon thành công");

        // 2. Insert vào bảng chitietdondichvu cho từng thiết bị
        $sqlChiTiet = "INSERT INTO chitietdondichvu (
                          maDon, maThietBi, phienban, motaTinhTrang, trangThai
                       ) VALUES (?, ?, ?, ?, 1)";

        $stmtChiTiet = $this->db->prepare($sqlChiTiet);

        foreach ($danhSachThietBi as $thietBi) {
            error_log("Insert chi tiết đơn: maDon=$maDon, maThietBi=" . $thietBi['maThietBi'] . 
                     ", phienBan=" . $thietBi['phienBan'] . 
                     ", motaTinhTrang=" . $thietBi['motaTinhTrang']);
            
            $stmtChiTiet->execute([
                $maDon,
                $thietBi['maThietBi'],
                $thietBi['phienBan'],
                $thietBi['motaTinhTrang']
            ]); 
            
            error_log("Đã insert chi tiết đơn thành công");
        }

        // Commit transaction
        $this->db->commit();

        // Ghi log
        if ($maKTV) {
            error_log("Đơn tại cửa hàng #$maDon đã được phân công cho KTV #$maKTV");
        } else {
            error_log("Đơn tại cửa hàng #$maDon được tạo không có KTV phân công");
        }

        return $maDon;

    } catch (Exception $e) {
        // Rollback transaction nếu có lỗi
        $this->db->rollBack();

        error_log("Lỗi khi thêm đơn dịch vụ tại cửa hàng: " . $e->getMessage());
        throw new Exception("Không thể tạo đơn dịch vụ tại cửa hàng: " . $e->getMessage());
    }
}

public function timKTVTaiCuaHang($ngayDat)
{
    try {
        // Tìm KTV tại cửa hàng (trạng thái 6) không có lịch nghỉ phép trong ngày
        // VÀ không có đơn đang thực hiện (trạng thái đơn: 1 - Chờ xử lý, 2 - Đang xử lý, 3 - Chờ phụ tùng)
        $sqlKTVTaiCuaHang = "
        SELECT nv.maND, nv.hoTen 
        FROM nguoidung nv 
        LEFT JOIN lichxinnghi lxn ON nv.maND = lxn.maNV AND lxn.ngayNghi = ?
        WHERE nv.maVaiTro = 3
        AND nv.trangThaiHD = 6  -- Trạng thái KTV tại cửa hàng
        AND lxn.maNV IS NULL 
        AND NOT EXISTS (
            SELECT 1 FROM dondichvu ddv 
            WHERE ddv.maKTV = nv.maND 
            AND ddv.trangThai IN (1, 2, 3)  -- Các trạng thái đơn đang thực hiện
        )
        ";

        $stmtKTV = $this->db->prepare($sqlKTVTaiCuaHang);
        $stmtKTV->execute([$ngayDat]);
        $danhSachKTV = $stmtKTV->fetchAll(PDO::FETCH_ASSOC);

        if (empty($danhSachKTV)) {
            error_log("Không tìm thấy KTV tại cửa hàng phù hợp. Đơn sẽ được tạo không có KTV phân công.");
            return null; // Trả về null nếu không tìm thấy KTV
        }

        // Đếm số đơn của từng KTV tại cửa hàng trong ngày
        $sqlDemDon = "
            SELECT 
                ddv.maKTV,
                COUNT(CASE WHEN DATE(ddv.ngayDat) = ? THEN 1 END) as soDonTrongNgay
            FROM dondichvu ddv
            WHERE ddv.maKTV IN (" . implode(',', array_fill(0, count($danhSachKTV), '?')) . ")
            AND ddv.trangThai != 0
            AND ddv.noiSuaChua = 1  -- Đơn tại cửa hàng
            GROUP BY ddv.maKTV
        ";

        $params = [$ngayDat];
        $maKTVs = array_column($danhSachKTV, 'maND');
        $params = array_merge($params, $maKTVs);

        $stmtDemDon = $this->db->prepare($sqlDemDon);
        $stmtDemDon->execute($params);
        $thongKeDon = $stmtDemDon->fetchAll(PDO::FETCH_ASSOC);

        // Tạo mảng thông tin cho KTV tại cửa hàng
        $ktvThongTin = [];
        foreach ($danhSachKTV as $ktv) {
            $soDonTrongNgay = 0;

            // Tìm thông tin thống kê cho KTV này
            foreach ($thongKeDon as $thongKe) {
                if ($thongKe['maKTV'] == $ktv['maND']) {
                    $soDonTrongNgay = $thongKe['soDonTrongNgay'];
                    break;
                }
            }

            $ktvThongTin[] = [
                'maND' => $ktv['maND'],
                'tenNV' => $ktv['hoTen'],
                'soDonTrongNgay' => $soDonTrongNgay
            ];
        }

        // Sắp xếp KTV theo số đơn trong ngày (ít đơn hơn = ưu tiên hơn)
        usort($ktvThongTin, function ($a, $b) {
            return $a['soDonTrongNgay'] - $b['soDonTrongNgay'];
        });

        // Chọn KTV có ít đơn nhất
        $ktvPhuHop = $ktvThongTin[0];

        error_log("Đã chọn KTV tại cửa hàng: " . $ktvPhuHop['maND'] . " - Số đơn trong ngày: " . $ktvPhuHop['soDonTrongNgay']);

        return $ktvPhuHop['maND'];

    } catch (Exception $e) {
        error_log("Lỗi khi tìm KTV tại cửa hàng: " . $e->getMessage());
        return null; // Trả về null nếu có lỗi
    }
}
}