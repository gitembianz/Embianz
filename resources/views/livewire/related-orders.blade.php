<div class="accordion @if ($showrelated) active @endif">
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


 {{-- Accordion Header --}}
 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif"
   wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">
   {{ __('Orders ') }}({{ $account->orders()->count() }})
   <svg>
    <polyline points="6 9 12 15 18 9"></polyline>
   </svg>
  </button>
  <!-- <button class="button button--secondary">
        <svg>
     <line x1="12" y1="5" x2="12" y2="19"></line>
     <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    </button> -->
 </div>


 {{-- Accordion Body --}}
 <div class="accordion__body">
  {{-- Navigation --}}
  <nav class="nav--controls">
   {{-- Search Input --}}
   <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
   {{-- IF CHECKED --}}
   <div class="dropdown dropdown--right" @if (!$checked) style="display:none;" @endif>
    {{-- Dropdown Button --}}
    <button class="button button--primary button--centered button--long" tooltip="Actions with checked" tooltip-top>
     <span>With Checked({{ count($checked) }})</span>
    </button>
    {{-- Dropdown Content --}}
    <div class="dropdown__content">
     <button class="button button--primary button--long" wire:click="confirmItemsRemoval()">
      Delete
     </button>
    </div>
   </div>
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


  {{-- Select All? --}}
  @if ($selectPage && $selectAll)
   <button class="button button--fill button--primary">
    You selected {{ count($checked) }} items.
   </button>
  @elseif($selectPage)
   <button class="button button--fill button--secondary" wire:click="selectAll">
    You selected {{ count($checked) }} items, select all?
   </button>
  @endif


  {{-- Table --}}
  <table class="expandable-table">
   <thead>
    <tr>
     <th style="border-right: none; border-left: none;">
      <div class="checkbox--primary">
       <input type="checkbox" id="selectPage15" wire:model="selectPage" />
       <label for="selectPage15"></label>
      </div>
     </th>
     @if ($this->showColumn('Id'))
      <th>
       <button wire:click="sortBy('id')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        ID
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Name'))
      <th>
       <button wire:click="sortBy('name')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Name
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Cart'))
      <th>
       <button wire:click="sortBy('cart_id')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Cart
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Session Id'))
      <th class="hidden">
       <button wire:click="sortBy('session_id')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Session Id
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Quantity Amount'))
      <th class="hidden">
       <button wire:click="sortBy('quantity_amount')"
        class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Quantity Amount
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Sum Amount'))
      <th class="hidden">
       <button wire:click="sortBy('sum_amount')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Sum Amount
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Currency'))
      <th class="hidden">
       <button wire:click="sortBy('currency_id')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Currency
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Status'))
      <th class="hidden">
       <button wire:click="sortBy('status')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Status
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Delivery Method'))
      <th class="hidden">
       <button wire:click="sortBy('delivery_method')"
        class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Delivery Method
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Created At'))
      <th class="hidden">
       <button wire:click="sortBy('created_at')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Created At
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
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
    @php
     $i = 0;
    @endphp
    @if ($orders->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($orders as $index => $order)
      <tr @if ($loop->last) id="last_record" @endif
       class="expandable-row @if ($this->isChecked($order->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $order->id }}" id="{{ $order->id }}" wire:model="checked">
         <label for="{{ $order->id }}"></label>
        </div>
       </td>
       @if ($this->showColumn('Id'))
        <td wire:click="expandRow({{ $index }})">
         {{ $order->id }}
        </td>
       @endif
       @if ($this->showColumn('Name'))
        <td wire:click="expandRow({{ $index }})">
         <a href="{{ route('show_order', ['id' => $order->id]) }}">
          {{ $order->name }}
         </a>
        </td>
       @endif
       @if ($this->showColumn('Cart'))
        <td wire:click="expandRow({{ $index }})">
         <a href="{{ route('show_cart', ['id' => $order->cart_id]) }}">
          {{ $order->cart->name }}
         </a>
        </td>
       @endif
       @if ($this->showColumn('Session Id'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $order->session_id }}
        </td>
       @endif
       @if ($this->showColumn('Quantity Amount'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $order->quantity_amount }}
        </td>
       @endif
       @if ($this->showColumn('Sum Amount'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $order->sum_amount }}
        </td>
       @endif
       @if ($this->showColumn('Currency'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $order->currency->name }}
        </td>
       @endif
       @if ($this->showColumn('Status'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $order->status->name }}
        </td>
       @endif
       @if ($this->showColumn('Delivery Method'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $order->payment->name }}
        </td>
       @endif
       @if ($this->showColumn('Created At'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $order->created_at }}
        </td>
       @endif
       <td>
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
       <td colspan="{{ count($columns) + 2 }}">
        <div class="details">
         @if ($this->showColumn('Id'))
          <p>
           <bold>Id</bold>{{ $order->id }}
          </p>
         @endif
         @if ($this->showColumn('Name'))
          <p>
           <bold>Name</bold>
           <a href="{{ route('show_order', ['id' => $order->id]) }}">{{ $order->name }}</a>
          </p>
         @endif
         @if ($this->showColumn('Session Id'))
          <p>
           <bold>Session Id</bold>{{ $order->session_id }}
          </p>
         @endif
         @if ($this->showColumn('Cart'))
          <p>
           <bold>Cart</bold>
           <a href="{{ route('show_cart', ['id' => $order->cart_id]) }}">{{ $order->cart->name }}</a>
          </p>
         @endif
         @if ($this->showColumn('Quantity Amount'))
          <p>
           <bold>Quantity Amount</bold>{{ $order->quantity_amount }}
          </p>
         @endif
         @if ($this->showColumn('Sum Amount'))
          <p>
           <bold>Sum Amount</bold>{{ $order->sum_amount }}
          </p>
         @endif
         @if ($this->showColumn('Currency'))
          <p>
           <bold>Currency</bold>{{ $order->currency->name }}
          </p>
         @endif
         @if ($this->showColumn('Status'))
          <p>
           <bold>Status</bold>{{ $order->status->name }}
          </p>
         @endif
         @if ($this->showColumn('Delivery Method'))
          <p>
           <bold>Delivery Method</bold>{{ $order->payment->name }}
          </p>
         @endif
         @if ($this->showColumn('Created At'))
          <p>
           <bold>Created At</bold>{{ $order->created_at }}
          </p>
         @endif
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


  {{-- Load More Manual --}}
  @if (count($orders) >= 10)
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="load">
    Load more
   </button>
  @endif
 </div>
</div>
