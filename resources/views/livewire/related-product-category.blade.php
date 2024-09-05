<div class="accordion @if ($showrelateitems) active @endif">
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

 {{-- Link Record || Link Records --}}
 <aside>
  <div class="background background--center @if ($linksingle || $linkmultiple) active @endif" style="z-index: 1100;">
  </div>
  <div class="aside aside--confirm @if ($linksingle || $linkmultiple) active @endif" style="z-index: 1100;">
   <span>
    @if ($linksingle)
     Are you sure to link this record?
    @else
     Are you sure to link those records?
    @endif
   </span>
   @if ($linksingle)
    <button class="button button--primary button--long" wire:click="linkSingleRecord">
     <span>Confirm</span>
    </button>
   @else
    <button class="button button--primary button--long" wire:click="linkRecords()">
     <span>Confirm</span>
    </button>
   @endif
   <button class="button button--danger button--long" wire:click="cancel_link()">
    <span>Cancel</span>
   </button>
  </div>
 </aside>

 {{-- Table Add Multiple --}}
 <aside>
  <div class="background background--center @if ($showTable) active @endif"></div>
  <div class="aside aside--table @if ($showTable) active @endif">
   {{-- Navigation --}}
   <nav class="nav--controls">
    <h1 class="table--name">
     {{ __('Add related products') }}
    </h1>
    <button class="button button--danger button--centered" wire:click="closemodal" tooltip="Close Modal" tooltip-left>
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M15 19v-2a2 2 0 0 1 2 -2h2" />
      <path d="M15 5v2a2 2 0 0 0 2 2h2" />
      <path d="M5 15h2a2 2 0 0 1 2 2v2" />
      <path d="M5 9h2a2 2 0 0 0 2 -2v-2" />
     </svg>
    </button>
   </nav>
   <nav class="nav--controls" style="margin-top: 10px">
    <input class="input input--long" type="text" wire:model.debounce.300ms="searchadd" placeholder="Search...">
    @if ($selectPageadd || $selectAlladd || $checkedadd)
     <button class="button button--primary button--centered" wire:click.prevent="confirmItemsLink()"
      tooltip="Link Multiple" tooltip-left>
      <svg>
       <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
       <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
       <g id="SVGRepo_iconCarrier">
        <path
         d="M12.7917 15.7991L14.2223 14.3676C16.5926 11.9959 16.5926 8.15054 14.2223 5.7788C11.8521 3.40707 8.0091 3.40707 5.63885 5.7788L2.77769 8.64174C0.407436 11.0135 0.407436 14.8588 2.77769 17.2306C3.87688 18.3304 5.29279 18.9202 6.73165 19"
         stroke="#000000" stroke-width="1.5" stroke-linecap="round"></path>
        <path
         d="M11.2083 8.20092L9.77769 9.63239C7.40744 12.0041 7.40744 15.8495 9.77769 18.2212C12.1479 20.5929 15.9909 20.5929 18.3612 18.2212L21.2223 15.3583C23.5926 12.9865 23.5926 9.14118 21.2223 6.76945C20.1231 5.66957 18.7072 5.07976 17.2683 5"
         stroke="#000000" stroke-width="1.5" stroke-linecap="round"></path>
       </g>
      </svg>
     </button>
    @endif

   </nav>

   {{-- Select All? --}}
   @if ($selectPageadd && $selectAlladd)
    <button class="button button--fill button--primary" style="margin-top: 10px;">
     You selected {{ count($checkedadd) }} items.
    </button>
   @elseif($selectPageadd)
    <button class="button button--fill button--secondary" style="margin-top: 10px;" wire:click.prevent="selectAlladd">
     You selected {{ count($checkedadd) }} items, select all?
    </button>
   @endif


   {{-- Tabel --}}
   <div class="table" style="height: calc(100% - 60px);">
    <table class="expandable-table">
     <thead>
      <tr>
       <th>
        <div class="checkbox--primary">
         <input type="checkbox" id="selectPageadd17" wire:model="selectPageadd" />
         <label for="selectPageadd17"></label>
        </div>
       </th>
       <th>
        <button class="table--btn">
         Product Name
        </button>
       </th>
       <th>
        <button class="table--btn">
         Product Description
        </button>
       </th>
       <th class="hidden">
        <button class="table--btn">
         Product type
        </button>
       </th>
       <th class="hidden">
        <button class="table--btn">
         Product is active?
        </button>
       </th>
       <th>
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
       $k = 0;
      @endphp
      @foreach ($prodds as $index => $product)
       <tr class="expandable-row">
        <td style="border-left: none" data-title="Check">
         <div class="checkbox--primary">
          <input type="checkbox" value="{{ $product->id }}" id="{{ $product->id }}" wire:model="checkedadd">
          <label for="{{ $product->id }}"></label>
         </div>
        </td>
        <td wire:click="expandRow2({{ $index }})">
         {{ $product->name }}
        </td>
        <td wire:click="expandRow2({{ $index }})">
         {{ $product->short_description }}
        </td>
        <td class="hidden">
         {{ $product->type }}
        </td>
        <td class="hidden">
         @if ($product->active)
          <div class="checkbox--secondary disabled">
           <input type="checkbox" for="disabled1" disabled checked>
           <label for="disabled1"></label>
          </div>
         @else
          <div class="checkbox--secondary disabled">
           <input type="checkbox" for="disabled2" disabled>
           <label for="disabled2"></label>
          </div>
         @endif
        </td>
        <td>
         <button class="button button--secondary button--sm" wire:click.prevent="confirmItemLink({{ $product->id }})">
          <svg>
           <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
           <line x1="8" y1="12" x2="16" y2="12"></line>
          </svg>
         </button>
        </td>
       </tr>
       <tr class="details-row  @if ($rind2 === $k) active @endif">
        <td colspan="3">
         <div class="details">
          <p>
           <bold>Product type</bold>
           {{ $product->type }}
          </p>
          <p>
           <bold>Product is active?</bold>
           @if ($product->active)
            <div class="checkbox--secondary disabled">
             <input type="checkbox" for="disabled3" disabled checked>
             <label for="disabled3"></label>
            </div>
           @else
            <div class="checkbox--secondary disabled">
             <input type="checkbox" for="disabled4" disabled>
             <label for="disabled4"></label>
            </div>
           @endif
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
    {{-- Load More Manual --}}
    @if ($loadAmount < $prodds->total())
     <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
      Load more
     </button>
    @endif
   </div>
  </div>
 </aside>




 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showrelateitems) button--secondary active @endif"
   wire:click.prevent="@if ($showrelateitems === false) $set('showrelateitems', true) @else $set('showrelateitems', false) @endif">
   {{ __('Products ') }}({{ $relatedproducts->total() }})
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
    <button class="button button--primary button--centered button--long" tooltip="Actions with checked" tooltip-top>
     <span>With Checked({{ count($checked) }})</span>
    </button>
    {{-- Dropdown Content --}}
    <div class="dropdown__content">
     <div class="dropdown__container">
      <button class="button button--primary button--long" wire:click="confirmItemsRemoval()">
       Delete
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
       <input type="checkbox" id="selectPage18" wire:model="selectPage" />
       <label for="selectPage18"></label>
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
     @if ($this->showColumn('Category Name'))
      <th>
       <button class="table--btn">
        Category Name
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Product Name'))
      <th>
       <button class="table--btn">
        Product Name
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Product Description'))
      <th class="hidden">
       <button class="table--btn">
        Product Description
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Product type'))
      <th class="hidden">
       <button class="table--btn">
        Product type
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Product is active?'))
      <th class="hidden">
       <button class="table--btn">
        Product is active?
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
     @if ($this->showColumn('Updated At'))
      <th class="hidden">
       <button class="table--btn">
        Updated At
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     <th>
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
    @if ($relatedproducts->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($relatedproducts as $index => $related)
      <tr class="expandable-row @if ($this->isChecked($related->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $related->id }}" id="{{ $related->id }}" wire:model="checked">
         <label for="{{ $related->id }}"></label>
        </div>
       </td>

       @if ($this->showColumn('Id'))
        <td wire:click="expandRow({{ $index }})">{{ $related->id }}</td>
       @endif
       @if ($this->showColumn('Category Name'))
        <td data-title="Name" wire:click="expandRow({{ $index }})">
         <a href="{{ route('show_category', ['id' => $related->category->id]) }}">
          {{ strip_tags($category->name) }}</a>
        </td>
       @endif
       @if ($this->showColumn('Product Name'))
        <td data-title="Name" wire:click="expandRow({{ $index }})">
         <a href="{{ route('show_product', ['id' => $related->product->id]) }}">
          {{ $related->product->name }}</a>
        </td>
       @endif

       @if ($this->showColumn('Product Description'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $related->product->short_description }}
        </td>
       @endif
       @if ($this->showColumn('Product type'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $related->product->type }}
        </td>
       @endif
       @if ($this->showColumn('Product is active?'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         @if ($related->product->active)
          <div class="checkbox--secondary disabled">
           <input type="checkbox" id="disabled5" disabled checked>
           <label for="disabled5"></label>
          </div>
         @else
          <div class="checkbox--secondary disabled">
           <input type="checkbox" id="disabled6" disabled>
           <label for="disabled6"></label>
          </div>
         @endif
        </td>
       @endif
       @if ($this->showColumn('Created At'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $related->created_at }}
        </td>
       @endif
       @if ($this->showColumn('Updated At'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $related->updated_at }}
        </td>
       @endif
       <td>
        <button class="button button--secondary button--sm"
         wire:click.prevent="confirmItemRemoval({{ $related->id }})">
         <svg>
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
         </svg>
        </button>
       </td>
      </tr>
      <tr class="details-row  @if ($rind === $i) active @endif">
       <td colspan="{{ count($columns) + 2 }}">
        <div class="details">

         @if ($this->showColumn('Product Description'))
          <p>
           <bold>Product Description</bold>
           {{ $related->product->short_description }}
          </p>
         @endif
         @if ($this->showColumn('Product type'))
          <p>
           <bold>Product type</bold>
           {{ $related->product->type }}
          </p>
         @endif
         @if ($this->showColumn('Product is active?'))
          <p>
           <bold>Product is active?</bold>
           @if ($related->product->active)
            <div class="checkbox--secondary disabled">
             <input type="checkbox" id="disabled7" disabled checked>
             <label for="disabled7"></label>
            </div>
           @else
            <div class="checkbox--secondary disabled">
             <input type="checkbox" id="disabled8" disabled>
             <label for="disabled8"></label>
            </div>
           @endif
          </p>
         @endif
         @if ($this->showColumn('Created At'))
          <p>
           <bold>Created At</bold>
           {{ $related->created_at }}
          </p>
         @endif
         @if ($this->showColumn('Updated At'))
          <p>
           <bold>Updated At</bold>
           {{ $related->updated_at }}
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


  {{-- Load More --}}
  @if ($loadAmount < $relatedproducts->total())
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
    Load more
   </button>
  @endif
 </div>

</div>
