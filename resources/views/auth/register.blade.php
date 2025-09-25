<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TaskMate</title>
    {{-- My CSS --}}
    <link rel="stylesheet" href="{{ asset('../css/auth/register.css') }}">

    {{-- My Icon --}}
    <link rel="icon" type="image/png" href="{{ asset('../images/logo.png') }}">

    {{-- Bx Icon CSS CDN --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
    <div class="section">
        <div class="left-content">
            <div class="overlay"></div>
            <img src="{{ asset('../images/logo.png') }}" alt="Logo">
            <div class="description">
                <h1>Let’s get things done together!</h1>
                <p>Join TaskMate and start managing your <br> tasks effortlessly. Create lists, set priorities, <br> and
                    achieve more!</p>
            </div>
        </div>
        <div class="right-content">
            <div class="header">
                <h1>Create Account</h1>
                <p>Use your email for registration!</p>
            </div>

            {{-- Menampilkan error validasi --}}
            @if ($errors->any())
                <div class="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="input-group">
                    <i class='bx bx-user'></i>
                    <input type="text" placeholder="Name" name="name" value="{{ old('name') }}" required>
                </div>
                @if ($errors->has('name'))
                    <span class="error">{{ $errors->first('name') }}</span>
                @endif

                <div class="input-group">
                    <i class='bx bx-envelope'></i>
                    <input type="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
                </div>
                @if ($errors->has('email'))
                    <span class="error">{{ $errors->first('email') }}</span>
                @endif

                <div class="input-group">
                    <i class='bx bx-lock-alt'></i>
                    <input type="password" placeholder="Password" name="password" id="password"required>
                    <i class='bx bx-hide' id="togglePassword" style="display: none"></i>
                </div>
                @if ($errors->has('password'))
                    <span class="error">{{ $errors->first('password') }}</span>
                @endif

                <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>

                <button type="submit">Create an Account</button>
            </form>
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

