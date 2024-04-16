<div>
	<main>
		<!---------------------------------------------------------->
		<!---------------------- Slider Images --------------------->
		@if (!$slideritems->isEmpty())
			<div class="main-slider">
				<div class="main-slider__wrapper">
          @foreach ($slideritems as $item)
          {{-- -- Modelul de schimb de imagini pe slider la rezolutie -- --}}
          <a class="main-slider__slide" href="{{ route("products", ["categorySlug" => $item->seo_id !== null && $item->seo_id !== "" ? $item->seo_id : $item->id]) }}" draggable="false">
            <picture>
              @if ($item->media != null)
                {{-- Default (Desktop) --}}
                @if ($item->media->where("sequence", 2)->first() != null)
                  <source media="(min-width: 992px)" sizes="(min-width: 992px) 50vw" srcset="/{{ $item->media->where("sequence", 2)->first()->path }}{{ $item->media->where("sequence", 2)->first()->name }}" loading="eager" fetchpriority="high" height="{{ $item->media->where("sequence", 2)->first()->height }}" width="{{ $item->media->where("sequence", 2)->first()->width }}">
                @else
                  <img src="/images/store/default/default.webp" alt="something wrong">
                @endif
                {{-- Tablet Picture --}}
                @if ($item->media->where("sequence", 3)->first() != null)
                  <source media="(min-width: 576px)" sizes="(min-width: 576px) 80vw" srcset="/{{ $item->media->where("sequence", 3)->first()->path }}{{ $item->media->where("sequence", 3)->first()->name }}" loading="eager" fetchpriority="high" height="{{ $item->media->where("sequence", 3)->first()->height }}" width="{{ $item->media->where("sequence", 3)->first()->width }}">
                @elseif ($item->media->where("sequence", 2)->first() != null)
                  <source media="(min-width: 576px)" sizes="(min-width: 576px) 80vw" srcset="/{{ $item->media->where("sequence", 2)->first()->path }}{{ $item->media->where("sequence", 2)->first()->name }}" loading="eager" fetchpriority="high" height="{{ $item->media->where("sequence", 2)->first()->height }}" width="{{ $item->media->where("sequence", 2)->first()->width }}">
                @else
                  <img src="/images/store/default/default.webp" alt="something wrong">
                @endif
                {{-- Mobile Picture --}}
                @if ($item->media->where("sequence", 4)->first() != null)
                  <img sizes="100vw" alt="{{ $item->media->where("sequence", 4)->first()->name }} {{ $item->name }}" src="/{{ $item->media->where("sequence", 4)->first()->path }}{{ $item->media->where("sequence", 4)->first()->name }}" loading="eager" fetchpriority="high" height="{{ $item->media->where("sequence", 4)->first()->height }}" width="{{ $item->media->where("sequence", 4)->first()->width }}">
                @elseif ($item->media->where("sequence", 3)->first() != null)
                  <img sizes="100vw" alt="{{ $item->media->where("sequence", 3)->first()->name }} {{ $item->name }}" src="/{{ $item->media->where("sequence", 3)->first()->path }}{{ $item->media->where("sequence", 3)->first()->name }}" loading="eager" fetchpriority="high" height="{{ $item->media->where("sequence", 3)->first()->height }}" width="{{ $item->media->where("sequence", 3)->first()->width }}">
                @elseif ($item->media->where("sequence", 2)->first() != null)
                  <img sizes="100vw" alt="{{ $item->media->where("sequence", 2)->first()->name }} {{ $item->name }}" src="/{{ $item->media->where("sequence", 2)->first()->path }}{{ $item->media->where("sequence", 2)->first()->name }}" loading="eager" fetchpriority="high" height="{{ $item->media->where("sequence", 2)->first()->height }}" width="{{ $item->media->where("sequence", 2)->first()->width }}">
                @else
                  <img src="/images/store/default/default.webp" alt="something wrong">
                @endif
              @else
                <img src="/images/store/default/default.webp" alt="something wrong">
              @endif
            </picture>
          </a>
          {{-- End Modelul de schimb de imagini pe slider la rezolutie --}}
        @endforeach







				</div>
				<button class="main-slider__button prev" aria-label="Previous main slider">
					<svg>
						<polyline points="15 18 9 12 15 6"></polyline>
					</svg>
				</button>
				<button class="main-slider__button next" aria-label="Next main slider">
					<svg>
						<polyline points="9 18 15 12 9 6"></polyline>
					</svg>
				</button>
			</div>
		@endif

		<!-------------------- End Slider Images ------------------->
		<!---------------------------------------------------------->
		<!---------------------------------------------------------->
		<!---------------------- Slider Cards ---------------------->
		@if ($popproducts->isNotEmpty())
			<!------------------- Section Description ------------------>
			<section>
				<div class="section__header container">
					<h1 class="section__title">Descoperă produsele noastre populare!</h1>
					<p class="section__text">
						Explorează colecția noastră de produse și găsește accesoriile perfecte pentru a-ți completa stilul.
						<a href="{{ url("/storeproducts") }}">Vezi toate produsele!</a>
					</p>
				</div>
			</section>
			<!----------------- End Section Description ---------------->

			<section>
				<div class="card-slider container new-slider">
					<div class="card-slider__wrapper new-slider__wrapper" >
						@foreach ($popproducts as $product)
							<div class="card-slider__slide new-slider__slide card">
								<a draggable="false" href="{{ route("product", ["product" => $product->seo_id !== null && $product->seo_id !== "" ? $product->seo_id : $product->id]) }}">
									@if ($product->media->first() != null)
										<img loading="lazy" class="card-image" src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}" alt="{{ $product->media->first()->name }} {{ $product->name }}">
									@else
										<img loading="lazy" class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
									@endif
								</a>
								@livewire("product-wishlist-button", ["productId" => $product->id, "class" => "card__action", "is_in_wishlist" => $product->wishlists->isNotEmpty()], key($product->id))
								<?php
								$price = null;
								$discount = false;

								if ($product->product_prices->count() != 0) {
								    $price = number_format($product->product_prices->first()->value, 2, ",", ".");
								    $discount = $product->product_prices->first()->discount != 0 ? true : false;
								}
								?>

								@if ($price)
									@if ($product->quantity < $quantity && $product->quantity > 0)
										<p class="card-status out">
											Stock limitat!!
										</p>
										@if ($discount)
											<p class="card-status save-secondary">
												-{{ $product->product_prices->first()->discount }}%
											</p>
										@endif
									@elseif($product->quantity == 0)
										<p class="card-status save">
											Produs indisponibil!
										</p>
									@else
										@if ($discount)
											<p class="card-status save">
												-{{ $product->product_prices->first()->discount }}%
											</p>
										@endif
									@endif
									{{-- tagul de discount --}}
								@else
									<p class="card-status save">
										În curând!
									</p>
								@endif
								<div class="card-info">
									<div class="card-text">
										<span>{{ $product->short_description }}</span>
									</div>
									<div class="card-text">
										<h2 class="card-title">{{ $product->name }}</h2>
										<p class="card-price">
											@if ($discount)
												<span class="card-price discount">
													@if ($product->product_prices->first())
														{{ $price }}
														{{ $product->product_prices->first()->pricelist->currency->symbol }}
													@endif
												</span>
												<span class="card-price oldprice">
													{{ $product->product_prices->first()->rrp_value }}
													{{ $product->product_prices->first()->pricelist->currency->symbol }}
												</span>
											@else
												<span>
													@if ($product->product_prices->first())
														{{ $price }}
														{{ $product->product_prices->first()->pricelist->currency->symbol }}
													@endif
												</span>
											@endif

										</p>
									</div>
									@if ($price)
										@livewire("add-to-cart-button", ["product" => $product], key($product->id))
									@else
										<button class="card-button-disabled" aria-label="Disabled add to cart button">Indisponibil</button>
									@endif
								</div>
							</div>
						@endforeach
					</div>
					<button class="card-slider__button new-slider__button prev" aria-label="Previous card slider button">
						<svg>
							<polyline points="15 18 9 12 15 6"></polyline>
						</svg>
					</button>
					<button class="card-slider__button new-slider__button next" aria-label="Next card slider button">
						<svg>
							<polyline points="9 18 15 12 9 6"></polyline>
						</svg>
					</button>
				</div>
			</section>
		@endif

		@if ($newproducts->isNotEmpty())
			<!------------------- Section Description ------------------>
			<section>
				<div class="section__header container">
					<h2 class="section__title">Produse adăugate recent</h1>
					<p class="section__text">
						Adăugăm constant noi produse pentru a-ți oferi ce este mai bun.
					</p>
				</div>
			</section>
			<!----------------- End Section Description ---------------->

			<section>
				<div class="card-slider container popular-slider">
					<div class="card-slider__wrapper popular-slider__wrapper" >
						@foreach ($newproducts as $product)
							<div class="card-slider__slide popular-slider__slide card" >
								<a draggable="false" href="{{ route("product", ["product" => $product->seo_id !== null && $product->seo_id !== "" ? $product->seo_id : $product->id]) }}">
									@if ($product->media->first() != null)
										<img loading="lazy" class="card-image" src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}" alt="{{ $product->media->first()->name }} {{ $product->name }}">
									@else
										<img loading="lazy" class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
									@endif
								</a>
								@livewire("product-wishlist-button", ["productId" => $product->id, "class" => "card__action", "is_in_wishlist" => $product->wishlists->isNotEmpty()], key($product->id))
								<?php
								$price = null;
								$discount = false;

								if ($product->product_prices->count() != 0) {
								    $price = number_format($product->product_prices->first()->value, 2, ",", ".");
								    $discount = $product->product_prices->first()->discount != 0 ? true : false;
								}
								?>

								@if ($price)
									@if ($product->quantity < $quantity && $product->quantity > 0)
										<p class="card-status out">
											Stock limitat!!
										</p>
										@if ($discount)
											<p class="card-status save-secondary">
												-{{ $product->product_prices->first()->discount }}%
											</p>
										@endif
									@elseif($product->quantity == 0)
										<p class="card-status save">
											Produs indisponibil!
										</p>
									@else
										@if ($discount)
											<p class="card-status save">
												-{{ $product->product_prices->first()->discount }}%
											</p>
										@endif
									@endif
									{{-- tagul de discount --}}
								@else
									<p class="card-status save">
										În curând!
									</p>
								@endif
								<div class="card-info">
									<div class="card-text">
										<span>{{ $product->short_description }}</span>
									</div>
									<div class="card-text">
										<h2 class="card-title">{{ $product->name }}</h2>
										<p class="card-price">
											@if ($discount)
												<span class="card-price discount">
													@if ($product->product_prices->first())
														{{ $price }}
														{{ $product->product_prices->first()->pricelist->currency->symbol }}
													@endif
												</span>
												<span class="card-price oldprice">
													{{ $product->product_prices->first()->rrp_value }}
													{{ $product->product_prices->first()->pricelist->currency->symbol }}
												</span>
											@else
												<span>
													@if ($product->product_prices->first())
														{{ $price }}
														{{ $product->product_prices->first()->pricelist->currency->symbol }}
													@endif
												</span>
											@endif

										</p>
									</div>
									@if ($price)
										@livewire("add-to-cart-button", ["product" => $product], key($product->id))
									@else
										<button class="card-button-disabled" aria-label="Disabled add to cart button">Indisponibil</button>
									@endif
								</div>
							</div>
						@endforeach
					</div>
					<button class="popular-slider__button card-slider__button prev" aria-label="Previous card slider button">
						<svg>
							<polyline points="15 18 9 12 15 6"></polyline>
						</svg>
					</button>
					<button class="popular-slider__button card-slider__button next" aria-label="Next card slider button">
						<svg>
							<polyline points="9 18 15 12 9 6"></polyline>
						</svg>
					</button>
				</div>
			</section>
		@endif
		<!-------------------- End Slider Cards -------------------->
		<!---------------------------------------------------------->
		<!---------------------- Support Center -------------------->
		<x-support />
		<!-------------------- End Support Center ------------------>
		<!---------------------------------------------------------->
		<!--------------------- support button --------------------->
		<x-help-button />
		<!------------------- End support button ------------------->
		<!---------------------------------------------------------->
	</main>
	<script src="/script/store/main.js" async defer></script>
</div>
