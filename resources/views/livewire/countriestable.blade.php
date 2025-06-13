<section class="content">
    {{-- X-Components --}}
    <x-alert />

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
    <h1 class="table--name">{{ __('Countries') }} ({{ $countries->total() }})</h1>
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
                    <button class="button button--primary button--fill button--flexed"
                        wire:click="addCountriesIfNotExist">
                        <svg>
                            <polyline points="17 1 21 5 17 9"></polyline>
                            <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                            <polyline points="7 23 3 19 7 15"></polyline>
                            <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                        </svg>
                        <span>Update countries</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>


    {{-- Table --}}
    <div class="table">
        <table class="expandable-table">
            <thead>
                <tr>
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
                @php
                    $i = 0;
                @endphp
                @foreach ($countries as $index => $country)
                    <tr>
                        @foreach ($selectedColumns as $j => $column)
                            <td @if ($j >= 2) class="hidden"@else wire:click="expandRow({{ $index }})" @endif
                                @if ($j === 0) style="border-left: none" @endif>
                                @if (
                                    $column === 'name' ||
                                        $column === 'iso_code' ||
                                        $column === 'iso_code3' ||
                                        $column === 'phone_code' ||
                                        $column === 'currency')
                                    @if ($rowindex !== $index)
                                        @if ($column === 'name')
                                            <a href="{{ route('show_country', ['id' => $country->id]) }}">
                                                {{ strip_tags($country->$column) }}
                                            </a>
                                        @else
                                            {{ $country->$column }}
                                        @endif
                                    @else
                                        <div class="searchable">
                                            <input type="text" class="input__searchable"
                                                wire:model.defer="element.{{ $index }}.{{ $column }}">
                                        </div>
                                    @endif
                                @elseif ($column === 'status')
                                    @if ($rowindex !== $index)
                                        @if ($country->status)
                                            <div class="checkbox--secondary">
                                                <input type="checkbox" id="isactive{{ $index }}" disabled
                                                    checked>
                                                <label for="isactive{{ $index }}"></label>
                                            </div>
                                        @else
                                            <div class="checkbox--secondary disabled">
                                                <input type="checkbox" id="notactive{{ $index }}" disabled>
                                                <label for="notactive{{ $index }}"></label>
                                            </div>
                                        @endif
                                    @else
                                        <div class="checkbox--secondary inline">
                                            <input type="checkbox" id="check{{ $index }}"
                                                wire:model.lazy="element.{{ $index }}.{{ $column }}" />
                                            <label for="check{{ $index }}"></label>
                                        </div>
                                    @endif
                                @else
                                    {{ $country->$column }}
                                @endif
                            </td>
                        @endforeach
                        <td>
                            @if ($rowindex !== $index)
                                <button class="button button--secondary button--sm"
                                    wire:click.prevent="edititem({{ $index }}, {{ $country->id }})">
                                    <svg>
                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                        </path>
                                    </svg>
                                </button>
                            @else
                                <div style="display: flex;">
                                    <button class="button button--secondary button--sm"
                                        wire:click.prevent="saveitem({{ $index }} , {{ $country->id }})">
                                        <svg>
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </button>
                                    <button class="button button--secondary button--sm"
                                        wire:click.prevent="cancelitem()">
                                        <svg>
                                            <line x1="18" y1="6" x2="6" y2="18">
                                            </line>
                                            <line x1="6" y1="6" x2="18" y2="18">
                                            </line>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr class="details-row  @if ($row === $i) active @endif">
                        <td colspan="17">
                            <div class="details">
                                @foreach ($selectedColumns as $index => $column)
                                    @if (
                                        $column === 'name' ||
                                            $column === 'iso_code' ||
                                            $column === 'iso_code3' ||
                                            $column === 'phone_code' ||
                                            $column === 'currency')
                                        @if ($rowindex !== $index)
                                            <p>
                                                <bold>{{ $column }}:</bold>
                                                {{ $country->$column }}
                                            </p>
                                        @else
                                            <p>
                                                <bold>{{ $column }}:</bold>
                                            <div class="searchable">
                                                <input type="text" class="input__searchable"
                                                    wire:model.defer="element.{{ $index }}.{{ $column }}">
                                            </div>
                                            </p>
                                        @endif
                                    @elseif ($column === 'status')
                                        @if ($rowindex !== $index)
                                            <p>
                                                <bold>{{ $column }}:</bold>
                                                @if ($country->$column)
                                                    <div class="checkbox--secondary">
                                                        <input type="checkbox" id="isactive{{ $index }}"
                                                            disabled checked>
                                                        <label for="isactive{{ $index }}"></label>
                                                    </div>
                                                @else
                                                    <div class="checkbox--secondary disabled">
                                                        <input type="checkbox" id="notactive{{ $index }}"
                                                            disabled>
                                                        <label for="notactive{{ $index }}"></label>
                                                    </div>
                                                @endif
                                            </p>
                                        @else
                                            <p>
                                                <bold>{{ $column }}:</bold>
                                            <div class="checkbox--secondary inline">
                                                <input type="checkbox" id="check{{ $index }}"
                                                    wire:model.lazy="element.{{ $index }}.{{ $column }}" />
                                                <label for="check{{ $index }}"></label>
                                            </div>
                                            </p>
                                        @endif
                                    @else
                                        <p>
                                            <bold>{{ $column }}:</bold>
                                            {{ $country->$column }}
                                        </p>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        @php
                            $i++;
                        @endphp
                @endforeach
            </tbody>
        </table>


        {{-- Load More Automatic --}}
        <x-admin-lazyload />


        {{-- Load More Manual --}}
        @if ($loadAmount <= count($countries))
            <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
                Load more
            </button>
        @endif
    </div>
</section>
