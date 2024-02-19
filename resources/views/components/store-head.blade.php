<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="stylesheet" type="text/css" href="/dist/css/loading-screen.css">
    <link rel="icon" type="image/x-icon" href="/images/store/svg/noren-icon.svg">
    <link rel="stylesheet" type="text/css" href="/dist/css/store.css">
    <link rel="canonical" href="/{{ $canonical }}">
    @livewireStyles

    {{-- ----------------------------------------------------------- --}}
    <meta property="og:image" content="images/store/logo-banner.webp" />
    <meta property="twitter:image" content="images/store/logo-banner.webp" />
    {{-- ----------------------------------------------------------- --}}
    {{-- <meta property="og:url" content="{{ $canonical }}" /> --}}
    {{-- <meta property="twitter:url" content="{{ $canonical }}" /> --}}
    {{-- ----------------------------------------------------------- --}}
    <meta property="og:type" content="website" />
    <meta property="twitter:card" content="{{ $description }}" />
    {{-- ----------------------------------------------------------- --}}
    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:description" content="{{ $description }}" />
    <!-- Twitter -->
    <meta property="twitter:title" content="{{ $title }}" />
    <meta property="twitter:description" content="{{ $description }}" />
    {{-- ----------------------------------------------------------- --}}
</head>

<body id="body">
    <!---------------------- Loading Logo ---------------------->
    <div class="loading-logo" id="loadingLogo">
        <img src="/images/store/svg/noren-black.svg" alt="logo-black">
    </div>
    <script src="/script/store/head.js"></script>

    <!-------------------- End Loading Logo -------------------->
