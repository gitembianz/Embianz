<div class="item">
    <div class="item__form">
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

        {{-- If you want to delete, its message --}}
        @if ($checked)
            <div class="dropdown">
                <span class="dropdown-name">With Checked ({{ count($checked) }})</span>

                <div class="dropdown-content">
                    <button class="dropdown-item delete" type="button"
                        onclick="confirm('Are you sure you want to delete these Records?') || event.stopImmediatePropagation()"
                        wire:click="deleteRecords()">
                        Delete
                    </button>
                    <button class="dropdown-item submit" type="button"
                        onclick="confirm('Are you sure you want to export these Records?') || event.stopImmediatePropagation()"
                        wire:click="exportSelected()">
                        Export
                    </button>
                </div>
            </div>
        @endif
    </div>

    @if ($selectPage)
    <div class="pt-2 talign-c">

      @if ($selectAll)
        <div>
            You have selected all <strong>{{ count($checked) }}</strong> items.
        </div>
        @else
        <div>
            You have selected <strong>{{ count($checked) }}</strong> items, Do you want to Select All?
            <a href="#" class="ml-2" wire:click="selectAll">Select All</a>
        </div>
        @endif

    </div>
    @endif


    {{-- Livewire Table --}}
    <table class="livewire-table">

        <thead>
            <tr>
                <th>Check<input type="checkbox" wire:model="selectPage"></th>
                <th class="cursor-p" wire:click="sortBy('id')">ID
                </th>
                <th class="cursor-p" wire:click="sortBy('name')">Name
                </th>
                <th class="cursor-p" wire:click="sortBy('short_description')">Short Description
                </th>
                <th class="cursor-p" wire:click="sortBy('created_at')">Created At
                </th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr class="@if ($this->isChecked($product->id))
                  th_checked
              @endif">
                    <td data-title="Check"><input type="checkbox" value="{{ $product->id }}" wire:model="checked"></td>
                    <td data-title="ID">{{ $product->id }}</td>
                    <td data-title="Name"><a href="/show_product/{{ $product->id }}'">{{ $product->name }}</a></td>
                    <td data-title="Short Description">{{ $product->short_description }}</td>
                    <td data-title="Created At">{{ $product->created_at }}</td>
                    <td data-title="Action">
                        <button class="delete"
                            onclick="confirm('Are you sure you want to delete this record?') || event.stopImmediatePropagation()"
                            wire:click="deleteSingleRecord({{ $product->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                fill="none" stroke="#BBFCDE" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="">{{ $products->links() }}</div>
</div>
