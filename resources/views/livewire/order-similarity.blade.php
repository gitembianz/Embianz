<div class="accordion @if ($showrelated) active @endif">
 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif"
   wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">
   {{ __('similarities Similatity ') }}({{ $similarities->count() }})
   <svg>
    <polyline points="6 9 12 15 18 9"></polyline>
   </svg>
  </button>
 </div>
 <div class="accordion__body">
  <nav class="nav--controls">
   <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
   <div class="dropdown dropdown--right" wire:ignore>
    <button class="button button--primary button--centered" tooltip="Show items in table" tooltip-left>
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
      <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
     </svg>
    </button>
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
     <th style="border-right: none; border-left: none;"></th>
     @if ($this->showColumn('Reference'))
      <th>Order Reference</th>
     @endif
     @if ($this->showColumn('Orders'))
      <th>Similar Orders</th>
     @endif
     <th>Similarity (%)</th>
    </tr>
   </thead>
   <tbody>
    @if ($similarities->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 3 }}">No record found.</td>
     </tr>
    @else
     @foreach ($similarities as $similarityPercentage => $ordersGroup)
      <tr class="expandable-row">
       <td style="border-left: none" data-title="Check"></td>

       @if ($this->showColumn('Reference'))
        <td>{{ $order->name ?? 'N/A' }}</td>
       @endif

       @if ($this->showColumn('Orders'))
        <td>
         <ul>
          @foreach ($ordersGroup as $similarity)
           <li>{{ $similarity['order']->name ?? 'N/A' }}</li>
          @endforeach
         </ul>
        </td>
       @endif

       <td>{{ $similarityPercentage }}%</td>
      </tr>
     @endforeach
    @endif
   </tbody>
  </table>

 </div>
</div>
