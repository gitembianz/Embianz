<div wire:scroll="loadMore">

	<!-- Acesta este Store Products (Catalogol Magazinului), acesta
				are sistemul de filtre, card-uri, si stilul Catalogului -->

	<!---------------------------------------------------------->
	<!------------------------Breadcrumbs----------------------->
	<div class="breadcrumbs container">
		<a class="breadcrumbs__link" href="{{ url("/") }}">
			Acasa
		</a>
		<a class="breadcrumbs__link" href="{{ url("/storeproducts") }}">
			Produse
		</a>
		<!-------------------If Category is appear------------------>
		@if ($category)
			<a class="breadcrumbs__link">{{ $category_details->name }}</a>
		@endif
		<!-----------------End If Category is appear---------------->
	</div>
	<!----------------------End Breadcrumbs--------------------->
	<!---------------------------------------------------------->
	<!----------------------Categorie + detalii--------------------->
	@if ($category)
		<section class="section__header container">
			<h1 class="section__title">{{ $category_details->name }}</h1>
			<p class="section__text">
				{!! $category_details->long_description !!}
			</p>
		</section>
	@endif
	<!----------------------End Categorie + detalii--------------------->

	<!---------------------------------------------------------->
	<!---------------------------Filter------------------------->
	<section class="controls container">
		<button class="controls__button" id="filterOpen" wire:click="$set('showspecfilter', true)" aria-label="Open filter button">
			<svg>
				<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
			</svg>
		</button>
		<input class="controls__search" type="text" wire:model="search" placeholder="Cauta produsul aici...">
		<button class="controls__button" id="sortOpen" aria-label="Open sort button">
			<svg>
				<line x1="21" y1="10" x2="7" y2="10"></line>
				<line x1="21" y1="6" x2="3" y2="6"></line>
				<line x1="21" y1="14" x2="3" y2="14"></line>
				<line x1="21" y1="18" x2="7" y2="18"></line>
			</svg>
		</button>
	</section>
	<!-------------------------End c----------------------->
	<!---------------------------------------------------------->
	<!----------------------Categorie + detalii--------------------->
	<!---------------------------- Tags-------------------------->
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
	<!--------------------------End  Tags------------------------>
	<!---------------------------------------------------------->
	<!-------------------------Catalogue------------------------>
	<section class="catalogue container">
		@if ($products->isEmpty())
			<p>Nu au fost produse gasite</p>
		@else
			@foreach ($products as $index => $product)
				<div class="product">
					<div @if ($loop->last) id="last_record" @endif class="card" role="listitem">
						<a href="{{ route("product", ["product" => $product->seo_id !== null && $product->seo_id !== "" ? $product->seo_id : $product->id]) }}">
							@if ($product->media->first() != null)
								<img class="card-image" src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}" alt="{{ $product->media->first()->name }} {{ $product->name }}">
							@else
								<img class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
							@endif
						</a>
						<?php if ($product->product_prices->count() != 0) {
						    $price = number_format($product->product_prices->first()->value, 2, ",", ".");
						    $discount = $product->product_prices->first()->discount != 0 ? true : false;
						} else {
						    $price = null;
						    $discount = false;
						}
						?>

						@if ($price)
							{{-- Out- negru // save - rosu --}}
							@if ($product->quantity < $quantity && $product->quantity > 0)
								<p class="card-status out">
									Stock limitat!
								</p>
								@if ($discount)
									<p class="card-status save-secondary">
										-{{ $product->product_prices->first()->discount }}%
									</p>
								@endif
							@elseif($product->quantity == 0)
								<p class="card-status save">
									Produs indisponibil!
								</p>
							@else
								@if ($discount)
									<p class="card-status save">
										-{{ $product->product_prices->first()->discount }}%
									</p>
								@endif
							@endif
							{{-- tagul de discount --}}
						@else
							<p class="card-status save">
								În curând!
							</p>
						@endif
						@livewire(
						    "product-wishlist-button",
						    [
						        "productId" => $product->id,
						        "class" => "card__action",
						        "is_in_wishlist" => $product->wishlists->isNotEmpty(),
						    ],
						    key($product->id)
						)

						<div class="card-info">
							<div class="card-text">
								<span>{{ $product->short_description }}</span>
							</div>
							<div class="card-text">
								<h3>{{ $product->name }}</h3>
								<p class="card-price">
									@if ($discount)
										<span class="card-price discount">
											@if ($product->product_prices->first())
												{{ $price }}
												{{ $product->product_prices->first()->pricelist->currency->name }}
											@endif
										</span>
										<span class="card-price oldprice">
											{{ $product->product_prices->first()->rrp_value }}
											{{ $product->product_prices->first()->pricelist->currency->name }}
										</span>
									@else
										<span>
											@if ($product->product_prices->first())
												{{ $price }}
												{{ $product->product_prices->first()->pricelist->currency->name }}
											@endif
										</span>
									@endif

								</p>
							</div>
							@if ($price)
								@livewire("add-to-cart-button", ["product" => $product], key($product->id . $index))
							@else
								<button class="card-button-disabled" aria-disabled="disabled add to cart button">Indisponibil</button>
							@endif
						</div>
					</div>
				</div>
			@endforeach
			<x-lazy />
		@endif
	</section>

	@if ($products->count() >= $loadAmount)
		<section class="container">
			<button class="filter__apply" wire:click="loadMore" wire:loading.remove>Vezi mai mult!</button>
		</section>
	@endif
	<!-----------------------End Catalogue---------------------->
	<!---------------------------------------------------------->
	<!---------------------------Filter------------------------->
	<div class="filter @if ($showspecfilter) active @endif" id="filterList">
		<div class="filter__content" id="filterContent">
			<div class="filter__top">
				<button class="filter__apply" id="resetFilter" wire:click="resetFilter">
					Șterge Filtrele
					<svg>
						<polyline points="23 4 23 10 17 10"></polyline>
						<polyline points="1 20 1 14 7 14"></polyline>
						<path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
					</svg>
				</button>
				<button class="filter__reset" wire:click="$set('showspecfilter', false)" id="filterClose" href="#">
					<svg>
						<line x1="18" y1="6" x2="6" y2="18"></line>
						<line x1="6" y1="6" x2="18" y2="18"></line>
					</svg>
				</button>
			</div>
			<button class="filter__top filter__top--button" wire:click="$set('showspecfilter', false)">
				Afișează rezultate: <span>{{ $productCount }}</span>
			</button>
			<div wire:ignore class="filter__list">
				@foreach ($filtervalues->groupBy("spec_id") as $values)
					<div class="dropfilter">
						<div class="dropfilter__button">
							{{-- <div class="dropfilter__button--link"> --}}
							{{-- </div> --}}
							<button class="dropfilter__open" href="#">
								<h4>{{ $values->first()->spec->name }}</h4>
								<svg>
									<polyline points="6 9 12 15 18 9"></polyline>
								</svg>
							</button>
						</div>
						<div class="dropfilter__list">
							@foreach ($values as $value)
								<label class="dropfilter__link" for="{{ $value->id }}{{ $value->value }}">
									<input type="checkbox" wire:model="selectedSpecValues.{{ $value->spec_id }}.{{ $value->value }}" wire:change="applyFilter" id="{{ $value->id }}{{ $value->value }}">
									<h4>{{ $value->value }}</h4>
								</label>
							@endforeach
						</div>
					</div>
				@endforeach
				<!-------------------- Dropdown (filter) -------------------->
			</div>
		</div>
		<button class="filter__close-modal" wire:click="$set('showspecfilter', false)"></button>
	</div>
	<!-------------------------End Filter----------------------->
	<!---------------------------------------------------------->
	<!-------------------------Asortiment----------------------->
	<div class="filter" id="sortList">
		<div class="filter__content" id="sortContent">
			<div class="filter__top">
				<div class="filter__text--long">
					Ordonează după:
				</div>
				<button class="filter__reset" id="sortClose" href="#">
					<svg>
						<line x1="18" y1="6" x2="6" y2="18"></line>
						<line x1="6" y1="6" x2="18" y2="18"></line>
					</svg>
				</button>
			</div>
			<div class="filter__list">

				<input class="filter__input" wire:model="orderBy" type="radio" name="sort" value="best_selling" id="sort">
				<label class="filter__link sort__item" for="sort">
					<h4>Cele mai vandute</h4>
				</label>

				<input class="filter__input" wire:model="orderBy" type="radio" name="sort1" value="price_as" id="sort1">
				<label class="filter__link sort__item" for="sort1">
					<h4>Pret crescator</h4>
				</label>

				<input class="filter__input" wire:model="orderBy" type="radio" name="sort2" value="price_ds" id="sort2">
				<label class="filter__link sort__item" for="sort2">
					<h4>Pret descrescator</h4>
				</label>

				<input class="filter__input" wire:model="orderBy" type="radio" name="sort3" value="quantity" id="sort3">
				<label class="filter__link sort__item" for="sort3">
					<h4>Disponibilitate (stock descrescator)</h4>
				</label>

				<input class="filter__input" wire:model="orderBy" type="radio" name="sort8" value="quantity_as" id="sort8">
				<label class="filter__link sort__item" for="sort8">
					<h4>Disponibilitate (stock crescator)</h4>
				</label>

				<input class="filter__input" wire:model="orderBy" type="radio" name="sort4" value="name_az" id="sort4">
				<label class="filter__link sort__item" for="sort4">
					<h4>Alfabetic, A-Z</h4>
				</label>

				<input class="filter__input" wire:model="orderBy" type="radio" name="sort5" value="name_za" id="sort5">
				<label class="filter__link sort__item" for="sort5">
					<h4>Alfabetic, Z-A</h4>
				</label>

				<input class="filter__input" wire:model="orderBy" type="radio" name="sort6" value="date_old_new" id="sort6">
				<label class="filter__link sort__item" for="sort6">
					<h4>Data, de la vechi la nou</h4>
				</label>

				<input class="filter__input" wire:model="orderBy" type="radio" name="sort7" value="date_new_old" id="sort7">
				<label class="filter__link sort__item" for="sort7">
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
