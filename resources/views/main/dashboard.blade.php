{{-- home.blade.php --}}
@extends('layouts.sidebar')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('../css/main/home.css') }}">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <title>TaskMate</title>
</head>

<body>
    <header class="header-section">
        <div class="text">{{ $greeting }}, {{ Auth::user()->name }}!👋</div>
        <p>Today, {{ $currentDateTime }} </p>
    </header>



</body>


</html>
