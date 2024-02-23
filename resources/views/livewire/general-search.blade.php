		<div class="search @if ($active) active @endif" id="searchList">
		<div class="search__container container" id="searchContent">
		<div class="search__top">
		<div class="search__input">
		<svg>
		<circle cx="11" cy="11" r="8"></circle>
		<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
		</svg>
		<input id="searchInput" wire:model.debounce.300ms="search" type="text" placeholder="Cauta...">
		</div>
		<button class="search__close" type="button" id="searchClose" wire:click.prevent="close" aria-label="Close general searchbar">
		<svg>
		<line x1="18" y1="6" x2="6" y2="18"></line>
		<line x1="6" y1="6" x2="18" y2="18"></line>
		</svg>
		</button>
		</div>
		@if ($search && $active)
		<ul class="search__list">
		@if (count($objects) > 0 || count($cats) > 0)
		@if (count($objects) > 0)
		@foreach ($objects as $product)
		<li class="search__item">
		<a class="search__link" href="{{ route("product", ["product" => $product->seo_id !== null && $product->seo_id !== "" ? $product->seo_id : $product->id]) }}">
		@if ($product->media->first() != null)
		<img src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}" alt="{{ $product->media->first()->name }} {{ $product->name }}">
	@else
		<img src="/images/store/default/default70.webp" alt="something wrong">
		@endif

		<div class="search__link--text">
		<div class="search__link--top">
		<p>{{ $product->short_description }}</p>
		</div>
		<div class="search__link--bottom">
		<h4>{{ $product->name }}</h4>

		@if ($product->product_prices->count() != 0)
		<span>
		@php
			$price = number_format($product->product_prices->first()->value, 2, ",", ".");
			$currency = $product->product_prices->first()->pricelist->currency->name;
		@endphp
		@if ($price)
		{{ $price }} {{ $currency }}
		@endif
		</span>
		@endif
		</div>
		</div>
		</a>
		</li>
		@endforeach

		@endif
		@if (count($cats) > 0)
		@foreach ($cats as $category)
		<li class="search__item">
		<a class="search__link" href="{{ route("products", ["categorySlug" => $category->seo_id !== null && $category->seo_id !== "" ? $category->seo_id : $category->id]) }}">
		@if ($category->media->first() != null)
		<img src="/{{ $category->media->first()->path }}{{ $category->media->first()->name }}" alt="{{ $category->media->first()->name }} {{ $category->name }}">
	@else
		<img src="/images/store/default/default70.webp" alt="something wrong">
		@endif

		<div class="search__link--text">
		<div class="search__link--bottom">
		<h4>{{ $category->name }}</h4>
		</div>
		</div>
		</a>
		</li>
		@endforeach
		@endif
	@else
		<span>{{ __("Niciun element gasit") }}</span>
		@endif
		</ul>
		@endif
		</div>
		<button class="search__close--hidden" id="modalClose" type="button" wire:click.prevent="close" aria-label="Close hidden general searchbar">
		</button>
		</div>
