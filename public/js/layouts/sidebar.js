document.addEventListener('DOMContentLoaded', function() {
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
        if (currentPath.includes('/home') || currentPath === '/') {
            document.querySelector('.nav-list li a .bx-home').parentElement.classList.add('active');
        } else if (currentPath.includes('/personal')) {
            document.querySelector('.nav-list li a .bx-user').parentElement.classList.add('active');
        } else if (currentPath.includes('/completed')) {
            document.querySelector('.nav-list li a .bx-checkbox-checked').parentElement.classList.add('active');
        } else if (currentPath.includes('/work')) {
            document.querySelector('.nav-list li a .bx-briefcase-alt').parentElement.classList.add('active');
        }
    }

    // Jalankan fungsi saat halaman dimuat
    setActiveMenu();
});
