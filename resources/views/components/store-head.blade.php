<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <title>{{ $title }}</title>
        <meta name="description"
            content="Noren.ro is a website dedicated to providing eco-friendly products for a sustainable lifestyle. Shop our wide range of environmentally-friendly products including reusable items, zero-waste essentials, and more.">
        <link rel="stylesheet" type="text/css" href="/dist/css/loading-screen.css">
        <link rel="icon" type="image/x-icon" href="/images/store/svg/noren-icon.svg">
        <link rel="stylesheet" type="text/css" href="/dist/css/store.css">
        <link rel="canonical" href="{{ url("/") }}">
        @livewireStyles

        {{-- ----------------------------------------------------------- --}}
        <meta property="og:image" content="images/store/logo-banner.webp" />
        <meta property="twitter:image" content="images/store/logo-banner.webp" />
        {{-- ----------------------------------------------------------- --}}
        {{-- <meta property="og:url" content="{{ $canonical }}" /> --}}
        {{-- <meta property="twitter:url" content="{{ $canonical }}" /> --}}
        {{-- ----------------------------------------------------------- --}}
        <meta property="og:type" content="website" />
        <meta property="twitter:card"
            content="Noren.ro is a website dedicated to providing eco-friendly products for a sustainable lifestyle. Shop our wide range of environmentally-friendly products including reusable items, zero-waste essentials, and more." />
        {{-- ----------------------------------------------------------- --}}
        <!-- Open Graph / Facebook -->
        <meta property="og:title" content="{{ $title }}" />
        <meta property="og:description"
            content="Noren.ro is a website dedicated to providing eco-friendly products for a sustainable lifestyle. Shop our wide range of environmentally-friendly products including reusable items, zero-waste essentials, and more." />
        <!-- Twitter -->
        <meta property="twitter:title" content="{{ $title }}" />
        <meta property="twitter:description"
            content="Noren.ro is a website dedicated to providing eco-friendly products for a sustainable lifestyle. Shop our wide range of environmentally-friendly products including reusable items, zero-waste essentials, and more." />
        {{-- ----------------------------------------------------------- --}}
    </head>

    <body id="body">
        <!---------------------- Loading Logo ---------------------->
        <div class="loading-logo" id="loadingLogo">
            <img src="/images/store/svg/noren-black.svg" alt="logo-black">
        </div>
        <script src="/script/store/head.js"></script>

        <!-------------------- End Loading Logo -------------------->
