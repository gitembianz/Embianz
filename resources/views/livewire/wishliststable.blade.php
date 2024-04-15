<div>
 <x-alert />
 <x-loading />
 {{-- delete single record --}}
 <div class="modal" id="confirmationmodalwislists">
  <div class="modal-content">
   <h1 class="modal-content-title">
    {{ __('Are you sure to delete this record?') }}
   </h1>
   <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button" value="Confirm">
   <input class="modal-content-btn delete" type="button"
    onclick="document.getElementById('confirmationmodalwislists').style.display='none'" value="Cancel">
   <span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodalwislists').style.display='none'">
    <svg>
     <line x1="18" y1="6" x2="6" y2="18"></line>
     <line x1="6" y1="6" x2="18" y2="18"></line>
    </svg>
   </span>
  </div>
 </div>
 {{-- delete myltiple records --}}
 <div class="modal" id="confirmationmodalmultiplewishlists">
  <div class="modal-content">
   <h1 class="modal-content-title">
    {{ __('Are you sure to delete those records?') }}
   </h1>
   <input wire:click.prevent="deleteRecords()" class="modal-content-btn submit" type="button" value="Confirm">
   <input class="modal-content-btn delete" type="button"
    onclick="document.getElementById('confirmationmodalmultiplewishlists').style.display='none'" value="Cancel">
   <span class="modal-content-btn delete"
    onclick="document.getElementById('confirmationmodalmultiplewishlists').style.display='none'">
    <svg>
     <line x1="18" y1="6" x2="6" y2="18"></line>
     <line x1="6" y1="6" x2="18" y2="18"></line>
    </svg>
   </span>
  </div>
 </div>
 {{-- Header of the table --}}
 <div class="panel__header">
  
  <div class="panel__header--bundle">
    <h1 class="panel__header--title">
   {{ __('Wislists') }}
  </h1>
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
     checked({{ count($checked) }})</button>
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
            @if ($column == 'id')
                <?php continue;?>
            @endif
                @if ($this->showColumn($column))
                    <th wire:click="sortBy('{{ $column }}')">
                        <button class="table__header--btn"
                            @if ($orderBy === $column && $orderAsc === '1') data-symbol="up"
                            @else data-symbol="down" @endif>
                            {{ str_replace("_id", "", $column) }}
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
        @if ($wishlists->isEmpty())
            <tr>
                <td class="table__empty" colspan="{{ count($selectedColumns) + 1 }}">No record found.</td>
            </tr>
        @else
            @foreach ($wishlists as $index => $item)
                <tr @if ($loop->last) id="last_record" @endif
                    class="@if ($this->isChecked($item->session_id)) table__row--selected @endif">
                    <td data-title="Check">
                        <input type="checkbox" value="{{ $item->session_id }}" wire:model="checked">
                    </td>
                    @foreach ($selectedColumns as $column)
                        @if ($column == 'id')
                            <?php continue;?>
                        @endif
                        <td data-title="{{ $column }}">
                            @if ($column == 'updated_at')
                                {{ $item->latest_updated_at }}
                            @elseif ($column == 'created_at')
                                {{ $item->earliest_created_at }}
                            @elseif ($column == 'product_id')
                                 @foreach(explode(',', $item->product_ids) as $productId)
                                    <a href="/show_product/{{ $productId }}">{{ $productId }}</a>
                                @endforeach
                            @else
                                {{ $item->{$column} }}
                            @endif
                        </td>
                    @endforeach
                    <td data-title="Action" class="table__buttons">
                        <div class="table__buttons">
                            <button class="delete" wire:click="remove('{{ $item->session_id }}')">
                                <svg>
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

 @if ($loadAmount <= count($wishlists))
  <div class="table__load-more" wire:click="loadMore">
   Load more
  </div>
 @endif
</div>
