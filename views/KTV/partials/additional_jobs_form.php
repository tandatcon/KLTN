<?php
// additional_jobs_form.php - SỬA LẠI
if (!isset($ctdd) || !isset($ctdd['chiTietGia']) || !isset($ctdd['chiTietGia']['success']) || !$ctdd['chiTietGia']['success']) {
    echo "<div class='alert alert-warning'>Không thể tải bảng giá cho thiết bị này.</div>";
    return;
}

// LẤY DANH SÁCH PRICES TỪ CHI TIET GIA
$prices = $ctdd['chiTietGia']['prices'] ?? [];
?>

<!-- FORM ẨN ĐỂ LƯU CÔNG VIỆC PHÁT SINH -->
<form id="save_additional_jobs_form_<?php echo $ctdd['maCTDon']; ?>" style="display: none;">
    <input type="hidden" name="danh_sach_cong_viec_phat_sinh_json"
        id="danh_sach_cong_viec_phat_sinh_json_<?php echo $ctdd['maCTDon']; ?>">
</form>

<div class="card border-primary mb-3">
    <div class="card-header bg-primary text-white py-2">
        <h6 class="mb-0">
            <i class="fas fa-plus-circle me-2"></i>Thêm Công Việc Phát Sinh
        </h6>
    </div>
    <div class="card-body">
        <!-- CHỌN LỖI -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Chọn lỗi phát sinh:</label>
            <select class="form-select" id="job_select_phatsinh_<?php echo $ctdd['maCTDon']; ?>"
                onchange="toggleCustomJobInputPhatSinh(this, '<?php echo $ctdd['maCTDon']; ?>')">
                <option value="">-- Chọn lỗi --</option>
                <?php if (!empty($prices)): ?>
                    <?php foreach ($prices as $congViec): ?>
                        <option value="<?php echo htmlspecialchars($congViec['tenLoi']); ?>"
                            data-range="<?php echo htmlspecialchars($congViec['khoangGia'] ?? ''); ?>"
                            data-time="<?php echo isset($congViec['thoiGianSua']) ? (int) $congViec['thoiGianSua'] : 0; ?>"
                            data-cost="<?php echo isset($congViec['gia']) ? (int) $congViec['gia'] : 0; ?>">
                            <?php echo htmlspecialchars($congViec['tenLoi']); ?>
                            (<?php echo $congViec['khoangGia'] ?? '0 VND'; ?> - <?php echo $congViec['thoiGianSua'] ?? 0; ?>
                            phút)
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">-- Không có dữ liệu giá --</option>
                <?php endif; ?>
                <option value="custom">-- Lỗi phát sinh khác --</option>
            </select>
        </div>

        <!-- INPUT TÊN LỖI KHÁC -->
        <div class="mb-3" id="custom_job_name_phatsinh_<?php echo $ctdd['maCTDon']; ?>" style="display: none;">
            <label class="form-label fw-semibold">Nhập tên lỗi phát sinh khác:</label>
            <input type="text" class="form-control" id="custom_job_input_phatsinh_<?php echo $ctdd['maCTDon']; ?>"
                placeholder="Nhập tên lỗi khác...">
        </div>

        <!-- CHI PHÍ SỬA CHỮA -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Chi phí sửa chữa (VND):</label>
            <input type="number" class="form-control" id="job_cost_phatsinh_<?php echo $ctdd['maCTDon']; ?>"
                placeholder="Nhập chi phí...">
            <div class="form-text" id="cost_hint_phatsinh_<?php echo $ctdd['maCTDon']; ?>">
                Nhập chi phí sửa chữa
            </div>
        </div>

        <!-- THÊM INPUT THỜI GIAN CHO PHÁT SINH -->
        <div class="mb-3" id="time_input_div_phatsinh_<?php echo $ctdd['maCTDon']; ?>" style="display:none;">
            <label class="form-label fw-semibold">Thời gian sửa chữa (phút):</label>
            <input type="number" class="form-control" id="job_time_phatsinh_<?php echo $ctdd['maCTDon']; ?>"
                placeholder="Nhập thời gian dự kiến (phút)" min="1" step="1">
            <div class="form-text">
                <i class="fas fa-clock me-1"></i>Thời gian ước tính để hoàn thành công việc (bắt buộc cho lỗi phát sinh
                khác) - nhập theo phút
            </div>
        </div>

        <!-- NÚT THÊM CÔNG VIỆC -->
        <button type="button" class="btn btn-primary w-100"
            onclick="addRepairJobPhatSinh('<?php echo $ctdd['maCTDon']; ?>')">
            <i class="fas fa-plus me-2"></i>Thêm vào danh sách
        </button>
    </div>
</div>

<!-- DANH SÁCH CÔNG VIỆC PHÁT SINH ĐÃ CHỌN -->
<div class="card border-primary mb-3">
    <div class="card-header bg-primary text-white py-2">
        <h6 class="mb-0">
            <i class="fas fa-list-check me-2"></i>Danh Sách Công Việc Phát Sinh Đã Chọn
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="45%">Công việc / Lỗi</th>
                        <th width="15%" class="text-center">Khoảng giá</th>
                        <th width="15%" class="text-center">Thời gian</th>
                        <th width="15%" class="text-end">Chi phí thực tế</th>
                        <th width="5%" class="text-center">Xóa</th>
                    </tr>
                </thead>
                <tbody id="repair_jobs_phatsinh_table_<?php echo $ctdd['maCTDon']; ?>">
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox me-2"></i>Chưa có công việc nào
                        </td>
                    </tr>
                </tbody>
                <tfoot id="repair_jobs_phatsinh_footer_<?php echo $ctdd['maCTDon']; ?>" style="display:none;">
                    <tr class="table-success fw-bold">
                        <td colspan="3" class="text-end">TỔNG CỘNG:</td>
                        <td class="text-center" id="total_time_phatsinh_table_<?php echo $ctdd['maCTDon']; ?>">0 phút
                        </td>
                        <td class="text-end" id="total_phatsinh_table_<?php echo $ctdd['maCTDon']; ?>">0 VND</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- TỔNG KẾT PHÁT SINH -->
        <div class="row g-3 mt-4 px-3">
            <div class="col-md-6">
                <div class="text-center p-3 bg-light rounded border">
                    <h5 class="text-info mb-1">Tổng thời gian phát sinh</h5>
                    <h3 class="text-info fw-bold mb-0" id="total_time_phatsinh_display_<?php echo $ctdd['maCTDon']; ?>">
                        0 phút</h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center p-3 bg-light rounded border">
                    <h5 class="text-primary mb-1">Tổng chi phí phát sinh</h5>
                    <h3 class="text-primary fw-bold mb-0" id="total_phatsinh_display_<?php echo $ctdd['maCTDon']; ?>">0
                        VND</h3>
                </div>
            </div>
        </div>

        <div align="center" class="mt-4 pb-3">
            <button type="button" class="btn btn-success btn-lg px-5"
                onclick="saveAdditionalJobs('<?php echo $ctdd['maCTDon']; ?>')">
                <i class="fas fa-save me-2"></i>Lưu các công việc phát sinh
            </button>
        </div>
    </div>
</div>

<!-- MINH CHỨNG HOÀN THÀNH - BẮT BUỘC TRƯỚC KHI KẾT THÚC -->
<div class="card border-primary mb-3">
    <div class="card-header bg-primary text-white py-2">
        <h6 class="mb-0">
            <i class="fas fa-camera me-2"></i>Minh Chứng Hoàn Thành (Bắt buộc)
            <?php if ($daUploadHoanThanh): ?>
                <span class="badge bg-success ms-2">Đã upload</span>
            <?php else: ?>
                <span class="badge bg-danger ms-2">Chưa upload</span>
            <?php endif; ?>
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <?php if ($daUploadHoanThanh): ?>
                    <!-- HIỂN THỊ ẢNH ĐÃ UPLOAD -->
                    <div class="text-center mb-3">
                        <img src="<?php echo url('assets/images/' . $minhChungThietBi['minhchunghoanthanh']); ?>"
                            class="img-fluid rounded cursor-pointer evidence-image"
                            style="max-height: 200px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal"
                            data-image-src="<?php echo url('assets/images/' . $minhChungThietBi['minhchunghoanthanh']); ?>"
                            onerror="this.src='<?php echo url('assets/images/no-image.jpg'); ?>'"
                            alt="Minh chứng hoàn thành">
                        <div class="mt-2">
                            <small class="text-muted">Click để phóng to</small>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- FORM UPLOAD MỚI - SỬ DỤNG CÙNG HÀM UPLOAD HIỆN TẠI -->
                    <form method="POST" enctype="multipart/form-data" class="ajax-upload-form">
                        <input type="hidden" name="ctdon_id" value="<?php echo $ctdd['maCTDon']; ?>">
                        <input type="hidden" name="evidence_type" value="completion">

                        <div class="upload-area-simple" id="uploadAreaCompletion_<?php echo $ctdd['maCTDon']; ?>">
                            <div class="upload-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="upload-text-simple">Upload ảnh hoàn thành</div>
                            <div class="upload-subtext-simple">
                                Chụp ảnh thiết bị sau khi sửa xong<br>
                                PNG, JPG, GIF up to 5MB
                            </div>
                        </div>

                        <input type="file" id="fileInputCompletion_<?php echo $ctdd['maCTDon']; ?>" name="evidence_image"
                            accept="image/*" style="display: none;">

                        <div class="preview-container-simple"
                            id="previewContainerCompletion_<?php echo $ctdd['maCTDon']; ?>" style="display: none;">
                            <div class="preview-title-simple">Ảnh minh chứng hoàn thành:</div>
                            <img id="previewImageCompletion_<?php echo $ctdd['maCTDon']; ?>" class="preview-image-simple"
                                src="" alt="Preview">
                            <div class="preview-actions mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    id="changeBtnCompletion_<?php echo $ctdd['maCTDon']; ?>">
                                    <i class="fas fa-redo me-1"></i>Đổi ảnh
                                </button>
                                <button type="button" class="btn btn-success btn-sm"
                                    onclick="uploadEvidence('<?php echo $ctdd['maCTDon']; ?>', 'completion')">
                                    <i class="fas fa-upload me-1"></i>Upload minh chứng
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Lưu ý:</strong> Phải upload minh chứng hoàn thành trước khi kết thúc sửa chữa
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Khởi tạo mảng + reset form ngay từ đầu
    document.addEventListener('DOMContentLoaded', function () {
        const ma = '<?php echo $ctdd['maCTDon']; ?>';

        // Khởi tạo mảng nếu chưa có
        if (typeof danhSachCongViecPhatSinh === 'undefined') {
            danhSachCongViecPhatSinh = {};
        }
        if (!danhSachCongViecPhatSinh[ma]) {
            danhSachCongViecPhatSinh[ma] = [];
        }

        // ẨN CHI PHÍ + HIỆN HINT MẶC ĐỊNH
        const chiPhiRow = document.querySelector('#job_cost_phatsinh_' + ma)?.closest('.mb-3');
        if (chiPhiRow) chiPhiRow.style.display = 'none';

        const hint = document.getElementById('cost_hint_phatsinh_' + ma);
        if (hint) hint.innerHTML = '<i class="fas fa-info-circle"></i> Chọn lỗi để xem thông tin giá';

        // Hiển thị danh sách cũ (nếu có)
        hienThiDanhSachCongViecPhatSinh(ma);

        // Upload ảnh hoàn thành
        <?php if (!$daUploadHoanThanh): ?>
            initUploadArea('Completion', ma);
        <?php endif; ?>
    });
</script>