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
   <button class="button button--primary button--long" wire:click.prevent="deleteRecord()">
    <span>Delete</span>
   </button>
   <button class="button button--danger button--long" wire:click.prevent="cancelItemRemoval()">
    <span>Cancel</span>
   </button>
  </div>
 </aside>


 {{-- Navigation --}}
 <nav class="nav--controls">
  <h1 class="table--name">Account: {{ $account->name }}</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to Accounts" tooltip-top
   href="{{ route('accounts') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  @if ($edititem === null)
   <button class="button button--primary button--centered" tooltip="Edit this Account" tooltip-left
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
    wire:click.prevent="canceledit()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
     <path d="M10 12l4 5" />
     <path d="M10 17l4 -5" />
    </svg>
   </button>
  @endif
  <button class="button button--primary button--centered" tooltip="Delete this Account" tooltip-left
   wire:click.prevent="confirmItemRemoval">
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

  {{-- Account Name --}}
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $account->name }}</span>
   @else
    <input type="text" placeholder=" " wire:model.defer="record.name">
   @endif
   <label>Name</label>
  </div>

  {{-- Account Type --}}
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $account->type }}</span>
   @else
    <input type="text" placeholder=" " wire:model.defer="record.type">
   @endif
   <label>Type: individual / juridic</label>
  </div>

  {{-- Account First Name --}}
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $account->first_name }}</span>
   @else
    <input type="text" placeholder=" " wire:model.defer="record.first_name">
   @endif
   <label>First Name</label>
  </div>

  {{-- Account Last Name --}}
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $account->last_name }}</span>
   @else
    <input type="text" placeholder=" " wire:model.defer="record.last_name">
   @endif
   <label>Last Name</label>
  </div>

  {{-- Account Phone --}}
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $account->phone }}</span>
   @else
    <input type="tel" placeholder=" " wire:model.defer="record.phone">
   @endif
   <label>Phone</label>
  </div>

  {{-- Account Email --}}
  <div class="input__tabs">
   @if ($edititem === null)
    <span class="disabled">{{ $account->email }}</span>
   @else
    <input type="email" placeholder=" " wire:model.defer="record.email">
   @endif
   <label>Email</label>
  </div>

  {{-- IF Account is Juridic --}}
  @if ($account->type == 'juridic')
   {{-- Account Company Name --}}
   <div class="input__tabs">
    @if ($edititem === null)
     <span class="disabled">{{ $account->company_name }}</span>
    @else
     <input type="text" placeholder=" " wire:model.defer="record.company_name">
    @endif
    <label>Company Name</label>
   </div>

   {{-- Account Registration Code --}}
   <div class="input__tabs">
    @if ($edititem === null)
     <span class="disabled">{{ $account->registration_code }}</span>
    @else
     <input type="text" placeholder=" " wire:model.defer="record.registration_code">
    @endif
    <label>Registration Code</label>
   </div>

   {{-- Account Registration Number --}}
   <div class="input__tabs">
    @if ($edititem === null)
     <span class="disabled">{{ $account->registration_number }}</span>
    @else
     <input type="text" placeholder=" " wire:model.defer="record.registration_number">
    @endif
    <label>Registration Number</label>
   </div>

   {{-- Account Bank Name --}}
   <div class="input__tabs">
    @if ($edititem === null)
     <span class="disabled">{{ $account->bank_name }}</span>
    @else
     <input type="text" placeholder=" " wire:model.defer="record.bank_name">
    @endif
    <label>Bank Name</label>
   </div>

   {{-- Account Bank Account --}}
   <div class="input__tabs">
    @if ($edititem === null)
     <span class="disabled">{{ $account->account }}</span>
    @else
     <input type="text" placeholder=" " wire:model.defer="record.account">
    @endif
    <label>Bank Account</label>
   </div>
  @endif

  {{-- Account create date / time --}}
  <div class="input__tabs">
   <span class="disabled">{{ $account->created_at }}</span>
   <label>Create date / time</label>
  </div>

  {{-- Account Updated date / time --}}
  <div class="input__tabs">
   <span class="disabled">{{ $account->updated_at }}</span>
   <label>Updated date / time</label>
  </div>

  {{-- Save Button --}}
  @if ($edititem != null)
   <button class="button button--fill button--secondary details__long" wire:click.prevent="saveitem()"
    value="Save">
    Save
   </button>
  @endif
 </form>


 {{-- Tabs Body (Related) --}}
 <div style="height: calc(100% - 107.5px);" class="tabs__content related__view" id="relatedContent">
  @livewire('related-addresses', ['account' => $account], key('first' . $account->id))
  @livewire('related-orders', ['account' => $account], key($account->id))
  @livewire('related-invoices', ['relatedby' => 'account', 'id' => $account->id])

 </div>
</section>
