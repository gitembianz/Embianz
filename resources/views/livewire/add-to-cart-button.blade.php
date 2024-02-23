<div class="card-button">
	@if ($product->quantity != 0)
		<button aria-label="Add to cart button" class="add-to-cart" onclick="flyToCart(this)" wire:click="addToCart({{ $product->id }})">Adauga in
			coș</button>
	@else
		<button class="card-button-disabled" aria-label="Disabled Add to cart button">Indisponibil</button>
	@endif
</div>
