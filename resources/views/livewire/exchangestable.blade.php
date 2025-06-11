<section class="content">
    <x-alert />

    <aside>
        <div class="background background--center @if ($single) active @endif"></div>
        <div class="aside aside--confirm @if ($single) active @endif">
            <span>
                Are you sure to delete this record?
            </span>
            <button class="button button--primary button--long" wire:click="deleteSingleRecord()">
                <span>Delete</span>
            </button>
            <button class="button button--danger button--long" wire:click="$set('single', false)">
                <span>Cancel</span>
            </button>
        </div>
    </aside>
    {{-- Add new exchange --}}
    <aside>
        <div class="background background--center @if ($add) active @endif"></div>
        <form class="aside aside--table @if ($add) active @endif">
            {{-- Navigation --}}
            <nav class="nav--controls">
                <h1 class="table--name">
                    {{ __('Add exchnages rates') }}
                </h1>
                <button class="button button--primary button--centered" wire:click.prevent="saveadd()">
                    <svg>
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M14 4l0 4l-6 0l0 -4" />
                    </svg>
                </button>
                <button class="button button--danger button--centered" wire:click="canceladd()">
                    <svg>
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 19v-2a2 2 0 0 1 2 -2h2" />
                        <path d="M15 5v2a2 2 0 0 0 2 2h2" />
                        <path d="M5 15h2a2 2 0 0 1 2 2v2" />
                        <path d="M5 9h2a2 2 0 0 0 2 -2v-2" />
                    </svg>
                </button>
            </nav>


            {{-- Table --}}
            <div class="table" style="height: calc(100% - 60px);">
                <table class="expandable-table">
                    <thead>
                        <tr>
                            <th>
                                <div class="table--btn">Nr.</div>

                            </th>
                            <th style="min-width: 30%">
                                <div class="table--btn">Base currency</div>
                            </th>

                            <th>
                                <div class="table--btn">Quote currency</div>
                            </th>
                            <th>
                                <div class="table--btn">Value</div>
                            </th>
                            <th>
                                <div class="table--btn">Date</div>
                            </th>
                            <th>
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
                        @for ($i = 1; $i <= $rowadd; $i++)
                            <tr class="expandable-row">
                                <td>
                                    {{ $i }}</td>
                                <td>
                                    <select class="searchable" wire:model.defer="base.{{ $i }}">
                                        <option value="">Select base currency</option>

                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                        @endforeach
                                    </select>

                                </td>
                                <td>
                                    <select class="searchable" wire:model.defer="quote.{{ $i }}">
                                        <option value="">Select quote currency</option>

                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="searchable">
                                        <input placeholder="value" type="number"class="input__searchable"
                                            wire:model.defer="value.{{ $i }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="searchable">
                                        <input type="date"class="input__searchable"
                                            wire:model.defer="date.{{ $i }}">
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex;">
                                        @if ($i == $rowadd)
                                            <button type="button" class="button button--secondary button--sm"
                                                wire:click="plus()">
                                                <svg>
                                                    <line x1="12" y1="5" x2="12" y2="19">
                                                    </line>
                                                    <line x1="5" y1="12" x2="19" y2="12">
                                                    </line>
                                                </svg>
                                            </button>
                                        @endif
                                        <button type="button" class="button button--secondary button--sm"
                                            wire:click="clear({{ $i }})">
                                            <svg>
                                                <line x1="18" y1="6" x2="6" y2="18">
                                                </line>
                                                <line x1="6" y1="6" x2="18" y2="18">
                                                </line>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </form>
    </aside>


    {{-- Navigation --}}
    <h1 class="table--name">{{ __('Exchanges rate') }} ({{ count($exchanges) }})</h1>
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
        <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
        {{-- Optional Dropdown --}}
        <div class="dropdown dropdown--right">
            {{-- Dropdown Button --}}
            <button class="button button--primary button--centered" tooltip="Show more actions" tooltip-left>
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
                    <button class="button button--primary button--long button--flexed" wire:click="$refresh">
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
                    <a class="button button--primary button--fill button--flexed"
                        wire:click.prevent="$set('add', true)">
                        <svg>
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                        <span>Add new exchange</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    {{-- Table --}}
    <div class="table">
        <table class="expandable-table">
            <thead>
                <tr>
                    <th style="border-right: none; border-left: none;">
                        <div class="checkbox--primary">
                            <input type="checkbox" id="selectPage3" wire:model="selectPage" />
                            <label for="selectPage3"></label>
                        </div>
                    </th>
                    @foreach ($selectedColumns as $index => $column)
                        <th @if ($index >= 2) class="hidden" @endif>
                            <button wire:click="sortBy('{{ $column }}')"
                                class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
                                {{ $column }}
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
                @if ($exchanges->isEmpty())
                    <tr>
                        <td class="table--empty" colspan="{{ count($selectedColumns) + 2 }}">No record found.</td>
                    </tr>
                @else
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($exchanges as $nr => $exchange)
                        <tr @if ($loop->last) id="last_record" @endif
                            class="expandable-row @if ($this->isChecked($exchange->id)) active @endif">
                            <td style="border-left: none" data-title="Check">
                                <div class="checkbox--primary">
                                    <input type="checkbox" value="{{ $exchange->id }}" id="{{ $exchange->id }}"
                                        wire:model="checked">
                                    <label for="{{ $exchange->id }}"></label>
                                </div>
                            </td>
                            @foreach ($selectedColumns as $index => $column)
                                <td @if ($index > 1) class="hidden" @endif
                                    wire:click="expandRow({{ $nr }})">
                                    @if ($column === 'base_currency_id')
                                        @if ($rowindex !== $index)
                                            {{ $exchange->base_currency->name }}
                                        @else
                                            <select class="searchable"
                                                wire:model.defer="element.{{ $index }}.base_currency_id">
                                                @foreach ($currencies as $currency)
                                                    <option value="{{ $currency->id }}">{{ $currency->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    @elseif ($column === 'quote_currency_id')
                                        @if ($rowindex !== $index)
                                            {{ $exchange->quote_currency->name }}
                                        @else
                                            <select class="searchable"
                                                wire:model.defer="element.{{ $index }}.quote_currency_id">
                                                @foreach ($currencies as $currency)
                                                    <option value="{{ $currency->id }}">{{ $currency->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    @elseif ($column === 'value')
                                        @if ($rowindex !== $index)
                                            {{ $exchange->value }}
                                        @else
                                            <div class="searchable">
                                                <input type="number" class="input__searchable"
                                                    wire:model.defer="element.{{ $index }}.value">
                                            </div>
                                        @endif
                                    @elseif ($column === 'date')
                                        @if ($rowindex !== $index)
                                            {{ $exchange->date }}
                                        @else
                                            <div class="searchable">
                                                <input type="date" class="input__searchable"
                                                    wire:model.defer="element.{{ $index }}.date">
                                            </div>
                                        @endif
                                    @else
                                        {{ $exchange->$column }}
                                    @endif
                            @endforeach
                            <td style="border-right: none">
                                <div style="display:flex;">
                                    @if ($rowindex !== $nr)
                                        <button class="button button--secondary button--sm"
                                            wire:click.prevent="edititem({{ $nr }}, {{ $exchange->id }})">
                                            <svg>
                                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button class="button button--secondary button--sm"
                                            wire:click.prevent="confirmItemRemoval({{ $exchange->id }})">
                                            <svg>
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                </path>
                                            </svg>
                                        </button>
                                    @else
                                        <button class="button button--secondary button--sm"
                                            wire:click.prevent="saveitem({{ $nr }} , {{ $exchange->id }})">
                                            <svg>
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        </button>
                                        <button class="button button--secondary button--sm"
                                            wire:click.prevent="canceledit()">
                                            <svg>
                                                <line x1="18" y1="6" x2="6" y2="18">
                                                </line>
                                                <line x1="6" y1="6" x2="18" y2="18">
                                                </line>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr class="details-row  @if ($row === $i) active @endif">
                            <td colspan="17">
                                <div class="details">
                                    @if ($column === 'base_currency_id')
                                        @if ($rowindex !== $index)
                                            <p>
                                                base_currency:
                                                {{ $exchange->base_currency->name }}
                                            </p>
                                        @else
                                            <p>
                                                base_currency:
                                                <select class="searchable"
                                                    wire:model.defer="element.{{ $index }}.base_currency_id">
                                                    @foreach ($currencies as $currency)
                                                        <option value="{{ $currency->id }}">{{ $currency->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </p>
                                        @endif
                                    @elseif ($column === 'quote_currency_id')
                                        @if ($rowindex !== $index)
                                            <p>
                                                quote_currency:
                                                {{ $exchange->quote_currency->name }}
                                            </p>
                                        @else
                                            <p>
                                                quote_currency:
                                                <select class="searchable"
                                                    wire:model.defer="element.{{ $index }}.quote_currency_id">
                                                    @foreach ($currencies as $currency)
                                                        <option value="{{ $currency->id }}">{{ $currency->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </p>
                                        @endif
                                    @elseif ($column === 'value')
                                        @if ($rowindex !== $index)
                                            <p>
                                                {{ $column }}:
                                                {{ $exchange->$column }}
                                            </p>
                                        @else
                                            <p>
                                                {{ $column }}:
                                            <div class="searchable">
                                                <input type="number" class="input__searchable"
                                                    wire:model.defer="element.{{ $index }}.value">
                                            </div>
                                            </p>
                                        @endif
                                    @elseif ($column === 'date')
                                        @if ($rowindex !== $index)
                                            <p>
                                                {{ $column }}:
                                                {{ $exchange->$column }}
                                            </p>
                                        @else
                                            <p>
                                                {{ $column }}:
                                            <div class="searchable">
                                                <input type="number" class="input__searchable"
                                                    wire:model.defer="element.{{ $index }}.date">
                                            </div>
                                            </p>
                                        @endif
                                    @else
                                        <p>
                                            <bold>{{ $column }}:</bold>

                                            {{ $exchange->$column }}
                                        </p>
                                    @endif

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


        {{-- Load More Automatic --}}
        <x-admin-lazyload />


        {{-- Load More Manual --}}
        @if ($loadAmount <= count($exchanges))
            <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
                Load more
            </button>
        @endif
    </div>
</section>
