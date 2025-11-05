<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

session_start();
// views/register.php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Đăng ký - TechCare";

// Xử lý đăng ký
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['fullname']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = trim($_POST['phone']);

    // Validate dữ liệu
    if (empty($username) || empty($password) || empty($confirm_password) || empty($phone)) {
        $error = 'Vui lòng điền đầy đủ thông tin.';
    } elseif (!preg_match('/^(0[3|5|7|8|9])+([0-9]{8})$/', $phone)) {
        $error = 'Số điện thoại không hợp lệ.';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } elseif ($password !== $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } else {
        // Kiểm tra số điện thoại đã tồn tại chưa
        $stmt = $db->prepare("SELECT maND FROM nguoidung WHERE sdt = ?");
        $stmt->execute([$phone]);

        if ($stmt->rowCount() > 0) {
            $error = 'Số điện thoại đã được đăng ký.';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Thêm user vào database
            $stmt = $db->prepare("INSERT INTO nguoidung (hoTen, password, sdt, login_method,maVaiTro) VALUES (?, ?, ?, 'normal','1')");

            if ($stmt->execute([$username, $hashed_password, $phone])) {
                // Lấy thông tin user vừa đăng ký
                $stmt = $db->prepare("SELECT * FROM nguoidung WHERE sdt = ?");
                $stmt->execute([$phone]);
                $newUser = $stmt->fetch();

                if ($newUser) {
                    // Set session đăng nhập
                    $_SESSION['user_id'] = $newUser['maND'];
                    $_SESSION['user_name'] = $newUser['hoTen'];
                    $_SESSION['user_phone'] = $newUser['sdt'];
                    $_SESSION['login_method'] = 'normal';
                    $_SESSION['login_time'] = time();

                    // Set thông báo thành công
                    $_SESSION['success_message'] = "🎉 Đăng ký thành công! Chào mừng bạn đến với TechCare.";

                    // Chuyển hướng về trang chủ
                    header("Location: " . url('home'));
                    exit;
                }
            } else {
                $error = 'Đăng ký thất bại. Vui lòng thử lại.';
            }
        }
    }
}

// Include header
include VIEWS_PATH . '/header.php';
?>

<!-- Register Form -->
<section class="min-vh-100 d-flex align-items-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-5">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <h2 class="text-primary fw-bold">
                                <i class="fas fa-tools me-2"></i>TechCare
                            </h2>
                            <p class="text-muted">Tạo tài khoản mới</p>
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
                                <?php echo $_SESSION['success_message'];
                                unset($_SESSION['success_message']); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <!-- Full Name Input -->
                            <div class="mb-3">
                                <label for="fullname" class="form-label fw-semibold">Họ và tên *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control" id="fullname" name="fullname"
                                        value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>"
                                        required placeholder="Nhập họ và tên của bạn">
                                </div>
                            </div>

                            <!-- Phone Input -->
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Số điện thoại *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-phone text-muted"></i>
                                    </span>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                        required placeholder="Nhập số điện thoại" pattern="(0[3|5|7|8|9])+([0-9]{8})">
                                </div>
                                <div class="form-text">Định dạng: 09xxxxxxxx hoặc 03xxxxxxxx</div>
                            </div>

                            <!-- Password Input -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Mật khẩu *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" required
                                        placeholder="Nhập mật khẩu (ít nhất 6 ký tự)" minlength="6">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password Input -->
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label fw-semibold">Xác nhận mật khẩu *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" required placeholder="Nhập lại mật khẩu" minlength="6">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text text-danger" id="password-match-message"></div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                                <i class="fas fa-user-plus me-2"></i> Đăng ký
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="position-relative my-4">
                            <hr>
                            <div class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">
                                Hoặc
                            </div>
                        </div>

                        <!-- Google Register -->
                        <a href="<?php echo BASE_URL . '/auth/google-login.php'; ?>"
                            class="btn btn-outline-danger w-100 mb-4">
                            <i class="fab fa-google me-2"></i> Đăng ký với Google
                        </a>

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Đã có tài khoản?
                                <a href="<?php echo url('login'); ?>"
                                    class="text-decoration-none fw-semibold text-primary">
                                    Đăng nhập ngay
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
// Include footer
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

    .password-match {
        border-color: #198754 !important;
    }

    .password-mismatch {
        border-color: #dc3545 !important;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .card-body {
            padding: 2rem 1.5rem !important;
        }
    }
</style>

<script>
    // Real-time password confirmation check
    document.addEventListener('DOMContentLoaded', function () {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const message = document.getElementById('password-match-message');

        function validatePassword() {
            if (password.value === '' || confirmPassword.value === '') {
                message.textContent = '';
                confirmPassword.classList.remove('password-match', 'password-mismatch');
                return;
            }

            if (password.value === confirmPassword.value) {
                message.textContent = '✓ Mật khẩu khớp';
                message.className = 'form-text text-success';
                confirmPassword.classList.add('password-match');
                confirmPassword.classList.remove('password-mismatch');
            } else {
                message.textContent = '✗ Mật khẩu không khớp';
                message.className = 'form-text text-danger';
                confirmPassword.classList.add('password-mismatch');
                confirmPassword.classList.remove('password-match');
            }
        }

        password.addEventListener('input', validatePassword);
        confirmPassword.addEventListener('input', validatePassword);

        // Real-time phone number validation
        document.getElementById('phone').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        // Show/hide password functionality
        const toggleButtons = document.querySelectorAll('.toggle-password');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                const input = this.parentElement.querySelector('input');
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                // Change icon
                const icon = this.querySelector('i');
                if (type === 'password') {
                    icon.className = 'fas fa-eye';
                } else {
                    icon.className = 'fas fa-eye-slash';
                }
            });
        });
    });
</script>