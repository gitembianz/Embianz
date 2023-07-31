<div class="products">
    <div class="products__control">
        <div class="filter">
            {{-- Filter Button --}}
            <button class="filter__open" id="filterOpen">
                Filters
                <svg>
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
            </button>
            {{-- Filter Content --}}
            <div class="filter__content">
                <button class="filter__close" id="filterClose" onclick="closeDrop()">
                    Filters
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <div class="filter__dropdown">
                    <button class="filter__dropdown--btn">
                        Quantity
                        <svg>
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="filter__dropdown--content">
                        <ul class="filter__list">
                            <li class="filter__item">
                                <input type="checkbox" name="size" id="filter">
                                <label for="filter">1200</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="filter__dropdown">
                    <button class="filter__dropdown--btn">
                        size
                        <svg>
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="filter__dropdown--content">
                        <ul class="filter__list">
                            <li class="filter__item">
                                <input type="checkbox" name="size" id="filter">
                                <label for="filter">250 ml</label>
                            </li>
                            <li class="filter__item">
                                <input type="checkbox" name="size" id="filter">
                                <label for="filter">500 ml</label>
                            </li>
                            <li class="filter__item">
                                <input type="checkbox" name="size" id="filter">
                                <label for="filter">1000 ml</label>
                            </li>
                            <li class="filter__item">
                                <input type="checkbox" name="size">
                                <label>250 ml</label>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="filter__dropdown">
                    <button class="filter__dropdown--btn">
                        Color
                        <svg>
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="filter__dropdown--content">
                        <ul class="filter__list">
                            <li class="filter__item">
                                <input type="checkbox" name="size" id="filter">
                                <label for="filter">blue</label>
                            </li>
                            <li class="filter__item">
                                <input type="checkbox" name="size" id="filter">
                                <label for="filter">red</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="filter__buttons">
                    <button>Apply</button>
                    <button>Reset</button>
                </div>
            </div>
        </div>
        <div class="filter__search">
            <input type="text" placeholder="Search...">
            <button><svg>
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg></button>
        </div>
        <div class="filter__sort">
            <button class="filter__sort--btn">Sort
                <svg>
                    <line x1="12" y1="20" x2="12" y2="10"></line>
                    <line x1="18" y1="20" x2="18" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="16"></line>
                </svg>
            </button>
            <div class="filter__sort--content">
                <ul class="filter__sort--list">
                    <li class="filter__sort--item">
                        <input type="checkbox" name="size" id="sort">
                        <label for="sort">Featured</label>
                    </li>
                    <li class="filter__sort--item">
                        <input type="checkbox" name="size" id="sort">
                        <label for="sort">Best selling</label>
                    </li>
                    <li class="filter__sort--item">
                        <input type="checkbox" name="size" id="sort">
                        <label for="sort">Alphabetically, A-Z</label>
                    </li>
                    <li class="filter__sort--item">
                        <input type="checkbox" name="size" id="sort">
                        <label for="sort">Alphabetically, Z-A</label>
                    </li>
                    <li class="filter__sort--item">
                        <input type="checkbox" name="size" id="sort">
                        <label for="sort">Price, low to high</label>
                    </li>
                    <li class="filter__sort--item">
                        <input type="checkbox" name="size" id="sort">
                        <label for="sort">Price, high to low</label>
                    </li>
                    <li class="filter__sort--item">
                        <input type="checkbox" name="size" id="sort">
                        <label for="sort">Date, old to new</label>
                    </li>
                    <li class="filter__sort--item">
                        <input type="checkbox" name="size" id="sort">
                        <label for="sort">Date, new to old</label>
                    </li>
                </ul>
            </div>
        </div>
        <ul class="filter__applied">
            <li>
                <button class="filter__applied--item">
                    Color: Red
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </li>
            <li>
                <button class="filter__applied--item">
                    Color: Blue
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </li>
            <li>
                <button class="filter__applied--item">
                    Color: Green
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </li>
            <li>
                <button class="filter__applied--clear">
                    Load more...
            </li>
            <li>
                <button class="filter__applied--clear">
                    Clear all
                </button>
            </li>

        </ul>
    </div>
    <div class="product__catalog">

        @foreach ($products as $product)
            <a href="/product/{{ $product->id }}'">
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
                <div class="product__item--bundle">
                    <h4>{{ $product->name }}</h4>
                    <span>1000ml</span>
                    <p>{{ $product->short_description }}</p>
                    <div class="product__item--buttons">
                        <div class="product__item--price">
                            <span>
                                @if ($product->product_prices->first() !== null)
                                    {{ $product->product_prices->first()->value }}
                                    {{ $product->product_prices->first()->pricelist->currency->first()->name }}
                                @else
                                    no price
                                @endif
                            </span>
                        </div>
                        <button class="product__item--btn">
                            <svg>
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="product__item--header">
                    <p class="product__item--stock">
                        Out of the stock!
                    </p>
                    <button class="product__item--heart">
                        <svg>
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                            </path>
                        </svg>
                    </button>
                </div>
            </article>
        </a>
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
