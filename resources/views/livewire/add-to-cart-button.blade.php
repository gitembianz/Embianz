<div class="card-button">
    @if ($product->quantity != 0)
        <a class="add-to-cart" onclick="flyToCart(this)" wire:click="addToCart({{ $product->id }})">Adauga in
            coș</a>
    @else
        <a class="card-button-disabled">Indisponibil</a>
    @endif
</div>
