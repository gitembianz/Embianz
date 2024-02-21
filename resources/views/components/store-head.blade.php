<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <title>{{ $title }}</title>
        <meta name="description" content="{{ $description }}">
        <link rel="icon" type="image/x-icon" href="/images/store/svg/noren-icon.svg">
        <link rel="canonical" href="{{ url("/" . $canonical) }}">
        <link rel="preload" href="/dist/css/loading-screen.css" as="style">
        <link rel="preload" href="/dist/css/store.css" as="style">
        <link rel="stylesheet" href="/dist/css/loading-screen.css">
        <link rel="stylesheet" href="/dist/css/store.css">
        <script rel="preload" src="script/store/head.js" as="script"></script>
        <script rel="preload" src="script/store/header.js" as="script"></script>
        <script rel="preload" src="script/store/general.js" as="script"></script>
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
            <img rel="preload" as="image" src="/images/store/svg/noren-black.svg" alt="logo-black">
        </div>
        <script src="/script/store/head.js"></script>
        <script src="/script/store/header.js" async defer></script>
        <script src="script/store/general.js" async defer></script>

        <!-------------------- End Loading Logo -------------------->
