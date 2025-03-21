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
  <h1 class="table--name">County: {{ $county->name }}</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to all country" tooltip-top
   href="{{ route('countries') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  @if ($edititem === null)
   <button class="button button--primary button--centered" tooltip="Edit this Country" tooltip-left
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
  <button class="button button--primary button--centered" tooltip="Delete this Country" tooltip-left
   wire:click.prevent="confirmItemRemoval({{ $county->id }})">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 14l6 0" />
   </svg>
  </button>
 </nav>


 <nav class="nav--tabs">
  <button class="button button--primary button--long button--active" id="detailsButton">
   Details
  </button>
  <button class="button button--primary button--long" id="relatedButton">
   Related
  </button>
 </nav>

 <form style="height: calc(100% - 107.5px);" class="tabs__content details__view active" id="detailsContent">
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $county->name }}</span>
   @else
    <input type="text" wire:model.defer="record.name">
   @endif
   <label> Name</label>
  </div>

  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $county->iso_code }}</span>
   @else
    <input type="text" wire:model.defer="record.iso_code">
   @endif
   <label>Iso code</label>
  </div>


  <div class="details__checkboxes details__long">

   <div class="checkbox__details ">
    @if ($edititem === null)
     @if ($county->status)
      <input type="checkbox" id="active1" checked class="disabled" disabled />
      <label for="active1" class="disabled">Active</label>
     @else
      <input type="checkbox" id="active2" class="disabled" disabled />
      <label for="active2" class="disabled">Active</label>
     @endif
    @else
     <input type="checkbox" id="active3" wire:model.defer="record.status" />
     <label for="active3">Status</label>
    @endif
   </div>
  </div>

  <div class="input__tabs">
   <span class="disabled">{{ $county->created_at }}</span>
   <label>Create date / time</label>
  </div>

  <div class="input__tabs">
   <span class="disabled">{{ $county->updated_at }}</span>
   <label>Updated date / time</label>
  </div>

  @if ($edititem != null)
   <button class="button button--fill button--secondary details__long" wire:click.prevent="saveitem()" value="Save">
    Save
   </button>
  @endif
 </form>


 {{-- Tabs Body (Related) --}}
 <div style="height: calc(100% - 107.5px);" class="tabs__content related__view" id="relatedContent">
  @livewire('related-city', ['countyId' => $county->id])
 </div>
</section>
