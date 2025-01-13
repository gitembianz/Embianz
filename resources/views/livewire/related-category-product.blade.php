<div class="accordion @if ($showrelateitems) active @endif">

 <x-alert />

 {{-- Delete Record|s --}}
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

 {{-- add Related Items --}}
 <aside>
  <div class="background background--center @if ($showTable) active @endif"></div>
  <div class="aside aside--table @if ($showTable) active @endif">
   {{-- Navigation --}}
   <nav class="nav--controls">
    <h1 class="table--name">
     {{ __('Add related categories') }}
    </h1>
    <button class="button button--primary button--centered" wire:click.prevent="saveitems()">
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
      <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
      <path d="M14 4l0 4l-6 0l0 -4" />
     </svg>
    </button>
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

   {{-- Tabel --}}
   <div class="table" style="height: calc(100% - 60px);">
    <table class="expandable-table">
     <thead>
      <tr>
       <th>
        <div style="display: flex; align-items: center; justify-content: center;">

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
        <button class="table--btn">
         Category
        </button>
       </th>
       <th class="hidden">
        <button class="table--btn">
         Select a category
        </button>
       </th>
       <th class="hidden">
        <button class="table--btn">
         Is primary category?
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
        <td style="width: auto !important;" wire:click="expandRow2({{ $index }})">
         <div style="display: flex; align-items: center; justify-content: center;">
          {{ $product->name }}
          <button class="button button--secondary button--sm" style="opacity: 0">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
           </svg>
          </button>
         </div>
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
             @if (count($cats) >= 1)
              @foreach ($cats as $category)
               <button class="item__searchable"
                wire:click.prevent="selectitem({{ $index }}, {{ $category->id }}, '{{ $category->name }}')">
                {{ $category->name }}
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
             {{ __('Select a category') }}
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

        <td class="hidden" data-title="Check">
         <div class="checkbox--primary">
          <input type="checkbox" id="{{ $index }}pri"
           wire:model.defer="productsAndValues.{{ $index }}.product.primary">
          <label for="{{ $index }}pri"></label>
         </div>
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
           <bold>Related Product Name</bold>
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
               @if (count($cats) >= 1)
                @foreach ($cats as $category)
                 <button class="item__searchable"
                  wire:click.prevent="selectitem({{ $index }}, {{ $category->id }}, '{{ $category->name }}')">
                  {{ $category->name }}
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
               {{ __('Select a category') }}
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
           <bold>Sequence</bold>
          <div class="checkbox--primary">
           <input type="checkbox" id="{{ $k }}pri"
            wire:model.defer="productsAndValues.{{ $index }}.product.primary">
           <label for="{{ $k }}pri"></label>
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

 {{-- Related Items --}}
 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showrelateitems) button--secondary active @endif"
   wire:click.prevent="@if ($showrelateitems === false) $set('showrelateitems', true) @else $set('showrelateitems', false) @endif">
   {{ __('Categories ') }}({{ $relatedcats->total() }})
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
       <input type="checkbox" id="selectPage11" wire:model="selectPage" />
       <label for="selectPage11"></label>
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
     @if ($this->showColumn('Category Description'))
      <th class="hidden">
       <button class="table--btn">
        Category Description
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Category displayed elements'))
      <th class="hidden">
       <button class="table--btn">
        Category displayed elements
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Category is active?'))
      <th class="hidden">
       <button class="table--btn">
        Category is active?
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Is primary category?'))
      <th class="hidden">
       <button class="table--btn">
        Is primary category?
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
    @if ($relatedcats->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($relatedcats as $index => $related)
      <tr class="expandable-row @if ($this->isChecked($related->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $related->id }}" id="{{ $related->id }}" wire:model="checked">
         <label for="{{ $related->id }}"></label>
        </div>
       </td>

       @if ($this->showColumn('Id'))
        <td wire:click="expandRow({{ $index }})">{{ $related->category->id }}</td>
       @endif
       @if ($this->showColumn('Category Name'))
        <td data-title="Name" wire:click="expandRow({{ $index }})">
         <a href="{{ route('show_category', ['id' => $related->category->id]) }}">
          {{ strip_tags($related->category->name) }}</a>
        </td>
       @endif
       @if ($this->showColumn('Category Description'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $related->category->short_description }}
        </td>
       @endif
       @if ($this->showColumn('Category displayed elements'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $related->category->accepted_items }}
        </td>
       @endif
       @if ($this->showColumn('Category is active?'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         @if ($related->category->active)
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
       @if ($this->showColumn('Is primary category?'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         @if ($editindex !== $index)
          @if ($related->primary_category)
           <div class="checkbox--secondary disabled">
            <input type="checkbox" id="disabled9" disabled checked>
            <label id="disabled9"></label>
           </div>
          @else
           <div class="checkbox--secondary disabled">
            <input type="checkbox" id="disabled10" disabled>
            <label id="disabled10"></label>
           </div>
          @endif
         @else
          <input type="checkbox" id="primary" wire:model="var.{{ $index }}.primary">
         @endif
        </td>
       @endif
       @if ($this->showColumn('Created At'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $related->category->created_at }}
        </td>
       @endif
       @if ($this->showColumn('Updated At'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $related->category->updated_at }}
        </td>
       @endif
       <td>
        @if ($editindex !== $index)
         <div style="display: flex;">
          <button class="button button--secondary button--sm"
           wire:click.prevent="edititem({{ $index }}, {{ $related->id }})">
           <svg>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
            </path>
           </svg>
          </button>
          <button class="button button--secondary button--sm"
           wire:click.prevent="confirmItemRemoval({{ $related->id }})">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
           </svg>
          </button>
         </div>
        @else
         <div style="display: flex;">
          <button class="button button--secondary button--sm"
           wire:click.prevent="saveitem({{ $index }},{{ $related->id }})">
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

         @if ($this->showColumn('Category Description'))
          <p>
           <bold>Category Description</bold>
           {{ $related->category->short_description }}
          </p>
         @endif
         @if ($this->showColumn('Category displayed elements'))
          <p>
           <bold>Category displayed elements</bold>
           {{ $related->category->accepted_items }}
          </p>
         @endif
         @if ($this->showColumn('Category is active?'))
          <p>
           <bold>Category is active?</bold>
           @if ($related->category->active)
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
         @if ($this->showColumn('Is primary category?'))
          <p>
           <bold>Is primary category?</bold>
           @if ($related->primary_category)
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
           {{ $related->category->created_at }}
          </p>
         @endif
         @if ($this->showColumn('Updated At'))
          <p>
           <bold>Updated At</bold>
           {{ $related->category->updated_at }}
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
  @if ($loadAmount < $relatedcats->total())
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
    Load more
   </button>
  @endif
 </div>

</div>
