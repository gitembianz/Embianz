<div class="card-button add-button">
    @if ($product->quantity != 0)
        <a wire:click="addToCart({{ $product->id }})">Adauga in coș</a>
    @else
        <a class="card-button-disabled" onclick="handleClick()">Indisponibil</a>
    @endif
</div>
