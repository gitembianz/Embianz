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
        </ol>
    @endif


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
<input type="hidden" name="articlestop" id="articlestop" />
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
    @foreach ($articles as $index => $article)
        <div style="padding-top: 15px" class="container" data-article>
            <div @if ($loop->last) id="last_record" @endif class="article-card">
                <div class="article-image">
                    @if ($article->media->where('type', 'main')->first())
                        <a href="{{ route('article', ['article' => $article->seo_id !== null && $article->seo_id !== '' ? $article->seo_id : $article->id]) }}">
                            <img @if ($loop->first) loading="eager"
                @else loading="lazy" @endif
                                src="/{{ $article->media->where('type', 'main')->first()->path ?? 'images/store/default/' }}{{ $article->media->where('type', 'main')->first()->name ?? 'default.webp' }}"
                                alt="{{ $article->name }}"
                                data-name-alt="{{ $article->media->where('type', 'main')->first()->name ?? 'default' }} {{ $article->name }}">
                        </a>
                    @else
                        <img title="Default image"
                            @if ($loop->first) loading="eager"
                @else loading="lazy" @endif
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

                    <p class="article-description">{{ $article->short_description }}
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
    @unless (app()->has('global_blog_pagination') && app('global_blog_pagination') === 'links')
        <x-lazy />
    @endunless
    @if (app()->has('global_blog_pagination') && app('global_blog_pagination') === 'links')
        <section class="container">
            <div id="blog-pagination"></div>
        </section>
    @else
        @if ($articles->total() > $loadAmount)
            <section class="container">
                <button class="filter__apply" wire:click="loadMore" wire:loading.remove>Vezi mai mult!</button>
            </section>
        @endif
    @endif
    <x-support />

  {{-- javascript pagination --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const articles = Array.from(document.querySelectorAll('[data-article]'));
            const perPage = {{ $loadAmount }};
            const storageKey = 'articles_page';
            const urlKey = 'article_page';

            let currentPage = getInitialPage();

            /* ---------------- INIT ---------------- */

            function getInitialPage() {
                const params = new URLSearchParams(window.location.search);
                if (params.get(urlKey)) return parseInt(params.get(urlKey), 10);
                if (localStorage.getItem(storageKey)) return parseInt(localStorage.getItem(storageKey), 10);
                return 1;
            }

            function isMobile() {
                return window.innerWidth <= 768;
            }

            /* ---------------- RENDER ---------------- */

            function render(withAnimation = false) {
                const start = (currentPage - 1) * perPage;
                const end = start + perPage;

                if (withAnimation) animateOut();

                setTimeout(() => {
                    articles.forEach((el, index) => {
                        el.style.display = (index >= start && index < end) ? '' : 'none';
                    });

                    renderPagination();
                    syncState();

                    if (withAnimation) animateIn();

                }, withAnimation ? 200 : 0);
                 document.getElementById('articlestop').scrollIntoView({ behavior: 'smooth' });
            }

            /* ---------------- PAGINATION ---------------- */

            function renderPagination() {
                const totalPages = Math.ceil(articles.length / perPage);
                const container = document.getElementById('blog-pagination');

                if (!container || totalPages <= 1) return;

                container.innerHTML = '';
                container.className = 'reviews-pagination';

                const ul = document.createElement('ul');
                ul.className = 'pagination';

                /* PREVIOUS */
                ul.appendChild(createButton('‹', currentPage - 1, currentPage === 1));

                if (!isMobile()) {
                    if (currentPage > 2) ul.appendChild(createButton('1', 1));
                    if (currentPage > 3) ul.appendChild(createEllipsis());

                    for (let i = Math.max(1, currentPage - 1); i <= Math.min(totalPages, currentPage + 1); i++) {
                        ul.appendChild(createButton(i, i, false, i === currentPage));
                    }

                    if (currentPage < totalPages - 2) ul.appendChild(createEllipsis());
                    if (currentPage < totalPages - 1) ul.appendChild(createButton(totalPages, totalPages));
                } else {
                    const li = document.createElement('li');
                    li.className = 'page-item active';
                    li.innerHTML = `<span class="page-link">${currentPage} / ${totalPages}</span>`;
                    ul.appendChild(li);
                }

                /* NEXT */
                ul.appendChild(createButton('›', currentPage + 1, currentPage === totalPages));

                container.appendChild(ul);
            }

            function createButton(label, page, disabled = false, active = false) {
                const li = document.createElement('li');
                li.className = 'page-item';

                if (disabled) li.classList.add('disabled');
                if (active) li.classList.add('active');

                const btn = document.createElement('button');
                btn.className = 'page-link';
                btn.innerText = label;
                btn.disabled = disabled;

                btn.onclick = () => {
                    if (disabled) return;
                    currentPage = page;
                    render(true);
                    document.getElementById('articles')?.scrollIntoView({
                        behavior: 'smooth'
                    });
                };

                li.appendChild(btn);
                return li;
            }

            function createEllipsis() {
                const li = document.createElement('li');
                li.className = 'page-item disabled';
                li.innerHTML = `<span class="page-link">…</span>`;
                return li;
            }

            /* ---------------- STATE SYNC ---------------- */

            function syncState() {
                localStorage.setItem(storageKey, currentPage);

                const params = new URLSearchParams(window.location.search);
                params.set(urlKey, currentPage);
                history.replaceState({}, '', `${window.location.pathname}?${params}`);
            }

            /* ---------------- ANIMATIONS ---------------- */

            function animateOut() {
                articles.forEach(el => {
                    el.style.opacity = 0;
                    el.style.transform = 'translateY(10px)';
                });
            }

            function animateIn() {
                articles.forEach(el => {
                    el.style.transition = 'opacity .3s ease, transform .3s ease';
                    el.style.opacity = 1;
                    el.style.transform = 'translateY(0)';
                });
            }

            /* ---------------- EVENTS ---------------- */

            window.addEventListener('resize', () => render());
            render();

        });
    </script>


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

                <input class="filter__input" wire:model="orderBy" type="radio" name="sort7"
                    value="date_new_old" id="sort7">
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
