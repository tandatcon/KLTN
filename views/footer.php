<!-- Footer -->
<footer class="bg-dark text-white pt-5 pb-3">
    <div class="container">
        <div class="row g-4">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6">
                <div class="mb-4">
                    <h3 class="h4 fw-bold text-primary mb-3">TechCare</h3>
                    <p class="text-light mb-4">Dịch vụ sửa chữa thiết bị điện tử uy tín, chuyên nghiệp hàng đầu Việt
                        Nam.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white text-decoration-none">
                            <i class="fab fa-facebook fs-5 hover-lift"></i>
                        </a>
                        <a href="#" class="text-white text-decoration-none">
                            <i class="fab fa-twitter fs-5 hover-lift"></i>
                        </a>
                        <a href="#" class="text-white text-decoration-none">
                            <i class="fab fa-instagram fs-5 hover-lift"></i>
                        </a>
                        <a href="#" class="text-white text-decoration-none">
                            <i class="fab fa-youtube fs-5 hover-lift"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <div class="mb-4">
                    <h4 class="h5 fw-bold mb-3">Liên kết nhanh</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="<?php echo url('index.php'); ?>"
                                class="text-light text-decoration-none hover-primary">
                                <i class="fas fa-chevron-right me-2 small"></i>Trang chủ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo url('index.php#services'); ?>"
                                class="text-light text-decoration-none hover-primary">
                                <i class="fas fa-chevron-right me-2 small"></i>Dịch vụ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo url('index.php#about'); ?>"
                                class="text-light text-decoration-none hover-primary">
                                <i class="fas fa-chevron-right me-2 small"></i>Về chúng tôi
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo url('index.php#contact'); ?>"
                                class="text-light text-decoration-none hover-primary">
                                <i class="fas fa-chevron-right me-2 small"></i>Liên hệ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Services -->
            <div class="col-lg-2 col-md-6">
                <div class="mb-4">
                    <h4 class="h5 fw-bold mb-3">Dịch vụ</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none hover-primary">
                                <i class="fas fa-chevron-right me-2 small"></i>Sửa Tivi
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none hover-primary">
                                <i class="fas fa-chevron-right me-2 small"></i>Sửa Tủ Lạnh
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none hover-primary">
                                <i class="fas fa-chevron-right me-2 small"></i>Sửa Máy Giặt
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none hover-primary">
                                <i class="fas fa-chevron-right me-2 small"></i>Sửa Máy Lạnh
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4 col-md-6">
                <div class="mb-4">
                    <h4 class="h5 fw-bold mb-3">Liên hệ</h4>
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-map-marker-alt text-primary mt-1 me-3"></i>
                        <p class="text-light mb-0 small">Nguyễn Văn Bảo/12 Đ. Hạnh Thông, Phường, Gò Vấp, Hồ Chí Minh
                            700000, Việt Nam</p>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-phone text-primary me-3"></i>
                        <p class="text-light mb-0 small">0900 123 456</p>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fab fa-zalo text-primary me-3"></i>
                        <p class="text-light mb-0 small">0900 123 456</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-envelope text-primary me-3"></i>
                        <p class="text-light mb-0 small">info@techcare.vn</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="row pt-4 mt-3 border-top border-secondary">
            <div class="col-12 text-center">
                <p class="mb-0 text-light small">
                    &copy; 2025 TechCare. Tất cả các quyền được bảo lưu.
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Optional: Add this CSS for hover effects -->
<style>
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-3px);
        color: #1e40af !important;
    }

    .hover-primary {
        transition: all 0.3s ease;
    }

    .hover-primary:hover {
        color: #1e40af !important;
        padding-left: 5px;
    }

    .border-custom {
        border-color: #374151 !important;
    }
</style>

<script src="<?php echo asset('js/script.js'); ?>"></script>
</body>

</html>