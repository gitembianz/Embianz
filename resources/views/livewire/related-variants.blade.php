<div>
 <x-alert />
 <div class="accordion">
  <div class="accordion__btn-flex">
   <button class="accordion__btn"
    wire:click.prevent="@if ($showvariant === false) $set('showvariant', true) @else $set('showvariant', false) @endif">
    {{ __('Product Variants ') }}({{ $item->variants->count() }})
   </button>
   <button wire:click.prevent="addrelated()" class="accordion__upload">
    <svg>
     <line x1="12" y1="5" x2="12" y2="19"></line>
     <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
   </button>
  </div>

  @if ($addvariant)
   <div class="modal" id="modalelements" style="display: block">
    <div class="modal-content modal--tabel">
     <div class="panel__header">
      <h1 class="panel__header--title">
       {{ __('Add new variant') }}
      </h1>
      <input class="panel__header--input panel__header--checked" wire:click.prevent="saveitems()" type="button"
       value="Save">
     </div>
     <div style="overflow-y: auto; position: relative; background: white; height: 100%;">
      <table class="table table-top">
       <thead>
        <tr>
         <th class="wid-2"><button class="table__header--btn">Parrent Product</button></th>
         <th class="wid-2"><button class="table__header--btn">Variant</button></th>
         <th class="wid-2"><button class="table__header--btn">Reference</button></th>
         <th class="wid-1"><button class="table__header--btn">Value</button></th>
         <th class="wid-2"><button class="table__header--btn">Dispalyed type</button></th>

         <th class="wid-1"></th>
        </tr>
       </thead>
      </table>
      <table class="table" style="margin-top: 1.5rem">
       <tbody>
        @foreach ($variantAndValues as $index => $variantAndValue)
         <tr wire:key="variant-row-{{ $index }}">
          <td class="wid-2" data-title="Name">
           {{ $item->name }}
          </td>
          <td class="wid-2" data-title="Pricelist">
           @if ($variantAndValue['allow'])
            <div class="table__drop" style="position: relative">
             <input class="table__drop--input" wire:model.debounce.300ms="searchadd" placeholder="Search..."
              type="text">
             <ul class="table__drop--list">
              @if (count($addvariants) >= 1)
               @foreach ($addvariants as $var)
                <li class="table__drop--item"
                 wire:click.prevent="selectitem({{ $index }}, {{ $var->id }}, '{{ $var->name }}')">
                 {{ $var->name }}
                </li>
               @endforeach
              @else
               <li class="table__drop--item">{{ __('No product-variants found') }}</li>
              @endif
             </ul>
             <svg wire:click.prevent="denny({{ $index }})"
              style="background: #35424b;position: absolute;top: 50%;transform: translateY(-50%);right: 10px;border-radius: 5px;padding: 5px;opacity: .7;stroke: white;"
              width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
             </svg>
            </div>
           @else
            <div style="position: relative" class="table__drop--input">
             @if ($variantAndValue['itemselected'])
              {{ $variantAndValue['itemselected'] }}
             @else
              {{ __('Select a item') }}
             @endif
             <svg wire:click.prevent="allowselect({{ $index }})"
              style="background: #35424b;position: absolute;top: 50%;transform: translateY(-50%);right: 10px;border-radius: 5px;padding: 5px;opacity: .7;stroke: white;"
              width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
              <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
              </path>
             </svg>
            </div>
           @endif
           <input type="hidden" wire:model.defer="variantAndValues.{{ $index }}.variant.name">
          </td>

          <td class="wid-2" data-title="Reference">
           <select class="table__drop--input"
            wire:model.defer="variantAndValues.{{ $index }}.variant.reference">
            @foreach ($references as $reference)
             <option value="{{ $reference->id }}">{{ $reference->name }}</option>
            @endforeach
           </select>
           {{-- <input type="text"  class="table__drop--input"
            wire:model.defer="variantAndValues.{{ $index }}.variant.reference"> --}}
          </td>

          <td class="wid-1" data-title="Value">
           <input placeholder="Insert a value" type="text" class="table__drop--input"
            wire:model.defer="variantAndValues.{{ $index }}.variant.value">
          </td>
          <td class="wid-2" data-title="Dispalyed type">
           <select class="table__drop--input"
            wire:model.defer="variantAndValues.{{ $index }}.variant.display">
            <option value="text">text</option>
            <option value="image">image</option>
            <option value="image & text">image & text</option>
           </select>
           {{-- <input type="text"  class="table__drop--input"
            wire:model.defer="variantAndValues.{{ $index }}.variant.reference"> --}}
          </td>

          {{-- <td class="wid-1" data-title="Price">
           <input type="text" required class="table__drop--input"
            wire:model.defer="variantAndValues.{{ $index }}.variant.value">
          </td>
          <td class="wid-1" data-title="Discount">
           <input type="number" max="100" class="table__drop--input"
            wire:model.defer="variantAndValues.{{ $index }}.variant.discount">
          </td>

          <td class="wid-1" data-title="TVA">
           <input type="text" required class="table__drop--input"
            wire:model.defer="variantAndValues.{{ $index }}.variant.vat"
            value="{{ old('variantAndValues.' . $index . '.variant.vat', 19) }}">
          </td> --}}

          <td class="wid-1" data-title="Action">
           <div class="table__buttons">
            @if ($index == $row - 1)
             <button type="button" class="edit" wire:click="plus">
              <svg>
               <line x1="12" y1="5" x2="12" y2="19">
               </line>
               <line x1="5" y1="12" x2="19" y2="12">
               </line>
              </svg>
             </button>
             <button type="button" class="save" wire:click="clear({{ $index }})">
              <svg>
               <line x1="18" y1="6" x2="6" y2="18">
               </line>
               <line x1="6" y1="6" x2="18" y2="18">
               </line>
              </svg>
             </button>
            @endif
            @if ($index != $row - 1)
             <button type="button" class="save" wire:click="clear({{ $index }})">
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
       </tbody>
      </table>
     </div>
     <span class="top-up-modal delete" wire:click="closemodal">
      <svg>
       <line x1="18" y1="6" x2="6" y2="18">
       </line>
       <line x1="6" y1="6" x2="18" y2="18">
       </line>
      </svg>
     </span>
     <a href="#top1" class="top-up-modal" id="topUp">
      <svg>
       <polyline points="18 15 12 9 6 15"></polyline>
      </svg>
     </a>
    </div>
   </div>
  @endif


  {{-- end add  references --}}
  @if ($showvariant)
   <div class="accordion__content">
    <div>
     @if ($item->variants->count() > 0)
      {{-- delete single record --}}
      <div class="modal" id="confirmationmodalvariant">
       <div class="modal-content">
        <h1 class="modal-content-title">
         {{ __('Are you sure to delete this record?') }}
        </h1>
        <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button"
         value="Confirm" id="confirmLoad">
        <input class="modal-content-btn delete" type="button"
         onclick="document.getElementById('confirmationmodalvariant').style.display='none'" value="Cancel">
        <span class="modal-content-btn delete"
         onclick="document.getElementById('confirmationmodalvariant').style.display='none'">
         <svg>
          <line x1="18" y1="6" x2="6" y2="18">
          </line>
          <line x1="6" y1="6" x2="18" y2="18">
          </line>
         </svg>
        </span>
       </div>
      </div>
      {{-- delete myltiple records --}}
      <div class="modal" id="confirmationmodalmultiple">
       <div class="modal-content">
        <h1 class="modal-content-title">
         {{ __('Are you sure to delete those records?') }}
        </h1>
        <input wire:click.prevent="deleteRecords()" class="modal-content-btn submit" type="button" value="Confirm"
         id="confirmLoad">
        <input class="modal-content-btn delete" type="button"
         onclick="document.getElementById('confirmationmodalmultiple').style.display='none'" value="Cancel">
        <span class="modal-content-btn delete"
         onclick="document.getElementById('confirmationmodalmultiple').style.display='none'">
         <svg>
          <line x1="18" y1="6" x2="6" y2="18">
          </line>
          <line x1="6" y1="6" x2="18" y2="18">
          </line>
         </svg>
        </span>
       </div>
      </div>
      {{-- Header of the table --}}
      <div class="panel__header">
       <input class="panel__header--input" type="text" wire:model.debounce.300ms="search" placeholder="Search..."
        style="grid-column: 1/4">
       <div class="panel__header--bundle">
        <div class="dropdown">
         <button
          wire:click.prevent="@if ($col === false) $set('col', true) @else $set('col', false) @endif"
          class="dropdown-button">
          Columns
         </button>
         @if ($col)
          <div class="dropdown-list" style="display: flex;">
           @foreach ($columns as $column)
            <div class="dropdown-item">
             <input type="checkbox" wire:model="selectedColumns" value="{{ $column }}"
              {{ in_array($column, $selectedColumns) ? 'checked' : '' }}>
             <label>{{ $column }}</label>
            </div>
           @endforeach
          </div>
         @endif
        </div>
        <div class="dropdown none" @if ($checked) style="display: unset; z-index: 5;" @endif>
         <button
          wire:click.prevent="@if ($all === false) $set('all', true); $set('col', false) @else $set('all', false) @endif"
          class="dropdown-button none" @if ($checked) style="display: flex" @endif>
          With Checked({{ count($checked) }})
         </button>
         @if ($checked && $all)
          <div class="dropdown-list" style="display: flex;">
           <button class="dropdown-item delete" type="button" wire:click="confirmRemovalmultiple()">
            Delete
           </button>
          </div>
         @endif
        </div>
       </div>
       @if ($selectPage && $selectAll)
        <div class="panel__header--checked">
         <p>
          You selected <strong>{{ count($checked) }}</strong> items.
         </p>
        </div>
       @elseif($selectPage)
        <div class="panel__header--checked" wire:click="selectAll">
         <p>
          You selected {{ count($checked) }} items, select all?
         </p>
        </div>
       @endif
      </div>
      {{-- Table --}}
      <table class="table">
       <thead>
        <tr>
         <th><input type="checkbox" wire:model="selectPage"></th>
         @if ($this->showColumn('Id'))
          <th wire:click="sortBy('id')">
           <button class="table__header--btn">
            ID
           </button>
          </th>
         @endif
         @if ($this->showColumn('Parrent Name'))
          <th>
           <button class="table__header--btn">
            Parrent Name
           </button>
          </th>
         @endif
         @if ($this->showColumn('Variant Name'))
          <th>
           <button class="table__header--btn">
            Variante Name
           </button>
          </th>
         @endif
         @if ($this->showColumn('Reference'))
          <th wire:click="sortBy('reference')">
           <button class="table__header--btn">
            Reference
           </button>
          </th>
         @endif
         @if ($this->showColumn('Value'))
          <th>
           <button class="table__header--btn">
            Value
           </button>
          </th>
         @endif
         @if ($this->showColumn('Dispalyed type'))
          <th>
           <button class="table__header--btn">
            Dispalyed type
           </button>
          </th>
         @endif
         <th wire:click="sortBy('created_at')">
          <button class="table__header--btn">
           Created at
          </button>
         </th>
         <th wire:click="sortBy('created_at')">
          <button class="table__header--btn">
           Updated at
          </button>
         </th>
         <th></th>
        </tr>
       </thead>
       <tbody>
        @if ($variants->isEmpty())
         <tr>
          <td class="table__empty" colspan="{{ count($columns) + 3 }}">No record
           found.</td>
         </tr>
        @else
         @foreach ($variants as $index => $variant)
          <tr class="@if ($this->isChecked($variant->id)) table__row--selected @endif">
           <td data-title="Check">
            <input type="checkbox" value="{{ $variant->id }}" wire:model="checked">
           </td>
           @if ($this->showColumn('Id'))
            <td data-title="ID">
             {{ $variant->id }}
            </td>
           @endif
           @if ($this->showColumn('Parrent Name'))
            <td data-title="Name">
             <a href="{{ route('show_product', ['id' => $item->id]) }}">
              {{ $item->name }}</a>
            </td>
           @endif
           @if ($this->showColumn('Variant Name'))
            <td data-title="Variante Name">
             <a href="{{ route('show_product', ['id' => $variant->product->id]) }}">
              {{ $variant->product->name }}</a>
            </td>
           @endif
           @if ($this->showColumn('Reference'))
            <td data-title="Reference">
             @if ($editindex !== $index)
              {{ $variant->reference->name }}
             @else
              <select class="table__drop--input" wire:model.defer="var.{{ $index }}.ref">
               @foreach ($references as $reference)
                <option value="{{ $reference->id }}">{{ $reference->name }}</option>
               @endforeach
              </select>
             @endif
            </td>
           @endif
           @if ($this->showColumn('Value'))
            <td data-title="Value">
             @if ($editindex !== $index)
              {{ $variant->value }}
             @else
              <input type="text" class="input" wire:model.defer="var.{{ $index }}.value">
             @endif
            </td>
           @endif
           @if ($this->showColumn('Dispalyed type'))
            <td data-title="Dispalyed type">
             @if ($editindex !== $index)
              {{ $variant->displayed }}
             @else
              <select class="table__drop--input" wire:model.defer="var.{{ $index }}.displayed">
               <option value="text">text</option>
               <option value="image">image</option>
               <option value="image & text">image & text</option>
              </select>
             @endif
            </td>
           @endif
           <td data-title="Created At">
            {{ $variant->created_at }}
           </td>
           <td data-title="Created At">
            {{ $variant->updated_at }}
           </td>
           <td data-title="Action">
            <div class="table__buttons">
             @if ($editindex !== $index)
              <button class="edit" wire:click.prevent="edititem({{ $index }}, {{ $variant->id }})">
               <svg>
                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                </path>
               </svg>
              </button>
              <button class="delete" wire:click.prevent="confirmRemoval({{ $variant->id }})">
               <svg>
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                </path>
               </svg>
              </button>
             @else
              <button class="edit" wire:click.prevent="saveitem({{ $index }} , {{ $variant->id }})">
               <svg>
                <polyline points="20 6 9 17 4 12"></polyline>
               </svg>
              </button>
              <button class="save" wire:click.prevent="canceledit()">
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
      @if ($perPage <= count($variants))
       <div class="table__load-more" wire:click="load">
        Load more
       </div>
      @endif
     @else
      <p class="mt-2">No records </p>
     @endif
    </div>
   </div>
  @endif
 </div>
</div>
