<?php
require_once __DIR__ . '/../models/devices.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/User.php';
class BookingController {
    private $deviceModel;
    private $db;
    public function __construct($db) {
        $this->db = $db;
        $this->deviceModel = new thietbi($db);
    }
    
    /**
     * Hiển thị trang đặt dịch vụ
     */
    public function showBookingPage() {
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
    public function processBooking() {
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
public function getBookedSchedules() {
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
}
?>