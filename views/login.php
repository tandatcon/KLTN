<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_start();
// login.php - Đặt ở thư mục gốc
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';

$pageTitle = "Đăng nhập - TechCare";

// Xử lý đăng nhập
$error = "";
if(isset($_POST['submit'])){
    echo 'hihi';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);
    
    if (empty($phone) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ thông tin.';
    } else {
        $stmt = $db->prepare("SELECT maND, sdt, hoTen, password, login_method,maVaiTro FROM nguoidung WHERE sdt = ?");
        $stmt->execute([$phone]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Kiểm tra mật khẩu
            if (password_verify($password, $user['password'])) {
                // Đăng nhập thành công - Set session đồng bộ với hệ thống
                $_SESSION['user_id'] = $user['maND'];
                $_SESSION['role'] = $user['maVaiTro'];
                $_SESSION['user_name'] = $user['hoTen'];
                $_SESSION['user_phone'] = $user['sdt'];
                $_SESSION['login_method'] = $user['login_method'] ?: 'normal';
                $_SESSION['login_time'] = time();
                
                // Set thông báo thành công
                $_SESSION['success_message'] = "🎉 Đăng nhập thành công! Chào mừng bạn trở lại.";
                
                // Chuyển hướng về trang chủ
                if($_SESSION['role'] == '2'){
                    header("Location: " . url('employee/dashboard'));
                }else if($_SESSION['role'] == '3'){
                    header("Location: " . url('KTV/dashboard'));
                }else if($_SESSION['role'] == '4'){
                    header("Location: " . url('quanly/dashboard'));
                }  else{
                    header("Location: " . url('home'));
                }
                
                exit();
            } else {
                $error = 'Mật khẩu không đúng.';
            }
        } else {
            $error = 'Số điện thoại không tồn tại.';
        }
    }
}

// Include header
include VIEWS_PATH . '/header.php';
?>

<!-- Login Form -->
<section class="min-vh-100 d-flex align-items-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-5">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <h2 class="text-primary fw-bold">
                                <i class="fas fa-tools me-2"></i>TechCare
                            </h2>
                            <p class="text-muted">Đăng nhập vào tài khoản của bạn</p>
                        </div>

                        <?php if ($error): ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo BASE_URL; ?>/login">
                            <!-- Phone Input -->
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Số điện thoại *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-phone text-muted"></i>
                                    </span>
                                    <input type="tel" 
                                           class="form-control" 
                                           id="phone" 
                                           name="phone" 
                                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" 
                                           required
                                           placeholder="Nhập số điện thoại"
                                           pattern="(0[3|5|7|8|9])+([0-9]{8})">
                                </div>
                            </div>
                            
                            <!-- Password Input -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Mật khẩu *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           required
                                           placeholder="Nhập mật khẩu">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Remember & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                                    <label class="form-check-label text-muted" for="remember_me">
                                        Ghi nhớ đăng nhập
                                    </label>
                                </div>
                                <a href="<?php echo url('forgot-password'); ?>" class="text-decoration-none text-primary">
                                    Quên mật khẩu?
                                </a>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                            </button>
                        </form>
                        
                        <!-- Divider -->
                        <div class="position-relative my-4">
                            <hr>
                            <div class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">
                                Hoặc
                            </div>
                        </div>
                        
                        <!-- Google Login -->
                        <a href="<?php echo BASE_URL . '/auth/google-login.php'; ?>" class="btn btn-outline-danger w-100 mb-3">
                            <i class="fab fa-google me-2"></i> Đăng nhập với Google
                        </a>
                        
                        <!-- Register Link -->
                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Chưa có tài khoản? 
                                <a href="<?php echo url('register'); ?>" class="text-decoration-none fw-semibold text-primary">
                                    Đăng ký ngay
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include VIEWS_PATH . '/footer.php';
?>

<style>
.min-vh-100 {
    min-height: 100vh;
}

.card {
    border: none;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0dcaf0);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
}

.toggle-password {
    border-left: none;
}

.input-group-text {
    border-right: none;
}

.input-group .form-control {
    border-left: none;
}

.input-group .form-control:focus {
    border-color: #ced4da;
    box-shadow: none;
}

.input-group:focus-within .input-group-text {
    border-color: #0d6efd;
}

/* Responsive */
@media (max-width: 576px) {
    .card-body {
        padding: 2rem 1.5rem !important;
    }
}
</style>

<script>
// Real-time phone number validation
document.getElementById('phone').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
});

// Show/hide password functionality
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Change icon
        const icon = this.querySelector('i');
        if (type === 'password') {
            icon.className = 'fas fa-eye';
        } else {
            icon.className = 'fas fa-eye-slash';
        }
    });
});
</script>