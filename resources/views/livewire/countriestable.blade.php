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
    <country class="switch switch--primary" style="margin: 0.25rem 0">
     <input type="checkbox" wire:ignore wire:model="selectedColumns" iso_code="{{ $column }}"
      {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
     <span>{{ $column }}</span>
    </country>
   @endforeach
  </div>
 </aside>


 {{-- Navigation --}}
 <h1 class="table--name">{{ __('Countries') }} ({{ $countries->total() }})</h1>
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
  {{-- Update countries --}}
  <button class="button button--primary button--centered display--desktop" tooltip="Update countries" tooltip-top
   wire:click="addCountriesIfNotExist">
   <svg>
    <polyline points="17 1 21 5 17 9"></polyline>
    <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
    <polyline points="7 23 3 19 7 15"></polyline>
    <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
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
      <country class="switch switch--primary inline">
       <input type="checkbox" wire:ignore wire:model="selectedColumns" iso_code="{{ $column }}"
        {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
       <span>{{ $column }}</span>
      </country>
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
     <button class="button button--primary button--long button--flexed" wire:click="addCountriesIfNotExist">
      <svg>
       <polyline points="17 1 21 5 17 9"></polyline>
       <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
       <polyline points="7 23 3 19 7 15"></polyline>
       <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
      </svg>
      <span>Update countries</span>
     </button>
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
     @if ($this->showColumn('name'))
      <th>
       <button wire:click="sortBy('name')" class="table--btn @if ($orderBy === 'name' && $orderAsc === '1') active @endif">
        name
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('iso_code'))
      <th>
       <button wire:click="sortBy('iso_code')" class="table--btn @if ($orderBy === 'iso_code' && $orderAsc === '1') active @endif">
        iso_code
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('iso_code3'))
      <th>
       <button wire:click="sortBy('iso_code3')" class="table--btn @if ($orderBy === 'iso_code3' && $orderAsc === '1') active @endif">
        iso_code3
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('phone_code'))
      <th>
       <button wire:click="sortBy('phone_code')" class="table--btn @if ($orderBy === 'phone_code' && $orderAsc === '1') active @endif">
        phone_code
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('currency'))
      <th>
       <button wire:click="sortBy('currency')" class="table--btn @if ($orderBy === 'currency' && $orderAsc === '1') active @endif">
        currency
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('status'))
      <th>
       <button wire:click="sortBy('status')" class="table--btn @if ($orderBy === 'status' && $orderAsc === '1') active @endif">
        status
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('created_at'))
      <th>
       <button wire:click="sortBy('created_at')" class="table--btn @if ($orderBy === 'created_at' && $orderAsc === '1') active @endif">
        created_at
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('updated_at'))
      <th>
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
    @foreach ($countries as $index => $country)
     <tr @if ($loop->last) id="last_record" @endif>
      @if ($this->showColumn('id'))
       <td>{{ $country->id }}</td>
      @endif
      @if ($this->showColumn('name'))
       <td>
        @if ($rowindex !== $index)
         <a href="{{ route('show_country', ['id' => $country->id]) }}">{{ strip_tags($country->name) }}</a>
        @else
         <div class="searchable">
          <input type="text" class="input__searchable" wire:model.defer="element.{{ $index }}.name">
         </div>
        @endif
       </td>
      @endif
      @if ($this->showColumn('iso_code'))
       <td>
        @if ($rowindex !== $index)
         {{ $country->iso_code }}
        @else
         <div class="searchable">
          <input type="text" class="input__searchable" wire:model.defer="element.{{ $index }}.iso_code">
         </div>
        @endif
       </td>
      @endif
      @if ($this->showColumn('iso_code3'))
       <td>
        @if ($rowindex !== $index)
         {{ $country->iso_code3 }}
        @else
         <div class="searchable">
          <input type="text" class="input__searchable" wire:model.defer="element.{{ $index }}.iso_code3">
         </div>
        @endif
       </td>
      @endif
      @if ($this->showColumn('phone_code'))
       <td>
        @if ($rowindex !== $index)
         {{ $country->phone_code }}
        @else
         <div class="searchable">
          <input type="text" class="input__searchable" wire:model.defer="element.{{ $index }}.phone_code">
         </div>
        @endif
       </td>
      @endif
      @if ($this->showColumn('currency'))
       <td>
        @if ($rowindex !== $index)
         {{ $country->currency }}
        @else
         <div class="searchable">
          <input type="text" class="input__searchable" wire:model.defer="element.{{ $index }}.currency">
         </div>
        @endif
       </td>
      @endif
      @if ($this->showColumn('status'))
       <td>

        @if ($rowindex !== $index)
         @if ($country->status)
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
           wire:model.lazy="element.{{ $index }}.status" />
          <label for="check{{ $index }}"></label>
         </div>
        @endif
       </td>
      @endif

      @if ($this->showColumn('created_at'))
       <td>
        {{ $country->created_at }}
       </td>
      @endif
      @if ($this->showColumn('updated_at'))
       <td>
        {{ $country->updated_at }}
       </td>
      @endif
      <td>
       @if ($rowindex !== $index)
        <button class="button button--secondary button--sm"
         wire:click.prevent="edititem({{ $index }}, {{ $country->id }})">
         <svg>
          <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
          </path>
         </svg>
        </button>
       @else
        <div style="display: flex;">
         <button class="button button--secondary button--sm"
          wire:click.prevent="saveitem({{ $index }} , {{ $country->id }})">
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
    @endforeach
   </tbody>
  </table>


  {{-- Load More Automatic --}}
  <script>
   document.addEventListener('livewire:load', function() {
    let observer = new IntersectionObserver((entries) => {
     entries.forEach(entry => {
      if (entry.isIntersecting) {
       @this.call('loadMore');
      }
     });
    });

    observer.observe(document.getElementById('last_record'));
   });
  </script>


  {{-- Load More Manual --}}
  @if ($loadAmount <= count($countries))
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
    Load more
   </button>
  @endif
 </div>
</section>
