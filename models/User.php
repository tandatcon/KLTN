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
    
    
}