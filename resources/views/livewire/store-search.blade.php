<div wire:scroll="loadMore">

	<!-- Acesta este Store Products (Catalogol Magazinului), acesta
				are sistemul de filtre, card-uri, si stilul Catalogului -->

	<!---------------------------------------------------------->
	<!------------------------Breadcrumbs----------------------->
	<div class="breadcrumbs container">
		<a class="breadcrumbs__link" href="{{ url("/") }}">
			Acasă
		</a>
		@if (app()->has("global_show_on_breadcrumbs") && app('global_show_on_breadcrumbs') == 'true')
			
		<a class="breadcrumbs__link" href="{{ url("/search") }}">
			Cautare
		</a>
		@endif
		<!-------------------If Category is appear------------------>

		<!-----------------End If Category is appear---------------->
	</div>
	<!----------------------End Breadcrumbs--------------------->
	<!---------------------------------------------------------->
	<!----------------------Categorie + detalii--------------------->
{{-- 
		<section class="section__header container">
			<h1 class="section__title">Cauta:</h1>
			<p class="section__text">
				Description
			</p>
		</section> --}}

	<!----------------------End Categorie + detalii--------------------->

	<!---------------------------------------------------------->
	<!---------------------------Filter------------------------->
	<section class="controls container">

		<input class="controls__search" type="text" wire:model="search" placeholder="Caută produse sau categorii...">

	</section>
	<!-------------------------End c----------------------->
	<!---------------------------------------------------------->
	<!----------------------Categorie + detalii--------------------->
	<!---------------------------- Tags-------------------------->
<button wire:click="toggle('products')">
products
</button>
<button wire:click="toggle('categories')">
Categories
</button>
	<!--------------------------End  Tags------------------------>
	<!---------------------------------------------------------->
	<!-------------------------Catalogue------------------------>

    @if ($showproducts)
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
								<h3 class="card-title">{{ $product->name }}</h3>
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
		@endif
	</section>
    @endif

    @if ($showcategories)
        <section class="catalogue container">
		@if ($categories->isEmpty())
			<p>Nu au fost produse categorii</p>
		@else
			@foreach ($categories as $index => $category)
				<div class="product">
					<div class="card" role="listitem">
						<a href="{{ route("products", ["categorySlug" => $category->seo_id !== null && $category->seo_id !== "" ? $category->seo_id : $category->id]) }}">
							@if ($category->media->first() != null)
								<img class="card-image" src="/{{ $category->media->first()->path }}{{ $category->media->first()->name }}" alt="{{ $category->media->first()->name }} {{ $category->name }}">
							@else
								<img class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
							@endif
						</a>
					

					

						<div class="card-info">
							
							<div class="card-text">
								<h3 class="card-title">{{ $category->name }}</h3>
								
							</div>
<div class="card__button--wrapper">

								<a href="{{ route("products", ["categorySlug" => $category->seo_id !== null && $category->seo_id !== "" ? $category->seo_id : $category->id]) }}" class="card__button">
			
			<span class="card__button--text"> Vizualizează categoria </span>
                                </a>
</div>
						</div>
					</div>
				</div>
			@endforeach
		@endif
	</section>
    @endif

	{{-- <script>
		// Sending the special Event for Each card to GTM
		let cards = document.querySelectorAll('.card');

		cards.forEach(function(card) {
			let addToCartButton = card.querySelector('.card__button');
			let addToWishButton = card.querySelector('.favorite__btn');

			addToCartButton.addEventListener('click', function() {
				let cardName = card.querySelector('.card-title').innerText.trim();
				let cardPrice = card.querySelector('.card-price').innerText.trim();

				if (typeof dataLayer !== 'undefined' && cardName && cardPrice) {
					dataLayer.push({
						'event': 'adaugareInCos',
						'cardName': cardName,
						'cardPrice': cardPrice
					});
				}
			});

			addToWishButton.addEventListener('click', function() {
				let cardName = card.querySelector('.card-title').innerText.trim();
				let cardPrice = card.querySelector('.card-price').innerText.trim();

				if (typeof dataLayer !== 'undefined' && cardName && cardPrice) {
					dataLayer.push({
						'event': 'adaugareInFavorite',
						'cardName': cardName,
						'cardPrice': cardPrice
					});
				}
			});
		});
	</script> --}}

	{{-- @if ($categorys->count() >= $loadAmount)
		<section class="container">
			<button class="filter__apply" wire:click="loadMore" wire:loading.remove>Vezi mai mult!</button>
		</section>
	@endif --}}
	<!-----------------------End Catalogue---------------------->

	<!---------------------------------------------------------->
	<!--------------------- support button --------------------->
	<x-help-button />
	<!------------------- End support button ------------------->
	<!---------------------------------------------------------->
	<script src="/script/store/catalog.js" async defer></script>
</div>
