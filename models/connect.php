<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'techcarepro';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Test connection
            $stmt = $this->conn->query("SELECT 1");
            // echo "Kết nối database thành công!";
            
        } catch(PDOException $exception) {
            echo "Lỗi kết nối database: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>