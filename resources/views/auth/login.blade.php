<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TaskMate</title>
    {{-- My CSS --}}
    <link rel="stylesheet" href="{{ asset('../css/auth/login.css') }}">

    {{-- My Icon --}}
    <link rel="icon" type="image/png" href="{{ asset('../images/logo.png') }}">

    {{-- Bx Icon CSS CDN --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="section">
        <div class="left-content">
            <div class="header">
                <h1>Sign In To <span style="color: black">Task</span>Mate</h1>
                <p>use your email for Sign In!</p>
            </div>
            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="input-group">
                    <i class='bx bx-envelope'></i>
                    <input type="email" placeholder="Email" name="email" required>
                </div>
                <div class="input-group">
                    <i class='bx bx-lock-alt'></i>
                    <input type="password" placeholder="Password" name="password" id="password" required>
                    <i class='bx bx-hide' id="togglePassword" style="display: none;"></i>
                </div>
                <p>Don't have an account? <a href="{{ Route('register') }}">Sign Up</a></p>
                <button type="submit">Sign In</button>
            </form>
        </div>
        <div class="right-content">
            <div class="overlay"></div>
            <img src="{{ '../images/logo.png' }}" alt="">
            <div class="description">
                <h1>Welcome back!</h1>
                <p>Login to TaskMate and keep your <br> productivity on track. Manage your tasks <br> easily and stay
                    organized every day!</p>
            </div>
        </div>
    </div>
</body>

{{-- Bx Icon JS CDN --}}
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    // Sembunyikan ikon mata saat halaman dimuat (sebenarnya sudah disembunyikan di HTML)
    togglePassword.style.display = 'none';

    // Fungsi untuk memeriksa apakah password kosong
    function checkPasswordValue() {
        if (password.value.length > 0) {
            togglePassword.style.display = 'block'; // Tampilkan ikon mata
        } else {
            togglePassword.style.display = 'none'; // Sembunyikan ikon mata
        }
    }

    // Dengarkan input pada field password
    password.addEventListener('input', checkPasswordValue);

    // Toggle password visibility saat ikon diklik
    togglePassword.addEventListener('click', function() {
        // Toggle tipe input antara password dan text
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Toggle antara ikon mata tertutup dan mata terbuka
        if (type === 'text') {
            this.classList.remove('bx-hide');
            this.classList.add('bx-show');
        } else {
            this.classList.remove('bx-show');
            this.classList.add('bx-hide');
        }
    });
</script>

</html>
