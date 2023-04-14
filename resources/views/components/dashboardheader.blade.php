<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('Embianz-project') }}</title>

        <!-- Scripts -->
        <link rel="stylesheet" href="/dist/css/main.css">

        <link href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.13.4/datatables.min.css" rel="stylesheet"/>


        <script src="/script/calendar.js" defer></script>
        <script src="/script/imgupload.js"></script>

    </head>
    <body>
