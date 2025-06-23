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
    <h1 class="table--name">{{ __('Jobs') }} ({{ $jobs->total() }})</h1>
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
        <div class="dropdown dropdown--right">
            <button class="button button--primary button--centered button--long" tooltip="Active listview"
                tooltip-top>
                <span>{{ $tableName }}</span>
            </button>

            <div class="dropdown__content custom-dropdown-content"
                style="gap: 5px; display: grid; left: 0; right: 0; max-height: 200px; overflow-y: auto;">
                <button class="button button--primary button--long" wire:click="$set('tableName', 'csv_import_jobs')">
                    Import Jobs
                </button>
                <button class="button button--primary button--long" wire:click="$set('tableName', 'failed_jobs')">
                    Failed Jobs
                </button>
                <button class="button button--primary button--long" wire:click="$set('tableName', 'jobs')">
                    Queued Jobs
                </button>
            </div>
        </div>

        {{-- Search Input --}}
        <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">

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
                    <button class="button button--primary button--fill button--flexed" wire:click="exportData">
                        <svg>
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Export data</span>
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
                            <input type="checkbox" id="selectPage26" wire:model="selectPage" />
                            <label for="selectPage26"></label>
                        </div>
                    </th>
                    @foreach ($selectedColumns as $index => $column)
                        <th @if ($index > 1) class="hidden" @endif>
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
                @if ($jobs->isEmpty())
                    <tr>
                        <td class="table--empty" colspan="{{ count($selectedColumns) + 2 }}">No record found.</td>
                    </tr>
                @else
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($jobs as $nr => $item)
                        <tr @if ($loop->last) id="last_record" @endif
                            class="expandable-row @if ($this->isChecked($item->id)) active @endif">
                            <td style="border-left: none" data-title="Check">
                                <div class="checkbox--primary">
                                    <input type="checkbox" value="{{ $item->id }}" id="{{ $item->id }}"
                                        wire:model="checked">
                                    <label for="{{ $item->id }}"></label>
                                </div>
                            </td>
                            @foreach ($selectedColumns as $index => $column)
                                <td @if ($index > 1) class="hidden" @endif
                                    data-title="{{ $column }}" wire:click="expandRow({{ $nr }})">

                                    {{ $item->$column }}

                                </td>
                            @endforeach
                            <td style="border-right: none">
                                <button wire:click.prevent="confirmItemRemoval('{{ $item->id }}')"
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
                                        @if ($index > 1)
                                            <p>
                                                <bold>{{ $column }}:</bold>{{ $item->$column }}
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
        @if ($loadAmount <= count($jobs))
            <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="loadMore">
                Load more
            </button>
        @endif
    </div>
</section>
