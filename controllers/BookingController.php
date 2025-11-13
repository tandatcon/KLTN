<?php
require_once __DIR__ . '/../models/devices.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/User.php';
class BookingController
{
    private $deviceModel;
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
        $this->deviceModel = new thietbi($db);
    }

    /**
     * Hiển thị trang đặt dịch vụ
     */
    public function showBookingPage()
    {
        session_start();

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để đặt dịch vụ!";
            header("Location: " . url('login'));
            exit;
        }

        // Lấy danh sách thiết bị từ CSDL
        $devices = $this->deviceModel->getAllDevices();

        return [
            'devices' => $devices
        ];
    }

    /**
     * Xử lý đặt dịch vụ
     */
    public function processBooking()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để đặt dịch vụ!";
            header("Location: " . url('login'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Method không hợp lệ!";
            header("Location: " . url('datdichvu'));
            exit;
        }

        // Xử lý dữ liệu form đặt dịch vụ
        $user_id = $_SESSION['user_id'];
        $customer_name = $_POST['customer_name'] ?? '';
        $customer_phone = $_POST['customer_phone'] ?? '';
        $customer_address = $_POST['customer_address'] ?? '';
        $booking_date = $_POST['booking_date'] ?? '';
        $booking_time = $_POST['booking_time'] ?? '';
        $problem_description = $_POST['problem_description'] ?? '';

        $device_types = $_POST['device_types'] ?? [];
        $device_models = $_POST['device_models'] ?? [];
        $device_problems = $_POST['device_problems'] ?? [];

        // TODO: Thêm logic xử lý đặt dịch vụ vào CSDL
        // Lưu vào bảng DonDichVu và ChiTietDonDichVu

        $_SESSION['success'] = "Đặt dịch vụ thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.";
        header("Location: " . url('my_orders'));
        exit;
    }
    //Kiểm tra xem ngày và giờ đặt có kín lịch không
    // Thêm vào BookingController.php
    public function getBookedSchedules()
    {
        try {
            $sql = "SELECT ngayDat, gioDat, COUNT(*) as count 
                FROM dondichvu 
                WHERE ngayDat >= CURDATE() 
                -- AND trangThai NOT IN ('huy', 'hoan_thanh')
                GROUP BY ngayDat, gioDat";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Chuyển đổi thành định dạng dễ sử dụng
            $schedules = [];
            foreach ($results as $row) {
                $key = $row['ngayDat'] . '_' . $row['gioDat'];
                $schedules[$key] = $row['count'];
            }

            return $schedules;

        } catch (Exception $e) {
            // Trả về mảng rỗng nếu có lỗi
            return [];
        }
    }


    // Trong BookingController.php

    // Hàm lấy số lượng KTV
    public function getTotalTechnicians()
    {
        $sql = "SELECT COUNT(*) as total FROM nguoidung WHERE maVaiTro = 3 ";
        $result = $this->db->query($sql);
        return $result->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Hàm lấy số lượng đặt lịch theo ngày và ca
    public function getBookingCountByDateAndShift($date, $shift)
    {
        $sql = "SELECT COUNT(*) as count FROM dondichvu
            WHERE ngayDat = ? AND gioDat = ? AND trangThai != 4"; // trạng thái != 4 (đã hủy)
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date, $shift]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    // Hàm kiểm tra KTV đã hoàn thành đơn trong ca sáng
    public function getCompletedMorningBookings($date)
    {
        $sql = "SELECT COUNT(DISTINCT b.id_nhanvien) as completed_count 
            FROM dondichvu a join
            chitietdondichvu b on a.maDon=b.maDon
            WHERE ngayDat = ? AND gioDat = 1 AND a.trangThai = 3"; // gioDat = 1 (sáng), trangThai = 3 (hoàn thành)
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['completed_count'];
    }

    // Hàm lấy thông tin slot available
    public function getAvailableSlots()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $totalTechnicians = $this->getTotalTechnicians();
        $today = date('Y-m-d');
        $currentHour = date('H');

        $slots = [];

        // Slot sáng (1) - 7:30-12:00
        $morningBookings = $this->getBookingCountByDateAndShift($today, 1);
        $morningCompleted = $this->getCompletedMorningBookings($today);

        // Tính slot available cho sáng
        $morningMax = ceil($totalTechnicians * 0.5); // 50% KTV
        $morningAvailable = $morningMax - $morningBookings;

        // Nếu đã qua 12:00, không cho đặt sáng nữa
        $morningDisabled = ($currentHour >= 12);

        // Slot chiều (2) - 13:00-18:00
        $afternoonBookings = $this->getBookingCountByDateAndShift($today, 2);
        $afternoonMax = ceil($totalTechnicians * 0.5); // 50% KTV cơ bản

        // Nếu sáng có KTV hoàn thành sớm, thêm vào slot chiều
        if ($currentHour >= 12 && $morningCompleted > 0) {
            $afternoonMax += $morningCompleted;
        }

        $afternoonAvailable = $afternoonMax - $afternoonBookings;
        $afternoonDisabled = ($currentHour >= 18);

        $slots[$today] = [
            1 => [ // Sáng
                'available' => max(0, $morningAvailable),
                'max' => $morningMax,
                'booked' => $morningBookings,
                'disabled' => $morningDisabled,
                'completed' => $morningCompleted
            ],
            2 => [ // Chiều
                'available' => max(0, $afternoonAvailable),
                'max' => $afternoonMax,
                'booked' => $afternoonBookings,
                'disabled' => $afternoonDisabled
            ]
        ];

        return $slots;
    }
    public function themDonDichVu($maKH, $booking_date, $booking_time, $problem_description, $customer_address, $device_types, $device_models, $device_problems, $service_type, $immediate_service = 0)
    {
        try {
            $this->db->beginTransaction();

            // 👇 KIỂM TRA SỐ LƯỢNG THIẾT BỊ (TỐI ĐA 3)
            $slTB = count($device_types);
            if ($slTB > 3) {
                throw new Exception("Mỗi đơn chỉ được đặt tối đa 3 thiết bị");
            }

            // Xử lý thời gian cho "Sửa chữa ngay"
            $ngayDat = $booking_date;

            if ($booking_time == '1') {
                $gioDat = '1'; // Sáng
            } else if ($booking_time == '2') {
                $gioDat = '2'; // Chiều
            } else {
                $gioDat = $booking_time;
            }

            if ($immediate_service) {
                $ngayDat = date('Y-m-d');
                $gioDat = '0';
            }

            // 👇 PHÂN CÔNG KTV TRỰC TIẾP
            $availableKTVs = $this->findAvailableKTV($ngayDat, $gioDat);

            if (empty($availableKTVs)) {
                throw new Exception("Hiện không có kỹ thuật viên khả dụng cho khung giờ này");
            }

            // 👇 CHỌN KTV ĐẦU TIÊN (đã được sắp xếp ưu tiên)
            $maKTV = $availableKTVs[0]['maND'];
            $tenKTV = $availableKTVs[0]['hoTen'];

            error_log("Phân công KTV: $tenKTV (ID: $maKTV) cho đơn");

            // 1. Thêm vào bảng DonDichVu - CÓ MAKTV
            $sql = "INSERT INTO DonDichVu (maKH, ngayDat, maKhungGio, ghiChu, diemhen, noiSuaChua, trangThai,  maKTV)
                VALUES (:user_id, :ngayDat, :gioDat, :ghiChu, :diemhen, :noiSuaChua, :trangThai, :maKTV)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $maKH,
                ':ngayDat' => $ngayDat,
                ':gioDat' => $gioDat,
                ':ghiChu' => $problem_description,
                ':diemhen' => $customer_address,
                ':noiSuaChua' => $service_type,
                ':trangThai' => '1',
                ':maKTV' => $maKTV
            ]);

            $maDon = $this->db->lastInsertId();

            // 2. Thêm các thiết bị vào bảng ChiTietDonDichVu
            $sqlDevice = "INSERT INTO ChiTietDonDichVu (maDon, loai_thietbi, phienban, mota_tinhtrang)
                      VALUES (:maDon, :type, :model, :problem)";
            $stmtDevice = $this->db->prepare($sqlDevice);

            foreach ($device_types as $i => $type) {
                $stmtDevice->execute([
                    ':maDon' => $maDon,
                    ':type' => $type,
                    ':model' => $device_models[$i] ?? '',
                    ':problem' => $device_problems[$i] ?? ''
                ]);
            }

            // 👇 THÊM VÀO LỊCH PHÂN CÔNG
            $this->themLichPhanCong($maKTV, $maDon, $ngayDat, $gioDat, $slTB);

            $this->db->commit();
            return $maDon;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // 👇 HÀM TÌM KTV RẢNH - CẢI TIẾN ĐỂ XỬ LÝ TRƯỜNG HỢP BẰNG NHAU
    public function findAvailableKTV($ngaydat, $giodat)
    {
        // Lấy tháng và năm từ ngày đặt
        $thang = date('m', strtotime($ngaydat));
        $nam = date('Y', strtotime($ngaydat));

        $sql = "
        SELECT 
            u.maND,
            u.hoTen, 
            u.sdt,
            COUNT(lpc.id) AS so_ngay_lam_viec,
            (SELECT COUNT(*) FROM DonDichVu dd WHERE dd.maKTV = u.maND AND dd.trangThai = '1') as tong_so_don,
            RAND() as random_value 
        FROM nguoidung u
        LEFT JOIN lichphancong lpc 
            ON u.maND = lpc.maKTV
            AND MONTH(lpc.ngayLamViec) = ?
            AND YEAR(lpc.ngayLamViec) = ?
        WHERE u.maVaiTro = 3
          AND NOT EXISTS (
              SELECT 1 
              FROM lichphancong lp2 
              WHERE lp2.maKTV = u.maND
                AND lp2.ngayLamViec = ?
                AND lp2.khungGio = ?
          )
        GROUP BY u.maND, u.hoTen, u.sdt
        ORDER BY 
            so_ngay_lam_viec ASC,        
            tong_so_don ASC,             
            random_value DESC             
        LIMIT 5
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$thang, $nam, $ngaydat, $giodat]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 👇 DEBUG: Xem kết quả phân công
        error_log("Kết quả phân công KTV: " . json_encode($result));

        return $result;
    }

    // 👇 HÀM THÊM VÀO LỊCH PHÂN CÔNG
    private function themLichPhanCong($maKTV, $maDon, $ngayDat, $gioDat, $soThietBi)
    {
        $sql = "INSERT INTO lichphancong (maKTV, maDon, ngayLamViec, khungGio, soThietBi, trangThai) 
            VALUES (?, ?, ?, ?, ?, '1')";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$maKTV, $maDon, $ngayDat, $gioDat, $soThietBi]);

        if ($result) {
            error_log("✅ Đã thêm lịch phân công: KTV $maKTV, Đơn $maDon, $soThietBi thiết bị");
        }

        return $result;
    }
}
?>