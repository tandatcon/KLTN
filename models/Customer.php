<?php
class Customer
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
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

    public function findByID($id)
    {
        $stmt = $this->db->prepare("
            SELECT maND, hoTen, sdt, email from nguoidung
            WHERE maND = ? 
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findKH($searchTerm)
{
    $stmt = $this->db->prepare("
        SELECT maND, hoTen, sdt, email FROM nguoidung
        WHERE (hoTen LIKE ? OR sdt LIKE ?) AND maVaiTro = 1
    ");
    
    $searchTerm = "%" . $searchTerm . "%";
    $stmt->execute([$searchTerm, $searchTerm]);
    
    // THAY ĐỔI: Dùng fetchAll() thay vì fetch()
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
}
?>