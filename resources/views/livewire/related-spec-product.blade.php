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
                wire:click.prevent="@if ($showrelatedspecs === false) $set('showrelatedspecs', true) @else $set('showrelatedspecs', false) @endif">
                {{ __('Specs ') }}({{ count($relatedspecs) }})
            </button>
            <button wire:click.prevent="addrelated()" class="accordion__upload">
                <svg>
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg> </button>
        </div>
        {{-- add related specs --}}
        <div class="modal" id="addspecsmodal" @if ($addrelatedspecs) style="display: flex;" @endif>
            <div class="modal-content-spec wid-2">
                <h1>
                    {{ __('Add related Specs') }}
                </h1>
                <div class="display-f align-center">
                    <div>Product:</div>
                    <div class="item__form-input item__form-long">
                        <div>{{ $item->name }}</div>
                    </div>
                </div>
                <div class="display-f align-center">
                    <div>Spec:</div>
                    <div class="item__form-input item__form-long cursor-p">
                        <div wire:click.prevent="allowselect()">
                            @if ($itemselected)
                                {{ $itemselected }}
                            @else
                                {{ __('Select a spec') }}
                            @endif
                        </div>
                    </div>
                </div>

                {{-- add specs list with search --}}
                @if ($allow)
                    <div class="item__form-input b-1 bra-sm p-1">
                        <input wire:model.debounce.200ms="searchadd" placeholder="Search.." type="text">
                        <ul>
                            @foreach ($addspecs as $spec)
                                <li class="cursor-p" wire:click.prevent="select({{ $spec->id }})">
                                    {{ $spec->name }} ( {{ $spec->um }})</li>
                            @endforeach
                        </ul>
                        <span class="modal-content-btn edit" style="left: 90%"
                            wire:click.prevent="$set('allow', false)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </span>
                    </div>
                @endif

                <div class="display-f align-center">
                    <div>Value:</div>
                    <div class="item__form-input item__form-long">
                        <input type="text"class="table-edit wid-6" wire:model.defer="spec.value">
                    </div>
                </div>
                {{-- end specs list with search --}}
                <div class="display-f wid-10">
                    <input class="modal-content-btn submit" wire:click.prevent="savespecs()" type="button"
                        value="Save">
                    <input class="modal-content-btn delete" wire:click.prevent="closemodal()" type="button"
                        value="Cancel">
                </div>

                <span class="modal-content-btn delete" wire:click.prevent="closemodal()">
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </span>
            </div>
        </div>
        {{-- end add related specs --}}
        @if ($showrelatedspecs)
            <div class="accordion__content">
                <div class="item">
                    @if ($relatedspecs && count($relatedspecs) > 0)
                        <div class="item__form form-table"
                            @if ($checked) style="grid-template-columns: 40% 1fr 1fr 1fr" @endif>

                            <div class="item__form-input">
                                <input wire:model.debounce.200ms="search" type="text">
                                <span>Search...</span>
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
                                                wire:click="confirmRemovalmultiple()">
                                                Delete
                                            </button>
                                            <button class="dropdown-item submit" type="button">
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
                                        You have selected <strong>{{ count($checked) }}</strong> items, Do you
                                        want
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
                                    @if ($this->showColumn('Unit'))
                                        <th>Unit
                                        </th>
                                    @endif
                                    @if ($this->showColumn('Value'))
                                        <th>Value
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
                                @foreach ($relatedspecs as $spec)
                                    <tr class="@if ($this->isChecked($spec->id)) th_checked @endif">
                                        <td data-title="Check"><input type="checkbox" value="{{ $spec->id }}"
                                                wire:model="checked">
                                        </td>

                                        @if ($this->showColumn('Id'))
                                            <td data-title="ID">{{ $spec->id }}</td>
                                        @endif
                                        @if ($this->showColumn('Name'))
                                            <td data-title="Name">{{ $spec->spec->name }}
                                            </td>
                                        @endif
                                        @if ($this->showColumn('Unit'))
                                            <td data-title="Unit">
                                                {{ $spec->spec->um }}</td>
                                        @endif
                                        @if ($this->showColumn('Unit'))
                                            <td data-title="Unit">
                                                {{ $spec->value }}</td>
                                        @endif
                                        @if ($this->showColumn('Created At'))
                                            <td data-title="Created At">{{ $spec->spec->created_at }}</td>
                                        @endif
                                        <td data-title="Action">
                                            <button class="delete"
                                                wire:click.prevent="confirmRemoval({{ $spec->id }})">
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
                        <div>{{ $relatedspecs->links() }} </div>
                    @else
                        <p>no Specs related</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
