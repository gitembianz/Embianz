<section class="content">
 {{-- X-Components --}}
 <x-alert />


 {{-- Delete Record --}}
 <aside>
  <div class="background background--center @if ($delete) active @endif"></div>
  <div class="aside aside--confirm @if ($delete) active @endif">
   <span>
    Are you sure to delete this record?
   </span>
   <button class="button button--primary button--long" wire:click.prevent="deleteRecord()">
    <span>Delete</span>
   </button>
   <button class="button button--danger button--long" wire:click.prevent="cancelItemRemoval()">
    <span>Cancel</span>
   </button>
  </div>
 </aside>



 {{-- Navigation --}}
 <nav class="nav--controls">
  <h1 class="table--name">Product: {{ $product->name }}</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to all products" tooltip-top
   href="{{ route('all_products') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <a class="button button--primary button--centered" tooltip="Create new Product" tooltip-top
   href="{{ route('add_product') }}">
   <svg>
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
    <polyline points="14 2 14 8 20 8"></polyline>
    <line x1="12" y1="18" x2="12" y2="12"></line>
    <line x1="9" y1="15" x2="15" y2="15"></line>
   </svg>
  </a>
  @if ($editproduct === null)
   <button class="button button--primary button--centered" tooltip="Edit this Product" tooltip-left
    wire:click.prevent="editproduct()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
     <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
     <path d="M16 5l3 3" />
    </svg>
   </button>
  @else
   <button class="button button--primary button--centered" tooltip="Save Edit" tooltip-left
    wire:click.prevent="saveproduct()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
     <path d="M9 15l2 2l4 -4" />
    </svg>
   </button>
   <button class="button button--primary button--centered" tooltip="Cancel edit" tooltip-left
    wire:click.prevent="cancelproduct()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
     <path d="M10 12l4 5" />
     <path d="M10 17l4 -5" />
    </svg>
   </button>
  @endif
  <button class="button button--primary button--centered" tooltip="Delete this Product" tooltip-left
   wire:click.prevent="confirmProductRemoval({{ $product->id }})">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 14l6 0" />
   </svg>
  </button>
 </nav>



 {{-- Tabs Header --}}
 <nav class="nav--tabs">
  <button class="button button--primary button--long button--active" id="detailsButton">
   Details
  </button>
  <button class="button button--primary button--long" id="relatedButton">
   Related
  </button>
 </nav>


 {{-- Tabs Body (Details) --}}
 <form style="height: calc(100% - 107.5px);" class="tabs__content details__view active" id="detailsContent">
  {{-- Product Name --}}
  <div class="input__tabs">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->name }}</span>
   @else
    <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.product_name" required>
   @endif
   <label for="product__name">Name</label>
  </div>



  {{-- Product Active && IsNew --}}
  <div class="details__checkboxes">
   {{-- Product Active --}}
   <div class="checkbox__details ">
    @if ($editproduct === null)
     @if ($product->active)
      <input type="checkbox" id="active1" checked class="disabled" disabled />
      <label for="active1" class="disabled">Active</label>
     @else
      <input type="checkbox" id="active2" class="disabled" disabled />
      <label for="active2" class="disabled">Active</label>
     @endif
    @else
     <input type="checkbox" id="active3" wire:model.defer="prod.active" />
     <label for="active3">Active</label>
    @endif
   </div>
   <div class="checkbox__details">
    @if ($editproduct === null)
     @if ($product->is_new)
      <input type="checkbox" id="isNew1" checked class="disabled" disabled />
      <label for="isNew1" class="disabled">Is New</label>
     @else
      <input type="checkbox" id="isNew2" class="disabled" disabled />
      <label for="isNew2" class="disabled">Is New</label>
     @endif
    @else
     <input type="checkbox" id="isNew3" wire:model.defer="prod.is_new" />
     <label for="isNew3">Is New</label>
    @endif
   </div>
   {{-- Product IsNew --}}
  </div>
  {{-- Product Brand --}}
  <div class="input__tabs">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->brand }}</span>
   @else
    <input type="text" placeholder=" " name="product__brand" wire:model.defer="prod.brand" required>
   @endif
   <label for="product__name">Brand</label>
  </div>

  {{-- Product Type --}}
  <div class="input__tabs">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->type }}</span>
   @else
    <select wire:model.defer="prod.type">
     <option value="parent">parent</option>
     <option value="standard">standard</option>
     <option value="variant">variant</option>
    </select>
   @endif
   <label for="product__name">Type</label>
  </div>

  {{-- Product Quantity --}}
  <div class="input__tabs">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->quantity }}</span>
   @else
    <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.quantity" required>
   @endif
   <label for="product__name">Quantity</label>
  </div>

  {{-- Product Popularity --}}
  <div class="input__tabs">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->popularity }}</span>
   @else
    <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.popularity" required>
   @endif
   <label for="product__name">Popularity</label>
  </div>
  <div class="details__checkboxes">
   {{-- Product SKU --}}
   <div class="input__tabs">
    @if ($editproduct === null)
     <span class="disabled">{{ $product->sku }}</span>
    @else
     <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.sku" required>
    @endif
    <label for="product__name">SKU</label>
   </div>

   {{-- Product EAN --}}
   <div class="input__tabs">
    @if ($editproduct === null)
     <span class="disabled">{{ $product->ean }}</span>
    @else
     <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.ean" required>
    @endif
    <label for="product__name">EAN</label>
   </div>
  </div>

  {{-- Product Short Description --}}
  <div class="input__tabs">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->short_description }}</span>
   @else
    <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.short_description" required>
   @endif
   <label for="product__name">Short Description</label>
  </div>

  {{-- Product Start Date --}}
  <div class="input__tabs">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->start_date }}</span>
   @else
    <input type="date" placeholder=" " name="product__name" wire:model.defer="prod.start_date" required>
   @endif
   <label for="product__name">Start Date</label>
  </div>

  {{-- Product End Date --}}
  <div class="input__tabs">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->end_date }}</span>
   @else
    <input type="date" placeholder=" " name="product__name" wire:model.defer="prod.end_date" required>
   @endif
   <label for="product__name">End Date</label>
  </div>

  {{-- Product Meta Description --}}
  <div class="input__tabs details__long">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->meta_description }}</span>
   @else
    <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.meta_description" required>
   @endif
   <label for="product__name">Meta Description</label>
  </div>

  {{-- Product Long Description --}}
  <div class="textarea__tabs details__long">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->comments }}</span>
   @else
    <textarea type="text" placeholder=" " name="product__name" wire:model.defer="prod.comments"></textarea>
   @endif
   <label for="product__name">Comments</label>
  </div>

  {{-- Product Long Description --}}
  <div class="textarea__tabs details__long">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->long_description }}</span>
   @else
    <textarea type="text" placeholder=" " name="product__name" wire:model.defer="prod.long_description" required></textarea>
   @endif
   <label for="product__name">Long Description</label>
  </div>

  {{-- Product Seo Title --}}
  <div class="input__tabs">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->seo_title }}</span>
   @else
    <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.seo_title" required>
   @endif
   <label for="product__name">Seo Title</label>
  </div>

  {{-- Product Friendly URL --}}
  <div class="input__tabs">
   @if ($editproduct === null)
    <span class="disabled">{{ $product->seo_id }}</span>
   @else
    <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.seo_id" required>
   @endif
   <label for="product__name">Friendly URL</label>
  </div>

  {{-- Create date / time --}}
  <div class="input__tabs">
   <span class="disabled">{{ $product->created_at }}</span>
   <label>Create date / time</label>
  </div>

  {{-- Created By --}}
  <div class="input__tabs">
   <span class="disabled">{{ $product->created_by }}</span>
   <label>Created By</label>
  </div>

  {{-- Updated At --}}
  <div class="input__tabs">
   <span class="disabled">{{ $product->updated_at }}</span>
   <label>Updated At</label>
  </div>

  {{-- Last Modified By --}}
  <div class="input__tabs">
   <span class="disabled">{{ $product->last_modified_by }}</span>
   <label>Last modified by</label>
  </div>

  {{-- Save Button --}}
  @if ($editproduct != null)
   <button class="button button--fill button--secondary details__long" wire:click.prevent="saveproduct()"
    value="Save">
    Save
   </button>
  @endif
 </form>


 {{-- Tabs Body (Related) --}}
 <div style="height: calc(100% - 107.5px);" class="tabs__content related__view" id="relatedContent">
  @livewire('related-media-product', ['product' => $product])
  @livewire('related-variants', ['product' => $product])
  @livewire('related-category-product', ['product' => $product])
  @livewire('related-products', ['product' => $product])
  @livewire('related-spec-product', ['product' => $product])
  @livewire('related-pricelist', ['product' => $product])
  @livewire('product-reviews', ['product' => $product, 'tableName' => 'product_reviews'])

 </div>
</section>
