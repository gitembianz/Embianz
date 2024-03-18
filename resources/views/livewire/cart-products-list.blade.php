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
							<span class="leftbar__link--quantity">
								{{ $cartItem->quantity }} x
							</span>
							@if ($cartItem->product->media->first())
								<img class="cart__list--img" src="/{{ $cartItem->product->media->first()->path }}{{ $cartItem->product->media->first()->name }}" alt="{{ $cartItem->product->media->first()->name }}{{ $cartItem->product->name }}">
							@else
								<img class="cart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
							@endif

							<div class="leftbar__link--text">
								<h4 class="leftbar__link--title">{{ $cartItem->product->name }}</h4>
								<span class="leftbar__link--price">
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
				<h5 class="leftbar__total--text">Total: <span id="leftbarTotalPrice">{{ number_format($total, 2, ",", ".") }}
						{{ $currency }}</span></h5>
				<a class="leftbar__button leftbar__button--long" id="headerContinue" wire:click.prevent="continue">Finalizare Comanda</a>
			</div>

			<script>
				document.getElementById('headerContinue').addEventListener('click', function() {
					let productsList = [];
					let products = document.querySelectorAll('.leftbar__item');
					let total = parseFloat(document.getElementById('leftbarTotalPrice').innerText.replace('RON', '').trim()); // Extrage totalul comenzii și converteste-l la float

					products.forEach(function(product) {
						let productName = product.querySelector('.leftbar__link--title').innerText; // Extrage numele produsului
						let productPrice = parseFloat(product.querySelector('.leftbar__link--price').innerText.replace('RON', '').trim()); // Extrage pretul produsului și converteste-l la float
						let productQuantity = parseInt(product.querySelector('.leftbar__link--quantity').innerText); // Extrage cantitatea produsului și converteste-l la int

						productsList.push(productName + ' --- ' + productQuantity + 'buc --- ' + productPrice);
					});

					// Adaugă informațiile în dataLayer
					window.dataLayer = window.dataLayer || [];
					window.dataLayer.push({
						'event': 'addToCart',
						'products': productsList,
						'total': total,
						'event': 'continueToCheckout'
					});
				});
			</script>
		@endif
	</div>
</div>
