<section class="content">
 {{-- X-Components --}}
 <x-alert />


 {{-- Delete Record --}}
 <aside>
  <div class="background background--center @if ($delete) active @endif"></div>
  <div class="aside aside--confirm @if ($delete) active @endif">
   <span>
    Are you sure to delete this record?
   </span>
   <button class="button button--primary button--long" wire:click.prevent="deleteSingleRecord()">
    <span>Delete</span>
   </button>
   <button class="button button--danger button--long" wire:click.prevent="cancelItemRemoval()">
    <span>Cancel</span>
   </button>
  </div>
 </aside>


 {{-- Navigation --}}
 <nav class="nav--controls">
  <h1 class="table--name">Order Supplier: {{ $supplier->name }}</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to all supplier" tooltip-top
   href="{{ route('suppliers') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <a class="button button--primary button--centered" tooltip="Create new supplier" tooltip-top
   href="{{ route('add_supplier') }}">
   <svg>
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
    <polyline points="14 2 14 8 20 8"></polyline>
    <line x1="12" y1="18" x2="12" y2="12"></line>
    <line x1="9" y1="15" x2="15" y2="15"></line>
   </svg>
  </a>
  @if ($edititem === null)
   <button class="button button--primary button--centered" tooltip="Edit this Supplier" tooltip-left
    wire:click.prevent="edititem()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
     <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
     <path d="M16 5l3 3" />
    </svg>
   </button>
  @else
   <button class="button button--primary button--centered" tooltip="Save Edit" tooltip-left
    wire:click.prevent="saveitem()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
     <path d="M9 15l2 2l4 -4" />
    </svg>
   </button>
   <button class="button button--primary button--centered" tooltip="Cancel edit" tooltip-left
    wire:click.prevent="cancelitem()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
     <path d="M10 12l4 5" />
     <path d="M10 17l4 -5" />
    </svg>
   </button>
  @endif
  <button class="button button--primary button--centered" tooltip="Delete this Supplier" tooltip-left
   wire:click.prevent="confirmItemRemoval({{ $supplier->id }})">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 14l6 0" />
   </svg>
  </button>
 </nav>


 {{-- Tabs Header --}}
 <nav class="nav--tabs">
  <button class="button button--primary button--long button--active" id="detailsButton">
   Details
  </button>
  <button class="button button--primary button--long" id="relatedButton">
   Related
  </button>
 </nav>


 {{-- Tabs Body (Details) --}}
 <form style="height: calc(100% - 107.5px);" class="tabs__content details__view active" id="detailsContent">
  {{-- Price List Name --}}
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $supplier->name }}</span>
   @else
    <input type="text" wire:model.defer="record.name">
   @endif
   <label>Name</label>
  </div>
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $supplier->supplier_name }}</span>
   @else
    <input type="text" wire:model.defer="record.supplier_name">
   @endif
   <label>Supplier Name</label>
  </div>
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $supplier->status }}</span>
   @else
    <select wire:model.defer="record.status">
     <option value="draft">draft</option>
     <option value="pending">pending</option>
     <option value="closed">closed</option>
    </select>
   @endif
   <label>status</label>
  </div>
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $supplier->date }}</span>
   @else
    <input type="date" wire:model.defer="record.date">
   @endif
   <label>Date</label>
  </div>
  {{-- Price List Currency --}}
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $supplier->currency }}</span>
   @else
    <select wire:model.defer="record.currency">
     @foreach ($currencies as $currency)
      <option value="{{ $currency->name }}">{{ $currency->name }}</option>
     @endforeach
    </select>
   @endif
   <label>Currency</label>
  </div>


  {{-- Price List Create by --}}
  <div class="input__tabs">
   <span class="disabled">{{ $totalPrice }} {{ $supplier->currency }}</span>
   <label>Total amount</label>
  </div>

  {{-- Price List Create by --}}
  <div class="input__tabs">
   <span class="disabled">{{ $supplier->created_by }}</span>
   <label>Create by</label>
  </div>
  {{-- Price List Last modified by --}}
  <div class="input__tabs">
   <span class="disabled">{{ $supplier->last_modified_by }}</span>
   <label>Last modified by</label>
  </div>
  {{-- Price List Create date / time --}}
  <div class="input__tabs">
   <span class="disabled">{{ $supplier->created_at }}</span>
   <label>Create date / time</label>
  </div>



  {{-- Price List Updated date / time --}}
  <div class="input__tabs">
   <span class="disabled">{{ $supplier->updated_at }}</span>
   <label>Updated date / time</label>
  </div>



  {{-- Save Button --}}
  @if ($edititem != null)
   <button class="button button--fill button--secondary details__long" wire:click.prevent="saveitem()" value="Save">
    Save
   </button>
  @endif
 </form>


 {{-- Tabs Body (Related) --}}
 <div style="height: calc(100% - 107.5px);" class="tabs__content related__view" id="relatedContent">
  @livewire('related-order-supplier', ['supplierId' => $supplier->id])
 </div>
</section>
