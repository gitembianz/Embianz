<div>
 <x-alert />
 <x-loading />
 {{-- modals --}}
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
   {{ __('Store settings') }}
  </h1>
  <input class="panel__header--input" type="text" wire:model.debounce.300ms="search"
   placeholder="Search your price field...">
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
    <button class="dropdown-button none" @if ($checked) style="display: flex" @endif>
     Checked {{ count($checked) }}</button>
    @if ($checked)
     <div class="dropdown-list">
      <button class="dropdown-item delete" style="width: 150px" type="button"
       wire:click="confirmItemsRemovalmultiple()">
       Delete
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
    <span style="margin-left: 10px">Refresh</span>
   </a>
   <a class="panel__header--button" href="{{ route('add_storesetting') }}">
    <svg>
     <line x1="12" y1="5" x2="12" y2="19"></line>
     <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    <span style="margin-left: 10px">Add new store setting</span>
   </a>
   <a class="panel__header--button" wire:click="actualizeaza">
    <svg>
     <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
     <polyline points="17 8 12 3 7 8"></polyline>
     <line x1="12" y1="3" x2="12" y2="15"></line>
    </svg> <span style="margin-left: 10px">Update website</span>
   </a>
   <a class="panel__header--button" wire:click="addSettingsIfNotExist">
    <svg>
     <polyline points="17 1 21 5 17 9"></polyline>
     <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
     <polyline points="7 23 3 19 7 15"></polyline>
     <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
    </svg> <span style="margin-left: 10px">Update Parameters</span>
   </a>
   <a class="panel__header--button" wire:click="sitemap">
    <svg>
     <circle cx="12" cy="12" r="10"></circle>
     <line x1="2" y1="12" x2="22" y2="12"></line>
     <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
    </svg> <span style="margin-left: 10px">Generate sitemap.xml</span>
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
    @if ($this->showColumn('Id'))
     <th wire:click="sortBy('id')">
      <button class="table__header--btn"
       @if ($orderBy === 'id' && $orderAsc === '1') data-symbol="up"
                              @else data-symbol="down" @endif>
       ID
       <svg>
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <polyline points="19 12 12 19 5 12"></polyline>
       </svg>
      </button>
     </th>
    @endif
    @if ($this->showColumn('Parameter'))
     <th wire:click="sortBy('parameter')">
      <button class="table__header--btn"
       @if ($orderBy === 'parameter' && $orderAsc === '1') data-symbol="up" @else
                            data-symbol="down" @endif>
       Parameter
       <svg>
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <polyline points="19 12 12 19 5 12"></polyline>
       </svg>
      </button>
     </th>
    @endif
    @if ($this->showColumn('Value'))
     <th wire:click="sortBy('value')">
      <button class="table__header--btn"
       @if ($orderBy === 'value' && $orderAsc === '1') data-symbol="up" @else
                            data-symbol="down" @endif>
       Value
       <svg>
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <polyline points="19 12 12 19 5 12"></polyline>
       </svg>
      </button>
     </th>
    @endif
    @if ($this->showColumn('Description'))
     <th wire:click="sortBy('description')">
      <button class="table__header--btn"
       @if ($orderBy === 'description' && $orderAsc === '1') data-symbol="up" @else
                            data-symbol="down" @endif>
       Description
       <svg>
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <polyline points="19 12 12 19 5 12"></polyline>
       </svg>
      </button>
     </th>
    @endif
    @if ($this->showColumn('Created At'))
     <th wire:click="sortBy('created_at')">
      <button class="table__header--btn"
       @if ($orderBy === 'created_at' && $orderAsc === '1') data-symbol="up"
                          @else data-symbol="down" @endif>
       Created at
       <svg>
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <polyline points="19 12 12 19 5 12"></polyline>
       </svg>
      </button>
     </th>
    @endif
    @if ($this->showColumn('Updated At'))
     <th wire:click="sortBy('updated_at')">
      <button class="table__header--btn"
       @if ($orderBy === 'updated_at' && $orderAsc === '1') data-symbol="up"
                          @else data-symbol="down" @endif>
       Updated at
       <svg>
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <polyline points="19 12 12 19 5 12"></polyline>
       </svg>
      </button>
     </th>
    @endif
    <th></th>
   </tr>
  </thead>
  <tbody>
   @foreach ($storesettings as $index => $store)
    <tr class="@if ($this->isChecked($store->id)) table__row--selected @endif">

     <td data-title="Check">
      <input type="checkbox" value="{{ $store->id }}" wire:model="checked">
     </td>
     @if ($this->showColumn('Id'))
      <td data-title="ID">{{ $store->id }}</td>
     @endif
     @if ($this->showColumn('Parameter'))
      <td data-title="Parameter">
       {{ $store->parameter }}
      </td>
     @endif
     @if ($this->showColumn('Value'))
      <td data-title="Value">
       @if ($indexstoresettings !== $index)
        {{ $store->value }}@if ($store->parameter == 'time_zone')
         (time is: {{ now() }}) - Please refresh to update after the edit
        @endif
       @else
        <input type="text" class="table__edit" wire:model.defer="settings.{{ $index }}.value">
       @endif
      </td>
     @endif
     @if ($this->showColumn('Description'))
      <td data-title="Description">
       @if ($indexstoresettings !== $index)
        {{ $store->description }}
       @else
        <textarea class="table__edit" wire:model.defer="settings.{{ $index }}.description"></textarea>
       @endif
      </td>
     @endif
     @if ($this->showColumn('Created At'))
      <td data-title="Created At">
       <div class="table__time">
        {{ $store->created_at }}
        <svg>
         <circle cx="12" cy="12" r="10"></circle>
         <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
       </div>
      </td>
     @endif
     @if ($this->showColumn('Updated At'))
      <td data-title="Updated At">
       <div class="table__time">
        {{ $store->updated_at }}
        <svg>
         <circle cx="12" cy="12" r="10"></circle>
         <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
       </div>
      </td>
     @endif
     <td data-title="Action" class="table__buttons">
      @if ($indexstoresettings !== $index)
       <button class="edit" wire:click.prevent="edititem({{ $index }}, {{ $store->id }})">
        <svg>
         <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
         </path>
        </svg>
       </button>
       {{-- <button class="delete" wire:click.prevent="confirmItemRemoval({{ $store->id }})">
        <svg>
         <polyline points="3 6 5 6 21 6"></polyline>
         <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
         </path>
        </svg>
       </button> --}}
      @else
       <button class="edit" wire:click.prevent="saveitem({{ $index }} , {{ $store->id }})">
        <svg>
         <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
       </button>
       <button class="save" wire:click.prevent="cancelitem()">
        <svg>
         <line x1="18" y1="6" x2="6" y2="18"></line>
         <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
       </button>
      @endif
     </td>
    </tr>
   @endforeach
  </tbody>
 </table>
 @if ($loadAmount <= count($storesettings))
  <div class="table__load-more" wire:click="loadMore">
   Load more
  </div>
 @endif
</div>
