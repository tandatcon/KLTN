<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Quản lý khách hàng - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../models/Customer.php';

$customerModel = new Customer($db);

// Kiểm tra role - chỉ cho phép nhân viên (role 2,3,4) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 1) {
    header('Location: ' . url('home'));
    exit();
}

// Xử lý tìm kiếm khách hàng
$searchResults = [];
$searchPerformed = false;
$searchKeyword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_customer'])) {
    $searchKeyword = trim($_POST['search_keyword']);
    $searchPerformed = true;
    
    if (!empty($searchKeyword)) {
        $searchResults = $customerModel->findKH($searchKeyword);
    }
}

// Hàm helper để tránh lỗi deprecated
function safe_htmlspecialchars($value) {
    return $value !== null ? htmlspecialchars($value) : '';
}
?>

<section class="customer-management-section">
    <div class="container">
        <div class="customer-header">
            <h1 class="section-title">
                <i class="fas fa-users"></i> Quản lý khách hàng
            </h1>
            <p class="section-subtitle">Tìm kiếm và quản lý thông tin khách hàng</p>
        </div>

        <!-- PHẦN TÌM KIẾM KHÁCH HÀNG -->
        <div class="customer-search-section">
            <div class="search-card">
                <h3><i class="fas fa-search"></i> Tìm kiếm khách hàng</h3>
                
                <form method="POST" class="search-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="search_keyword">Tìm theo tên hoặc số điện thoại</label>
                            <input type="text" id="search_keyword" name="search_keyword" 
                                   value="<?php echo safe_htmlspecialchars($searchKeyword); ?>"
                                   placeholder="Nhập tên hoặc số điện thoại...">
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" name="search_customer" class="btn-search">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>

                <!-- HIỂN THỊ KẾT QUẢ TÌM KIẾM -->
                <?php if ($searchPerformed): ?>
                    <div class="search-results">
                        <h4>Kết quả tìm kiếm (<?php echo count($searchResults); ?> kết quả)</h4>
                        
                        <?php if (!empty($searchResults)): ?>
                            <div class="customer-table-container">
                                <table class="customer-table">
                                    <thead>
                                        <tr>
                                            <th>Mã KH</th>
                                            <th>Tên khách hàng</th>
                                            <th>Số điện thoại</th>
                                            <th>Email</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($searchResults as $customer): ?>
                                        <tr>
                                            <td class="customer-id"><?php echo $customer['maND']; ?></td>
                                            <td class="customer-name"><?php echo safe_htmlspecialchars($customer['hoTen']); ?></td>
                                            <td class="customer-phone"><?php echo safe_htmlspecialchars($customer['sdt']); ?></td>
                                            <td class="customer-email"><?php echo !empty($customer['email']) ? safe_htmlspecialchars($customer['email']) : '<span class="text-muted">Chưa có</span>'; ?></td>
                                            <td class="customer-actions">
                                                <a href="<?php echo url('employee/chitietkhachhang?id=' . $customer['maND']); ?>" class="btn-action btn-view">
                                                    <i class="fas fa-eye"></i> Xem chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="no-results">
                                <i class="fas fa-search"></i>
                                <p>Không tìm thấy khách hàng nào phù hợp</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- CÁC Ô CHỨC NĂNG -->
        <div class="function-grid-section">
            <h3><i class="fas fa-th-large"></i> Chức năng quản lý</h3>
            
            <div class="function-grid">
                <!-- Thêm khách hàng -->
                <a href="<?php echo url('employee/themkhachhang'); ?>" class="function-card">
                    <div class="function-icon add-customer">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="function-info">
                        <h4>Thêm khách hàng</h4>
                        <p>Thêm khách hàng mới vào hệ thống</p>
                    </div>
                </a>

                <!-- Lập dịch vụ -->
                <a href="<?php echo url('employee/themdichvu'); ?>" class="function-card">
                    <div class="function-icon create-service">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="function-info">
                        <h4>Lập dịch vụ</h4>
                        <p>Đăng ký dịch vụ sửa chữa mới</p>
                    </div>
                </a>

                <!-- Quản lý đơn hàng -->
                <a href="<?php echo url('employee/donhang'); ?>" class="function-card">
                    <div class="function-icon orders">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="function-info">
                        <h4>Quản lý đơn hàng</h4>
                        <p>Theo dõi và quản lý đơn dịch vụ</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../footer.php'; ?>

<style>
.customer-management-section {
    padding: 40px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.customer-header {
    text-align: center;
    margin-bottom: 40px;
}

.section-title {
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 10px;
    font-weight: 700;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* PHẦN TÌM KIẾM */
.customer-search-section {
    margin-bottom: 40px;
}

.search-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
}

.search-card h3 {
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.3rem;
}

.search-form .form-row {
    display: flex;
    gap: 15px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.search-form .form-group {
    flex: 1;
    min-width: 300px;
    margin-bottom: 0;
}

.search-form .form-group:last-child {
    flex: 0 0 auto;
    min-width: 140px;
}

.search-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
}

.search-form input[type="text"] {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s;
    box-sizing: border-box;
}

.search-form input[type="text"]:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    outline: none;
}

.btn-search {
    background: #3498db;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    height: 44px;
    white-space: nowrap;
    width: 100%;
    justify-content: center;
}

.btn-search:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

/* TABLE KẾT QUẢ TÌM KIẾM */
.search-results {
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid #e9ecef;
}

.search-results h4 {
    color: #2c3e50;
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.customer-table-container {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.customer-table {
    width: 100%;
    border-collapse: collapse;
}

.customer-table th {
    background: #3498db;
    color: white;
    padding: 15px 12px;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
}

.customer-table td {
    padding: 12px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.customer-table tr:hover {
    background: #f8f9fa;
}

.customer-id {
    font-weight: 600;
    color: #2c3e50;
    font-family: 'Courier New', monospace;
}

.customer-name {
    font-weight: 600;
    color: #2c3e50;
}

.customer-phone {
    color: #495057;
    font-family: 'Courier New', monospace;
}

.customer-email {
    color: #6c757d;
}

.text-muted {
    color: #6c757d;
    font-style: italic;
}

/* NÚT THAO TÁC */
.customer-actions {
    white-space: nowrap;
}

.btn-action {
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: #3498db;
    color: white;
}

.btn-view:hover {
    background: #2980b9;
    transform: translateY(-1px);
    text-decoration: none;
    color: white;
}

.no-results {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.no-results i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

/* CÁC Ô CHỨC NĂNG */
.function-grid-section {
    margin-bottom: 40px;
}

.function-grid-section h3 {
    color: #2c3e50;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.3rem;
}

.function-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
}

.function-card {
    background: white;
    border-radius: 15px;
    padding: 30px 25px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s;
    border: 2px solid transparent;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 20px;
}

.function-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
    border-color: #3498db;
}

.function-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
}

.add-customer {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
}

.create-service {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.orders {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
}

.function-info h4 {
    color: #2c3e50;
    margin-bottom: 8px;
    font-size: 1.1rem;
    font-weight: 600;
}

.function-info p {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
    line-height: 1.4;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .customer-management-section {
        padding: 20px 0;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .search-card {
        padding: 20px;
        margin: 0 10px 20px 10px;
    }
    
    .search-form .form-row {
        flex-direction: column;
    }
    
    .search-form .form-group {
        min-width: 100%;
    }
    
    .function-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .function-card {
        padding: 20px;
    }
    
    .function-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    /* Responsive table */
    .customer-table-container {
        overflow-x: auto;
    }
    
    .customer-table {
        min-width: 600px;
    }
}

@media (max-width: 480px) {
    .section-title {
        font-size: 1.6rem;
    }
    
    .search-card {
        padding: 15px;
    }
    
    .function-card {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
}
</style>