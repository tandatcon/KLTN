<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Thêm Nhân Viên - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../models/Employee.php';

$employeeModel = new Employee($db);

// Kiểm tra role - chỉ cho phép quản lý (role 4) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 4) {
    header('Location: ' . url('home'));
    exit();
}

// Xử lý form thêm nhân viên
$tns_success_message = '';
$tns_error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tns_name = $_POST['tns_name'] ?? '';
    $tns_birthdate = $_POST['tns_birthdate'] ?? '';
    $tns_gender = $_POST['tns_gender'] ?? '';
    $tns_email = $_POST['tns_email'] ?? '';
    $tns_phone = $_POST['tns_phone'] ?? '';
    $tns_address = $_POST['tns_address'] ?? '';
    $tns_specialty = $_POST['tns_specialty'] ?? '';
    $tns_role = $_POST['tns_role'] ?? 3; // Mặc định là Kỹ thuật viên

    // Validate dữ liệu
    if (empty($tns_name) || empty($tns_birthdate) || empty($tns_gender) || empty($tns_email) || empty($tns_phone)) {
        $tns_error_message = 'Vui lòng điền đầy đủ thông tin bắt buộc';
    } elseif (!filter_var($tns_email, FILTER_VALIDATE_EMAIL)) {
        $tns_error_message = 'Email không hợp lệ';
    } else {
        // Mật khẩu mặc định
        $tns_password = password_hash('1111', PASSWORD_DEFAULT);
        
        // Thêm nhân viên vào database
        $result = $employeeModel->addEmployee([
            'name' => $tns_name,
            'birthdate' => $tns_birthdate,
            'gender' => $tns_gender,
            'email' => $tns_email,
            'phone' => $tns_phone,
            'password' => $tns_password,
            'address' => $tns_address,
            'specialty' => $tns_specialty,
            'role' => $tns_role
        ]);

        if ($result) {
            $tns_success_message = 'Thêm nhân viên thành công! Mật khẩu mặc định: 1111';
            // Reset form
            $_POST = [];
        } else {
            $tns_error_message = 'Có lỗi xảy ra khi thêm nhân viên. Có thể email hoặc số điện thoại đã tồn tại.';
        }
    }
}
?>

<section class="tns_add_employee_section">
    <div class="tns_container">
        <!-- HEADER -->
        <div class="tns_page_header">
            <div class="tns_header_content">
                <h1><i class="fas fa-user-plus"></i> Thêm Nhân Viên</h1>
                <p>Thêm nhân viên mới vào hệ thống TechCare</p>
            </div>
            <div class="tns_header_actions">
                <a href="<?php echo url('quanly/nhansu'); ?>" class="tns_btn_back">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <!-- THÔNG BÁO -->
        <?php if ($tns_success_message): ?>
            <div class="tns_alert tns_alert_success">
                <i class="fas fa-check-circle"></i> <?php echo $tns_success_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($tns_error_message): ?>
            <div class="tns_alert tns_alert_error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $tns_error_message; ?>
            </div>
        <?php endif; ?>

        <!-- FORM THÊM NHÂN VIÊN -->
        <div class="tns_form_container">
            <form method="POST" class="tns_employee_form">
                <div class="tns_form_grid">
                    <!-- Thông tin cơ bản -->
                    <div class="tns_form_section">
                        <h3><i class="fas fa-user"></i> Thông tin cá nhân</h3>
                        
                        <div class="tns_form_group">
                            <label for="tns_name" class="tns_label">Họ và tên <span class="tns_required">*</span></label>
                            <input type="text" id="tns_name" name="tns_name" class="tns_input" 
                                   value="<?php echo htmlspecialchars($_POST['tns_name'] ?? ''); ?>" required>
                        </div>

                        <div class="tns_form_row">
                            <div class="tns_form_group">
                                <label for="tns_birthdate" class="tns_label">Ngày sinh <span class="tns_required">*</span></label>
                                <input type="date" id="tns_birthdate" name="tns_birthdate" class="tns_input" 
                                       value="<?php echo htmlspecialchars($_POST['tns_birthdate'] ?? ''); ?>" required>
                            </div>

                            <div class="tns_form_group">
                                <label for="tns_gender" class="tns_label">Giới tính <span class="tns_required">*</span></label>
                                <select id="tns_gender" name="tns_gender" class="tns_select" required>
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="male" <?php echo ($_POST['tns_gender'] ?? '') == 'male' ? 'selected' : ''; ?>>Nam</option>
                                    <option value="female" <?php echo ($_POST['tns_gender'] ?? '') == 'female' ? 'selected' : ''; ?>>Nữ</option>
                                    <option value="other" <?php echo ($_POST['tns_gender'] ?? '') == 'other' ? 'selected' : ''; ?>>Khác</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin liên hệ -->
                    <div class="tns_form_section">
                        <h3><i class="fas fa-address-book"></i> Thông tin liên hệ</h3>
                        
                        <div class="tns_form_group">
                            <label for="tns_email" class="tns_label">Email <span class="tns_required">*</span></label>
                            <input type="email" id="tns_email" name="tns_email" class="tns_input" 
                                   value="<?php echo htmlspecialchars($_POST['tns_email'] ?? ''); ?>" required>
                        </div>

                        <div class="tns_form_group">
                            <label for="tns_phone" class="tns_label">Số điện thoại <span class="tns_required">*</span></label>
                            <input type="tel" id="tns_phone" name="tns_phone" class="tns_input" 
                                   value="<?php echo htmlspecialchars($_POST['tns_phone'] ?? ''); ?>" required>
                        </div>

                        <div class="tns_form_group">
                            <label for="tns_address" class="tns_label">Địa chỉ</label>
                            <textarea id="tns_address" name="tns_address" class="tns_textarea" rows="3"><?php echo htmlspecialchars($_POST['tns_address'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <!-- Thông tin công việc -->
                    <div class="tns_form_section">
                        <h3><i class="fas fa-briefcase"></i> Thông tin công việc</h3>
                        
                        <div class="tns_form_group">
                            <label for="tns_specialty" class="tns_label">Chuyên môn</label>
                            <input type="text" id="tns_specialty" name="tns_specialty" class="tns_input" 
                                   value="<?php echo htmlspecialchars($_POST['tns_specialty'] ?? ''); ?>" 
                                   placeholder="Ví dụ: Điện tử, Điện lạnh, Cơ khí...">
                        </div>

                        <div class="tns_form_group">
                            <label for="tns_role" class="tns_label">Chức vụ <span class="tns_required">*</span></label>
                            <select id="tns_role" name="tns_role" class="tns_select" required>
                                <option value="">-- Chọn chức vụ --</option>
                                <option value="3" <?php echo ($_POST['tns_role'] ?? '3') == '3' ? 'selected' : ''; ?>>Kỹ thuật viên</option>
                                <option value="2" <?php echo ($_POST['tns_role'] ?? '') == '2' ? 'selected' : ''; ?>>Nhân viên kinh doanh</option>
                                <option value="1" <?php echo ($_POST['tns_role'] ?? '') == '1' ? 'selected' : ''; ?>>Nhân viên chăm sóc khách hàng</option>
                                <option value="4" <?php echo ($_POST['tns_role'] ?? '') == '4' ? 'selected' : ''; ?>>Quản lý</option>
                            </select>
                        </div>

                        <div class="tns_password_info">
                            <i class="fas fa-info-circle"></i>
                            <span>Mật khẩu mặc định: <strong>1111</strong> (Nhân viên nên đổi mật khẩu sau khi đăng nhập lần đầu)</span>
                        </div>
                    </div>
                </div>

                <div class="tns_form_actions">
                    <button type="reset" class="tns_btn_reset">
                        <i class="fas fa-redo"></i> Nhập lại
                    </button>
                    <button type="submit" class="tns_btn_submit">
                        <i class="fas fa-save"></i> Thêm nhân viên
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../footer.php'; ?>

<style>
.tns_add_employee_section {
    padding: 30px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.tns_container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

/* HEADER */
.tns_page_header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.tns_header_content h1 {
    color: #2c3e50;
    margin-bottom: 5px;
    font-size: 2rem;
    font-weight: 700;
}

.tns_header_content p {
    color: #6c757d;
    margin: 0;
    font-size: 1.1rem;
}

.tns_btn_back {
    background: #6c757d;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.tns_btn_back:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* ALERTS */
.tns_alert {
    padding: 16px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
}

.tns_alert_success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    border-left: 4px solid #28a745;
}

.tns_alert_error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-left: 4px solid #dc3545;
}

/* FORM CONTAINER */
.tns_form_container {
    background: white;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
}

.tns_form_grid {
    display: grid;
    gap: 30px;
    margin-bottom: 30px;
}

/* FORM SECTIONS */
.tns_form_section {
    padding: 30px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #f8fafc;
    transition: all 0.3s ease;
}

.tns_form_section:hover {
    border-color: #3498db;
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.1);
}

.tns_form_section h3 {
    color: #2c3e50;
    margin-bottom: 25px;
    font-size: 1.3rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

/* FORM GROUPS */
.tns_form_group {
    margin-bottom: 25px;
}

.tns_form_row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.tns_label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2d3748;
    font-size: 0.95rem;
}

.tns_required {
    color: #e53e3e;
}

.tns_input, .tns_select, .tns_textarea {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.tns_input:focus, .tns_select:focus, .tns_textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    background: #fafbfc;
}

.tns_textarea {
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
}

/* PASSWORD INFO */
.tns_password_info {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    padding: 16px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
    color: #2d3748;
    font-size: 0.9rem;
    border-left: 4px solid #3498db;
}

.tns_password_info i {
    color: #3498db;
    font-size: 1.1rem;
}

.tns_password_info strong {
    color: #e53e3e;
}

/* FORM ACTIONS */
.tns_form_actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    padding-top: 25px;
    border-top: 2px solid #e9ecef;
}

.tns_btn_reset, .tns_btn_submit {
    padding: 14px 28px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    min-width: 160px;
    justify-content: center;
}

.tns_btn_reset {
    background: #718096;
    color: white;
}

.tns_btn_reset:hover {
    background: #4a5568;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(113, 128, 150, 0.3);
}

.tns_btn_submit {
    background: linear-gradient(135deg, #27ae60 0%, #219653 100%);
    color: white;
}

.tns_btn_submit:hover {
    background: linear-gradient(135deg, #219653 0%, #1e8449 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .tns_page_header {
        flex-direction: column;
        text-align: center;
    }
    
    .tns_form_grid {
        grid-template-columns: 1fr;
    }
    
    .tns_form_row {
        grid-template-columns: 1fr;
    }
    
    .tns_form_actions {
        flex-direction: column;
    }
    
    .tns_btn_reset, .tns_btn_submit {
        width: 100%;
    }
    
    .tns_form_container {
        padding: 25px;
    }
    
    .tns_form_section {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .tns_container {
        padding: 0 15px;
    }
    
    .tns_form_container {
        padding: 20px;
    }
    
    .tns_header_content h1 {
        font-size: 1.7rem;
    }
}
</style>

<script>
// Auto-format phone number
document.getElementById('tns_phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 10) {
        e.target.value = value;
    }
});

// Form validation
document.querySelector('.tns_employee_form').addEventListener('submit', function(e) {
    const phone = document.getElementById('tns_phone').value;
    const birthdate = document.getElementById('tns_birthdate').value;
    const email = document.getElementById('tns_email').value;
    
    // Validate phone number
    if (phone.length < 10) {
        alert('Số điện thoại phải có ít nhất 10 số');
        e.preventDefault();
        return;
    }
    
    // Validate birthdate (must be at least 18 years old)
    if (birthdate) {
        const birthDate = new Date(birthdate);
        const today = new Date();
        const age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        if (age < 18) {
            alert('Nhân viên phải đủ 18 tuổi trở lên');
            e.preventDefault();
            return;
        }
    }
    
    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Email không hợp lệ');
        e.preventDefault();
        return;
    }
});

// Real-time validation feedback
const inputs = document.querySelectorAll('.tns_input, .tns_select, .tns_textarea');
inputs.forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value.trim() === '' && this.hasAttribute('required')) {
            this.style.borderColor = '#e53e3e';
        } else {
            this.style.borderColor = '#e2e8f0';
        }
    });
});
</script>