<div>
    <x-alert />
    <div wire:loading.delay>
        <div class="modal" style="display:flex; z-index: 99999;">
            <div class="loader"></div>
        </div>
    </div>
    <div class="accordion">
        <div class="accordion__btn-flex">
            <button class="accordion__btn"
                wire:click.prevent="@if ($showrelatedcat === false) $set('showrelatedcat', true) @else $set('showrelatedcat', false) @endif">
                {{ __('Categories ') }}({{ count($relatedcats) }})
            </button>
            <button class="accordion__upload" wire:click="toggleTable">
                <svg>
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg> </button>
        </div>

        @if ($showrelatedcat)
            <div class="accordion__content">
                <div class="item">
                    {{-- html for adding products --}}
                    @if ($showTable === false)
                    @else
                        <div class="modal" id="modalelements" style="display: block">
                            <div class="modal-content"
                                style="margin: 0 auto; width:80%; top: 50%; transform: translateY(-50%); display: flex; height: 98vh; overflow-y: auto; align-items: flex-start">
                                <div class="item" style="width: 100%">
                                    <p id="top"></p>
                                    <div class="item__form form-table"
                                        @if ($checkedadd) style="grid-template-columns: 40% 1fr 1fr" @else style="grid-template-columns: 3fr 1fr" @endif>

                                        <div class="item__form-input">
                                            <input wire:model.debounce.200ms="searchadd" type="text"
                                                placeholder="Search here" required>
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
                                        <button
                                            @if ($checkedadd) style="display: unset; z-index: 5; height: 3rem;" @else style="display: none;" @endif
                                            class="dropdown-button" wire:click.prevent="confirmItemsLinkmultiple()"
                                            @if ($checkedadd) style="display: flex" @endif> Add
                                            ({{ count($checkedadd) }}) records</button>
                                    </div>

                                    {{-- modals start --}}
                                    <div class="modal" id="confirmationmodallink">
                                        <div class="modal-content">
                                            <h1 class="modal-content-title">
                                                {{ __('Are you sure to relate this record?') }}
                                            </h1>
                                            <input wire:click.prevent="linkSingleRecord()"
                                                class="modal-content-btn submit" type="button" value="Confirm"
                                                id="confirmLoad">
                                            <input class="modal-content-btn delete" type="button"
                                                onclick="document.getElementById('confirmationmodallink').style.display='none'"
                                                value="Cancel">

                                            <span class="modal-content-btn delete"
                                                onclick="document.getElementById('confirmationmodallink').style.display='none'">
                                                <svg>
                                                    <line x1="18" y1="6" x2="6" y2="18">
                                                    </line>
                                                    <line x1="6" y1="6" x2="18" y2="18">
                                                    </line>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="modal" id="confirmationmodallinkmultiple">
                                        <div class="modal-content">
                                            <h1 class="modal-content-title">
                                                {{ __('Are you sure to link those records?') }}
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
                                                    You have selected all <strong>{{ count($checkedadd) }}</strong>
                                                    items.
                                                </div>
                                            @else
                                                <div>
                                                    You have selected <strong>{{ count($checkedadd) }}</strong> items,
                                                    Do
                                                    you
                                                    want to Select All?
                                                    <a class="ml-2" wire:click.prevent="selectAlladd">Select All</a>
                                                </div>
                                            @endif

                                        </div>
                                    @endif

                                    {{-- Livewire Table --}}
                                    <table class="livewire-table livewire-table--modal">

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
                                        <tbody style="max-height: 100px !important;">
                                            @foreach ($cats as $cat)
                                                <tr class="@if ($this->isCheckedadd($cat->id)) th_checked @endif"
                                                    @if ($loop->last) id="last_record" @endif>
                                                    <td data-title="Check"><input type="checkbox"
                                                            value="{{ $cat->id }}" wire:model="checkedadd">
                                                    </td>

                                                    @if ($this->showColumnadd('Id'))
                                                        <td data-title="ID">{{ $cat->id }}</td>
                                                    @endif
                                                    @if ($this->showColumnadd('Name'))
                                                        <td data-title="Name"><a
                                                                href="/show_category/{{ $cat->id }}'">{{ $cat->name }}</a>
                                                        </td>
                                                    @endif
                                                    @if ($this->showColumnadd('Short Description'))
                                                        <td data-title="Short Description">
                                                            {{ $cat->short_description }}
                                                        </td>
                                                    @endif
                                                    @if ($this->showColumnadd('Created At'))
                                                        <td data-title="Created At">{{ $cat->created_at }}</td>
                                                    @endif

                                                    <td data-title="Action">
                                                        <button class="edit"
                                                            wire:click.prevent="confirmItemlink({{ $cat->id }})">
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

                                </div>
                            </div>
                            <span class="top-up-modal delete" style="right: 5%" wire:click="cancel">

                                <svg>
                                    <line x1="18" y1="6" x2="6" y2="18">
                                    </line>
                                    <line x1="6" y1="6" x2="18" y2="18">
                                    </line>
                                </svg>
                            </span>
                            <a href="#top" class="top-up-modal" id="topUp">
                                <svg>
                                    <polyline points="18 15 12 9 6 15"></polyline>
                                </svg>
                            </a>
                        </div>
                        {{-- script for lazy load data --}}
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
                    @endif
                    {{-- end script --}}
                    {{-- end html for adding products --}}
                </div>
                <div class="item">
                    @if ($relatedcats && count($relatedcats) > 0)
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
                                                wire:click="confirmItemsRemovalmultiple()">
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
                                    {{ __('Are you sure to delete this record?') }}
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
                                    {{ __('Are you sure to delete those records?') }}
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
                                @foreach ($relatedcats as $categori)
                                    <tr class="@if ($this->isChecked($categori->id)) th_checked @endif">
                                        <td data-title="Check"><input type="checkbox" value="{{ $categori->id }}"
                                                wire:model="checked">
                                        </td>

                                        @if ($this->showColumn('Id'))
                                            <td data-title="ID">{{ $categori->category->id }}</td>
                                        @endif
                                        @if ($this->showColumn('Name'))
                                            <td data-title="Name"><a
                                                    href="/show_category/{{ $categori->category->id }}'">{{ $categori->category->name }}</a>
                                            </td>
                                        @endif
                                        @if ($this->showColumn('Short Description'))
                                            <td data-title="Short Description">
                                                {{ $categori->category->short_description }}</td>
                                        @endif
                                        @if ($this->showColumn('Created At'))
                                            <td data-title="Created At">{{ $categori->category->created_at }}</td>
                                        @endif

                                        <td data-title="Action">
                                            <button class="delete"
                                                wire:click.prevent="confirmItemRemoval({{ $categori->id }})">
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
                        <div>{{ $relatedcats->links('pagination-links') }} </div>
                    @else
                        <p>no products related</p>
                    @endif

                </div>
            </div>
        @endif
    </div>

</div>
