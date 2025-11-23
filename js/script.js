document.addEventListener('DOMContentLoaded', function() {
    
    // Auto hide alert setelah 5 detik
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.display = 'none';
        }, 5000);
    });

    // Highlight menu aktif
    var currentUrl = window.location.href;
    var menuLinks = document.querySelectorAll('.menu-link');
    menuLinks.forEach(function(link) {
        if (currentUrl.indexOf(link.getAttribute('href')) > -1) {
            link.classList.add('active');
        }
    });

    // Submit search dengan Enter
    var searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
    }
});

// Konfirmasi hapus
function confirmDelete() {
    return confirm('Apakah Anda yakin ingin menghapus data ini?');
}
