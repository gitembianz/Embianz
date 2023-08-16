<div>
    <x-alert />
    <div wire:loading.delay>
        <div class="modal" style="display:flex;">
            <div class="loader"></div>
        </div>
    </div>


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


    {{-- Header of the table --}}
    <div class="panel__header">
        <h1 class="panel__header--title">
            {{ __('Price List') }}
        </h1>
        <input class="panel__header--input" type="text" wire:model.debounce.200ms="search"
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
                        <button class="dropdown-item submit" style="width: 150px" type="button"
                            wire:click="exportSelected()">
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
            <a class="panel__header--button" href="{{ route('newpricelist') }}">
                <svg>
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </a>
        </div>
        @if ($selectPage)
            <div class="panel__header--checked">
                @if ($selectAll)
                    <p>
                        You selected <strong>{{ count($checked) }}</strong> items.
                    </p>
                @else
                    <a href="#" class="ml-2" wire:click="selectAll">
                        <p>
                            You selected <strong>{{ count($checked) }}</strong> items, Do you want to Select All?
                        </p>
                    </a>
                @endif
            </div>
        @endif
    </div>
    {{-- Table --}}
    <table class="table">
        <thead>
            <tr>
                <th><input type="checkbox" wire:model="selectPage"></th>

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

                @if ($this->showColumn('Name'))
                    <th wire:click="sortBy('name')">
                        <button class="table__header--btn"
                            @if ($orderBy === 'name' && $orderAsc === '1') data-symbol="up"
                            @else data-symbol="down" @endif>
                            Name
                            <svg>
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <polyline points="19 12 12 19 5 12"></polyline>
                            </svg>
                        </button>
                    </th>
                @endif

                @if ($this->showColumn('Currency'))
                    <th>
                        <button class="table__header--btn">
                            Currency
                        </button>
                    </th>
                @endif

                @if ($this->showColumn('Active'))
                    <th>
                        <button class="table__header--btn">
                            Is Active
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

                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($pricelists as $index => $price)
                <tr @if ($loop->last) id="last_record" @endif
                    class="@if ($this->isChecked($price->id)) table__row--selected @endif">

                    <td data-title="Check">
                        <input type="checkbox" value="{{ $price->id }}" wire:model="checked">
                    </td>

                    @if ($this->showColumn('Id'))
                        <td data-title="ID">{{ $price->id }}</td>
                    @endif

                    @if ($this->showColumn('Name'))
                        <td data-title="Name">
                            @if ($indexprice !== $index)
                                <div><a href="/show_pricelist/{{ $price->id }}'">{{ $price->name }}</a>
                                </div>
                            @else
                                <input type="text" class="table__edit wid-1"
                                    wire:model.defer="prices.{{ $index }}.name"
                                    placeholder="{{ $price->name }}">
                            @endif

                        </td>
                    @endif

                    @if ($this->showColumn('Currency'))
                        <td data-title="Currency">
                            @if ($indexprice !== $index)
                                <div class="cursor-p" wire:click.prevent="edititem({{ $index }})">
                                    {{ $price->currency->name }}</div>
                            @else
                                <select class="table__edit" wire:model.defer="prices.{{ $index }}.currency">
                                    <option>Select currency</option>
                                    @foreach ($currencies as $curency)
                                        <option value="{{ $curency->id }}">
                                            {{ $curency->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </td>
                    @endif

                    @if ($this->showColumn('Active'))
                        <td data-title="Active">
                            @if ($indexprice !== $index)
                                <div class="cursor-p" wire:click.prevent="edititem({{ $index }})">
                                    @if ($price->active === 1)
                                        True
                                    @else
                                        False
                                    @endif
                                </div>
                            @else
                                <input type="checkbox" wire:model.defer="prices.{{ $index }}.active" checked>
                            @endif
                        </td>
                    @endif

                    @if ($this->showColumn('Created At'))
                        <td data-title="Created At">
                            <div class="table__time">
                                <svg>
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                {{ $price->created_at }}
                        </td>
                    @endif

                    <td data-title="Action" class="table__buttons">
                        @if ($indexprice !== $index)
                            <button class="edit" wire:click.prevent="edititem({{ $index }})">
                                <svg>
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                    </path>
                                </svg>
                            </button>
                            <button class="delete" wire:click.prevent="confirmItemRemoval({{ $price->id }})">
                                <svg>
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg>
                            </button>
                        @else
                            <button class="edit"
                                wire:click.prevent="saveitem({{ $index }} , {{ $price->id }})">
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

        <script>
            const lastRecord = document.getElementById('last_record');
            const options = {
                root: null,
                threshold: 1,
                rootMargin: '0px'
            }
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        @this.loadMore()
                    }
                });
            });
            observer.observe(lastRecord);
        </script>
    </table>
</div>
