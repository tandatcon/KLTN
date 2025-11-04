<?php
    class thietbi{
        private $db;

        public function __construct($database) {
            $this->db = $database;
        }
        public function getAllDevices() {
            try {
                $sql = "SELECT * FROM thietbi ORDER BY maThietBi ASC";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
                
            } catch (Exception $e) {
                error_log("Device Model Error: " . $e->getMessage());
                return [];
            }
        }
        
        public function getDeviceById($device_id) {
            try {
                $sql = "SELECT * FROM thietbi WHERE maThietBi = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$device_id]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
                
            } catch (Exception $e) {
                error_log("Get Device By ID Error: " . $e->getMessage());
                return null;
            }
        }

        public function getAllBangGia() {
            try {
                $sql = "SELECT * FROM banggiasc ORDER BY maThietBi ASC";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
                
            } catch (Exception $e) {
                error_log("Device Model Error: " . $e->getMessage());
                return [];
            }
        }
        public function getBangGiaByTB($tb) {
            try {
                $sql = "SELECT * FROM banggiasc where maThietBi = ? ORDER BY maThietBi ASC";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$tb]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
                
            } catch (Exception $e) {
                error_log("Device Model Error: " . $e->getMessage());
                return [];
            }
        }
    
    // Lấy bảng giá theo thiết bị với tìm kiếm
    public function getBangGiaByDevice($deviceId, $searchKeyword = '') {
        $sql = "SELECT bg.*, tb.tenThietBi 
                FROM banggiasc bg 
                JOIN thietbi tb ON bg.maThietBi = tb.maThietBi 
                WHERE bg.maThietBi = ? ";
        
        $params = [$deviceId];
        
        if (!empty($searchKeyword)) {
            $sql .= " AND (bg.chitietLoi LIKE ? OR bg.moTa LIKE ?)";
            $searchParam = "%{$searchKeyword}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $sql .= " ORDER BY bg.chitietLoi ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lấy tất cả bảng giá với tìm kiếm
    
    }
?>