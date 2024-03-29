<div class="leftbar @if ($showcart) active @endif" id="basketList">
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
			<ul class="leftbar__list">
				<?php
				$isdisabled = false;
				$disables =[];
				?>
				@foreach ($cartItems as $index => $cartItem)
				<?php
				$disabled[$index] = false;
				if (($cartItem->product->active != true) || ($cartItem->product->start_date > now()->format('Y-m-d')) || ($cartItem->product->end_date < now()->format('Y-m-d'))){
					$disabled[$index] = true;
				$isdisabled = true;

				}

				?>
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
						@if ($disabled[$index])
              <div class="item__product--disabled">
                <span>Produs Indisponibil</span>
                <button class="leftbar__delete" type="button" wire:click="removeFromCart({{ $cartItem->product->id }})">
                  <svg>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
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
            1024 test
          </span>
        </h5>
          <h5 class="leftbar__total--text">
            Livrare:
            <span id="leftbarTotalPrice">
              20 test
            </span>
          </h5>
				<h5 class="leftbar__total--text">
          Total:
          <span id="leftbarTotalPrice">
            {{ number_format($total, 2, ",", ".") }}
						{{ $currency }}
          </span>
        </h5>
        <p class="voucher__error" style="color: black !important">Voucher-ul nu a fost gasit!</p>
        <div class="voucher">
          <input type="text" name="voucher" maxlength="100" placeholder="Ai un voucher sau card cadou?">
          <button type="submit">
            Aplica
          </button>
        </div>

        @if ($isdisabled)
          <a class="leftbar__button leftbar__button--long item__button--disabled">Finalizare Comandă</a>
          <span class="item__text--disabled" id="headerContinue">Ai cel puțin un produs indisponibil adaugat in coș!</span>
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
</div>
