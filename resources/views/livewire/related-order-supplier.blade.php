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
       <th class="hidden">
        <button class="table--btn">
         Price
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
                wire:click.prevent="selectitem({{ $index }}, {{ $product->id }}, '{{ addslashes($product->name) }}')">
                {{ $product->name }}
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
        <td class="hidden" style="width: auto;">
         <input type="text" placeholder="Insert price" required class="input button--fill button--xs"
          wire:model.defer="productsAndValues.{{ $index }}.product.price">
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
                  wire:click.prevent="selectitem({{ $index }}, {{ $product->id }}, '{{ $product->name }}')">
                  {{ $product->name }}
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
          <p>
           <bold>Price</bold>
           <input type="text" placeholder="Insert price" required class="input button--fill button--xs"
            wire:model.defer="productsAndValues.{{ $index }}.product.price">
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
   class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif"
   wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">
   {{ __('Order Supplier Items ') }}({{ $orderproducts->total() }})
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
     @if ($this->showColumn('Product Quantity'))
      <th class="hidden">
       <button wire:click="sortBy('product_quantity')"
        class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Product Quantity @if ($supplier->status === 'draft')
         Now
        @endif
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Product Quantity Interim'))
      <th class="hidden">
       <button wire:click="sortBy('product_quantity_interim')"
        class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Product Quantity Interim @if ($supplier->status === 'draft')
         Now
        @endif
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Quantity'))
      <th class="hidden">
       <button wire:click="sortBy('quantity')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Quantity
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Total Quantity'))
      <th class="hidden">
       <button class="table--btn">
        Total Quantity
       </button>
      </th>
     @endif
     @if ($this->showColumn('Quantity Received'))
      <th class="hidden">
       <button wire:click="sortBy('quantity_received')"
        class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Quantity Received
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
     @if ($this->showColumn('Created by'))
      <th class="hidden">
       <button wire:click="sortBy('created_by')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Created by
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Updated by'))
      <th class="hidden">
       <button wire:click="sortBy('last_modified_by')"
        class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Updated by
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
     @if ($this->showColumn('Updated At'))
      <th class="hidden">
       <button wire:click="sortBy('updated_at')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Updated At
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
    @if ($orderproducts->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($orderproducts as $index => $order)
      @php
       $pr = $order->product;
       $interimQuantity = $pr->quantity + $pr->interim_quantity;
       $totalQuantity = $pr->total_quantity;
      @endphp
      <tr @if ($loop->last) id="last_record" @endif
       class="expandable-row @if ($this->isChecked($order->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $order->id }}" id="{{ $order->id }}" wire:model="checked">
         <label for="{{ $order->id }}"></label>
        </div>
       </td>
       @if ($this->showColumn('Id'))
        <td wire:click="expandRow({{ $index }})">{{ $order->id }}</td>
       @endif
       @if ($this->showColumn('Product'))
        <td wire:click="expandRow({{ $index }})">
         <a href="{{ route('show_product', ['id' => $order->product->id]) }}">{{ $order->product->name }}</a>
        </td>
       @endif
       @if ($this->showColumn('Product Quantity'))
        <td class="hidden">
         @if ($order->order->status != 'draft')
          {{ $order->product_quantity }}
         @else
          {{ $order->product->quantity }}
         @endif
        </td>
       @endif
       @if ($this->showColumn('Product Quantity Interim'))
        <td class="hidden">
         @if ($order->order->status != 'draft')
          {{ $order->product_quantity_interim }}
         @else
          {{ $interimQuantity }}
         @endif
        </td>
       @endif
       @if ($this->showColumn('Quantity'))
        <td class="hidden">
         @if ($editindex !== $index)
          {{ $order->quantity }}
         @else
          <div class="searchable">
           <input type="number" required class="input__searchable"
            wire:model.defer="order_item.{{ $index }}.quantity">
          </div>
         @endif
        </td>
       @endif
       @if ($this->showColumn('Total Quantity'))
        <td class="hidden">
         {{ $totalQuantity }}
        </td>
       @endif
       @if ($this->showColumn('Quantity Received'))
        <td class="hidden">
         @if ($editindex !== $index)
          {{ $order->quantity_received }}
         @else
          <div class="searchable">
           <input type="number" required class="input__searchable"
            wire:model.defer="order_item.{{ $index }}.quantity_received">
          </div>
         @endif
        </td>
       @endif
       @if ($this->showColumn('Price'))
        <td class="hidden">
         @if ($editindex !== $index)
          {{ $order->price }}
         @else
          <div class="searchable">
           <input type="text" required class="input__searchable"
            wire:model.defer="order_item.{{ $index }}.price">
          </div>
         @endif
        </td>
       @endif
       @if ($this->showColumn('Created by'))
        <td class="hidden">
         {{ $order->created_by }}
        </td>
       @endif
       @if ($this->showColumn('Updated by'))
        <td class="hidden">
         {{ $order->last_modified_by }}
        </td>
       @endif
       @if ($this->showColumn('Created At'))
        <td class="hidden">
         {{ $order->created_at }}
        </td>
       @endif
       @if ($this->showColumn('Updated At'))
        <td class="hidden">
         {{ $order->updated_at }}
        </td>
       @endif
       <td style="border-right: none">
        <div style="display:flex;">
         @if ($editindex !== $index)
          <button class="button button--secondary button--sm"
           wire:click.prevent="edititem({{ $index }}, {{ $order->id }})">
           <svg>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
            </path>
           </svg>
          </button>
          <button wire:click.prevent="confirmItemRemoval({{ $order->id }})"
           class="button button--secondary button--sm">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
           </svg>
          </button>
         @else
          <button class="button button--secondary button--sm"
           wire:click.prevent="saveitem({{ $index }} , {{ $order->id }})">
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
