<div class="item">
    @if (session()->has('message'))
        <div class="alert__session" id="alertevent">
            <span class="alert__session-text">{!! session('message') !!}</span>
            <button class="alert__session-btn" type="button"
                onclick="document.getElementById('alertevent').style.display='none'" data-bs-dismiss="alert"
                aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                    stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    @endif

    <div class="item__form form-table"
        @if ($checked) style="grid-template-columns: 5fr 1fr 1fr 1fr" @endif>

        <div class="item__form-input">
            <input wire:model.debounce.200ms="search" type="text" required>
            <span>Search product...</span>
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

        <div class="dropdown" onclick="OpenDropdown()()">
            <span class="dropdown-name">Columns</span>

            <div class="dropdown-content">
                @foreach ($columns as $column)
                    <div class="display-f jus-fs wid-10 align-center">
                        <input class="dropdown-item mr-1" type="checkbox" wire:model="selectedColumns"
                            value="{{ $column }}" {{ in_array($column, $selectedColumns) ? 'checked' : '' }}>
                        <label>{{ $column }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($checked)
            <div class="dropdown" onclick="OpenDropdown()()">
                <span class="dropdown-name" onclick="dropdown()">With Checked ({{ count($checked) }})</span>

                <div class="dropdown-content">
                    <button class="dropdown-item delete" type="button" wire:click="confirmProductsRemovalmultiple()">
                        Delete
                    </button>
                    <button class="dropdown-item submit" type="button"
                        wire:click="exportSelected()">
                        Export
                    </button>
                </div>
            </div>
        @endif
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
                {{ __('Are you sure to delete this product?') }}
            </h1>
            <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button"
                value="Confirm">
            <input class="modal-content-btn delete" type="button"
                onclick="document.getElementById('confirmationmodal').style.display='none'" value="Cancel">

            <span class="modal-content-btn delete"
                onclick="document.getElementById('confirmationmodal').style.display='none'">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                    stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                {{ __('Are you sure to delete those product?') }}
            </h1>
            <input wire:click.prevent="deleteRecords()" class="modal-content-btn submit" type="button" value="Confirm">
            <input class="modal-content-btn delete" type="button"
                onclick="document.getElementById('confirmationmodalmultiple').style.display='none'" value="Cancel">

            <span class="modal-content-btn delete"
                onclick="document.getElementById('confirmationmodalmultiple').style.display='none'">

                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24"
                    fill="none" stroke="#BBFCDE" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">

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
                <tr class="@if ($this->isChecked($product->id)) th_checked @endif">
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                viewBox="0 0 24 24" fill="none" stroke="#BBFCDE" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>{{ $products->links('pagination-links') }} </div>
</div>
