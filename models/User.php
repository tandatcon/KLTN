<?php
class User {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getUserByGoogleId($google_id) {
        $query = "SELECT * FROM nguoidung WHERE google_id = :google_id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':google_id', $google_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registerWithGoogle($fullname, $email, $phone, $google_id) {
        $stmt = $this->db->prepare("
            INSERT INTO nguoidung (hoTen, email, sdt, google_id, login_method, created_at,role_id) 
            VALUES (?, ?, ?, ?, 'google', NOW(),'1')
        ");
        return $stmt->execute([$fullname, $email, $phone, $google_id]);
    }

    public function loginWithGoogle($google_id) {
        $user = $this->getUserByGoogleId($google_id);
        return $user;
    }
    
    public function getUserByPhone($phone) {
        $query = "SELECT * FROM nguoidung WHERE sdt = :phone LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM nguoidung WHERE maND = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function themDonDichVu($maKH, $booking_date, $booking_time, $problem_description, $customer_address, $device_types, $device_models, $device_problems, $service_type, $immediate_service = 0) {
        try {
            $this->db->beginTransaction();
    
            // Xử lý thời gian cho "Sửa chữa ngay"
            $ngayDat = $booking_date;
            $gioDat = $booking_time; // GIỮ NGUYÊN giá trị sang/chieu/toi
            
            if ($immediate_service) {
                // Nếu là sửa chữa ngay, đặt ngày giờ là thời điểm hiện tại
                $ngayDat = date('Y-m-d');
                $gioDat = 'Khẩn cấp-Trong ngày'; // Nhưng vẫn giờ thực cho sửa chữa ngay
            }
    
            // 1. Thêm vào bảng DonDichVu - LƯU TRỰC TIẾP sang/chieu/toi
            $sql = "INSERT INTO DonDichVu (user_id, ngayDat, gioDat, ghiChu, diemhen, noiSuaChua, trangThai, suaChuaNgay)
                    VALUES (:user_id, :ngayDat, :gioDat, :ghiChu, :diemhen, :noiSuaChua, :trangThai, :suaChuaNgay)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id'  => $maKH,
                ':ngayDat'  => $ngayDat,
                ':gioDat'   => $gioDat, // Lưu trực tiếp 'sang', 'chieu', 'toi'
                ':ghiChu'   => $problem_description,
                ':diemhen'  => $customer_address,
                ':noiSuaChua' => $service_type,
                ':trangThai' => '1',
                ':suaChuaNgay' => $immediate_service
            ]);
            $maDon = $this->db->lastInsertId();
    
            // 2. Thêm các thiết bị vào bảng ChiTietDonDichVu (giữ nguyên)
            $sqlDevice = "INSERT INTO ChiTietDonDichVu (maDon, loai_thietbi, phienban, mota_tinhtrang)
                          VALUES (:maDon, :type, :model, :problem)";
            $stmtDevice = $this->db->prepare($sqlDevice);
    
            foreach ($device_types as $i => $type) {
                $stmtDevice->execute([
                    ':maDon'   => $maDon,
                    ':type'    => $type,
                    ':model'   => $device_models[$i] ?? '',
                    ':problem' => $device_problems[$i] ?? ''
                ]);
            }
    
            $this->db->commit();
            return $maDon;
    
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}