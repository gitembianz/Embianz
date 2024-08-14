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
 <h1 class="table--name">{{ __('Payments') }} ({{ $payments->total() }})</h1>
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
 <div class="table" @if ($selectPage || $selectAll) style="height: calc(100% - 150px);" @endif>
  <table class="expandable-table">
   <thead>
    <tr>
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
     <th>
      <button class="button button--secondary button--sm" style="opacity: 0;">
       <svg>
        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
        </path>
       </svg>
      </button>
     </th>
    </tr>
   </thead>
   <tbody>
    @if ($payments->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($selectedColumns) + 2 }}">No record found.</td>
     </tr>
    @else
     @php
      $i = 0;
     @endphp
     @foreach ($payments as $index => $item)
      <tr @if ($loop->last) id="last_record" @endif
       class="expandable-row @if ($this->isChecked($item->id)) active @endif">
       @foreach ($selectedColumns as $inde => $column)
        <td @if ($inde > count($selectedColumns) - 5) class="hidden" @endif wire:click="expandRow({{ $index }})">
         @if ($column === 'active')
          @if ($editindex !== $index)
           @if ($item->active)
            <div class="checkbox--primary disabled">
             <input type="checkbox" id="isactive{{ $index }}" disabled checked>
             <label for="isactive{{ $index }}"></label>
            </div>
           @else
            <div class="checkbox--primary disabled">
             <input type="checkbox" id="notactive{{ $index }}" disabled>
             <label for="notactive{{ $index }}"></label>
            </div>
           @endif
          @else
           <div class="checkbox--primary inline">
            <input type="checkbox" id="check{{ $index }}"
             wire:model.defer="isactive.{{ $index }}.active" />
            <label for="check{{ $index }}"></label>
           </div>
          @endif
         @elseif($column === 'description')
          @if ($editindex !== $index)
           {{ $item->description }}
          @else
           <div class="searchable">
            <input type="text" class="input__searchable"
             wire:model.defer="isactive.{{ $index }}.description">
           </div>
          @endif
         @else
          {{ $item->$column }}
         @endif
        </td>
       @endforeach
       <td>
        <div style="display: flex;">
         @if ($editindex !== $index)
          <button class="button button--secondary button--sm"
           wire:click.prevent="edit({{ $index }}, {{ $item->id }})">
           <svg>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
            </path>
           </svg>
          </button>
         @else
          <button class="button button--secondary button--sm"
           wire:click.prevent="save({{ $index }} , {{ $item->id }})">
           <svg>
            <polyline points="20 6 9 17 4 12"></polyline>
           </svg>
          </button>
          <button class="button button--secondary button--sm" wire:click.prevent="cancel()">
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
         @foreach ($selectedColumns as $nr => $column)
          @if ($nr >= count($selectedColumns) - 5)
           @if ($column === 'active')
            @if ($editindex !== $index)
             <p>
              <bold>{{ $column }}:</bold>
              @if ($item->active)
               <div class="checkbox--primary disabled">
                <input type="checkbox" id="isactive{{ $nr }}" disabled checked>
                <label for="isactive{{ $nr }}"></label>
               </div>
              @else
               <div class="checkbox--primary disabled">
                <input type="checkbox" id="notactive{{ $nr }}" disabled>
                <label for="notactive{{ $nr }}"></label>
               </div>
              @endif
             </p>
            @else
             <p>
              <bold>{{ $column }}:</bold>
             <div class="checkbox--primary">
              <input type="checkbox" id="check{{ $nr }}"
               wire:model.defer="isactive.{{ $index }}.active" />
              <label for="check{{ $nr }}"></label>
             </div>
             </p>
            @endif
           @elseif($column === 'description')
            @if ($editindex !== $index)
             <p>
              <bold>{{ $column }}:</bold>
              {{ $item->description }}
             </p>
            @else
             <p>
              <bold>{{ $column }}:</bold>
             <div class="searchable">
              <input type="text" class="input__searchable"
               wire:model.defer="isactive.{{ $index }}.description">
             </div>
             </p>
            @endif
           @else
            <p>
             <bold>{{ $column }}:</bold>
             {{ $item->$column }}
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

  <x-admin-lazyload />

  {{-- Load More Manual --}}
  @if ($loadAmount <= count($payments))
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
    Load more
   </button>
  @endif
 </div>
</section>
