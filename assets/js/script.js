// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenu = document.querySelector('.mobile-menu');
    const navbar = document.querySelector('.navbar');
    
    if (mobileMenu) {
        mobileMenu.addEventListener('click', function() {
            navbar.style.display = navbar.style.display === 'block' ? 'none' : 'block';
        });
    }
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const password = form.querySelector('input[name="password"]');
            const confirmPassword = form.querySelector('input[name="confirm_password"]');
            
            if (confirmPassword && password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
                confirmPassword.focus();
            }
        });
    });
});



window.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.auto-hide');
    alerts.forEach((alert, index) => {
        setTimeout(() => {
            alert.style.opacity = 0;
            alert.style.transform = 'translateX(100%)';
        }, 3000 + index * 500); // có thể thêm offset nhỏ cho từng thông báo
    });
});





