<div>
  <div class="item__header">
    <h1 class="item__header-title" id="title">Product- {{ $product->name }}</h1>
    <div class="item__header-buttons">
        <a class="item__header-btn" href="{{ route('products') }}">Back</a>
        <a class="item__header-btn" href="{{ route('add_product') }}">New</a>
        @if ($editproduct === null)

        <input class="item__header-btn" type="button" value="Edit" wire:click.prevent="editproduct()">
        @else
        <input class="item__header-btn" type="button" wire:click.prevent="saveproduct()" value="Save">
        <input class="item__header-btn" type="button" wire:click.prevent="cancelproduct()" value="Cancel">
        @endif
        <input wire:click.prevent="confirmProductRemoval({{ $product->id }})" class="item__header-btn delete" type="button" value="Delete" name="delete">
    </div>
</div>

<div class="modal" id="confirmationmodalitem">
  <div class="modal-content">
      <h1 class="modal-content-title">
          {{ __('Are you sure to delete this product?') }}
      </h1>
      <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button"
          value="Confirm">
      <input class="modal-content-btn delete" type="button"
          onclick="document.getElementById('confirmationmodalitem').style.display='none'" value="Cancel">

      <span class="modal-content-btn delete"
          onclick="document.getElementById('confirmationmodalitem').style.display='none'">
          <svg>
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
      </span>
  </div>
</div>

<div class="tab">
  <div class="tabs">
      <h3 class="tabs__page active">Details</h3>
      <h3 class="tabs__page">Related</h3>
  </div>
  <div class="tab__list">
      <div class="tabs__content active" id="Details">
          <form class="item__form" action="{{ route('category_update', $product->id) }}" method="POST"
              enctype="multipart/form-data">
              @csrf
              <input class="item__form-btn item__form-long" type="submit" style="display: none" value="Update"
                  id="Update">

              <div class="item__form-input-close">
                  <div id="category_name">{{ $product->name }}</div>
                  <span>Product Name</span>
              </div>
              <div class="item__form-input-close">
                  <div id="category_parrent">{{ $product->product_status }}</div>
                  <span>Product Status</span>
              </div>
              <div class="item__form-input-close">
                  <div id="category_start_date">{{ $product->start_date }}</div>
                  <span for="start_date">Product Start Date</span>
              </div>
              <div class="item__form-input-close">
                  <div id="category_end_date">{{ $product->end_date }}</div>
                  <span>Product End Date </span>
              </div>
              <div class="item__form-input-close">
                  <div id="category_sequence">{{ $product->quantity }}</div>
                  <span>Product Quantity</span>
              </div>
              <div class="item__form-input-close item__form-long">
                  <div id="category_short_description">{{ $product->short_description }}</div>
                  <span>Product Short Description</span>
              </div>
              <div class="item__form-input-close item__form-long">
                  <div id="category_long_description">{{ $product->long_description }}</div>
                  <span>Product Long Description</span>
              </div>
              <div class="item__form-input-close item__form-long">
                  <div id="seo_title">{{ $product->seo_title }}</div>
                  <span>SEO Title</span>
              </div>
              <div class="item__form-close">
                  <div>{{ $product->created_at }}</div>
                  <span>Create date / time</span>
              </div>
              <div class="item__form-close">
                  <div>{{ $product->created_by }}</div>
                  <span>Create by</span>
              </div>
              <div class="item__form-close">
                  <div>{{ $product->updated_at }}</div>
                  <span>Updated date / time</span>
              </div>
              <div class="item__form-close">
                  <div>{{ $product->last_modified_by }}</div>
                  <span>Last modified by</span>
              </div>
          </form>
      </div>
      <div class="tabs__content display-f g-1">
          {{-- <livewire:related-media-category categoryId="{{ $category->id }}" /> --}}

          {{-- <livewire:related-product-category categoryId="{{ $category->id }}" /> --}}
      </div>

</div>
</div>

</div>
