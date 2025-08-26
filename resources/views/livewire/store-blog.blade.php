<div id="store-show-product">
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
            {{-- @if ($article->product_categories->isNotEmpty())
                @foreach ($article->getCategoryHierarchy() as $breadcrumb)
                    <li>
                        <a class="breadcrumbs__link"
                            href="{{ route('products', ['categorySlug' => $breadcrumb['slug']]) }}">
                            {{ $breadcrumb['name'] }}
                        </a>
                    </li>
                @endforeach
            @endif --}}
            {{-- <li> --}}
            {{-- <a href="{{ route('article', ['article' => $article->seo_id !== null && $article->seo_id !== '' ? $article->seo_id : $article->id]) }}"
                    class="breadcrumbs__link">{{ $article->name }}</a> --}}
            {{-- de adaugat link cu categoria actuala --}}
            {{-- </li> --}}
        </ol>
    @endif

    <style>
        /* Container for all articles */
        .articles-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            padding: 20px;
            background: #fff;
        }

        /* Card styling */
        .article-card {
         height: 200px;
            background-color: #fafafa;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            /* Default: column on mobile */
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-bottom: 15px;
        }

        /* For tablets and larger screens */
        @media (min-width: 768px) {
            .article-card {
                flex-direction: row;
                /* Switch to row on larger screens */
            }

            .article-image img {
                width: 250px;
                height: 100%;
                object-fit: cover;
            }

            .article-content {
                flex: 1;
                padding: 20px;
            }
        }

        .article-card:hover {
            transform: translateX(+1px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        /* Image styling */
        .article-image img {
            min-width: 350px;
            width: 350px;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Content area */
        .article-content {
            padding: 16px;
            display: flex;
            flex-direction: column;
        }

        /* Category badge */
        .article-category {
            color: #777;
            font-size: 14px;
            margin-top: 4px;
            margin-bottom: 5px;
        }

        /* Title */
        .article-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #222;
        }

        /* Date */
        .article-date {
            color: #777;
            font-size: 14px;
            margin-top: 4px;
            margin-bottom: 12px;
        }

        /* Description */
        .article-description {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }

        .read-more-button {
            margin-top: auto;
            align-self: flex-start;
            padding: 5px 10px;
            background-color: #333333;
            color: white;
            font-weight: 500;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        @media (max-width: 768px) {
            .read-more-button {
                width: 100%;
                text-align: center;
                /* optional: centers the text */
            }
        }
    </style>
    @if ($category)
        <section class="section__header container">
            <h1 class="section__title">
                @if (!empty($category->short_description))
                    {{ $category->short_description }}
                @else
                    {!! $category->name !!}
                @endif
            </h1>
            <p class="section__text">
                {!! $category->long_description !!}
            </p>
        </section>
    @else
        <section class="section__header container">
            <h1 class="section__title">
                @if (app()->has('label_blog_page_title'))
                    {!! app('label_blog_page_title') !!}
                @else
                    Blog
                @endif
            </h1>
            <p class="section__text">
                @if (app()->has('label_blog_page_description'))
                    {!! app('label_blog_page_description') !!}
                @endif
            </p>
        </section>
    @endif

    <section class="controls container" id="productlist">

        <input class="controls__search" maxlength="100" type="text" name="search" id="search"
            wire:model.live.debounce.500ms="search" autocomplete="off"
            placeholder="@if (app()->has('label_placeholder_search')) {!! app('label_placeholder_search') !!} @endif">
        <button class="controls__button" id="sortOpen" aria-label="Open sort button">
            <svg>
                <line x1="21" y1="10" x2="7" y2="10"></line>
                <line x1="21" y1="6" x2="3" y2="6"></line>
                <line x1="21" y1="14" x2="3" y2="14"></line>
                <line x1="21" y1="18" x2="7" y2="18"></line>
            </svg>
        </button>
    </section>
    @foreach ($articles as $article)
        <div class="article container">
            <div class="article-card">
                <div class="article-image">
                    @if ($article->media->where('type', 'main')->first())
                        <img @if ($loop->first) loading="eager"
                        @else
                        loading="lazy" @endif
                            src="/{{ $article->media->where('type', 'main')->first()->path ?? 'images/store/default/' }}{{ $article->media->where('type', 'main')->first()->name ?? 'default.webp' }}"
                            alt="{{ $article->name }}"
                            data-name-alt="{{ $article->media->where('type', 'main')->first()->name ?? 'default' }} {{ $article->name }}">
                    @else
                        <img title="Default image"
                            @if ($loop->first) loading="eager"
                        @else
                        loading="lazy" @endif
                            class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
                    @endif
                </div>

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

                    <p class="article-description">{{ $article->short_description }}{{ $article->short_description }}
                    </p>
                    <a href="{{ route('article', ['article' => $article->seo_id !== null && $article->seo_id !== '' ? $article->seo_id : $article->id]) }}"
                        class="read-more-button">
                        {{-- Read More --}}
                        @if (app()->has('label_article_show_more'))
                            {!! app('label_article_show_more') !!}
                        @else
                            Read More
                        @endif
                    </a>
                </div>
            </div>
        </div>
    @endforeach
    <x-support />
    <div class="filter" id="sortList">
        <div class="filter__content" id="sortContent">
            <div class="filter__top">
                <div class="filter__text--long">
                    @if (app()->has('label_sort_title'))
                        {!! app('label_sort_title') !!}
                    @endif
                </div>
                <button class="filter__reset" id="sortClose">
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            {{-- orderby --}}
            <div class="filter__list">
                <input class="filter__input" wire:model="orderBy" type="radio" name="sort4" value="name_az"
                    id="sort4">
                <label class="filter__link sort__item" for="sort4">
                    <h4>
                        @if (app()->has('label_sort_name_az'))
                            {!! app('label_sort_name_az') !!}
                        @endif
                    </h4>
                </label>

                <input class="filter__input" wire:model="orderBy" type="radio" name="sort5" value="name_za"
                    id="sort5">
                <label class="filter__link sort__item" for="sort5">
                    <h4>
                        @if (app()->has('label_sort_name_za'))
                            {!! app('label_sort_name_za') !!}
                        @endif
                    </h4>
                </label>

                <input class="filter__input" wire:model="orderBy" type="radio" name="sort6" value="date_old_new"
                    id="sort6">
                <label class="filter__link sort__item" for="sort6">
                    <h4>
                        @if (app()->has('label_sort_date_ds'))
                            {!! app('label_sort_date_ds') !!}
                        @endif
                    </h4>
                </label>

                <input class="filter__input" wire:model="orderBy" type="radio" name="sort7" value="date_new_old"
                    id="sort7">
                <label class="filter__link sort__item" for="sort7">
                    <h4>
                        @if (app()->has('label_sort_date_as'))
                            {!! app('label_sort_date_as') !!}
                        @endif
                    </h4>
                </label>
            </div>
        </div>
    </div>
    <script src="/script/store/catalog.js" defer></script>

</div>
