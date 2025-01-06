<div class="accordion @if ($showrelatedprod) active @endif">
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

 {{-- Table Add Multiple --}}
 <aside>
  <div class="background background--center @if ($additems) active @endif"></div>
  <div class="aside aside--table @if ($additems) active @endif">
   {{-- Navigation --}}
   <nav class="nav--controls">
    <h1 class="table--name">
     {{ __('Add order items') }}
    </h1>
    <button class="button button--primary button--centered" wire:click.prevent="saveitems()">
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
      <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
      <path d="M14 4l0 4l-6 0l0 -4" />
     </svg>
    </button>
    <button class="button button--danger button--centered" wire:click="closemodal">
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M15 19v-2a2 2 0 0 1 2 -2h2" />
      <path d="M15 5v2a2 2 0 0 0 2 2h2" />
      <path d="M5 15h2a2 2 0 0 1 2 2v2" />
      <path d="M5 9h2a2 2 0 0 0 2 -2v-2" />
     </svg>
    </button>
   </nav>


   {{-- Tabel --}}
   <div class="table" style="height: calc(100% - 60px);">
    <table class="expandable-table">
     <thead>
      <tr>
       <th></th>
       <th>
        <button class="table--btn">
         Product Name
        </button>
       </th>
       <th>
        <button class="table--btn">
         Product Price
        </button>
       </th>
       <th class="hidden">
        <button class="table--btn">
         Product
        </button>
       </th>
       <th class="hidden">
        <button class="table--btn">
         Quantity
        </button>
       </th>
       <th>
        <div style="display: flex;">
         <button class="button button--secondary button--sm" style="opacity: 0">
          <svg>
           <polyline points="3 6 5 6 21 6"></polyline>
           <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
         </button>
         <button class="button button--secondary button--sm" style="opacity: 0">
          <svg>
           <polyline points="3 6 5 6 21 6"></polyline>
           <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
         </button>
        </div>
       </th>
      </tr>
     </thead>
     <tbody>
      @php
       $k = 0;
      @endphp
      @foreach ($productsAndValues as $index => $productsAndValue)
       <tr class="expandable-row">
        <td>
         {{ $k }}
        </td>
        <td wire:click="expandRow2({{ $index }})">
         {{ $productsAndValue['itemselected'] }}
        </td>
        <td wire:click="expandRow2({{ $index }})">
         <input type="number" placeholder="Product price" required class="input button--fill button--xs"
          wire:model.defer="productsAndValues.{{ $index }}.price">

        </td>
        <td class="hidden">
         @if ($productsAndValue['allow'])
          <div class="searchable active">
           {{-- Dropdown Header --}}
           <input class="input" wire:model.debounce.300ms="searchadd" placeholder="Search..." type="text">
           <button class="button__searchable" wire:click.prevent="dennyselect({{ $index }})">
            <svg>
             <path stroke="none" d="M0 0h24v24H0z" fill="none" />
             <path d="M10 10l-6 6v4h4l6 -6m1.99 -1.99l2.504 -2.504a2.828 2.828 0 1 0 -4 -4l-2.5 2.5" />
             <path d="M13.5 6.5l4 4" />
             <path class="button__searchable--line" d="M3 3l18 18" />
            </svg>
           </button>
           {{-- Dropdown Content --}}
           <div class="content__searchable">
            <div class="list__searchable">
             @if (count($products) >= 1)
              @foreach ($products as $product)
               <button class="item__searchable"
                wire:click.prevent="selectitem({{ $index }}, {{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->product_prices->first()->value }}', '{{ $product->product_prices->first()->vat }}')">
                {{ $product->name }}
                ({{ number_format($product->product_prices->first()->value, 2, ',', '.') . app('global_currency_primary_symbol') }})
               </button>
              @endforeach
             @else
              <button class="item__searchable">{{ __('No record found') }}</button>
             @endif
            </div>
           </div>
          </div>
         @else
          <div class="searchable">
           {{-- Dropdown Show Selected || Select now --}}
           <button class="input__searchable">
            @if ($productsAndValue['itemselected'])
             {{ $productsAndValue['itemselected'] }}
            @else
             {{ __('Select a product') }}
            @endif
           </button>
           <button class="button__searchable" wire:click.prevent="allowselect({{ $index }})">
            <svg>
             <path stroke="none" d="M0 0h24v24H0z" fill="none" />
             <path d="M10 10l-6 6v4h4l6 -6m1.99 -1.99l2.504 -2.504a2.828 2.828 0 1 0 -4 -4l-2.5 2.5" />
             <path d="M13.5 6.5l4 4" />
             <path class="button__searchable--line" d="M3 3l18 18" />
            </svg>
           </button>
          </div>
         @endif
         <input type="hidden" wire:model.defer="productsAndValues.{{ $index }}.product.name">
        </td>

        <td class="hidden" style="width: auto;">
         <input type="number" placeholder="Insert quantity" required class="input button--fill button--xs"
          wire:model.defer="productsAndValues.{{ $index }}.product.quantity">
        </td>

        <td>
         <div style="display: flex;">
          @if ($index == $row - 1)
           <button class="button button--secondary button--sm" wire:click="plus">
            <svg>
             <line x1="12" y1="5" x2="12" y2="19">
             </line>
             <line x1="5" y1="12" x2="19" y2="12">
             </line>
            </svg>
           </button>
           <button class="button button--secondary button--sm" wire:click="clear({{ $index }})">
            <svg>
             <line x1="18" y1="6" x2="6" y2="18">
             </line>
             <line x1="6" y1="6" x2="18" y2="18">
             </line>
            </svg>
           </button>
          @endif
          @if ($index != $row - 1)
           <button class="button button--secondary button--sm" wire:click="clear({{ $index }})">
            <svg>
             <line x1="18" y1="6" x2="6" y2="18">
             </line>
             <line x1="6" y1="6" x2="18" y2="18">
             </line>
            </svg>
           </button>
          @endif
         </div>
        </td>
       </tr>
       <tr class="details-row  @if ($rind2 === $k) active @endif">
        <td colspan="3">
         <div class="details">
          <p>
           <bold>Product</bold>
           @if ($productsAndValue['allow'])
            <div class="searchable active">
             {{-- Dropdown Header --}}
             <input class="input" wire:model.debounce.300ms="searchadd" placeholder="Search..." type="text">
             <button class="button__searchable" wire:click.prevent="dennyselect({{ $index }})">
              <svg>
               <path stroke="none" d="M0 0h24v24H0z" fill="none" />
               <path d="M10 10l-6 6v4h4l6 -6m1.99 -1.99l2.504 -2.504a2.828 2.828 0 1 0 -4 -4l-2.5 2.5" />
               <path d="M13.5 6.5l4 4" />
               <path class="button__searchable--line" d="M3 3l18 18" />
              </svg>
             </button>
             {{-- Dropdown Content --}}
             <div class="content__searchable">
              <div class="list__searchable">
               @if (count($products) >= 1)
                @foreach ($products as $product)
                 <button class="item__searchable"
                  wire:click.prevent="selectitem({{ $index }}, {{ $product->id }}, '{{ $product->name }}', '{{ $product->product_prices->first()->value }}','{{ $product->product_prices->first()->vat }}')">
                  {{ $product->name }}
                  ({{ number_format($product->product_prices->first()->value, 2, ',', '.') . app('global_currency_primary_symbol') }})
                 </button>
                @endforeach
               @else
                <button class="item__searchable">{{ __('No record found') }}</button>
               @endif
              </div>
             </div>
            </div>
           @else
            <div class="searchable">
             {{-- Dropdown Show Selected || Select now --}}
             <button class="input__searchable">
              @if ($productsAndValue['itemselected'])
               {{ $productsAndValue['itemselected'] }}
              @else
               {{ __('Select a product') }}
              @endif
             </button>
             <button class="button__searchable" wire:click.prevent="allowselect({{ $index }})">
              <svg>
               <path stroke="none" d="M0 0h24v24H0z" fill="none" />
               <path d="M10 10l-6 6v4h4l6 -6m1.99 -1.99l2.504 -2.504a2.828 2.828 0 1 0 -4 -4l-2.5 2.5" />
               <path d="M13.5 6.5l4 4" />
               <path class="button__searchable--line" d="M3 3l18 18" />
              </svg>
             </button>
            </div>
           @endif
           <input type="hidden" wire:model.defer="productsAndValues.{{ $index }}.product.name">
          </p>
          <p>
           <bold>Quantity</bold>
           <input type="number" placeholder="Insert quantity" required class="input button--fill button--xs"
            wire:model.defer="productsAndValues.{{ $index }}.product.quantity">
          </p>
         </div>
        </td>
       </tr>
       @php
        $k++;
       @endphp
      @endforeach
     </tbody>
    </table>
   </div>
  </div>
 </aside>

 {{-- Accordion Header --}}
 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showrelatedprod) button--secondary active @endif"
   wire:click.prevent="@if ($showrelatedprod === false) $set('showrelatedprod', true) @else $set('showrelatedprod', false) @endif">
   {{ __('Order Items ') }}({{ $order->orders()->count() }})
   <svg>
    <polyline points="6 9 12 15 18 9"></polyline>
   </svg>
  </button>
  <button wire:click="addorderitems()" class="button button--secondary">
   <svg>
    <line x1="12" y1="5" x2="12" y2="19"></line>
    <line x1="5" y1="12" x2="19" y2="12"></line>
   </svg>
  </button>
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
       <input type="checkbox" id="selectPage14" wire:model="selectPage" />
       <label for="selectPage14"></label>
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
     @if ($this->showColumn('Product'))
      <th>
       <button class="table--btn">
        Product Name
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Price'))
      <th class="hidden">
       <button wire:click="sortBy('price')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Price
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Quantity'))
      <th>
       <button wire:click="sortBy('quantity')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Quantity
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
      <th> <button class="table--btn">
        Interim Quantity

       </button></th>
     @endif
     @if ($this->showColumn('VAT'))
      <th class="hidden">
       <button wire:click="sortBy('quantity')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        VAT
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
    @if ($orderproducts->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($orderproducts as $index => $product)
      @php
       $class = 'process';
       $pr = $product->product;
       $interimQuantity = $pr->quantity + $pr->interim_quantity;

       if ($interimQuantity < $product->quantity) {
           $class = 'notprocess';
       }
      @endphp
      <tr @if ($loop->last) id="last_record" @endif
       class="expandable-row {{ $class }} @if ($this->isChecked($product->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $product->id }}" id="{{ $product->id }}" wire:model="checked">
         <label for="{{ $product->id }}"></label>
        </div>
       </td>
       @if ($this->showColumn('Id'))
        <td wire:click="expandRow({{ $index }})">{{ $product->id }}</td>
       @endif
       @if ($this->showColumn('Product'))
        <td wire:click="expandRow({{ $index }})">
         <a href="{{ route('show_product', ['id' => $product->product->id]) }}">{{ $product->product->name }}</a>
        </td>
       @endif
       @if ($this->showColumn('Price'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         @if ($editindex !== $index)
          {{ $product->price }}
         @else
          <div class="searchable">
           <input type="text" required class="input__searchable"
            wire:model.defer="order_item.{{ $index }}.price">
          </div>
         @endif
        </td>
       @endif
       @if ($this->showColumn('Quantity'))
        <td wire:click="expandRow({{ $index }})"
         style="display: flex; align-items: center; text-align: center;  flex-direction: column;">
         <div
          style="display: flex; flex-direction: row; align-content: center; justify-content: space-between; width: 100px; align-items: center;">

          <svg style="{{ $product->quantity == 1 ? 'opacity: 50%;' : '' }}"
           wire:click="decrement({{ $product->id }})">
           <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>



          {{ $product->quantity }}
          <svg wire:click="increment({{ $product->id }})">
           <line x1="12" y1="5" x2="12" y2="19"></line>
           <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
         </div>
        </td>
        <td wire:click="expandRow({{ $index }})"> {{ $interimQuantity }}</td>
       @endif
       @if ($this->showColumn('VAT'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $product->vat }}
        </td>
       @endif
       @if ($this->showColumn('Created At'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $product->created_at }}
        </td>
       @endif
       <td style="border-right: none">
        <div style="display:flex;">
         @if ($editindex !== $index)
          <button class="button button--secondary button--sm"
           wire:click.prevent="edititem({{ $index }}, {{ $product->id }})">
           <svg>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
            </path>
           </svg>
          </button>
          <button wire:click.prevent="confirmItemRemoval({{ $product->id }})"
           class="button button--secondary button--sm">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
           </svg>
          </button>
         @else
          <button class="button button--secondary button--sm"
           wire:click.prevent="saveitem({{ $index }} , {{ $product->id }})">
           <svg>
            <polyline points="20 6 9 17 4 12"></polyline>
           </svg>
          </button>
          <button class="button button--secondary button--sm" wire:click.prevent="canceledit()">
           <svg>
            <line x1="18" y1="6" x2="6" y2="18">
            </line>
            <line x1="6" y1="6" x2="18" y2="18">
            </line>
           </svg>
          </button>
         @endif
        </div>
       </td>

      </tr>
      <tr class="details-row  @if ($rand === $i) active @endif">
       <td colspan="{{ count($columns) + 2 }}">
        <div class="details">

         @if ($this->showColumn('Price'))
          <p>
           <bold>Price</bold>
           {{ $product->price }}
          </p>
         @endif
         @if ($this->showColumn('Quantity'))
          <p>
           <bold>Quantity</bold>
           {{ $product->quantity }}
          </p>
         @endif
         @if ($this->showColumn('VAT'))
          <p>
           <bold>VAT</bold>
           {{ $product->vat }}
          </p>
         @endif
         @if ($this->showColumn('Created At'))
          <p>
           <bold>Created At</bold>
           {{ $product->created_at }}
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
  @if (count($orderproducts) >= 10)
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="load">
    Load more
   </button>
  @endif
 </div>
</div>
