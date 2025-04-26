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
                <input type="text" placeholder="Search..." id="searchInput">
            </li>
            <li>
                <a href="/dashboard">
                    <i class='bx bx-home' ></i>
                    <span class="links_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/board">
                    <i class='bx bx-task'></i>
                    <span class="links_name">Board</span>
                </a>
            </li>
            <li>
                <a href="/event">
                    <i class='bx bx-calendar-event'></i>
                    <span class="links_name">Event</span>
                </a>
            </li>
            <li>
                <a href="/notes">
                    <i class='bx bx-notepad'></i>
                    <span class="links_name">Notes</span>
                </a>
            </li>
            <li>
                <a href="/weekly-schedule">
                    <i class='bx bx-calendar-week'></i>
                    <span class="links_name">Weekly Schedule</span>
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
