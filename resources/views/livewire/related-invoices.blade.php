<div class="accordion @if ($showrelated) active @endif">
 {{-- Delete Record OR Records --}}
 <x-alert />

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
 {{-- preview sistem --}}
 <aside>
  <div class="background background--center @if ($previewurl != null) active @endif"></div>
  <div class="aside aside--table @if ($previewurl != null) active @endif">
   <iframe src="/{{ $previewurl }}" width="100%" height="95%" style="border: none;">
    Your browser does not support iframes. Please download the file
    <a href="/{{ $previewurl }}">here</a>.
   </iframe>
   <button class="button button--danger button--long" wire:click="cancel_preview()">
    <span>Cancel</span>
   </button>
  </div>
 </aside>


 {{-- Accordion Header --}}
 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif"
   wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">
   {{ __('Invoices ') }}({{ $invoices->count() }})
   <svg>
    <polyline points="6 9 12 15 18 9"></polyline>
   </svg>
  </button>
  <!-- <button class="button button--secondary">
        <svg>
     <line x1="12" y1="5" x2="12" y2="19"></line>
     <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    </button> -->
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
       <input type="checkbox" id="selectPage14" wire:model="selectPage" />
       <label for="selectPage14"></label>
      </div>
     </th>
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
     @if ($this->showColumn('Order'))
      <th>
       <button class="table--btn">
        Order
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Account'))
      <th>
       <button class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Account
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Date'))
      <th class="hidden">
       <button wire:click="sortBy('date')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Date
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Type'))
      <th class="hidden">
       <button wire:click="sortBy('type')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Type
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Path'))
      <th class="hidden">
       <button wire:click="sortBy('path')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Path
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Created At'))
      <th class="hidden">
       <button wire:click="sortBy('created_at')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Created At
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Updated At'))
      <th class="hidden">
       <button wire:click="sortBy('updated_at')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
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
    @if ($invoices->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($invoices as $index => $invoice)
      <tr @if ($loop->last) id="last_record" @endif
       class="expandable-row @if ($this->isChecked($invoice->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $invoice->id }}" id="{{ $invoice->id }}" wire:model="checked">
         <label for="{{ $invoice->id }}"></label>
        </div>
       </td>
       @if ($this->showColumn('Id'))
        <td wire:click="expandRow({{ $index }})">{{ $invoice->id }}</td>
       @endif
       @if ($this->showColumn('Order'))
        <td wire:click="expandRow({{ $index }})">
         <a href="{{ route('show_order', ['id' => $invoice->order_id]) }}">{{ $invoice->order->name }}</a>
        </td>
       @endif
       @if ($this->showColumn('Account'))
        <td wire:click="expandRow({{ $index }})">
         <a href="{{ route('show_account', ['id' => $invoice->account_id]) }}">{{ $invoice->account->name }}</a>
        </td>
       @endif
       @if ($this->showColumn('Date'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $invoice->date }}
        </td>
       @endif
       @if ($this->showColumn('Type'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $invoice->type }}
        </td>
       @endif
       @if ($this->showColumn('Path'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $invoice->path }}
        </td>
       @endif
       @if ($this->showColumn('Created At'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $invoice->created_at }}
        </td>
       @endif
       @if ($this->showColumn('Updated At'))
        <td class="hidden" wire:click="expandRow({{ $index }})">
         {{ $invoice->updated_at }}
        </td>
       @endif
       <td style="border-right: none">
        <div style="display:flex;">
         <button wire:click.prevent="confirmItemRemoval({{ $invoice->id }})"
          class="button button--secondary button--sm">
          <svg>
           <polyline points="3 6 5 6 21 6"></polyline>
           <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
         </button>
         <button wire:click.prevent="downloadInvoice({{ $invoice->id }})"
          class="button button--secondary button--sm">
          <svg>
           <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
           <polyline points="7 10 12 15 17 10"></polyline>
           <line x1="12" y1="15" x2="12" y2="3"></line>
          </svg>
         </button>
         <button wire:click.prevent="previewInvoice({{ $invoice->id }})"
          class="button button--secondary button--sm">
          <svg>
           <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
           <circle cx="12" cy="12" r="3"></circle>
          </svg>
         </button>

        </div>
       </td>
      </tr>
      <tr class="details-row  @if ($row === $i) active @endif">
       <td colspan="{{ count($columns) + 2 }}">
        <div class="details">

         @if ($this->showColumn('Date'))
          <p>
           <bold>Date</bold>
           {{ $invoice->date }}
          </p>
         @endif
         @if ($this->showColumn('Type'))
          <p>
           <bold>Type</bold>
           {{ $invoice->type }}
          </p>
         @endif
         @if ($this->showColumn('Path'))
          <p>
           <bold>Path</bold>
           {{ $invoice->path }}
          </p>
         @endif

         @if ($this->showColumn('Created At'))
          <p>
           <bold>Created At</bold>
           {{ $invoice->created_at }}
          </p>
         @endif
         @if ($this->showColumn('Updated At'))
          <p>
           <bold>Updated At</bold>
           {{ $invoice->updated_at }}
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
  @if (count($invoices) >= 10)
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="load">
    Load more
   </button>
  @endif
 </div>
</div>
