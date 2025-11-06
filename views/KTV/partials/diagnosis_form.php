<?php
// Đảm bảo biến $ctdd và $chiTietGia đã được định nghĩa
if (!isset($ctdd) || !isset($chiTietGia))
    return;
?>

<form id="diagnosis_form_<?php echo $ctdd['maCTDon']; ?>">
    <input type="hidden" name="ctdon_id" value="<?php echo $ctdd['maCTDon']; ?>">
    <input type="hidden" name="danh_sach_cong_viec_json" id="danh_sach_cong_viec_json_<?php echo $ctdd['maCTDon']; ?>">

    <!-- CHẨN ĐOÁN TÌNH TRẠNG -->
    <div class="card border-primary mb-3">
        <div class="card-header bg-primary text-white py-2">
            <h6 class="mb-0">
                <i class="fas fa-stethoscope me-2"></i>Chẩn Đoán Tình Trạng
            </h6>
        </div>
        <div class="card-body">
            <textarea class="form-control" id="diagnosis_<?php echo $ctdd['maCTDon']; ?>" name="diagnosis" rows="3"
                required placeholder="Mô tả chi tiết tình trạng hư hỏng..."></textarea>
        </div>
    </div>

    <!-- THÊM CÔNG VIỆC MỚI -->
    <div class="card border-primary mb-3">
        <div class="card-header bg-primary text-white py-2">
            <h6 class="mb-0">
                <i class="fas fa-plus-circle me-2"></i>Thêm Công Việc Sửa Chữa
            </h6>
        </div>
        <div class="card-body">
            <!-- CHỌN LỖI -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Chọn lỗi:</label>
                <select class="form-select" id="job_select_<?php echo $ctdd['maCTDon']; ?>"
                    onchange="toggleCustomJobInput(this, '<?php echo $ctdd['maCTDon']; ?>')">
                    <option value="">-- Chọn lỗi --</option>
                    <?php if (!empty($chiTietGia)): ?>
                        <?php foreach ($chiTietGia as $congViec): ?>
                            <option value="<?php echo htmlspecialchars($congViec['chitietloi']); ?>"
                                data-range="<?php echo htmlspecialchars($congViec['khoangGia'] ?? ''); ?>">
                                <?php echo htmlspecialchars($congViec['chitietloi']); ?>
                                <?php if (!empty($congViec['khoangGia'])): ?>
                                    (<?php echo htmlspecialchars($congViec['khoangGia']); ?>)
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <option value="custom">-- Lỗi khác --</option>
                </select>
            </div>

            <!-- INPUT TÊN LỖI KHÁC -->
            <div class="mb-3" id="custom_job_name_<?php echo $ctdd['maCTDon']; ?>" style="display: none;">
                <label class="form-label fw-semibold">Nhập tên lỗi:</label>
                <input type="text" class="form-control" id="custom_job_input_<?php echo $ctdd['maCTDon']; ?>"
                    placeholder="Nhập tên lỗi khác...">
            </div>

            <!-- CHI PHÍ SỬA CHỮA -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Chi phí sửa chữa (VND):</label>
                <input type="number" class="form-control" id="job_cost_<?php echo $ctdd['maCTDon']; ?>"
                    placeholder="Nhập chi phí...">
                <div class="form-text" id="cost_hint_<?php echo $ctdd['maCTDon']; ?>">
                    Nhập chi phí sửa chữa
                </div>
            </div>

            <!-- NÚT THÊM CÔNG VIỆC -->
            <button type="button" class="btn btn-primary w-100"
                onclick="addRepairJob('<?php echo $ctdd['maCTDon']; ?>')">
                <i class="fas fa-plus me-2"></i>Thêm vào danh sách
            </button>
        </div>
    </div>

    <!-- DANH SÁCH CÔNG VIỆC ĐÃ CHỌN -->
    <div class="card border-primary mb-3">
        <div class="card-header bg-primary text-white py-2">
            <h6 class="mb-0">
                <i class="fas fa-list-check me-2"></i>Danh Sách Công Việc Đã Chọn
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">STT</th>
                            <th width="50%">Công việc</th>
                            <th width="20%">Khoảng giá</th>
                            <th width="15%">Chi phí (VND)</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="repair_jobs_table_<?php echo $ctdd['maCTDon']; ?>">
                        <tr>
                            <td colspan="5" class="text-center py-3 text-muted">
                                <i class="fas fa-info-circle me-2"></i>
                                Chưa có công việc nào được thêm
                            </td>
                        </tr>
                    </tbody>
                    <tfoot id="repair_jobs_footer_<?php echo $ctdd['maCTDon']; ?>" style="display: none;">
                        <tr class="table-secondary">
                            <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                            <td class="text-end fw-bold" id="total_table_<?php echo $ctdd['maCTDon']; ?>">0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- TỔNG BÁO GIÁ -->
    <div class="mb-4">
        <h6 class="text-primary mb-2">
            <i class="fas fa-calculator me-2"></i>Tổng Báo Giá
        </h6>
        <div class="border rounded p-3 bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label class="form-label fw-bold mb-0">Tổng chi phí:</label>
                    <div class="form-text">Tổng chi phí các công việc đã chọn</div>
                </div>
                <div class="col-md-6 text-end">
                    <span class="h4 text-primary fw-bold" id="total_display_<?php echo $ctdd['maCTDon']; ?>">0
                        VND</span>
                    <input type="hidden" name="estimated_cost" id="total_estimated_cost_<?php echo $ctdd['maCTDon']; ?>"
                        value="0">
                </div>
            </div>
        </div>
    </div>

    <!-- QUYẾT ĐỊNH CỦA KHÁCH HÀNG -->
    <div class="mb-4">
        <h6 class="text-primary mb-2">
            <i class="fas fa-user-check me-2"></i>Quyết Định Của Khách Hàng
        </h6>
        <div class="card border-warning">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Khách hàng:</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="decision_<?php echo $ctdd['maCTDon']; ?>"
                                id="agree_<?php echo $ctdd['maCTDon']; ?>" value="1" required>
                            <label class="form-check-label text-success" for="agree_<?php echo $ctdd['maCTDon']; ?>">
                                <i class="fas fa-check me-1"></i>Đồng ý sửa chữa
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="decision_<?php echo $ctdd['maCTDon']; ?>"
                                id="disagree_<?php echo $ctdd['maCTDon']; ?>" value="2">
                            <label class="form-check-label text-danger" for="disagree_<?php echo $ctdd['maCTDon']; ?>">
                                <i class="fas fa-times me-1"></i>Không đồng ý
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3" id="reasonSection_<?php echo $ctdd['maCTDon']; ?>" style="display: none;">
                    <label class="form-label fw-bold">Lý do không đồng ý:</label>
                    <textarea class="form-control" id="reason_<?php echo $ctdd['maCTDon']; ?>" name="reason" rows="2"
                        placeholder="Nhập lý do khách hàng không đồng ý sửa chữa..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- NÚT LƯU -->
    <div class="text-center">
        <button type="button" class="btn btn-success btn-lg" onclick="saveDiagnosis('<?php echo $ctdd['maCTDon']; ?>')">
            <i class="fas fa-save me-2"></i>Lưu Chẩn Đoán & Báo Giá
        </button>
    </div>
</form>

<script>
    // Xử lý hiển thị/ẩn lý do khi chọn không đồng ý
    document.addEventListener('DOMContentLoaded', function () {
        const disagreeRadio = document.getElementById('disagree_<?php echo $ctdd['maCTDon']; ?>');
        const agreeRadio = document.getElementById('agree_<?php echo $ctdd['maCTDon']; ?>');
        const reasonSection = document.getElementById('reasonSection_<?php echo $ctdd['maCTDon']; ?>');

        if (disagreeRadio && reasonSection) {
            disagreeRadio.addEventListener('change', function () {
                reasonSection.style.display = this.checked ? 'block' : 'none';
            });

            agreeRadio.addEventListener('change', function () {
                reasonSection.style.display = 'none';
            });
        }
    });
</script>