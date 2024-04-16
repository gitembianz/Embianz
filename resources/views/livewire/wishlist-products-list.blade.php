<div class="leftbar @if ($showwis) active @endif" id="wishList">
	<button class="leftbar__hidden--close" wire:click="$set('showwis', false)"></button>
	<div class="leftbar__content" id="wishContent">
		<div class="leftbar__top">
			<a class="leftbar__button" href="/wishlist">Vizualizare produse favorite</a>
			<button class="leftbar__close" wire:click="$set('showwis', false)" id="wishClose" href="#">
				<svg>
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>

		@if ($items->isEmpty())
			<span class="leftbar__empty">Nu sunt produse adăugate în lista de favorite</span>
		@else
		<?php
				$disables =[];
				?>
			<ul class="leftbar__list">
				@foreach ($items as $index => $item)
					<?php
				$disabled[$index] = false;
				if (($item->product->active != true) || ($item->product->start_date > now()->format('Y-m-d')) || ($item->product->end_date < now()->format('Y-m-d'))){
					$disabled[$index] = true;

				} ?>
					<li class="leftbar__item">

						<a class="leftbar__link" href="{{ route("product", ["product" => $item->product->seo_id !== null && $item->product->seo_id !== "" ? $item->product->seo_id : $item->product->id]) }}">
							@if ($item->product->media->first())
								<img loading="lazy" class="cart__list--img" src="/{{ $item->product->media->first()->path }}{{ $item->product->media->first()->name }}" alt="{{ $item->product->media->first()->name }} {{ $item->product->name }}">
							@else
								<img loading="lazy" class="heart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
							@endif

              <div class="leftbar__link--text">
								<h4 class="leftbar__link--title">{{ $item->product->name }}</h4>
								<span class="leftbar__link--price">
									@php
									$currency = $item->product->product_prices->first()->pricelist->currency->symbol;
										$price = number_format($item->product->product_prices->first()->value, 2, ",", ".");
									@endphp
									@if ($price)
										{{ $price }} {{ $currency }}
									@else
										Indisponibil
									@endif
								</span>
							</div>
						</a>
						@if ($item->product->quantity > 0 && $item->product->product_prices->first() !== null)
						<button class="leftbar__delete" style="border: none" wire:click="addToCart({{ $item->product->id }}, {{ $index }})" aria-label="add to cart">
							<svg>
							  <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
							  <line x1="3" y1="6" x2="21" y2="6"></line>
							  <path d="M16 10a4 4 0 0 1-8 0"></path>
							 </svg>
									  </button>
						@endif

						<button class="leftbar__delete" style="border: none" wire:click="removeFromWishlist({{ $item->product->id }})" aria-label="delete from wishlist">
              <svg><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
						</button>
						@if ($disabled[$index])
						<div class="item__product--disabled">
						  <span>Produs Indisponibil</span>
						  <button class="leftbar__delete" type="button" wire:click="removeFromWishlist({{ $item->product->id }})">
                <svg><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
						  </button>
						</div>
						@endif
					</li>
            @if ($message === $index)
              <p class="leftbar__message">Produsul a fost adăugat în coș!</p>
			  <script>
  setTimeout(function() {
    @this.removemessage();
  }, 2500);
</script>
            @endif
				@endforeach

			</ul>
		@endif
	</div>
</div>

