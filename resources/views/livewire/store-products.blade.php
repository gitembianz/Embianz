<div class="products">
    {{-- <x-loading /> --}}
    @if ($category)
        <div>
            <p>Category: {{ $categoryname }}</p>
        </div>
    @endif
    <div class="products__control">
        <div class="filter">
            {{-- Filter Button Left overs --}}
            <button class="filter__open" id="filterOpen" wire:click="$toggle('property')">
                Filters
                <svg>
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
            </button>
            {{-- Filter Content --}}
            <div class="filter__content @if ($property) show @endif">
                {{-- {{ $this->getUniqueSpecValues() }} --}}
                @foreach ($specification as $index => $spec)
                    @if ($spec->product_spec->count() > 0)
                        <div class="filter__dropdown">
                            <button class="filter__dropdown--btn">
                                {{ $spec->name }}
                            </button>
                            <div class="filter__dropdown--content show">
                                <ul class="filter__list">
                                    @foreach ($this->getUniqueSpecValues($spec->id) as $innerIndex => $uniqueValue)
                                        <li class="filter__item">
                                            <input type="checkbox"
                                                wire:model="selectedSpecValues.{{ $innerIndex }}.{{ $uniqueValue }}"
                                                id="{{ $innerIndex }}_{{ $uniqueValue }}">
                                            <label
                                                for="{{ $innerIndex }}_{{ $uniqueValue }}">{{ $uniqueValue }}</label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                @endforeach
                <div class="filter__buttons">
                    <button wire:click="applyFilter">Apply</button>
                    <button wire:click="resetFilter">Reset</button>
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
    </div>
    @if (!empty($selectedSpecNames))
        <ul class="filter__applied">
            {{-- {{ $selectedSpecNames }} --}}
            @foreach ($selectedSpecNames as $key => $name)
                {{-- {{ $name }} --}}
                <li>
                    <button class="filter__applied--item">
                        {{ $name }}: {{ $key }}
                        <svg wire:click="removeSpec('{{ $key }}')">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </li>
            @endforeach

            {{-- <li>
                <button class="filter__applied--clear">
                    Load more...
            </li> --}}
            <li>
                <button wire:click="clearall()" class="filter__applied--clear">
                    Clear all
                </button>
            </li>
        </ul>
    @endif
    <div class="product__catalog">
        @if ($products->isEmpty())
            <p>No products found</p>
        @else
            @foreach ($products as $product)
                <article class="product__item" @if ($loop->last) id="last_record" @endif>
                    <a href="/product/{{ $product->id }}">
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
                    </a>
                    <div class="product__item--bundle">
                        <h4>{{ $product->name }}</h4>
                        <div class="product__item--buttons">
                            <div class="product__item--price">
                                <p>
                                    @if ($product->product_prices->first())
                                        {{ $product->product_prices->first()->pricelist->currency->name }}
                                        {{ $product->product_prices->first()->value }}
                                    @else
                                        {{ __('no price') }}
                                    @endif
                                </p>
                            </div>
                            <?php $price = $product->product_prices->first()->value; ?>
                            @if ($price && $product->quantity != 0)
                                <button wire:click="addToCart({{ $product->id }})" class="product__item--btn"
                                    aria-label="product cart">
                                    <svg>
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                        </path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="product__item--header">
                        @if ($price)
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
                        @else
                            <p class="product__item--stock">
                                Comming soon!
                            </p>
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
            @endforeach
        @endif
    </div>
    <x-lazy />
</div>
