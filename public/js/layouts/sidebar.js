document.addEventListener('DOMContentLoaded', function () {
    // Mendapatkan path URL saat ini (misal: /home, /personal, dll)
    const currentPath = window.location.pathname;

    // Mendapatkan semua menu item
    const menuItems = document.querySelectorAll('.nav-list li a');

    // Fungsi untuk mengaktifkan menu berdasarkan path
    function setActiveMenu() {
        // Hapus kelas aktif dari semua menu terlebih dahulu
        menuItems.forEach(item => {
            item.classList.remove('active');
        });

        // Tentukan menu mana yang harus aktif berdasarkan path
        if (currentPath.includes('/dashboard') || currentPath === '/') {
            document.querySelector('.nav-list li a .bx-home').parentElement.classList.add('active');
        } else if (currentPath.includes('/board')) {
            document.querySelector('.nav-list li a .bx-task').parentElement.classList.add('active');
        } else if (currentPath.includes('/completed')) {
            document.querySelector('.nav-list li a .bx-checkbox-checked').parentElement.classList.add('active');
        } else if (currentPath.includes('/calendar')) {
            document.querySelector('.nav-list li a .bx-calendar').parentElement.classList.add('active');
        } else if (currentPath.includes('/event')) {
            document.querySelector('.nav-list li a .bx-calendar-event').parentElement.classList.add('active');
        } else if (currentPath.includes('/notes')) {
            document.querySelector('.nav-list li a .bx-notepad').parentElement.classList.add('active');
        } else if (currentPath.includes('/weekly-schedule')) {
            document.querySelector('.nav-list li a .bx-calendar-week').parentElement.classList.add('active');
        }
    }

    // Jalankan fungsi saat halaman dimuat
    setActiveMenu();
});

document.addEventListener('DOMContentLoaded', function () {
    // Mendapatkan elemen search bar
    const searchInput = document.querySelector('.sidebar .bx-search').nextElementSibling || document.getElementById('searchInput');
    const searchContainer = document.querySelector('.sidebar li:first-child');

    let isClickingSearchResult = false; // Variabel untuk mendeteksi klik pada hasil pencarian

    // Data yang dapat dicari (menu-menu yang tersedia)
    const searchableItems = [{
            name: 'Dashboard',
            url: '/dashboard',
            keywords: ['beranda', 'utama', 'home', 'dasbor']
        },
        {
            name: 'Board',
            url: '/board',
            keywords: ['tugas', 'task', 'papan', 'to-do']
        },
        {
            name: 'Event',
            url: '/event',
            keywords: ['acara', 'kegiatan', 'jadwal acara', 'events']
        },
        {
            name: 'Notes',
            url: '/notes',
            keywords: ['catatan', 'memo', 'note', 'tulisan']
        },
        {
            name: 'Weekly Schedule',
            url: '/weekly-schedule',
            keywords: ['jadwal mingguan', 'schedule', 'agenda', 'week planner']
        }
    ];

    // Elemen untuk menampilkan hasil pencarian - posisi di sebelah kanan
    let searchResults = document.createElement('div');
    searchResults.className = 'search-results';
    searchResults.style.display = 'none';
    searchResults.style.position = 'absolute';
    searchResults.style.left = '100%'; // Posisikan di kanan sidebar
    searchResults.style.top = '0';
    searchResults.style.width = '250px';
    searchResults.style.maxHeight = '300px';
    searchResults.style.overflowY = 'auto';
    searchResults.style.backgroundColor = '#fff';
    searchResults.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';
    searchResults.style.borderRadius = '5px';
    searchResults.style.zIndex = '1000';
    searchResults.style.marginLeft = '10px'; // Memberi jarak dengan sidebar

    // Styling tambahan untuk scrollbar
    searchResults.style.cssText += `
        scrollbar-width: thin;
        scrollbar-color: #888 #f1f1f1;
    `;

    // Menambahkan elemen hasil pencarian ke container pencarian
    searchContainer.style.position = 'relative'; // Memastikan container relative untuk positioning
    searchContainer.appendChild(searchResults);

    // Event listener untuk mendeteksi mousedown pada hasil pencarian
    searchResults.addEventListener('mousedown', function () {
        isClickingSearchResult = true;
    });

    // Event listener untuk input pencarian
    searchInput.addEventListener('focus', function () {
        // Menampilkan ikon loading atau hasil saat fokus pada input
        searchResults.style.display = 'block';
        performSearch(this.value);
    });

    searchInput.addEventListener('blur', function () {
        // Menyembunyikan hasil setelah beberapa saat untuk memungkinkan klik pada hasil
        setTimeout(() => {
            if (!isClickingSearchResult) {
                searchResults.style.display = 'none';
            }
            isClickingSearchResult = false;
        }, 200);
    });

    searchInput.addEventListener('input', function () {
        performSearch(this.value);
    });

    // Fungsi untuk melakukan pencarian
    function performSearch(query) {
        if (!query.trim()) {
            searchResults.innerHTML = '<div class="no-results" style="padding: 10px; color: #666;">Ketik untuk mencari...</div>';
            return;
        }

        query = query.toLowerCase();
        const filteredItems = searchableItems.filter(item => {
            return item.name.toLowerCase().includes(query) ||
                item.keywords.some(keyword => keyword.toLowerCase().includes(query));
        });

        renderSearchResults(filteredItems, query);
    }

    // Fungsi untuk merender hasil pencarian
    function renderSearchResults(items, query) {
        searchResults.innerHTML = '';

        if (items.length === 0) {
            searchResults.innerHTML = '<div class="no-results" style="padding: 10px; color: #666;">Tidak ada hasil yang ditemukan</div>';
            return;
        }

        items.forEach(item => {
            const resultItem = document.createElement('a');
            resultItem.href = item.url;
            resultItem.className = 'search-result-item';
            resultItem.style.display = 'block';
            resultItem.style.padding = '10px 15px';
            resultItem.style.textDecoration = 'none';
            resultItem.style.color = '#333';
            resultItem.style.borderBottom = '1px solid #eee';
            resultItem.style.transition = 'background-color 0.2s';
            resultItem.style.cursor = 'pointer'; // Menambahkan cursor pointer

            resultItem.innerHTML = `
                <div style="font-weight: bold;">${item.name}</div>
                <div style="font-size: 0.8em; color: #666;">${item.keywords.join(', ')}</div>
            `;

            resultItem.addEventListener('mouseenter', function () {
                this.style.backgroundColor = '#f5f5f5';
            });

            resultItem.addEventListener('mouseleave', function () {
                this.style.backgroundColor = 'transparent';
            });

            // Tambahkan event listener click yang eksplisit
            resultItem.addEventListener('click', function (e) {
                e.preventDefault(); // Mencegah perilaku default link
                window.location.href = this.href; // Navigasi secara manual
            });

            searchResults.appendChild(resultItem);
        });
    }

    // Event listener untuk ikon pencarian
    document.querySelector('.sidebar .bx-search').addEventListener('click', function () {
        searchInput.focus();
    });
});
