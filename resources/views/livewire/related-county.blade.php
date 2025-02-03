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
  <div class="background background--center @if ($add) active @endif"></div>
  <div class="aside aside--table @if ($add) active @endif">
   {{-- Navigation --}}
   <nav class="nav--controls">
    <h1 class="table--name">
     {{ __('Add counties') }}
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
        </button>
       </th>
       <th style="width: unset !important;">
        <button class="table--btn">
         County name
        </button>
       </th>
       <th>
        <button class="table--btn">
         Iso code
        </button>
       </th>
       <th class="hidden">
        <button class="table--btn">
         Is Active
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
       $k = 1;
      @endphp
      @foreach ($Values as $index => $Value)
       <tr class="expandable-row">
        <td style="width: auto;">
         {{ $k }}
        </td>
        <td style="width: auto;">
         <div class="searchable">
          <input type="text" placeholder="County name" required class="input__searchable"
           wire:model.defer="Values.{{ $index }}.county.name">
         </div>
        </td>
        <td class="hidden" style="width: auto;">
         <div class="searchable">
          <input type="text" placeholder="Iso code" required class="input__searchable"
           wire:model.defer="Values.{{ $index }}.county.iso_code">
         </div>
        </td>
        <td class="hidden">
         <div class="checkbox--primary">
          <input type="checkbox" id="checkbox{{ $index }}"
           wire:model.defer="Values.{{ $index }}.county.active" />
          <label for="checkbox{{ $index }}"></label>
         </div>
        </td>

        <td>
         <div style="display: flex;">
          @if ($loop->last)
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
   {{ __('Counties ') }}({{ $counties->count() }})
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
       <input type="checkbox" id="selectPage9" wire:model="selectPage" />
       <label for="selectPage9"></label>
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
     @if ($this->showColumn('Iso code'))
      <th class="hidden">
       <button class="table--btn">
        Iso code
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Status'))
      <th class="hidden">
       <button class="table--btn">
        Status
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Created at'))
      <th class="hidden">
       <button wire:click="sortBy('created_at')" class="table--btn @if ($orderBy === $column && $countyAsc === '1') active @endif">
        Created At
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Updated at'))
      <th class="hidden">
       <button wire:click="sortBy('updated_at')" class="table--btn @if ($orderBy === $column && $countyAsc === '1') active @endif">
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
    @php
     $i = 0;
    @endphp
    @if ($counties->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($counties as $index => $county)
      <tr @if ($loop->last) id="last_record" @endif
       class="expandable-row @if ($this->isChecked($county->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $county->id }}" id="{{ $county->id }}" wire:model="checked">
         <label for="{{ $county->id }}"></label>
        </div>
       </td>
       @if ($this->showColumn('Id'))
        <td wire:click="expandRow({{ $index }})">{{ $county->id }}</td>
       @endif
       @if ($this->showColumn('Name'))
        <td wire:click="expandRow({{ $index }})">
         @if ($editindex !== $index)
          {{ $county->name }}
         @else
          <div class="searchable">
           <input type="text" class="input__searchable" wire:model.defer="item.{{ $index }}.name">
          </div>
         @endif
        </td>
       @endif
       @if ($this->showColumn('Iso code'))
        <td class="hidden">
         @if ($editindex !== $index)
          {{ $county->iso_code }}
         @else
          <div class="searchable">
           <input type="text" class="input__searchable" wire:model.defer="item.{{ $index }}.iso_code">
          </div>
         @endif
        </td>
       @endif
       @if ($this->showColumn('Status'))
        <td class="hidden">
         @if ($editindex !== $index)
          @if ($county->status)
           <div class="checkbox--secondary">
            <input type="checkbox" id="isactive{{ $index }}" disabled checked>
            <label for="isactive{{ $index }}"></label>
           </div>
          @else
           <div class="checkbox--secondary disabled">
            <input type="checkbox" id="notactive{{ $index }}" disabled>
            <label for="notactive{{ $index }}"></label>
           </div>
          @endif
         @else
          <div class="checkbox--secondary inline">
           <input type="checkbox" id="check{{ $index }}"
            wire:model.lazy="item.{{ $index }}.active" />
           <label for="check{{ $index }}"></label>
          </div>
         @endif
        </td>
       @endif

       @if ($this->showColumn('Created at'))
        <td class="hidden">
         {{ $county->created_at }}
        </td>
       @endif
       @if ($this->showColumn('Updated at'))
        <td class="hidden">
         {{ $county->updated_at }}
        </td>
       @endif
       <td>
        @if ($editindex !== $index)
         <div style="display: flex;">
          <button class="button button--secondary button--sm"
           wire:click.prevent="edititem({{ $index }}, {{ $county->id }})">
           <svg>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
            </path>
           </svg>
          </button>
          <button class="button button--secondary button--sm"
           wire:click.prevent="confirmItemRemoval({{ $county->id }})">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
           </svg>
          </button>
         </div>
        @else
         <div style="display: flex;">
          <button class="button button--secondary button--sm"
           wire:click.prevent="saveitem({{ $index }},{{ $county->id }})">
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
      <tr class="details-row  @if ($row === $i) active @endif">
       <td colspan="{{ count($columns) + 2 }}">
        <div class="details">
         @if ($this->showColumn('Iso code'))
          <p>
           <bold>Iso code</bold>
           {{ $county->iso_code }}
          </p>
         @endif

         @if ($this->showColumn('Created at'))
          <p>
           <bold>Created At</bold>
           {{ $county->created_at }}
          </p>
         @endif
         @if ($this->showColumn('Updated at'))
          <p>
           <bold>Updated At</bold>
           {{ $county->updated_at }}
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
  @if (count($counties) >= 10)
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="load">
    Load more
   </button>
  @endif
 </div>
</div>
