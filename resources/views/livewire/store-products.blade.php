<div wire:scroll="loadMore">
    <!-- Acesta este Store Products (Catalogol Magazinului), acesta
    are sistemul de filtre, card-uri, si stilul Catalogului -->
    <!---------------------------------------------------------->
    <!------------------------Breadcrumbs----------------------->
    <section>
        <div class="breadcrumbs container">
            <a class="breadcrumbs__link" href="{{ url('/') }}">
                Acasa
            </a>
            <a class="breadcrumbs__link" href="{{ url('/storeproducts') }}">
                Produse
            </a>
            <!-------------------If Category is appear------------------>
            @if ($category)
                <a class="breadcrumbs__link">{{ $categoryname }}</a>
            @endif
            <!-----------------End If Category is appear---------------->

        </div>
    </section>
    <!----------------------End Breadcrumbs--------------------->
    <!---------------------------------------------------------->

    <!---------------------------------------------------------->
    <!---------------------------Filter------------------------->
    <section class="controls container">
        <button class="controls__button" id="filterOpen">
            <svg>
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
        </button>
        <input class="controls__search" type="text" wire:model="search" placeholder="Cauta produsul aici...">
        <button class="controls__button" id="sortOpen">
            <svg>
                <line x1="21" y1="10" x2="7" y2="10"></line>
                <line x1="21" y1="6" x2="3" y2="6"></line>
                <line x1="21" y1="14" x2="3" y2="14"></line>
                <line x1="21" y1="18" x2="7" y2="18"></line>
            </svg>
        </button>
    </section>
    <!-------------------------End Filter----------------------->
    <!---------------------------------------------------------->
    <!----------------------------Tags-------------------------->
    @if (!empty($selectedSpecNames))
        <section class="tag container">
            @foreach ($selectedSpecNames as $key => $name)
                <button class="tag__button" wire:click="removeSpec('{{ $key }}')">
                    {{ $name }}: {{ $key }}
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            @endforeach
            <button class="tag__button" wire:click="clearall()" class="filter__applied--clear">
                Elimina toate filtrele-ul
                <svg>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </section>
    @endif
    <!--------------------------End Tags------------------------>
    <!---------------------------------------------------------->
    <!-------------------------Catalogue------------------------>
    <section class="catalogue container">
        @if ($products->isEmpty())
            <p>Nu au fost produse gasite</p>
        @else
            @foreach ($products as $product)
                <div @if ($loop->last) id="last_record" @endif class="card" role="listitem">
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
                            <img class="card-image" src="/images/store/default/default.svg" draggable="false"
                                alt="something wrong">
                        @endif
                    </a>
                    <?php if ($product->product_prices->count() != 0) {
                        $price = $product->product_prices->first()->value;
                    } else {
                        $price = null;
                    }
                    ?>

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
                    {{-- alt="Card-Image"> --}}
                    @livewire('product-wishlist-button', ['product' => $product], key($product->id))

                    <div class="card-info">
                        <div class="card-text">
                            <span>{{ $product->short_description }}</span>
                        </div>
                        <div class="card-text">
                            <h3>{{ $product->name }}</h3>
                            <p>
                                @if ($product->product_prices->first())
                                    {{ $price }}
                                    {{ $product->product_prices->first()->pricelist->currency->name }}
                                @else
                                    {{ __('') }}
                                @endif
                            </p>
                        </div>

                        @if ($price && $product->quantity != 0)
                            <a class="card-button" wire:click="addToCart({{ $product->id }})">Adauga in coș</a>
                        @else
                            <a class="card-button-disabled" onclick="handleClick()">Indisponibil</a>
                        @endif

                    </div>
                </div>
            @endforeach
            <x-lazy />
        @endif
    </section>
    <!-----------------------End Catalogue---------------------->
    <!---------------------------------------------------------->
    <!---------------------------Filter------------------------->
    <div class="filter" id="filterList">
        <div class="filter__content" id="filterContent">
            <div class="filter__top">
                <button class="filter__close" id="filterClose" href="#">
                    Inchideti Filtrele
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="filter__list">
                <!------------------ End Dropdown (filter) ------------------>
                @foreach ($specification as $index => $spec)
                    @if ($spec->product_spec->count() > 0)
                        <div class="dropfilter">
                            <div class="dropfilter__button">
                                <div class="dropfilter__button--link">
                                    <h4>{{ $spec->name }}</h4>
                                </div>
                                <button class="dropfilter__open" href="#">
                                    <svg>
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                            </div>
                            <div class="dropfilter__list">
                                @foreach ($this->getUniqueSpecValues($spec->id) as $innerIndex => $uniqueValue)
                                    <label class="dropfilter__link" for="{{ $innerIndex }}_{{ $uniqueValue }}">
                                        <input type="checkbox"
                                            wire:model.defer="selectedSpecValues.{{ $innerIndex }}.{{ $uniqueValue }}"
                                            wire:key="checkbox-{{ $innerIndex }}-{{ $uniqueValue }}"
                                            id="{{ $innerIndex }}_{{ $uniqueValue }}">

                                        <h4>{{ $uniqueValue }}</h4>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
                <!-------------------- Dropdown (filter) -------------------->
            </div>
            <div class="filter__bottom">
                <button class="filter__apply" id="closeFilter" wire:click.prevent="applyFilter">
                    Aplica
                </button>
                <button class="filter__reset" id="resetFilter" wire:click="resetFilter">
                    <svg>
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-------------------------End Filter----------------------->
    <!---------------------------------------------------------->
    <!-------------------------Asortiment----------------------->
    <div class="filter" id="sortList">
        <div class="filter__content" id="sortContent">
            <div class="filter__top">
                <button class="filter__close" id="sortClose" href="#">
                    Inchideti
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="filter__list">
                <input class="filter__input" wire:model="orderBy" type="radio" name="sort"
                    value="best_selling" id="sort2">
                <label class="filter__link sort__item" for="sort2">
                    <h4>Cel mai bine vândut</h4>
                </label>
                <input class="filter__input" wire:model="orderBy" type="radio" name="sort" value="name_az"
                    id="sort3">
                <label class="filter__link sort__item" for="sort3">
                    <h4>Alfabetic, A-Z</h4>
                </label>
                <input class="filter__input" wire:model="orderBy" type="radio" name="sort" value="name_za"
                    id="sort4">
                <label class="filter__link sort__item" for="sort4">
                    <h4>Alfabetic, Z-A</h4>
                </label>
                <input class="filter__input" wire:model="orderBy" type="radio" name="sort"
                    value="date_old_new" id="sort7">
                <label class="filter__link sort__item" for="sort7">
                    <h4>Data, de la vechi la nou</h4>
                </label>
                <input class="filter__input" wire:model="orderBy" type="radio" name="sort"
                    value="date_new_old" id="sort8">
                <label class="filter__link sort__item" for="sort8">
                    <h4>Data, de la nou la vechi</h4>
                </label>
            </div>
        </div>
    </div>
    <!-----------------------End Asortiment--------------------->
    <!---------------------------------------------------------->
    <!---------------------------------------------------------->
    <!--------------------- support button --------------------->
    <x-help-button />
    <!------------------- End support button ------------------->
    <!---------------------------------------------------------->
    <script src="/script/store/catalog.js" async defer></script>
</div>
