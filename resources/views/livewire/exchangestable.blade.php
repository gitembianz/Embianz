<section class="content">
 <x-alert />

 <aside>
  <div class="background background--center @if ($single) active @endif"></div>
  <div class="aside aside--confirm @if ($single) active @endif">
   <span>
    Are you sure to delete this record?
   </span>
   <button class="button button--primary button--long" wire:click="deleteSingleRecord()">
    <span>Delete</span>
   </button>
   <button class="button button--danger button--long" wire:click="$set('single', false)">
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
    <exchange class="switch switch--primary" style="margin: 0.25rem 0">
     <input type="checkbox" wire:ignore wire:model="selectedColumns" iso_code="{{ $column }}"
      {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
     <span>{{ $column }}</span>
    </exchange>
   @endforeach
  </div>
 </aside>
 {{-- Add new exchange --}}
 <aside>
  <div class="background background--center @if ($add) active @endif"></div>
  <form class="aside aside--table @if ($add) active @endif">
   {{-- Navigation --}}
   <nav class="nav--controls">
    <h1 class="table--name">
     {{ __('Add exchnages rates') }}
    </h1>
    <button class="button button--primary button--centered" wire:click.prevent="saveadd()">
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
      <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
      <path d="M14 4l0 4l-6 0l0 -4" />
     </svg>
    </button>
    <button class="button button--danger button--centered" wire:click="canceladd()">
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M15 19v-2a2 2 0 0 1 2 -2h2" />
      <path d="M15 5v2a2 2 0 0 0 2 2h2" />
      <path d="M5 15h2a2 2 0 0 1 2 2v2" />
      <path d="M5 9h2a2 2 0 0 0 2 -2v-2" />
     </svg>
    </button>
   </nav>


   {{-- Table --}}
   <div class="table" style="height: calc(100% - 60px);">
    <table class="expandable-table">
     <thead>
      <tr>
       <th>
        <div class="table--btn">Nr.</div>

       </th>
       <th style="min-width: 30%">
        <div class="table--btn">Base currency</div>
       </th>

       <th>
        <div class="table--btn">Quote currency</div>
       </th>
       <th>
        <div class="table--btn">Value</div>
       </th>
       <th>
        <div class="table--btn">Date</div>
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
      @for ($i = 1; $i <= $rowadd; $i++)
       <tr class="expandable-row">
        <td>
         {{ $i }}</td>
        <td>
         <select class="searchable" wire:model.defer="base.{{ $i }}">
          <option value="">Select base currency</option>

          @foreach ($currencies as $currency)
           <option value="{{ $currency->id }}">{{ $currency->name }}</option>
          @endforeach
         </select>

        </td>
        <td>
         <select class="searchable" wire:model.defer="quote.{{ $i }}">
          <option value="">Select quote currency</option>

          @foreach ($currencies as $currency)
           <option value="{{ $currency->id }}">{{ $currency->name }}</option>
          @endforeach
         </select>
        </td>
        <td>
         <div class="searchable">
          <input placeholder="value" type="number"class="input__searchable"
           wire:model.defer="value.{{ $i }}">
         </div>
        </td>
        <td>
         <div class="searchable">
          <input type="date"class="input__searchable" wire:model.defer="date.{{ $i }}">
         </div>
        </td>
        <td>
         <div style="display: flex;">
          @if ($i == $rowadd)
           <button type="button" class="button button--secondary button--sm" wire:click="plus()">
            <svg>
             <line x1="12" y1="5" x2="12" y2="19">
             </line>
             <line x1="5" y1="12" x2="19" y2="12">
             </line>
            </svg>
           </button>
          @endif
          <button type="button" class="button button--secondary button--sm"
           wire:click="clear({{ $i }})">
           <svg>
            <line x1="18" y1="6" x2="6" y2="18">
            </line>
            <line x1="6" y1="6" x2="18" y2="18">
            </line>
           </svg>
          </button>
         </div>
        </td>
       </tr>
      @endfor
     </tbody>
    </table>
   </div>
  </form>
 </aside>


 {{-- Navigation --}}
 <h1 class="table--name">{{ __('Exchanges rate') }} ({{ count($exchanges) }})</h1>
 <nav class="nav--controls">
  <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
  <button class="button button--primary button--centered display--desktop" tooltip="Refresh table" tooltip-top
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
  <a class="button button--primary button--centered display--desktop" tooltip="Add" tooltip-top
   wire:click.prevent="$set('add', true)">
   <svg>
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
    <polyline points="14 2 14 8 20 8"></polyline>
    <line x1="12" y1="18" x2="12" y2="12"></line>
    <line x1="9" y1="15" x2="15" y2="15"></line>
   </svg>
  </a>
  {{-- Sorting Dropdown --}}
  <div class="dropdown dropdown--right display--desktop" wire:ignore>
   {{-- Dropdown Button --}}
   <button class="button button--primary button--centered" tooltip="Sort items in table" tooltip-left>
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
  {{-- Visible Dropdown --}}
  <div class="dropdown dropdown--right display--desktop" wire:ignore>
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
       <input type="checkbox" wire:ignore wire:model="selectedColumns" iso_code="{{ $column }}"
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
   <button class="button button--primary button--centered" tooltip="Show more actions" tooltip-left>
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
     <button class="button button--primary button--long button--flexed" wire:click="$refresh">
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
     <a class="button button--primary button--fill button--flexed" wire:click.prevent="$set('add', true)">
      <svg>
       <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
       <polyline points="14 2 14 8 20 8"></polyline>
       <line x1="12" y1="18" x2="12" y2="12"></line>
       <line x1="9" y1="15" x2="15" y2="15"></line>
      </svg>
      <span>Add</span>
     </a>
     <button class="button button--primary button--long button--flexed" id="sort__open">
      <svg>
       <path stroke="none" d="M0 0h24v24H0z" fill="none" />
       <path d="M15 10v-5c0 -1.38 .62 -2 2 -2s2 .62 2 2v5m0 -3h-4" />
       <path d="M19 21h-4l4 -7h-4" />
       <path d="M4 15l3 3l3 -3" />
       <path d="M7 6v12" />
      </svg>
      <span>Sorting data</span>
     </button>
     <button class="button button--primary button--long button--flexed" id="visi__open">
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


 {{-- Table --}}
 <div class="table">
  <table class="expandable-table">
   <thead>
    <tr>
     @if ($this->showColumn('id'))
      <th>
       <button wire:click="sortBy('id')" class="table--btn @if ($orderBy === 'id' && $orderAsc === '1') active @endif">
        id
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('base_currency_id'))
      <th>
       <button class="table--btn">
        Base currency

       </button>
      </th>
     @endif
     @if ($this->showColumn('quote_currency_id'))
      <th>
       <button class="table--btn">
        Quote currency

       </button>
      </th>
     @endif
     @if ($this->showColumn('value'))
      <th class="hidden">
       <button wire:click="sortBy('value')" class="table--btn @if ($orderBy === 'value' && $orderAsc === '1') active @endif">
        Value
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('date'))
      <th class="hidden">
       <button wire:click="sortBy('date')" class="table--btn @if ($orderBy === 'date' && $orderAsc === '1') active @endif">
        date
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('created_by'))
      <th class="hidden">
       <button wire:click="sortBy('created_by')" class="table--btn @if ($orderBy === 'created_by' && $orderAsc === '1') active @endif">
        Created by
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('last_modified_by'))
      <th class="hidden">
       <button wire:click="sortBy('last_modified_by')"
        class="table--btn @if ($orderBy === 'last_modified_by' && $orderAsc === '1') active @endif">
        Last modified by
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif

     @if ($this->showColumn('created_at'))
      <th class="hidden">
       <button wire:click="sortBy('created_at')" class="table--btn @if ($orderBy === 'created_at' && $orderAsc === '1') active @endif">
        created_at
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('updated_at'))
      <th class="hidden">
       <button wire:click="sortBy('updated_at')" class="table--btn @if ($orderBy === 'updated_at' && $orderAsc === '1') active @endif">
        updated_at
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     <th>
      <button class="button button--secondary button--sm" style="opacity: 0;">
       <svg>
        <polyline points="20 6 9 17 4 12"></polyline>
       </svg>
      </button>
     </th>

    </tr>
   </thead>
   <tbody>
    @if ($exchanges->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($selectedColumns) + 2 }}">No record found.</td>
     </tr>
    @else
    @php
     $i = 0;
    @endphp
     @foreach ($exchanges as $index => $exchange)
      <tr @if ($loop->last) id="last_record" @endif>
       @if ($this->showColumn('id'))
        <td wire:click="expandRow({{ $index }})">{{ $exchange->id }}</td>
       @endif
       @if ($this->showColumn('base_currency_id'))
        <td wire:click="expandRow({{ $index }})">
         @if ($rowindex !== $index)
          {{ $exchange->base_currency->name }}
         @else
          <select class="searchable" wire:model.defer="element.{{ $index }}.base_currency_id">
           @foreach ($currencies as $currency)
            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
           @endforeach
          </select>
         @endif
        </td>
       @endif
       @if ($this->showColumn('quote_currency_id'))
        <td wire:click="expandRow({{ $index }})">
         @if ($rowindex !== $index)
          {{ $exchange->quote_currency->name }}
         @else
          <select class="searchable" wire:model.defer="element.{{ $index }}.quote_currency_id">
           @foreach ($currencies as $currency)
            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
           @endforeach
          </select>
         @endif
        </td>
       @endif
       @if ($this->showColumn('value'))
        <td class="hidden">
         @if ($rowindex !== $index)
          {{ $exchange->value }}
         @else
          <div class="searchable">
           <input type="number" class="input__searchable" wire:model.defer="element.{{ $index }}.value">
          </div>
         @endif
        </td>
       @endif
       @if ($this->showColumn('date'))
        <td class="hidden">
         @if ($rowindex !== $index)
          {{ $exchange->date }}
         @else
          <div class="searchable">
           <input type="date" class="input__searchable" wire:model.defer="element.{{ $index }}.date">
          </div>
         @endif
       @endif
       @if ($this->showColumn('created_by'))
        <td class="hidden">
         {{ $exchange->created_by }}
        </td>
       @endif
       @if ($this->showColumn('last_modified_by'))
        <td class="hidden">
         {{ $exchange->last_modified_by }}
        </td>
       @endif

       @if ($this->showColumn('created_at'))
        <td class="hidden">
         {{ $exchange->created_at }}
        </td>
       @endif
       @if ($this->showColumn('updated_at'))
        <td class="hidden">
         {{ $exchange->updated_at }}
        </td>
       @endif
       <td>
        <div style="display:flex;">

         @if ($rowindex !== $index)
          <button class="button button--secondary button--sm"
           wire:click.prevent="edititem({{ $index }}, {{ $exchange->id }})">
           <svg>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
            </path>
           </svg>
          </button>
          <button wire:click.prevent="confirmItemRemoval({{ $exchange->id }})"
           class="button button--secondary button--sm">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
           </svg>
          </button>
         @else
          <div style="display: flex;">
           <button class="button button--secondary button--sm"
            wire:click.prevent="saveitem({{ $index }} , {{ $exchange->id }})">
            <svg>
             <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
           </button>
           <button class="button button--secondary button--sm" wire:click.prevent="cancelitem()">
            <svg>
             <line x1="18" y1="6" x2="6" y2="18"></line>
             <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
           </button>
          </div>
         @endif
        </div>
       </td>
      </tr>
      <tr class="details-row  @if ($row === $i) active @endif">
      <td colspan="17">
       <div class="details">
        @if ($this->showColumn('value'))
         @if ($rowindex !== $index)
          <p>
           Value:
           {{ $exchange->value }}
          </p>
         @else
          <p>
           Value:
          <div class="searchable">
           <input type="number" class="input__searchable" wire:model.defer="element.{{ $index }}.value">
          </div>
          </p>
         @endif
        @endif
        @if ($this->showColumn('date'))
         @if ($rowindex !== $index)
          <p>
           Date:
           {{ $exchange->date }}
          </p>
         @else
          <p>
           Date:
           <div class="searchable">
           <input type="date" class="input__searchable" wire:model.defer="element.{{ $index }}.date">
          </div>
          </p>
         @endif
        @endif
        @if ($this->showColumn('created_by'))
         <p>
          Created by:
          {{ $exchange->created_by }}
         </p>
        @endif
        @if ($this->showColumn('last_modified_by'))
         <p>
          Last modified by:
          {{ $exchange->last_modified_by }}
         </p>
        @endif
        @if ($this->showColumn('created_at'))
         <p>
          Created At:
          {{ $exchange->created_at }}
         </p>
        @endif
        @if ($this->showColumn('updated_at'))
         <p>
          Updated At:
          {{ $exchange->updated_at }}
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


  {{-- Load More Automatic --}}
 <x-admin-lazyload />


  {{-- Load More Manual --}}
  @if ($loadAmount <= count($exchanges))
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
    Load more
   </button>
  @endif
 </div>
</section>
