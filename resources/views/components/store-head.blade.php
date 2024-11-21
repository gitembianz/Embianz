<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
 @if (app()->has('global_site_name'))
  <meta name="author" content="{{ app('global_site_name') }}">
 @endif
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
 <title>{{ $title }}</title>
 @if (app()->has('global_script_head-top'))
  {!! app('global_script_head-top') !!}
 @endif
 <meta name="description" content="{{ $description }}">

 <link rel="alternate" hreflang="{{ app()->getLocale() }}" href="{{ route('home') }}">
 <link rel="apple-touch-icon" sizes="180x180" href="/images/store/svg/apple-touch-icon.png">
 <link rel="icon" type="image/png" sizes="48x48" href="/images/store/svg/favicon-48x48.png">
 <link rel="icon" type="image/png" sizes="32x32" href="/images/store/svg/favicon-32x32.png">
 <link rel="icon" type="image/png" sizes="16x16" href="/images/store/svg/favicon-16x16.png">
 <link rel="icon" href="/images/store/svg/favicon.svg">
 <link rel="manifest" href="/images/store/svg/site.webmanifest">
 <link rel="mask-icon" href="/images/store/svg/safari-pinned-tab.svg" color="#333333">
 <link rel="shortcut icon" href="/images/store/svg/favicon.ico">
 <meta name="msapplication-TileColor" content="#fafafa">
 <meta name="msapplication-config" content="/images/store/svg/browserconfig.xml">
 <meta name="theme-color" content="#fafafa">

 {{-- dinamical image playload --}}
 @if ($preload != '')
  <link rel="preload" href="{{ $preload }}" as="image">
 @endif
 {{-- favicon end --}}

 <link rel="canonical" href="{{ $canonical }}">

 <?php
 $theme = app()->has('global_theme') ? app('global_theme') : 'store';
 $href = '/dist/css/' . $theme . '.css';
 ?>
 <link rel="preload" href="{{ $href }}" as="style">
 <link rel="stylesheet" href="{{ $href }}" async>
 @if (app()->has('global_site_name'))
  <meta property="og:site_name" content="{{ app('global_site_name') }}">
 @endif
 <meta property="og:locale" content="{{ env('APP_LOCALE') }}">
 <!-- Open Graph / Facebook -->
 <meta property="og:url" content="{{ config('app.url') . '/' . $canonical }}" />
 <meta property="og:type" content="website" />
 <meta property="og:image" content="{{ url('/' . $image) }}" />
 <meta property="og:title" content="{{ $title }}" />
 <meta property="og:description" content="{{ $description }}" />
 <!-- Twitter -->
 <meta property="twitter:url" content="{{ config('app.url') . '/' . $canonical }}" />
 <meta property="twitter:card" content="summary_large_image" />
 <meta property="twitter:image" content="{{ url('/' . $image) }}" />
 <meta property="twitter:title" content="{{ $title }}" />
 <meta property="twitter:description" content="{{ $description }}" />


 @if (app()->has('global_script_head-bottom'))
  {!! app('global_script_head-bottom') !!}
 @endif
 @livewireStyles
</head>

<body id="body">
 @if (app()->has('global_script_body-top'))
  {!! app('global_script_body-top') !!}
 @endif
