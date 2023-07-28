<div>
    <x-alert />
    <div wire:loading.delay>
        <div class="modal" style="display:flex;">
            <div class="loader"></div>
        </div>
    </div>
    <div class="accordion">
        <div class="accordion__btn-flex">
            <button class="accordion__btn"
                wire:click.prevent="@if ($showrelatedprods === false) $set('showrelatedprods', true) @else $set('showrelatedprods', false) @endif">
                {{ __('Products ') }}({{ count($relatedprods) }})
            </button>
            <button wire:click.prevent="addrelated()" class="accordion__upload">
                <svg>
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg> </button>
        </div>
        {{-- add related specs --}}
        @if ($addrelatedproducts)

            <div class="modal" id="modalelements" style="display: block">
                <div class="modal-content"
                    style="margin: 0 auto; width:80%; top: 50%; transform: translateY(-50%); display: flex; height: 98vh; overflow-y: auto; align-items: flex-start">
                    <div class="item" style="width: 100%">
                        <h1 id="top1">

                            {{ __('Add related Products') }}
                        </h1>
                        <div class="display-f wid-10">

                            <input class="modal-content-btn submit" wire:click.prevent="saveprod()" type="button"
                                value="Save">
                        </div>
                        <table class="table-external">
                            <thead>
                                <tr>
                                    <th>Specification</th>
                                    <th>Product</th>
                                    <th>Value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prod as $index => $pro)
                                    <tr wire:key="spec-row-{{ $index }}">
                                        <td>
                                            <div class="item__form-input-close">
                                                <div>{{ $item->name }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="item__form-input">
                                                @if ($pro['allow'])
                                                    <input wire:model.debounce.200ms="searchadd" placeholder="Search.."
                                                        type="text">
                                                    <ul class="pos-abs wid-10 b-1 bra-sm p-1"
                                                        style="top: 3rem; z-index: 99999; background: white">
                                                        @if (count($addprods) >= 1)
                                                            @foreach ($addprods as $pr)
                                                                <li class="cursor-p"
                                                                    wire:click.prevent="selectProduct({{ $index }}, {{ $pr->id }}, '{{ $pr->name }}')">
                                                                    {{ $pr->name }}
                                                                </li>
                                                            @endforeach
                                                        @else
                                                            <li>{{ __('No product found') }}</li>
                                                        @endif
                                                    </ul>
                                                @else
                                                    <div wire:click.prevent="allowselect({{ $index }})">
                                                        @if ($pro['itemselected'])
                                                            {{ $pro['itemselected'] }}
                                                            <input type="hidden"
                                                                wire:model.defer="prod.{{ $index }}.product.name">
                                                        @else
                                                            {{ __('Select a product') }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="item__form-input">
                                                <input type="text"
                                                    wire:model.defer="prod.{{ $index }}.product.value">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="display-f jus-c">
                                                @if ($index == $row - 1)
                                                    <button type="button" class="edit" wire:click="plus">
                                                        <svg>
                                                            <line x1="12" y1="5" x2="12"
                                                                y2="19">
                                                            </line>
                                                            <line x1="5" y1="12" x2="19"
                                                                y2="12">
                                                            </line>
                                                        </svg>
                                                    </button>
                                                @endif
                                                @if ($index != $row - 1)
                                                    <button type="button" class="save"
                                                        wire:click="clear({{ $index }})">
                                                        <svg>
                                                            <line x1="18" y1="6" x2="6"
                                                                y2="18">
                                                            </line>
                                                            <line x1="6" y1="6" x2="18"
                                                                y2="18">
                                                            </line>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <span class="top-up-modal delete" style="right: 5%" wire:click="closemodal">

                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18">
                        </line>
                        <line x1="6" y1="6" x2="18" y2="18">
                        </line>
                    </svg>
                </span>
                <a href="#top1" class="top-up-modal" id="topUp">
                    <svg>
                        <polyline points="18 15 12 9 6 15"></polyline>
                    </svg>
                </a>
            </div>
        @endif

        @if ($editmultiple)
            <div class="modal" id="modalelements" style="display: block">
                <div class="modal-content"
                    style="margin: 0 auto; width:80%; top: 50%; transform: translateY(-50%); display: flex; height: 98vh; overflow-y: auto; align-items: flex-start">
                    <div class="item" style="width: 100%">
                        <h1 id="top">

                            {{ __('Edit multiple related items') }}

                        </h1>
                        <div class="display-f wid-10">
                            <input class="modal-content-btn submit" wire:click.prevent="confirmmultiple" type="button"
                                value="Edit">

                        </div>
                        <table class="table-external">
                            <thead>
                                <tr>
                                    <th>Specification</th>
                                    <th>Product</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($prod as $index => $pro)
                                    <tr wire:key="prod-row-{{ $index }}">
                                        <td>
                                            <div class="item__form-input-close">
                                                <div>{{ $item->name }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="item__form-input">
                                                @if ($pro['allow'])
                                                    <input wire:model.debounce.200ms="searchadd"
                                                        placeholder="Search.." type="text">
                                                    <ul class="pos-abs wid-10" style="top: 3rem; z-index: 99999">
                                                        @if (count($addprods) >= 1)
                                                            @foreach ($addprods as $pr)
                                                                <li class="cursor-p"
                                                                    wire:click.prevent="selectProduct({{ $index }}, {{ $pr->id }}, '{{ $pr->name }}')">
                                                                    {{ $pr->name }}
                                                                </li>
                                                            @endforeach
                                                        @else
                                                            <li>{{ __('No product found') }}</li>
                                                        @endif
                                                    </ul>
                                                @else
                                                    <div wire:click.prevent="allowselect({{ $index }})">
                                                        @if ($pro['itemselected'])
                                                            {{ $pro['itemselected'] }}
                                                            <input type="hidden"
                                                                wire:model.defer="prod.{{ $index }}.product.name">
                                                        @else
                                                            {{ __('Select a product') }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="item__form-input">
                                                <input type="text"
                                                    wire:model.defer="prod.{{ $index }}.product.value">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
                <span class="top-up-modal delete" style="right: 5%" wire:click="closemodal">

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
        @endif
        {{-- end add related specs --}}
        @if ($showrelatedprods)
            <div class="accordion__content">
                <div class="item">
                    @if ($relatedprods && count($relatedprods) > 0)
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
                                            <button class="dropdown-item submit" type="button"
                                                wire:click="editSelected">
                                                Edit
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
                                    @if ($this->showColumn('Product name'))
                                        <th>Product name
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
                                @foreach ($relatedprods as $index => $prod)
                                    <tr class="@if ($this->isChecked($prod->id)) th_checked @endif">
                                        <td data-title="Check"><input type="checkbox" value="{{ $prod->id }}"
                                                wire:model="checked">
                                        </td>

                                        @if ($this->showColumn('Id'))
                                            <td data-title="ID">{{ $prod->id }}</td>
                                        @endif
                                        @if ($this->showColumn('Product name'))
                                            <td data-title="Product name">
                                                @if ($editedrow !== $index)
                                                    <div class="cursor-p"
                                                        wire:click.prevent="editprod({{ $prod->id }}, {{ $prod->product->id }}, {{ $index }})">
                                                        {{ $prod->product->name }}</div>
                                                @else
                                                    @if ($allow)
                                                        <div>
                                                            <input class="wid-10 p-1"
                                                                wire:model.debounce.200ms="searchadd"
                                                                placeholder="Search.." type="text">
                                                            <ul class="pos-abs b-1 bra-sm p-1"
                                                                style="z-index: 99999; background: white">
                                                                @foreach ($addprods as $pr)
                                                                    <li class="cursor-p"
                                                                        wire:click.prevent="select({{ $pr->id }})">
                                                                        {{ $pr->name }}</li>
                                                                @endforeach
                                                            </ul>
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div wire:click.prevent="allow"
                                                            class="cursor-p b-1 bra-sm p-1" style="background: white">
                                                            {{ $itemselected }}
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                        @if ($this->showColumn('Unit'))
                                            <td data-title="Unit">
                                                {{ $prod->spec->um }}</td>
                                        @endif
                                        @if ($this->showColumn('Value'))
                                            <td data-title="Value">
                                                @if ($editedrow !== $index)
                                                    <div class="cursor-p"
                                                        wire:click.prevent="editprod({{ $prod->id }}, {{ $prod->product->id }}, {{ $index }})">
                                                        {{ $prod->value }}</div>
                                                @else
                                                    <input type="text" required class="table-edit wid-6"
                                                        wire:model="product.{{ $index }}.value">
                                                @endif
                                            </td>
                                        @endif
                                        @if ($this->showColumn('Created At'))
                                            <td data-title="Created At">{{ $prod->created_at }}</td>
                                        @endif
                                        <td data-title="Action">

                                            @if ($editedrow !== $index)
                                                <button class="edit"
                                                    wire:click.prevent="editprod({{ $prod->id }}, {{ $prod->product->id }}, {{ $index }})">
                                                    <svg>
                                                        <path
                                                            d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                <button class="delete"
                                                    wire:click.prevent="confirmRemoval({{ $prod->id }})">
                                                    <svg>
                                                        <circle cx="12" cy="12" r="10">
                                                        </circle>
                                                        <line x1="15" y1="9" x2="9"
                                                            y2="15"></line>
                                                        <line x1="9" y1="9" x2="15"
                                                            y2="15"></line>
                                                    </svg>
                                                </button>
                                            @else
                                                <button class="edit"
                                                    wire:click.prevent="confirmprod({{ $index }},{{ $prod->id }})">
                                                    <svg>
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                </button>
                                                <button class="save" wire:click.prevent="canceledit()">
                                                    <svg>
                                                        <line x1="18" y1="6" x2="6"
                                                            y2="18">
                                                        </line>
                                                        <line x1="6" y1="6" x2="18"
                                                            y2="18">
                                                        </line>
                                                    </svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>{{ $relatedprods->links() }} </div>
                    @else
                        <p>No products related</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
