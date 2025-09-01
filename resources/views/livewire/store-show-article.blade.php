<div id="store-show-article">
    @if (app()->has('global_display_breadcrumbs') && app('global_display_breadcrumbs') === 'true')

        <ol class="breadcrumbs container">
            <li>
                <a class="breadcrumbs__link" href="{{ url('/') }}">
                    @if (app()->has('label_breadcrumbs_home_page'))
                        {!! app('label_breadcrumbs_home_page') !!}
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('article', ['article' => $article->seo_id !== null && $article->seo_id !== '' ? $article->seo_id : $article->id]) }}"
                    class="breadcrumbs__link">{{ $article->name }}</a>
            </li>
        </ol>
    @endif

    <section class="product container">
        <!-------------------- Slider article ------------------>
        <div class="product-slider">
            <div class="product-slider__center">

                <div class="product-slider__wrapper">
                    @if ($article->media->count() != 0)
                        <div class="product-slider__slide">
                            <img width="550" height="550" loading="eager"
                                src="/{{ $article->media->where('type', 'full')->first()->path }}{{ $article->media->where('type', 'full')->first()->name }}"
                                data-name-alt="{{ $article->media->where('type', 'full')->first()->name }}{{ $article->name }}"
                                alt="{{ $article->media->where('type', 'full')->first()->name }}{{ $article->name }}"
                                data-img-src="/{{ $article->media->where('type', 'original')->first()->path }}{{ $article->media->where('type', 'original')->first()->name }}">
                        </div>
                    @else
                        <div class="product-slider__slide">
                            <img loading="eager" src="/images/store/default/default.webp"
                                data-img-src="/images/store/default/default.webp" alt="something wrong"
                                data-name-alt="something wrong">
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!------------------ End Slider article ---------------->
        <!------------------------------------------------------>
        <!-------------------- Modal article ------------------>
        <!------------------ End Modal article ---------------->
        <!------------------------------------------------------>
        <!----------------------- article details ---------------------->
        <div class="product__container">
            <div class="product__text">
                <div>
                    <h1 class="product__title">{{ $article->name }}</h1>
                    <h2 class="product__subtitle">{{ $article->short_description }}</h2>
                </div>
            </div>
        </div>

        <!--------------------- End article details -------------------->
        <!------------------------------------------------------>
    </section>

    <h2></h2>
    {{-- Display all categories --}}

    <section class="container">
        <div class="related__cat">
            @if (app()->has('label_pdp_category_tag'))
                {!! app('label_pdp_category_tag') !!}
                @foreach ($article->article_categories as $index => $related)
                    <a
                        href="{{ route('articles', ['categorySlug' => $related->category->seo_id !== null && $related->category->seo_id !== '' ? $related->category->seo_id : $related->category->id]) }}">
                        {{ $related->category->short_description }}
                    </a>
                    @if (!$loop->last)
                        ,
                    @endif
                @endforeach
            @endif
        </div>
    </section>
    <section class="tab container">
        <div class="tab__content active">
            <p class="tab__info">{!! $article->long_description !!}</p>
        </div>

    </section>
    <!---------------------------------------------------------->
    <!---------------------- Support Center -------------------->
    <x-support />


    {{-- <script src="/script/store/article.js" defer></script> --}}
</div>
