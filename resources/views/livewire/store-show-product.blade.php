<div id="store-show-product">

    @php
        if (app()->has('global_numberformat_element')) {
            if (app('global_numberformat_element') === '.') {
                $mill = '.';
                $decimal = ',';
            } else {
                $mill = ',';
                $decimal = '.';
            }
        } else {
            $mill = '.';
            $decimal = ',';
        }
    @endphp
    @if (app()->has('global_display_breadcrumbs') && app('global_display_breadcrumbs') === 'true')

        <ol class="breadcrumbs container">
            <li>
                <a class="breadcrumbs__link" href="{{ url('/') }}">
                    @if (app()->has('label_breadcrumbs_home_page'))
                        {!! app('label_breadcrumbs_home_page') !!}
                    @endif
                </a>
            </li>
            @if (app()->has('global_show_on_breadcrumbs') && app('global_show_on_breadcrumbs') == 'true')
                <li>
                    <a class="breadcrumbs__link" href="{{ url('/storeproducts') }}">
                        @if (app()->has('label_breadcrumbs_allproducts'))
                            {!! app('label_breadcrumbs_allproducts') !!}
                        @endif
                    </a>
                </li>
            @endif
            @if ($product->product_categories->isNotEmpty())
                @foreach ($product->getCategoryHierarchy() as $breadcrumb)
                    <li>
                        <a class="breadcrumbs__link"
                            href="{{ route('products', ['categorySlug' => $breadcrumb['slug']]) }}">
                            {{ $breadcrumb['name'] }}
                        </a>
                    </li>
                @endforeach
            @endif
            <li>
                <a href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}"
                    class="breadcrumbs__link">{{ $product->name }}</a>
            </li>
        </ol>
    @endif

    <section class="product container">
        <!-------------------- Slider Product ------------------>
        <div class="product-slider">
            <div class="product-slider__center">

                <div class="product-slider__wrapper">
                    @if ($product->media->count() != 0)
                        @foreach ($product->media->where('type', 'full') as $media)
                            <div class="product-slider__slide">
                                <img width="550" height="550" loading="eager"
                                    src="/{{ $media->path }}{{ $media->name }}"
                                    data-name-alt="{{ $media->name }}{{ $product->name }}"
                                    alt="{{ $media->name }}{{ $product->name }}"
                                    data-img-src="/{{ $product->media->where('type', 'original')->where('sequence', $media->sequence)->first()->path }}{{ $product->media->where('type', 'original')->where('sequence', $media->sequence)->first()->name }}">
                            </div>
                        @endforeach
                    @else
                        <div class="product-slider__slide">
                            <img loading="eager" src="/images/store/default/default.webp"
                                data-img-src="/images/store/default/default.webp" alt="something wrong"
                                data-name-alt="something wrong">
                        </div>
                    @endif
                </div>
                <!-- EDIT TO APPLY CLASS DINAMICALLY -->
                @if ($product->media->where('type', 'full')->count() <= 1)
                    <div class="product-slider__navigation" style="display:none">
                        <button class="product-slider__prev" aria-label="Previous slide">
                            <svg>
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <button class="product-slider__next" aria-label="Next slide">
                            <svg>
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="product-slider__navigation">
                        <button class="product-slider__prev" aria-label="Previous slide">
                            <svg>
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <button class="product-slider__next" aria-label="Next slide">
                            <svg>
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                @endif
                <!-- EDIT TO APPLY CLASS DINAMICALLY -->
            </div>
            <div class="product-slider__pagination--navigation">
                <button class="product-slider__pagination--button product-slider__pagination--prev disabled"
                    aria-label="Previous">
                    <svg>
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <div class="product-slider__pagination">
                </div>
                <button class="product-slider__pagination--button product-slider__pagination--next disabled"
                    aria-label="Next">
                    <svg>
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </div>
        <!------------------ End Slider Product ---------------->
        <!------------------------------------------------------>
        <!-------------------- Modal Product ------------------>
        <div class="product-modal">
            <div class="product-modal__content"></div>
            <button class="product-modal__close">
                <svg>
                    <polyline points="4 14 10 14 10 20"></polyline>
                    <polyline points="20 10 14 10 14 4"></polyline>
                    <line x1="14" y1="10" x2="21" y2="3"></line>
                    <line x1="3" y1="21" x2="10" y2="14"></line>
                </svg>
            </button>

            @if ($product->media->where('type', 'full')->count() == 1)
                <button class="product-modal__prev" style="display:none">
                    <svg>
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button class="product-modal__next" style="display:none">
                    <svg>
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            @else
                <button class="product-modal__prev">
                    <svg>
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button class="product-modal__next">
                    <svg>
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            @endif

            <span class="product-modal__count"></span>
        </div>
        <!------------------ End Modal Product ---------------->
        <!------------------------------------------------------>
        <!----------------------- Product details ---------------------->
        @livewire('product-details', ['product' => $product, 'wishlistItems' => $wishlistItems])
        <!--------------------- End Product details -------------------->
        <!------------------------------------------------------>
    </section>

    <section class="tab container">
        <div class="tab__top">
            <button class="tab__button active" onclick="switchTab(0)">
                @if (app()->has('label_pdp_description_tag'))
                    {!! app('label_pdp_description_tag') !!}
                @endif
            </button>
            @if ($product->product_specs->isNotEmpty())
                <button class="tab__button" onclick="switchTab(1)">
                    @if (app()->has('label_pdp_details_tag'))
                        {!! app('label_pdp_details_tag') !!}
                    @endif
                </button>
            @endif
        </div>
        <div class="tab__content active">
            <p class="tab__info">{!! $product->long_description !!}</p>
        </div>
        @if ($product->product_specs->isNotEmpty())
            <div class="tab__content">
                <table class="tab__table">
                    <thead>
                        <tr>
                            <th>
                                @if (app()->has('label_pdp_specs_tag'))
                                    {!! app('label_pdp_specs_tag') !!}
                                @endif
                            </th>
                            <th>
                                @if (app()->has('label_pdp_description_tag'))
                                    {!! app('label_pdp_description_tag') !!}
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($product->product_specs->isNotEmpty())
                            @foreach ($product->product_specs->sortBy('sequence') as $spec)
                                <tr>
                                    <td>{{ $spec->spec->name }}</td>
                                    <td>{{ $spec->value }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2">
                                    @if (app()->has('label_pdp_specs_error'))
                                        {!! app('label_pdp_specs_error') !!}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <script>
        function switchTab(tabIndex) {
            const tabs = document.querySelectorAll('.tab__content');
            const buttons = document.querySelectorAll('.tab__button');

            tabs.forEach((tab, index) => {
                if (index === tabIndex) {
                    tab.classList.add('active');
                    buttons[index].classList.add('active');
                } else {
                    tab.classList.remove('active');
                    buttons[index].classList.remove('active');
                }
            });
        }
    </script>

    {{-- modal add review --}}
    <div class="alertorder @if ($showaddreview) active @elseif($sendreview) out @endif"
        id="review__modal" wire:click.self="$set('showaddreview', false)">
        <div class="alertorder__content">
            <button type="button" wire:click="$set('showaddreview', false)" class="modal-close"
                aria-label="Close review modal">
                &times;
            </button>
            <div>
                <h2 style="text-align: center">
                    @if (app()->has('label_pdp_add_review_title'))
                        {!! app('label_pdp_add_review_title') !!}
                    @endif
                </h2>
                <p class="subtitle" style="margin-top: 10px; text-align:center">
                    @if (app()->has('label_pdp_add_review_modal_description'))
                        {!! app('label_pdp_add_review_modal_description') !!}
                    @endif
                </p>

                <form wire:submit.prevent="saveReview">
                    {{-- Rating --}}
                    <div class="stars" wire:ignore>
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="rating-{{ $i }}" name="addrating"
                                value="{{ $i }}" wire:model.live="addrating" />
                            <label for="rating-{{ $i }}" title="{{ $i }} stars">★</label>
                        @endfor
                    </div>


                    {{-- Acronym --}}
                    <div class="checkout__item checkout__item--required">
                        <input type="text" wire:model.defer="acronym"
                            placeholder="@if (app()->has('label_order_email')) {!! app('label_order_email') !!} @endif" required
                            id="acroniminput">
                        <label for="acroniminput">
                            @if (app()->has('label_pdp_add_review_acronim'))
                                {!! app('label_pdp_add_review_acronim') !!}
                            @endif
                        </label>
                    </div>

                    {{-- Message --}}
                    <div class="checkout__item checkout__item--required" id="message">
                        <textarea wire:model.defer="message" style="height: 150px; padding: 10px 20px;" maxlength="5000" required
                            placeholder="Spune-ne mai multe. Incepe să scrii aici..."></textarea>
                        <label>Mesaj</label>
                    </div>
                    @if ($errors->any())
                        <div class="error-list">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="error">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- Submit --}}
                    <button type="submit" class="leftbar__button" style="margin-top: 10px">
                        @if (app()->has('label_add_review_button'))
                            {!! app('label_add_review_button') !!}
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>



    <style>
        .modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            font-size: 28px;
            font-weight: bold;
            color: #333;
            background: transparent;
            border: none;
            cursor: pointer;
            line-height: 1;
            transition: color 0.2s ease;
        }

        .modal-close:hover {
            color: #ff4242;
        }

        form {
            display: grid;
            gap: 14px;
        }

        .stars {
            direction: rtl;
            display: flex;
            gap: 6px;
            justify-content: center;
            align-items: center;
        }

        .stars input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .stars label {
            cursor: pointer;
            font-size: 50px;
            color: #d1d5db;
            transition: color .2s ease;
        }

        .stars label:hover,
        .stars label:hover~label {
            color: #fbbf24;
        }

        .stars input:checked~label {
            color: #f59e0b;
        }

        .error-list {
            margin-bottom: 15px;
            background: #ffe5e5;
            border: 1px solid #ffb3b3;
            border-radius: 5px;
            padding: 10px 15px;
        }

        .error-list li {
            color: #d60000;
            font-size: 0.9rem;
            list-style: none;
            margin: 4px 0;
        }

        .circle-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 32px;
            text-transform: uppercase;
        }
    </style>

    <h2></h2>
    @if (app()->has('global_one_product_page_system') && app('global_one_product_page_system') != 'true')
        <section class="container">
            <div class="related__cat">
                @if (app()->has('label_pdp_category_tag'))
                    {!! app('label_pdp_category_tag') !!}
                    @foreach ($product->product_categories as $index => $related)
                        <a
                            href="{{ route('products', ['categorySlug' => $related->category->seo_id !== null && $related->category->seo_id !== '' ? $related->category->seo_id : $related->category->id]) }}">
                            {{ $related->category->short_description }}
                        </a>
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                @endif
            </div>
        </section>
    @endif
    @if ($product->related_product->filter(fn($item) => !is_null($item['product']))->isNotEmpty())
        <section>
            <div class="section__header container">
                <h2 class="section__title">
                    @if (app()->has('label_pdp_relatedproducts_slider_title'))
                        {!! app('label_pdp_relatedproducts_slider_title') !!}
                    @endif
                </h2>
                <p class="section__text">
                    @if (app()->has('label_pdp_relatedproducts_slider_description'))
                        {!! app('label_pdp_relatedproducts_slider_description') !!}
                    @endif
                </p>
            </div>
        </section>
        <section id="relatedSlider" class="related__slider container section__margin">
            <button class="related__btn prev" aria-label="Previous related slider">
                <svg>
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <button class="related__btn next" aria-label="Next related slider">
                <svg>
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

            <div class="related__wrapper">
                @foreach ($product->related_product as $index => $product)
                    @if (
                        $product->product &&
                            $product->product->active == true &&
                            $product->product->end_date >= now(config('app.timezone'))->format('Y-m-d') &&
                            $product->product->start_date <= now(config('app.timezone'))->format('Y-m-d'))
                        <div class="card product" style="width: 100%;">
                            <a style="width: 100%"
                                href="{{ route('product', ['product' => $product->product->seo_id !== null && $product->product->seo_id !== '' ? $product->product->seo_id : $product->product->id]) }}">
                                @if ($product->product->media->first() != null)
                                    <img loading="eager" width="300" height="300" class="card-image"
                                        src="/{{ $product->product->media->first()->path }}{{ $product->product->media->first()->name }}"
                                        alt="{{ $product->product->media->first()->name }} {{ $product->product->name }}">
                                @else
                                    <img loading="eager" width="300" height="300" class="card-image"
                                        src="/images/store/default/default300.webp" alt="something wrong">
                                @endif
                            </a>
                            @livewire('product-wishlist-button', ['productId' => $product->product->id, 'class' => 'card__action', 'is_in_wishlist' => $this->isInWishlist($product->product->id)], key('relw' . $index))
                            @php
                                if ($product->product->product_prices->count() != 0) {
                                    $price = number_format(
                                        $product->product->product_prices->first()->value,
                                        2,
                                        $decimal,
                                        $mill,
                                    );
                                    $discount =
                                        $product->product->product_prices->first()->discount != 0 ? true : false;
                                } else {
                                    $price = null;
                                    $discount = false;
                                }
                            @endphp

                            @if ($price)
                                @if (
                                    ($product->product->quantity < app('global_low_stock') && $product->product->quantity > 0) ||
                                        $product->product->low_stock)
                                    <p
                                        class="card-status @if ($discount) save-secondary @else save @endif ">
                                        @if (app()->has('label_product_status_stock'))
                                            {!! app('label_product_status_stock') !!}
                                        @endif
                                    </p>
                                    @if ($discount)
                                        <p class="card-status save">
                                            -{{ $product->product->product_prices->first()->discount }}%
                                        </p>
                                    @endif
                                @elseif($product->product->quantity <= 0 && !$product->product->preorder)
                                    <p class="card-status save">
                                        @if (app()->has('label_product_status_indisponible'))
                                            {!! app('label_product_status_indisponible') !!}
                                        @endif
                                    </p>
                                @else
                                    @if ($discount)
                                        <p class="card-status save">
                                            -{{ $product->product->product_prices->first()->discount }}%
                                        </p>
                                    @endif
                                @endif
                            @else
                                <p class="card-status save">
                                    @if (app()->has('label_product_status_coming_soon'))
                                        {!! app('label_product_status_coming_soon') !!}
                                    @endif
                                </p>
                            @endif
                            <div class="card-info">
                                <div class="card-text">
                                    <h2>
                                        <a style="text-decoration: none; font-weight:500"
                                            href="{{ route('product', ['product' => $product->product->seo_id !== null && $product->product->seo_id !== '' ? $product->product->seo_id : $product->product->id]) }}">{{ $product->product->name }}</a>
                                    </h2>
                                    @php
                                        if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
                                            $primaryCategory = $product->product->product_categories
                                                ->where('primary_category', true)
                                                ->first();
                                        } else {
                                            $primaryCategory = $product->product->product_categories->first();
                                        }
                                    @endphp

                                    @if ($primaryCategory && $primaryCategory->category)
                                        <a class="categorylink"
                                            href="{{ route('products', ['categorySlug' => $primaryCategory->category->seo_id !== null && $primaryCategory->category->seo_id !== '' ? $primaryCategory->category->seo_id : $primaryCategory->category->id]) }}">
                                            {{ $primaryCategory->category->short_description }}
                                        </a>
                                    @endif

                                    <p class="card-price">
                                        @if ($discount)
                                            <span class="card-price discount">
                                                @if ($product->product->product_prices->first())
                                                    {{ $price }}
                                                    @if (app()->has('global_currency_primary_symbol'))
                                                        {!! app('global_currency_primary_symbol') !!}
                                                    @endif
                                                @endif
                                            </span>
                                            <span class="card-price oldprice">
                                                {{ number_format($product->product->product_prices->first()->value_no_discount, 2, $decimal, $mill) }}
                                                @if (app()->has('global_currency_primary_name'))
                                                    {!! app('global_currency_primary_name') !!}
                                                @endif
                                            </span>
                                        @else
                                            <span>
                                                @if ($product->product->product_prices->first())
                                                    {{ $price }}
                                                    @if (app()->has('global_currency_primary_symbol'))
                                                        {!! app('global_currency_primary_symbol') !!}
                                                    @endif
                                                @endif
                                            </span>
                                        @endif

                                    </p>
                                </div>
                                @if ($price)
                                    @livewire('add-to-cart-button', ['product' => $product->product], key('rel' . $index))
                                @else
                                    <button class="card-button-disabled" aria-label="Disabled Add to cart button">
                                        @if (app()->has('label_add_to_cart_button_indisponibil'))
                                            {!! app('label_add_to_cart_button_indisponibil') !!}
                                        @endif
                                    </button>
                                @endif
                            </div>
                            <div style="display: none" class="dlv">
                                <span class="dlv_name">{{ $product->product->name }}</span>
                                <span class="dlv_price">{{ $price }}</span>
                                <span class="dlv_currency">
                                    @if (app()->has('global_currency_primary_name'))
                                        {!! app('global_currency_primary_name') !!}
                                    @endif
                                </span>
                            </div>
                            <div style="display: none" class="json-ld-data"
                                data-product-json='@json($product->product)'>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    @endif

    @if ($last_visited_products->count() > 0)
        <section>
            <div class="section__header container">
                <h2 class="section__title">
                    @if (app()->has('label_pdp_lastviewproducts_slider_title'))
                        {!! app('label_pdp_lastviewproducts_slider_title') !!}
                    @endif
                </h2>
                <p class="section__text">
                    @if (app()->has('label_pdp_lastviewproducts_slider_description'))
                        {!! app('label_pdp_lastviewproducts_slider_description') !!}
                    @endif
                </p>
            </div>
        </section>
        <section id="lastseenSlider" class="related__slider container">
            <div class="related__navigation">
                <button class="related__btnlast prev" aria-label="Previous related slider">
                    <svg>
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button class="related__btnlast next" aria-label="Next related slider">
                    <svg>
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>

            </div>
            <div class="related__wrapperlast">
                @foreach ($last_visited_products as $key => $product)
                    <div class="card product" style="width: 100%;">
                        <a style="width: 100%"
                            href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}">

                            @if ($product->media->first() != null)
                                <img loading="eager" width="300" height="300" class="card-image"
                                    src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}"
                                    alt="{{ $product->media->first()->name }} {{ $product->name }}">
                            @else
                                <img loading="eager" width="300" height="300" class="card-image"
                                    src="/images/store/default/default300.webp" alt="something wrong">
                            @endif
                        </a>
                        @livewire('product-wishlist-button', ['productId' => $product->id, 'class' => 'card__action', 'is_in_wishlist' => $this->isInWishlist($product->id)], key('lastw' . $key))
                        <?php if ($product->product_prices->count() != 0) {
                            $price = number_format($product->product_prices->first()->value, 2, $decimal, $mill);
                            $discount = $product->product_prices->first()->discount != 0 ? true : false;
                        } else {
                            $price = null;
                            $discount = false;
                        }
                        ?>
                        @if ($price)
                            @if (($product->quantity < app('global_low_stock') && $product->quantity > 0) || $product->low_stock)
                                <p
                                    class="card-status @if ($discount) save-secondary
          @else
             save @endif ">
                                    @if (app()->has('label_product_status_stock'))
                                        {!! app('label_product_status_stock') !!}
                                    @endif
                                </p>
                                @if ($discount)
                                    <p class="card-status save">
                                        -{{ $product->product_prices->first()->discount }}%
                                    </p>
                                @endif
                            @elseif($product->quantity <= 0 && !$product->preorder)
                                <p class="card-status save">
                                    @if (app()->has('label_product_status_indisponible'))
                                        {!! app('label_product_status_indisponible') !!}
                                    @endif
                                </p>
                            @else
                                @if ($discount)
                                    <p class="card-status save">
                                        -{{ $product->product_prices->first()->discount }}%
                                    </p>
                                @endif
                            @endif
                            {{-- tagul de discount --}}
                        @else
                            <p class="card-status save">
                                @if (app()->has('label_product_status_coming_soon'))
                                    {!! app('label_product_status_coming_soon') !!}
                                @endif
                            </p>
                        @endif
                        <div class="card-info">
                            <div class="card-text">
                                <h2><a style="text-decoration: none; font-weight:500"
                                        href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}">{{ $product->name }}</a>
                                </h2>
                                @php
                                    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
                                        $primaryCategory = $product->product_categories
                                            ->where('primary_category', true)
                                            ->first();
                                    } else {
                                        $primaryCategory = $product->product_categories->first();
                                    }
                                @endphp

                                @if ($primaryCategory && $primaryCategory->category)
                                    <a class="categorylink"
                                        href="{{ route('products', ['categorySlug' => $primaryCategory->category->seo_id !== null && $primaryCategory->category->seo_id !== '' ? $primaryCategory->category->seo_id : $primaryCategory->category->id]) }}">
                                        {{ $primaryCategory->category->short_description }}
                                    </a>
                                @endif

                                <p class="card-price">
                                    @if ($discount)
                                        <span class="card-price discount">
                                            @if ($product->product_prices->first())
                                                {{ $price }}
                                                @if (app()->has('global_currency_primary_symbol'))
                                                    {!! app('global_currency_primary_symbol') !!}
                                                @endif
                                            @endif
                                        </span>
                                        <span class="card-price oldprice">
                                            {{ number_format($product->product_prices->first()->value_no_discount, 2, $decimal, $mill) }}
                                            @if (app()->has('global_currency_primary_name'))
                                                {!! app('global_currency_primary_name') !!}
                                            @endif
                                        </span>
                                    @else
                                        <span>
                                            @if ($product->product_prices->first())
                                                {{ $price }}
                                                @if (app()->has('global_currency_primary_symbol'))
                                                    {!! app('global_currency_primary_symbol') !!}
                                                @endif
                                            @endif
                                        </span>
                                    @endif

                                </p>
                            </div>
                            @if ($price)
                                @livewire('add-to-cart-button', ['product' => $product], key('adlast' . $key))
                            @else
                                <button class="card-button-disabled" aria-label="Disabled Add to cart button">
                                    @if (app()->has('label_add_to_cart_button_indisponibil'))
                                        {!! app('label_add_to_cart_button_indisponibil') !!}
                                    @endif
                                </button>
                            @endif
                        </div>
                        <div style="display: none" class="dlv">
                            <span class="dlv_name">{{ $product->name }}</span>
                            <span class="dlv_price">{{ $price }}</span>
                            <span class="dlv_currency">
                                @if (app()->has('global_currency_primary_name'))
                                    {!! app('global_currency_primary_name') !!}
                                @endif
                            </span>
                        </div>
                        <div style="display: none" class="json-ld-data"
                            data-product-json='@json($product)'></div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if (app()->has('global_review_system') && app('global_review_system') === 'true')
        @php
            $stats = $this->productReviewStats;
            $ratings = $this->productRatingBreakdown;

            $totalReviews = $stats->count ?? 0;
            $avgScore = $stats->avg ?? 0;

            $reviews45 = ($ratings[4] ?? 0) + ($ratings[5] ?? 0);
            $recommendedPercent = $totalReviews > 0 ? round(($reviews45 / $totalReviews) * 100) : 0;
        @endphp

        <section>
            <div class="section__header container">
                <h2 class="section__title">
                    {!! app('label_pdp_reviews_section_title') ?? '' !!}
                </h2>
            </div>

            <input type="hidden" name="total_reviews" id="total_reviews" value="{{ $totalReviews }}">

            <p class="section__text" style="padding: 15px">
                {!! app('label_pdp_reviews_description') ?? '' !!}
            </p>

            @if ($totalReviews > 0)

                <div class="container grid-product-reviews">

                    <div wire:ignore class="product-reviews__info reviews-info">
                        <h2 class="product__title">
                            {!! app('label_pdp_review_count_text') ?? '' !!}
                            {{ $totalReviews }}
                        </h2>

                        <div class="ratingscore">
                            <div class="rating" style="--rating: {{ $avgScore * 20 }}%;"></div>
                            @if (app('global_display_rating_value') === 'true')
                                ({{ number_format($avgScore, 2) }})
                            @endif
                        </div>

                        @if ($reviews45 > 0)
                            <div class="reviews-info__percentage">
                                {{ $reviews45 }}
                                {!! app('label_pdp_reviews_out_of') ?? '' !!}
                                {{ $totalReviews }}
                                ({{ $recommendedPercent }}%)
                            </div>

                            <span class="reviews-info__caption">
                                {!! app('label_pdp_reviews_customers_recommended') ?? '' !!}
                            </span>
                        @endif
                    </div>

                    <div wire:ignore class="product-reviews__bar reviews-bar">
                        <ul class="list-reset reviews-bar__list">
                            @for ($i = 5; $i >= 1; $i--)
                                <li class="reviews-bar__item">
                                    <div class="progress-bar">
                                        <span class="progress-bar__star">{{ $i }}</span>
                                        <div class="progress-bar__outter-line" data-rating="{{ $ratings[$i] }}">
                                            <span class="progress-bar__inner-line"></span>
                                        </div>
                                        <span class="progress-bar__quantity">
                                            {{ $ratings[$i] }}
                                        </span>
                                    </div>
                                </li>
                            @endfor
                        </ul>
                    </div>

                    <div class="product-reviews__info">
                        <h2 class="product__title">
                            {!! app('label_pdp_add_review_title') ?? '' !!}
                        </h2>
                        <p class="subtitle">
                            {!! app('label_pdp_add_review_description') ?? '' !!}
                        </p>
                        <button wire:click="addreview" class="leftbar__button" style="margin-top:10px">
                            {!! app('label_add_review_button') ?? '' !!}
                        </button>
                    </div>
                </div>
            @else
                <div class="container product-reviews__info">
                    <h2 class="product__title">
                        {!! app('label_pdp_add_review_title') ?? '' !!}
                    </h2>
                    <p class="subtitle">
                        {!! app('label_pdp_add_review_description') ?? '' !!}
                    </p>
                    <button wire:click="addreview" class="leftbar__button" style="margin-top:10px">
                        {!! app('label_add_review_button') ?? '' !!}
                    </button>
                </div>
            @endif

            @if ($totalReviews > 0)

                <div class="container" style="margin-top: 15px">
                    <p class="section__text">
                        {!! app('label_pdp_reviews_list') ?? '' !!}
                    </p>
                </div>

                <script>
                    document.addEventListener('livewire:load', function() {
                        let observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    Livewire.emit('loadMoreReviews');
                                }
                            });
                        }, {
                            rootMargin: '100px'
                        });

                        let lastRecord = document.getElementById('last_record');
                        if (lastRecord) observer.observe(lastRecord);

                        Livewire.hook('message.processed', (message, component) => {
                            lastRecord = document.getElementById('last_record');
                            if (lastRecord) observer.observe(lastRecord);
                        });
                    });
                </script>


                @foreach ($product_reviews as $index => $review)
                    @php
                        $initial = strtoupper(mb_substr($review->acronim, 0, 1));
                        $colors = [
                            '#E57373',
                            '#81C784',
                            '#64B5F6',
                            '#FFD54F',
                            '#BA68C8',
                            '#4DB6AC',
                            '#FF8A65',
                            '#A1887F',
                        ];
                        $color = $colors[crc32($review->acronim) % count($colors)];
                    @endphp

                    <div class="container" style="padding-top: 15px">
                        <div @if ($loop->last) id="last_record" @endif class="article-card">
                            <div class="article-image">
                                <div class="circle-avatar" style="background-color: {{ $color }}">
                                    {{ $initial }}
                                </div>
                            </div>

                            <div class="article-content">
                                <h2 class="article-title">{{ $review->acronim }}</h2>
                                <div class="ratingscore">
                                    <div class="rating" style="--rating: {{ $review->score * 20 }}%;"></div>
                                    ({{ number_format($review->score, 2) }})
                                </div>
                                <p class="article-date">
                                    {{ \Carbon\Carbon::parse($review->created_at)->format('M. j, Y') }}
                                </p>
                                <p class="article-description">{{ $review->comment }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if (app('global_pagination') === 'links')
                    <section class="container">
                        {{ $product_reviews->links() }}
                    </section>
                @else
                    <div wire:loading>
                    </div>
                @endif
            @else
                <div class="container">
                    <p class="section__text">
                        {!! app('label_pdp_reviews_no_reviews') ?? '' !!}
                    </p>
                </div>
            @endif
        </section>
    @endif


    <!---------------------- Support Center -------------------->
    <x-support />

    <script>
        document.addEventListener("livewire:load", function() {
            injectJsonLd();

            Livewire.hook("message.processed", (message, component) => {
                injectJsonLd();
            });

            function injectJsonLd() {
                document.querySelectorAll('.json-ld-data').forEach((element, index) => {
                    const productData = element.dataset.productJson;
                    let product;

                    try {
                        product = JSON.parse(productData);
                    } catch (e) {
                        console.warn("Invalid JSON in .json-ld-data", e);
                        return;
                    }

                    const currencyElement = document.querySelector('.dlv_currency');
                    const currency = currencyElement ? currencyElement.textContent.trim() : 'EUR';

                    const price = product.product_prices?.[0]?.value || '0';
                    const ratingValue = product.reviews?.[0]?.value || '0';
                    const reviewCount = product.reviews?.[0]?.count || '0';

                    const media = product.media?.[0] ?
                        `${window.location.origin}/${product.media[0].path}${product.media[0].name}` :
                        `${window.location.origin}/images/store/default/default300.webp`;

                    const description = product.long_description ?
                        product.long_description.replace(/(<([^>]+)>)/gi, "") :
                        "";

                    const seoUrl = `${window.location.origin}/product/${product.seo_id || product.id}`;

                    const existingScript = document.getElementById(`jsonld-product-${product.id}`);
                    if (existingScript) {
                        existingScript.remove();
                    }

                    if (price !== '0') {
                        const jsonLd = {
                            "@context": "https://schema.org/",
                            "@type": "Product",
                            "name": product.name,
                            "image": media,
                            "description": description,
                            "brand": {
                                "@type": "Brand",
                                "name": product.brand
                            },
                            "sku": product.sku,
                            "offers": {
                                "@type": "Offer",
                                "url": seoUrl,
                                "priceCurrency": currency,
                                "price": price,
                                "availability": "https://schema.org/InStock",
                                "priceValidUntil": product.end_date || "2030-12-31"
                            }
                        };

                        if (ratingValue !== '0' && reviewCount !== '0') {
                            jsonLd.aggregateRating = {
                                "@type": "AggregateRating",
                                "ratingValue": ratingValue,
                                "reviewCount": reviewCount
                            };
                            jsonLd.review = {
                                "@type": "Review",
                                "reviewRating": {
                                    "@type": "Rating",
                                    "ratingValue": ratingValue,
                                    "bestRating": "5"
                                },
                                "author": {
                                    "@type": "Person",
                                    "name": "anonim"
                                }
                            };
                        }

                        const script = document.createElement("script");
                        script.type = "application/ld+json";
                        script.id = `jsonld-product-${product.id}`;
                        script.textContent = JSON.stringify(jsonLd);
                        document.head.appendChild(script);
                    }
                });
            }
        });
    </script>

    <script src="/script/store/product.js" defer></script>
</div>
