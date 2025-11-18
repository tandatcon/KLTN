<?php
class NguoiDungService {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Đăng nhập với số điện thoại và mật khẩu
     */
    public function dangNhap($sdt, $matKhau) {
        try {
            $sql = "SELECT maND, sdt, hoTen, password, login_method, maVaiTro 
                    FROM nguoidung 
                    WHERE sdt = ? AND login_method = 'normal' AND trangThaiHD = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$sdt]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Kiểm tra mật khẩu
                if (password_verify($matKhau, $user['password'])) {
                    return [
                        'success' => true,
                        'user' => $user
                    ];
                } else {
                    return [
                        'success' => false,
                        'error' => 'Mật khẩu không đúng'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'error' => 'Số điện thoại không tồn tại hoặc tài khoản bị khóa'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Lỗi đăng nhập: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Lỗi hệ thống, vui lòng thử lại sau'
            ];
        }
    }

    /**
     * Đăng ký tài khoản mới
     */
    public function dangKy($hoTen, $sdt, $email, $matKhau,$diachi) {
        try {
            // Kiểm tra số điện thoại đã tồn tại chưa
            if ($this->kiemTraSoDienThoaiTonTai($sdt)) {
                return [
                    'success' => false,
                    'error' => 'Số điện thoại đã được đăng ký'
                ];
            }

            // Kiểm tra email đã tồn tại chưa
            if (!empty($email) && $this->kiemTraEmailTonTai($email)) {
                return [
                    'success' => false,
                    'error' => 'Email đã được đăng ký'
                ];
            }

            $sql = "INSERT INTO nguoidung (hoTen, sdt, email, password, login_method, maVaiTro,  trangThaiHD, ngayTao,diaChi) 
                    VALUES (?, ?, ?, ?, 'normal', 1, 1, NOW(),?)";
            
            $stmt = $this->db->prepare($sql);
            $hashedPassword = password_hash($matKhau, PASSWORD_DEFAULT);
            
            $success = $stmt->execute([$hoTen, $sdt, $email, $hashedPassword,$diachi]);
            
            if ($success) {
                $maND = $this->db->lastInsertId();
                return [
                    'success' => true,
                    'maND' => $maND
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Không thể tạo tài khoản'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Lỗi đăng ký: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Lỗi hệ thống, vui lòng thử lại sau'
            ];
        }
    }

    /**
     * Kiểm tra số điện thoại đã tồn tại chưa
     */
    public function kiemTraSoDienThoaiTonTai($sdt) {
        try {
            $sql = "SELECT maND FROM nguoidung WHERE sdt = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$sdt]);
            return $stmt->fetch() !== false;
            
        } catch (Exception $e) {
            error_log("Lỗi kiểm tra số điện thoại: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra email đã tồn tại chưa
     */
    public function kiemTraEmailTonTai($email) {
        try {
            $sql = "SELECT maND FROM nguoidung WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetch() !== false;
            
        } catch (Exception $e) {
            error_log("Lỗi kiểm tra email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thông tin người dùng theo mã
     */
    public function getThongTinNguoiDung($maND) {
        try {
            $sql = "SELECT maND, hoTen, sdt, email, maVaiTro, login_method, trangThaiHD, ngayTao 
                    FROM nguoidung 
                    WHERE maND = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maND]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Lỗi lấy thông tin người dùng: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function capNhatThongTin($maND, $hoTen, $email, $sdt) {
        try {
            $sql = "UPDATE nguoidung 
                    SET hoTen = ?, email = ?, sdt = ? 
                    WHERE maND = ?";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([$hoTen, $email, $sdt, $maND]);
            
            return $success;
            
        } catch (Exception $e) {
            error_log("Lỗi cập nhật thông tin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Đổi mật khẩu
     */
    public function doiMatKhau($maND, $matKhauCu, $matKhauMoi) {
        try {
            // Kiểm tra mật khẩu cũ
            $sqlCheck = "SELECT password FROM nguoidung WHERE maND = ?";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute([$maND]);
            $user = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if (!$user || !password_verify($matKhauCu, $user['password'])) {
                return [
                    'success' => false,
                    'error' => 'Mật khẩu cũ không đúng'
                ];
            }
            
            // Cập nhật mật khẩu mới
            $sqlUpdate = "UPDATE nguoidung SET password = ? WHERE maND = ?";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $hashedPassword = password_hash($matKhauMoi, PASSWORD_DEFAULT);
            $success = $stmtUpdate->execute([$hashedPassword, $maND]);
            
            if ($success) {
                return ['success' => true];
            } else {
                return [
                    'success' => false,
                    'error' => 'Không thể đổi mật khẩu'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Lỗi đổi mật khẩu: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Lỗi hệ thống'
            ];
        }
    }

    /**
     * Đặt lại mật khẩu (quên mật khẩu)
     */
    public function datLaiMatKhau($sdt, $matKhauMoi) {
        try {
            $sql = "UPDATE nguoidung SET password = ? WHERE sdt = ?";
            $stmt = $this->db->prepare($sql);
            $hashedPassword = password_hash($matKhauMoi, PASSWORD_DEFAULT);
            $success = $stmt->execute([$hashedPassword, $sdt]);
            
            return $success;
            
        } catch (Exception $e) {
            error_log("Lỗi đặt lại mật khẩu: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tạo hoặc cập nhật người dùng đăng nhập Google
     */
    public function xuLyDangNhapGoogle($googleData) {
        try {
            $email = $googleData['email'] ?? '';
            $hoTen = $googleData['name'] ?? '';
            $googleId = $googleData['id'] ?? '';
            
            // Kiểm tra xem email đã tồn tại chưa
            $sqlCheck = "SELECT maND FROM nguoidung WHERE email = ? OR google_id = ?";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute([$email, $googleId]);
            $existingUser = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if ($existingUser) {
                // Cập nhật thông tin Google
                $sqlUpdate = "UPDATE nguoidung 
                             SET hoTen = ?, google_id = ?, login_method = 'google' 
                             WHERE maND = ?";
                $stmtUpdate = $this->db->prepare($sqlUpdate);
                $stmtUpdate->execute([$hoTen, $googleId, $existingUser['maND']]);
                
                return $existingUser['maND'];
            } else {
                // Tạo user mới
                $sqlInsert = "INSERT INTO nguoidung (hoTen, email, google_id, login_method, maVaiTro, trangThai, ngayTao) 
                             VALUES (?, ?, ?, 'google', 1, 1, NOW())";
                $stmtInsert = $this->db->prepare($sqlInsert);
                $stmtInsert->execute([$hoTen, $email, $googleId]);
                
                return $this->db->lastInsertId();
            }
            
        } catch (Exception $e) {
            error_log("Lỗi xử lý đăng nhập Google: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra quyền truy cập
     */
    public function kiemTraQuyen($maND, $vaiTroYeuCau) {
        try {
            $sql = "SELECT maVaiTro FROM nguoidung WHERE maND = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maND]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) return false;
            
            $maVaiTro = $user['maVaiTro'];
            
            // Logic kiểm tra quyền (có thể tùy chỉnh theo hệ thống phân quyền)
            switch ($vaiTroYeuCau) {
                case 'admin':
                    return $maVaiTro == 4;
                case 'quanly':
                    return $maVaiTro == 4 || $maVaiTro == 3;
                case 'ktv':
                    return $maVaiTro == 3;
                case 'employee':
                    return $maVaiTro == 2;
                case 'customer':
                    return $maVaiTro == 1;
                default:
                    return false;
            }
            
        } catch (Exception $e) {
            error_log("Lỗi kiểm tra quyền: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách KTV (cho dropdown, select...)
     */
    public function getDanhSachKTV() {
        try {
            $sql = "SELECT maND, hoTen, sdt, email 
                    FROM nguoidung 
                    WHERE maVaiTro = 3 AND trangThai = 1 
                    ORDER BY hoTen";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Lỗi lấy danh sách KTV: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Đăng xuất
     */
    public function dangXuat() {
        try {
            // Xóa tất cả session
            session_unset();
            session_destroy();
            
            return true;
            
        } catch (Exception $e) {
            error_log("Lỗi đăng xuất: " . $e->getMessage());
            return false;
        }
    }
}
?>