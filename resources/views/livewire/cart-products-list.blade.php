<div class="leftbar @if ($showcart) active @endif" id="basketList">
	<button class="leftbar__hidden--close" wire:click="$set('showcart', false)"></button>
	<div class="leftbar__content" id="basketContent">
		<div class="leftbar__top">
			<a class="leftbar__button" href="{{ url("/cart") }}">Vizualizare cos de cumparaturi </a>
			<button class="leftbar__close" id="basketClose" wire:click="$set('showcart', false)">
				<svg>
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>

		@if ($cartItems->isEmpty())
			<span class="leftbar__empty">Cosul de cumparaturi este gol</span>
		@else
			<?php $total = 0; ?>
			<ul class="leftbar__list">
				@foreach ($cartItems as $cartItem)
					<li class="leftbar__item">
						<a class="leftbar__link" href="{{ route("product", ["product" => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== "" ? $cartItem->product->seo_id : $cartItem->product->id]) }}">
							<span>
								{{ $cartItem->quantity }} x
							</span>
							@if ($cartItem->product->media->first())
								<img class="cart__list--img" src="/{{ $cartItem->product->media->first()->path }}{{ $cartItem->product->media->first()->name }}" alt="{{ $cartItem->product->media->first()->name }}{{ $cartItem->product->name }}">
							@else
								<img class="cart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
							@endif

							<div class="leftbar__link--text">
								<h4>{{ $cartItem->product->name }}</h4>
								<span>
									@php
										$price = number_format($cartItem->product->product_prices->first()->value, 2, ",", ".");
										$total = $total + $cartItem->quantity * $cartItem->product->product_prices->first()->value;
									@endphp
									@if ($price)
										{{ $price }} {{ $currency }}
									@else
										indisponibil
									@endif

								</span>
							</div>
						</a>
						<button class="leftbar__delete" type="button" wire:click="removeFromCart({{ $cartItem->product->id }})">
							<svg>
								<line x1="18" y1="6" x2="6" y2="18"></line>
								<line x1="6" y1="6" x2="18" y2="18"></line>
							</svg>
						</button>
					</li>
				@endforeach
			</ul>

			<div class="leftbar__total">
				<h5 class="leftbar__total--text">Total: <span>{{ number_format($total, 2, ",", ".") }}
						{{ $currency }}</span></h5>
				<a class="leftbar__button leftbar__button--long" wire:click.prevent="continue">Finalizare Comanda</a>
			</div>
		@endif
	</div>
</div>
