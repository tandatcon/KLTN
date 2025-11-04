// custom-modal.js - Hàm thông báo dùng chung cho toàn bộ website
class CustomModal {
    constructor() {
        // KHÔNG gọi init() trong constructor nữa
        this.modal = null;
        this.modalTitle = null;
        this.modalMessage = null;
        this.confirmBtn = null;
        this.cancelBtn = null;
        this.confirmCallback = null;
        this.cancelCallback = null;
    }
    
    init() {
        // Chỉ tạo modal nếu chưa tồn tại
        if (!document.getElementById('customModal')) {
            this.createModal();
        }
        
        this.modal = document.getElementById('customModal');
        this.modalTitle = document.getElementById('modalTitle');
        this.modalMessage = document.getElementById('modalMessage');
        this.confirmBtn = document.getElementById('modalConfirmBtn');
        this.cancelBtn = document.getElementById('modalCancelBtn');
        
        this.setupEventListeners();
    }
    
    createModal() {
        const modalHTML = `
            <div id="customModal" class="custom-modal">
                <div class="custom-modal-content">
                    <div class="custom-modal-header">
                        <h3 id="modalTitle">Thông báo</h3>
                    </div>
                    <div class="custom-modal-body">
                        <div id="modalMessage"></div>
                    </div>
                    <div class="custom-modal-footer">
                        <button type="button" class="custom-modal-btn confirm" id="modalConfirmBtn">Xác nhận</button>
                        <button type="button" class="custom-modal-btn cancel" id="modalCancelBtn">Hủy</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }
    
    setupEventListeners() {
        if (!this.modal) return;
        
        // Đóng modal khi click bên ngoài
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.hide();
            }
        });
        
        // Đóng modal khi click cancel
        if (this.cancelBtn) {
            this.cancelBtn.addEventListener('click', () => {
                this.hide();
                if (this.cancelCallback) {
                    this.cancelCallback();
                }
            });
        }
        
        // Ngăn sự kiện click trên content
        const modalContent = this.modal.querySelector('.custom-modal-content');
        if (modalContent) {
            modalContent.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }
        
        // Xử lý phím ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal && this.modal.style.display === 'block') {
                this.hide();
                if (this.cancelCallback) {
                    this.cancelCallback();
                }
            }
        });
    }
    
    show(title, message, confirmCallback = null, cancelCallback = null) {
        // Đảm bảo modal đã được khởi tạo
        if (!this.modal) {
            this.init();
        }
        
        if (this.modalTitle) this.modalTitle.textContent = title;
        if (this.modalMessage) this.modalMessage.innerHTML = message;
        if (this.modal) this.modal.style.display = 'block';
        
        this.confirmCallback = confirmCallback;
        this.cancelCallback = cancelCallback;
        
        // Xử lý nút xác nhận
        if (this.confirmBtn) {
            this.confirmBtn.onclick = () => {
                if (this.confirmCallback) {
                    this.confirmCallback();
                }
                this.hide();
            };
        }
        
        // Hiển thị/ẩn nút hủy dựa trên callback
        if (this.cancelBtn) {
            if (cancelCallback) {
                this.cancelBtn.style.display = 'block';
            } else {
                this.cancelBtn.style.display = 'none';
            }
        }
        
        // Thêm hiệu ứng và khóa scroll body
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
    }
    
    hide() {
        if (this.modal) {
            this.modal.style.display = 'none';
        }
        document.body.style.overflow = 'auto';
        document.documentElement.style.overflow = 'auto';
        
        // Reset callbacks
        this.confirmCallback = null;
        this.cancelCallback = null;
    }
}

// KHÔNG khởi tạo ngay lập tức - sẽ khởi tạo khi DOM ready
let customModal = null;

// Hàm khởi tạo modal khi DOM ready
function initCustomModal() {
    if (!customModal) {
        customModal = new CustomModal();
        customModal.init();
    }
    return customModal;
}

// Hàm tiện ích để gọi modal từ bất kỳ đâu
function showCustomAlert(title, message, confirmCallback = null, cancelCallback = null) {
    const modal = initCustomModal();
    modal.show(title, message, confirmCallback, cancelCallback);
}

// Hàm hiển thị thông báo đơn giản (chỉ thông báo, không có nút hủy)
function showAlert(message, title = 'Thông báo') {
    const modal = initCustomModal();
    modal.show(title, message, () => {
        // Callback mặc định khi click xác nhận
    });
}

// Hàm hiển thị xác nhận (có cả xác nhận và hủy)
function showConfirm(message, title = 'Xác nhận', confirmCallback = null, cancelCallback = null) {
    const modal = initCustomModal();
    modal.show(title, message, confirmCallback, cancelCallback);
}

// Khởi tạo khi DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initCustomModal();
});