<div class="leftbar
    @if ($showcart) active @else @endif
    @if ($cartmodified) problem @endif
    @if ($aplicabble_voucher) mod @endif" id="basketList">
	<button class="leftbar__hidden--close" wire:click="$set('showcart', false)"></button>
	<div class="leftbar__content" id="basketContent">
		<div class="leftbar__top">
			<a class="leftbar__button" href="{{ url("/cart") }}">Vizualizare coș de cumpărături </a>
			<button class="leftbar__close" id="basketClose" wire:click="$set('showcart', false)">
				<svg>
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>

		@if ($cartItems->isEmpty())
			<span class="leftbar__empty">Coșul de cumpărături nu conține produse</span>
		@else
			<?php $total = 0; ?>
			<ul wire:ignore="$refresh" class="leftbar__list">
				<?php
				$isdisabled = false;
				$pricemodified = false;
				$disables = [];
				$nonquantity = [];
				?>
				@foreach ($cartItems as $index => $cartItem)
					<?php
					$disabled[$index] = false;
				$nonquantity[$index] = false;

					if ($cartItem->product->active != true || $cartItem->product->start_date > now()->format("Y-m-d") || $cartItem->product->end_date < now()->format("Y-m-d")) {
					    $disabled[$index] = true;
					    $isdisabled = true;
					}
					if ($cartItem->product->quantity < $cartItem->quantity) {
					    $nonquantity[$index] = true;
					    $isdisabled = true;
					}
					?>
					<script>
						@this.pricechanged();
					</script>
					<li class="leftbar__item">
						@if ($nonquantity[$index])
						<div class="leftbar__link" href="{{ route("product", ["product" => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== "" ? $cartItem->product->seo_id : $cartItem->product->id]) }}">
							<span class="leftbar__link--quantity">
								{{ $cartItem->quantity }} x
							</span>
							@if ($cartItem->product->media->first())
								<img loading="lazy" class="cart__list--img" src="/{{ $cartItem->product->media->first()->path }}{{ $cartItem->product->media->first()->name }}" alt="{{ $cartItem->product->media->first()->name }}{{ $cartItem->product->name }}">
							@else
								<img loading="lazy" class="cart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
							@endif
							<div class="leftbar__link--text">
								<h4 class="leftbar__link--title">{{ $cartItem->product->name }}</h4>
								<span class="item__product--error">Stoc disponibil pentru acest produs: {{ $cartItem->product->quantity }}</span>
								<a class="item__product--link" href="{{ url("/cart") }}">Modifică cantitatea</a>
							</div>
						</div>
						@else

						<a class="leftbar__link" href="{{ route("product", ["product" => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== "" ? $cartItem->product->seo_id : $cartItem->product->id]) }}">
							<span class="leftbar__link--quantity">
								{{ $cartItem->quantity }} x
							</span>
							@if ($cartItem->product->media->first())
								<img loading="lazy" class="cart__list--img" src="/{{ $cartItem->product->media->first()->path }}{{ $cartItem->product->media->first()->name }}" alt="{{ $cartItem->product->media->first()->name }}{{ $cartItem->product->name }}">
							@else
								<img loading="lazy" class="cart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
							@endif
							<div class="leftbar__link--text">
								<h4 class="leftbar__link--title">{{ $cartItem->product->name }}</h4>
								<span class="leftbar__link--price">
									@php
										$price = number_format($cartItem->product->product_prices->first()->value, 2, ",", ".");
									@endphp
									@if ($price)
										{{ $price }} {{ $currency }}
									@else
										indisponibil
									@endif
								</span>
							</div>
						</a>
						@endif

						<button class="leftbar__delete" style="border: none" type="button" wire:click="removeFromCart({{ $cartItem->product->id }})">
							<svg>
								<polyline points="3 6 5 6 21 6"></polyline>
								<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
							</svg>
						</button>
						@if ($disabled[$index])
							<div class="item__product--disabled">
								<span>Produs Indisponibil</span>
								<button class="leftbar__delete" type="button" wire:click="removeFromCart({{ $cartItem->product->id }})">
									<svg>
										<polyline points="3 6 5 6 21 6"></polyline>
										<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
									</svg>
								</button>
							</div>
						@endif
					</li>
				@endforeach
			</ul>

			<div class="leftbar__total">
				<h5 class="leftbar__total--text">
					Produse:
					<span id="leftbarTotalPrice">
						{{ number_format($cart->sum_amount, 2, ",", ".") }}
						{{ $currency }}
					</span>
				</h5>
				<h5 class="leftbar__total--text">
					Livrare:
					<span id="leftbarTotalPrice">
						@if ($cart->delivery_price == 0)
							Gratuit
						@else
							{{ $cart->delivery_price }} {{ $currency }}
						@endif
					</span>
				</h5>
				@if ($cart->voucher_id != null)
					<h5 class="leftbar__total--text">Voucher:
						<span class="voucher__choice">
							-{{ number_format($cart->voucher_value, 2, ",", ".") }} {{ $currency }}
							<button wire:click="removevoucher" class="details__delete" aria-label="Remove voucher">
								<svg>
									<polyline points="3 6 5 6 21 6"></polyline>
									<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
								</svg>
							</button>
						</span>
					</h5>
				@endif
				<h5 class="leftbar__total--text">
					Total:
					<span id="leftbarTotalPrice">
						{{ number_format($cart->final_amount, 2, ",", ".") }}
						{{ $currency }}
					</span>
				</h5>
				@if ($message)
					<p class="voucher__error">{{ $message }}</p>
				@endif
				@if ($cart->voucher_id == null)
					<div class="voucher">
						<input type="text" wire:model="voucher" maxlength="100" name="voucher" placeholder="Ai un voucher sau card cadou?">
						<button type="submit" wire:click="checkvoucher">
							Aplică
						</button>
					</div>
				@endif

				@if ($isdisabled)
					<a class="leftbar__button leftbar__button--long item__button--disabled">Finalizare Comandă</a>
					<span class="item__text--disabled" id="headerContinue">Cantitatea anumitor produse nu mai este disponibilă, sau ai cel puțin un produs indisponibil adaugat in coș!</span>
				@else
					<a class="leftbar__button leftbar__button--long" id="headerContinue" wire:click.prevent="continue">Finalizare Comandă</a>
				@endif
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
	<div class="leftbar__modal">
		<div class="leftbar__modal--text">Vrei să activezi voucher-ul "{{ $voucher }}"?</div>
		<div class="leftbar__modal--bundle">
			<button class="leftbar__modal--btn" wire:click="confirm_aplicabble">Da</button>
			<button class="leftbar__modal--btn" wire:click="cancel_aplicabble">Nu</button>
		</div>
	</div>
  <div class="leftbar__problem">
		<div class="leftbar__modal--text">De la ultima ta vizita unul sau mai multe produse din coșul tău de cumpărături a fost actualizat. Te rugam să verifici coșul înainte de a plasa comanda.</div>
		<div class="leftbar__modal--bundle">
			<button class="leftbar__modal--btn" wire:click="seen">Am înțeles</button>
		</div>
	</div>
</div>
