<div class="accordion @if ($showrelated) active @endif">
 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif"
   wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">
   {{ __('similarities Similatity ') }}({{ count($similarities['similar']) }})
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
     @if ($this->showColumn('Products'))
      <th>Products with Count</th>
     @endif
     @if ($this->showColumn('Orders'))
      <th>Similar Orders</th>
     @endif
     @if ($this->showColumn('Similarity'))
      <th>Similarity (%)</th>
     @endif
    </tr>
   </thead>
   <tbody>
    @if ($similarities['similar']->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 3 }}">No record found.</td>
     </tr>
    @else
     @foreach ($similarities['similar'] as $similarityPercentage => $group)
      <tr class="expandable-row">
       <td style="border-left: none" data-title="Check"></td>

       @if ($this->showColumn('Reference'))
        <td>{{ $similarities['reference']['order']->name }}</td>
       @endif

       @if ($this->showColumn('Products'))
        <td>
         <ul>
          @foreach ($group['products'] as $product)
           @php
            $referenceQuantity = 0;
            foreach ($similarities['reference']['products'] as $pr) {
                if ($pr['name'] === $product['name']) {
                    $referenceQuantity = $pr['total_quantity'];
                    break;
                }
            }
           @endphp
           <li>{{ $product['name'] }} - sku({{ $product['sku'] }})
            total quanity (x{{ $product['total_quantity'] + $referenceQuantity }})
           </li>
          @endforeach
         </ul>
        </td>
       @endif

       @if ($this->showColumn('Orders'))
        <td>
         <ul>
          @foreach ($group['orders'] as $order)
           <li>
            <a href="{{ route('show_order', ['id' => $order['order']->id]) }}">
             {{ $order['order']->name }}
            </a>
           </li>
          @endforeach
         </ul>
        </td>
       @endif

       @if ($this->showColumn('Similarity'))
        <td>{{ $similarityPercentage }}%</td>
       @endif
      </tr>
     @endforeach
    @endif
   </tbody>
  </table>







 </div>
</div>
