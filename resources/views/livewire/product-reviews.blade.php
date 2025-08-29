<div class="accordion @if ($showrelated) active @endif">
 <x-alert />
 {{-- Accordion Header --}}
 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif"
   wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">
   {{ __('Reviews ') }}({{ $reviews->total() }})
   <svg>
    <polyline points="6 9 12 15 18 9"></polyline>
   </svg>
  </button>
  <button class="button button--secondary">
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
  {{-- Table --}}
  <table class="expandable-table">
   <thead>
    <tr>
     <th style="border-right: none; border-left: none;">
     </th>
     @foreach ($selectedColumns as $index => $column)
      @if ($this->showColumn($column))
       <th @if ($index > count($selectedColumns) - 10) class="hidden" @endif>
        <button wire:click="sortBy('{{ $column }}')"
         class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
         {{ str_replace('_id', '', $column) }} {{-- Remove '_id' from column name --}}
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
    @if ($reviews->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($reviews as $index => $review)
      <tr @if ($loop->last) id="last_record" @endif class="expandable-row">
       <td style="border-left: none" data-title="Check">
       </td>
       @foreach ($selectedColumns as $column)
        <td @if ($index > count($selectedColumns) - 17) class="hidden" @endif data-title="{{ $column }}">
         @if ($column === 'product_id')
          <a href="{{ route('show_product', ['id' => $review->$column]) }}">{{ $review->product->name }}</a>
         @elseif ($column === 'count')
          @if ($editindex !== $index)
           {{ $review->$column }}
          @else
           <input type="number" min="0" required class="input__searchable"
            wire:model.defer="record.{{ $index }}.count">
          @endif
         @elseif ($column === 'value')
          @if ($editindex !== $index)
           {{ $review->$column }}
          @else
           <input type="number" required class="input__searchable"
            wire:model.defer="record.{{ $index }}.value">
          @endif
         @else
          {{ $review->$column }}
         @endif
        </td>
       @endforeach
       <td>
        @if ($editindex !== $index)
         <div style="display: flex;">
          <button class="button button--secondary button--sm"
           wire:click.prevent="edititem({{ $index }}, {{ $review->id }})">
           <svg>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
            </path>
           </svg>
          </button>
         </div>
        @else
         <div style="display: flex;">
          <button class="button button--secondary button--sm"
           wire:click.prevent="saveitem({{ $index }},{{ $review->id }})">
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
     @endforeach
    @endif
   </tbody>
  </table>
 </div>
</div>
