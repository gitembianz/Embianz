<div>
	{{-- <script rel="preload" src="script/store/header.js" as="script"></script> --}}
	<x-store-alert />
	<!-- This is the Header;
				the <header> tag encompasses the Logo and component-calling buttons located below,
				such as the Searchbar, Shopping Basket, WishList, and Burger Menu. The styles for
				Similarly, its JavaScript functionality is implemented in the "header.js" file. -->
	<!---------------------------------------------------------->
	<!--------------------Banner(Header Top)-------------------->
	<div class="banner">
		<div class="banner__container container">
			<p>
				Încălzește-ți iarna cu stil! Descoperă confortul termic al produselor noastre la jumătate de preț. Alege
				să fii fresh în fiecare sezon!
			</p>
		</div>
	</div>
	<!------------------END-Banner(Header Top)------------------>
	<!---------------------------------------------------------->
	<!--------------------------Header-------------------------->
	<header>
		<div class="header__container container">
			<!-------------------------Logo------------------------->

			<a class="logo" href="{{ url("/") }}">
				<img src="/images/store/svg/noren-black.svg" alt="Embianz Logo">
			</a>
			<!-----------------------END-Logo----------------------->
			<!------------------------------------------------------>
			<!---------------------NavMenu bar---------------------->
			<div class="navbar__list">
				@foreach ($categories as $category)
					@if ($category->subcategory->count() != 0)
						<div class="dropdown">
							<a class="dropdown__button" href="{{ route("products", ["categorySlug" => $category->seo_id !== null && $category->seo_id !== "" ? $category->seo_id : $category->id]) }}">
								{{ $category->name }}
								<svg>
									<polyline points="6 9 12 15 18 9"></polyline>
								</svg>
							</a>
							<div class="dropdown__list">
								@foreach ($category->subcategory as $subcategory)
									<div class="dropdown__item">
										<a class="dropdown__item--button" href="{{ route("products", ["categorySlug" => $subcategory->category->seo_id !== null && $subcategory->category->seo_id !== "" ? $subcategory->category->seo_id : $subcategory->category->id]) }}">
											{{ $subcategory->category->name }}
											@if ($subcategory->category->subcategory->count() != 0)
												<svg>
													<polyline points="9 18 15 12 9 6"></polyline>
												</svg>
											@endif
										</a>
										@if ($subcategory->category->subcategory->count() != 0)
											<div class="dropdown__item--list">
												@foreach ($subcategory->category->subcategory as $subsubCategory)
													<a href="{{ route("products", ["categorySlug" => $subsubCategory->category->seo_id !== null && $subsubCategory->category->seo_id !== "" ? $subsubCategory->category->seo_id : $subsubCategory->category->id]) }}">
														{{ $subsubCategory->category->name }}
													</a>
												@endforeach
											</div>
										@endif
									</div>
								@endforeach
							</div>
						</div>
					@else
						<a class="navbar__link" href="{{ route("products", ["categorySlug" => $category->seo_id !== null && $category->seo_id !== "" ? $category->seo_id : $category->id]) }}">
							{{ $category->name }}
						</a>
					@endif
				@endforeach
			</div>
			<!---------------------NavMenu bar--------------------->
			<!------------------------------------------------------>
			<!---------------------Right-Buttons--------------------->
			<div class="header__buttons">
				<button class="header__btn" id="menuOpen" aria-label="Open burger menu button">
					<svg>
						<line x1="3" y1="12" x2="21" y2="12"></line>
						<line x1="3" y1="6" x2="21" y2="6"></line>
						<line x1="3" y1="18" x2="21" y2="18"></line>
					</svg>
				</button>
				{{-- search button --}}
				<button class="header__btn" wire:click="$emit('showsearch')" id="searchOpen" aria-label="Open Searchbar button">
					<svg>
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</button>
				<a class="logo__hidden" href="{{ url("/") }}">
					<img src="/images/store/svg/noren-black.svg" alt="Site Logo">
				</a>
				{{-- wislist button --}}
				<button class="header__btn" wire:click="$emit('showwis')" id="wishOpen" aria-label="Open wishlist button">
					@livewire("wishlist-quantity")
					<svg>
						<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
						</path>
					</svg>
				</button>
				{{-- cart button --}}
				<button class="header__btn" wire:click="$emit('showcart')" id="basketOpen" aria-label="Open cart button">
					@if ($cart)
						@livewire("cart-quantity", ["cart" => $cart])
					@endif
					<svg>
						<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
						<line x1="3" y1="6" x2="21" y2="6"></line>
						<path d="M16 10a4 4 0 0 1-8 0"></path>
					</svg>
				</button>
			</div>
			<!-------------------END-Right-Buttons------------------->
		</div>
	</header>
	<!------------------------END-Header------------------------>
	<!---------------------------------------------------------->
	<!-------------------------Searchbar------------------------>
	@livewire("general-search")
	<!-----------------------END-Searchbar---------------------->
	<!---------------------------------------------------------->
	<!---------------------Basket (Leftbar)--------------------->
	<!-- In your Blade view -->
	@if ($cart)
		@livewire("cart-products-list", ["cartId" => $cart->id])
	@else
		@livewire("cart-products-list", ["cartId" => 0])
	@endif

	<!-------------------END-Basket (Leftbar)------------------->
	<!---------------------------------------------------------->
	<!----------------------Wish (Leftbar)---------------------->
	@livewire("wishlist-products-list")
	<!--------------------END-wish (Leftbar)-------------------->
	<!---------------------------------------------------------->
	<!----------------------Menu (Leftbar)---------------------->
	<div class="menu" id="menuList">
		<div class="menu__content" id="menuContent">
			<div class="menu__top">
				<button class="menu__close" id="menuClose" href="#">
					Inchide Meniul
					<svg>
						<line x1="18" y1="6" x2="6" y2="18"></line>
						<line x1="6" y1="6" x2="18" y2="18"></line>
					</svg>
				</button>
			</div>
			<div class="menu__list">
				@foreach ($categories as $category)
					@if ($category->subcategory->count() != 0)
						<div class="dropmenu">
							<div class="dropmenu__button">
								<a class="dropmenu__button--link" href="{{ route("products", ["categorySlug" => $category->seo_id !== null && $category->seo_id !== "" ? $category->seo_id : $category->id]) }}">
									@if ($category->media->first())
										<img class="cart__list--img" src="/{{ $category->media->first()->path }}{{ $category->media->first()->name }}" alt="{{ $category->media->first()->name }}{{ $category->name }}">
									@else
										<img class="heart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
									@endif
									<h4>{{ $category->name }}</h4>
								</a>
								<button class="dropmenu__open" href="#">
									<svg>
										<polyline points="6 9 12 15 18 9"></polyline>
									</svg>
								</button>
							</div>
							<div class="dropmenu__list">
								@foreach ($category->subcategory as $subcategory)
									<div class="submenu">
										<div class="submenu__button">
											<a class="submenu__button--link" href="{{ route("products", ["categorySlug" => $subcategory->category->seo_id !== null && $subcategory->category->seo_id !== "" ? $subcategory->category->seo_id : $subcategory->category->id]) }}">
												@if ($subcategory->category->media->first() != null)
													<img src="/{{ $subcategory->category->media->first()->path }}{{ $subcategory->category->media->first()->name }}" alt="{{ $subcategory->category->media->first()->name }}{{ $subcategory->category->name }}">
												@else
													<img src="/images/store/default/default70.webp" alt="something wrong">
												@endif
												<h4>{{ $subcategory->category->name }}</h4>
											</a>
											@if ($subcategory->category->subcategory->count() != 0)
												<button class="submenu__open" href="#">
													<svg>
														<polyline points="6 9 12 15 18 9"></polyline>
													</svg>
												</button>
											@endif
										</div>
										@if ($subcategory->category->subcategory->count() != 0)
											<div class="submenu__list">
												@foreach ($subcategory->category->subcategory as $subsubCategory)
													<a class="submenu__link" href="{{ route("products", ["categorySlug" => $subsubCategory->category->seo_id !== null && $subsubCategory->category->seo_id !== "" ? $subsubCategory->category->seo_id : $subsubCategory->category->id]) }}">
														@if ($subsubCategory->category->media->first() != null)
															<img src="/{{ $subsubCategory->category->media->first()->path }}{{ $subsubCategory->category->media->first()->name }}" alt="{{ $subsubCategory->category->media->first()->name }}{{ $subsubCategory->category->name }}">
														@else
															<img src="/images/store/default/default70.webp" alt="something wrong">
														@endif
														<h4>{{ $subsubCategory->category->name }}</h4>
													</a>
												@endforeach
											</div>
										@endif
									</div>
								@endforeach
							</div>
						</div>
					@else
						<a class="menu__link" href="{{ route("products", ["categorySlug" => $category->seo_id !== null && $category->seo_id !== "" ? $category->seo_id : $category->id]) }}">
							@if ($category->media->first() != null)
								<img src="/{{ $category->media->first()->path }}{{ $category->media->first()->name }}" alt="{{ $category->media->first()->name }} {{ $category->name }}">
							@else
								<img src="/images/store/default/default70.webp" alt="something wrong">
							@endif
							<h4> {{ $category->name }}</h4>
						</a>
					@endif
				@endforeach
			</div>
		</div>
	</div>
	<!--------------------END-Menu (Leftbar)-------------------->
	<!---------------------------------------------------------->
	<script src="/script/store/header.js" async defer></script>
</div>
