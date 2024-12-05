<section class="content">
 {{-- X-Components --}}
 <x-alert />


 {{-- Delete Record --}}
 <aside>
  <div class="background background--center @if ($delete) active @endif"></div>
  <div class="aside aside--confirm @if ($delete) active @endif">
   <span>
    Are you sure to delete this record?
   </span>
   <button class="button button--primary button--long" wire:click.prevent="deleteRecord()">
    <span>Delete</span>
   </button>
   <button class="button button--danger button--long" wire:click.prevent="cancelItemRemoval()">
    <span>Cancel</span>
   </button>
  </div>
 </aside>


 {{-- Navigation --}}
 <nav class="nav--controls">
  <h1 class="table--name">Cart: {{ $cart->name }}</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to Carts" tooltip-top href="{{ route('carts') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  @if ($edititem === null)
   <button class="button button--primary button--centered" tooltip="Edit this Cart" tooltip-left
    wire:click.prevent="edititem()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
     <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
     <path d="M16 5l3 3" />
    </svg>
   </button>
  @else
   <button class="button button--primary button--centered" tooltip="Save Edit" tooltip-left
    wire:click.prevent="savecart()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
     <path d="M9 15l2 2l4 -4" />
    </svg>
   </button>
   <button class="button button--primary button--centered" tooltip="Cancel edit" tooltip-left
    wire:click.prevent="cancelcart()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
     <path d="M10 12l4 5" />
     <path d="M10 17l4 -5" />
    </svg>
   </button>
  @endif
  <button class="button button--primary button--centered" tooltip="Delete this Cart" tooltip-left
   wire:click.prevent="confirmItemRemoval">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 14l6 0" />
   </svg>
  </button>
 </nav>


 {{-- Tabs Header --}}
 <nav class="nav--tabs">
  <button class="button button--primary button--long button--active" id="detailsButton">
   Details
  </button>
  <button class="button button--primary button--long" id="relatedButton">
   Related
  </button>
 </nav>


 {{-- Tabs Body (Details) --}}
 <form style="height: calc(100% - 107.5px);" class="tabs__content details__view active" id="detailsContent">
  {{-- Cart Name --}}
  <div class="input__tabs">
   <span class="disabled">{{ $cart->name }}</span>
   <label for="category__name">Name</label>
  </div>

  {{-- Related Order --}}
  @if ($cart->order_id != null)
   <div class="input__tabs">
    <a href="{{ route('show_order', ['id' => $cart->order_id]) }}">{{ $cart->order->name }}</a>
    <label for="category__name">Related Order</label>
   </div>
  @endif
  {{-- Cart Quantity Amount --}}
  <div class="input__tabs">
   <span class="disabled">{{ $cart->quantity_amount }}</span>
   <label for="category__name">Quantity Amount</label>
  </div>

  {{-- Cart Session Id --}}
  <div class="input__tabs details__long">
   <a href="{{ route('show_session', ['id' => $cart->session_id]) }}">{{ $cart->session_id }}</a>
   <label for="category__name">Session Id</label>
  </div>



  {{-- Cart Sum Amount --}}
  <div class="input__tabs">
   <span class="disabled">{{ $cart->sum_amount }}</span>
   <label for="category__name">Sum Amount</label>
  </div>

  {{-- Cart Currency --}}
  <div class="input__tabs">
   <span class="disabled">{{ $cart->currency->name }}</span>
   <label for="category__name">Currency</label>
  </div>

  {{-- Cart Status --}}
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $cart->status->name }}</span>
   @else
    <select wire:model.defer="record.status_id">
     @foreach ($statuses as $status)
      <option value="{{ $status->id }}">{{ $status->name }}</option>
     @endforeach
    </select>
   @endif
   <label for="category__name"> Status</label>
  </div>
  {{-- Cart Sum Amount --}}
  <div class="input__tabs">
   <span class="disabled">{{ $cart->final_amount }}</span>
   <label for="category__name">Final Amount</label>
  </div>
  <div class="input__tabs">
   <span class="disabled">{{ $cart->delivery_price }}</span>
   <label for="category__name">Delivery Price</label>
  </div>
  @if ($cart->voucher_id != null)
   <div class="input__tabs">
    <a href="{{ route('vouchers') }}">{{ $cart->voucher->code }}</a>
    <label for="category__name">Voucher</label>
   </div>
  @endif
  @if ($cart->voucher_id != null)
   <div class="input__tabs">
    <span class="disabled">{{ $cart->voucher_value }}</span>
    <label for="category__name">Voucher Value</label>
   </div>
  @endif
  <div class="details__checkboxes">
   <div class="checkbox__details ">
    @if ($cart->seen_by_customer)
     <input type="checkbox" id="active1" checked class="disabled" disabled />
     <label for="active1" class="disabled">Cart seen by customer</label>
    @else
     <input type="checkbox" id="active2" class="disabled" disabled />
     <label for="active2" class="disabled">Cart seen by customer</label>
    @endif
   </div>
  </div>
  {{-- Create date / time --}}
  <div class="input__tabs">
   <span class="disabled">{{ $cart->created_at }}</span>
   <label>Create date / time</label>
  </div>


  {{-- Updated At --}}
  <div class="input__tabs">
   <span class="disabled">{{ $cart->updated_at }}</span>
   <label>Updated At</label>
  </div>

  {{-- Save Button --}}
  @if ($edititem != null)
   <button class="button button--fill button--secondary details__long" wire:click.prevent="savecart()" value="Save">
    Save
   </button>
  @endif
 </form>


 {{-- Tabs Body (Related) --}}
 <div style="height: calc(100% - 107.5px);" class="tabs__content related__view" id="relatedContent">
  @livewire('related-cart-items', ['cart' => $cart])
 </div>
</section>
