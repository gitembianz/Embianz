<div>
    @if (session()->has('message'))
        <div class="alert__session liveAlert" id="alertevent">
            <span class="alert__session-text">{!! session('message') !!}</span>
            <button class="alert__session-btn" type="button" data-bs-dismiss="alert" aria-hidden="true">
                <svg>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <script>
            const alertEvent = document.getElementById("alertevent");
            header.style.marginBottom = '4rem';
            alertEvent.style.opacity = '1';

            setTimeout(function() {
                alertEvent.style.opacity = '0';
                setTimeout(function() {
                    alertEvent.remove();
                    header.style.marginBottom = '0';
                }, 500);
            }, 2000);
        </script>
    @endif

    <div class="accordion mt-1">
        <button class="accordion__btn"
            wire:click.prevent="@if ($showrelatedprod === false) $set('showrelatedprod', true) @else $set('showrelatedprod', false) @endif">
            {{ __('Products ') }}({{ count($products_related) }})
        </button>

        @if ($showrelatedprod)
            <div class="accordion__content">
                <div class="item">
                    <div class="item">
                      <button class="item__upload-btn" wire:click.prevent="getproducts()">
                        {{ __('Add related products') }}
                      </button>
                      @if (count($products) >0)
                        <p>exista acum produse</p>
                      @endif
                    </div>
                    <div class="item">
                        @if ($products_related && count($products_related) > 0)
                            @foreach ($products_related as $product)
                                {{ $product->product->name }}
                            @endforeach
                        @else
                            <p>no products related</p>
                        @endif
                    </div>

                </div>
            </div>
        @endif
    </div>

</div>
