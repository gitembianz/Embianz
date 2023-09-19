<div class="products">
    <div class="products__control">
        <div class="filter">
            {{-- Filter Button --}}
            <button class="filter__open" id="filterOpen" wire:click="$toggle('property')">
                Filters
                <svg>
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
            </button>
            {{-- Filter Content --}}
            <div class="filter__content @if ($property) show @endif">
                @foreach ($specification as $spec)
                    <div class="filter__dropdown">
                        <button class="filter__dropdown--btn">
                            {{ $spec->name }}
                            <svg>
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="filter__dropdown--content">
                            <ul class="filter__list">
                                @foreach ($spec->product_spec as $index => $value)
                                    <li class="filter__item">
                                        <input type="checkbox" name="size" id="filter {{ $index }}">
                                        <label for="filter {{ $index }}">{{ $value->value }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
                {{-- <div class="filter__dropdown">
                    <button class="filter__dropdown--btn">
                        size
                        <svg>
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="filter__dropdown--content">
                        <ul class="filter__list">
                            <li class="filter__item">
                                <input type="checkbox" name="size" id="filter2">
                                <label for="filter2">250 ml</label>
                            </li>
                            <li class="filter__item">
                                <input type="checkbox" name="size" id="filter3">
                                <label for="filter3">500 ml</label>
                            </li>
                            <li class="filter__item">
                                <input type="checkbox" name="size" id="filter4">
                                <label for="filter4">1000 ml</label>
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
                                <input type="checkbox" name="size" id="filter5">
                                <label for="filter5">blue</label>
                            </li>
                            <li class="filter__item">
                                <input type="checkbox" name="size" id="filter">
                                <label for="filter">red</label>
                            </li>
                        </ul>
                    </div>
                </div> --}}
                <div class="filter__buttons">
                    <button>Apply</button>
                    <button>Reset</button>
                </div>
            </div>
        </div>
        <div class="filter__search">
            <input type="text" wire:model="search" placeholder="Search...">
            <button aria-label="search button">
                <svg>
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
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
                        <input wire:model="orderBy" type="radio" name="sort" value="best_selling" id="sort2">
                        <label for="sort2">Best selling</label>
                    </li>
                    <li class="filter__sort--item">
                        <input wire:model="orderBy" type="radio" name="sort" value="name_az" id="sort3">
                        <label for="sort3">Alphabetically, A-Z</label>
                    </li>
                    <li class="filter__sort--item">
                        <input wire:model="orderBy" type="radio" name="sort" value="name_za" id="sort4">
                        <label for="sort4">Alphabetically, Z-A</label>
                    </li>
                    <li class="filter__sort--item">
                        <input wire:model="orderBy" type="radio" name="sort" value="date_old_new" id="sort7">
                        <label for="sort7">Date, old to new</label>
                    </li>
                    <li class="filter__sort--item">
                        <input wire:model="orderBy" type="radio" name="sort" value="date_new_old" id="sort8">
                        <label for="sort8">Date, new to old</label>
                    </li>
                </ul>
            </div>
        </div>

        {{-- <li class="filter__sort--item">
                        <input wire:model="orderBy.price_low_high" type="checkbox" name="sort[]"
                            value="price_low_high" id="sort5">
                        <label for="sort5">Price, low to high</label>
                    </li>
                    <li class="filter__sort--item">
                        <input wire:model="orderBy.price_high_low" type="checkbox" name="sort[]"
                            value="price_high_low" id="sort6">
                        <label for="sort6">Price, high to low</label>
                    </li> --}}

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
    <div class="product__catalog">
        @if ($products->isEmpty())
            <p>No products found</p>
        @else
            @foreach ($products as $product)
                {{-- <a href="/product/{{ $product->id }}'"> --}}
                <article class="product__item" @if ($loop->last) id="last_record" @endif>
                    @if (count($product->media) > 0)
                        @foreach ($product->media as $media)
                            @if ($media->location->location == 'main')
                                @if ($media->external)
                                    <img src="{{ $media->path }}" draggable="false" alt="{{ $media->path }}">
                                @else
                                    <img src="/{{ $media->path }}{{ $media->name }}" draggable="false"
                                        alt="{{ $media->path }}">
                                @endif
                                <?php break; ?>
                            @endif
                        @endforeach
                    @else
                        <img src="/images/store/default/default.svg" draggable="false" alt="something wrong">
                    @endif
                    <div class="product__item--bundle">
                        <h4>{{ $product->name }}</h4>
                        {{-- <span>1000ml</span> --}}
                        {{-- <p>{{ $product->short_description }}</p> --}}
                        <div class="product__item--buttons">
                            <div class="product__item--price">
                                <span>
                                    @if ($product->product_prices->first() !== null)
                                        {{ $product->product_prices->first()->value }}
                                        {{ $product->product_prices->first()->pricelist->currency->first()->name }}
                                    @else
                                        unavailable
                                    @endif
                                </span>
                            </div>
                            <button class="product__item--btn" aria-label="product cart">
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
                        @if ($product->quantity < $quantity && $product->quantity > 0)
                            <p class="product__item--stock">
                                Low stock!
                            </p>
                        @elseif($product->quantity == 0)
                            <p class="product__item--stock">
                                Out of stock!
                            </p>
                        @else
                            <p></p>
                        @endif
                        <button class="product__item--heart @if ($product->wishlists->where('session_id', $session_id)->isNotEmpty()) active @endif"
                            aria-label="add to favorites" wire:click="toggleWishlist({{ $product->id }})">
                            <svg>
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </article>
                {{-- </a> --}}
            @endforeach
        @endif
    </div>
    <x-lazy />
</div>
