<div class="products">
    <div class="products__control">
        <button class="sidebar__open" id="filterOpen">
            <svg>
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
        </button>
        <nav class="sidebar" id="filter">
            <div class="filter__list sidebar__content" id="filterContent">
                <button class="sidebar__close" id="filterClose">
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </nav>
        <div class="products__select">
            <select name="cars" id="cars">
                <option value="none" selected disabled>Select By</option>
                <option value="Ford">Ford</option>
                <option value="Ferrari">Ferrari</option>
                <option value="BMW">BMW</option>
                <option value="Porsche">Porsche</option>
            </select>
            <svg>
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>
    </div>
    <div class="product__catalog">

        @foreach ($products as $product)
            <article class="product__item" @if ($loop->last) id="last_record" @endif>
                <?php
                $foundMedia = false; // Variable to track if matching media is found
                $mediaPath = null; // Variable to store the media path
                ?>

                @foreach ($medias as $media)
                    @if ($media->item_id == $product->id)
                        <?php
                        $foundMedia = true;
                        $mediaPath = $media->extrenal ? $media->path : "/{$media->path}{$media->name}";
                        ?>
                    @break

                    // Exit the inner loop once a matching media is found
                @endif
            @endforeach

            @if ($foundMedia)
                <img src="{{ $mediaPath }}" alt="something wrong">
            @else
                <img src="/images/store/default/product.png" alt="something wrong">
            @endif

            <h3>{{ $product->name }}</h3>
            <p>{{ $product->short_description }}</p>
            <div class="product__item--price">
                {{-- <span class="deleted">99,99 lei</span>
                    <span>89,99 lei</span> --}}
                <span>99,99</span>
            </div>
            {{-- <span class="percent">-10%</span> --}}
            <div class="product__item--buttons">
                <button class="product__item--btn">Buy now</button>
                <button class="product__item--btn">
                    <svg>
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                        </path>
                    </svg>
                </button>
            </div>
            <button class="product__item--heart">
                <svg>
                    <path
                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                    </path>
                </svg>
            </button>
        </article>
    @endforeach
</div>
{{-- script for lazy load --}}
<script>
    const lastRecord = document.getElementById('last_record');
    const options = {
        root: null,
        threshold: 1,
        rootMargin: '0px'
    }
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                @this.loadMore()
            }
        });
    });
    observer.observe(lastRecord);
</script>
{{-- end script --}}
</div>
