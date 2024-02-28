<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
 <title>{{ $title }}</title>
 @if (app()->has('global_script_head-top'))
  {!! app('global_script_head-top') !!}
 @endif
 <meta name="description" content="{{ $description }}">

 {{-- DormiSoft Favicon --}}
 <link rel="icon" type="image/x-icon" href="/images/store/svg/dormisoft_favicon.svg">
 {{-- Noren Favicon --}}
 <link rel="icon" type="image/x-icon" href="/images/store/svg/noren_favicon.svg">

 <link rel="canonical" href="{{ url('/' . $canonical) }}">
 <link rel="preload" href="/dist/css/loading-screen.css" as="style">
 <link rel="preload" href="/dist/css/store.css" as="style">
 <link rel="stylesheet" href="/dist/css/loading-screen.css">
 <link rel="stylesheet" href="/dist/css/store.css">
 {{-- <script rel="preload" src="script/store/head.js" as="script"></script> --}}

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


 @if (app()->has('global_script_head-bottom'))
  {!! app('global_script_head-bottom') !!}
 @endif
 @livewireStyles
</head>

<body id="body">
 @if (app()->has('global_script_body-top'))
  {!! app('global_script_body-top') !!}
 @endif
 <!---------------------- Loading Logo ---------------------->
 <div class="loading-logo" id="loadingLogo">
  <img rel="preload" as="image" src="/images/store/svg/noren-black.svg" alt="logo-black">
 </div>
 <script src="/script/store/head.js"></script>
 <!-------------------- End Loading Logo -------------------->
