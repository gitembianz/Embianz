<div class="product__container">
	<!------------------------------------------------------>
	<!------------------ Product (Details) ----------------->
	<?php if ($product->product_prices->count() != 0) {
	    $price = number_format($product->product_prices->first()->value, 2, ",", ".");
	    $discount = $product->product_prices->first()->discount != 0 ? true : false;
	    $currency = $product->product_prices->first()->pricelist->currency->name;
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
		<span>Pret</span>
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
				Pret Indisponibil
			@endif
		@endif
	</div>
	@if ($price)
		<span class="product__tva">
			Pretul include taxa TVA de
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
		<button wire:click="addToCart({{ $product->id }})" class="product__button" onclick="flyToCart(this)" aria-label="Add to cart button">Adauga
			in coș</button>
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
						<th>Specificatii </th>
						<th>Descriere</th>
					</tr>
				</thead>
				<tbody>
					@if ($product->product_specs->first() !== null)
						@foreach ($product->product_specs as $spec)
							<tr>
								<td>{{ $spec->spec->name }}</td>
								<td>{{ $spec->value }}</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="2">Nu exista specificatii pentru acest product</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
	<!------------------ End Tab (Details) ----------------->
	<!------------------------------------------------------>
</div>
