<div class="accordion @if ($showrelatedprice) active @endif">

 {{-- ASIDES --}}
 {{-- Delete Record || Delete Records --}}
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
 {{-- Table Edit Multiple --}}
 <aside>
  <div class="background background--center  @if ($editmultiple) active @endif"></div>
  <div class="aside aside--table @if ($editmultiple) active @endif">


   {{-- Navigation --}}
   <nav class="nav--controls">
    <h1 class="table--name">
     {{ __('Edit related items') }}
    </h1>
    <button class="button button--primary button--centered" wire:click.prevent="confirmpricemultiple()">
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
       <th style="width: unset !important;">
        <div style="display: flex;">
         <button class="table--btn">
          Product
         </button>
         <button class="button button--secondary button--sm" style="opacity: 0">
          <svg>
           <polyline points="3 6 5 6 21 6"></polyline>
           <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
         </button>
        </div>
       </th>
       <th>
        <button class="table--btn">Pricelist</button>
       </th>
       <th>
        <button class="table--btn">Value without VAT</button>
       </th>
       <th class="hidden">
        <button class="table--btn">Discount %</button>
       </th>
       <th class="hidden" style="width: unset !important;">
        <button class="table--btn">VAT %</button>
       </th>
       <th class="hidden" style="width: unset !important;">
        <button class="table--btn">Price</button>
       </th>
      </tr>
     </thead>
     <tbody>
      @php
       $j = 0;
      @endphp
      @foreach ($priceAndValues as $index => $priceAndValue)
       <tr class="expandable-row" wire:key="price-row-{{ $index }}">
        <td wire:click="expandRow2({{ $index }})" style="width: unset !important;">
         {{ $item->name }}
        </td>
        <td wire:click="expandRow2({{ $index }})">
         {{ $priceAndValue['itemselected'] }}
        </td>
        <td wire:click="expandRow2({{ $index }})">
         <div class="searchable">
          <input type="text" required class="input__searchable"
           wire:model.defer="priceAndValues.{{ $index }}.price.value">
         </div>
        </td>
        <td class="hidden" wire:click="expandRow2({{ $index }})">
         <div class="searchable">
          <input type="number" required class="input__searchable"
           wire:model.defer="priceAndValues.{{ $index }}.price.discount">
         </div>
        </td>
        <td class="hidden" style="width: unset !important;" wire:click="expandRow2({{ $index }})">
         <div class="searchable">
          <input type="number" required
           class="input__searchable"wire:model.defer="priceAndValues.{{ $index }}.price.vat">
         </div>
        </td>
        <td class="hidden" style="width: unset !important;" wire:click="expandRow2({{ $index }})">
         <div class="searchable">
          <input type="number" required
           class="input__searchable"wire:model.defer="priceAndValues.{{ $index }}.price.price">
         </div>
        </td>
       </tr>
       <tr class="details-row  @if ($row2 === $j) active @endif">
        <td colspan="3">
         <div class="details">



          <p>
           <bold>Discount</bold>
          <div class="searchable">
           <input type="number" required class="input__searchable"
            wire:model.defer="priceAndValues.{{ $index }}.price.discount">
          </div>
          </p>
          <p>
           <bold>VAT</bold>
          <div class="searchable">
           <input type="number" required
            class="input__searchable"wire:model.defer="priceAndValues.{{ $index }}.price.vat">
          </div>
          </p>
          <p>
           <bold>Price</bold>
          <div class="searchable">
           <input type="number" required
            class="input__searchable"wire:model.defer="priceAndValues.{{ $index }}.price.price">
          </div>
          </p>
         </div>
        </td>
       </tr>
       @php
        $j++;
       @endphp
      @endforeach
     </tbody>
    </table>
   </div>

  </div>
 </aside>
 {{-- Table Add Multiple --}}
 <aside>
  <div class="background background--center  @if ($addrelatedprice) active @endif"></div>
  <div class="aside aside--table @if ($addrelatedprice) active @endif">


   {{-- Navigation --}}
   <nav class="nav--controls">
    <h1 class="table--name">
     {{ __('Add related Pricelist') }}
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
       <th style="width: unset !important;">
        <button class="table--btn">
         Product
        </button>
       </th>
       <th>
        <button class="table--btn">Pricelist</button>
       </th>
       <th>
        <button class="table--btn">Value without VAT</button>
       </th>
       <th class="hidden">
        <button class="table--btn">Discount %</button>
       </th>
       <th class="hidden" style="width: unset !important;">
        <button class="table--btn">VAT %</button>
       </th>
       <th class="hidden" style="width: unset !important;">
        <button class="table--btn">Price</button>
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
      @foreach ($priceAndValues as $index => $priceAndValue)
       <tr class="expandable-row" wire:key="price-row-{{ $index }}">
        <td wire:click="expandRow3({{ $index }})" style="width: unset !important;">
         {{ $item->name }}
        </td>
        <td wire:click="expandRow3({{ $index }})">
         @if ($priceAndValue['allow'])
          <div class="searchable active">
           <!-- Dropdown Header -->
           <input class="input" wire:model.debounce.300ms="searchadd" placeholder="Search..." type="text">
           <button class="button__searchable" wire:click.prevent="dennyselect({{ $index }})">
            <svg>
             <path stroke="none" d="M0 0h24v24H0z" fill="none" />
             <path d="M10 10l-6 6v4h4l6 -6m1.99 -1.99l2.504 -2.504a2.828 2.828 0 1 0 -4 -4l-2.5 2.5" />
             <path d="M13.5 6.5l4 4" />
             <path class="button__searchable--line" d="M3 3l18 18" />
            </svg>
           </button>
           <div class="content__searchable">
            <div class="list__searchable">
             @if (count($addprices) >= 1)
              @foreach ($addprices as $pri)
               <button class="item__searchable"
                wire:click.prevent="selectitem({{ $index }}, {{ $pri->id }}, '{{ $pri->name }}')">
                {{ $pri->name }}
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
           <button class="input__searchable">
            @if ($priceAndValue['itemselected'])
             {{ $priceAndValue['itemselected'] }}
            @else
             {{ __('Select a item') }}
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
         <input type="hidden" wire:model.defer="priceAndValues.{{ $index }}.price.name">
        </td>
        <td wire:click="expandRow3({{ $index }})">
         <div class="searchable">
          <input type="text" required class="input__searchable"
           wire:model.defer="priceAndValues.{{ $index }}.price.value">
         </div>
        </td>
        <td class="hidden" wire:click="expandRow3({{ $index }})">
         <div class="searchable">
          <input type="number" required class="input__searchable"
           wire:model.defer="priceAndValues.{{ $index }}.price.discount">
         </div>
        </td>
        <td class="hidden" wire:click="expandRow3({{ $index }})">
         <div class="searchable">
          <input type="number" required
           class="input__searchable"wire:model.defer="priceAndValues.{{ $index }}.price.vat">
         </div>
        </td>
        <td class="hidden" wire:click="expandRow3({{ $index }})">
         <div class="searchable">
          <input type="number" required
           class="input__searchable"wire:model.defer="priceAndValues.{{ $index }}.price.price">
         </div>
        </td>
        <td style="width: unset !important;">
         <div style="display: flex;">
          @if ($index == $row4 - 1)
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
          @else
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
       <tr class="details-row  @if ($row3 === $k) active @endif">
        <td colspan="3">
         <div class="details">



          <p>
           <bold>Discount</bold>
          <div class="searchable">
           <input type="number" required class="input__searchable"
            wire:model.defer="priceAndValues.{{ $index }}.price.discount">
          </div>
          </p>
          <p>
           <bold>VAT</bold>
          <div class="searchable">
           <input type="number" required
            class="input__searchable"wire:model.defer="priceAndValues.{{ $index }}.price.vat">
          </div>
          </p>
          <p>
           <bold>Price</bold>
          <div class="searchable">
           <input type="number" required
            class="input__searchable"wire:model.defer="priceAndValues.{{ $index }}.price.price">
          </div>
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
   class="button button--flexed button--fill button--primary @if ($showrelatedprice) button--secondary active @endif"
   wire:click.prevent="@if ($showrelatedprice === false) $set('showrelatedprice', true) @else $set('showrelatedprice', false) @endif">
   {{ __('Price List ') }}({{ $item->product_prices->count() }})
   <svg>
    <polyline points="6 9 12 15 18 9"></polyline>
   </svg>
  </button>
  <button class="button button--secondary" wire:click.prevent="addrelated()">
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
    <button class="button button--primary button--centered button--long dropdown__button"
     tooltip="Actions with checked" tooltip-top>
     <span>With Checked({{ count($checked) }})</span>
    </button>
    {{-- Dropdown Content --}}
    <div class="dropdown__content">
     <div class="dropdown__container">
      <button class="button button--primary button--long" wire:click="confirmItemsRemoval()">
       Delete
      </button>
      <button class="button button--primary button--long" wire:click="editSelected">
       Edit
      </button>
     </div>
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
       <input type="checkbox" id="selectPage16" wire:model="selectPage" />
       <label for="selectPage16"></label>
      </div>
     </th>
     @foreach ($selectedColumns as $index => $column)
      @if ($this->showColumn($column))
       <th @if ($index > count($selectedColumns) - 5) class="hidden" @endif>
        <button wire:click="sortBy('{{ $column }}')"
         class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
         {{ $column }}
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
    @if ($relatedprices->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($selectedColumns) + 2 }}">No record found.</td>
     </tr>
    @else
     @php
      $i = 0;
     @endphp
     @foreach ($relatedprices as $nr => $prices)
      <tr @if ($loop->last) id="last_record" @endif
       class="expandable-row @if ($this->isChecked($prices->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $prices->id }}" id="{{ $prices->id }}" wire:model="checked">
         <label for="{{ $prices->id }}"></label>
        </div>
       </td>
       @foreach ($selectedColumns as $index => $column)
        <td @if ($index > count($selectedColumns) - 5) class="hidden" @endif wire:click="expandRow({{ $nr }})">
         @if ($column === 'Id')
          {{ $prices->id }}
         @elseif ($column === 'Name')
          <a href="{{ route('show_pricelist', ['id' => $prices->pricelist->id]) }}">
           {{ $prices->pricelist->name }}
          </a>
         @elseif ($column === 'Currency')
          {{ $prices->pricelist->currency->name }}
         @elseif ($column === 'Value')
          {{ $prices->value }}
         @elseif ($column === 'PRICE')
          @if ($editedrow !== $nr)
           {{ $prices->price }}
          @else
           <div class="searchable">
            <input type="number" required class="input__searchable"
             wire:model.defer="pricelist.{{ $nr }}.price">
           </div>
          @endif
         @elseif ($column === 'Value without Discount')
          {{ $prices->value_no_discount }}
         @elseif ($column === 'Discount')
          @if ($editedrow !== $nr)
           {{ $prices->discount }}%
          @else
           <div class="searchable">
            <input type="number" required class="input__searchable"
             wire:model.defer="pricelist.{{ $nr }}.discount">
           </div>
          @endif
         @elseif ($column === 'Value without VAT')
          @if ($editedrow !== $nr)
           {{ $prices->value_no_vat }}
          @else
           <div class="searchable">
            <input type="number" required class="input__searchable"
             wire:model.defer="pricelist.{{ $nr }}.value">
           </div>
          @endif
         @elseif ($column === 'VAT')
          @if ($editedrow !== $nr)
           {{ $prices->vat }}%
          @else
           <div class="searchable">
            <input type="number" required class="input__searchable"
             wire:model.defer="pricelist.{{ $nr }}.vat">
           </div>
          @endif
         @else
          {{ $prices->$column }}
         @endif
        </td>
       @endforeach
       <td style="border-right: none">
        <div style="display: flex;">
         @if ($editedrow !== $nr)
          <button class="button button--secondary button--sm"
           wire:click.prevent="edititem({{ $prices->id }}, {{ $prices->pricelist->id }}, {{ $nr }})">
           <svg>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
            </path>
           </svg>
          </button>
          <button class="button button--secondary button--sm"
           wire:click.prevent="confirmItemRemoval({{ $prices->id }})">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
            </path>
           </svg>
          </button>
         @else
          <button class="button button--secondary button--sm"
           wire:click.prevent="confirmitem({{ $nr }},{{ $prices->id }})">
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
      <tr class="details-row  @if ($row === $i) active @endif">
       <td colspan="17">
        <div class="details">
         @foreach ($selectedColumns as $index => $column)
          @if ($index >= count($selectedColumns) - 4)
           @if ($column === 'Id')
            <p>
             <bold>{{ $column }}:</bold>
             {{ $prices->id }}
            </p>
           @elseif ($column === 'Name')
            <p>
             <bold>{{ $column }}:</bold>
             @if ($editedrow !== $nr)
              <a href="{{ route('show_pricelist', ['id' => $prices->pricelist->id]) }}">
               {{ $prices->pricelist->name }}
              </a>
             @else
              @if ($allow)
               <div class="searchable active">
                <!-- Dropdown Header -->
                <input class="input" wire:model.debounce.300ms="searchadd" placeholder="Search..." type="text">
                <button class="button__searchable" wire:click.prevent="dennyselect({{ $nr }})">
                 <svg>
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M10 10l-6 6v4h4l6 -6m1.99 -1.99l2.504 -2.504a2.828 2.828 0 1 0 -4 -4l-2.5 2.5" />
                  <path d="M13.5 6.5l4 4" />
                  <path class="button__searchable--line" d="M3 3l18 18" />
                 </svg>
                </button>
                <div class="content__searchable">
                 <div class="list__searchable">
                  @if (count($addprices) >= 1)
                   @foreach ($addprices as $pri)
                    <button class="item__searchable" wire:click.prevent="select({{ $pri->id }})">
                     {{ $pri->name }}
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
                <button class="input__searchable">
                 @if (!$itemselected)
                  {{ $itemselected->name }}
                 @else
                  {{ __('Select a product') }}
                 @endif
                </button>
                <button class="button__searchable" wire:click.prevent="allow({{ $nr }})">
                 <svg>
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M10 10l-6 6v4h4l6 -6m1.99 -1.99l2.504 -2.504a2.828 2.828 0 1 0 -4 -4l-2.5 2.5" />
                  <path d="M13.5 6.5l4 4" />
                  <path class="button__searchable--line" d="M3 3l18 18" />
                 </svg>
                </button>
               </div>
              @endif
             @endif
            </p>
           @elseif ($column === 'Currency')
            <p>
             <bold>{{ $column }}:</bold>
             {{ $prices->pricelist->currency->name }}
            </p>
           @elseif ($column === 'Value')
            <p>
             <bold>{{ $column }}:</bold>
             {{ $prices->value }}
            </p>
           @elseif ($column === 'Value without Discount')
            <p>
             <bold>{{ $column }}:</bold>
             {{ $prices->value_no_discount }}
            </p>
           @elseif ($column === 'Discount')
            <p>
             <bold>{{ $column }}:</bold>
             @if ($editedrow !== $nr)
              {{ $prices->discount }}%
             @else
              <div class="searchable">
               <input type="number" required class="input__searchable"
                wire:model.defer="pricelist.{{ $nr }}.discount">
              </div>
             @endif
            </p>
           @elseif ($column === 'Value without VAT')
            <p>
             <bold>{{ $column }}:</bold>
             @if ($editedrow !== $nr)
              {{ $prices->value_no_vat }}
             @else
              <div class="searchable">
               <input type="number" required class="input__searchable"
                wire:model.defer="pricelist.{{ $nr }}.value">
              </div>
             @endif
            </p>
           @elseif ($column === 'VAT')
            <p>
             <bold>{{ $column }}:</bold>
             @if ($editedrow !== $nr)
              {{ $prices->vat }}%
             @else
              <div class="searchable">
               <input type="number" required class="input__searchable"
                wire:model.defer="pricelist.{{ $nr }}.vat">
              </div>
             @endif
            </p>
           @elseif ($column === 'PRICE')
            <p>
             <bold>{{ $column }}:</bold>
             @if ($editedrow !== $nr)
              {{ $prices->price }}
             @else
              <div class="searchable">
               <input type="number" required class="input__searchable"
                wire:model.defer="pricelist.{{ $nr }}.price">
              </div>
             @endif
            </p>
           @else
            <p>
             <bold>{{ $column }}:</bold>
             {{ $prices->$column }}
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


  {{-- Load More Manual --}}
  @if (count($relatedprices) >= 10)
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="load">
    Load more
   </button>
  @endif
 </div>
</div>
