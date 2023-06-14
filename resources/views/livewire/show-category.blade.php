<div>
  <div class="item__header">
    <h1 class="item__header-title" id="title">Category - {{ $category->name }}</h1>
    <div class="item__header-buttons">
        <a class="item__header-btn" href="{{ route('category') }}">Back</a>
        <a class="item__header-btn" href="{{ route('newcategory') }}">New</a>
        <input class="item__header-btn" type="button" value="Edit" name="edit" id="edit">
        <input class="item__header-btn delete" type="button" value="Delete" name="delete" id="deletecat">
    </div>
</div>
    {{-- adding tab-bar --}}
    <div class="tab ">
      <button class="tablinks item__header-btn" onclick="opentab(event, 'Details')" id="defaultOpen">Details</button>
      <button class="tablinks item__header-btn" onclick="opentab(event, 'Releated')">Releated</button>
  </div>

  <div class="tabcontent br-xs" id="Details">
      {{-- Item Form --}}
      <form class="item__form" action="{{ route('category_update', $category->id) }}" method="POST"
          enctype="multipart/form-data">
          @csrf
          {{-- content --}}
          <input class="item__form-btn item__form-long" type="submit" style="display: none" value="Update"
              id="Update">

          <div class="item__form-input-close">
              <div id="category_name">{{ $category->name }}</div>
              <span>Category Name</span>
          </div>
          <div class="item__form-input-close">
              <div id="category_parrent">{{ $category->parrent }}</div>
              <span>Category Parrent</span>
          </div>
          <div class="item__form-input-close">
              <div id="category_start_date">{{ $category->start_date }}</div>
              <span for="start_date">Category Start Date</span>
          </div>
          <div class="item__form-input-close">
              <div id="category_end_date">{{ $category->end_date }}</div>
              <span>Category End Date </span>
              <input type="hidden" name="hidden_id" value="{{ $category->id }}" id="hidden_id">
          </div>
          <div class="item__form-input-close">
              <div id="category_sequence">{{ $category->sequence }}</div>
              <span>Category Sequence</span>
          </div>
          <div class="item__form-input-close item__form-long">
              <div id="category_short_description">{{ $category->short_description }}</div>
              <span>Category Short Description</span>
          </div>
          <div class="item__form-input-close item__form-long">
              <div id="category_long_description">{{ $category->long_description }}</div>
              <span>Category Long Description</span>
          </div>
          <div class="item__form-input-close item__form-long">
              <div id="seo_title">{{ $category->seo_title }}</div>
              <span>SEO Title</span>
          </div>
          <div class="item__form-close">
              <div>{{ $category->created_at }}</div>
              <span>Create date / time</span>
          </div>
          <div class="item__form-close">
              <div>{{ $category->createdby }}</div>
              <span>Create by</span>
          </div>
          <div class="item__form-close">
              <div>{{ $category->updated_at }}</div>
              <span>Updated date / time</span>
          </div>
          <div class="item__form-close">
              <div>{{ $category->lastmodifiedby }}</div>
              <span>Last modified by</span>
          </div>
      </form>
  </div>

  <div class="tabcontent" id="Releated">
{{-- related media --}}

      <livewire:related-media-category categoryId="{{ $category->id }}" />
      {{-- related media end --}}
      {{-- related products --}}
      <div class="releated display-n mt-1 wid-10 talign-c br-xs">

          <button class="collapsible"><Span> {{ __('Products ') }}</Span></button>


          <div class="contenttabb" id="contentDivp">
              <div class="col-12-xs col-12-sm col-12-xl mt-1 talign-c">
                  <form id="deleteProductsForm" action="{{ route('deleteSelectedProducts') }}" method="POST">
                      @csrf
                      <input type="submit" id="deletefromcheckbox" class="delete float-r mb-1"
                          style="display: none" value="">
                  </form>
                  <button id="addProductButton" class="add float-r mr-1 mb-1"> Add new product</button>
              </div>
              <div class="col-12-xs col-12-sm col-12-xl mt-1  display-b talign-c">
                  <form id="addProductsForm" class="mb-1 p-1" style="display: none"
                      action="{{ route('addSelectedProducts', $category->id) }}" method="POST">
                      @csrf
                      <input type="hidden" name="productIdsadd">
                      <button class="delete float-l" id="calceladdproducts"> Cancel</button>
                      <input type="submit" id="addfromcheckbox" class="add float-r" style="display: none"
                          value="">
                  </form>
                  <div id="tableContainerproducts" class="wid-10 bg-bg-light-9 p-1 mt-3 br-xs"
                      style="display: none"></div>


              </div>
              {{-- @if ($products->first() != null)
                  <div class="col-12-xs col-12-sm col-12-xl mt-1 pt-1 align-center">

                      <table id="productsTable">
                          <thead>
                              <tr>
                                  <th></th>
                                  <th>Name</th>
                                  <th>Status</th>
                                  <th>Quantity</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($products as $product)
                                  <tr>
                                      <td><input type="checkbox" class="product-checkbox"
                                              value="{{ $product->product->id }}"></td>
                                      <!-- Add checkbox column -->
                                      <td><a href="/show_product/{{ $product->product->id }}"
                                              class="link-name">{{ $product->product->name }}</a></td>
                                      <td>{{ $product->product->product_status }}</td>
                                      <td>{{ $product->product->quantity }}</td>
                                      <td><button class="cursor-p" type="button"
                                              onclick="removeProduct({{ $product->product->id }})">x</button>
                                      </td>
                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
              @else --}}
                  <div class="col-12-xs col-12-sm col-12-xl mt-1 talign-c">
                      <span class="mt-4 m-a display-b text-bg wid-7 p-1 br-xs mb-2 bg-bg-light-9">No products
                          related</span>
                  </div>
              {{-- @endif --}}
          </div>
      </div>
       {{-- related products --}}
  </div>
</div>
