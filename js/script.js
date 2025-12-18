document.addEventListener('DOMContentLoaded', function () {

    // Auto hide alert setelah 5 detik
    // var alerts = document.querySelectorAll('.alert');
    // alerts.forEach(function (alert) {
    //     setTimeout(function () {
    //         alert.style.display = 'none';
    //     }, 5000);
    // });

    // Highlight menu aktif
    var currentUrl = window.location.href;
    var menuLinks = document.querySelectorAll('.menu-link');
    var submenuLinks = document.querySelectorAll('.submenu-link');

    // Check regular menu links
    menuLinks.forEach(function (link) {
        var href = link.getAttribute('href');
        if (href && currentUrl.indexOf(href) > -1 && href !== 'index.php') {
            link.classList.add('active');
        }
    });

    // Submit search dengan Enter
    var searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
    }

    // Search tanpa enter, menggunakan debounce
    var searchInputRT = document.getElementById('searchInput');

    if (searchInputRT) {
        var searchTimeout;

        searchInputRT.addEventListener('input', function () {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(function () {
                var form = searchInputRT.closest('form');
                if (form) {
                    form.submit();
                }
            }, 500);
        });

        searchInputRT.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                this.closest('form').submit();
            }
        });
    }

    // auto submit jadi tanpa enter
    var autoSubmitSelects = document.querySelectorAll('.auto-submit');
    autoSubmitSelects.forEach(function (select) {
        select.addEventListener('change', function () {
            var form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });

    // Filter selects
    var filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(function (select) {
        // Skip if already has auto-submit class
        if (!select.classList.contains('auto-submit')) {
            select.addEventListener('change', function () {
                var form = this.closest('form');
                if (form) {
                    form.submit();
                }
            });
        }
    });

    // Sortable
    var sortableHeaders = document.querySelectorAll('.sortable');

    sortableHeaders.forEach(function (header) {
        header.addEventListener('click', function () {
            var sortColumn = this.getAttribute('data-sort');
            var currentUrl = new URL(window.location.href);
            var currentSort = currentUrl.searchParams.get('sort');
            var currentOrder = currentUrl.searchParams.get('order');

            // Toggle order jika column yang sama diklik
            if (currentSort === sortColumn) {
                var newOrder = currentOrder === 'ASC' ? 'DESC' : 'ASC';
                currentUrl.searchParams.set('order', newOrder);
            } else {
                // Set column baru, default DESC
                currentUrl.searchParams.set('sort', sortColumn);
                currentUrl.searchParams.set('order', 'DESC');
            }

            // Redirect ke URL baru
            window.location.href = currentUrl.toString();
        });
    });

    // Highlight active sort column
    var currentUrl = new URL(window.location.href);
    var activeSort = currentUrl.searchParams.get('sort');
    var activeOrder = currentUrl.searchParams.get('order');

    if (activeSort) {
        sortableHeaders.forEach(function (header) {
            if (header.getAttribute('data-sort') === activeSort) {
                header.classList.add('active');
                var sortIcon = header.querySelector('.sort-icon');
                if (sortIcon) {
                    sortIcon.textContent = activeOrder === 'ASC' ? '↑' : '↓';
                }
            }
        });
    }


    // Sidebar toggle
    var sidebarToggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebar');
    var sidebarOverlay = document.getElementById('sidebarOverlay');
    var mainWrapper = document.querySelector('.main-wrapper');

    if (sidebarToggle && sidebar && sidebarOverlay && mainWrapper) {

        sidebarToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            console.log('Toggle clicked!');

            var isMobile = window.innerWidth <= 768;

            if (isMobile) {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
                sidebarToggle.classList.toggle('active');
                console.log('Mobile mode - sidebar active:', sidebar.classList.contains('active'));
            } else {
                sidebar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('expanded');
                sidebarToggle.classList.toggle('active');
                console.log('Desktop mode - sidebar collapsed:', sidebar.classList.contains('collapsed'));
                console.log('Main wrapper expanded:', mainWrapper.classList.contains('expanded'));
            }
        });

        sidebarOverlay.addEventListener('click', function () {
            console.log('Overlay clicked!');
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            sidebarToggle.classList.remove('active');
        });

        window.addEventListener('resize', function () {
            var isMobile = window.innerWidth <= 768;

            if (isMobile) {
                sidebar.classList.remove('collapsed');
                mainWrapper.classList.remove('expanded');
            } else {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            }
        });
    } else {
        console.error('Element not found!');
        console.error('sidebarToggle:', sidebarToggle);
        console.error('sidebar:', sidebar);
        console.error('sidebarOverlay:', sidebarOverlay);
        console.error('mainWrapper:', mainWrapper);
    }

    // Toggle for dropdown sub menu
    var menuParents = document.querySelectorAll('.menu-parent');

    menuParents.forEach(function (parent) {
        parent.addEventListener('click', function (e) {
            e.preventDefault();

            var submenuId = this.getAttribute('data-submenu');
            var submenu = document.getElementById(submenuId);

            // Toggle current submenu
            this.classList.toggle('open');
            submenu.classList.toggle('open');

            // Close other submenus
            menuParents.forEach(function (otherParent) {
                if (otherParent !== parent) {
                    otherParent.classList.remove('open');
                    var otherSubmenuId = otherParent.getAttribute('data-submenu');
                    var otherSubmenu = document.getElementById(otherSubmenuId);
                    if (otherSubmenu) {
                        otherSubmenu.classList.remove('open');
                    }
                }
            });
        });
    });

    // Table with scrollbar
    var tableContainers = document.querySelectorAll('.table-container');

    tableContainers.forEach(function (container) {
        // Check if table is scrollable
        function checkScroll() {
            if (container.scrollWidth > container.clientWidth) {
                container.setAttribute('data-scrollable', 'true');

                // Add visual indicator on first load
                if (!container.hasAttribute('data-scroll-initialized')) {
                    container.style.boxShadow = 'inset -10px 0 10px -10px rgba(0,0,0,0.2)';
                    container.setAttribute('data-scroll-initialized', 'true');

                    setTimeout(function () {
                        container.style.boxShadow = '';
                    }, 2000);
                }
            }
        }

        checkScroll();
        window.addEventListener('resize', checkScroll);

        // Update shadow on scroll
        container.addEventListener('scroll', function () {
            if (this.scrollLeft > 0) {
                this.style.boxShadow = 'inset 10px 0 10px -10px rgba(0,0,0,0.2), inset -10px 0 10px -10px rgba(0,0,0,0.2)';
            } else {
                this.style.boxShadow = 'inset -10px 0 10px -10px rgba(0,0,0,0.2)';
            }

            if (this.scrollLeft + this.clientWidth >= this.scrollWidth - 1) {
                this.style.boxShadow = 'inset 10px 0 10px -10px rgba(0,0,0,0.2)';
            }
        });
    });

    // Scroll to top button
    var mainContent = document.querySelector('.main-content');

    if (mainContent) {
        // Create scroll to top button
        var scrollTopBtn = document.createElement('button');
        scrollTopBtn.innerHTML = '↑';
        scrollTopBtn.className = 'scroll-to-top';
        scrollTopBtn.style.cssText = 'position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #1d3557 0%, #457b9d 100%); color: white; border: none; font-size: 24px; cursor: pointer; display: none; box-shadow: 0 4px 15px rgba(29, 53, 87, 0.4); z-index: 1000; transition: all 0.3s ease;';

        document.body.appendChild(scrollTopBtn);

        // Show/hide button on scroll
        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 300) {
                scrollTopBtn.style.display = 'block';
            } else {
                scrollTopBtn.style.display = 'none';
            }
        });

        // Scroll to top on click
        scrollTopBtn.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Hover effect
        scrollTopBtn.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 6px 20px rgba(29, 53, 87, 0.5)';
        });

        scrollTopBtn.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 15px rgba(29, 53, 87, 0.4)';
        });
    }

    // Close sidebar pakai overlay di mobile
    if (window.innerWidth <= 768) {
        var allMenuLinks = document.querySelectorAll('.menu-link:not(.menu-parent), .submenu-link');

        allMenuLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        });
    }

    // Form validation
    var forms = document.querySelectorAll('form');

    forms.forEach(function (form) {
        var inputs = form.querySelectorAll('.form-input[required], .form-select[required]');

        inputs.forEach(function (input) {
            input.addEventListener('invalid', function (e) {
                e.preventDefault();
                this.classList.add('error');

                // Remove error class on input
                this.addEventListener('input', function () {
                    this.classList.remove('error');
                }, { once: true });
            });
        });
    });

    // Auto calculate total biaya rental
    var tanggalSewa = document.getElementById('tanggal_sewa');
    var tanggalKembali = document.getElementById('tanggal_kembali');
    var kendaraanSelect = document.getElementById('id_kendaraan');
    var totalBiayaInput = document.getElementById('total_biaya');

    if (tanggalSewa && tanggalKembali && kendaraanSelect && totalBiayaInput) {

        function calculateTotal() {
            var sewa = tanggalSewa.value;
            var kembali = tanggalKembali.value;
            var kendaraanVal = kendaraanSelect.value;

            // Validasi semua field terisi
            if (!sewa || !kembali || !kendaraanVal) {
                totalBiayaInput.value = 0;
                return;
            }

            // Hitung durasi hari
            var dateStart = new Date(sewa);
            var dateEnd = new Date(kembali);
            var diffTime = dateEnd - dateStart;
            var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            // Validasi tanggal kembali >= tanggal sewa
            if (diffDays < 0) {
                alert('Tanggal kembali harus lebih besar atau sama dengan tanggal sewa!');
                totalBiayaInput.value = 0;
                return;
            }

            // Minimal 1 hari
            var durasi = diffDays === 0 ? 1 : diffDays;

            // Ambil tarif dari option yang dipilih
            var selectedKendaraan = kendaraanSelect.options[kendaraanSelect.selectedIndex];

            var tarifKendaraan = parseInt(selectedKendaraan.getAttribute('data-tarif')) || 0;

            // Calculate total
            var total = tarifKendaraan * durasi;
            totalBiayaInput.value = total;
        }

        // Event listeners
        tanggalSewa.addEventListener('change', calculateTotal);
        tanggalKembali.addEventListener('change', calculateTotal);
        kendaraanSelect.addEventListener('change', calculateTotal);
    }

    // Auto calculate total denda ketika pengembalian dilakukan
    var idRental = document.getElementById('id_rental');
    var tanggalPengembalian = document.getElementById('tanggal_pengembalian');
    var dendaInput = document.getElementById('denda');

    if (idRental && tanggalPengembalian && dendaInput) {

        function calculateDenda() {
            var rentalVal = idRental.value;
            var tglPengembalian = tanggalPengembalian.value;

            // Validasi semua field terisi
            if (!rentalVal || !tglPengembalian) {
                dendaInput.value = 0;
                return;
            }

            // Ambil data dari option yang dipilih
            var selectedRental = idRental.options[idRental.selectedIndex];
            var tanggalKembali = selectedRental.getAttribute('data-tanggal-kembali');
            var tarifHarian = parseInt(selectedRental.getAttribute('data-tarif')) || 0;

            console.log('Rental dipilih:', rentalVal);
            console.log('Tanggal Kembali:', tanggalKembali);
            console.log('Tanggal Pengembalian:', tglPengembalian);
            console.log('Tarif Harian:', tarifHarian);

            if (!tanggalKembali || tarifHarian === 0) {
                console.log('Data tidak lengkap!');
                dendaInput.value = 0;
                return;
            }

            // Hitung selisih hari
            var dateKembali = new Date(tanggalKembali);
            var datePengembalian = new Date(tglPengembalian);
            var diffTime = datePengembalian - dateKembali;
            var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            console.log('Selisih Hari:', diffDays);

            // Jika terlambat, hitung denda (10% dari tarif harian per hari)
            if (diffDays > 0) {
                var denda = diffDays * tarifHarian * 0.1;
                dendaInput.value = Math.round(denda);
                console.log('Denda:', Math.round(denda));
            } else {
                dendaInput.value = 0;
                console.log('Tidak ada denda (tepat waktu)');
            }
        }

        // Event listeners
        idRental.addEventListener('change', calculateDenda);
        tanggalPengembalian.addEventListener('change', calculateDenda);

        // Trigger calculate saat page load jika form edit
        calculateDenda();
    } else {
        console.log('Element tidak ditemukan:');
        console.log('idRental:', idRental);
        console.log('tanggalPengembalian:', tanggalPengembalian);
        console.log('dendaInput:', dendaInput);
    }

    // Add error style dynamically
    var style = document.createElement('style');
    style.textContent = '.form-input.error, .form-select.error { border-color: #ef4444 !important; animation: shake 0.3s ease; } @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }';
    document.head.appendChild(style);

    // Auto submit form saat filter berubah
    document.querySelectorAll('.auto-submit').forEach(select => {
        select.addEventListener('change', function () {
            document.getElementById('filterForm').submit();
        });
    });
});

// Konfirmasi hapus
function confirmDelete() {
    return confirm('Apakah Anda yakin ingin menghapus data ini?');
}