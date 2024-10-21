<section class="content">
 {{-- X-Components --}}
 <x-alert />

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
    <label class="switch switch--primary" style="margin: 0.25rem 0">
     <input type="checkbox" wire:ignore wire:model="selectedColumns" value="{{ $column }}"
      {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
     <span>{{ $column }}</span>
    </label>
   @endforeach
  </div>
 </aside>

 {{-- Navigation --}}
 <h1 class="table--name">{{ __('Store Settings') }} ({{ $storesettings->total() }})</h1>
 <nav class="nav--controls">
  {{-- Search Input --}}
  <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
  {{-- Refresh Button --}}
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
  {{-- Add store setting
  <a class="button button--primary button--centered display--desktop" tooltip="Add new setting" tooltip-top
   href="{{ route('add_storesetting') }}">
   <svg>
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
    <polyline points="14 2 14 8 20 8"></polyline>
    <line x1="12" y1="18" x2="12" y2="12"></line>
    <line x1="9" y1="15" x2="15" y2="15"></line>
   </svg>
  </a> --}}
  {{-- Add store setting --}}

  <button class="button button--primary button--centered display--desktop" tooltip="Reset cached filters" tooltip-top
   wire:click.prevent="refreshfilters">
   <svg>
    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
   </svg>
  </button>
  <button class="button button--primary button--centered display--desktop" tooltip="Seed reviews" tooltip-top
   wire:click.prevent="seedreviews">
   <svg>
    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
   </svg>
  </button>
  {{-- Update Website --}}
  <button class="button button--primary button--centered display--desktop" tooltip="Update Website" tooltip-top
   wire:click="actualizeaza">
   <svg>
    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
    <polyline points="17 8 12 3 7 8"></polyline>
    <line x1="12" y1="3" x2="12" y2="15"></line>
   </svg>
  </button>
  {{-- Update Parameters --}}
  <button class="button button--primary button--centered display--desktop" tooltip="Update Parameters" tooltip-top
   wire:click="addSettingsIfNotExist">
   <svg>
    <polyline points="17 1 21 5 17 9"></polyline>
    <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
    <polyline points="7 23 3 19 7 15"></polyline>
    <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
   </svg>
  </button>
  {{-- Generate Sitemap --}}
  <button class="button button--primary button--centered display--desktop" tooltip="Generate Sitemap" tooltip-top
   wire:click="sitemap">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
    <path d="M4 15l4 6" />
    <path d="M4 21l4 -6" />
    <path d="M19 15v6h3" />
    <path d="M11 21v-6l2.5 3l2.5 -3v6" />
   </svg>
  </button>
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
       <input type="checkbox" wire:ignore wire:model="selectedColumns" value="{{ $column }}"
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
     <button class="button button--primary button--fill button--flexed" wire:click="$refresh">
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
     <a class="button button--primary button--fill button--flexed" href="{{ route('add_storesetting') }}">
      <svg>
       <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
       <polyline points="14 2 14 8 20 8"></polyline>
       <line x1="12" y1="18" x2="12" y2="12"></line>
       <line x1="9" y1="15" x2="15" y2="15"></line>
      </svg>
      <span>Add store setting</span>
     </a>
     <button class="button button--primary button--fill button--flexed" wire:click="actualizeaza">
      <svg>
       <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
       <polyline points="17 8 12 3 7 8"></polyline>
       <line x1="12" y1="3" x2="12" y2="15"></line>
      </svg>
      <span>Update Website</span>
     </button>
     <button class="button button--primary button--fill button--flexed" wire:click="addSettingsIfNotExist">
      <svg>
       <polyline points="17 1 21 5 17 9"></polyline>
       <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
       <polyline points="7 23 3 19 7 15"></polyline>
       <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
      </svg>
      <span>Update Parameters</span>
     </button>
     <button class="button button--primary button--fill button--flexed" wire:click="sitemap">
      <svg>
       <path stroke="none" d="M0 0h24v24H0z" fill="none" />
       <path d="M14 3v4a1 1 0 0 0 1 1h4" />
       <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
       <path d="M4 15l4 6" />
       <path d="M4 21l4 -6" />
       <path d="M19 15v6h3" />
       <path d="M11 21v-6l2.5 3l2.5 -3v6" />
      </svg>
      <span>Generate Sitemap</span>
     </button>
     <button class="button button--primary button--fill button--flexed" id="sort__open">
      <svg>
       <path stroke="none" d="M0 0h24v24H0z" fill="none" />
       <path d="M15 10v-5c0 -1.38 .62 -2 2 -2s2 .62 2 2v5m0 -3h-4" />
       <path d="M19 21h-4l4 -7h-4" />
       <path d="M4 15l3 3l3 -3" />
       <path d="M7 6v12" />
      </svg>
      <span>Sorting data</span>
     </button>
     <button class="button button--primary button--fill button--flexed" id="visi__open">
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
     @if ($this->showColumn('Parameter'))
      <th>
       <button wire:click="sortBy('parameter')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Parameter
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Value'))
      <th class="hidden">
       <button wire:click="sortBy('value')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Value
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Description'))
      <th class="hidden">
       <button wire:click="sortBy('description')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Description
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Created At'))
      <th class="hidden">
       <button wire:click="sortBy('created_at')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Created at
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Updated At'))
      <th class="hidden">
       <button wire:click="sortBy('updated_at')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Updated at
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
    @foreach ($storesettings as $index => $store)
     <tr @if ($loop->last) id="last_record" @endif class="expandable-row">
      @if ($this->showColumn('Id'))
       <td wire:click="expandRow({{ $index }})">{{ $store->id }}</td>
      @endif
      @if ($this->showColumn('Parameter'))
       <td wire:click="expandRow({{ $index }})">
        {{ $store->parameter }}
       </td>
      @endif
      @if ($this->showColumn('Value'))
       <td wire:click="expandRow({{ $index }})" class="hidden">
        @if ($editindex !== $index)
         {{ $store->value }}@if ($store->parameter == 'time_zone')
          (time is: {{ now() }}) - Please refresh to update after the edit
         @endif
        @else
         <div class="searchable">
          <input type="text" class="input__searchable" wire:model.defer="settings.{{ $index }}.value">
         </div>
        @endif
       </td>
      @endif
      @if ($this->showColumn('Description'))
       <td wire:click="expandRow({{ $index }})" class="hidden">
        {{ $store->description }}
       </td>
      @endif
      @if ($this->showColumn('Created At'))
       <td wire:click="expandRow({{ $index }})" class="hidden">
        {{ $store->created_at }}
       </td>
      @endif
      @if ($this->showColumn('Updated At'))
       <td wire:click="expandRow({{ $index }})" class="hidden">
        {{ $store->updated_at }}
       </td>
      @endif
      <td>
       @if ($editindex !== $index)
        <button class="button button--secondary button--sm"
         wire:click.prevent="edititem({{ $index }}, {{ $store->id }})">
         <svg>
          <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
          </path>
         </svg>
        </button>
       @else
        <div style="display: flex;">
         <button class="button button--secondary button--sm"
          wire:click.prevent="saveitem({{ $index }} , {{ $store->id }})">
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
      </td>
     </tr>
     <tr class="details-row  @if ($row === $i) active @endif">
      <td colspan="17">
       <div class="details">
        @if ($this->showColumn('Value'))
         @if ($editindex !== $index)
          <p>
           <bold>Value:</bold>
           {{ $store->value }}@if ($store->parameter == 'time_zone')
            (time is: {{ now() }}) - Please refresh to update after the edit
           @endif
          </p>
         @else
          <p>
           <bold>Value:</bold>
          <div class="searchable">
           <input type="text" class="input__searchable" wire:model.defer="settings.{{ $index }}.value">
          </div>
          </p>
         @endif
        @endif
        @if ($this->showColumn('Description'))
         <p>
          <bold>Description:</bold>
          {{ $store->description }}
         </p>
        @endif
        @if ($this->showColumn('Created At'))
         <p>
          <bold>Created At:</bold>
          {{ $store->created_at }}
         </p>
        @endif
        @if ($this->showColumn('Updated At'))
         <p>
          <bold>Updated At:</bold>
          {{ $store->updated_at }}
         </p>
        @endif
       </div>
      </td>
     </tr>
     @php
      $i++;
     @endphp
    @endforeach
   </tbody>
  </table>

  <x-admin-lazyload />

  {{-- Load More Manual --}}
  @if ($loadAmount <= count($storesettings))
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
    Load more
   </button>
  @endif
 </div>

</section>
