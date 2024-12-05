<div class="accordion @if ($showrelated) active @endif">
 <x-alert />

 {{-- Delete Record --}}
 <aside>
  <div class="background background--center @if ($delete) active @endif"></div>
  <div class="aside aside--confirm @if ($delete) active @endif">
   <span>
    Are you sure to delete this record?
   </span>
   <button class="button button--primary button--long" wire:click.prevent="deleteRecord()">
    <span>Delete</span>
   </button>
   <button class="button button--danger button--long" wire:click.prevent="cancelItemRemoval()">
    <span>Cancel</span>
   </button>
  </div>
 </aside>

 {{-- Accordion Header --}}
 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif"
   wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">
   @php
    if ($relatedby === 'user_promotions') {
        $relatedby = 'promotions';
    }
   @endphp
   {{ ucfirst($relatedby) }}
   ({{ $relateds->total() }})
   <svg>
    <polyline points="6 9 12 15 18 9"></polyline>
   </svg>
  </button>
 </div>

 {{-- Accordion Body --}}
 <div class="accordion__body">
  {{-- Navigation --}}
  <nav class="nav--controls">
   {{-- Search Input --}}
   <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">

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
      @if ($column === 'innersession' || $column === 'session_id')
       <?php continue; ?>
      @endif
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
     @if ($relatedby === 'promotions')
      <th style="border-left: none; border-right: none;">
       <div style="display: flex;">
        <button class="button button--secondary button--sm" style="opacity: 0">
         <svg>
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
         </svg>
        </button>
       </div>
      </th>
     @endif
    </tr>
   </thead>
   <tbody>
    @if ($relateds->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($relateds as $index => $related)
      <tr @if ($loop->last) id="last_record" @endif class="expandable-row">
       <td style="border-left: none" data-title="Check">
       </td>
       @foreach ($selectedColumns as $column)
        @if ($column === 'innersession' || $column === 'session_id')
         <?php continue; ?>
        @endif
        <td @if ($index > 1) class="hidden" @endif data-title="{{ $column }}">
         @if ($column === 'payload')
          <span class="show-less">
           {{ $related->$column }}
          </span>
         @elseif ($column === 'last_activity')
          {{ date('Y-m-d H:i:s', $related->$column) }}
         @elseif ($column === 'name' && $relatedby === 'carts')
          <a href="{{ route('show_cart', ['id' => $related->id]) }}">{{ $related->name }}</a>
         @elseif ($column === 'name' && $relatedby === 'orders')
          <a href="{{ route('show_order', ['id' => $related->id]) }}">{{ $related->name }}</a>
         @elseif ($column === 'product_id' && $relatedby === 'wishlist')
          <a href="{{ route('show_product', ['id' => $related->id]) }}">{{ $related->product->name }}</a>
         @elseif ($column === 'promotion_id')
          <a href="{{ route('all_promotions') }}">{{ $related->promotion->name }}</a>
         @else
          {{ $related->$column }}
         @endif
        </td>
       @endforeach
       @if ($relatedby === 'promotions')
        <td>
         <button wire:click.prevent="confirmItemRemoval({{ $related->id }})"
          class="button button--secondary button--sm">
          <svg>
           <polyline points="3 6 5 6 21 6"></polyline>
           <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
         </button>
        </td>
       @endif
      </tr>
     @endforeach
    @endif
   </tbody>
  </table>
 </div>
</div>
