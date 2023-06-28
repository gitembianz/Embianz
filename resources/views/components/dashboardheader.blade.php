<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Embianz-project') }}</title>

    <!-- Scripts -->
    <link rel="stylesheet" href="/dist/css/main.css">
    <script src="/script/calendar.js" defer></script>

    @livewireStyles
</head>

<body>
