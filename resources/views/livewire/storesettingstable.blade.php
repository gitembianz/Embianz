<div class="item">
    @if (session()->has('message'))
        <div class="alert__session liveAlert" id="alertevent">
            <span class="alert__session-text">{!! session('message') !!}</span>
            <button class="alert__session-btn" type="button" data-bs-dismiss="alert" aria-hidden="true">
                <svg>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <script>
            const alertEvent = document.getElementById("alertevent");
            header.style.marginBottom = '4rem';
            alertEvent.style.opacity = '1';

            setTimeout(function() {
                alertEvent.style.opacity = '0';
                setTimeout(function() {
                    alertEvent.remove();
                    header.style.marginBottom = '0';
                }, 500);
            }, 2000);
        </script>
    @endif
    <div wire:loading>
        <div class="modal" style="display:flex;">
            <div class="loader"></div>
        </div>
    </div>
    <div class="item__header">
        <h1 id="title" class="item__header-title">{{ __('All Store Settings') }}</h1>
        <div class="item__header-buttons">
            <a href="{{ route('addstoresetting') }}" class="item__header-btn">{{ __('New') }}</a>
        </div>
    </div>

    <div class="item__form form-table"
        @if ($checked) style="grid-template-columns: 2fr 1fr 1fr 1fr" @endif>

        <div class="item__form-input">
            <input wire:model.debounce.200ms="search" type="text" required>
            <span>Search...</span>
        </div>
        <div class="item__form-input">
            <select id="perPage" wire:model="perPage">
                <option>10</option>
                <option>25</option>
                <option>50</option>
                <option>100</option>
            </select>
            <span>Per Page :</span>
        </div>

        <div class="dropdown">
            <button class="dropdown-button">Columns</button>
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
                Checked({{ count($checked) }})</button>
            @if ($checked)
                <div class="dropdown-list">
                    <button class="dropdown-item delete" type="button" wire:click="confirmItemsRemovalmultiple()">
                        Delete
                    </button>
                    <button class="dropdown-item submit" type="button" wire:click="exportSelected()">
                        Export
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if ($selectPage)
        <div class="pt-2 talign-c">

            @if ($selectAll)
                <div>
                    You have selected all <strong>{{ count($checked) }}</strong> items.
                </div>
            @else
                <div>
                    You have selected <strong>{{ count($checked) }}</strong> items, Do you want to Select All?
                    <a href="#" class="ml-2" wire:click="selectAll">Select All</a>
                </div>
            @endif

        </div>
    @endif

    {{-- modals --}}
    {{-- delete single record --}}

    <div class="modal" id="confirmationmodal">
        <div class="modal-content">
            <h1 class="modal-content-title">
                {{ __('Are you sure to delete this record?') }}
            </h1>
            <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button"
                value="Confirm">
            <input class="modal-content-btn delete" type="button"
                onclick="document.getElementById('confirmationmodal').style.display='none'" value="Cancel">

            <span class="modal-content-btn delete"
                onclick="document.getElementById('confirmationmodal').style.display='none'">
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

    {{-- end modals --}}

    {{-- Livewire Table --}}
    <table class="livewire-table">
        <thead>
            <tr>
                <th><input type="checkbox" wire:model="selectPage"></th>

                @if ($this->showColumn('Id'))
                    <th class="cursor-p"
                        @if ($orderBy === 'id' && $orderAsc === '1') data-symbol="up" @else data-symbol="down" @endif
                        wire:click="sortBy('id')">ID
                    </th>
                @endif
                @if ($this->showColumn('Parameter'))
                    <th class="cursor-p"
                        @if ($orderBy === 'parameter' && $orderAsc === '1') data-symbol="up" @else
                    data-symbol="down" @endif
                        wire:click="sortBy('parameter')">Parameter
                    </th>
                @endif
                @if ($this->showColumn('Value'))
                    <th class="cursor-p"
                        @if ($orderBy === 'value' && $orderAsc === '1') data-symbol="up" @else
                    data-symbol="down" @endif
                        wire:click="sortBy('value')">
                        Value
                    </th>
                @endif
                @if ($this->showColumn('Description'))
                    <th class="cursor-p"
                        @if ($orderBy === 'description' && $orderAsc === '1') data-symbol="up" @else
                    data-symbol="down" @endif
                        wire:click="sortBy('description')">
                        Description
                    </th>
                @endif
                @if ($this->showColumn('Created At'))
                    <th class="cursor-p"
                        @if ($orderBy === 'created_at' && $orderAsc === '1') data-symbol="up" @else
                    data-symbol="down" @endif
                        wire:click="sortBy('created_at')">Created At
                    </th>
                @endif
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($storesettings as $index => $store)
                <tr class="@if ($this->isChecked($store->id)) th_checked @endif">
                    <td data-title="Check"><input type="checkbox" value="{{ $store->id }}" wire:model="checked">
                    </td>

                    @if ($this->showColumn('Id'))
                        <td data-title="ID">{{ $store->id }}</td>
                    @endif
                    @if ($this->showColumn('Parameter'))
                        <td data-title="Parameter">
                            @if ($indexstoresettings !== $index)
                                <div class="cursor-p" wire:click.prevent="edititem({{ $index }})">
                                    {{ $store->parameter }}
                                </div>
                            @else
                                <input type="text" class="table-edit wid-1"
                                    wire:model.defer="stores.{{ $index }}.parameter"
                                    placeholder="{{ $store->parameter }}">
                            @endif
                        </td>
                    @endif
                    @if ($this->showColumn('Value'))
                        <td data-title="Value">
                            @if ($indexstoresettings !== $index)
                                <div class="cursor-p" wire:click.prevent="edititem({{ $index }})">
                                    {{ $store->value }}
                                </div>
                            @else
                                <input type="text" class="table-edit wid-1"
                                    wire:model.defer="stores.{{ $index }}.value"
                                    placeholder="{{ $store->value }}">
                            @endif

                        </td>
                    @endif
                    @if ($this->showColumn('Description'))
                        <td data-title="Description">
                            @if ($indexstoresettings !== $index)
                                <div class="cursor-p" wire:click.prevent="edititem({{ $index }})">
                                    {{ $store->description }}
                                </div>
                            @else
                                <textarea wire:model.defer="stores.{{ $index }}.description" placeholder="{{ $store->description }}"></textarea>
                            @endif

                        </td>
                    @endif

                    @if ($this->showColumn('Created At'))
                        <td data-title="Created At">{{ $store->created_at }}</td>
                    @endif

                    <td data-title="Action">
                        @if ($indexstoresettings !== $index)
                            <button class="edit" wire:click.prevent="edititem({{ $index }})">
                                <svg>
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                    </path>
                                </svg>
                            </button>
                            <button class="delete" wire:click.prevent="confirmItemRemoval({{ $store->id }})">
                                <svg>
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                            </button>
                        @else
                            <button class="edit"
                                wire:click.prevent="saveitem({{ $index }} , {{ $store->id }})">
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
    <div>{{ $storesettings->links('pagination-links') }} </div>
</div>
