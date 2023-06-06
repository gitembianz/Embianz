<div>
  <div class="wid-5 display-f m-a jus-sb">
      <div class="">
          <input wire:model.debounce.200ms="search" type="text" placeholder="Search product...">
      </div>
      <div class="">
        <label for="orderBy"> Order by:</label>
          <select id="orderBy" wire:model="orderBy">
              <option value="id">ID</option>
              <option value="name">Name</option>
              <option value="short_description">Short Description</option>
              <option value="created_at">Created At Date</option>
          </select>
          <label for="orderAsc"> Order direction: </label>
          <select id="orderAsc" wire:model="orderAsc">
            <option value="1">Ascending</option>
            <option value="0">Descending</option>
        </select>
      </div>

      <div class="">
        <label for="perPage"> Per Page : </label>
          <select id="perPage" wire:model="perPage">
              <option>10</option>
              <option>25</option>
              <option>50</option>
              <option>100</option>
          </select>
      </div>
      <div>
        @if ($checked)
        <div class="dropdown ml-4">
            <button class="" data-toggle="dropdown">With Checked({{ count($checked) }})</button>
            <div class="dropdown-content">
                <a href="#" class="dropdown-item" type="button"
                    onclick="confirm('Are you sure you want to delete these Records?') || event.stopImmediatePropagation()"
                    wire:click="deleteRecords()">
                    Delete
                </a>
                <a href="#" class="dropdown-content" type="button"
                    onclick="confirm('Are you sure you want to export these Records?') || event.stopImmediatePropagation()"
                    wire:click="exportSelected()">
                    Export
                </a>

            </div>
        </div>
        @endif
    </div>
  </div>
  <table class="wid-10 mt-1 p-1">
      <thead>
          <tr>
            <th><input type="checkbox" wire:model="selectPage"></th>
              <th class="wid-1">ID</th>
              <th class="wid-2">Name</th>
              <th class="wid-3">Short Description</th>
              <th class="wid-2">Created At</th>
              <th class="wid-2">Action</th>
          </tr>
      </thead>
      <tbody>
          @foreach($products as $product)
              <tr >
                <td><input type="checkbox" value="{{ $product->id }}" wire:model="checked"></td>
                  <td class="wid-1">{{ $product->id }}</td>
                  <td class="wid-2"><a href="/show_product/{{ $product->id }}'" class="link-name">{{ $product->name }}</a></td>
                  <td class="wid-3">{{ $product->short_description }}</td>
                  <td class="wid-2">{{ $product->created_at }}</td>
                  <td>
                    <button class="btn btn-danger btn-sm"
                        onclick="confirm('Are you sure you want to delete this record?') || event.stopImmediatePropagation()"
                        wire:click="deleteSingleRecord({{ $product->id }})">x</button>
                </td>
              </tr>
          @endforeach
      </tbody>
  </table>
   <div class="">{{ $products->links() }}</div>
</div>
