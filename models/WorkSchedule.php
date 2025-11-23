<?php
class WorkSchedule
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Lấy lịch làm việc của nhân viên
    public function getLichbyNV($idNV)
    {
        try {
            $sql = "SELECT lc.*
                    FROM lichlamviec lc 
                    JOIN hosokythuatvien u ON lc.maLLV = u.maLLV 
                    WHERE maKTV = ?
                    ";
            // echo "SELECT lc.*, u.name as nguoi_tao_ten
            // FROM lichcung lc 
            // JOIN users u ON lc.maLichCung = u.maLichCung 
            // WHERE id = '$idNV'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$idNV]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("getLichbyNV Error: " . $e->getMessage());
            return [];
        }
    }

    // Kiểm tra xem ngày đó có phải ngày làm việc không
    public function layNgayLV($employeeId, $date)
{
    try {
        $dayOfWeek = date('w', strtotime($date)); // 0 = Chủ nhật, 1 = Thứ 2, ...

        $sql = "SELECT lc.*
        FROM lichlamviec lc 
        JOIN hosokythuatvien u ON lc.maLLV = u.maLLV 
        WHERE maKTV = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$employeeId]);
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($schedules as $schedule) {
            $ngayLamViec = $schedule['ngayLamViec'] ?? '';
            
            // Xử lý chuỗi "2,3,4,5,6" thành mảng
            $workingDays = array_map('trim', explode(',', $ngayLamViec));
            $workingDays = array_filter($workingDays); // Loại bỏ phần tử rỗng
            
            if (in_array($dayOfWeek, $workingDays)) {
                return true;
            }
        }

        return false;

    } catch (Exception $e) {
        error_log("isWorkingDay Error: " . $e->getMessage());
        return false;
    }
}

    // Gửi yêu cầu nghỉ phép
    public function xinNghi($employeeId, $startDate, $endDate, $totalDays, $reason)
{
    try {
        $sql = "INSERT INTO lichxinnghi (
                    maNV, 
                    ngayBatDau, 
                    ngayKetThuc, 
                    songayXN, 
                    lyDo, 
                    trangThai, 
                    ngayTao
                ) VALUES (?, ?, ?, ?, ?, '0', NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $employeeId,
            $startDate,
            $endDate,
            $totalDays,
            $reason
        ]);

    } catch (Exception $e) {
        error_log("requestLeave Error: " . $e->getMessage());
        return false;
    }
}

    // Lấy danh sách yêu cầu nghỉ phép của nhân viên
    public function getLichNP($maNV)
    {
        try {
            $sql = "SELECT * FROM lichxinnghi 
                    WHERE maNV = ? AND trangThai IN (0,1,2)
                    ORDER BY ngayTao DESC  ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maNV]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("getEmployeeLeaveRequests Error: " . $e->getMessage());
            return [];
        }
    }

    public function getLNPbyNV($employeeId)
    {
        try {
            $sql = "SELECT * FROM lichxinnghi 
                    WHERE maNV = ? 
                    ORDER BY ngayTao DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$employeeId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("getLeaveRequestsByEmployee Error: " . $e->getMessage());
            return [];
        }
    }

    // Huy xin nghi
    public function huyDonNghi($leaveId)
    {
        try {
            $sql = "UPDATE lichxinnghi 
                SET trangThai = '3'

                WHERE maLichXN = ? AND trangThai = '0'";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$leaveId]);

            // Kiểm tra xem có bản ghi nào được cập nhật không
            return $stmt->rowCount() > 0;

        } catch (Exception $e) {
            error_log("huyDonNghi Error: " . $e->getMessage());
            return false;
        }
    }
}
?>