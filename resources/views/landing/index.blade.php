<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TaskMate</title>
    <link rel="icon" type="image/png" href="{{ asset('../images/logo.png') }}">

    {{-- My Css --}}
    <link rel="stylesheet" href="{{ asset('../css/landing.css') }}">
</head>

<body>
    <nav>
        <div class="nav-logo">
            <img src="{{ '../images/logo.png' }}" alt="">
        </div>
        <div class="nav-button">
            <a href="{{ Route('login') }}">
                <button class="sign-in">Sign In</button>
            </a>
            <a href="{{ Route('register') }}">
                <button class="sign-up">Sign Up</button>
            </a>
        </div>
    </nav>

    <div class="section">
        <div class="description">
            <h1>Hey, Go-Getter! <br> Welcome to Task<span style="color: #39A5ED;">Mate</span></h1>
            <p>Smash your to-dos, stay on top of your game, and get <br> things done effortlessly. TaskMate’s got your back <br>
                for a smoother, smarter way to tackle your day!</p>
            <button>Get Started</button>
        </div>
        <div class="image">
            <img src="{{ '../images/landingImage.png' }}" alt="">
        </div>
    </div>
</body>

</html>
