<section class="content">
 {{-- X-Components --}}
 <x-alert />


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
     {{ str_replace('_id', '', $column) }}
    </button>
   @endforeach
  </div>
 </aside>
 <aside>
  <div class="background background--right" wire:ignore id="visi__backdrop" wire:ignore></div>
  <div class="aside aside--right" wire:ignore id="visi" wire:ignore>
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
     <input type="checkbox" wire:model="selectedColumns" value="{{ $column }}"
      {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
     <span>{{ str_replace('_id', '', $column) }}</span>
    </label>
   @endforeach
  </div>
 </aside>


 {{-- Navigation --}}
 <h1 class="table--name">{{ __('Promotions') }} ({{ $promotions->total() }})</h1>
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
  <button class="button button--primary  button--centered display--desktop" tooltip="Refresh cookieids" tooltip-top
   wire:click.prevent="getcookieids()">
   <svg>
    <polyline points="1 4 1 10 7 10"></polyline>
    <polyline points="23 20 23 14 17 14"></polyline>
    <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
   </svg>
  </button>
  {{-- Add New Button --}}
  <a class="button button--primary button--centered display--desktop" tooltip="Add new promotion" tooltip-top
   href="{{ route('newpromotion') }}">
   <svg>
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
    <polyline points="14 2 14 8 20 8"></polyline>
    <line x1="12" y1="18" x2="12" y2="12"></line>
    <line x1="9" y1="15" x2="15" y2="15"></line>
   </svg>
  </a>
  {{-- IF CHECKED --}}
  <div class="dropdown dropdown--right" @if (!$checked) style="display: none;" @endif>
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
       {{ str_replace('_id', '', $column) }}
      </button>
     @endforeach
    </div>
   </div>
  </div>
  {{-- Visible Dropdown --}}
  <div class="dropdown dropdown--right display--desktop" wire:ignore wire:ignore>
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
       <span>{{ str_replace('_id', '', $column) }}</span>
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
     <button class="button button--primary button--fill button--flexed" wire:click="getcookieids()">
      <svg>
       <polyline points="1 4 1 10 7 10"></polyline>
       <polyline points="23 20 23 14 17 14"></polyline>
       <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
      </svg>
      <span>Refresh cookieids</span>
     </button>
     {{-- Add New Button --}}
     <a class="button button--primary button--fill button--flexed" href="{{ route('newpromotion') }}">
      <svg>
       <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
       <polyline points="14 2 14 8 20 8"></polyline>
       <line x1="12" y1="18" x2="12" y2="12"></line>
       <line x1="9" y1="15" x2="15" y2="15"></line>
      </svg>
      <span>Add Promotions</span>
     </a>
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
      <span>Columns</span>
     </button>
    </div>
   </div>
  </div>
 </nav>


 {{-- Select All? --}}
 @if ($selectPage && $selectAll)
  <button class="button button--fill button--primary" style="margin-top: 10px;">
   You selected {{ count($checked) }} items.
  </button>
 @elseif($selectPage)
  <button class="button button--fill button--secondary" style="margin-top: 10px;" wire:click="selectAll">
   You selected {{ count($checked) }} items, select all?
  </button>
 @endif


 {{-- Table --}}
 <div class="table" @if ($selectPage || $selectAll) style="height: calc(100% - 150px);" @endif>
  <table class="expandable-table">
   <thead>
    <tr>
     <th style="border-right: none; border-left: none;">
      <div class="checkbox--primary">
       <input type="checkbox" id="selectPage3" wire:model="selectPage" />
       <label for="selectPage3"></label>
      </div>
     </th>
     @foreach ($selectedColumns as $index => $column)
      @if ($this->showColumn($column))
       <th @if ($index > 1) class="hidden" @endif>
        <button wire:click="sortBy('{{ $column }}')"
         class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
         {{ str_replace('_id', '', $column) }}
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
    @if ($promotions->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($selectedColumns) + 2 }}">No record found.</td>
     </tr>
    @else
     @php
      $i = 0;
     @endphp
     @foreach ($promotions as $nr => $promotion)
      <tr @if ($loop->last) id="last_record" @endif
       class="expandable-row @if ($this->isChecked($promotion->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $promotion->id }}" id="{{ $promotion->id }}" wire:model="checked">
         <label for="{{ $promotion->id }}"></label>
        </div>
       </td>
       @foreach ($selectedColumns as $index => $column)
        <td @if ($index > 1) class="hidden" @endif data-title="{{ $column }}"
         wire:click="expandRow({{ $nr }})">
         @if ($column === 'name' || $column === 'details')
          @if ($editindex !== $nr)
           {{ $promotion->$column }}
          @else
           <div class="searchable">
            <input type="text" class="input__searchable"
             wire:model.defer="item.{{ $nr }}.{{ $column }}">
           </div>
          @endif
         @elseif (
             $column === 'cooldown_timer' ||
                 $column === 'cart_amount' ||
                 $column === 'cookie_time' ||
                 $column === 'promotion_percent' ||
                 $column === 'promotion_value')
          @if ($editindex !== $nr)
           {{ $promotion->$column }}
          @else
           <div class="searchable">
            <input type="number" class="input__searchable"
             wire:model.defer="item.{{ $nr }}.{{ $column }}">
           </div>
          @endif
         @elseif ($column === 'type')
          @if ($editindex !== $nr)
           {{ $promotion->$column }}
          @else
           <div class="searchable">
            <select class="input__searchable" wire:model.defer="item.{{ $nr }}.type">
             <option value="counter">counter</option>
             <option value="amount">amount</option>
            </select>
           </div>
          @endif
         @elseif ($column === 'active')
          @if ($editindex !== $nr)
           @if ($promotion->active)
            <div class="checkbox--secondary">
             <input type="checkbox" id="isactive{{ $nr }}" disabled checked>
             <label for="isactive{{ $nr }}"></label>
            </div>
           @else
            <div class="checkbox--secondary disabled">
             <input type="checkbox" id="notactive{{ $nr }}" disabled>
             <label for="notactive{{ $nr }}"></label>
            </div>
           @endif
          @else
           <div class="checkbox--secondary inline">
            <input type="checkbox" id="check{{ $nr }}"
             wire:model.lazy="item.{{ $nr }}.{{ $column }}" />
            <label for="check{{ $nr }}"></label>
           </div>
          @endif
         @elseif ($column === 'details')
          <span class="show-less">
           {!! $promotion->$column !!}
          </span>
         @elseif ($column === 'start_date' || $column === 'end_date')
          @if ($editindex !== $nr)
           {{ $promotion->$column }}
          @else
           <div class="searchable">
            <input type="date" required class="input__searchable"
             wire:model.defer="item.{{ $nr }}.{{ $column }}">
           </div>
          @endif
         @else
          {{ $promotion->$column }}
         @endif
        </td>
       @endforeach
       <td style="border-right: none">
        <div style="display:flex;">
         <button class="button button--secondary button--sm" wire:click.prevent="getcookieid({{ $promotion->id }})">
          <svg>
           <polyline points="1 4 1 10 7 10"></polyline>
           <polyline points="23 20 23 14 17 14"></polyline>
           <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
          </svg>
         </button>
         @if ($editindex !== $nr)
          <button class="button button--secondary button--sm"
           wire:click.prevent="edititem({{ $nr }}, {{ $promotion->id }})">
           <svg>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
            </path>
           </svg>
          </button>
          <button class="button button--secondary button--sm"
           wire:click.prevent="confirmItemRemoval({{ $promotion->id }})">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
            </path>
           </svg>
          </button>
         @else
          <button class="button button--secondary button--sm"
           wire:click.prevent="saveitem({{ $nr }} , {{ $promotion->id }})">
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
       <td colspan="18">
        <div class="details">
         @foreach ($selectedColumns as $index => $column)
          @if ($index > 1 && $row === $i)
           <p>
            <bold>{{ $column }}:</bold>
            @if ($column === 'name' || $column === 'details')
             @if ($editindex !== $nr)
              {{ $promotion->$column }}
             @else
              <div class="searchable">
               <input type="text" class="input__searchable"
                wire:model.defer="item.{{ $nr }}.{{ $column }}">
              </div>
             @endif
            @elseif (
                $column === 'cooldown_timer' ||
                    $column === 'cart_amount' ||
                    $column === 'cookie_time' ||
                    $column === 'promotion_percent' ||
                    $column === 'promotion_value')
             @if ($editindex !== $nr)
              {{ $promotion->$column }}
             @else
              <div class="searchable">
               <input type="number" class="input__searchable"
                wire:model.defer="item.{{ $nr }}.{{ $column }}">
              </div>
             @endif
            @elseif ($column === 'type')
             @if ($editindex !== $nr)
              {{ $promotion->$column }}
             @else
              <div class="searchable">
               <select class="input__searchable" wire:model.defer="item.{{ $nr }}.type">
                <option value="counter">counter</option>
                <option value="amount">amount</option>
               </select>
              </div>
             @endif
            @elseif ($column === 'active')
             @if ($editindex !== $nr)
              @if ($promotion->active)
               <div class="checkbox--secondary">
                <input type="checkbox" id="isactive{{ $nr }}" disabled checked>
                <label for="isactive{{ $nr }}"></label>
               </div>
              @else
               <div class="checkbox--secondary disabled">
                <input type="checkbox" id="notactive{{ $nr }}" disabled>
                <label for="notactive{{ $nr }}"></label>
               </div>
              @endif
             @else
              <div class="checkbox--secondary inline">
               <input type="checkbox" id="check{{ $nr }}"
                wire:model.lazy="item.{{ $nr }}.{{ $column }}" />
               <label for="check{{ $nr }}"></label>
              </div>
             @endif
            @elseif ($column === 'details')
             <span class="show-less">
              {!! $promotion->$column !!}
             </span>
            @elseif ($column === 'start_date' || $column === 'end_date')
             @if ($editindex !== $nr)
              {{ $promotion->$column }}
             @else
              <div class="searchable">
               <input type="date" required class="input__searchable"
                wire:model.defer="item.{{ $nr }}.{{ $column }}">
              </div>
             @endif
            @else
             {{ $promotion->$column }}
            @endif
           </p>
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
  @if ($loadAmount <= count($promotions))
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
    Load more
   </button>
  @endif
 </div>
</section>
