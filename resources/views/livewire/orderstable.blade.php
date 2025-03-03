<section class="content">
 {{-- X-Components --}}
 <x-alert />


 {{-- Delete Record OR Records --}}
 <aside>
  <div class="background background--center @if ($single || $multiple) active @endif"></div>
  <div class="aside aside--confirm @if ($single || $multiple) active @endif">
   <span>
    @if ($single)
     Are you sure to delete this record?
    @else
     Are you sure to delete those records?
    @endif
   </span>
   @if ($single)
    <button class="button button--primary button--long" wire:click="deleteSingleRecord">
     <span>Delete</span>
    </button>
   @else
    <button class="button button--primary button--long" wire:click="deleteRecords()">
     <span>Delete</span>
    </button>
   @endif
   <button class="button button--danger button--long" wire:click="cancel_delete()">
    <span>Cancel</span>
   </button>
  </div>
 </aside>

 {{-- xml invoice --}}
 <aside>
  <div class="background background--center @if ($xmlinvoicesmodal || $xmlstornomodal) active @endif"></div>
  <div class="aside aside--confirm @if ($xmlinvoicesmodal || $xmlstornomodal) active @endif"
   style="min-width: 400px;min-height:250px">
   <span>
    @if ($xmlinvoicesmodal)
     Select the invoice start and end date
    @else
     Select the storno start and end date
    @endif
   </span>
   <form method="POST" wire:submit.prevent="handleSubmission">
    @csrf
    <div class="input__tabs">
     <input type="date" id="start_date" wire:model.defer="start_date" name="start_date">
     <label>Start Date</label>
    </div>

    {{-- End Date --}}
    <div class="input__tabs">
     <input type="date" id="end_date" wire:model.defer="end_date" name="end_date">
     <label>End Date</label>
    </div>

    {{-- Buttons for Submit --}}
    <div class="button-group" style="margin-top: 10px">
     @if ($xmlinvoicesmodal)
      <button type="button" class="button button--primary button--long" wire:click="generate_xml_invoice">
       <span>Generate XML Invoice</span>
      </button>
     @else
      <button type="button" class="button button--primary button--long" wire:click="generate_xml_storno">
       <span>Generate XML Storno</span>
      </button>
     @endif
     {{-- Cancel Button --}}
     <button type="button" class="button button--danger button--long" wire:click="cancel_xml">
      <span>Cancel</span>
     </button>
    </div>

   </form>
  </div>
 </aside>

 {{-- xml invoice --}}
 <aside>
  <div class="background background--center @if ($filteractive) active @endif"></div>
  <div class="aside aside--confirm @if ($filteractive) active @endif"
   style="min-width: 400px;min-height:250px">
   <span>
    Select the dates
   </span>
   <div class="input__tabs">
    <input type="date" id="start_date" wire:model.defer="start_date_filter" name="start_date">
    <label>Start Date</label>
   </div>

   {{-- Product End Date --}}
   <div class="input__tabs">
    <input type="date" id="end_date" wire:model.defer="end_date_filter" name="end_date">
    <label>End Date</label>
   </div>

   <button class="button button--primary button--long" wire:click="filter_order()">
    <span>Filter</span>
   </button>
   <button class="button button--danger button--long" wire:click="cancel_filter()">
    <span>Cancel</span>
   </button>
  </div>
 </aside>


 {{-- Asides --}}
 <aside>
  <div class="background background--right" wire:ignore id="sort__backdrop"></div>
  <div class="aside aside--right" wire:ignore id="sort">
   <div class="aside--controls">
    <button class="button button--flexed button--primary" id="sort__close">
     <svg>
      <polyline points="4 14 10 14 10 20"></polyline>
      <polyline points="20 10 14 10 14 4"></polyline>
      <line x1="14" y1="10" x2="21" y2="3"></line>
      <line x1="3" y1="21" x2="10" y2="14"></line>
     </svg>
    </button>
    <h3>Sorting Data</h3>
   </div>
   <span class="aside--line"></span>
   @foreach ($columns as $column)
    <button
     class="button button--primary button--long button--flexed button--arrow @if ($orderBy === $column && $orderAsc === '1') active @endif"
     wire:click="sortBy('{{ $column }}')">
     <svg>
      <polyline points="6 9 12 15 18 9"></polyline>
     </svg>
     {{ $column }}
    </button>
   @endforeach
  </div>
 </aside>
 <aside>
  <div class="background background--right" wire:ignore id="visi__backdrop"></div>
  <div class="aside aside--right" wire:ignore id="visi">
   <div class="aside--controls">
    <button class="button button--flexed button--primary" id="visi__close">
     <svg>
      <polyline points="4 14 10 14 10 20"></polyline>
      <polyline points="20 10 14 10 14 4"></polyline>
      <line x1="14" y1="10" x2="21" y2="3"></line>
      <line x1="3" y1="21" x2="10" y2="14"></line>
     </svg>
    </button>
    <h3>Visibility Data</h3>
   </div>
   <span class="aside--line"></span>
   @foreach ($columns as $column)
    <label class="switch switch--primary" style="margin: 0.25rem 0">
     <input type="checkbox" wire:ignore wire:model="selectedColumns" value="{{ $column }}"
      {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
     <span>{{ $column }}</span>
    </label>
   @endforeach
  </div>
 </aside>


 {{-- Navigation --}}
 <h1 class="table--name">{{ __('Orders') }} ({{ $orders->total() }})</h1>

 <div style="padding-top:5px; font-size:14px; color:#bcfcde;"><input type="checkbox" style="cursor:pointer;"
   wire:model="status31Only"> Show Processing Only</div>
 <nav class="nav--controls">
  {{-- Search Input --}}
  <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
  {{-- Refresh Button --}}
  <button class="button button--secondary button--centered display--desktop" tooltip="Refresh table" tooltip-top
   wire:click="$refresh">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M15 4.55a8 8 0 0 0 -6 14.9m0 -4.45v5h-5" />
    <path d="M18.37 7.16l0 .01" />
    <path d="M13 19.94l0 .01" />
    <path d="M16.84 18.37l0 .01" />
    <path d="M19.37 15.1l0 .01" />
    <path d="M19.94 11l0 .01" />
   </svg>
  </button>
  <a href="{{ route('checkorders') }}" class="button button--secondary button--centered display--desktop"
   tooltip="Check orders values" tooltip-top>
   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
    class="feather feather-info">
    <circle cx="12" cy="12" r="10"></circle>
    <line x1="12" y1="16" x2="12" y2="12"></line>
    <line x1="12" y1="8" x2="12.01" y2="8"></line>
   </svg>
  </a>
  <button class="button button--secondary" tooltip="Filter by invoice date" tooltip-top wire:click="filter">
   <svg>
    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
   </svg>
  </button>
  {{-- IF CHECKED --}}
  <div class="dropdown dropdown--right" @if (!$checked) style="display: none;" @endif>
   {{-- Dropdown Button --}}
   <button class="button button--secondary button--centered button--long" tooltip="Actions with checked" tooltip-top>
    <span>With Checked({{ count($checked) }})</span>
   </button>
   {{-- Dropdown Content --}}
   <div class="dropdown__content">
    <button class="button button--primary button--long" wire:click="confirmItemsRemoval()">
     Delete
    </button>
   </div>
  </div>
  {{-- Sorting Dropdown --}}
  <div class="dropdown dropdown--right display--desktop" wire:ignore>
   {{-- Dropdown Button --}}
   <button class="button button--secondary button--centered" tooltip="Sort items in table" tooltip-left>
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M15 10v-5c0 -1.38 .62 -2 2 -2s2 .62 2 2v5m0 -3h-4" />
     <path d="M19 21h-4l4 -7h-4" />
     <path d="M4 15l3 3l3 -3" />
     <path d="M7 6v12" />
    </svg>
   </button>

   {{-- Dropdown Content --}}
   <div class="dropdown__content">
    <div class="dropdown__container">
     @foreach ($columns as $column)
      <button
       class="button button--primary button--long button--flexed button--arrow @if ($orderBy === $column && $orderAsc === '1') active @endif"
       wire:click="sortBy('{{ $column }}')">
       <svg>
        <polyline points="6 9 12 15 18 9"></polyline>
       </svg>
       {{ $column }}
      </button>
     @endforeach
    </div>
   </div>
  </div>
  {{-- xml generator --}}
  <div class="dropdown dropdown--right display--desktop" wire:ignore>
   {{-- Dropdown Button --}}
   <button class="button button--secondary button--centered" tooltip="Generate xml for orders" tooltip-left>
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
     <path d="M4 15l4 6" />
     <path d="M4 21l4 -6" />
     <path d="M19 15v6h3" />
     <path d="M11 21v-6l2.5 3l2.5 -3v6" />
    </svg>
   </button>

   {{-- Dropdown Content --}}
   <div class="dropdown__content">
    <div class="dropdown__container">
     <button class="button button--primary button--long button--flexed button--arrow" wire:click="xmlinvoices">
      for invoices
     </button>
     <button class="button button--primary button--long button--flexed button--arrow" wire:click="xmlstorno">
      for storno
     </button>
    </div>
   </div>
  </div>
  {{-- Visible Dropdown --}}
  <div class="dropdown dropdown--right display--desktop" wire:ignore>
   {{-- Dropdown Button --}}
   <button class="button button--secondary button--centered" tooltip="Show items in table" tooltip-left>
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
       <input type="checkbox" wire:ignore wire:model="selectedColumns" value="{{ $column }}"
        {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
       <span>{{ $column }}</span>
      </label>
     @endforeach
    </div>
   </div>
  </div>
  {{-- Optional Dropdown --}}
  <div class="dropdown dropdown--right display--mobile">
   {{-- Dropdown Button --}}
   <button class="button button--secondary button--centered" tooltip="Show more actions" tooltip-left>
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
     <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
     <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
    </svg>
   </button>
   {{-- Dropdown Content --}}
   <div class="dropdown__content">
    <div class="dropdown__container">
     <button class="button button--primary button--fill button--flexed" wire:click="$refresh">
      <svg>
       <path stroke="none" d="M0 0h24v24H0z" fill="none" />
       <path d="M15 4.55a8 8 0 0 0 -6 14.9m0 -4.45v5h-5" />
       <path d="M18.37 7.16l0 .01" />
       <path d="M13 19.94l0 .01" />
       <path d="M16.84 18.37l0 .01" />
       <path d="M19.37 15.1l0 .01" />
       <path d="M19.94 11l0 .01" />
      </svg>
      <span>Refresh table</span>
     </button>
     <a class="button button--primary button--fill button--flexed" href="{{ route('checkorders') }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
       class="feather feather-info">
       <circle cx="12" cy="12" r="10"></circle>
       <line x1="12" y1="16" x2="12" y2="12"></line>
       <line x1="12" y1="8" x2="12.01" y2="8"></line>
      </svg>
      <span>Check order values</span>
     </a>
     <button class="button button--primary button--fill button--flexed" id="sort__open">
      <svg>
       <path stroke="none" d="M0 0h24v24H0z" fill="none" />
       <path d="M15 10v-5c0 -1.38 .62 -2 2 -2s2 .62 2 2v5m0 -3h-4" />
       <path d="M19 21h-4l4 -7h-4" />
       <path d="M4 15l3 3l3 -3" />
       <path d="M7 6v12" />
      </svg>
      <span>Sorting data</span>
     </button>
     <button class="button button--primary button--fill button--flexed" id="visi__open">
      <svg>
       <path stroke="none" d="M0 0h24v24H0z" fill="none" />
       <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
       <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
      </svg>
      <span>Visible</span>
     </button>
    </div>
   </div>
  </div>
 </nav>


 {{-- Select All? --}}
 @if ($selectPage && $selectAll)
  <button class="button button--fill button--primary" style="margin-top: 10px;">
   You selected {{ count($checked) }} items.
  </button>
 @elseif($selectPage)
  <button class="button button--fill button--secondary" style="margin-top: 10px;" wire:click="selectAll">
   You selected {{ count($checked) }} items, select all?
  </button>
 @endif


 {{-- Table --}}
 <div class="table" @if ($selectPage || $selectAll) style="height: calc(100% - 150px);" @endif>
  <table class="expandable-table">
   <thead>
    <tr>
     <th style="border-right: none; border-left: none;">
      <div class="checkbox--primary">
       <input type="checkbox" id="selectPage5" wire:model="selectPage" />
       <label for="selectPage5"></label>
      </div>
     </th>
     @foreach ($selectedColumns as $index => $column)
      @if ($this->showColumn($column))
       <th @if ($index > 2) class="hidden" @endif>
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
     <th style="border-left: none; border-right: none;">
      <button class="button button--secondary button--sm" style="opacity: 0">
       <svg>
        <polyline points="3 6 5 6 21 6"></polyline>
        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
       </svg>
      </button>
     </th>
    </tr>
   </thead>
   <tbody>
    @if ($orders->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($selectedColumns) + 2 }}">No record found.</td>
     </tr>
    @else
     @php
      $i = 0;
     @endphp
     @foreach ($orders as $nr => $order)
      @if ($order->status_id === app('global_order_processing'))
       @php
        $class = 'process';
        $productDetails = [];
       @endphp

       @foreach ($order->orders as $orderItem)
        @php
         $product = $orderItem->product;
         $interimQuantity = $product->quantity + $product->interim_quantity;

         // Check if interimQuantity is less than the order quantity
         if ($interimQuantity < $orderItem->quantity) {
             $class = 'notprocess';
             $productDetails[] = $orderItem->quantity - $interimQuantity . " x {$product->name} <br>";
         }
        @endphp
       @endforeach
      @else
       @php
        $class = '';
       @endphp
      @endif
      <tr @if ($loop->last) id="last_record" @endif
       @if ($class === 'notprocess') data-tooltip="{{ implode('<br>', $productDetails) }}" @endif
       class="expandable-row {{ $class }} @if ($this->isChecked($order->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $order->id }}" id="{{ $order->id }}" wire:model="checked">
         <label for="{{ $order->id }}"></label>
        </div>
       </td>
       @foreach ($selectedColumns as $index => $column)
        <td @if ($index > 2) class="hidden" @endif data-title="{{ $column }}"
         wire:click="expandRow({{ $nr }})">
         @if ($column === 'name')
          <a href="{{ route('show_order', ['id' => $order->id]) }}">{{ $order->name }}</a>
         @elseif ($column === 'account_id')
          @if ($order->account_id)
           <a href="{{ route('show_account', ['id' => $order->account_id]) }}">{{ $order->account->name }}</a>
          @endif
         @elseif($column === 'session_id')
          <a href="{{ route('show_session', ['id' => $order->session_id]) }}">{{ $order->$column }}</a>
         @elseif ($column === 'cart_id')
          @if ($order->cart_id)
           <a href="{{ route('show_cart', ['id' => $order->cart_id]) }}">{{ $order->cart->name }}</a>
          @endif
         @elseif ($column === 'currency_id')
          {{ $order->currency->name }}
         @elseif ($column === 'status_id')
          {{ $order->status->name }}
         @elseif ($column === 'payment_id')
          {{ $order->payment->name }}
         @elseif($column === 'comments')
          <span class="show-less">
           {!! $order->$column !!}
          </span>
         @elseif ($column === 'voucher_id')
          @if ($order->voucher_id)
           {{ $order->voucher->code }}
          @endif
         @else
          {{ $order->$column }}
         @endif
       @endforeach
       <td style="border-right: none">
        <button wire:click.prevent="confirmItemRemoval({{ $order->id }})"
         class="button button--secondary button--sm">
         <svg>
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
         </svg>
        </button>
       </td>
      </tr>
      <tr class="details-row  @if ($row === $i) active @endif">
       <td colspan="17">
        <div class="details">
         @foreach ($selectedColumns as $index => $column)
          @if ($index > 2)
           @if ($column === 'account_id')
            @if ($order->account_id)
             <p>
              <bold>{{ str_replace('_id', '', $column) }}:</bold>
              <a href="{{ route('show_account', ['id' => $order->account_id]) }}">{{ $order->account->name }}</a>
             </p>
            @endif
           @elseif ($column === 'cart_id')
            @if ($order->cart_id)
             <p>
              <bold>{{ str_replace('_id', '', $column) }}:</bold>
              <a href="{{ route('show_cart', ['id' => $order->cart_id]) }}">{{ $order->cart->name }}</a>
             </p>
            @endif
           @elseif ($column === 'currency_id')
            <p>
             <bold>{{ str_replace('_id', '', $column) }}:</bold>
             {{ $order->currency->name }}
            </p>
           @elseif ($column === 'status_id')
            <p>
             <bold>{{ str_replace('_id', '', $column) }}:</bold>
             {{ $order->status->name }}
            </p>
           @elseif ($column === 'payment_id')
            <p>
             <bold>{{ str_replace('_id', '', $column) }}:</bold>
             {{ $order->payment->name }}
            </p>
           @elseif ($column === 'voucher_id')
            @if ($order->voucher_id)
             <p>
              <bold>{{ str_replace('_id', '', $column) }}:</bold>
              {{ $order->voucher->code }}
             </p>
            @endif
           @else
            <p>
             <bold>{{ str_replace('_id', '', $column) }}:</bold>
             {{ $order->$column }}
            </p>
           @endif
          @endif
         @endforeach
        </div>
       </td>
      </tr>
      @php
       $i++;
      @endphp
     @endforeach
    @endif
   </tbody>
  </table>

  <x-admin-lazyload />
  <script>
   document.addEventListener('DOMContentLoaded', function() {
    const tooltip = document.createElement('div');
    tooltip.className = 'row-tooltip';
    document.body.appendChild(tooltip);

    function attachTooltipListeners() {
     document.querySelectorAll('.expandable-row.notprocess').forEach(row => {
      row.addEventListener('mouseenter', function() {
       tooltip.innerHTML = row.getAttribute('data-tooltip');
       tooltip.style.display = 'flex';
       tooltip.style.position = 'fixed';
       tooltip.style.color = 'white';
       tooltip.style.backgroundColor = '#333';
       tooltip.style.border = '1px solid #fff';
       tooltip.style.borderRadius = '6px';
       tooltip.style.padding = '8px 12px';
       tooltip.style.fontSize = '11px';
       tooltip.style.maxWidth = '400px';
       tooltip.style.wordWrap = 'break-word';
      });

      row.addEventListener('mousemove', function(event) {
       tooltip.style.left = `${event.pageX + 15}px`;
       tooltip.style.top = `${event.pageY + 15}px`;
      });

      row.addEventListener('mouseleave', function() {
       tooltip.style.display = 'none';
      });
     });
    }

    attachTooltipListeners();

    window.addEventListener('livewire:load', attachTooltipListeners);
    window.addEventListener('livewire:update', attachTooltipListeners);
   });
  </script>

  {{-- Load More Manual --}}
  @if ($loadAmount <= count($orders))
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
    Load more
   </button>
  @endif
 </div>
</section>
