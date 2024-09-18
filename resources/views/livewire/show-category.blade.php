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
  <h1 class="table--name">Category: {{ strip_tags($category->name) }}</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to all categories" tooltip-top
   href="{{ route('category') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <a class="button button--primary button--centered" tooltip="Create new category" tooltip-top
   href="{{ route('newcategory') }}">
   <svg>
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
    <polyline points="14 2 14 8 20 8"></polyline>
    <line x1="12" y1="18" x2="12" y2="12"></line>
    <line x1="9" y1="15" x2="15" y2="15"></line>
   </svg>
  </a>
  @if ($editcategory === null)
   <button class="button button--primary button--centered" tooltip="Edit this category" tooltip-left
    wire:click.prevent="editcategory()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
     <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
     <path d="M16 5l3 3" />
    </svg>
   </button>
  @else
   <button class="button button--primary button--centered" tooltip="Save Edit" tooltip-left
    wire:click.prevent="savecategory()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
     <path d="M9 15l2 2l4 -4" />
    </svg>
   </button>
   <button class="button button--primary button--centered" tooltip="Cancel edit" tooltip-left
    wire:click.prevent="cancelcategory()">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 3v4a1 1 0 0 0 1 1h4" />
     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
     <path d="M10 12l4 5" />
     <path d="M10 17l4 -5" />
    </svg>
   </button>
  @endif
  <button class="button button--primary button--centered" tooltip="Delete this category" tooltip-left
   wire:click.prevent="confirmItemRemoval({{ $category->id }})">
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
  {{-- Category Name --}}
  <div class="input__tabs">
   @if ($editcategory === null)
    <span class="disabled">{{ $category->name }}</span>
   @else
    <input type="text" placeholder=" " name="category__name" wire:model.defer="cat.name" required>
   @endif
   <label for="category__name">Name</label>
  </div>

  {{-- Category Active && Store Tab --}}
  <div class="details__checkboxes">
   {{-- Category Active --}}
   <div class="checkbox__details ">
    @if ($editcategory === null)
     @if ($category->active)
      <input type="checkbox" id="active1" checked class="disabled" disabled />
      <label for="active1" class="disabled">Active</label>
     @else
      <input type="checkbox" id="active2" class="disabled" disabled />
      <label for="active2" class="disabled">Active</label>
     @endif
    @else
     <input type="checkbox" id="active3" wire:model.defer="cat.active" />
     <label for="active3">Active</label>
    @endif
   </div>
   {{-- Category Store Tab --}}
   <div class="checkbox__details">
    @if ($editcategory === null)
     @if ($category->store_tab)
      <input type="checkbox" id="store_tab1" checked class="disabled" disabled />
      <label for="store_tab1" class="disabled">Show in store</label>
     @else
      <input type="checkbox" id="store_tab2" class="disabled" disabled />
      <label for="store_tab2" class="disabled">Show in store</label>
     @endif
    @else
     <input type="checkbox" id="store_tab3" wire:model.defer="cat.visible" />
     <label for="store_tab3">Show in store</label>
    @endif
   </div>
  </div>
  <div class="details__checkboxes">

   <div class="checkbox__details ">
    @if ($editcategory === null)
     @if ($category->preload_image)
      <input type="checkbox" id="preload1" checked class="disabled" disabled />
      <label for="preload1" class="disabled">Preload image</label>
     @else
      <input type="checkbox" id="preload2" class="disabled" disabled />
      <label for="preload2" class="disabled">Preload image</label>
     @endif
    @else
     <input type="checkbox" id="preload3" wire:model.defer="cat.preload" />
     <label for="preload3">Preload image</label>
    @endif
   </div>
   <div class="checkbox__details ">
    @if ($editcategory === null)
     @if ($category->display_variant_price)
      <input type="checkbox" id="varprice1" checked class="disabled" disabled />
      <label for="varprice1" class="disabled">Display Variant Price</label>
     @else
      <input type="checkbox" id="varprice2" class="disabled" disabled />
      <label for="varprice2" class="disabled">Display Variant Price</label>
     @endif
    @else
     <input type="checkbox" id="varprice3" wire:model.defer="cat.varprice" />
     <label for="varprice3">Display Variant Price</label>
    @endif
   </div>
  </div>

  {{-- Category Start Date --}}
  <div class="input__tabs">
   @if ($editcategory === null)
    <span class="disabled">{{ $category->start_date }}</span>
   @else
    <input type="date" placeholder=" " name="category__name" wire:model.defer="cat.start_date" required>
   @endif
   <label for="category__name">Start Date</label>
  </div>

  {{-- Category End Date --}}
  <div class="input__tabs">
   @if ($editcategory === null)
    <span class="disabled">{{ $category->end_date }}</span>
   @else
    <input type="date" placeholder=" " name="category__name" wire:model.defer="cat.end_date" required>
   @endif
   <label for="category__name">End Date</label>
  </div>
  <div class="details__checkboxes">

   {{-- Category Slider Sequence --}}
   <div class="input__tabs">
    @if ($editcategory === null)
     <span class="disabled">{{ $category->slider_sequence }}</span>
    @else
     <input type="number" placeholder=" " name="category__name" wire:model.defer="cat.slider_sequence" required>
    @endif
    <label for="category__name">Slider Sequence</label>
   </div>

   {{-- Category Sequence --}}
   <div class="input__tabs">
    @if ($editcategory === null)
     <span class="disabled">{{ $category->sequence }}</span>
    @else
     <input type="number" placeholder=" " name="category__name" wire:model.defer="cat.sequence" required>
    @endif
    <label for="category__name">Sequence</label>
   </div>

  </div>

  {{-- Category Meta Description --}}
  <div class="input__tabs details__long">
   @if ($editcategory === null)
    <span class="disabled">{{ $category->meta_description }}</span>
   @else
    <input type="text" placeholder=" " name="category__name" wire:model.defer="cat.meta_description" required>
   @endif
   <label for="category__name">Meta Description</label>
  </div>


  {{-- Category Short Description --}}
  <div class="input__tabs">
   @if ($editcategory === null)
    <span class="disabled">{{ $category->short_description }}</span>
   @else
    <input type="text" placeholder=" " name="category__name" wire:model.defer="cat.short_description" required>
   @endif
   <label for="category__name">Short Description</label>
  </div>

  {{-- Category Displayed Items --}}
  <div class="input__tabs">
   @if ($editcategory === null)
    <span class="disabled">{{ $category->accepted_items }}</span>
   @else
    <select wire:model.defer="cat.acc_items">
     <option value="default">default</option>
     <option value="parents">parents</option>
    </select>
   @endif
   <label for="category__name">Displayed items</label>
  </div>

  {{-- Category Long Description --}}
  <div class="textarea__tabs details__long">
   @if ($editcategory === null)
    <span class="disabled">{{ $category->long_description }}</span>
   @else
    <textarea type="text" placeholder=" " name="category__name" wire:model.defer="cat.long_description" required></textarea>
   @endif
   <label for="category__name">Long Description</label>
  </div>

  {{-- Category Seo Title --}}
  <div class="input__tabs">
   @if ($editcategory === null)
    <span class="disabled">{{ $category->seo_title }}</span>
   @else
    <input type="text" placeholder=" " name="category__name" wire:model.defer="cat.seo_title" required>
   @endif
   <label for="category__name">Seo Title</label>
  </div>

  {{-- Category Friendly URL --}}
  <div class="input__tabs">
   @if ($editcategory === null)
    <span class="disabled">{{ $category->seo_id }}</span>
   @else
    <input type="text" placeholder=" " name="category__name" wire:model.defer="cat.seo_id" required>
   @endif
   <label for="category__name">Friendly URL</label>
  </div>

  {{-- Create date / time --}}
  <div class="input__tabs">
   <span class="disabled">{{ $category->created_at }}</span>
   <label>Create date / time</label>
  </div>

  {{-- Created By --}}
  <div class="input__tabs">
   <span class="disabled">{{ $category->created_by }}</span>
   <label>Created By</label>
  </div>

  {{-- Updated At --}}
  <div class="input__tabs">
   <span class="disabled">{{ $category->updated_at }}</span>
   <label>Updated At</label>
  </div>

  {{-- Last Modified By --}}
  <div class="input__tabs">
   <span class="disabled">{{ $category->last_modified_by }}</span>
   <label>Last modified by</label>
  </div>

  {{-- Save Button --}}
  @if ($editcategory != null)
   <button class="button button--fill button--secondary details__long" wire:click.prevent="savecategory()"
    value="Save">
    Save
   </button>
  @endif
 </form>


 {{-- Tabs Body (Related) --}}
 <div style="height: calc(100% - 107.5px);" class="tabs__content related__view" id="relatedContent">
  @livewire('related-media-category', ['category' => $category])
  @livewire('related-product-category', ['category' => $category])
  @livewire('related-subcategory', ['category' => $category])
 </div>
</section>
