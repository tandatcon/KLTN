<?php
/**
 * CLASS TEST SLOT SERVICE - PHÂN BỔ ĐỀU KTV + CẬP NHẬT SLOT TỪ ĐƠN HOÀN THÀNH
 */

require_once __DIR__ . '/ketnoi.php';

class TestSlotService
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * Lấy tổng số KTV làm việc trong ngày
     */
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
        
        // 🔥 QUAN TRỌNG: Tạo mapping từ số sang KG
        
        
        foreach ($results as $row) {
            $maKhungGioSo = (int) $row['maKhungGio'];
            
            // 🔥 CHUYỂN ĐỔI: từ 1 → 'KG1', từ 2 → 'KG2', ...
            $maKhungGio = $mapping[$maKhungGioSo] ?? 'KG' . $maKhungGioSo;
            
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

    private function tinhKTVConLai($tongKTV, $ngay, $gioHienTai, $danhSachKhungGio, $donConLai)
{
    $ktvDaDung = 0;

    foreach ($danhSachKhungGio as $khung) {
        $gioChan = (int) $khung['gioChan'];
        $maKhungGio = $khung['maKhungGio'];

        // Chỉ tính khung ĐÃ QUA
        if ($gioHienTai >= $gioChan) {
            $soDon = $donConLai['chi_tiet'][$maKhungGio]['soDon'] ?? 0;
            $ktvDaDung += $soDon; // Mỗi đơn = 1 KTV đã được dùng
        }
    }

    $ktvConLai = max(0, $tongKTV - $ktvDaDung);

    error_log("Tổng KTV: $tongKTV | KTV đã dùng ở khung đã qua: $ktvDaDung | KTV còn lại: $ktvConLai");

    return $ktvConLai;
}
    /**
     * Tính slot khả dụng - SỬA LẠI TOÀN BỘ LOGIC
     */
    // public function layDonHoanThanhTaiKhungGioDaQua($ngay, $gioHienTai)
    // {
    //     try {
    //         // 🔥 THÊM PHẦN TẠO MAPPING (giống như trong layDonConLaiTaiKhungGio)
    //         $sqlKhungGio = "SELECT * FROM bangKhungGio ORDER BY gioBatDau";
    //         $stmtKhungGio = $this->db->query($sqlKhungGio);
    //         $khungGios = $stmtKhungGio->fetchAll(PDO::FETCH_ASSOC);
            
    //         // Tạo mapping: số thứ tự → maKhungGio thực tế
    //         $mapping = [];
    //         foreach ($khungGios as $index => $khung) {
    //             $mapping[$index + 1] = $khung['maKhungGio'];
    //         }
            
    //         error_log("Mapping khung giờ (đơn hoàn thành): " . json_encode($mapping));
            
    //         $sql = "SELECT dd.maKhungGio, kg.khoangGio, kg.gioChan, COUNT(*) as soDon
    //                 FROM DonDichVu dd
    //                 JOIN bangKhungGio kg ON dd.maKhungGio = kg.maKhungGio
    //                 WHERE dd.ngayDat = ? 
    //                 AND dd.trangThai = 3 
    //                 AND kg.gioChan < ?
    //                 GROUP BY dd.maKhungGio, kg.khoangGio, kg.gioChan";
            
    //         $stmt = $this->db->prepare($sql);
    //         $stmt->execute([$ngay, $gioHienTai]);
    //         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
    //         $tongDonHoanThanh = 0;
    //         $donTheoKhungGio = [];
            
    //         foreach ($results as $row) {
    //             $maKhungGioSo = (int) $row['maKhungGio'];
                
    //             // 🔥 CHUYỂN ĐỔI: từ số (1,2,3...) sang maKhungGio thực tế
    //             $maKhungGio = $mapping[$maKhungGioSo] ?? $row['maKhungGio'];
                
    //             $donTheoKhungGio[$maKhungGio] = [
    //                 'soDon' => (int) $row['soDon'],
    //                 'khoangGio' => $row['khoangGio'],
    //                 'gioChan' => (int) $row['gioChan']
    //             ];
    //             $tongDonHoanThanh += (int) $row['soDon'];
    //         }
            
    //         error_log("Số đơn hoàn thành tại khung giờ đã qua: $tongDonHoanThanh");
    //         error_log("Chi tiết đơn hoàn thành: " . json_encode($donTheoKhungGio));
            
    //         return [
    //             'tong' => $tongDonHoanThanh,
    //             'chi_tiet' => $donTheoKhungGio
    //         ];
    //     } catch (Exception $e) {
    //         error_log("Lỗi lấy đơn hoàn thành: " . $e->getMessage());
    //         return ['tong' => 0, 'chi_tiet' => []];
    //     }
    // }

    /**
     * Tính slot khả dụng - ĐÃ SỬA LỖI OUTPUT HTML
     */
    public function tinhSlotKhaDung($ngay, $gioHienTai = null)
{
    if ($gioHienTai === null) {
        $gioHienTai = (int) date('H');
    }

    //$tongKTV = $this->layTongKTVLamViec($ngay);
    $tongKTV = 6;
    $donHoanThanh = $this->layDonHoanThanhTaiKhungGioDaQua($ngay, $gioHienTai);
    $donConLai = $this->layDonConLaiTaiKhungGio($ngay, $gioHienTai);
    $danhSachKhungGio = $this->layDanhSachKhungGio();

    // BƯỚC MỚI: TÍNH KTV CÒN LẠI SAU KHI TRỪ KHUNG ĐÃ QUA
    $ktvConLai = $this->tinhKTVConLai($tongKTV, $ngay, $gioHienTai, $danhSachKhungGio, $donConLai);

    // PHÂN BỔ CHỈ CHO KHUNG CHƯA QUA, DÙNG KTV CÒN LẠI
    $phanBoKTV = $this->phanBoKTV($ktvConLai, $danhSachKhungGio, $gioHienTai);

    // Tính KTV dư từ khung đã qua (vẫn cần để tận dụng nếu có dư)
    $ktvDuTheoKhung = $this->tinhKTVDuTheoKhung($ngay, $gioHienTai, $danhSachKhungGio, $phanBoKTV, $donConLai);
    $phanBoKTVDu = $this->phanBoKTVDuThongMinh($ktvDuTheoKhung, $danhSachKhungGio, $gioHienTai);

    // Phân bổ slot từ đơn hoàn thành
    $phanBoSlotTuDonHoanThanh = $this->phanBoSlotTuDonHoanThanh(
        $donHoanThanh['tong'],
        $danhSachKhungGio,
        $gioHienTai,
        $phanBoKTV  // Truyền thêm để biết khung nào còn trống
    );
    $ketQua = [];

    foreach ($danhSachKhungGio as $khungGio) {
        $maKhungGio = $khungGio['maKhungGio'];
        $gioChan = (int) $khungGio['gioChan'];
        $daQuaGio = ($gioHienTai >= $gioChan);

        $soKTVPhanBo = $phanBoKTV[$maKhungGio] ?? 0;
        $soKTVDuPhanBo = $phanBoKTVDu[$maKhungGio] ?? 0;
        $tongKTVThucTe = $soKTVPhanBo + $soKTVDuPhanBo;

        $slotTuDonHoanThanh = $phanBoSlotTuDonHoanThanh[$maKhungGio] ?? 0;
        $slotToiDa = $tongKTVThucTe + $slotTuDonHoanThanh;
        $soDonDaDat = $donConLai['chi_tiet'][$maKhungGio]['soDon'] ?? 0;
        $khaDung = max(0, $slotToiDa - $soDonDaDat);

        $ketQua[$maKhungGio] = [
            'pham_vi' => $khungGio['khoangGio'],
            'toi_da' => $slotToiDa,
            'da_dat' => $soDonDaDat,
            'kha_dung' => $khaDung,
            'tong_ktv_thuc_te' => $tongKTVThucTe,
            'slot_tu_don_hoan_thanh' => $slotTuDonHoanThanh,
            'vo_hieu_hoa' => $daQuaGio || $khaDung <= 0,
            'da_qua_gio' => $daQuaGio,
            'ly_do' => $daQuaGio ? 'Đã qua giờ' : ($khaDung <= 0 ? 'Hết slot' : 'Có thể đặt')
        ];
    }

    return $ketQua;
}

    /**
     * Tính số KTV dư từ các khung giờ đã qua - SỬA LẠI: DÙNG ĐƠN CÒN LẠI
     */
    private function tinhKTVDuTheoKhung($ngay, $gioHienTai, $danhSachKhungGio, $phanBoKTV, $donConLai)
    {
        $ktvDuTheoKhung = [];

        foreach ($danhSachKhungGio as $index => $khungGio) {
            $maKhungGio = $khungGio['maKhungGio'];
            $gioChan = (int) $khungGio['gioChan'];

            // Chỉ tính KTV dư từ các khung giờ đã qua
            if ($gioHienTai >= $gioChan) {
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
    /**
 * PHÂN BỔ KTV CHỈ CHO CÁC KHUNG GIỜ CHƯA QUA
 */
private function phanBoKTV($ktvConLai, $danhSachKhungGio, $gioHienTai)
{
    $phanBo = [];
    $khungChuaQua = [];

    // Lấy danh sách khung chưa qua
    foreach ($danhSachKhungGio as $khung) {
        if ($gioHienTai < $khung['gioChan']) {
            $khungChuaQua[] = $khung['maKhungGio'];
            $phanBo[$khung['maKhungGio']] = 0;
        }
    }

    $soKhung = count($khungChuaQua);
    if ($soKhung == 0 || $ktvConLai == 0) return $phanBo;

    // PHÂN BỔ ĐỀU: LUÂN PHIÊN TỪ ĐẦU ĐẾN CUỐI
    for ($i = 0; $i < $ktvConLai; $i++) {
        $maKhungGio = $khungChuaQua[$i % $soKhung]; // Luân phiên
        $phanBo[$maKhungGio]++;
    }

    error_log("Phân bổ KTV đều (luân phiên): " . json_encode($phanBo));
    return $phanBo;
}

    /**
     * Phân bổ slot từ đơn hoàn thành cho các khung giờ còn lại
     */
    private function phanBoSlotTuDonHoanThanh($tongDonHoanThanh, $danhSachKhungGio, $gioHienTai, $phanBoKTV)
{
    $phanBo = [];

    // Khởi tạo: 0 cho mọi khung
    foreach ($danhSachKhungGio as $khung) {
        $phanBo[$khung['maKhungGio']] = 0;
    }

    if ($tongDonHoanThanh <= 0) {
        return $phanBo;
    }

    // Bước 1: Lấy danh sách khung chưa qua
    $khungChuaQua = [];
    foreach ($danhSachKhungGio as $khung) {
        if ($gioHienTai < $khung['gioChan']) {
            $khungChuaQua[] = $khung;
        }
    }

    if (empty($khungChuaQua)) {
        return $phanBo;
    }

    // Bước 2: ƯU TIÊN khung CHƯA CÓ KTV (có slot trống)
    $khungTrong = [];
    $khungDaCo = [];

    foreach ($khungChuaQua as $khung) {
        $ma = $khung['maKhungGio'];
        $ktvHienTai = $phanBoKTV[$ma] ?? 0;
        if ($ktvHienTai == 0) {
            $khungTrong[] = $ma;
        } else {
            $khungDaCo[] = $ma;
        }
    }

    $slotConLai = $tongDonHoanThanh;

    // Bước 3: Điền vào khung trống trước
    if (!empty($khungTrong)) {
        foreach ($khungTrong as $ma) {
            if ($slotConLai <= 0) break;
            $phanBo[$ma]++;
            $slotConLai--;
        }
    }

    // Bước 4: Nếu còn dư → mới điền vào khung đã có KTV
    if ($slotConLai > 0 && !empty($khungDaCo)) {
        foreach ($khungDaCo as $ma) {
            if ($slotConLai <= 0) break;
            $phanBo[$ma]++;
            $slotConLai--;
        }
    }

    // Bước 5: Nếu vẫn còn dư → chia đều cho tất cả khung chưa qua
    if ($slotConLai > 0) {
        $i = 0;
        $soKhung = count($khungChuaQua);
        while ($slotConLai > 0) {
            $ma = $khungChuaQua[$i % $soKhung]['maKhungGio'];
            $phanBo[$ma]++;
            $slotConLai--;
            $i++;
        }
    }

    error_log("Phân bổ slot HT thông minh: " . json_encode($phanBo));
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