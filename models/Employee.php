<?php
class Employee
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


    public function getEmployeeById($employeeId) {
        try {
            $sql = "SELECT * FROM NhanVien WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$employeeId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("getEmployeeById Error: " . $e->getMessage());
            return null;
        }
    }

    // Tìm khách hàng theo số điện thoại
    public function findByPhone($phone)
    {
        $stmt = $this->db->prepare("
            SELECT id, name, phone, email from users
            WHERE phone = ? AND role_id = 1
        ");
        $stmt->execute([$phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function findKTV()
    {
        $stmt = $this->db->prepare("
        SELECT 
            u.id, 
            u.name, 
            u.phone, 
            u.email,
            g.maKTV,
            g.soDon
        FROM users u
        INNER JOIN hskythuatvien g ON u.id = g.maKTV
        WHERE u.role_id = 3
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Tat ca cac KTV
    public function findALLNV()
    {
        $stmt = $this->db->prepare("
        SELECT 
            u.id, 
            u.name, 
            u.phone,
            role_name            
            
        FROM users u join roles i
        on u.id=i.role_id        
        WHERE u.role_id = 3 and u.role_id=2
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    

    // Lay KTV trong viec
    //Bo
    public function findAvailableKTV($ngaydat, $giodat)
{
    // Lấy tháng và năm từ ngày đặt
    $thang = date('m', strtotime($ngaydat));
    $nam   = date('Y', strtotime($ngaydat));

    $sql = "
        SELECT 
            u.maND,
            u.hoTen, 
            u.sdt,
            COUNT(lpc.ngaylamviec) AS so_ngay_lam_viec
        FROM nguoidung u
        LEFT JOIN lichphancong lpc 
            ON u.maND = lpc.maKTV
            AND MONTH(lpc.ngaylamviec) = ?
            AND YEAR(lpc.ngaylamviec) = ?
        WHERE u.maVaiTro = 3
          AND NOT EXISTS (
              SELECT 1 
              FROM lichphancong lp2 
              WHERE lp2.maKTV = u.maND
                AND lp2.ngaylamviec = ?
                AND (lp2.khungGio = ? )
          )
        GROUP BY u.maND, u.hoTen, u.sdt
        ORDER BY so_ngay_lam_viec ASC
    ";
            
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$thang, $nam, $ngaydat, $giodat]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}





    public function findKH($searchTerm)
    {
        $stmt = $this->db->prepare("
        SELECT id, name, phone, email FROM users
        WHERE (name LIKE ? OR phone LIKE ?) AND role_id = 1
    ");

        $searchTerm = "%" . $searchTerm . "%";
        $stmt->execute([$searchTerm, $searchTerm]);

        // 
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy lịch sử dịch vụ của khách hàng
    public function getServiceHistory($customerId)
    {
        $stmt = $this->db->prepare("
        SELECT o.maDon, o.ngayDat, o.trangThai, 
        COUNT(od.maCTDon) as so_luong_thiet_bi
 FROM dondichvu o 
 LEFT JOIN chitietdondichvu od ON o.maDon = od.maDon 
 WHERE o.user_id =  ?
 GROUP BY o.maDon 
 ORDER BY o.ngayDat DESC 
 LIMIT 5
        ");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tạo khách hàng mới
    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, phone, email, address, password, role, status) 
            VALUES (?, ?, ?, ?, ?, 1, 'active')
        ");

        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        return $stmt->execute([
            $data['name'],
            $data['phone'],
            $data['email'] ?? null,
            $data['address'],
            $password
        ]);
    }

    // Cập nhật thông tin khách hàng
    public function update($customerId, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET name = ?, email = ?, address = ? 
            WHERE id = ? AND role = 1
        ");

        return $stmt->execute([
            $data['name'],
            $data['email'] ?? null,
            $data['address'],
            $customerId
        ]);
    }



    public function getEmployeesForSchedule($filter = 'all') {
        try {
            $sql = "SELECT id, name, phone, role, chuyenMon, work_schedule, status 
                    FROM NhanVien 
                    WHERE status = 'active'";
            
            // Thêm điều kiện lọc
            switch ($filter) {
                case 'employee':
                    $sql .= " AND role IN (1,2)"; // NV kinh doanh, CSKH
                    break;
                case 'technician':
                    $sql .= " AND role = 3"; // KTV
                    break;
                case 'no_schedule':
                    $sql .= " AND (work_schedule IS NULL OR work_schedule = 0)";
                    break;
                case 'schedule_1':
                    $sql .= " AND work_schedule = 1";
                    break;
                case 'schedule_2':
                    $sql .= " AND work_schedule = 2";
                    break;
                // 'all' không cần thêm điều kiện
            }
            
            $sql .= " ORDER BY name ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("getEmployeesForSchedule Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getScheduleStatistics() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_employees,
                        SUM(CASE WHEN work_schedule = 1 THEN 1 ELSE 0 END) as schedule_1_count,
                        SUM(CASE WHEN work_schedule = 2 THEN 1 ELSE 0 END) as schedule_2_count,
                        SUM(CASE WHEN work_schedule IS NULL OR work_schedule = 0 THEN 1 ELSE 0 END) as no_schedule_count
                    FROM NhanVien 
                    WHERE status = 'active'";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("getScheduleStatistics Error: " . $e->getMessage());
            return ['total_employees' => 0, 'schedule_1_count' => 0, 'schedule_2_count' => 0, 'no_schedule_count' => 0];
        }
    }
    
    public function assignWorkSchedule($employee_id, $schedule_type, $assigned_by) {
        try {
            $sql = "UPDATE NhanVien 
                    SET work_schedule = ?, 
                        schedule_assigned_at = NOW(),
                        assigned_by = ?
                    WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$schedule_type, $assigned_by, $employee_id]);
            
        } catch (Exception $e) {
            error_log("assignWorkSchedule Error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getRoleText($role) {
        $roles = [
            1 => 'Nhân viên CSKH',
            2 => 'Nhân viên kinh doanh', 
            3 => 'Kỹ thuật viên',
            4 => 'Quản lý'
        ];
        return $roles[$role] ?? 'Nhân viên';
    }






    
}
?>