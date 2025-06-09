<section class="content">
    {{-- X-Components --}}
    <x-alert />


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

    {{-- xml invoice --}}
    <aside>
        <div class="background background--center @if ($xmlinvoicesmodal || $xmlstornomodal) active @endif"></div>
        <div class="aside aside--confirm @if ($xmlinvoicesmodal || $xmlstornomodal) active @endif"
            style="min-width: 400px;min-height:250px">
            <span>
                @if ($xmlinvoicesmodal)
                    Select the invoice start and end date
                @else
                    Select the storno start and end date
                @endif
            </span>
            <form method="POST" wire:submit.prevent="handleSubmission">
                @csrf
                <div class="input__tabs">
                    <input type="date" id="start_date" wire:model.defer="start_date" name="start_date">
                    <label>Start Date</label>
                </div>

                {{-- End Date --}}
                <div class="input__tabs">
                    <input type="date" id="end_date" wire:model.defer="end_date" name="end_date">
                    <label>End Date</label>
                </div>

                {{-- Buttons for Submit --}}
                <div class="button-group" style="margin-top: 10px">
                    @if ($xmlinvoicesmodal)
                        <button type="button" class="button button--primary button--long"
                            wire:click="generate_xml_invoice">
                            <span>Generate XML Invoice</span>
                        </button>
                    @else
                        <button type="button" class="button button--primary button--long"
                            wire:click="generate_xml_storno">
                            <span>Generate XML Storno</span>
                        </button>
                    @endif
                    {{-- Cancel Button --}}
                    <button type="button" class="button button--danger button--long" wire:click="cancel_xml">
                        <span>Cancel</span>
                    </button>
                </div>

            </form>
        </div>
    </aside>

    {{-- Filter modal --}}
    <aside>
        <div class="background background--center @if ($filteractive) active @endif"></div>
        <div class="aside aside--confirm @if ($filteractive) active @endif"
            style="min-width: 400px;min-height:250px">
            <span>
                Select the dates
            </span>
            <div class="input__tabs">
                <input type="date" id="start_date" wire:model.defer="start_date_filter" name="start_date">
                <label>Start Date</label>
            </div>

            {{-- Product End Date --}}
            <div class="input__tabs">
                <input type="date" id="end_date" wire:model.defer="end_date_filter" name="end_date">
                <label>End Date</label>
            </div>

            <button class="button button--primary button--long" wire:click="filter_order()">
                <span>Filter</span>
            </button>
            <button class="button button--danger button--long" wire:click="cancel_filter()">
                <span>Cancel</span>
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
                        <select wire:model.defer="addfilter.column">
                            <option value="">Select a column</option>
                            @foreach ($columns as $field)
                                <option value="{{ $field }}">{{ $field }}</option>
                            @endforeach
                        </select>
                        <label> Column</label>
                    </div>
                    <div class="input__tabs details__long">
                        <select wire:model.defer="addfilter.operator">
                            <option value="">Select a operator</option>
                            <option value="=">equal</option>
                            <option value="like">contains</option>
                            <option value=">">greater than</option>
                            <option value="<">less than</option>
                        </select>
                        <label> Operator</label>
                    </div>
                    <div class="input__tabs details__long">
                        <input type="text" wire:model.defer="addfilter.value">
                        <label>Value</label>
                    </div>
                    <div class="details__long" style="margin-bottom: 5px;">

                        <button style="max-width: none!important; width:100%!important"
                            class="button button--secondary button--long" wire:click.prevent="save_filter()">
                            <span>Add filter</span>
                        </button>
                    </div>
                    @if (!empty($listview['filters']))
                        <label class="details__long"
                            style="font-weight: bold; text-align:center; color: white; padding-top:10px">Listview
                            filters</label>

                        @foreach ($listview['filters'] as $index => $filter)
                            <div class="details__long"
                                style="display: flex; align-items: center; justify-content: space-between; background-color: #f3f3f3; padding: 10px; border-radius: 6px; margin-bottom: 8px;">
                                <div>{{ $index }}.
                                    <strong>{{ $filter['column'] }}</strong>
                                    {{ $filter['label'] ?? $filter['operator'] }}
                                    <em>{{ $filter['value'] }}</em>
                                </div>

                                <button wire:click.prevent="removeFilter({{ $index }})"
                                    style="border: none; background: none; color: red; font-weight: bold;">✕</button>
                            </div>
                        @endforeach
                        <div class="details__long" style="margin-bottom: 5px;">

                            <button style="max-width: none!important; width:100%!important"
                                class="button button--danger button--long" wire:click.prevent="clearAllFilters()">
                                <span>Remove all filters</span>
                            </button>
                        </div>
                        <div class="details__long" style="margin-bottom: 5px;">

                            <button style="max-width: none!important; width:100%!important"
                                class="button button--primary button--long"
                                wire:click.prevent="$set('filterlogic', true)">
                                <span>Edit filters logic</span>
                            </button>
                        </div>
                        @if ($filterlogic)
                            <div class="textarea__tabs details__long" style="margin-bottom: 5px">
                                <textarea wire:model="listview.logic" name="textarea" id="textarea10" cols="30" rows="3"></textarea>
                                <label>Filter logic (AND & OR)</label>
                            </div>
                        @endif
                    @endif
                    <button class="button button--primary button--long" wire:click.prevent="save_listview()">
                        <span>Save and close</span>
                    </button>
                    <button class="button button--danger button--long" style="margin-bottom: 10px !important"
                        wire:click.prevent="$set('editlistview', false)">
                        <span>Cancel</span>
                    </button>

                @endif


            </div>
        </div>
        </div>
    </aside>


    {{-- Navigation --}}
    <h1 class="table--name">{{ __('Orders') }} ({{ $orders->total() }})</h1>

    <div style="padding-top:5px; font-size:14px; color:#bcfcde;"><input type="checkbox" style="cursor:pointer;"
            wire:model="status31Only"> Show Processing Only</div>
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
                            others listviews found</span>
                    @endif


                </div>
            </div>
            <button class="button button--primary button--centered" tooltip="Edit listview" tooltip-top
                wire:click="$set('editlistview', true)">
                <svg>
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                    </path>
                </svg>
            </button>
        @endif
        {{-- Search Input --}}
        <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
        {{-- Refresh Button --}}



        {{-- IF CHECKED --}}
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

        {{-- xml generator --}}
        <div class="dropdown dropdown--right" wire:ignore>
            {{-- Dropdown Button --}}
            <button class="button button--primary button--centered" tooltip="Generate xml for orders" tooltip-left>
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                    <path d="M4 15l4 6" />
                    <path d="M4 21l4 -6" />
                    <path d="M19 15v6h3" />
                    <path d="M11 21v-6l2.5 3l2.5 -3v6" />
                </svg>
            </button>

            {{-- Dropdown Content --}}
            <div class="dropdown__content">
                <div class="dropdown__container">
                    <button class="button button--primary button--long button--flexed button--arrow"
                        wire:click="xmlinvoices">
                        for invoices
                    </button>
                    <button class="button button--primary button--long button--flexed button--arrow"
                        wire:click="xmlstorno">
                        for storno
                    </button>
                </div>
            </div>
        </div>

        {{-- Optional Dropdown --}}
        <div class="dropdown dropdown--right">
            {{-- Dropdown Button --}}
            <button class="button button--secondary button--centered" tooltip="Show more actions" tooltip-left>
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
                    <a class="button button--primary button--fill button--flexed" href="{{ route('getavgvalues') }}">
                        <svg>
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>

                        <span>Check orders cost</span>
                    </a>
                    <a class="button button--primary button--fill button--flexed" href="{{ route('checkorders') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-info">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <span>Check order values</span>
                    </a>
                    <button class="button button--primary button--fill button--flexed" wire:click="$refresh">
                        <svg>
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        <span>Filters</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>


    {{-- Select All? --}}
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
                            <input type="checkbox" id="selectPage5" wire:model="selectPage" />
                            <label for="selectPage5"></label>
                        </div>
                    </th>
                    @foreach ($selectedColumns as $index => $column)
                        @if ($column === 'shipping_id' || $column === 'billing_id')
                            @php
                                continue;
                            @endphp
                        @endif
                        <th @if ($index > 2) class="hidden" @endif>
                            <button wire:click="sortBy('{{ $column }}')"
                                class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
                                {{ str_replace('_id', '', $column) }}
                                <svg>
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                        </th>
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
                @if ($orders->isEmpty())
                    <tr>
                        <td class="table--empty" colspan="{{ count($selectedColumns) + 2 }}">No record found.</td>
                    </tr>
                @else
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($orders as $nr => $order)
                        @if ($order->status_id === app('global_order_processing'))
                            @php
                                $class = 'process';
                                $productDetails = [];
                            @endphp

                            @foreach ($order->orders as $orderItem)
                                @php
                                    $product = $orderItem->product;
                                    $interimQuantity = $product->quantity + $product->interim_quantity;

                                    // Check if interimQuantity is less than the order quantity
                                    if ($interimQuantity < $orderItem->quantity) {
                                        $class = 'notprocess';
                                        $productDetails[] =
                                            $orderItem->quantity - $interimQuantity . " x {$product->name} <br>";
                                    }
                                @endphp
                            @endforeach
                        @else
                            @php
                                $class = '';
                            @endphp
                        @endif
                        <tr @if ($loop->last) id="last_record" @endif
                            @if ($class === 'notprocess') data-tooltip="{{ implode('<br>', $productDetails) }}" @endif
                            class="expandable-row {{ $class }} @if ($this->isChecked($order->id)) active @endif">
                            <td style="border-left: none" data-title="Check">
                                <div class="checkbox--primary">
                                    <input type="checkbox" value="{{ $order->id }}" id="{{ $order->id }}"
                                        wire:model="checked">
                                    <label for="{{ $order->id }}"></label>
                                </div>
                            </td>
                            @foreach ($selectedColumns as $index => $column)
                                @if ($column === 'shipping_id' || $column === 'billing_id')
                                    @php
                                        continue;
                                    @endphp
                                @endif
                                <td @if ($index > 2) class="hidden" @endif
                                    data-title="{{ $column }}" wire:click="expandRow({{ $nr }})">
                                    @if ($column === 'name')
                                        <a
                                            href="{{ route('show_order', ['id' => $order->id]) }}">{{ $order->name }}</a>
                                    @elseif ($column === 'account_id')
                                        @if ($order->account_id)
                                            <a
                                                href="{{ route('show_account', ['id' => $order->account_id]) }}">{{ $order->account->name }}</a>
                                        @endif
                                    @elseif($column === 'session_id')
                                        <a
                                            href="{{ route('show_session', ['id' => $order->session_id]) }}">{{ $order->$column }}</a>
                                    @elseif ($column === 'cart_id')
                                        @if ($order->cart_id)
                                            <a
                                                href="{{ route('show_cart', ['id' => $order->cart_id]) }}">{{ $order->cart->name }}</a>
                                        @endif
                                    @elseif ($column === 'currency_id')
                                        {{ $order->currency->name }}
                                    @elseif ($column === 'status_id')
                                        {{ $order->status->name }}
                                    @elseif ($column === 'payment_id')
                                        {{ $order->payment->name }}
                                    @elseif($column === 'comments')
                                        <span class="show-less">
                                            {!! $order->$column !!}
                                        </span>
                                    @elseif ($column === 'voucher_id')
                                        @if ($order->voucher_id)
                                            {{ $order->voucher->code }}
                                        @endif
                                    @else
                                        {{ $order->$column }}
                                    @endif
                            @endforeach
                            <td style="border-right: none">
                                <button wire:click.prevent="confirmItemRemoval({{ $order->id }})"
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
                                        @if ($index > 2)
                                            @if ($column === 'account_id')
                                                @if ($order->account_id)
                                                    <p>
                                                        <bold>{{ str_replace('_id', '', $column) }}:</bold>
                                                        <a
                                                            href="{{ route('show_account', ['id' => $order->account_id]) }}">{{ $order->account->name }}</a>
                                                    </p>
                                                @endif
                                            @elseif ($column === 'cart_id')
                                                @if ($order->cart_id)
                                                    <p>
                                                        <bold>{{ str_replace('_id', '', $column) }}:</bold>
                                                        <a
                                                            href="{{ route('show_cart', ['id' => $order->cart_id]) }}">{{ $order->cart->name }}</a>
                                                    </p>
                                                @endif
                                            @elseif ($column === 'currency_id')
                                                <p>
                                                    <bold>{{ str_replace('_id', '', $column) }}:</bold>
                                                    {{ $order->currency->name }}
                                                </p>
                                            @elseif ($column === 'status_id')
                                                <p>
                                                    <bold>{{ str_replace('_id', '', $column) }}:</bold>
                                                    {{ $order->status->name }}
                                                </p>
                                            @elseif ($column === 'payment_id')
                                                <p>
                                                    <bold>{{ str_replace('_id', '', $column) }}:</bold>
                                                    {{ $order->payment->name }}
                                                </p>
                                            @elseif ($column === 'voucher_id')
                                                @if ($order->voucher_id)
                                                    <p>
                                                        <bold>{{ str_replace('_id', '', $column) }}:</bold>
                                                        {{ $order->voucher->code }}
                                                    </p>
                                                @endif
                                            @else
                                                <p>
                                                    <bold>{{ str_replace('_id', '', $column) }}:</bold>
                                                    {{ $order->$column }}
                                                </p>
                                            @endif
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tooltip = document.createElement('div');
                tooltip.className = 'row-tooltip';
                document.body.appendChild(tooltip);

                function attachTooltipListeners() {
                    document.querySelectorAll('.expandable-row.notprocess').forEach(row => {
                        row.addEventListener('mouseenter', function() {
                            tooltip.innerHTML = row.getAttribute('data-tooltip');
                            tooltip.style.display = 'flex';
                            tooltip.style.position = 'fixed';
                            tooltip.style.color = 'white';
                            tooltip.style.backgroundColor = '#333';
                            tooltip.style.border = '1px solid #fff';
                            tooltip.style.borderRadius = '6px';
                            tooltip.style.padding = '8px 12px';
                            tooltip.style.fontSize = '11px';
                            tooltip.style.maxWidth = '400px';
                            tooltip.style.wordWrap = 'break-word';
                        });

                        row.addEventListener('mousemove', function(event) {
                            tooltip.style.left = `${event.pageX + 15}px`;
                            tooltip.style.top = `${event.pageY + 15}px`;
                        });

                        row.addEventListener('mouseleave', function() {
                            tooltip.style.display = 'none';
                        });
                    });
                }

                attachTooltipListeners();

                window.addEventListener('livewire:load', attachTooltipListeners);
                window.addEventListener('livewire:update', attachTooltipListeners);
            });
        </script>

        {{-- Load More Manual --}}
        @if ($loadAmount <= count($orders))
            <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
                Load more
            </button>
        @endif
    </div>
</section>
