<div>
 <x-alert />
 <x-loading />
 {{-- delete single record --}}
 <div class="modal" id="confirmationmodal">
  <div class="modal-content">
   <h1 class="modal-content-title">
    {{ __('Are you sure to delete this record?') }}
   </h1>
   <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button" value="Confirm">
   <input class="modal-content-btn delete" type="button"
    onclick="document.getElementById('confirmationmodal').style.display='none'" value="Cancel">
   <span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodal').style.display='none'">
    <svg>
     <line x1="18" y1="6" x2="6" y2="18"></line>
     <line x1="6" y1="6" x2="18" y2="18"></line>
    </svg>
   </span>
  </div>
 </div>
 {{-- delete myltiple records --}}
 <div class="modal" id="confirmationmodalmultiple">
  <div class="modal-content">
   <h1 class="modal-content-title">
    {{ __('Are you sure to delete those records?') }}
   </h1>
   <input wire:click.prevent="deleteRecords()" class="modal-content-btn submit" type="button" value="Confirm">
   <input class="modal-content-btn delete" type="button"
    onclick="document.getElementById('confirmationmodalmultiple').style.display='none'" value="Cancel">
   <span class="modal-content-btn delete"
    onclick="document.getElementById('confirmationmodalmultiple').style.display='none'">
    <svg>
     <line x1="18" y1="6" x2="6" y2="18"></line>
     <line x1="6" y1="6" x2="18" y2="18"></line>
    </svg>
   </span>
  </div>
 </div>
 {{-- Header of the table --}}
 <div class="panel__header">
  <h1 class="panel__header--title">
   {{ __('Payment Methods') }}
  </h1>
  <input class="panel__header--input" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
  <div class="panel__header--bundle">
   <div class="dropdown">
    <button class="dropdown-button">Columns
     <svg>
      <polyline points="6 9 12 15 18 9"></polyline>
     </svg>
    </button>
    <div class="dropdown-list">
     @foreach ($columns as $column)
      <div class="dropdown-item">
       <input type="checkbox" wire:model="selectedColumns" value="{{ $column }}"
        {{ in_array($column, $selectedColumns) ? 'checked' : '' }}>
       <label>{{ $column }}</label>
      </div>
     @endforeach
    </div>
   </div>
   <div class="dropdown none" @if ($checked) style="display: unset" @endif>
    <button class="dropdown-button none" @if ($checked) style="display: flex" @endif>With
     With checked({{ count($checked) }})</button>
    @if ($checked)
     <div class="dropdown-list">
      <button class="dropdown-item delete" style="width: 150px" type="button"
       wire:click="confirmItemsRemovalmultiple()">
       Delete
      </button>
      <button class="dropdown-item submit" style="width: 150px" type="button" wire:click="exportSelected()">
       Export
      </button>
     </div>
    @endif
   </div>
   <a class="panel__header--button" wire:click="$refresh">
    <svg>
     <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
     <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
     <g id="SVGRepo_iconCarrier">
      <path
       d="M3 3V8M3 8H8M3 8L6 5.29168C7.59227 3.86656 9.69494 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.71683 21 4.13247 18.008 3.22302 14"
       stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
     </g>
    </svg>
   </a>
  </div>
  @if ($selectPage && $selectAll)
   <div class="panel__header--checked">
    <p>
     You selected <strong>{{ count($checked) }}</strong> items.
    </p>
   </div>
  @elseif($selectPage)
   <div class="panel__header--checked" wire:click="selectAll">
    <p>
     You selected {{ count($checked) }} items, select all?
    </p>
   </div>
  @endif
 </div>
 {{-- Table --}}
 <table class="table">
  <thead>
   <tr>
    <th>
     <input type="checkbox" wire:model="selectPage">
    </th>
    @foreach ($selectedColumns as $column)
     @if ($this->showColumn($column))
      <th wire:click="sortBy('{{ $column }}')">
       <button class="table__header--btn"
        @if ($orderBy === $column && $orderAsc === '1') data-symbol="up"
                @else data-symbol="down" @endif>
        {{ $column }}
        <svg>
         <line x1="12" y1="5" x2="12" y2="19"></line>
         <polyline points="19 12 12 19 5 12"></polyline>
        </svg>
       </button>
      </th>
     @endif
    @endforeach
    <th></th>
   </tr>
  </thead>
  <tbody>
   @if ($payments->isEmpty())
    <tr>
     <td class="table__empty" colspan="{{ count($selectedColumns) + 3 }}">No record found.</td>
    </tr>
   @else
    @foreach ($payments as $index => $item)
     <tr @if ($loop->last) id="last_record" @endif
      class="@if ($this->isChecked($item->id)) table__row--selected @endif">
      <td data-title="Check">
       <input type="checkbox" value="{{ $item->id }}" wire:model="checked">
      </td>
      @foreach ($selectedColumns as $column)
       @if ($column === 'created_at' || $column === 'updated_at')
        <td data-title="{{ $column }}">
         <div class="table__time">
          <svg>
           <circle cx="12" cy="12" r="10"></circle>
           <polyline points="12 6 12 12 16 14"></polyline>
          </svg>
          {{ $item->$column }}
         </div>
        </td>
       @elseif ($column === 'active')
        <td data-title="{{ $column }}">
         @if ($editeindex !== $index)
          {{ $item->active ? 'Active' : 'Inactive' }}
         @else
          <input type="checkbox" class="table__edit" wire:model.defer="isactive.{{ $index }}.active">
         @endif
        </td>
       @else
        <td data-title="{{ $column }}">{{ $item->$column }}</td>
       @endif
      @endforeach
      <td data-title="Action" class="table__buttons">
       <div class="table__buttons">
        @if ($editeindex !== $index)
         <button class="edit" wire:click.prevent="edit({{ $index }}, {{ $item->id }})">
          <svg>
           <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
           </path>
          </svg>
         </button>
         <button class="delete" wire:click.prevent="confirmItemRemoval({{ $item->id }})">
          <svg>
           <polyline points="3 6 5 6 21 6"></polyline>
           <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
           </path>
          </svg>
         </button>
        @else
         <button class="edit" wire:click.prevent="save({{ $index }} , {{ $item->id }})">
          <svg>
           <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
         </button>
         <button class="save" wire:click.prevent="cancel()">
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
    @endforeach
   @endif
  </tbody>
 </table>
 @if ($loadAmount <= count($payments))
  <div class="table__load-more" wire:click="loadMore">
   Load more
  </div>
 @endif
</div>
