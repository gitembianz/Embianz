<!DOCTYPE html>
<html lang="ro">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
 <title>{{ $title }}</title>
 @if (app()->has('global_script_head-top'))
  {!! app('global_script_head-top') !!}
 @endif
 <meta name="description" content="{{ $description }}">

 {{-- <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script> --}}

 {{-- DormiSoft Favicon --}}
 {{-- <link rel="icon" type="image/x-icon" href="/images/store/svg/dormisoft_favicon.svg"> --}}
 {{-- noren Favicon --}}
 {{-- <link rel="icon" type="image/x-icon" href="/images/store/svg/noren_favicon.svg"> --}}
 {{-- favicon --}}

 <link rel="apple-touch-icon" sizes="180x180" href="/images/store/svg/apple-touch-icon.png">
 <link rel="icon" type="image/png" sizes="48x48" href="/images/store/svg/favicon-48x48.png">
 <link rel="icon" type="image/png" sizes="32x32" href="/images/store/svg/favicon-32x32.png">
 <link rel="icon" type="image/png" sizes="16x16" href="/images/store/svg/favicon-16x16.png">
 <link rel="manifest" href="/images/store/svg/site.webmanifest">
 <link rel="mask-icon" href="/images/store/svg/safari-pinned-tab.svg" color="#333333">
 <link rel="shortcut icon" href="/images/store/svg/favicon.ico">
 <link rel="preload" href="/images/store/default/default.webp" as="image">
 <meta name="msapplication-TileColor" content="#fafafa">
 <meta name="msapplication-config" content="/images/store/svg/browserconfig.xml">
 <meta name="theme-color" content="#fafafa">

 {{-- favicon end --}}

 <link rel="canonical" href="{{ url('/' . $canonical) }}">
 <link rel="preload" href="/dist/css/store.css" as="style">
 <link rel="stylesheet" href="/dist/css/store.css">

 {{-- ----------------------------------------------------------- --}}
 {{-- ----------------------------------------------------------- --}}
 {{-- ----------------------------------------------------------- --}}
 {{-- ----------------------------------------------------------- --}}
 <!-- Open Graph / Facebook -->
 <meta property="og:url" content="{{ url('/' . $canonical) }}" />
 <meta property="og:type" content="website" />
 <meta property="og:image" content="{{ url('/' . $image) }}" />
 <meta property="og:title" content="{{ $title }}" />
 <meta property="og:description" content="{{ $description }}" />
 <!-- Twitter -->
 <meta property="twitter:url" content="{{ url('/' . $canonical) }}" />
 <meta property="twitter:card" content="summary_large_image" />
 <meta property="twitter:image" content="{{ url('/' . $image) }}" />
 <meta property="twitter:title" content="{{ $title }}" />
 <meta property="twitter:description" content="{{ $description }}" />
 {{-- ----------------------------------------------------------- --}}
 <link rel="preload" href="/images/store/svg/noren-black.svg" as="image">
 <link rel="preload" href="/images/store/default/default300.webp" as="image">
 <link rel="preload" href="/images/store/svg/headset.svg" as="image">
 <link rel="preload" href="/images/store/svg/truck.svg" as="image">
 <link rel="preload" href="/images/store/brands/dhl.webp" as="image">
 <link rel="preload" href="/images/store/brands/Fan.webp" as="image">
 <link rel="preload" href="/images/store/svg/shield.svg" as="image">
 <link rel="preload" href="/images/store/brands/visa.webp" as="image">
 <link rel="preload" href="/images/store/brands/mastercard.webp" as="image">
 <link rel="preload" href="/images/store/svg/chat.svg" as="image">
 <link rel="preload" href="/images/store/svg/thankyou.svg" as="image">
 <link rel="preload" href="/images/store/svg/noren-white.svg" as="image">
 <link rel="preload" href="/fonts/Montserrat/Montserrat-Thin.woff2" as="font" type="font/woff2" crossorigin>
 <link rel="preload" href="/fonts/Montserrat/Montserrat-ExtraLight.woff2" as="font" type="font/woff2"
  crossorigin>
 <link rel="preload" href="/fonts/Montserrat/Montserrat-Light.woff2" as="font" type="font/woff2" crossorigin>
 <link rel="preload" href="/fonts/Montserrat/Montserrat-Regular.woff2" as="font" type="font/woff2"
  crossorigin>
 <link rel="preload" href="/fonts/Montserrat/Montserrat-Medium.woff2" as="font" type="font/woff2" crossorigin>
 <link rel="preload" href="/fonts/Montserrat/Montserrat-SemiBold.woff2" as="font" type="font/woff2"
  crossorigin>
 <link rel="preload" href="/fonts/Montserrat/Montserrat-Bold.woff2" as="font" type="font/woff2" crossorigin>
 <link rel="preload" href="/fonts/Montserrat/Montserrat-ExtraBold.woff2" as="font" type="font/woff2"
  crossorigin>
 <link rel="preload" href="/fonts/Montserrat/Montserrat-Black.woff2" as="font" type="font/woff2" crossorigin>
 <!-- CSS -->

 @if (app()->has('global_script_head-bottom'))
  {!! app('global_script_head-bottom') !!}
 @endif
 @livewireStyles
</head>

<body id="body">
 @if (app()->has('global_script_body-top'))
  {!! app('global_script_body-top') !!}
 @endif
