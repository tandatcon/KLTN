<?php
class KTV
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function finKTVdByID($id)
    {
        $stmt = $this->db->prepare("
            SELECT maND, hoTen, sdt, email from nguoidung
            WHERE maND = ? and maVaiTro='3'
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>