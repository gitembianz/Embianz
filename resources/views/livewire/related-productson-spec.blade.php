<div class="accordion @if ($showrelatedprods) active @endif">

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
    <button class="button button--primary button--centered" wire:click.prevent="confirmmultiple()">
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
       <th style="width: auto; !important">
        <div style="display: flex;">
         <button class="table--btn">
          Specification
         </button>
         <!-- Button for mentain height of row -->
         <button class="button button--secondary button--sm" style="opacity: 0">
          <svg>
           <polyline points="3 6 5 6 21 6"></polyline>
           <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
         </button>
        </div>
       </th>
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
       <th class="hidden" style="width: auto; !important">
        <button class="table--btn">
         Value
        </button>
       </th>
      </tr>
     </thead>
     <tbody>
      @php
       $j = 0;
      @endphp
      @foreach ($prod as $index => $pro)
       <tr class="expandable-row">
        <td wire:click="expandRow2({{ $index }})" style="width: auto; !important">
         <div style="display: flex; align-items: center; justify-content: center;">
          {{ $item->name }}
          <button class="button button--secondary button--sm" style="opacity: 0">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
           </svg>
          </button>
         </div>
        </td>
        <td wire:click="expandRow2({{ $index }})">
         {{ $prod[$index]['itemselected'] }}
        </td>
        <td class="hidden">
         @if ($pro['allow'])
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
             @if (count($addprods) >= 1)
              @foreach ($addprods as $pr)
               <button class="item__searchable"
                wire:click.prevent="selectitem({{ $index }}, {{ $pr->id }}, '{{ $pr->name }}')">
                {{ $pr->name }}
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
            @if ($pro['itemselected'])
             {{ $pro['itemselected'] }}
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
         <input type="hidden" wire:model.defer="prod.{{ $index }}.product.name">
        </td>
        <td class="hidden" style="width: auto; !important">
         <input type="text" required class="input button--fill button--xs"
          wire:model.defer="prod.{{ $index }}.product.value">
        </td>
       </tr>
       <tr class="details-row  @if ($rind2 === $j) active @endif">
        <td colspan="3">
         <div class="details">
          <p>
           <bold>Product Name</bold>
           @if ($pro['allow'])
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
               @if (count($addprods) >= 1)
                @foreach ($addprods as $pr)
                 <button class="item__searchable"
                  wire:click.prevent="selectitem({{ $index }}, {{ $pr->id }}, '{{ $pr->name }}')">
                  {{ $pr->name }}
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
              @if ($pro['itemselected'])
               {{ $pro['itemselected'] }}
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
           <input type="hidden" wire:model.defer="prod.{{ $index }}.product.name">
          </p>
          <p>
           <bold>Value</bold>
          <div class="searchable">
           <input class="input__searchable" type="text" required
            wire:model.defer="prod.{{ $index }}.product.value">
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
  <div class="background background--center @if ($addrelatedproducts) active @endif"></div>
  <div class="aside aside--table @if ($addrelatedproducts) active @endif">
   {{-- Navigation --}}
   <nav class="nav--controls">
    <h1 class="table--name">
     {{ __('Add related products') }}
    </h1>
    <button class="button button--primary button--centered" wire:click.prevent="saveprod()">
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
       <th style="width: auto; !important">
        <button class="table--btn">
         Specification
        </button>
       </th>
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
       <th class="hidden" style="width: auto;">
        <button class="table--btn">
         Value
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
      @foreach ($prod as $index => $pro)
       <tr class="expandable-row">
        <td wire:click="expandRow3({{ $index }})" style="width: auto; !important">
         <div style="display: flex; align-items: center; justify-content: center;">
          {{ $item->name }}
          <button class="button button--secondary button--sm" style="opacity: 0">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
           </svg>
          </button>
         </div>
        </td>
        <td wire:click="expandRow3({{ $index }})">
         {{ $prod[$index]['itemselected'] }}
        </td>
        <td class="hidden">
         @if ($pro['allow'])
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
             @if (count($addprods) >= 1)
              @foreach ($addprods as $pr)
               <button class="item__searchable"
                wire:click.prevent="selectitem({{ $index }}, {{ $pr->id }}, '{{ $pr->name }}')">
                {{ $pr->name }}
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
            @if ($pro['itemselected'])
             {{ $pro['itemselected'] }}
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
         <input type="hidden" wire:model.defer="prod.{{ $index }}.product.name">
        </td>
        <td class="hidden" style="width: auto;">
         <input type="text" required class="input button--fill button--xs"
          wire:model.defer="prod.{{ $index }}.product.value">
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
       <tr class="details-row  @if ($rind3 === $k) active @endif">
        <td colspan="3">
         <div class="details">
          <p>
           <bold>Product Name</bold>
           @if ($pro['allow'])
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
               @if (count($addprods) >= 1)
                @foreach ($addprods as $pr)
                 <button class="item__searchable"
                  wire:click.prevent="selectitem({{ $index }}, {{ $pr->id }}, '{{ $pr->name }}')">
                  {{ $pr->name }}
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
              @if ($pro['itemselected'])
               {{ $pro['itemselected'] }}
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
           <input type="hidden" wire:model.defer="prod.{{ $index }}.product.name">
          </p>
          <p>
           <bold>Value</bold>
          <div class="searchable">
           <input class="input__searchable" type="text" required
            wire:model.defer="prod.{{ $index }}.product.value">
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
   class="button button--flexed button--fill button--primary @if ($showrelatedprods) button--secondary active @endif"
   wire:click.prevent="@if ($showrelatedprods === false) $set('showrelatedprods', true) @else $set('showrelatedprods', false) @endif">
   {{ __('Products ') }}({{ $relatedprods->total() }})
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
       <input type="checkbox" id="selectPage21" wire:model="selectPage" />
       <label for="selectPage21"></label>
      </div>
     </th>
     @if ($this->showColumn('Id'))
      <th>
       <button class="table--btn">
        ID
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Name'))
      <th>
       <button class="table--btn">
        Name
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Unit'))
      <th class="hidden">
       <button class="table--btn">
        Unit
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Value'))
      <th class="hidden">
       <button class="table--btn">
        Value
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Created At'))
      <th class="hidden">
       <button class="table--btn">
        Created At
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     <th style="border-left: none; border-right: none;">
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
     $i = 0;
    @endphp
    @if ($relatedprods->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($relatedprods as $index => $prod)
      <tr @if ($loop->last) id="last_record" @endif
       class="expandable-row @if ($this->isChecked($prod->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $prod->id }}" id="{{ $prod->id }}" wire:model="checked">
         <label for="{{ $prod->id }}"></lab>
        </div>
       </td>

       @if ($this->showColumn('Id'))
        <td wire:click="expandRow({{ $index }})">{{ $prod->id }}</td>
       @endif
       @if ($this->showColumn('Name'))
        <td>
         @if ($editedrow !== $index)
          <a href="{{ route('show_product', ['id' => $prod->product->id]) }}">
           {{ $prod->product->name }}
          </a>
         @else
          @if ($allow)
           <div class="searchable active">
            <!-- Header -->
            <input class="input__searchable" type="text" wire:model.debounce.300ms="searchadd"
             placeholder="Search..">
            <button class="button__searchable" wire:click.prevent="denny()">
             <svg>
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M10 10l-6 6v4h4l6 -6m1.99 -1.99l2.504 -2.504a2.828 2.828 0 1 0 -4 -4l-2.5 2.5" />
              <path d="M13.5 6.5l4 4" />
              <path class="button__searchable--line" d="M3 3l18 18" />
             </svg>
            </button>

            <!-- Body -->
            <div class="content__searchable">
             <div class="list__searchable">
              @if ($addprods->isEmpty())
               <button class="item__searchable">
                No record found
               </button>
              @else
               @foreach ($addprods as $pr)
                <button class="item__searchable" wire:click.prevent="select({{ $pr->id }})">
                 {{ $pr->name }}
                </button>
               @endforeach
              @endif
             </div>
            </div>
           </div>
          @else
           <div class="searchable">
            <button class="input__searchable">
             {{ $itemselected }}
            </button>
            <button class="button__searchable" wire:click.prevent="allow">
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
        </td>
       @endif
       @if ($this->showColumn('Unit'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $prod->spec->um }}
        </td>
       @endif
       @if ($this->showColumn('Value'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         @if ($editedrow !== $index)
          {{ $prod->value }}
         @else
          <div class="searchable">
           <input class="input__searchable" type="text" required
            wire:model.defer="prod.{{ $index }}.product.value">
          </div>
         @endif
        </td>
       @endif
       @if ($this->showColumn('Created At'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $prod->created_at }}
        </td>
       @endif

       <td>
        @if ($editedrow !== $index)
         <div style="display: flex;">
          <button class="button button--secondary button--sm"
           wire:click.prevent="editprod({{ $prod->id }}, {{ $prod->product->id }}, {{ $index }})">
           <svg>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
            </path>
           </svg>
          </button>
          <button class="button button--secondary button--sm"
           wire:click.prevent="confirmItemRemoval({{ $prod->id }})">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
           </svg>
          </button>
         </div>
        @else
         <div style="display: flex;">
          <button class="button button--secondary button--sm"
           wire:click.prevent="confirmprod({{ $index }},{{ $prod->id }})">
           <svg>
            <polyline points="20 6 9 17 4 12"></polyline>
           </svg>
          </button>
          <button class="button button--secondary button--sm" wire:click.prevent="canceledit()">
           <svg>
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
           </svg>
          </button>
         </div>
        @endif
       </td>
      </tr>
      <tr class="details-row  @if ($rind === $i) active @endif">
       <td colspan="{{ count($columns) + 2 }}">
        <div class="details">

         @if ($this->showColumn('Unit'))
          <p>
           <bold>Unit</bold>
           {{ $prod->spec->um }}
          </p>
         @endif
         @if ($this->showColumn('Value'))
          <p>
           <bold>Value</bold>
           @if ($editedrow !== $index)
            {{ $prod->value }}
           @else
            <input type="text" required class="input__searchable" wire:model="product.{{ $index }}.value">
           @endif
          </p>
         @endif
         @if ($this->showColumn('Created At'))
          <p>
           <bold>Created At</bold>
           {{ $prod->created_at }}
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
  @if (count($relatedprods) >= 10)
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="load">
    Load more
   </button>
  @endif
 </div>
</div>
