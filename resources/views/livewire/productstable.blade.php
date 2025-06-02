<section class="content">
    {{-- X-Components --}}
    <x-alert />

    {{-- Modal delete record --}}
    <aside>
        <div class="background background--center @if ($relation) active @endif"></div>
        <div class="aside aside--confirm @if ($relation) active @endif" style="min-height: 150px">
            <span>
                This product is involved in an order/cart/supplier, delete this record?
            </span>
            <button class="button button--primary button--long" wire:click.prevent="forcedeleteRecord()">
                <span>Yes</span>
            </button>
            <button class="button button--danger button--long" wire:click.prevent="cancelItemRemoval()">
                <span>No</span>
            </button>
        </div>
    </aside>

    {{-- Modal add new listview --}}
    <aside>
        <div class="background background--center @if ($addlistview) active @endif"></div>
        <div class="aside aside--confirm @if ($addlistview) active @endif"
            style="min-height: 225px !important;">
            <div class="tabs__content details__view active" style="max-height: 100%;">

                <span class="details__long"
                    style="text-align: center; font-size: 1.2em; font-weight: bold; color: #fff !important;">
                    New Listview
                </span>
                @foreach ($errors->all() as $error)
                    <span class="details__long"
                        style="text-align: center; font-size: 1.2em; font-weight: bold; color: red !important;">{{ $error }}</span>
                @endforeach

                <div class="input__tabs details__long">
                    <input type="text" wire:model.defer="listview.name">
                    <label>Listview name</label>
                </div>


                <button class="button button--primary button--long" wire:click.prevent="add_listview()">
                    <span>Save</span>
                </button>
                <button class="button button--danger button--long" style="margin-bottom: 10px !important"
                    wire:click.prevent="$set('addlistview', false)">
                    <span>Cancel</span>
                </button>

            </div>
        </div>
    </aside>

    {{-- Modal edit listview --}}
    <aside>
        <div class="background background--center @if ($editlistview) active @endif"></div>
        <div class="aside aside--confirm @if ($editlistview) active @endif" style="min-height: 450px;">
            <div class="tabs__content details__view active" style="max-height: 100%;">
                @foreach ($errors->all() as $error)
                    <span class="details__long"
                        style="text-align: center; font-size: 1.2em; font-weight: bold; color: red !important;">{{ $error }}</span>
                @endforeach


                <button
                    class="button @if ($edit) button--secondary @else button--primary @endif button--long"
                    wire:click.prevent="toggle('edit')">
                    <span>Edit listview</span>
                </button>
                <button
                    class="button @if ($filter) button--secondary @else button--primary @endif button--long"
                    style="margin-bottom: 10px !important" wire:click.prevent="toggle('filter')">
                    <span>Add filter</span>
                </button>


                @if ($edit)
                    <div class="input__tabs details__long">
                        <input type="text" wire:model.defer="listview.name">
                        <label>Listview name</label>
                    </div>
                    <div class="details__long">

                        <button style="max-width: none!important; width:100%!important"
                            class="button button--danger button--long" wire:click.prevent="delete_listview()">
                            <span>Delete listview</span>
                        </button>
                    </div>
                    <div class="details__long"
                        style="display: flex; justify-content: center; gap: 10px; margin-top: 5px;">
                        <div style="flex: 1;">
                            <label style="font-weight: bold; color: white;">Columns</label>
                            <select wire:model="selectedAvailable" size="8" style="width: 100%;">
                                @foreach ($availableFields as $field)
                                    <option value="{{ $field }}">{{ $field }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="display: flex; flex-direction: column; justify-content: center; gap: 5px;">
                            <button type="button" wire:click="moveToVisible" class="button">→</button>
                            <button type="button" wire:click="moveToAvailable" class="button">←</button>
                        </div>

                        <div style="flex: 1;">
                            <label style="font-weight: bold; color: white;">Visible Fields</label>
                            <select wire:model="selectedVisible" size="8" style="width: 100%;">
                                @foreach ($listview['columns'] as $field)
                                    <option value="{{ $field }}">{{ $field }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; gap: 10px;">
                            <button type="button" wire:click="moveVisibleFieldUp" class="button">↑</button>
                            <button type="button" wire:click="moveVisibleFieldDown" class="button">↓</button>
                        </div>
                    </div>
                    <button class="button button--primary button--long" wire:click.prevent="save_listview()">
                        <span>Save</span>
                    </button>
                    <button class="button button--danger button--long" style="margin-bottom: 10px !important"
                        wire:click.prevent="$set('editlistview', false)">
                        <span>Cancel</span>
                    </button>
                @else
                    <div class="input__tabs details__long">
                        <select wire:model.defer="selectedVisible">
                            @foreach ($listview['columns'] as $field)
                                <option value="{{ $field }}">{{ $field }}</option>
                            @endforeach
                        </select>
                        <label> Column</label>
                    </div>
                    <div class="input__tabs details__long">
                        <select wire:model.defer="operator">
                            <option value="=">equal</option>
                            <option value="like">contains</option>
                            <option value=">">greater than</option>
                            <option value="<">less than</option>
                        </select>
                        <label> Operator</label>
                    </div>
                    <div class="input__tabs details__long">
                        <input type="text" wire:model.defer="value">
                        <label>Value</label>
                    </div>
                    <div class="details__long" style="margin-bottom: 5px;">

                        <button style="max-width: none!important; width:100%!important"
                            class="button button--secondary button--long" wire:click.prevent="save_filter()">
                            <span>Save filter</span>
                        </button>
                    </div>
                @endif


            </div>
        </div>
    </aside>


    <aside>
        <div class="background background--center @if ($addlistview) active @endif"></div>
        <div class="aside aside--confirm @if ($addlistview) active @endif"
            style="min-height: 225px !important;">
            <div class="tabs__content details__view active" style="max-height: 100%;">

                <span class="details__long"
                    style="text-align: center; font-size: 1.2em; font-weight: bold; color: #fff !important;">
                    New Listview
                </span>
                @foreach ($errors->all() as $error)
                    <span class="details__long"
                        style="text-align: center; font-size: 1.2em; font-weight: bold; color: red !important;">{{ $error }}</span>
                @endforeach

                <div class="input__tabs details__long">
                    <input type="text" wire:model.defer="listview.name">
                    <label>Listview name</label>
                </div>


                <button class="button button--primary button--long" wire:click.prevent="add_listview()">
                    <span>Save</span>
                </button>
                <button class="button button--danger button--long" style="margin-bottom: 10px !important"
                    wire:click.prevent="$set('addlistview', false)">
                    <span>Cancel</span>
                </button>

            </div>
        </div>
    </aside>

    {{-- Delete Record OR Records --}}
    <aside>
        <div class="background background--center @if ($single || $multiple) active @endif"></div>
        <div class="aside aside--confirm @if ($single || $multiple) active @endif">
            <span>
                @if ($single)
                    Are you sure to delete this record?
                @else
                    Are you sure to delete those records?
                @endif
            </span>
            @if ($single)
                <button class="button button--primary button--long" wire:click="deleteSingleRecord">
                    <span>Delete</span>
                </button>
            @else
                <button class="button button--primary button--long" wire:click="deleteRecords()">
                    <span>Delete</span>
                </button>
            @endif
            <button class="button button--danger button--long" wire:click="cancel_delete()">
                <span>Cancel</span>
            </button>
        </div>
    </aside>

    {{-- CSV Upload --}}
    <aside>
        <div class="background background--center @if ($uploadcsv == true) active @endif"></div>
        <div class="aside aside--confirm @if ($uploadcsv == true) active @endif">
            <span>
                Please select the CSV file
            </span>
            <input style="display: none;" id="CSVMedia" wire:model="csvFile" type="file" accept="csv/*">
            <label class="button button--primary button--long" type="button" for="CSVMedia">
                <span>
                    Select CSV
                </span>
            </label>
            <button class="button button--danger button--long" wire:click.prevent="$set('uploadcsv', false)">
                <span>
                    Close
                </span>
            </button>
        </div>
    </aside>
    {{-- Asides --}}
    <aside>
        <div class="background background--right" wire:ignore id="sort__backdrop"></div>
        <div class="aside aside--right" wire:ignore id="sort">
            <div class="aside--controls">
                <button class="button button--flexed button--primary" id="sort__close">
                    <svg>
                        <polyline points="4 14 10 14 10 20"></polyline>
                        <polyline points="20 10 14 10 14 4"></polyline>
                        <line x1="14" y1="10" x2="21" y2="3"></line>
                        <line x1="3" y1="21" x2="10" y2="14"></line>
                    </svg>
                </button>
                <h3>Sorting Data</h3>
            </div>
            <span class="aside--line"></span>
            @foreach ($columns as $column)
                <button
                    class="button button--primary button--long button--flexed button--arrow @if ($orderBy === $column && $orderAsc === '1') active @endif"
                    wire:click="sortBy('{{ $column }}')">
                    <svg>
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                    {{ $column }}
                </button>
            @endforeach
        </div>
    </aside>
    <aside>
        <div class="background background--right" wire:ignore id="visi__backdrop"></div>
        <div class="aside aside--right" wire:ignore id="visi">
            <div class="aside--controls">
                <button class="button button--flexed button--primary" id="visi__close">
                    <svg>
                        <polyline points="4 14 10 14 10 20"></polyline>
                        <polyline points="20 10 14 10 14 4"></polyline>
                        <line x1="14" y1="10" x2="21" y2="3"></line>
                        <line x1="3" y1="21" x2="10" y2="14"></line>
                    </svg>
                </button>
                <h3>Visibility Data</h3>
            </div>
            <span class="aside--line"></span>
            @foreach ($columns as $column)
                <label class="switch switch--primary" style="margin: 0.25rem 0">
                    <input type="checkbox" wire:ignore wire:model="selectedColumns" value="{{ $column }}"
                        {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
                    <span>{{ $column }}</span>
                </label>
            @endforeach
        </div>
    </aside>


    {{-- Navigation --}}
    <h1 class="table--name">{{ __('Products') }} ({{ $products->total() }})</h1>
    <nav class="nav--controls">
        @if ($activelistview)

            <div class="dropdown dropdown--right">
                {{-- Dropdown Button --}}

                <button class="button button--primary button--centered button--long" tooltip="Active listview"
                    tooltip-top>
                    <span>{{ $activelistview->name }}</span>
                </button>
                {{-- Dropdown Content --}}
                <div class="dropdown__content custom-dropdown-content"
                    style="gap: 5px; display: grid; left: 0; right: 0; max-height: 200px; overflow-y: auto;">

                    @foreach ($listviews as $listview)
                        <button class="button button--primary button--long"
                            wire:click="setActiveListview({{ $listview->id }})">
                            {{ $listview->name }}
                        </button>
                    @endforeach
                    @if (count($listviews) < 1)
                        <span class="details__long"
                            style="text-align: center; font-size: 1.2em; font-weight: bold; color: red !important;">No
                            listviews found</span>
                    @endif


                </div>
        @endif
        <style>
            .custom-dropdown-content::before {
                content: "";
                position: absolute;
                left: 10px;
            }
        </style>
        </div>
        <button class="button button--primary button--centered" tooltip="Edit listview" tooltip-top
            wire:click="$set('editlistview', true)">
            <svg>
                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                </path>
            </svg>
        </button>
        {{-- Search Input --}}
        <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
        <div class="dropdown dropdown--right" @if (!$checked) style="display: none;" @endif>
            {{-- Dropdown Button --}}
            <button class="button button--primary button--centered button--long" tooltip="Actions with checked"
                tooltip-top>
                <span>With Checked({{ count($checked) }})</span>
            </button>
            {{-- Dropdown Content --}}
            <div class="dropdown__content">
                <button class="button button--primary button--long" wire:click="confirmItemsRemoval()">
                    Delete
                </button>
            </div>
        </div>

        {{-- Visible Dropdown --}}
        <div class="dropdown dropdown--right display--desktop" wire:ignore>

            {{-- Dropdown Content --}}
            <div class="dropdown__content">
                <div class="dropdown__container">
                    @foreach ($columns as $column)
                        <label class="switch switch--primary inline">
                            <input type="checkbox" wire:ignore wire:model="selectedColumns"
                                value="{{ $column }}"
                                {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
                            <span>{{ $column }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Optional Dropdown --}}
        <div class="dropdown dropdown--right">
            {{-- Dropdown Button --}}
            <button class="button button--primary button--centered" tooltip="Table actions" tooltip-left>
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                </svg>
            </button>
            {{-- Dropdown Content --}}
            <div class="dropdown__content">
                <div class="dropdown__container">
                    <button class="button button--primary button--fill button--flexed" wire:click="$refresh">
                        <svg>
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 4.55a8 8 0 0 0 -6 14.9m0 -4.45v5h-5" />
                            <path d="M18.37 7.16l0 .01" />
                            <path d="M13 19.94l0 .01" />
                            <path d="M16.84 18.37l0 .01" />
                            <path d="M19.37 15.1l0 .01" />
                            <path d="M19.94 11l0 .01" />
                        </svg>
                        <span>Refresh table</span>
                    </button>
                    <button class="button button--primary button--fill button--flexed"
                        wire:click="$set('addlistview', true)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-clipboard">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                        <span>Add listview</span>
                    </button>
                    <a class="button button--primary button--fill button--flexed" href="{{ route('create_feed') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-file-text">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <span>Generate feed</span>
                    </a>
                    <button class="button button--primary button--fill button--flexed"
                        wire:click.prevent="$set('uploadcsv', true)">
                        <svg>
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        <span>Media from CSV</span>
                    </button>
                    <a class="button button--primary button--fill button--flexed" href="{{ route('add_product') }}">
                        <svg>
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                        <span>Add Product</span>
                    </a>
                    <button class="button button--primary button--fill button--flexed"
                        wire:click="ProductshuffledIds">
                        <svg>
                            <polyline points="16 3 21 3 21 8"></polyline>
                            <line x1="4" y1="20" x2="21" y2="3"></line>
                            <polyline points="21 16 21 21 16 21"></polyline>
                            <line x1="15" y1="15" x2="21" y2="21"></line>
                            <line x1="4" y1="4" x2="9" y2="9"></line>
                        </svg>
                        <span>Schuffle innerids</span>
                    </button>
                    <button class="button button--primary button--fill button--flexed" wire:click="Relatedshuffleseq">
                        <svg>
                            <polyline points="17 1 21 5 17 9"></polyline>
                            <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                            <polyline points="7 23 3 19 7 15"></polyline>
                            <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                        </svg>
                        <span>Schuffle sequences</span>
                    </button>
                    <button class="button button--primary button--fill button--flexed" id="sort__open">
                        <svg>
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 10v-5c0 -1.38 .62 -2 2 -2s2 .62 2 2v5m0 -3h-4" />
                            <path d="M19 21h-4l4 -7h-4" />
                            <path d="M4 15l3 3l3 -3" />
                            <path d="M7 6v12" />
                        </svg>
                        <span>Sorting data</span>
                    </button>
                    <button class="button button--primary button--fill button--flexed" id="visi__open">
                        <svg>
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path
                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                        </svg>
                        <span>Visible</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>


    {{-- Select All --}}
    @if ($selectPage && $selectAll)
        <button class="button button--fill button--primary" style="margin-top: 10px;">
            You selected {{ count($checked) }} items.
        </button>
    @elseif($selectPage)
        <button class="button button--fill button--secondary" style="margin-top: 10px;" wire:click="selectAll">
            You selected {{ count($checked) }} items, select all?
        </button>
    @endif


    {{-- Table --}}
    <div class="table" @if ($selectPage || $selectAll) style="height: calc(100% - 150px);" @endif>
        <table class="expandable-table">
            <thead>
                <tr>
                    <th style="border-right: none; border-left: none;">
                        <div class="checkbox--primary">
                            <input type="checkbox" id="selectPage7" wire:model="selectPage" />
                            <label for="selectPage7"></label>
                        </div>
                    </th>
                    @foreach ($selectedColumns as $index => $column)
                        @if ($this->showColumn($column))
                            <th @if ($index > 2) class="hidden" @endif>
                                <button wire:click="sortBy('{{ $column }}')"
                                    class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
                                    {{ $column }}
                                    <svg>
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                            </th>
                        @endif
                    @endforeach
                    <th style="border-left: none; border-right: none;">
                        <button class="button button--secondary button--sm" style="opacity: 0">
                            <svg>
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path
                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                </path>
                            </svg>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($products->isEmpty())
                    <tr>
                        <td class="table--empty" colspan="{{ count($selectedColumns) + 2 }}">No record found.</td>
                    </tr>
                @else
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($products as $nr => $product)
                        <tr @if ($loop->last) id="last_record" @endif
                            class="expandable-row @if ($this->isChecked($product->id)) active @endif">
                            <td style="border-left: none" data-title="Check">
                                <div class="checkbox--primary">
                                    <input type="checkbox" value="{{ $product->id }}" id="{{ $product->id }}"
                                        wire:model="checked">
                                    <label for="{{ $product->id }}"></label>
                                </div>
                            </td>
                            @foreach ($selectedColumns as $index => $column)
                                <td @if ($index > 2) class="hidden" @endif
                                    data-title="{{ $column }}" wire:click="expandRow({{ $nr }})">
                                    @if ($column === 'name')
                                        <a
                                            href="{{ route('show_product', ['id' => $product->id]) }}">{{ $product->name }}</a>
                                    @elseif ($column === 'parent_id')
                                        @if ($product->$column)
                                            <a
                                                href="{{ route('show_product', ['id' => $product->$column]) }}">{{ $product->parent->name }}</a>
                                        @else
                                            {{ $product->$column }}
                                        @endif
                                    @elseif ($column === 'active' || $column === 'preorder' || $column === 'is_new' || $column === 'low_stock')
                                        @if ($product->$column)
                                            <div class="checkbox--secondary disabled">
                                                <input type="checkbox" id="disabled1" disabled checked>
                                                <label for="disabled1"></label>
                                            </div>
                                        @else
                                            <div class="checkbox--secondary disabled">
                                                <input type="checkbox" id="disabled2" disabled>
                                                <label for="disabled2"></label>
                                            </div>
                                        @endif
                                    @elseif($column === 'interim_quantity')
                                        <span>
                                            {{ $product->quantity + $product->$column }}
                                        </span>
                                    @elseif($column === 'quantity_supplier_ordered')
                                        <span>
                                            {{ $product->quantity_ordered }}
                                        </span>
                                    @elseif($column === 'long_description')
                                        <span class="show-less">
                                            {!! $product->$column !!}
                                        </span>
                                    @elseif($column === 'comments')
                                        <span class="show-less">
                                            {!! $product->$column !!}
                                        </span>
                                    @elseif($column === 'short_description')
                                        <span class="show-less">
                                            {!! $product->$column !!}
                                        </span>
                                    @elseif($column === 'meta_description')
                                        <span class="show-less">
                                            {!! $product->$column !!}
                                        </span>
                                    @elseif($column === 'seo_id')
                                        <span class="show-less">
                                            {!! $product->$column !!}
                                        </span>
                                    @elseif($column === 'seo_title')
                                        <span class="show-less">
                                            {!! $product->$column !!}
                                        </span>
                                    @else
                                        {{ $product->$column }}
                                    @endif
                            @endforeach
                            <td style="border-right: none">
                                <button wire:click.prevent="confirmItemRemoval({{ $product->id }})"
                                    class="button button--secondary button--sm">
                                    <svg>
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                        </path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr class="details-row  @if ($row === $i) active @endif">
                            <td colspan="17">
                                <div class="details">
                                    @foreach ($selectedColumns as $index => $column)
                                        @if ($index >= 3)
                                            <p>
                                                @if ($column === 'active' || $column === 'preorder' || $column === 'is_new' || $column === 'low_stock')
                                                    <bold>{{ $column }}:</bold>
                                                    @if ($product->$column)
                                                        <div class="checkbox--secondary disabled">
                                                            <input type="checkbox" id="disabled3" disabled checked>
                                                            <label for="disabled3"></label>
                                                        </div>
                                                    @else
                                                        <div class="checkbox--secondary disabled">
                                                            <input type="checkbox" id="disabled4" disabled>
                                                            <label for="disabled4"></label>
                                                        </div>
                                                    @endif
                                                @else
                                                    <bold>{{ $column }}:</bold> {{ $product->$column }}
                                                @endif
                                            </p>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                @endif
            </tbody>
        </table>

        <x-admin-lazyload />


        {{-- Load More Manual --}}
        @if ($loadAmount <= count($products))
            <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
                Load more
            </button>
        @endif
    </div>

</section>
