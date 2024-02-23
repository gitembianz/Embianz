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
			<span class="leftbar__empty">Niciun produs favorit</span>
		@else
			<ul class="leftbar__list">
				@foreach ($items as $item)
					<li class="leftbar__item">

						<a class="leftbar__link wishlist__link" href="{{ route("product", ["product" => $item->product->seo_id !== null && $item->product->seo_id !== "" ? $item->product->seo_id : $item->product->id]) }}">
							@if ($item->product->media->first())
								<img class="cart__list--img" src="/{{ $item->product->media->first()->path }}{{ $item->product->media->first()->name }}" alt="{{ $item->product->media->first()->name }} {{ $item->product->name }}">
							@else
								<img class="heart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
							@endif
							<div class="leftbar__link--text">
								<h4>{{ $item->product->name }}</h4>
							</div>
						</a>
						<button class="leftbar__delete" wire:click="removeFromWishlist({{ $item->product->id }})" aria-label="delete from wishlist">
							<svg>
								<line x1="18" y1="6" x2="6" y2="18"></line>
								<line x1="6" y1="6" x2="18" y2="18"></line>
							</svg>
						</button>
					</li>
				@endforeach
			</ul>
		@endif
	</div>
</div>
