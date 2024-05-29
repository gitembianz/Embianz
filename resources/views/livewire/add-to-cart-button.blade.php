<div class="card__button--wrapper">
	@if ($product->quantity != 0)
		<button class="card__button" onclick="flyToCart(this)" wire:click="addToCart({{ $product->id }})" wire:ignore="$refresh">
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
</div>
