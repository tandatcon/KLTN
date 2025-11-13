<?php
/**
 * FILE: classes/DichVuService.php  
 * CLASS QUẢN LÝ DỊCH VỤ - TECH CARE (OOP VERSION)
 */

require_once __DIR__ . '/ketnoi.php';

class DichVuService
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * Lấy danh sách đơn dịch vụ
     */
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

    /**
     * Lấy chi tiết đơn dịch vụ
     */
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

    /**
     * Tạo đơn dịch vụ mới
     */
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

    /**
     * Hủy đơn dịch vụ
     */
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

    /**
     * Cập nhật trạng thái đơn
     */
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

    /**
     * Lấy danh sách thiết bị
     */
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

    //lay tt KTV tren don hang
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

    

    public function layTongKTVLamViec($ngay)
    {
        try {
            $sql = "SELECT COUNT(DISTINCT nv.maND) as soLuong 
                    FROM NguoiDung nv
                    WHERE nv.maVaiTro = 3 
                    AND nv.trangThaiHD = 1 
                    AND nv.maND NOT IN (
                        SELECT maNV FROM lichxinnghi 
                        WHERE ngayNghi = ?
                    )";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ngay]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? (int) $result['soLuong'] : 0;
        } catch (PDOException $e) {
            error_log("Lỗi lấy số lượng KTV: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lấy số đơn hoàn thành trong ngày (tại các khung giờ đã qua)
     */
public function layDonConLaiTaiKhungGio($ngay, $gioHienTai)
{
    try {
        // 🔥 LẤY DANH SÁCH KHUNG GIỜ ĐỂ TẠO MAPPING
        $sqlKhungGio = "SELECT * FROM bangKhungGio ORDER BY gioBatDau";
        $stmtKhungGio = $this->db->query($sqlKhungGio);
        $khungGios = $stmtKhungGio->fetchAll(PDO::FETCH_ASSOC);
        
        // Tạo mapping: số thứ tự → maKhungGio thực tế
        $mapping = [];
        foreach ($khungGios as $index => $khung) {
            $mapping[$index + 1] = $khung['maKhungGio']; // 1 → khung đầu tiên, 2 → khung thứ hai, ...
        }
        
        error_log("Mapping khung giờ: " . json_encode($mapping));
        
        // Lấy đơn hàng
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
            
            // 🔥 ÁNH XẠ: từ số (1,2,3...) sang maKhungGio thực tế
            $maKhungGio = $mapping[$maKhungGioSo] ?? $row['maKhungGio'];
            
            $donTheoKhungGio[$maKhungGio] = [
                'soDon' => (int) $row['soDon'],
                'khoangGio' => $row['khoangGio'],
                'gioChan' => (int) $row['gioChan']
            ];
            $tongDonConLai += (int) $row['soDon'];
        }
        
        error_log("Số đơn còn lại tại khung giờ: $tongDonConLai");
        error_log("Chi tiết đơn còn lại (SAU MAPPING): " . json_encode($donTheoKhungGio));
        
        return [
            'tong' => $tongDonConLai,
            'chi_tiet' => $donTheoKhungGio
        ];
    } catch (Exception $e) {
        error_log("Lỗi lấy đơn còn lại: " . $e->getMessage());
        return ['tong' => 0, 'chi_tiet' => []];
    }
}

/**
 * Lấy số đơn hoàn thành trong ngày (tại các khung giờ đã qua) - ĐÃ SỬA LỖI MAPPING
 */
public function layDonHoanThanhTaiKhungGioDaQua($ngay, $gioHienTai)
    {
        try {
            // 🔥 THÊM PHẦN TẠO MAPPING (giống như trong layDonConLaiTaiKhungGio)
            $sqlKhungGio = "SELECT * FROM bangKhungGio ORDER BY gioBatDau";
            $stmtKhungGio = $this->db->query($sqlKhungGio);
            $khungGios = $stmtKhungGio->fetchAll(PDO::FETCH_ASSOC);
            
            // Tạo mapping: số thứ tự → maKhungGio thực tế
            $mapping = [];
            foreach ($khungGios as $index => $khung) {
                $mapping[$index + 1] = $khung['maKhungGio'];
            }
            
            error_log("Mapping khung giờ (đơn hoàn thành): " . json_encode($mapping));
            
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
                
                // 🔥 CHUYỂN ĐỔI: từ số (1,2,3...) sang maKhungGio thực tế
                $maKhungGio = $mapping[$maKhungGioSo] ?? $row['maKhungGio'];
                
                $donTheoKhungGio[$maKhungGio] = [
                    'soDon' => (int) $row['soDon'],
                    'khoangGio' => $row['khoangGio'],
                    'gioChan' => (int) $row['gioChan']
                ];
                $tongDonHoanThanh += (int) $row['soDon'];
            }
            
            error_log("Số đơn hoàn thành tại khung giờ đã qua: $tongDonHoanThanh");
            error_log("Chi tiết đơn hoàn thành (SAU MAPPING): " . json_encode($donTheoKhungGio));
            
            return [
                'tong' => $tongDonHoanThanh,
                'chi_tiet' => $donTheoKhungGio
            ];
        } catch (Exception $e) {
            error_log("Lỗi lấy đơn hoàn thành: " . $e->getMessage());
            return ['tong' => 0, 'chi_tiet' => []];
        }
    }

    /**
     * Tính slot khả dụng - ĐÃ SỬA LỖI OUTPUT HTML
     */
    /**
 * Tính slot khả dụng - ĐÃ SỬA LỖI KIỂM TRA THỜI GIAN
 */
public function tinhSlotKhaDung($ngay, $gioHienTai = null)
{
    if ($gioHienTai === null) {
        $gioHienTai = (int) date('H');
    }

    $ngayHienTai = date('Y-m-d');
    
    error_log("=== TÍNH SLOT CHO NGÀY: $ngay - GIỜ HIỆN TẠI: $gioHienTai ===");
    error_log("📅 Ngày hiện tại: $ngayHienTai");

    // 1. Lấy tổng KTV làm việc
    $tongKTV = $this->layTongKTVLamViec($ngay);
    
    // 2. Lấy số đơn hoàn thành tại khung giờ đã qua
    $donHoanThanh = $this->layDonHoanThanhTaiKhungGioDaQua($ngay, $gioHienTai);
    
    // 3. Lấy số đơn còn lại tại khung giờ
    $donConLai = $this->layDonConLaiTaiKhungGio($ngay, $gioHienTai);
    
    // 4. Lấy danh sách khung giờ
    $danhSachKhungGio = $this->layDanhSachKhungGio();
    $soKhungGio = count($danhSachKhungGio);

    error_log("Tổng KTV: $tongKTV / Số khung giờ: $soKhungGio");
    error_log("Tổng đơn hoàn thành: {$donHoanThanh['tong']}");
    error_log("Tổng đơn còn lại: {$donConLai['tong']}");

    // 5. Phân bổ KTV cho các khung giờ (PHÂN BỔ ĐỀU)
    $phanBoKTV = $this->phanBoKTV($tongKTV, $soKhungGio);

    // 6. Tính toán KTV dư từ các khung giờ đã qua
    $ktvDuTheoKhung = $this->tinhKTVDuTheoKhung($ngay, $gioHienTai, $danhSachKhungGio, $phanBoKTV, $donConLai);
    
    // 7. Phân bổ slot từ đơn hoàn thành cho các khung giờ còn lại
    $phanBoSlotTuDonHoanThanh = $this->phanBoSlotTuDonHoanThanh(
        $donHoanThanh['tong'], 
        $danhSachKhungGio, 
        $gioHienTai
    );

    // 8. Phân bổ thêm KTV dư cho các khung giờ còn lại
    $phanBoKTVDu = $this->phanBoKTVDuThongMinh($ktvDuTheoKhung, $danhSachKhungGio, $gioHienTai);

    $ketQua = [];

    foreach ($danhSachKhungGio as $index => $khungGio) {
        $maKhungGio = $khungGio['maKhungGio'];
        $gioBatDau = (int) $khungGio['gioBatDau'];
        $gioChan = (int) $khungGio['gioChan'];

        // 🔥 SỬA: KIỂM TRA CẢ NGÀY VÀ GIỜ
        $daQuaGio = false;
        
        if ($ngay === $ngayHienTai) {
            // Nếu là ngày hôm nay: kiểm tra giờ hiện tại
            $daQuaGio = ($gioHienTai >= $gioChan);
            error_log("📅 Ngày hôm nay - Khung {$khungGio['khoangGio']}: Giờ hiện tại $gioHienTai >= $gioChan ? " . ($daQuaGio ? 'YES' : 'NO'));
        } else if (strtotime($ngay) < strtotime($ngayHienTai)) {
            // Nếu là ngày trong quá khứ: tất cả đều qua giờ
            $daQuaGio = true;
            error_log("📅 Ngày quá khứ - Khung {$khungGio['khoangGio']}: Đã qua");
        } else {
            // Nếu là ngày trong tương lai: không khung giờ nào qua giờ
            $daQuaGio = false;
            error_log("📅 Ngày tương lai - Khung {$khungGio['khoangGio']}: Chưa qua");
        }
        
        // Số KTV được phân bổ ban đầu cho khung giờ này
        $soKTVPhanBo = $phanBoKTV[$index] ?? 0;
        
        // Số KTV dư được phân bổ thêm
        $soKTVDuPhanBo = $phanBoKTVDu[$maKhungGio] ?? 0;
        
        // Tổng KTV thực tế = KTV phân bổ ban đầu + KTV dư
        $tongKTVThucTe = $soKTVPhanBo + $soKTVDuPhanBo;

        // Số slot từ đơn hoàn thành được phân bổ cho khung giờ này
        $slotTuDonHoanThanh = $phanBoSlotTuDonHoanThanh[$maKhungGio] ?? 0;

        // Tổng slot = KTV thực tế + slot từ đơn hoàn thành
        $slotToiDa = $tongKTVThucTe + $slotTuDonHoanThanh;

        // Lấy số đơn đã đặt tại khung giờ này
        $soDonDaDat = $donConLai['chi_tiet'][$maKhungGio]['soDon'] ?? 0;
        
        // Tính slot khả dụng
        $khaDung = max(0, $slotToiDa - $soDonDaDat);
        
        // Vô hiệu hóa nếu đã qua giờ hoặc không có slot khả dụng
        $voHieuHoa = $daQuaGio || $khaDung <= 0;

        // Xác định lý do
        if ($daQuaGio) {
            $lyDo = 'Đã qua giờ';
        } elseif ($tongKTVThucTe === 0 && $slotTuDonHoanThanh === 0) {
            $lyDo = 'Không có KTV và slot';
        } elseif ($khaDung <= 0) {
            $lyDo = 'Đã hết slot';
        } else {
            $lyDo = 'Có thể đặt';
        }

        $ketQua[$maKhungGio] = [
            'pham_vi' => $khungGio['khoangGio'],
            'toi_da' => $slotToiDa,
            'da_dat' => $soDonDaDat,
            'kha_dung' => $khaDung,
            'tong_ktv' => $tongKTV,
            'ktv_phan_bo' => $soKTVPhanBo,
            'ktv_du_phan_bo' => $soKTVDuPhanBo,
            'tong_ktv_thuc_te' => $tongKTVThucTe,
            'slot_tu_don_hoan_thanh' => $slotTuDonHoanThanh,
            'tong_don_hoan_thanh' => $donHoanThanh['tong'],
            'vo_hieu_hoa' => $voHieuHoa,
            'da_qua_gio' => $daQuaGio,
            'gio_bat_dau' => $gioBatDau,
            'gio_ket_thuc' => $gioChan,
            'ly_do' => $lyDo
        ];

        error_log("Khung {$khungGio['khoangGio']}: Qua giờ: " . ($daQuaGio ? 'YES' : 'NO') . ", KTV phân bổ: $soKTVPhanBo, KTV dư: $soKTVDuPhanBo, Slot từ đơn HT: $slotTuDonHoanThanh, Tổng slot: $slotToiDa, Đã đặt: $soDonDaDat, Khả dụng: $khaDung");
    }

    return $ketQua;
}

    /**
     * Lấy danh sách khung giờ
     */
    public function layDanhSachKhungGio()
    {
        try {
            $sql = "SELECT * FROM bangKhungGio ORDER BY gioBatDau";
            $stmt = $this->db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Danh sách khung giờ: " . json_encode($results));
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
    /**
 * Tính số KTV dư từ các khung giờ đã qua - SỬA LẠI: KIỂM TRA CẢ NGÀY
 */
/**
 * Tính số KTV dư từ các khung giờ đã qua - SỬA LẠI: KIỂM TRA CẢ NGÀY
 */
private function tinhKTVDuTheoKhung($ngay, $gioHienTai, $danhSachKhungGio, $phanBoKTV, $donConLai)
{
    $ktvDuTheoKhung = [];
    $ngayHienTai = date('Y-m-d');

    foreach ($danhSachKhungGio as $index => $khungGio) {
        $maKhungGio = $khungGio['maKhungGio'];
        $gioChan = (int) $khungGio['gioChan'];

        // 🔥 SỬA: CHỈ TÍNH KTV DƯ TỪ CÁC KHUNG GIỜ ĐÃ QUA (CHỈ ÁP DỤNG CHO NGÀY HÔM NAY)
        $daQuaGio = false;
        
        if ($ngay === $ngayHienTai) {
            // Chỉ tính KTV dư từ các khung giờ đã qua trong ngày hôm nay
            $daQuaGio = ($gioHienTai >= $gioChan);
        }
        // Ngày khác không tính KTV dư vì chưa biết thực tế

        if ($daQuaGio) {
            $soKTVPhanBo = $phanBoKTV[$index] ?? 0;
            $soDonDaDat = $donConLai['chi_tiet'][$maKhungGio]['soDon'] ?? 0;
            
            // KTV dư = KTV phân bổ - số đơn thực tế (nếu > 0)
            $ktvDu = max(0, $soKTVPhanBo - $soDonDaDat);
            $ktvDuTheoKhung[$maKhungGio] = $ktvDu;
            
            error_log("Khung {$khungGio['khoangGio']} đã qua: KTV phân bổ: $soKTVPhanBo, Đơn thực tế: $soDonDaDat, KTV dư: $ktvDu");
        } else {
            $ktvDuTheoKhung[$maKhungGio] = 0;
        }
    }

    return $ktvDuTheoKhung;
}

    /**
     * Phân bổ KTV dư cho các khung giờ còn lại - SỬA LẠI LOGIC
     */
    private function phanBoKTVDuThongMinh($ktvDuTheoKhung, $danhSachKhungGio, $gioHienTai)
    {
        $phanBo = [];
        
        // Khởi tạo phân bổ
        foreach ($danhSachKhungGio as $khungGio) {
            $phanBo[$khungGio['maKhungGio']] = 0;
        }

        // Tính tổng KTV dư
        $tongKTVDu = array_sum($ktvDuTheoKhung);
        
        if ($tongKTVDu <= 0) {
            error_log("Không có KTV dư để phân bổ");
            return $phanBo;
        }

        // Tìm các khung giờ CHƯA QUA để phân bổ
        $khungGioChuaQua = [];
        foreach ($danhSachKhungGio as $khungGio) {
            if ($gioHienTai < $khungGio['gioChan']) {
                $khungGioChuaQua[] = $khungGio;
            }
        }

        $soKhungGioChuaQua = count($khungGioChuaQua);

        if ($soKhungGioChuaQua <= 0) {
            error_log("Tất cả khung giờ đã qua, không phân bổ KTV dư");
            return $phanBo;
        }

        error_log("Phân bổ $tongKTVDu KTV dư cho $soKhungGioChuaQua khung giờ chưa qua");

        // Phân bổ đều KTV dư cho các khung giờ chưa qua
        $ktvDuConLai = $tongKTVDu;
        
        while ($ktvDuConLai > 0) {
            foreach ($khungGioChuaQua as $khungGio) {
                if ($ktvDuConLai <= 0) break;
                
                $maKhungGio = $khungGio['maKhungGio'];
                $phanBo[$maKhungGio]++;
                $ktvDuConLai--;
                
                error_log("Phân bổ 1 KTV dư cho khung $maKhungGio");
            }
        }

        error_log("Kết quả phân bổ KTV dư: " . json_encode($phanBo));
        return $phanBo;
    }

    /**
     * Phân bổ KTV cho các khung giờ - PHÂN BỔ ĐỀU
     */
    private function phanBoKTV($tongKTV, $soKhungGio)
    {
        $phanBo = array_fill(0, $soKhungGio, 0);
        
        error_log("Phân bổ $tongKTV KTV cho $soKhungGio khung giờ");

        if ($tongKTV <= 0) {
            error_log("Không có KTV nào để phân bổ");
            return $phanBo;
        }

        // Nếu số KTV <= số khung giờ: mỗi khung giờ 1 KTV theo thứ tự
        if ($tongKTV <= $soKhungGio) {
            for ($i = 0; $i < $tongKTV; $i++) {
                $phanBo[$i] = 1;
            }
        } else {
            // Nếu số KTV > số khung giờ: phân bổ đều
            // Bước 1: Mỗi khung giờ được ít nhất 1 KTV
            for ($i = 0; $i < $soKhungGio; $i++) {
                $phanBo[$i] = 1;
            }
            
            // Bước 2: Phân bổ KTV còn lại đều cho các khung giờ
            $ktvConLai = $tongKTV - $soKhungGio;
            $index = 0;
            
            while ($ktvConLai > 0) {
                $phanBo[$index]++;
                $ktvConLai--;
                $index = ($index + 1) % $soKhungGio;
            }
        }

        error_log("Kết quả phân bổ KTV: " . implode(', ', $phanBo));
        return $phanBo;
    }

    /**
     * Phân bổ slot từ đơn hoàn thành cho các khung giờ còn lại
     */
    private function phanBoSlotTuDonHoanThanh($tongDonHoanThanh, $danhSachKhungGio, $gioHienTai)
    {
        $phanBo = [];
        
        if ($tongDonHoanThanh <= 0) {
            error_log("Không có đơn hoàn thành để phân bổ");
            foreach ($danhSachKhungGio as $khungGio) {
                $phanBo[$khungGio['maKhungGio']] = 0;
            }
            return $phanBo;
        }

        // Chỉ phân bổ cho các khung giờ chưa qua
        $khungGioChuaQua = [];
        foreach ($danhSachKhungGio as $khungGio) {
            if ($gioHienTai < $khungGio['gioChan']) {
                $khungGioChuaQua[] = $khungGio;
            }
        }

        $soKhungGioChuaQua = count($khungGioChuaQua);

        if ($soKhungGioChuaQua <= 0) {
            error_log("Tất cả khung giờ đã qua, không phân bổ slot từ đơn hoàn thành");
            foreach ($danhSachKhungGio as $khungGio) {
                $phanBo[$khungGio['maKhungGio']] = 0;
            }
            return $phanBo;
        }

        error_log("Phân bổ $tongDonHoanThanh slot từ đơn hoàn thành cho $soKhungGioChuaQua khung giờ chưa qua");

        // Phân bổ đều slot từ đơn hoàn thành cho các khung giờ chưa qua
        $slotConLai = $tongDonHoanThanh;
        
        // Tính số slot cơ bản cho mỗi khung giờ
        $slotCoBan = floor($tongDonHoanThanh / $soKhungGioChuaQua);
        
        foreach ($danhSachKhungGio as $khungGio) {
            $maKhungGio = $khungGio['maKhungGio'];
            
            if ($gioHienTai < $khungGio['gioChan']) {
                // Khung giờ chưa qua: được phân bổ slot cơ bản
                $phanBo[$maKhungGio] = $slotCoBan;
                $slotConLai -= $slotCoBan;
            } else {
                // Khung giờ đã qua: không được phân bổ
                $phanBo[$maKhungGio] = 0;
            }
        }

        // Phân bổ slot còn lại cho các khung giờ đầu
        if ($slotConLai > 0) {
            foreach ($khungGioChuaQua as $khungGio) {
                if ($slotConLai <= 0) break;
                
                $maKhungGio = $khungGio['maKhungGio'];
                $phanBo[$maKhungGio]++;
                $slotConLai--;
            }
        }

        error_log("Kết quả phân bổ slot từ đơn hoàn thành: " . json_encode($phanBo));
        return $phanBo;
    }

    /**
     * Debug thông tin
     */
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
}