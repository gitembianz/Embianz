<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __("Store") }}</title>
        <meta name="description"
            content="Ecosticle.ro is a website dedicated to providing eco-friendly products for a sustainable lifestyle. Shop our wide range of environmentally-friendly products including reusable items, zero-waste essentials, and more.">
        <link rel="stylesheet" type="text/css" href="/dist/css/store.css" async defer>

        @livewireStyles
    </head>

    <body>
        <div class="header__top" id="header-banner">
            <div class="container header__top--flex">
                <h4>
                    Dublu confort, jumătate de preț! Ofertă limitată: 2 sticle la prețul uneia singure. Profită acum!
                </h4>
                <a href="{{ url("/storeproducts") }}">
                    {{-- <button aria-label="Go to Store">Store</button> --}}
                </a>
            </div>
        </div>
