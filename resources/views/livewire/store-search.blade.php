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
	</div>
	<!---------------------------------------------------------->
  <section class="controls container controls--search">
    <input class="controls__search" type="text" maxlength="100" autocomplete="off" name="search" id="search" wire:model="search" placeholder="Caută produse sau categorii...">
    <h2 class="section__title">Rezultatele căutării:</h2>
    <div>
      <button class="tab__button tab__button--long @if ($showproducts) active @endif" wire:click="toggle('products')">
        Produse (@if ($products->isNotEmpty())

		{{ $products->total() }}
		@else
		0
		@endif)
      </button>
      <button class="tab__button tab__button--long @if ($showcategories) active @endif" wire:click="toggle('categories')">
        Categorii (@if ($categories->isNotEmpty())

		{{ $categories->total() }}
		@else
		0
		@endif)
      </button>
    </div>
    </section>
    @if ($showproducts)
	<section class="catalogue container">
		@if ($products->isEmpty())
			<p>Nu au fost găsite produse</p>
		@else
			@foreach ($products as $index => $product)
				<div class="product">
					<div @if ($loop->last) id="last_record" @endif class="card" >
						<a href="{{ route("product", ["product" => $product->seo_id !== null && $product->seo_id !== "" ? $product->seo_id : $product->id]) }}">
							@if ($product->media->first() != null)
								<img loading="lazy" class="card-image" src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}" alt="{{ $product->media->first()->name }} {{ $product->name }}">
							@else
								<img loading="lazy" class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
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
												{{ $product->product_prices->first()->pricelist->currency->symbol }}
											@endif
										</span>
										<span class="card-price oldprice">
											{{ $product->product_prices->first()->rrp_value }}
											{{ $product->product_prices->first()->pricelist->currency->symbol }}
										</span>
									@else
										<span>
											@if ($product->product_prices->first())
												{{ $price }}
												{{ $product->product_prices->first()->pricelist->currency->symbol }}
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

    @endif

    @if ($showcategories)
        <section class="catalogue container catalogue--categories">
		@if ($categories->isEmpty())
			<p>Nu au fost găsite categorii</p>
		@else
			@foreach ($categories as $index => $category)
				<div @if ($loop->last) id="last_record" @endif class="product">
					<a class="card card--category" role="listitem" href="{{ route("products", ["categorySlug" => $category->seo_id !== null && $category->seo_id !== "" ? $category->seo_id : $category->id]) }}">
						<div>
							@if ($category->media->first() != null)
								<img loading="lazy" class="card-image" src="/{{ $category->media->first()->path }}{{ $category->media->first()->name }}" alt="{{ $category->media->first()->name }} {{ $category->name }}">
							@else
								<img loading="lazy" class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
							@endif
						</div>
						<div class="card-info">
							<div class="card-text">
								<h3 class="card-title">{{ $category->name }}</h2>
							</div>
              {!! $category->long_description !!}
						</div>
					</a>
				</div>
			@endforeach
			<x-lazy />
		@endif
	</section>
    @endif
	@if ($products->count() > $loadAmount || $categories->count() > $loadAmount)
		<section class="container">
			<button class="filter__apply" wire:click="loadMore" wire:loading.remove>Vezi mai mult!</button>
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
