<div class="accordion @if ($showrelated) active @endif">
 {{-- Accordion Header --}}
 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif"
   wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">
   @if ($relatedby === 'order')
    {{ __('Beeing in orders ') }}
   @elseif ($relatedby === 'supplier')
    {{ __('Beeing in orders supplier ') }}
   @elseif ($relatedby === 'cart')
    {{ __('Beeing in carts') }}
   @endif
   ({{ $orders->total() }})
   <svg>
    <polyline points="6 9 12 15 18 9"></polyline>
   </svg>
  </button>
 </div>
 {{-- Accordion Body --}}
 <div class="accordion__body">
  {{-- Navigation --}}
  <nav class="nav--controls">
   {{-- Search Input --}}
   <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
   {{-- Visible Dropdown --}}
   <div class="dropdown dropdown--right" wire:ignore>
    {{-- Dropdown Button --}}
    <button class="button button--primary button--centered" tooltip="Show items in table" tooltip-left>
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
      <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
     </svg>
    </button>
    {{-- Dropdown Content --}}
    <div class="dropdown__content">
     <div class="dropdown__container">
      @foreach ($columns as $column)
       <label class="switch switch--primary inline">
        <input type="checkbox" wire:model="selectedColumns" value="{{ $column }}"
         {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
        <span>{{ $column }}</span>
       </label>
      @endforeach
     </div>
    </div>
   </div>
  </nav>
  {{-- Table --}}
  <table class="expandable-table">
   <thead>
    <tr>
     <th style="border-right: none; border-left: none;">
     </th>
     @foreach ($selectedColumns as $index => $column)
      @if ($this->showColumn($column))
       <th @if ($index > 1) class="hidden" @endif>
        <button wire:click="sortBy('{{ $column }}')"
         class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
         {{ str_replace('_id', '', $column) }}
         <svg>
          <polyline points="6 9 12 15 18 9"></polyline>
         </svg>
        </button>
       </th>
      @endif
     @endforeach
     <th></th>
    </tr>
   </thead>
   <tbody>
    @php
     $i = 0;
    @endphp
    @if ($orders->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($orders as $order)
      <tr @if ($loop->last) id="last_record" @endif class="expandable-row">
       <td style="border-left: none" data-title="Check">
       </td>
       @foreach ($selectedColumns as $index => $column)
        <td @if ($index > 1) class="hidden" @endif data-title="{{ $column }}">
         @if ($column === 'order_id')
          <a href="{{ route('show_order', ['id' => $order->order->id]) }}">{{ $order->order->name }}</a>
         @elseif ($column === 'status')
          {{ $order->order->status->name }}
         @elseif ($column === 'order__supplier_id')
          <a href="{{ route('show_supplier', ['id' => $order->order->id]) }}">{{ $order->order->name }}</a>
         @elseif ($column === 'product_id')
          @if ($order->product_id)
           <a href="{{ route('show_product', ['id' => $order->product_id]) }}">{{ $order->product->name }}</a>
          @endif
         @elseif ($column === 'cart_id')
          @if ($order->cart_id)
           <a href="{{ route('show_cart', ['id' => $order->cart_id]) }}">{{ $order->cart->name }}</a>
          @endif
         @else
          {{ $order->$column }}
         @endif
        </td>
       @endforeach
       <td></td>
      </tr>
      @php
       $i++;
      @endphp
     @endforeach
    @endif
   </tbody>
  </table>
  @if ($orders->total() > $loadAmount)
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
    Load more
   </button>
  @endif
 </div>
</div>
