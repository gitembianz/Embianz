<div class="product__container">
	<!------------------------------------------------------>
	<!------------------ Product (Details) ----------------->
	<?php if ($product->product_prices->count() != 0) {
	    $price = number_format($product->product_prices->first()->value, 2, ",", ".");
	    $discount = $product->product_prices->first()->discount != 0 ? true : false;
	    $currency = $product->product_prices->first()->pricelist->currency->symbol;
	} else {
	    $price = null;
	    $discount = false;
	}
	?>
	<div class="product__text">
		<div>
			<span class="product__subtitle">{{ $product->short_description }}</span>
			<h1 class="product__title">{{ $product->name }}</h1>
			@if ($discount)
				<span class="product__discount">-{{ $product->product_prices->first()->discount }}%</span>
			@endif

		</div>
		@livewire("product-wishlist-button", [
		    "productId" => $product->id,
		    "class" => "product__action",
		    "is_in_wishlist" => $product->wishlists->where("session_id", $this->session_id)->where("product_id", $product->id)->first()
		        ? true
		        : false,
		])
	</div>
	<div class="product__price">
		<span class="product__price--title">Preț</span>
		@if ($discount && $price)
			@if ($price)
				<div class="product__price--discount">
					<span class="product__price--oldprice">{{ $product->product_prices->first()->rrp_value }}{{ $currency }}</span>
					<span class="product__price--newprice">{{ $price }}
						{{ $currency }}</span>
				</div>
			@endif
		@else
			@if ($price)
				{{ $price }}
				{{ $currency }}
			@else
				Preț Indisponibil
			@endif
		@endif
	</div>
	@if ($price)
		<span class="product__tva">
			Prețul include taxa TVA de
			{{ number_format($product->product_prices->first()->tva_percent, 2, ",", ".") }}%
		</span>
		<div class="quantity">
			<span>Cantitate</span>
			<div class="quantity__buttons">
				<button class="quantity__arrow" wire:click="decrementCounter" aria-label="Decrement quantity">
					<svg>
						<circle cx="12" cy="12" r="10"></circle>
						<line x1="8" y1="12" x2="16" y2="12"></line>
					</svg>
				</button>
				<span class="quantity__input" name="count" id="count">
					{{ $quantity }}
				</span>
				<button class="quantity__arrow" wire:click="incrementCounter" aria-label="Increment quantity">
					<svg>
						<circle cx="12" cy="12" r="10"></circle>
						<line x1="12" y1="8" x2="12" y2="16"></line>
						<line x1="8" y1="12" x2="16" y2="12"></line>
					</svg>
				</button>
			</div>

		</div>
	@endif
	@if ($maxlimit)
		<span>Cantitatea maxima a produsului este {{ $limit }}</span>
	@endif
	@if ($price && $product->quantity != 0)
		<button class="card__button" style="width: 100%;height: 40px;" onclick="flyToCart(this)" aria-label="Add to cart button" wire:click="addToCart({{ $product->id }})" wire:ignore="$refresh">
			<div class="card__button--cart">
				<svg>
					<circle cx="9" cy="21" r="1"></circle>
					<circle cx="20" cy="21" r="1"></circle>
					<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
				</svg>
			</div>
			<div class="card__button--gift">
				<svg>
					<polyline points="20 12 20 22 4 22 4 12"></polyline>
					<rect x="2" y="7" width="20" height="5"></rect>
					<line x1="12" y1="22" x2="12" y2="7"></line>
					<path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
					<path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
				</svg>
			</div>
			<span class="card__button--text"> Adaugă în coș </span>
		</button>
	@else
		<button class="card-button-disabled" aria-label="Disabled Add to cart button">Indisponibil</button>
	@endif
	<!---------------- End Product (Details) --------------->
	<!------------------------------------------------------>
	<!-------------------- Tab (Details) ------------------->
	<div class="tab">
		<div class="tab__top">
			<button class="tab__button @if ($activeTab === 0) active @endif" wire:click="switchTab(0)">Descriere</button>
			<button class="tab__button @if ($activeTab === 1) active @endif" wire:click="switchTab(1)">Detalii</button>
		</div>
		<div class="tab__content @if ($activeTab === 0) active @endif">
			<p class="tab__info">{!! $product->long_description !!}</p>
		</div>
		<div class="tab__content @if ($activeTab === 1) active @endif">
			<table class="tab__table">
				<thead>
					<tr>
						<th>Specificații </th>
						<th>Descriere</th>
					</tr>
				</thead>
				<tbody>
					@if ($product->product_specs->first() !== null)
						@foreach ($product->product_specs->sortBy("sequence") as $spec)
							<tr>
								<td>{{ $spec->spec->name }}</td>
								<td>{{ $spec->value }}</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="2">Nu exista specificații pentru acest product</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
	<!------------------ End Tab (Details) ----------------->
	<!------------------------------------------------------>
	{{-- <script>
		let addToCartButton = document.querySelector('.card__button');
		let addToWishButton = document.querySelector('.favorite__btn');
		let cardName = document.querySelector('.product__title').innerText.trim();
		let cardPrice = document.querySelector('.product__price--title').innerText.trim();

		addToCartButton.addEventListener('click', function() {
			if (typeof dataLayer !== 'undefined' && cardName && cardPrice) {
				dataLayer.push({
					'event': 'adaugareInCos',
					'cardName': cardName,
					'cardPrice': cardPrice
				});
			}
		});

		addToWishButton.addEventListener('click', function() {
			if (typeof dataLayer !== 'undefined' && cardName && cardPrice) {
				dataLayer.push({
					'event': 'adaugareInFavorite',
					'cardName': cardName,
					'cardPrice': cardPrice
				});
			}
		});
	</script> --}}
</div>
