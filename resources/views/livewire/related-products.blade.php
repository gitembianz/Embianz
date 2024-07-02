
<div class="accordion @if ($showrelated) active @endif">
  {{-- ASIDES --}}
      {{-- Delete Record || Delete Records --}}
      <aside>
        <div class="background background--center @if($single || $multiple) active @endif"></div>
        <div class="aside aside--confirm @if($single || $multiple) active @endif">
          <span>
            @if($single)
            Are you sure to delete this record?
            @else
            Are you sure to delete those records?
            @endif
          </span>
          @if($single)
          <button class="button button--primary button--long" wire:click="deleteSingleRecord()">
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


  {{-- Accordion Header --}}
    <div class="accordion__header">
      <button class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif" wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">
        {{ __('Related Products ') }}({{ $product->related_product()->count() }})
        <svg><polyline points="6 9 12 15 18 9"></polyline></svg>
      </button>
      <button class="button button--secondary" wire:click.prevent="toggleTable()">
          <svg>
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
      </button>
    </div>


  {{-- Accordion Body --}}
    <div class="accordion__body">
      {{-- Navigation --}}
      <nav class="nav--controls">
        {{-- Search Input --}}
        <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
        {{-- IF CHECKED --}}
        <div class="dropdown dropdown--right" @if (!$checked) style="display:none;" @endif >
          {{-- Dropdown Button --}}
          <button class="button button--secondary button--centered button--long" tooltip="Actions with checked" tooltip-top >
            <span>With Checked({{ count($checked) }})</span>
          </button>
          {{-- Dropdown Content --}}
          <div class="dropdown__content">
            <div class="dropdown__container">
              <button class="button button--primary button--long" wire:click="confirmItemsRemoval()">
                Delete
              </button>
            </div>
          </div>
        </div>
        {{-- Visible Dropdown --}}
        <div class="dropdown dropdown--right" id="visible__dropdown">
          {{-- Dropdown Button --}}
          <button class="button button--secondary button--centered" tooltip="Show items in table" tooltip-left id="visible__open" >
            <svg>
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
              <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
            </svg>
          </button>
          {{-- Dropdown Content --}}
          <div class="dropdown__content">
            <div class="dropdown__container">
              @foreach ($columns as $column)
                <label class="switch switch--primary inline">
                  <input type="checkbox" wire:model="selectedColumns" value="{{ $column }}" {{ in_array($column, $selectedColumns) ? "checked" : "" }}/>
                  <span>{{ $column}}</span>
                </label>
              @endforeach
            </div>
          </div>
        </div>
      </nav>


      {{-- Table --}}
      <table class="expandable-table">
        <thead>
          <tr>
            <th>
              <label class="checkbox checkbox--secondary inline">
                <input type="checkbox" wire:model="selectPage" />
                <span></span>
              </label>
            </th>
            @if ($this->showColumn('Id'))
              <th>
              <button class="table--btn @if ($orderBy === 'id' && $orderAsc === '1') active @endif" wire:click="sortBy('id')">
                ID
                <svg><polyline points="6 9 12 15 18 9"></polyline></svg>
              </button>
              </th>
            @endif
            @if ($this->showColumn('Name'))
              <th>
              <button class="table--btn @if ($orderBy === 'name' && $orderAsc === '1') active @endif" wire:click="sortBy('name')">
                Name
                <svg><polyline points="6 9 12 15 18 9"></polyline></svg>
              </button>
              </th>
            @endif
            @if ($this->showColumn('Short Description'))
              <th class="hidden">
              <button class="table--btn @if ($orderBy === 'short_description' && $orderAsc === '1') active @endif" wire:click="sortBy('short_description')">
                Description
                <svg><polyline points="6 9 12 15 18 9"></polyline></svg>
              </button>
              </th>
            @endif
            @if ($this->showColumn('Created At'))
              <th class="hidden">
              <button class="table--btn @if ($orderBy === 'created_at' && $orderAsc === '1') active @endif" wire:click="sortBy('created_at')">
                Created at
                <svg><polyline points="6 9 12 15 18 9"></polyline></svg>
              </button>
              </th>
            @endif
            <th>
              <button class="button button--secondary button--sm" style="opacity: 0">
                <svg>
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
              </button>
            </th>
          </tr>
        </thead>
        <tbody>
          @php
            $i = 0;
          @endphp
          @if ($relatedproducts->isEmpty())
            <tr>
              <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
            </tr>
          @else
            @foreach ($relatedproducts as $index => $relatedproduct)
              <tr @if ($loop->last) id="last_record" @endif class="expandable-row @if ($this->isChecked($relatedproduct->id)) active @endif">
                <td>
                  <label class="checkbox checkbox--secondary inline">
                    <input type="checkbox" value="{{ $relatedproduct->id }}" wire:model="checked">
                    <span></span>
                  </label>
                </td>

                @if ($this->showColumn('Id'))
                  <td wire:click="expandRow({{ $index }})">
                    {{ $relatedproduct->id }}
                  </td>
                @endif
                @if ($this->showColumn('Name'))
                  <td>
                    <a href="{{ route("show_product", ['id'=> $relatedproduct->product_id ])}}">
                      {{ $relatedproduct->product->name }}
                    </a>
                  </td>
                @endif
                @if ($this->showColumn('Short Description'))
                  <td wire:click="expandRow({{ $index }})" class="hidden">
                    {{ $relatedproduct->product->short_description }}
                  </td>
                @endif
                @if ($this->showColumn('Created At'))
                  <td wire:click="expandRow({{ $index }})" class="hidden">
                    {{ $relatedproduct->created_at }}
                  </td>
                @endif

                <td>
                  <button class="button button--secondary button--sm" wire:click.prevent="confirmItemRemoval({{ $relatedproduct->id }})">
                    <svg>
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                    </path>
                    </svg>
                  </button>
                </td>
              </tr>

              <tr class="details-row  @if ($row === $i) active @endif">
                <td colspan="17">
                  <div class="details">
                    @if ($this->showColumn('Id'))
                      <p>
                        <bold>Id</bold>
                        {{ $relatedproduct->id }}
                      </p>
                    @endif
                    @if ($this->showColumn('Name'))
                      <p>
                        <bold>Name</bold>
                        <a href="{{ route("show_product", ['id'=> $relatedproduct->product_id ])}}">
                          {{ $relatedproduct->product->name }}
                        </a>
                      </p>
                    @endif
                    @if ($this->showColumn('Short Description'))
                      <p>
                        <bold>Short Description</bold>
                        {{ $relatedproduct->product->short_description }}
                      </p>
                    @endif
                    @if ($this->showColumn('Created At'))
                      <p>
                        <bold>Created At</bold>
                        {{ $relatedproduct->created_at }}
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
    </div>
</div>





