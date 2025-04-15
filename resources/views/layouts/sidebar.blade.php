<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="{{ asset('/css/layouts/sidebar.css') }}">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <img src="{{ asset('../images/logo.png') }}" alt="">
        </div>
        <ul class="nav-list">
            <li>
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Search...">
            </li>
            <li>
                <a href="/home">
                    <i class='bx bx-home' ></i>
                    <span class="links_name">Home</span>
                </a>
            </li>
            <li>
                <a href="/completed">
                    <i class='bx bx-checkbox-checked'></i>
                    <span class="links_name">Completed</span>
                </a>
            </li>
            <li>
                <a href="/personal">
                    <i class='bx bx-user'></i>
                    <span class="links_name">Personal</span>
                </a>
            </li>
            <li>
                <a href="/work">
                    <i class='bx bx-briefcase-alt'></i>
                    <span class="links_name">Work</span>
                </a>
            </li>
            <li>
                <a href="/education">
                    <i class='bx bxs-school'></i>
                    <span class="links_name">Education</span>
                </a>
            </li>
            <li>
                <a href="/health">
                    <i class='bx bx-heart' ></i>
                    <span class="links_name">Health</span>
                </a>
            </li>
            <li class="profile">
                <div class="profile-details">
                    <div class="name">{{ Auth::user()->name }}</div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <i class='bx bx-log-out' id="log_out" onclick="document.getElementById('logout-form').submit();"></i>
            </li>
        </ul>
    </div>
</body>
<script src="{{ asset('../js/layouts/sidebar.js') }}"></script>
</html>
