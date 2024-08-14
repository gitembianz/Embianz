<div class="accordion @if ($showrelatedadd) active @endif">
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


 {{-- Accordion Header --}}
 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showrelatedadd) button--secondary active @endif"
   wire:click.prevent="@if ($showrelatedadd === false) $set('showrelatedadd', true) @else $set('showrelatedadd', false) @endif">
   {{ __('Addresses ') }}({{ $account->addresses()->count() }})
   <svg>
    <polyline points="6 9 12 15 18 9"></polyline>
   </svg>
  </button>
  <!-- <button class="button button--secondary">
        <svg>
     <line x1="12" y1="5" x2="12" y2="19"></line>
     <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    </button> -->
 </div>


 {{-- Accordion Body --}}
 <div class="accordion__body">
  {{-- Navigation --}}
  <nav class="nav--controls">
   {{-- Search Input --}}
   <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
   {{-- IF CHECKED --}}
   <div class="dropdown dropdown--right" @if (!$checked) style="display:none;" @endif>
    {{-- Dropdown Button --}}
    <button class="button button--primary button--centered button--long" tooltip="Actions with checked" tooltip-top>
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
   <div class="dropdown dropdown--right" wire:ignore>
    {{-- Dropdown Button --}}
    <button class="button button--primary button--centered" tooltip="Show items in table" tooltip-left>
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
        <input type="checkbox" wire:model="selectedColumns" value="{{ $column }}"
         {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
        <span>{{ $column }}</span>
       </label>
      @endforeach
     </div>
    </div>
   </div>
  </nav>


  {{-- Select All? --}}
  @if ($selectPage && $selectAll)
   <button class="button button--fill button--primary">
    You selected {{ count($checked) }} items.
   </button>
  @elseif($selectPage)
   <button class="button button--fill button--secondary" wire:click="selectAll">
    You selected {{ count($checked) }} items, select all?
   </button>
  @endif


  {{-- Table --}}
  <table class="expandable-table">
   <thead>
    <tr>
     <th style="border-right: none; border-left: none;">
      <div class="checkbox--primary">
       <input type="checkbox" id="selectPage8" wire:model="selectPage" />
       <label for="selectPage8"></label>
      </div>
     </th>
     @if ($this->showColumn('Id'))
      <th>
       <button wire:click="sortBy('id')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        ID
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Type'))
      <th>
       <button wire:click="sortBy('type')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Type
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('First Name'))
      <th>
       <button wire:click="sortBy('first_name')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        First Name
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Last Name'))
      <th>
       <button wire:click="sortBy('last_name')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Last Name
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif

     @if ($this->showColumn('Phone'))
      <th class="hidden">
       <button wire:click="sortBy('phone')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Phone
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Email'))
      <th class="hidden">
       <button wire:click="sortBy('email')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Email
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Address'))
      <th class="hidden">
       <button wire:click="sortBy('address1')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Address
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Optional Address'))
      <th class="hidden">
       <button wire:click="sortBy('address2')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Optional Address
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Country'))
      <th class="hidden">
       <button wire:click="sortBy('country')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Country
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('County'))
      <th class="hidden">
       <button wire:click="sortBy('county')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        County
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('City'))
      <th class="hidden">
       <button wire:click="sortBy('city')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        City
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Post Code'))
      <th class="hidden">
       <button wire:click="sortBy('zipcode')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Post Code
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     @if ($this->showColumn('Created At'))
      <th class="hidden">
       <button wire:click="sortBy('created_at')" class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
        Created At
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </th>
     @endif
     <th style="border-left: none; border-right: none;">
      <div style="display: flex;">
       <button class="button button--secondary button--sm" style="opacity: 0">
        <svg>
         <polyline points="3 6 5 6 21 6"></polyline>
         <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        </svg>
       </button>
       <button class="button button--secondary button--sm" style="opacity: 0">
        <svg>
         <polyline points="3 6 5 6 21 6"></polyline>
         <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        </svg>
       </button>
      </div>
     </th>
    </tr>
   </thead>
   <tbody>
    @php
     $i = 0;
    @endphp
    @if ($addresses->isEmpty())
     <tr>
      <td class="table--empty" colspan="{{ count($columns) + 2 }}">No record found.</td>
     </tr>
    @else
     @foreach ($addresses as $index => $address)
      <tr @if ($loop->last) id="last_record" @endif
       class="expandable-row @if ($this->isChecked($address->id)) active @endif">
       <td style="border-left: none" data-title="Check">
        <div class="checkbox--primary">
         <input type="checkbox" value="{{ $address->id }}" id="{{ $address->id }}" wire:model="checked">
         <label for="{{ $address->id }}"></label>
        </div>
       </td>
       @if ($this->showColumn('Id'))
        <td wire:click="expandRow({{ $index }})">
         {{ $address->id }}
        </td>
       @endif
       @if ($this->showColumn('Type'))
        <td wire:click="expandRow({{ $index }})">
         {{ $address->type }}
        </td>
       @endif
       @if ($this->showColumn('First Name'))
        <td wire:click="expandRow({{ $index }})">
         @if ($editindex !== $index)
          {{ $address->first_name }}
         @else
          <input type="text" required class="input" wire:model.defer="adress.{{ $index }}.first_name">
         @endif
        </td>
       @endif
       @if ($this->showColumn('Last Name'))
        <td wire:click="expandRow({{ $index }})">
         @if ($editindex !== $index)
          {{ $address->last_name }}
         @else
          <input type="text" required class="input" wire:model.defer="adress.{{ $index }}.last_name">
         @endif
        </td>
       @endif
       @if ($this->showColumn('Phone'))
        <td wire:click="expandRow({{ $index }})" class="hidden">
         @if ($editindex !== $index)
          {{ $address->phone }}
         @else
          <input type="phone" required class="input" wire:model.defer="adress.{{ $index }}.phone">
         @endif
        </td>
       @endif
       @if ($this->showColumn('Email'))
        <td wire:click="expandRow({{ $index }})" class="hidden">
         @if ($editindex !== $index)
          {{ $address->email }}
         @else
          <input type="email" required class="input" wire:model.defer="adress.{{ $index }}.email">
         @endif
        </td>
       @endif
       @if ($this->showColumn('Address'))
        <td wire:click="expandRow({{ $index }})" class="hidden">
         @if ($editindex !== $index)
          {{ $address->address1 }}
         @else
          <input type="text" required class="input" wire:model.defer="adress.{{ $index }}.address1">
         @endif
        </td>
       @endif
       @if ($this->showColumn('Optional Address'))
        <td wire:click="expandRow({{ $index }})" class="hidden">
         @if ($editindex !== $index)
          {{ $address->address2 }}
         @else
          <input type="text" required class="input" wire:model.defer="adress.{{ $index }}.address2">
         @endif
        </td>
       @endif
       @if ($this->showColumn('Country'))
        <td wire:click="expandRow({{ $index }})" class="hidden">
         @if ($editindex !== $index)
          {{ $address->country }}
         @else
          <input type="text" required class="input" wire:model.defer="adress.{{ $index }}.country">
         @endif
        </td>
       @endif
       @if ($this->showColumn('County'))
        <td wire:click="expandRow({{ $index }})" class="hidden">
         @if ($editindex !== $index)
          {{ $address->county }}
         @else
          <input type="text" required class="input" wire:model.defer="adress.{{ $index }}.county">
         @endif
        </td>
       @endif
       @if ($this->showColumn('City'))
        <td wire:click="expandRow({{ $index }})" class="hidden">
         @if ($editindex !== $index)
          {{ $address->city }}
         @else
          <input type="text" required class="input" wire:model.defer="adress.{{ $index }}.city">
         @endif
        </td>
       @endif
       @if ($this->showColumn('Post Code'))
        <td wire:click="expandRow({{ $index }})" class="hidden">
         @if ($editindex !== $index)
          {{ $address->zipcode }}
         @else
          <input type="text" required class="input" wire:model.defer="adress.{{ $index }}.zipcode">
         @endif
        </td>
       @endif
       @if ($this->showColumn('Created At'))
        <td wire:click="expandRow({{ $index }})" class="hidden">
         {{ $address->created_at }}
        </td>
       @endif
       <td>
        @if ($editindex !== $index)
         <div style="display: flex;">
          <button class="button button--secondary button--sm"
           wire:click.prevent="edititem({{ $index }}, {{ $address->id }})">
           <svg>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
            </path>
           </svg>
          </button>
          <button wire:click.prevent="confirmItemRemoval({{ $address->id }})"
           class="button button--secondary button--sm">
           <svg>
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
           </svg>
          </button>
         </div>
        @else
         <div style="display: flex;">
          <button class="button button--secondary button--sm"
           wire:click.prevent="saveitem({{ $index }} , {{ $address->id }})">
           <svg>
            <polyline points="20 6 9 17 4 12"></polyline>
           </svg>
          </button>
          <button class="button button--secondary button--sm" wire:click.prevent="cancelitem()">
           <svg>
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
           </svg>
          </button>
         </div>
        @endif
       </td>
      </tr>
      <tr class="details-row  @if ($row === $i) active @endif">
       <td colspan="{{ count($columns) + 2 }}">
        <div class="details">
         @if ($this->showColumn('Id'))
          <p>
           <bold>Id:</bold>{{ $address->id }}
          </p>
         @endif
         @if ($this->showColumn('Type'))
          <p>
           <bold>Type:</bold>{{ $address->type }}
          </p>
         @endif
         @if ($this->showColumn('First Name'))
          <p>
           @if ($editindex !== $index)
            <bold>First Name:</bold>{{ $address->first_name }}
           @else
            <bold>First Name:</bold><input type="text" required class="input"
             wire:model.defer="adress.{{ $index }}.first_name">
           @endif
          </p>
         @endif
         @if ($this->showColumn('Last Name'))
          <p>
           @if ($editindex !== $index)
            <bold>Last Name:</bold>{{ $address->last_name }}
           @else
            <bold>Last Name:</bold><input type="text" required class="input"
             wire:model.defer="adress.{{ $index }}.last_name">
           @endif
          </p>
         @endif
         @if ($this->showColumn('Phone'))
          <p>
           @if ($editindex !== $index)
            <bold>Phone:</bold>{{ $address->phone }}
           @else
            <bold>Phone:</bold><input type="phone" required class="input"
             wire:model.defer="adress.{{ $index }}.phone">
           @endif
          </p>
         @endif
         @if ($this->showColumn('Email'))
          <p>
           @if ($editindex !== $index)
            <bold>Email:</bold>{{ $address->email }}
           @else
            <bold>Email:</bold><input type="email" required class="input"
             wire:model.defer="adress.{{ $index }}.email">
           @endif
          </p>
         @endif
         @if ($this->showColumn('Address'))
          <p>
           @if ($editindex !== $index)
            <bold>Address:</bold>{{ $address->address1 }}
           @else
            <bold>Address:</bold><input type="text" required class="input"
             wire:model.defer="adress.{{ $index }}.address1">
           @endif
          </p>
         @endif
         @if ($this->showColumn('Optional Address'))
          <p>
           @if ($editindex !== $index)
            <bold>Optional Address:</bold>{{ $address->address2 }}
           @else
            <bold>Optional Address:</bold><input type="text" required class="input"
             wire:model.defer="adress.{{ $index }}.address2">
           @endif
          </p>
         @endif
         @if ($this->showColumn('Country'))
          <p>
           @if ($editindex !== $index)
            <bold>Country:</bold>{{ $address->country }}
           @else
            <bold>Country:</bold><input type="text" required class="input"
             wire:model.defer="adress.{{ $index }}.country">
           @endif
          </p>
         @endif
         @if ($this->showColumn('County'))
          <p>
           @if ($editindex !== $index)
            <bold>County:</bold>{{ $address->county }}
           @else
            <bold>County:</bold><input type="text" required class="input"
             wire:model.defer="adress.{{ $index }}.county">
           @endif
          </p>
         @endif
         @if ($this->showColumn('City'))
          <p>
           @if ($editindex !== $index)
            <bold>City:</bold>{{ $address->city }}
           @else
            <bold>City:</bold><input type="text" required class="input"
             wire:model.defer="adress.{{ $index }}.city">
           @endif
          </p>
         @endif
         @if ($this->showColumn('Post Code'))
          <p>
           @if ($editindex !== $index)
            <bold>Post Code:</bold>{{ $address->zipcode }}
           @else
            <bold>Post Code:</bold><input type="text" required class="input"
             wire:model.defer="adress.{{ $index }}.zipcode">
           @endif
          </p>
         @endif
         @if ($this->showColumn('Created At'))
          <p>
           <bold>Created At:</bold>{{ $address->created_at }}
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


  {{-- Load More Manual --}}
  @if (count($addresses) >= 10)
   <button class="button button--secondary button--fill" style="margin-top: 10px;" wire:click="load">
    Load more
   </button>
  @endif
 </div>
</div>
