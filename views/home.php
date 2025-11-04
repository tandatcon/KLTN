<?php
$pageTitle = "TechCare - Trung tâm sửa chữa điện tử";
include 'views/header.php';
session_start();
?>

<!-- Banner Section -->
<section class="banner mb-0">
    <div class="container-fluid p-0">
        <div class="position-relative">
            <div class="banner-image-container">
                <img src="<?php echo asset('images/banner3.jpg'); ?>" alt="TechCare Banner"
                    class="img-fluid w-100 banner-image">
            </div>
        </div>
    </div>
</section>

<!-- Contact Hotline Bar -->
<section class="contact-bar bg-primary text-white py-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 gap-md-5">
                    <div class="d-flex align-items-center contact-item">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <div class="contact-text">
                            <strong>Địa chỉ:</strong>
                            <span>12 Đ. Hạnh Thông, Gò Vấp, HCM</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center contact-item">
                        <i class="fas fa-phone-alt me-2"></i>
                        <div class="contact-text">
                            <strong>Hotline:</strong>
                            <span>0797 008 745</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Floating Contact Buttons -->
<div class="floating-contact-buttons">
    <a href="tel:0797008745" class="floating-btn floating-call" title="Gọi ngay">
        <i class="fas fa-phone"></i>
        <span class="floating-pulse"></span>
    </a>

</div>

<!-- Giới thiệu về TechCare -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold text-primary mb-4">Sửa Điện Tử – Trung Tâm Sửa Thiết Bị Điện Tử TPHCM</h1>
                <p class="lead text-dark mb-4">
                    Trung tâm Sửa Điện Tử <strong>TechCare</strong> cung cấp các dịch vụ <strong>SỬA CHỮA – BẢO DƯỠNG –
                        LẮP ĐẶT</strong> các loại thiết bị điện tử dân dụng, văn phòng với cam kết <strong>UY TÍN – CHẤT
                        LƯỢNG – CHUYÊN NGHIỆP – GIÁ RẺ</strong>.
                </p>
                <p class="text-muted mb-4">
                    Chúng tôi không ngừng cố gắng để tạo nên sự đa dạng và ngày càng nâng cấp dịch vụ của mình thông qua
                    việc chuyên môn hóa từng bộ phận. Tự tin là doanh nghiệp mang lại giải pháp tiết kiệm nhất cho các
                    gia đình Việt trong việc lắp đặt, bảo trì sửa chữa bo mạch điện tử, thiết bị, máy móc dân dụng.
                </p>
                <a href="<?php echo url('datdichvu'); ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-right me-2"></i>Đặt dịch vụ ngay
                </a>
            </div>
            <div class="col-lg-4">
                <div class="text-center">
                    <img src="<?php echo asset('images/home1.jpg'); ?>" alt="Trung tâm TechCare"
                        class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Quy trình dịch vụ -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-dark mb-3">QUY TRÌNH TRIỂN KHAI DỊCH VỤ TẠI TECHCARE</h2>
            <p class="lead text-muted">Quy trình chuyên nghiệp - Phục vụ tận tâm</p>
        </div>

        <div class="row g-4">
            <!-- Bước 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-3"
                            style="width: 60px; height: 60px;">
                            01
                        </div>
                        <h5 class="fw-bold text-dark mb-3">Tiếp nhận thông tin</h5>
                        <p class="text-muted mb-0">Tiếp nhận yêu cầu của khách hàng thông qua <br> <strong
                                class="text-primary"><a href="<?php echo url('datdichvu'); ?>">
                                    Đặt dịch vụ - Hotline
                                </a></strong></p>
                    </div>
                </div>
            </div>

            <!-- Bước 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-3"
                            style="width: 60px; height: 60px;">
                            02
                        </div>
                        <h5 class="fw-bold text-dark mb-3">Khảo sát tình hình</h5>
                        <p class="text-muted mb-0">Gọi điẹn thoại xác nhận và điều động KTV đến tận nơi theo <strong class="text-primary">thời gian sớm nhất</strong> và tiến hành kiểm tra máy</p>
                    </div>
                </div>
            </div>

            <!-- Bước 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-3"
                            style="width: 60px; height: 60px;">
                            03
                        </div>
                        <h5 class="fw-bold text-dark mb-3">Báo giá dịch vụ</h5>
                        <p class="text-muted mb-0">Tiến hành báo giá dịch vụ dựa theo tình trạng máy</p>
                    </div>
                </div>
            </div>

            <!-- Bước 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-3"
                            style="width: 60px; height: 60px;">
                            04
                        </div>
                        <h5 class="fw-bold text-dark mb-3">Tiến hành sửa chữa</h5>
                        <p class="text-muted mb-0">Thực hiện vệ sinh, sửa chữa khi khách hàng đồng ý với mức phí</p>
                    </div>
                </div>
            </div>

            <!-- Bước 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-3"
                            style="width: 60px; height: 60px;">
                            05
                        </div>
                        <h5 class="fw-bold text-dark mb-3">Kiểm tra và bàn giao máy</h5>
                        <p class="text-muted mb-0">Kiểm tra và vận hành máy lần cuối trước khi bàn giao máy cho khách
                            hàng</p>
                    </div>
                </div>
            </div>

            <!-- Bước 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-3"
                            style="width: 60px; height: 60px;">
                            06
                        </div>
                        <h5 class="fw-bold text-dark mb-3">Hoàn tất và thu phí dịch vụ</h5>
                        <p class="text-muted mb-0">Hoàn tất quy trình và tiến hành thanh toán phí dịch vụ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Section -->
<section id="services" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-dark mb-3">Dịch Vụ Sửa Chữa Của Chúng Tôi</h2>
            <p class="lead text-muted">Đa dạng dịch vụ sửa chữa cho mọi thiết bị điện tử gia dụng</p>
        </div>

        <div class="row g-4">
            <!-- Service 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-tv text-white fs-3"></i>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-3">Sửa Tivi</h5>
                        <p class="card-text text-muted mb-3">Khắc phục mọi sự cố về hình ảnh, âm thanh</p>
                        <ul class="list-unstyled text-start mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Sửa màn hình</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Khắc phục âm thanh</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Lỗi phần mềm</li>
                        </ul>
                        <a href="<?php echo url('datdichvu'); ?>" class="btn btn-primary w-100">
                            <i class="fas fa-calendar-plus me-2"></i>Đặt dịch vụ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-snowflake text-white fs-3"></i>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-3">Sửa Tủ Lạnh</h5>
                        <p class="card-text text-muted mb-3">Sửa chữa, bảo dưỡng tủ lạnh chuyên nghiệp</p>
                        <ul class="list-unstyled text-start mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Không làm lạnh</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Xả đá tự động</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bảo dưỡng định kỳ</li>
                        </ul>
                        <a href="<?php echo url('datdichvu'); ?>" class="btn btn-primary w-100">
                            <i class="fas fa-calendar-plus me-2"></i>Đặt dịch vụ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-wind text-white fs-3"></i>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-3">Sửa Máy Lạnh</h5>
                        <p class="card-text text-muted mb-3">Vệ sinh, bảo dưỡng, sửa chữa máy lạnh</p>
                        <ul class="list-unstyled text-start mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Vệ sinh máy lạnh</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bơm gas</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Sửa board mạch</li>
                        </ul>
                        <a href="<?php echo url('datdichvu'); ?>" class="btn btn-primary w-100">
                            <i class="fas fa-calendar-plus me-2"></i>Đặt dịch vụ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-blender text-white fs-3"></i>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-3">Sửa Máy Giặt</h5>
                        <p class="card-text text-muted mb-3">Khắc phục lỗi vắt, xả, không hoạt động</p>
                        <ul class="list-unstyled text-start mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Không vắt được</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Rò rỉ nước</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bảo dưỡng định kỳ</li>
                        </ul>
                        <a href="<?php echo url('datdichvu'); ?>" class="btn btn-primary w-100">
                            <i class="fas fa-calendar-plus me-2"></i>Đặt dịch vụ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-utensils text-white fs-3"></i>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-3">Sửa Lò Vi Sóng</h5>
                        <p class="card-text text-muted mb-3">Sửa chữa lò vi sóng không nóng, không hoạt động</p>
                        <ul class="list-unstyled text-start mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Thay magnetron</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Sửa board điều khiển</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Thay cầu chì</li>
                        </ul>
                        <a href="<?php echo url('datdichvu'); ?>" class="btn btn-primary w-100">
                            <i class="fas fa-calendar-plus me-2"></i>Đặt dịch vụ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-fire text-white fs-3"></i>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-3">Sửa Bếp Từ</h5>
                        <p class="card-text text-muted mb-3">Khắc phục lỗi bếp từ không nóng, mất nguồn</p>
                        <ul class="list-unstyled text-start mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Thay mâm nhiệt</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Sửa mạch điện</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Lỗi cảm ứng</li>
                        </ul>
                        <a href="<?php echo url('datdichvu'); ?>" class="btn btn-primary w-100">
                            <i class="fas fa-calendar-plus me-2"></i>Đặt dịch vụ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-dark mb-3">Tại Sao Chọn TechCare?</h2>
            <p class="lead text-muted">Những lý do khách hàng tin tưởng lựa chọn chúng tôi</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 100px; height: 100px;">
                        <i class="fas fa-user-tie text-white fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Đội ngũ chuyên gia</h5>
                    <p class="text-muted">Kỹ thuật viên được đào tạo bài bản, có chứng chỉ chuyên môn</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 100px; height: 100px;">
                        <i class="fas fa-cogs text-white fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Thiết bị hiện đại</h5>
                    <p class="text-muted">Trang thiết bị sửa chữa hiện đại, đáp ứng mọi yêu cầu kỹ thuật</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 100px; height: 100px;">
                        <i class="fas fa-clock text-white fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Nhanh chóng</h5>
                    <p class="text-muted">Thời gian sửa chữa nhanh, đảm bảo tiến độ cam kết</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 100px; height: 100px;">
                        <i class="fas fa-medal text-white fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Chất lượng</h5>
                    <p class="text-muted">Linh kiện chính hãng, bảo hành dài hạn, hậu mãi chu đáo</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-dark mb-3">Khách Hàng Nói Gì Về Chúng Tôi</h2>
            <p class="lead text-muted">Những phản hồi chân thực từ khách hàng</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="text-warning mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text text-muted mb-4">"Dịch vụ rất chuyên nghiệp, kỹ thuật viên nhiệt tình.
                            Laptop của tôi được sửa nhanh chóng và hoạt động tốt."</p>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Anh Minh</h6>
                                <small class="text-muted">Khách hàng sửa laptop</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="text-warning mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text text-muted mb-4">"Máy lạnh nhà tôi hỏng được sửa rất nhanh, giá cả hợp lý.
                            Rất hài lòng với dịch vụ của TechCare."</p>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Chị Hương</h6>
                                <small class="text-muted">Khách hàng sửa máy lạnh</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="text-warning mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text text-muted mb-4">"Máy nước nóng lạnh của tôi bị vỡ màn hình, TechCare thay
                            thế nhanh chóng với giá tốt. Sẽ giới thiệu bạn bè đến đây."</p>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Bạn Nam</h6>
                                <small class="text-muted">Khách hàng sửa điện thoại</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'views/footer.php'; ?>

<style>
    /* Banner styles */
    .banner-image-container {
        width: 100%;
        overflow: hidden;
    }

    .banner-image {
        height: 500px;
        object-fit: cover;
        object-position: center;
    }

    /* Contact Bar Styles */
    .contact-bar {
        font-size: 0.9rem;
        padding: 0.5rem 0 !important;
    }

    .contact-item {
        display: flex;
        align-items: center;
    }

    .contact-text {
        font-size: 0.9rem;
        white-space: nowrap;
    }

    .contact-bar .fas {
        font-size: 0.9rem;
        min-width: 16px;
    }

    /* Floating Contact Buttons */
    .floating-contact-buttons {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .floating-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        border: none;
    }

    .floating-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        color: white;
    }

    .floating-call {
        background: linear-gradient(135deg, #28a745, #20c997);
        animation: pulse 2s infinite;
    }

    .floating-zalo {
        background: linear-gradient(135deg, #0068FF, #0099FF);
        font-weight: bold;
    }

    .zalo-icon {
        font-weight: bold;
        font-size: 1.2rem;
    }

    .floating-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #28a745;
        animation: ring 1.5s infinite;
        z-index: -1;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    @keyframes ring {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }

    /* Mobile styles */


    @media (max-width: 576px) {
        .banner-image {
            height: 200px !important;
        }

        .contact-bar {
            padding: 0.9rem 0 !important;
        }

        .contact-text {
            font-size: 0.6rem !important;
        }

        .contact-bar .fas {
            font-size: 0.6rem;
        }

        .floating-contact-buttons {
            bottom: 15px;
            right: 15px;
        }

        .floating-btn {
            width: 45px;
            height: 45px;
            font-size: 1.1rem;
        }

        .zalo-icon {
            font-size: 0.9rem;
        }
    }

    /* Step number styles */
    .step-number {
        transition: all 0.3s ease;
    }

    .card:hover .step-number {
        transform: scale(1.1);
        background: linear-gradient(135deg, #0d6efd, #0dcaf0) !important;
    }

    /* Hover effects */
    .hover-shadow {
        transition: all 0.3s ease;
    }

    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(30, 64, 175, 0.15) !important;
    }

    /* Ensure full width */
    .container-fluid {
        padding: 0;
    }

    .banner {
        margin: 0;
    }
</style>

<script>
    // Smooth scroll
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>