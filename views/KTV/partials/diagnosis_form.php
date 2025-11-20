<?php
// diagnosis_form.php - PHIÊN BẢN HOÀN CHỈNH 2025
if (!isset($ctdd) || !isset($chiTietGia)) {
    return;
}
?>

<form id="diagnosis_form_<?php echo $ctdd['maCTDon']; ?>">
    <input type="hidden" name="ctdon_id" value="<?php echo $ctdd['maCTDon']; ?>">
    <input type="hidden" name="danh_sach_cong_viec_json" id="danh_sach_cong_viec_json_<?php echo $ctdd['maCTDon']; ?>">

    <!-- CHẨN ĐOÁN TÌNH TRẠNG -->
    <div class="card border-primary mb-4 shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h6 class="mb-0">
                <i class="fas fa-stethoscope me-2"></i>Chẩn Đoán Tình Trạng Thiết Bị
            </h6>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label fw-bold">Mô tả tình trạng thực tế:</label>
                <textarea class="form-control" id="diagnosis_<?php echo $ctdd['maCTDon']; ?>" name="diagnosis" rows="3"
                    placeholder="Nhập chi tiết tình trạng thực tế của thiết bị sau khi kiểm tra..." required></textarea>
                <div class="form-text">Mô tả rõ ràng giúp khách hàng hiểu được vấn đề thiết bị đang gặp phải</div>
            </div>
        </div>
    </div>

    <!-- THÊM CÔNG VIỆC SỬA CHỮA -->
    <div class="card border-success mb-4 shadow-sm">
        <div class="card-header bg-success text-white py-2">
            <h6 class="mb-0">
                <i class="fas fa-tools me-2"></i>Thêm Công Việc Sửa Chữa
            </h6>
        </div>
        <div class="card-body">
            <!-- CHỌN LỖI -->
            <div class="row mb-3">
                <div class="col-12">
                    <label class="form-label fw-bold text-success">Chọn lỗi phổ biến:</label>
                    <select class="form-select" id="job_select_<?php echo $ctdd['maCTDon']; ?>"
                        onchange="toggleCustomJobInput(this, '<?php echo $ctdd['maCTDon']; ?>')">
                        <option value="">-- Chọn lỗi sửa chữa --</option>
                        <?php foreach ($chiTietGia as $cv): ?>
                            <option value="<?php echo htmlspecialchars($cv['chitietloi']); ?>"
                                data-range="<?php echo htmlspecialchars($cv['khoangGia'] ?? ''); ?>"
                                data-time="<?php echo $cv['thoigiansuachua'] ?? 0; ?>"
                                data-cost="<?php echo $cv['gia'] ?? 0; ?>">
                                <?php echo htmlspecialchars($cv['chitietloi']); ?>
                                (<?php echo $cv['khoangGia']; ?> - <?php echo $cv['thoigiansuachua']; ?>phút)
                            </option>
                        <?php endforeach; ?>
                        <option value="custom">-- Lỗi khác (tự nhập) --</option>
                    </select>
                </div>
            </div>
<!-- LỖI KHÁC -->
<div class="row mb-3" id="custom_job_name_<?php echo $ctdd['maCTDon']; ?>" style="display:none;">
                <div class="col-12">
                    <label class="form-label fw-bold">Tên lỗi khác:</label>
                    <input type="text" class="form-control" id="custom_job_input_<?php echo $ctdd['maCTDon']; ?>"
                        placeholder="Nhập tên lỗi khác...">
                </div>
            </div>
<!-- THÊM INPUT THỜI GIAN VÀO TRANG PHỤ -->
<div class="row mb-3" id="time_input_div_<?php echo $ctdd['maCTDon']; ?>" style="display:none;">
    <div class="col-12">
        <label class="form-label fw-bold">Thời gian sửa chữa (phút):</label>
        <input type="number" class="form-control" id="job_time_<?php echo $ctdd['maCTDon']; ?>"
            placeholder="Nhập thời gian dự kiến" min="0.1" step="0.5" required>
        <div class="form-text">
            <i class="fas fa-clock me-1"></i>Thời gian ước tính để hoàn thành(DVT phút)
        </div>
    </div>
</div>

            

            <!-- CHI PHÍ -->
            <div class="row mb-3">
                <div class="col-12">
                    <label class="form-label fw-bold">Chi phí thực tế (VND):</label>
                    <input type="number" class="form-control" id="job_cost_<?php echo $ctdd['maCTDon']; ?>"
                        placeholder="Nhập chi phí chính xác" min="1">
                    <div class="form-text" id="cost_hint_<?php echo $ctdd['maCTDon']; ?>">
                        <i class="fas fa-info-circle me-1"></i>Chọn lỗi để xem khoảng giá tham khảo
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="button" class="btn btn-success w-100" 
                        onclick="addRepairJob('<?php echo $ctdd['maCTDon']; ?>')">
                        <i class="fas fa-plus-circle me-2"></i>Thêm vào danh sách
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- DANH SÁCH CÔNG VIỆC -->
    <div class="card border-info mb-4 shadow-sm">
        <div class="card-header bg-info text-white py-2">
            <h6 class="mb-0">
                <i class="fas fa-list-check me-2"></i>Danh Sách Công Việc Đã Chọn
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
                    <tbody id="repair_jobs_table_<?php echo $ctdd['maCTDon']; ?>">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox me-2"></i>Chưa có công việc nào
                            </td>
                        </tr>
                    </tbody>
                    <tfoot id="repair_jobs_footer_<?php echo $ctdd['maCTDon']; ?>" style="display:none;">
                        <tr class="table-success">
                            <td colspan="3" class="text-end fw-bold">TỔNG CỘNG:</td>
                            <td class="text-center fw-bold" id="total_time_table_<?php echo $ctdd['maCTDon']; ?>">0 phút</td>
                            <td class="text-end fw-bold" id="total_table_<?php echo $ctdd['maCTDon']; ?>">0 VND</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- TỔNG KẾT -->
    <div class="row mb-4" style="display: none;">
    <div class="col-md-6 mb-3">
        <div class="card border-info h-100">
            <div class="card-body text-center">
                <h5 class="card-title text-info">
                    <i class="fas fa-clock me-2"></i>Tổng thời gian
                </h5>
                <h2 class="text-info fw-bold mb-0" id="total_time_display_<?php echo $ctdd['maCTDon']; ?>">0 phút</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card border-primary h-100">
            <div class="card-body text-center">
                <h5 class="card-title text-primary">
                    <i class="fas fa-money-bill-wave me-2"></i>Tổng chi phí
                </h5>
                <h2 class="text-primary fw-bold mb-0" id="total_display_<?php echo $ctdd['maCTDon']; ?>">0 VND</h2>
            </div>
        </div>
    </div>
</div>

    <input type="hidden" name="estimated_cost" id="total_estimated_cost_<?php echo $ctdd['maCTDon']; ?>" value="0">
    <input type="hidden" name="estimated_time" id="total_estimated_time_<?php echo $ctdd['maCTDon']; ?>" value="0">

    <!-- QUYẾT ĐỊNH KHÁCH HÀNG -->
    <div class="card border-warning mb-4">
        <div class="card-header bg-warning text-dark py-2">
            <h6 class="mb-0">
                <i class="fas fa-clipboard-check me-2"></i>Quyết định của khách hàng
            </h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="form-check form-check-inline me-4">
                        <input class="form-check-input" type="radio" name="decision_<?php echo $ctdd['maCTDon']; ?>" 
                            id="agree_<?php echo $ctdd['maCTDon']; ?>" value="1" required>
                        <label class="form-check-label fw-bold text-success" for="agree_<?php echo $ctdd['maCTDon']; ?>">
                            <i class="fas fa-check-circle me-1"></i>Đồng ý sửa chữa
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="decision_<?php echo $ctdd['maCTDon']; ?>" 
                            id="disagree_<?php echo $ctdd['maCTDon']; ?>" value="2">
                        <label class="form-check-label fw-bold text-danger" for="disagree_<?php echo $ctdd['maCTDon']; ?>">
                            <i class="fas fa-times-circle me-1"></i>Không đồng ý
                        </label>
                    </div>
                </div>
            </div>

            <div class="row" id="reasonSection_<?php echo $ctdd['maCTDon']; ?>" style="display:none;">
                <div class="col-12">
                    <label class="form-label fw-bold">Lý do không đồng ý:</label>
                    <textarea class="form-control" id="reason_<?php echo $ctdd['maCTDon']; ?>" name="reason" rows="2" 
                        placeholder="Ghi rõ lý do khách hàng không đồng ý sửa chữa..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- NÚT LƯU -->
    <div class="row">
        <div class="col-12">
            <div class="d-grid">
                <button type="button" class="btn btn-success btn-lg py-3" 
                    onclick="saveDiagnosis('<?php echo $ctdd['maCTDon']; ?>')">
                    <i class="fas fa-save me-2"></i>Lưu Chẩn Đoán & Báo Giá
                </button>
            </div>
        </div>
    </div>
</form>

<script>
// Xử lý hiển thị/ẩn lý do khi chọn không đồng ý
document.addEventListener('DOMContentLoaded', function() {
    const disagreeRadio = document.getElementById('disagree_<?php echo $ctdd['maCTDon']; ?>');
    const agreeRadio = document.getElementById('agree_<?php echo $ctdd['maCTDon']; ?>');
    const reasonSection = document.getElementById('reasonSection_<?php echo $ctdd['maCTDon']; ?>');

    if (disagreeRadio && reasonSection) {
        disagreeRadio.addEventListener('change', function() {
            reasonSection.style.display = this.checked ? 'block' : 'none';
        });

        agreeRadio.addEventListener('change', function() {
            reasonSection.style.display = 'none';
        });
    }
});
</script>