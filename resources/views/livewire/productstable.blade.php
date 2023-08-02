<div>
    {{-- Asta trebuie de facut componenta --}}
    <x-alert />
    <div wire:loading.delay>
        <div class="modal" style="display:flex;">
            <div class="loader"></div>
        </div>
    </div>
    <div class=" @if ($checked) item__form form-table-mobile @else item__form form-table @endif">
        <h1 id="title" class="item__header-title">{{ __('Products') }}</h1>

        <div class="item__form-input" id="searchInput">
            <input wire:model.debounce.200ms="search" required type="text" id="newInput" placeholder="Search...">
        </div>
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
                        wire:click="confirmCategoriesRemovalmultiple()">
                        Delete
                    </button>
                    <button class="dropdown-item submit" style="width: 150px" type="button"
                        wire:click="exportSelected()">
                        Export
                    </button>
                </div>
            @endif
        </div>
        <a href="{{ route('add_product') }}" class="item__header-btn">
            <svg>
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
        </a>
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
                value="Confirm" id="confirmLoad">
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
            <input wire:click.prevent="deleteRecords()" class="modal-content-btn submit" type="button" value="Confirm"
                id="confirmLoad">
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
                        @if ($orderBy === 'id' && $orderAsc === '1') data-symbol="up"
            @else
                data-symbol="down" @endif
                        wire:click="sortBy('id')">ID
                    </th>
                @endif
                @if ($this->showColumn('Name'))
                    <th class="cursor-p"
                        @if ($orderBy === 'name' && $orderAsc === '1') data-symbol="up"
        @else
            data-symbol="down" @endif
                        wire:click="sortBy('name')">Name
                    </th>
                @endif
                @if ($this->showColumn('Short Description'))
                    <th class="cursor-p"
                        @if ($orderBy === 'short_description' && $orderAsc === '1') data-symbol="up"
    @else
        data-symbol="down" @endif
                        wire:click="sortBy('short_description')">Short Description
                    </th>
                @endif
                @if ($this->showColumn('Created At'))
                    <th class="cursor-p"
                        @if ($orderBy === 'created_at' && $orderAsc === '1') data-symbol="up"
            @else
                data-symbol="down" @endif
                        wire:click="sortBy('created_at')">Created At
                    </th>
                @endif

                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr @if ($loop->last) id="last_record" @endif
                    class="@if ($this->isChecked($product->id)) th_checked @endif">
                    <td data-title="Check"><input type="checkbox" value="{{ $product->id }}" wire:model="checked">
                    </td>

                    @if ($this->showColumn('Id'))
                        <td data-title="ID">{{ $product->id }}</td>
                    @endif
                    @if ($this->showColumn('Name'))
                        <td data-title="Name"><a href="/show_product/{{ $product->id }}'">{{ $product->name }}</a>
                        </td>
                    @endif
                    @if ($this->showColumn('Short Description'))
                        <td data-title="Short Description">{{ $product->short_description }}</td>
                    @endif
                    @if ($this->showColumn('Created At'))
                        <td data-title="Created At">{{ $product->created_at }}</td>
                    @endif

                    <td data-title="Action">
                        <button class="delete" wire:click.prevent="confirmProductRemoval({{ $product->id }})">
                            <svg>
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                        </button>
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
