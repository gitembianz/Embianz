<div>
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

    <div class="accordion">
        <div class="accordion__btn-flex">
            <button class="accordion__btn"
                wire:click.prevent="@if ($showrelatedprod === false) $set('showrelatedprod', true) @else $set('showrelatedprod', false) @endif">
                {{ __('Products ') }}({{ count($relatedproducts) }})
            </button>
            <button class="accordion__upload" wire:click="toggleTable">
                <svg>
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
        </div>

        @if ($showrelatedprod)
            <div class="accordion__content">
                <div class="item">
                    <div class="item">
                        {{-- html for adding products --}}
                        @if ($showTable === false)
                        @else
                            <button class="item__upload-btn"
                                wire:click="cancel">{{ __('Cancel and Save all') }}</button>

                            <div class="item__form form-table"
                                @if ($checkedadd) style="grid-template-columns: 40% 1fr 1fr 1fr" @endif>

                                <div class="item__form-input">
                                    <input wire:model.debounce.200ms="searchadd" type="text" required>
                                    <span>Search product...</span>
                                </div>
                                <div class="item__form-input">
                                    <select id="perPage" wire:model="perPageadd">
                                        <option>10</option>
                                        <option>25</option>
                                        <option>50</option>
                                        <option>100</option>
                                    </select>
                                    <span>Per Page :</span>
                                </div>

                                <div class="dropdown">
                                    <button
                                        wire:click.prevent="@if ($coladd === false) $set('coladd', true) @else $set('coladd', false) @endif"
                                        class="dropdown-button">Columns</button>
                                    @if ($coladd)
                                        <div class="dropdown-list" style="display: flex;">
                                            @foreach ($columnsadd as $column)
                                                <div class="dropdown-item">
                                                    <input type="checkbox" wire:model="selectedColumnsadd"
                                                        value="{{ $column }}"
                                                        {{ in_array($column, $selectedColumnsadd) ? 'checked' : '' }}>
                                                    <label>{{ $column }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>

                                <div class="dropdown none"
                                    @if ($checkedadd) style="display: unset; z-index: 5;" @endif>
                                    <button class="dropdown-button none"
                                        wire:click.prevent="@if ($alladd === false) $set('alladd', true) @else $set('alladd', false) @endif"
                                        @if ($checkedadd) style="display: flex" @endif>With
                                        Checked({{ count($checkedadd) }})</button>
                                    @if ($checkedadd)
                                        @if ($alladd)
                                            <div class="dropdown-list" style="display: flex;">
                                                <button class="dropdown-item submit" type="button"
                                                    wire:click.prevent="confirmProductsLinkmultiple()">
                                                    Add Multiple
                                                </button>

                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            {{-- modals start --}}
                            <div class="modal" id="confirmationmodallink">
                                <div class="modal-content">
                                    <h1 class="modal-content-title">
                                        {{ __('Are you sure to relate this product?') }}
                                    </h1>
                                    <input wire:click.prevent="linkSingleRecord()" class="modal-content-btn submit"
                                        type="button" value="Confirm" id="confirmLoad">
                                    <input class="modal-content-btn delete" type="button"
                                        onclick="document.getElementById('confirmationmodallink').style.display='none'"
                                        value="Cancel">

                                    <span class="modal-content-btn delete"
                                        onclick="document.getElementById('confirmationmodallink').style.display='none'">
                                        <svg>
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="modal" id="confirmationmodallinkmultiple">
                                <div class="modal-content">
                                    <h1 class="modal-content-title">
                                        {{ __('Are you sure to link those product?') }}
                                    </h1>
                                    <input wire:click.prevent="linkRecords()" class="modal-content-btn submit"
                                        type="button" value="Confirm" id="confirmLoad">
                                    <input class="modal-content-btn delete" type="button"
                                        onclick="document.getElementById('confirmationmodallinkmultiple').style.display='none'"
                                        value="Cancel">

                                    <span class="modal-content-btn delete"
                                        onclick="document.getElementById('confirmationmodallinkmultiple').style.display='none'">

                                        <svg>
                                            <line x1="18" y1="6" x2="6" y2="18">
                                            </line>
                                            <line x1="6" y1="6" x2="18" y2="18">
                                            </line>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            {{-- modals end --}}

                            @if ($selectPageadd)
                                <div class="pt-2 talign-c">

                                    @if ($selectAlladd)
                                        <div>
                                            You have selected all <strong>{{ count($checkedadd) }}</strong> items.
                                        </div>
                                    @else
                                        <div>
                                            You have selected <strong>{{ count($checkedadd) }}</strong> items, Do you
                                            want to Select All?
                                            <a class="ml-2" wire:click.prevent="selectAlladd">Select All</a>
                                        </div>
                                    @endif

                                </div>
                            @endif



                            {{-- Livewire Table --}}
                            <table class="livewire-table">


                                <thead>
                                    <tr>
                                        <th><input type="checkbox" wire:model="selectPageadd"></th>

                                        @if ($this->showColumnadd('Id'))
                                            <th class="cursor-p"
                                                @if ($orderByadd === 'id' && $orderAscadd === '1') data-symbol="up"
                            @else
                                data-symbol="down" @endif
                                                wire:click="sortByadd('id')">ID
                                            </th>
                                        @endif
                                        @if ($this->showColumnadd('Name'))
                                            <th class="cursor-p"
                                                @if ($orderByadd === 'name' && $orderAscadd === '1') data-symbol="up"
                        @else
                            data-symbol="down" @endif
                                                wire:click="sortByadd('name')">Name
                                            </th>
                                        @endif
                                        @if ($this->showColumnadd('Short Description'))
                                            <th class="cursor-p"
                                                @if ($orderByadd === 'short_description' && $orderAscadd === '1') data-symbol="up"
                    @else
                        data-symbol="down" @endif
                                                wire:click="sortByadd('short_description')">Short Description
                                            </th>
                                        @endif
                                        @if ($this->showColumnadd('Created At'))
                                            <th class="cursor-p"
                                                @if ($orderByadd === 'created_at' && $orderAscadd === '1') data-symbol="up"
                            @else
                                data-symbol="down" @endif
                                                wire:click="sortByadd('created_at')">Created At
                                            </th>
                                        @endif


                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prodds as $product)
                                        <tr class="@if ($this->isCheckedadd($product->id)) th_checked @endif">
                                            <td data-title="Check"><input type="checkbox"
                                                    value="{{ $product->id }}" wire:model="checkedadd">
                                            </td>

                                            @if ($this->showColumnadd('Id'))
                                                <td data-title="ID">{{ $product->id }}</td>
                                            @endif
                                            @if ($this->showColumnadd('Name'))
                                                <td data-title="Name"><a
                                                        href="/show_product/{{ $product->id }}'">{{ $product->name }}</a>
                                                </td>
                                            @endif
                                            @if ($this->showColumnadd('Short Description'))
                                                <td data-title="Short Description">{{ $product->short_description }}
                                                </td>
                                            @endif
                                            @if ($this->showColumnadd('Created At'))
                                                <td data-title="Created At">{{ $product->created_at }}</td>
                                            @endif


                                            <td data-title="Action">
                                                <button class="edit"
                                                    wire:click.prevent="confirmProductlink({{ $product->id }})">
                                                    <svg>
                                                        <path
                                                            d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71">
                                                        </path>
                                                        <path
                                                            d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div>{{ $prodds->links('') }} </div>
                        @endif
                        {{-- end html for adding products --}}
                    </div>
                    <div class="item">
                        @if ($relatedproducts && count($relatedproducts) > 0)
                            <div class="item__form form-table"
                                @if ($checked) style="grid-template-columns: 40% 1fr 1fr 1fr" @endif>

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

                                <div class="dropdown" style="z-index: 5;">
                                    <button
                                        wire:click.prevent="@if ($col === false) $set('col', true) @else $set('col', false) @endif"
                                        class="dropdown-button">Columns</button>
                                    @if ($col)

                                        <div class="dropdown-list" style="display: flex;">
                                            @foreach ($columns as $column)
                                                <div class="dropdown-item">
                                                    <input type="checkbox" wire:model="selectedColumns"
                                                        value="{{ $column }}"
                                                        {{ in_array($column, $selectedColumns) ? 'checked' : '' }}>
                                                    <label>{{ $column }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="dropdown none"
                                    @if ($checked) style="display: unset; z-index: 5;" @endif>
                                    <button
                                        wire:click.prevent="@if ($all === false) $set('all', true); $set('col', false) @else $set('all', false) @endif"
                                        class="dropdown-button none"
                                        @if ($checked) style="display: flex" @endif>With
                                        Checked({{ count($checked) }})</button>
                                    @if ($checked)
                                        @if ($all)
                                            <div class="dropdown-list" style="display: flex;">
                                                <button class="dropdown-item delete" type="button"
                                                    wire:click="confirmProductsRemovalmultiple()">
                                                    Delete
                                                </button>
                                                <button class="dropdown-item submit" type="button"
                                                    wire:click="exportSelected()">
                                                    Export
                                                </button>
                                            </div>
                                        @endif
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
                                            You have selected <strong>{{ count($checked) }}</strong> items, Do you want
                                            to Select All?
                                            <a class="ml-2" wire:click.prevent="selectAll">Select All</a>
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
                                    <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit"
                                        type="button" value="Confirm" id="confirmLoad">
                                    <input class="modal-content-btn delete" type="button"
                                        onclick="document.getElementById('confirmationmodal').style.display='none'"
                                        value="Cancel">

                                    <span class="modal-content-btn delete"
                                        onclick="document.getElementById('confirmationmodal').style.display='none'">
                                        <svg>
                                            <line x1="18" y1="6" x2="6" y2="18">
                                            </line>
                                            <line x1="6" y1="6" x2="18" y2="18">
                                            </line>
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
                                    <input wire:click.prevent="deleteRecords()" class="modal-content-btn submit"
                                        type="button" value="Confirm" id="confirmLoad">
                                    <input class="modal-content-btn delete" type="button"
                                        onclick="document.getElementById('confirmationmodalmultiple').style.display='none'"
                                        value="Cancel">

                                    <span class="modal-content-btn delete"
                                        onclick="document.getElementById('confirmationmodalmultiple').style.display='none'">

                                        <svg>
                                            <line x1="18" y1="6" x2="6" y2="18">
                                            </line>
                                            <line x1="6" y1="6" x2="18" y2="18">
                                            </line>
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
                                            <th>Name
                                            </th>
                                        @endif
                                        @if ($this->showColumn('Short Description'))
                                            <th>Short Description
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
                                    @foreach ($relatedproducts as $product)
                                        <tr class="@if ($this->isChecked($product->id)) th_checked @endif">
                                            <td data-title="Check"><input type="checkbox"
                                                    value="{{ $product->id }}" wire:model="checked">
                                            </td>

                                            @if ($this->showColumn('Id'))
                                                <td data-title="ID">{{ $product->product->id }}</td>
                                            @endif
                                            @if ($this->showColumn('Name'))
                                                <td data-title="Name"><a
                                                        href="/show_product/{{ $product->product->id }}'">{{ $product->product->name }}</a>
                                                </td>
                                            @endif
                                            @if ($this->showColumn('Short Description'))
                                                <td data-title="Short Description">
                                                    {{ $product->product->short_description }}</td>
                                            @endif
                                            @if ($this->showColumn('Created At'))
                                                <td data-title="Created At">{{ $product->product->created_at }}</td>
                                            @endif


                                            <td data-title="Action">
                                                <button class="delete"
                                                    wire:click.prevent="confirmProductRemoval({{ $product->id }})">
                                                    <svg>
                                                        <circle cx="12" cy="12" r="10">
                                                        </circle>
                                                        <line x1="15" y1="9" x2="9"
                                                            y2="15"></line>
                                                        <line x1="9" y1="9" x2="15"
                                                            y2="15"></line>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div>{{ $relatedproducts->links() }} </div>
                        @else
                            <p>no products related</p>
                        @endif

                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
