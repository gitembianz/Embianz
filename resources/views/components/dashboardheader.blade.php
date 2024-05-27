<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">

 <title>{{ __('Embianz') }}</title>
 <link rel="icon" type="image/x-icon" href="/favicon.png">

 <!-- Scripts -->
 <link rel="stylesheet" href="/dist/css/main.css">
 <script src="/script/calendar.js" defer></script>
 <style>
  .status {
   width: 100%;
   height: auto;
   display: grid;
   grid-template-columns: repeat(4, 1fr);
   gap: 10px;
  }

  @media (max-width: 1024px) {
   .status {
    grid-template-columns: repeat(2, 1fr);
   }
  }

  @media (max-width: 768px) {
   .status {
    grid-template-columns: repeat(1, 1fr);
   }
  }

  .status__item {
   width: 100%;
   flex-shrink: 1;
   height: 50px;
   display: flex;
   align-items: center;
   justify-content: center;
   background-color: #333333;
   border-radius: 7px;
   padding: 5px;
   gap: 5px;
   color: #fafafa;
   font-size: 16px;
   line-height: 150%;
   font-weight: 500;
   text-decoration: none;
   cursor: pointer
  }

  .status__item span {
   font-size: 16px;
   line-height: 150%;
   font-weight: 500;
   text-decoration: none;
   margin-left: 15px;
  }
 </style>
 @livewireStyles
</head>

<body>
