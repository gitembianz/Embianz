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
  </div>
  <table class="wid-10 mt-1 p-1">
      <thead>
          <tr>
              <th class="wid-1">ID</th>
              <th class="wid-2">Name</th>
              <th class="wid-3">Short Description</th>
              <th class="wid-2">Created At</th>
          </tr>
      </thead>
      <tbody>
          @foreach($products as $product)
              <tr>
                  <td class="wid-1">{{ $product->id }}</td>
                  <td class="wid-2"><a href="/show_product/{{ $product->id }}'" class="link-name">{{ $product->name }}</a></td>
                  <td class="wid-3">{{ $product->short_description }}</td>
                  <td class="wid-2">{{ $product->created_at }}</td>
              </tr>
          @endforeach
      </tbody>
  </table>
   <div class="">{!! $products->links() !!}</div>
</div>
