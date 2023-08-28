<div>
    <x-alert />
    <x-loading />
    <div class="accordion">
        <div class="accordion__btn-flex">
            <button class="accordion__btn"
                wire:click.prevent="@if ($showrelatedprods === false) $set('showrelatedprods', true) @else $set('showrelatedprods', false) @endif">
                {{ __("Products ") }}({{ count($relatedprods) }})
            </button>
            <button wire:click.prevent="addrelated()" class="accordion__upload">
                <svg>
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg> </button>
        </div>
        @if ($addrelatedproducts)
            <div class="modal" id="modalelements" style="display: block">
                <div class="modal-content modal--tabel">
                    {{-- Header of the table --}}
                    <div class="panel__header">
                        <h1 class="panel__header--title">
                            {{ __("Add related Products") }}
                        </h1>
                        <input class="panel__header--checked" wire:click.prevent="saveprod()" type="button"
                            value="Save">
                    </div>
                    {{-- Table --}}
                    <div style="overflow: auto; position: relative; background: white">
                        <table class="table table-top">
                            <thead>
                                <tr>
                                    <th><button class="table__header--btn">Specification</button></th>
                                    <th><button class="table__header--btn">Product</button></th>
                                    <th><button class="table__header--btn">Value</button></th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                        <table class="table" style="margin-top: 2rem">
                            <tbody>
                                @foreach ($prod as $index => $pro)
                                    <tr wire:key="spec-row-{{ $index }}">
                                        <td data-title="Specification">
                                            {{ $item->name }}
                                        </td>

                                        <td data-title="Product">
                                            @if ($pro["allow"])
                                                <div class="table__drop">
                                                    <input class="table__drop--input" wire:model.live="searchadd"
                                                        placeholder="Search..." type="text">
                                                    <ul class="table__drop--list">
                                                        @if (count($addprods) >= 1)
                                                            @foreach ($addprods as $pr)
                                                                <li class="table__drop--item"
                                                                    wire:click.prevent="selectProduct({{ $index }}, {{ $pr->id }}, '{{ $pr->name }}')">
                                                                    {{ $pr->name }}
                                                                </li>
                                                            @endforeach
                                                        @else
                                                            <li>{{ __("No product found") }}</li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @else
                                                <div wire:click.prevent="allowselect({{ $index }})"
                                                    class="table__drop--input">
                                                    @if ($pro["itemselected"])
                                                        {{ $pro["itemselected"] }}
                                                        <input type="hidden"
                                                            wire:model.defer="prod.{{ $index }}.product.name">
                                                    @else
                                                        {{ __("Select a product") }}
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        <td data-title="Value">
                                            <input type="text" required class="table__drop--input"
                                                wire:model.defer="prod.{{ $index }}.product.value">
                                        </td>

                                        <td data-title="Action">
                                            <div class="table__buttons">
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

                    <span class="top-up-modal delete" wire:click="closemodal">
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
            </div>
        @endif

        @if ($editmultiple)
            <div class="modal" id="modalelements" style="display: block">
                <div class="modal-content modal--tabel">
                    {{-- Header of the table --}}
                    <div class="panel__header">
                        <h1 class="panel__header--title">
                            {{ __("Edit multiple related items") }}
                        </h1>
                        <input class="panel__header--checked" wire:click.prevent="confirmmultiple()" type="button"
                            value="Save">
                    </div>
                    {{-- Table --}}
                    <div style="overflow: auto; position: relative; background: white">
                        <table class="table table-top">
                            <thead>
                                <tr>
                                    <th><button class="table__header--btn">Specification</button></th>
                                    <th><button class="table__header--btn">Product</button></th>
                                    <th><button class="table__header--btn">Value</button></th>
                                </tr>
                            </thead>
                        </table>
                        <table class="table" style="margin-top: 2rem">
                            <tbody>
                                @foreach ($prod as $index => $pro)
                                    <tr wire:key="spec-row-{{ $index }}">
                                        <td data-title="Name">
                                            {{ $item->name }}
                                        </td>

                                        <td data-title="Specification">
                                            @if ($pro["allow"])
                                                <div class="table__drop">
                                                    <input class="table__drop--input" wire:model.live="searchadd"
                                                        placeholder="Search..." type="text">
                                                    <ul class="table__drop--list">
                                                        @if (count($addprods) >= 1)
                                                            @foreach ($addprods as $pr)
                                                                <li class="table__drop--item"
                                                                    wire:click.prevent="selectSpec({{ $index }}, {{ $pr->id }}, '{{ $pr->name }}')">
                                                                    {{ $pr->name }}
                                                                </li>
                                                            @endforeach
                                                        @else
                                                            <li>{{ __("No product found") }}</li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @else
                                                <div wire:click.prevent="allowselect({{ $index }})"
                                                    class="table__drop--input">
                                                    @if ($pro["itemselected"])
                                                        {{ $pro["itemselected"] }}
                                                        <input type="hidden"
                                                            wire:model.defer="prod.{{ $index }}.product.name">
                                                    @else
                                                        {{ __("Select a product") }}
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        <td data-title="Value">
                                            <input type="text" required class="table__drop--input"
                                                wire:model.defer="prod.{{ $index }}.product.value">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <span class="top-up-modal delete" wire:click="closemodal">
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
            </div>
        @endif

        @if ($showrelatedprods)
            <div class="accordion__content">
                <div>
                    @if ($relatedprods && count($relatedprods) > 0)
                        {{-- delete single record --}}
                        <div class="modal" id="confirmationmodal">
                            <div class="modal-content">
                                <h1 class="modal-content-title">
                                    {{ __("Are you sure to delete this product?") }}
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
                                    {{ __("Are you sure to delete those product?") }}
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

                        {{-- Header of the table --}}
                        <div class="panel__header">
                            <input class="panel__header--input" type="text" wire:model.live="search"
                                placeholder="Search your specifications..." style="grid-column: 1/4">
                            <div class="panel__header--bundle">
                                <div class="dropdown">
                                    <button
                                        wire:click.prevent="@if ($col === false) $set('col', true) @else $set('col', false) @endif"
                                        class="dropdown-button">Columns</button>
                                    @if ($col)

                                        <div class="dropdown-list" style="display: flex;">
                                            @foreach ($columns as $column)
                                                <div class="dropdown-item">
                                                    <input type="checkbox" wire:model="selectedColumns"
                                                        value="{{ $column }}"
                                                        {{ in_array($column, $selectedColumns) ? "checked" : "" }}>
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
                                @if ($selectAll)
                                    <div class="panel__header--checked">
                                        <p>
                                            You selected <strong>{{ count($checked) }}</strong> items.
                                        </p>
                                    </div>
                                @else
                                    <div class="panel__header--checked" wire:click="selectAll">
                                        <p>
                                            You selected {{ count($checked) }} items, select all?
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Table --}}
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" wire:model="selectPage"></th>

                                    @if ($this->showColumn("Id"))
                                        <th wire:click="sortBy('id')">
                                            <button class="table__header--btn"
                                                @if ($orderBy === "id" && $orderAsc === "1") data-symbol="up"
                                            @else data-symbol="down" @endif>
                                                ID
                                                <svg>
                                                    <line x1="12" y1="5" x2="12"
                                                        y2="19"></line>
                                                    <polyline points="19 12 12 19 5 12"></polyline>
                                                </svg>
                                            </button>
                                        </th>
                                    @endif

                                    @if ($this->showColumn("Name"))
                                        <th wire:click="sortBy('name')">
                                            <button class="table__header--btn"
                                                @if ($orderBy === "name" && $orderAsc === "1") data-symbol="up"
                                            @else data-symbol="down" @endif>
                                                Name
                                                <svg>
                                                    <line x1="12" y1="5" x2="12"
                                                        y2="19"></line>
                                                    <polyline points="19 12 12 19 5 12"></polyline>
                                                </svg>
                                            </button>
                                        </th>
                                    @endif

                                    @if ($this->showColumn("Unit"))
                                        <th>
                                            <button class="table__header--btn">
                                                Unit
                                            </button>
                                        </th>
                                    @endif
                                    @if ($this->showColumn("Value"))
                                        <th>
                                            <button class="table__header--btn">
                                                Value
                                            </button>
                                        </th>
                                    @endif

                                    @if ($this->showColumn("Created At"))
                                        <th wire:click="sortBy('created_at')">
                                            <button class="table__header--btn"
                                                @if ($orderBy === "created_at" && $orderAsc === "1") data-symbol="up"
                                            @else data-symbol="down" @endif>
                                                Created at
                                                <svg>
                                                    <line x1="12" y1="5" x2="12"
                                                        y2="19"></line>
                                                    <polyline points="19 12 12 19 5 12"></polyline>
                                                </svg>
                                            </button>
                                        </th>
                                    @endif
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($relatedprods as $index => $prod)
                                    <tr class="@if ($this->isChecked($prod->id)) table__row--selected @endif">
                                        <td data-title="Check">
                                            <input type="checkbox" value="{{ $prod->id }}" wire:model="checked">
                                        </td>

                                        @if ($this->showColumn("Id"))
                                            <td data-title="ID">{{ $prod->id }}</td>
                                        @endif

                                        @if ($this->showColumn("Name"))
                                            <td data-title="Name">
                                                @if ($editedrow !== $index)
                                                    <div
                                                        wire:click.prevent="editprod({{ $prod->id }}, {{ $prod->product->id }}, {{ $index }})">
                                                        {{ $prod->product->name }}

                                                    </div>
                                                @else
                                                    @if ($allow)
                                                        <div class="table__drop">
                                                            <input class="table__drop--input"
                                                                wire:model.live="searchadd" placeholder="Search.."
                                                                type="text">
                                                            <ul class="table__drop--list">
                                                                @foreach ($addprods as $pr)
                                                                    <li class="table__drop--item"
                                                                        wire:click.prevent="select({{ $pr->id }})">
                                                                        {{ $pr->name }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                            </span>
                                                        </div>
                                                    @else
                                                        <button wire:click.prevent="allow" class="table__drop--input">
                                                            {{ $itemselected }}
                                                        </button>
                                                    @endif
                                                @endif
                                                {{-- <a href="/show_product/{{ $categori->category->id }}'">{{ $categori->category->name }}</a> --}}
                                            </td>
                                        @endif

                                        @if ($this->showColumn("Unit"))
                                            <td data-title="Unit">
                                                {{ $prod->spec->um }}
                                            </td>
                                        @endif

                                        @if ($this->showColumn("Value"))
                                            <td
                                                wire:click.prevent="editspec({{ $prod->id }}, {{ $prod->product->id }}, {{ $index }})">
                                                @if ($editedrow !== $index)
                                                    {{ $prod->value }}
                                                @else
                                                    <input type="text" required class="table__edit"
                                                        wire:model="specification.{{ $index }}.value">
                                                @endif
                                            </td>
                                        @endif

                                        @if ($this->showColumn("Created At"))
                                            <td data-title="Created At">
                                                <div class="table__time">
                                                    <svg>
                                                        <circle cx="12" cy="12" r="10">
                                                        </circle>
                                                        <polyline points="12 6 12 12 16 14"></polyline>
                                                    </svg>
                                                    {{ $prod->created_at }}
                                            </td>
                                        @endif

                                        <td data-title="Action">
                                            <div class="table__buttons">
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
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path
                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                            </path>
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
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No Specs Related</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
