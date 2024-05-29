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

 {{-- <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" defer></script> --}}

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
 <meta name="msapplication-TileColor" content="#fafafa">
 <meta name="msapplication-config" content="/images/store/svg/browserconfig.xml">
 <meta name="theme-color" content="#fafafa">

 {{-- favicon end --}}

 <link rel="canonical" href="{{ url('/' . $canonical) }}">
 <link rel="preload" href="/dist/css/store.css" as="style">
 <link rel="stylesheet" href="/dist/css/store.css" async>
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

  <script type="text/javascript" src="/~partytown/partytown.js"></script>
  {{-- <script type="text/partytown">
    for (let i = 0; i < 999; i++) console.log(i)
  </script> --}}
 @if (app()->has('global_script_head-bottom'))
  {!! app('global_script_head-bottom') !!}
 @endif
 {{-- @livewireStyles --}}
</head>

<body id="body">
 @if (app()->has('global_script_body-top'))
  {!! app('global_script_body-top') !!}
 @endif
