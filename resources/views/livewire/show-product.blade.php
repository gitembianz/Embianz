<div>
 <x-alert />
 <x-loading />
 <div class="item__header">
  <h1 class="item__header-title" id="title">Product: {{ $product->name }}</h1>
  <div class="item__header-buttons">
   <a class="item__header-btn" href="{{ url('/products') }}" data-tooltip-left="Back to all Products">
    <svg>
     <polyline points="11 17 6 12 11 7"></polyline>
     <polyline points="18 17 13 12 18 7"></polyline>
    </svg>
   </a>
   <a class="item__header-btn" href="{{ route('add_product') }}" data-tooltip-center="Add a new Product">
    <svg>
     <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
     <polyline points="14 2 14 8 20 8"></polyline>
     <line x1="12" y1="18" x2="12" y2="12"></line>
     <line x1="9" y1="15" x2="15" y2="15"></line>
    </svg>
   </a>
   @if ($editproduct === null)
    <button class="item__header-btn" type="button" value="Edit" wire:click.prevent="editproduct()"
     data-tooltip-center="Edit this Product">
     <svg>
      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
     </svg>
    </button>
   @else
    <button class="item__header-btn confirm" type="button" wire:click.prevent="saveproduct()" value="Save"
     data-tooltip-center="Save this changes"><svg>
      <polyline points="20 6 9 17 4 12"></polyline>
     </svg></button>
    <button class="item__header-btn" type="button" wire:click.prevent="cancelproduct()" value="Cancel"
     data-tooltip-center="Cancel this changes"><svg>
      <line x1="18" y1="6" x2="6" y2="18">
      </line>
      <line x1="6" y1="6" x2="18" y2="18">
      </line>
     </svg></button>
   @endif
   <button wire:click.prevent="confirmProductRemoval({{ $product->id }})" class="item__header-btn delete"
    type="button" value="Delete" data-tooltip-right="Delete this Product">
    <svg>
     <polyline points="3 6 5 6 21 6"></polyline>
     <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
    </svg>
   </button>
  </div>
 </div>
 <div class="modal" id="confirmationmodal">
  <div class="modal-content">
   <h1 class="modal-content-title">
    {{ __('Are you sure to delete this record?') }}
   </h1>
   <input wire:click.prevent="deleteRecord()" class="modal-content-btn submit" type="button" value="Confirm">
   <input class="modal-content-btn delete" type="button"
    onclick="document.getElementById('confirmationmodal').style.display='none'" value="Cancel">
   <span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodal').style.display='none'">
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
    <div class="item__form">
     @if ($editproduct === null)
      <div class="item__form-input-close">
       <div>{{ $product->name }}</div>
       <label>Name</label>
      </div>
     @else
      <div class="item__form-input">
       <input type="text" wire:model.defer="prod.product_name" required>
       <label> Name</label>
      </div>
     @endif
     @if ($editproduct === null)
      <div class="item__form-input-close">
       <div>{{ $product->sku }}</div>
       <label>Sku</label>
      </div>
     @else
      <div class="item__form-input">
       <input type="text" wire:model.defer="prod.sku" required>
       <label> Sku</label>
      </div>
     @endif
     @if ($editproduct === null)
      <div class="item__form-input-close">
       <div>{{ $product->ean }}</div>
       <label>Ean</label>
      </div>
     @else
      <div class="item__form-input">
       <input type="text" wire:model.defer="prod.ean" required>
       <label> Ean</label>
      </div>
     @endif
     @if ($editproduct === null)
      <div class="item__form-input-close">
       <div>{{ $product->start_date }}</div>
       <label>Start Date</label>
      </div>
     @else
      <div class="item__form-input">
       <input type="date" id="start_date" wire:model.defer="prod.start_date">
       <label>Start Date</label>
      </div>
     @endif
     @if ($editproduct === null)
      <div class="item__form-input-close">
       <div>{{ $product->end_date }}</div>
       <label>End Date </label>
      </div>
     @else
      <div class="item__form-input">
       <input type="date" id="end_date" wire:model.defer="prod.end_date">
       <label>End Date</label>
      </div>
     @endif
     @if ($editproduct === null)
      <div class="item__form-input-close">
       <div>{{ $product->quantity }}</div>
       <label>Quantity</label>
      </div>
     @else
      <div class="item__form-input">
       <input type="number" min="0" wire:model.defer="prod.quantity" required>
       <label>Quantity</label>
      </div>
     @endif
     <div style="display: flex; gap:20px">
     @if ($editproduct === null)
      <div style="display: flex; align-items: center;justify-content: flex-start;gap: 10px">
       @if ($product->active)
        <div class="simple__checkbox">
         <svg>
          <polyline points="20 6 9 17 4 12"></polyline>
         </svg>
        </div>
       @else
        <div class="simple__checkbox--disabled">
         <svg>
          <polyline points="20 6 9 17 4 12"></polyline>
         </svg>
        </div>
       @endif
       {{ _('Active') }}
      </div>
     @else
      <div style="display: flex; align-items: center;justify-content: flex-start;gap: 10px">
       <input type="checkbox" wire:model.defer="prod.active">
       <span>Active</span>
      </div>
     @endif
      @if ($editproduct === null)
      <div style="display: flex; align-items: center;justify-content: flex-start;gap: 10px">
       @if ($product->is_new)
        <div class="simple__checkbox">
         <svg>
          <polyline points="20 6 9 17 4 12"></polyline>
         </svg>
        </div>
       @else
        <div class="simple__checkbox--disabled">
         <svg>
          <polyline points="20 6 9 17 4 12"></polyline>
         </svg>
        </div>
       @endif
       {{ _('Is New') }}
      </div>
     @else
      <div style="display: flex; align-items: center;justify-content: flex-start;gap: 10px">
       <input type="checkbox" wire:model.defer="prod.is_new">
       <span>Is New</span>
      </div>
     @endif
     </div>
     @if ($editproduct === null)
      <div class="item__form-input-close">
       <div>{{ $product->popularity }}</div>
       <label>Popularity</label>
      </div>
     @else
      <div class="item__form-input">
       <input type="number" min="0" wire:model.defer="prod.popularity" required>
       <label>Popularity</label>
      </div>
     @endif
     @if ($editproduct === null)
      <div class="item__form-input-close">
       <div>{{ $product->short_description }}</div>
       <label>Short Description</label>
      </div>
     @else
      <div class="item__form-input">
       <input type="text" wire:model.defer="prod.short_description" required>
       <label>Short Description</label>
      </div>
     @endif
     @if ($editproduct === null)
      <div class="item__form-input-close">
       <div>{{ $product->meta_description }}</div>
       <label>Meta Description</label>
      </div>
     @else
      <div class="item__form-input">
       <input type="text" wire:model.defer="prod.meta_description" required>
       <label>Meta Description</label>
      </div>
     @endif
     @if ($editproduct === null)
      <div class="item__form-input-close item__form-textarea">
       <div>{!! $product->long_description !!}</div>
       <label>Long Description</label>
      </div>
     @else
      <div class="item__form-input item__form-textarea">
       <textarea id="editor" wire:model="prod.long_description" required></textarea>
       <label>Long Description</label>
      </div>
     @endif
     @if ($editproduct === null)
      <div class="item__form-input-close">
       <div>{{ $product->seo_title }}</div>
       <label>SEO Title</label>
      </div>
     @else
      <div class="item__form-input">
       <input type="text" wire:model.defer="prod.seo_title" required>
       <label>SEO Title</label>
      </div>
     @endif
     @if ($editproduct === null)
      <div class="item__form-input-close">
       <div>{{ $product->seo_id }}</div>
       <label>Friendly URL</label>
      </div>
     @else
      <div class="item__form-input">
       <input type="text" wire:model.defer="prod.seo_id" required>
       <label>Friendly URL</label>
      </div>
     @endif
     <div class="item__form-input-close">
      <div>{{ $product->created_at }}</div>
      <label>Create date / time</label>
     </div>
     <div class="item__form-input-close">
      <div>{{ $product->created_by }}</div>
      <label>Create by</label>
     </div>
     <div class="item__form-input-close">
      <div>{{ $product->updated_at }}</div>
      <label>Updated date / time</label>
     </div>
     <div class="item__form-input-close">
      <div>{{ $product->last_modified_by }}</div>
      <label>Last modified by</label>
     </div>
     @if ($editproduct != null)
      <button class="item__form-btn item__form-long" wire:click.prevent="saveproduct()" value="Save">
       Save
      </button>
     @endif
    </div>
   </div>
   <div class="tabs__content">
    @livewire('related-media-product', ['product' => $product])
    @livewire('related-category-product', ['product' => $product])
    @livewire('related-products', ['product' => $product])
    @livewire('related-spec-product', ['product' => $product])
    @livewire('related-pricelist', ['product' => $product])
   </div>
  </div>
 </div>
</div>
