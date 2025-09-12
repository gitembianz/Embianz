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
                <a class="breadcrumbs__link" href="{{ route('blog') }}">
                    @if (app()->has('label_blog_page'))
                        {!! app('label_blog_page') !!}
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('article', ['article' => $article->seo_id !== null && $article->seo_id !== '' ? $article->seo_id : $article->id]) }}"
                    class="breadcrumbs__link">{{ $article->name }}</a>
            </li>
        </ol>
    @endif

    <section class="container" style="display: flex; flex-direction: row; gap: 2rem;">
        <!-------------------- Slider article ------------------>

        <div>
            @if ($article->media->count() != 0)
                <div>
                    <img id="show-article-image" loading="eager"
                        src="/{{ $article->media->where('type', 'full')->first()->path }}{{ $article->media->where('type', 'full')->first()->name }}"
                        data-name-alt="{{ $article->media->where('type', 'full')->first()->name }}{{ $article->name }}"
                        alt="{{ $article->media->where('type', 'full')->first()->name }}{{ $article->name }}"
                        data-img-src="/{{ $article->media->where('type', 'original')->first()->path }}{{ $article->media->where('type', 'original')->first()->name }}">
                </div>
            @else
                <div>
                    <img id="show-article-image" loading="eager" src="/images/store/default/default.webp"
                        data-img-src="/images/store/default/default.webp" alt="something wrong"
                        data-name-alt="something wrong">
                </div>
            @endif
        </div>
        <!-------------------- Modal article ------------------>
        <div id="imageModal" class="article-image-modal">
            <span class="article-image-modal-close">&times;</span>
            <img class="article-image-modal-content" id="modalImage">
        </div>
        <!----------------------- article details ---------------------->
        <div class="article-content">
            <h2 class="article-title">{{ $article->name }}</h2>
            @php
                $primaryCategory = $article->article_categories->where('primary_category', true)->first();
            @endphp

            @if ($primaryCategory && $primaryCategory->category)
                <span class="article-category">{{ $primaryCategory->category->short_description }}</span>
            @endif
            <p class="article-date">
                {{ \Carbon\Carbon::parse($article->created_at)->format('M. j, Y') }}
            </p>

            <p class="article-description">{{ $article->short_description }}
            </p>
        </div>

        <!--------------------- End article details -------------------->
        <!------------------------------------------------------>
    </section>

    <h2></h2>
    @if ($article->article_categories->isNotEmpty())
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
    @endif
    <section class="tab container">
        <div class="tab__content active">
            <p class="tab__info">{!! $article->long_description !!}</p>
        </div>
    </section>
    <!---------------------- Support Center -------------------->
    <x-support />

    <script src="/script/store/article.js" defer></script>
</div>
