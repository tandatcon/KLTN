<?php
if (!isset($ctdd) || !isset($chiTietGia)) return;
?>

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
                <option value="custom">-- Lỗi phát sinh khác --</option>
            </select>
        </div>

        <!-- INPUT TÊN LỖI KHÁC -->
        <div class="mb-3" id="custom_job_name_phatsinh_<?php echo $ctdd['maCTDon']; ?>"
            style="display: none;">
            <label class="form-label fw-semibold">Nhập tên lỗi phát sinh khác:</label>
            <input type="text" class="form-control"
                id="custom_job_input_phatsinh_<?php echo $ctdd['maCTDon']; ?>"
                placeholder="Nhập tên lỗi khác...">
        </div>

        <!-- CHI PHÍ SỬA CHỮA -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Chi phí sửa chữa (VND):</label>
            <input type="number" class="form-control"
                id="job_cost_phatsinh_<?php echo $ctdd['maCTDon']; ?>"
                placeholder="Nhập chi phí...">
            <div class="form-text" id="cost_hint_phatsinh_<?php echo $ctdd['maCTDon']; ?>">
                Nhập chi phí sửa chữa
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
                        <th width="5%">STT</th>
                        <th width="50%">Công việc</th>
                        <th width="20%">Khoảng giá</th>
                        <th width="15%">Chi phí (VND)</th>
                        <th width="10%">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="repair_jobs_phatsinh_table_<?php echo $ctdd['maCTDon']; ?>">
                    <tr>
                        <td colspan="5" class="text-center py-3 text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            Chưa có công việc nào được thêm
                        </td>
                    </tr>
                </tbody>
                <tfoot id="repair_jobs_phatsinh_footer_<?php echo $ctdd['maCTDon']; ?>"
                    style="display: none;">
                    <tr class="table-secondary">
                        <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                        <td class="text-end fw-bold"
                            id="total_phatsinh_table_<?php echo $ctdd['maCTDon']; ?>">0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- NÚT LƯU CÔNG VIỆC PHÁT SINH -->
<div class="text-center mb-4">
    <button type="button" class="btn btn-success btn-lg"
        onclick="saveAdditionalJobs('<?php echo $ctdd['maCTDon']; ?>')">
        <i class="fas fa-save me-2"></i>Lưu các công việc phát sinh
    </button>
</div>

<script>
// Khởi tạo mảng công việc phát sinh cho thiết bị này
if (typeof danhSachCongViecPhatSinh === 'undefined') {
    danhSachCongViecPhatSinh = {};
}
danhSachCongViecPhatSinh['<?php echo $ctdd['maCTDon']; ?>'] = [];

// Gắn sự kiện submit form
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('save_additional_jobs_form_<?php echo $ctdd['maCTDon']; ?>');
    if (form) {
        form.addEventListener('submit', function(e) {
            const inputJSON = document.getElementById('danh_sach_cong_viec_phat_sinh_json_<?php echo $ctdd['maCTDon']; ?>');
            if (inputJSON) {
                const danhSach = danhSachCongViecPhatSinh['<?php echo $ctdd['maCTDon']; ?>'] || [];
                inputJSON.value = JSON.stringify(danhSach);
            }
        });
    }

    // Hiển thị danh sách ban đầu
    hienThiDanhSachCongViecPhatSinh('<?php echo $ctdd['maCTDon']; ?>');
});
</script>