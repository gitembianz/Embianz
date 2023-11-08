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
                Filtre
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
                                                wire:model.defer="selectedSpecValues.{{ $innerIndex }}.{{ $uniqueValue }}"
                                                wire:key="checkbox-{{ $innerIndex }}-{{ $uniqueValue }}"
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
                    <button wire:click.prevent="applyFilter">Aplica</button>
                    <button wire:click="resetFilter">Reseteaza</button>
                </div>
            </div>
        </div>
        <div class="filter__search">
            <input type="text" wire:model="search" placeholder="Cauta...">
            <button aria-label="search button">
                <svg>
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </div>
        <div class="filter__sort">
            <button class="filter__sort--btn">Sortare
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
                    Elimina toate filtrele
                </button>
            </li>
        </ul>
    @endif
    <div class="product__catalog">
        @if ($products->isEmpty())
            <p>Nu au fost produse gasite</p>
        @else
            @foreach ($products as $product)
                <div class="card" role="listitem">
                    <a href="/product/{{ $product->id }}">
                        @if (count($product->media) > 0)
                            @foreach ($product->media as $media)
                                @if ($media->location->location == 'main')
                                    @if ($media->external)
                                        <img class="card-image" src="{{ $media->path }}" draggable="false"
                                            alt="{{ $media->path }}">
                                    @else
                                        <img class="card-image" src="/{{ $media->path }}{{ $media->name }}"
                                            draggable="false" alt="{{ $media->path }}">
                                    @endif
                                    <?php break; ?>
                                @endif
                            @endforeach
                        @else
                            <img src="/images/store/default/default.svg" draggable="false" alt="something wrong">
                        @endif
                    </a>
                    <?php $price = $product->product_prices->first()->value; ?>
                    @if ($price)
                        {{-- Out- negru // save - rosu --}}
                        @if ($product->quantity < $quantity && $product->quantity > 0)
                            <p class="card-status out">
                                Ultimele produse!
                            </p>
                        @elseif($product->quantity == 0)
                            <p class="card-status save">
                                Produs indisponibil!
                            </p>
                        @else
                            <p></p>
                        @endif
                    @else
                        <p class="card-status save">
                            În curând!
                        </p>
                    @endif
                    {{-- <img class="card-image" src="https://24bottles.com/cdn/shop/products/1496_01_590x.png?v=1644250387" --}}
                    {{-- alt="Card-Image"> --}}
                    <button class="card-favorites @if ($product->wishlists->where('session_id', $session_id)->isNotEmpty()) active @endif"
                        wire:click="toggleWishlist({{ $product->id }})">
                        <svg viewBox="0 0 512 512" width="20" title="heart">
                            <path
                                d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z" />
                        </svg>
                    </button>
                    <div class="card-info">
                        <div class="card-text">
                            <span>{{ $product->product_categories->first()->category->name }}</span>
                            {{-- <span>600ml</span> --}}
                        </div>
                        <div class="card-text">
                            <h3>{{ $product->name }}</h3>
                            <p>
                                @if ($product->product_prices->first())
                                    {{ $product->product_prices->first()->pricelist->currency->name }}
                                    {{ $price }}
                                @else
                                    {{ __('no price') }}
                                @endif
                            </p>
                        </div>

                        @if ($price && $product->quantity != 0)
                            <a class="card-button" wire:click="addToCart({{ $product->id }})">Adauga in coș</a>
                        @endif

                    </div>
                </div>
            @endforeach

            <x-lazy />
        @endif
    </div>
</div>
