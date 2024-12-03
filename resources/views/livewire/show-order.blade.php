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
  <h1 class="table--name">Order: {{ $order->name }}</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to Order" tooltip-top href="{{ route('orders') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <div class="dropdown dropdown--right">
   {{-- Dropdown Button --}}
   <button class="button button--primary  button--centered button--long dropdown__button" tooltip="Actions with checked"
    tooltip-top>
    <span>Invoice</span>
   </button>
   {{-- Dropdown Content --}}
   <div class="dropdown__content">
    <div class="dropdown__container">
     <button class="button button--primary button--long" wire:click="generate_invoice_number()">
      Get invoice number
     </button>
     <button class="button button--primary button--long" wire:click="generate_invoice()">
      Generate invoice
     </button>
     <button class="button button--primary button--long" wire:click="generate_storno_number()">
      Get storno number
     </button>
     <button class="button button--primary button--long" wire:click="generate_storno()">
      Generate storno
     </button>
    </div>
   </div>
  </div>
  @if ($edititem === null)
   <button class="button button--primary button--centered" tooltip="Edit this Order" tooltip-left
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
    wire:click.prevent="saveitem()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
     <path d="M9 15l2 2l4 -4" />
    </svg>
   </button>
   <button class="button button--primary button--centered" tooltip="Cancel edit" tooltip-left
    wire:click.prevent="canceledit()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
     <path d="M10 12l4 5" />
     <path d="M10 17l4 -5" />
    </svg>
   </button>
  @endif
  <button class="button button--primary button--centered" tooltip="Delete this Order" tooltip-left
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
  {{-- Order Name --}}
  <div class="input__tabs">
   <span class="disabled">{{ $order->name }}</span>
   <label for="category__name">Name</label>
  </div>
  {{-- Order Name --}}
  <div class="input__tabs">
   <span class="disabled">{{ $order->order_number }}</span>
   <label for="category__name">Ordernumber</label>
  </div>
  {{-- Order Session Id --}}
  <div class="input__tabs details__long">
   <a href="{{ route('show_session', ['id' => $order->session_id]) }}">{{ $order->session_id }}</a>

   <label for="category__name">Session Id</label>
  </div>
  {{-- Order Account --}}
  <div class="input__tabs">
   <a href="{{ route('show_account', ['id' => $order->account_id]) }}">{{ $order->account->name }}</a>
   <label for="category__name">Account</label>
  </div>
  {{-- Order Cart --}}
  @if ($order->cart_id)
   <div class="input__tabs">
    <a href="{{ route('show_cart', ['id' => $order->cart_id]) }}">{{ $order->cart->name }}</a>
    <label for="category__name">Cart</label>
   </div>
  @endif
  {{-- Order Quantity amount --}}
  <div class="input__tabs">
   <span class="disabled">{{ $order->quantity_amount }}</span>
   <label>Quantity amount </label>
  </div>
  {{-- Order Sum amount --}}
  <div class="input__tabs">
   <span class="disabled">{{ $order->sum_amount }}</span>
   <label>Sum amount </label>
  </div>
  {{-- Order Sum amount --}}
  <div class="input__tabs">
   <span class="disabled">{{ $order->final_amount }}</span>
   <label>Final amount </label>
  </div>
  {{-- Order Sum amount --}}
  <div class="input__tabs">
   <span class="disabled">{{ $order->delivery_price }}</span>
   <label>Delivery Price </label>
  </div>
  {{-- Order Currency --}}
  <div class="input__tabs">
   <span class="disabled">{{ $order->currency->name }}</span>
   <label>Currency </label>
  </div>
  {{-- Order Status --}}
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $order->status->name }}</span>
   @else
    <select wire:model.defer="record.status_id">
     @foreach ($statuses as $status)
      <option value="{{ $status->id }}">{{ $status->name }}</option>
     @endforeach
    </select>
   @endif
   <label for="category__name"> Status</label>
  </div>
  {{-- Order Payment Method --}}
  <div class="input__tabs">
   <span class="disabled">{{ $order->payment->name }}</span>
   <label>Payment Method </label>
  </div>
  @if ($order->voucher_id != null)
   <div class="input__tabs">
    <a href="{{ route('vouchers') }}">{{ $order->voucher->code }}</a>
    <label for="category__name">Voucher</label>
   </div>
  @endif
  @if ($order->voucher_id != null)
   <div class="input__tabs">
    <span class="disabled">{{ $order->voucher_value }}</span>
    <label for="category__name">Voucher Value</label>
   </div>
  @endif
  {{-- Product Start Date --}}
  <div class="input__tabs">
   <span class="disabled">{{ $order->invoice_series }}</span>

   <label for="product__name">Invoice Series</label>
  </div>
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $order->invoice_date }}</span>
   @else
    <input type="date" wire:model.defer="record.invoice_date">
   @endif
   <label for="product__name">Invoice Date</label>
  </div>
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $order->storno_date }}</span>
   @else
    <input type="date" wire:model.defer="record.storno_date">
   @endif
   <label for="product__name">Storno Date</label>
  </div>
  {{-- Product Popularity --}}

  <div class="input__tabs">
   <span class="disabled">{{ $order->external_invoice_number }}</span>
   <label>External Invoice Number</label>
  </div>
  <div class="input__tabs">
   <span class="disabled">{{ $order->external_storno_number }}</span>
   <label>External Storno Number</label>
  </div>
  {{-- Create date / time --}}
  <div class="input__tabs">
   <span class="disabled">{{ $order->created_at }}</span>
   <label>Create date / time</label>
  </div>

  {{-- Updated At --}}
  <div class="input__tabs">
   <span class="disabled">{{ $order->updated_at }}</span>
   <label>Updated At</label>
  </div>
  {{-- Save Button --}}
  @if ($edititem != null)
   <button class="button button--fill button--secondary details__long" wire:click.prevent="saveitem()"
    value="Save">
    Save
   </button>
  @endif
 </form>


 {{-- Tabs Body (Related) --}}
 <div style="height: calc(100% - 107.5px);" class="tabs__content related__view" id="relatedContent">
  @livewire('related-order-items', ['order' => $order])
  @livewire('related-invoices', ['relatedby' => 'order', 'id' => $order->id])

 </div>
</section>
