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

    <section class="container article-wrapper">
        @if ($article->media->count() != 0)
            @php
                $fullMedia = $article->media->where('type', 'full')->first();
                $originalMedia = $article->media->where('type', 'original')->first();
            @endphp
            <img id="show-article-image" loading="eager" src="/{{ $fullMedia->path }}{{ $fullMedia->name }}"
                data-img-src="/{{ $originalMedia->path }}{{ $originalMedia->name }}"
                alt="{{ $fullMedia->name }} {{ $article->name }}" class="showarticle-image">
        @else
            <img id="show-article-image" loading="eager" src="/images/store/default/default300.webp"
                data-img-src="/images/store/default/default.webp" alt="No image" class="showarticle-image">
        @endif
          <!-------------------- Modal article ------------------>
        <div id="imageModal" class="article-image-modal">
            <span class="article-image-modal-close">&times;</span>
            <img class="article-image-modal-content" id="modalImage">
        </div>
        <h1 class="article-title">{{ $article->name }}</h1>
        @php
            $primaryCategory = $article->article_categories->where('primary_category', true)->first();
        @endphp
        @if ($primaryCategory && $primaryCategory->category)
            <span class="article-category">{{ $primaryCategory->category->short_description }}</span>
        @endif

        <p class="article-date">{{ \Carbon\Carbon::parse($article->created_at)->format('M. j, Y') }}</p>
        <p class="article-description">{{ $article->short_description }}</p>

        @if ($article->article_categories->isNotEmpty())
            <div class="related__cat">
                @if (app()->has('label_pdp_category_tag'))
                    {!! app('label_pdp_category_tag') !!}
                    @foreach ($article->article_categories as $related)
                        <a
                            href="{{ route('blog', ['categorySlug' => $related->category->seo_id ?: $related->category->id]) }}">
                            {{ $related->category->short_description }}
                        </a>
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                @endif
            </div>
        @endif

        <p>
            {!! $article->long_description !!}
        </p>

    </section>

    <!---------------------- Support Center -------------------->
    <x-support />

    <script src="/script/store/article.js" defer></script>
</div>
