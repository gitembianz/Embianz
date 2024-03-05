<div>
 <x-alert />
 <x-loading />
 <div class="item__header">
  <h1 class="item__header-title" id="title">Category: {{ $category->name }}</h1>
  <div class="item__header-buttons">
   <a class="item__header-btn" href="{{ route('category') }}" data-tooltip-left="Back to all Categories">
    <svg>
     <polyline points="11 17 6 12 11 7"></polyline>
     <polyline points="18 17 13 12 18 7"></polyline>
    </svg>
   </a>
   <a class="item__header-btn" href="{{ route('newcategory') }}" data-tooltip-center="Create a new Category">
    <svg>
     <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
     <polyline points="14 2 14 8 20 8"></polyline>
     <line x1="12" y1="18" x2="12" y2="12"></line>
     <line x1="9" y1="15" x2="15" y2="15"></line>
    </svg>
   </a>
   @if ($editcategory === null)
    <button class="item__header-btn" type="button" value="Edit" wire:click.prevent="editcategory()"
     data-tooltip-center="Edit this Category">
     <svg>
      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
     </svg>
    </button>
   @else
    <button class="item__header-btn confirm" type="button" wire:click.prevent="savecategory()" value="Save"
     data-tooltip-center="Save this changes">
     <svg>
      <polyline points="20 6 9 17 4 12"></polyline>
     </svg>
    </button>
    <button class="item__header-btn" type="button" wire:click.prevent="cancelcategory()" value="Cancel"
     data-tooltip-center="Cancel this changes">
     <svg>
      <line x1="18" y1="6" x2="6" y2="18">
      </line>
      <line x1="6" y1="6" x2="18" y2="18">
      </line>
     </svg>
    </button>
   @endif
   <button wire:click.prevent="confirmItemRemoval({{ $category->id }})" class="item__header-btn delete" type="button"
    value="Delete" data-tooltip-right="Delete this Category">
    <svg>
     <polyline points="3 6 5 6 21 6"></polyline>
     <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
    </svg>
   </button>
  </div>
 </div>
 <div class="tab">
  <div class="tabs">
   <h3 class="tabs__page active">Details</h3>
   <h3 class="tabs__page">Related</h3>
  </div>
  <div class="tab__list">
   <div class="tabs__content active" id="Details">
    <form method="POST">
     @csrf
     <div class="item__form">
      @if ($editcategory === null)
       <div class="item__form-input-close">
        <div>{{ $category->name }}</div>
        <label>Name</label>
       </div>
      @else
       <div class="item__form-input">
        <input type="text" wire:model.defer="cat.name" required>
        <label>Name</label>
       </div>
      @endif
      @if ($editcategory === null)
       <div class="item__form-input-close">
        <div>{{ $category->start_date }}</div>
        <label>Start Date</label>
       </div>
      @else
       <div class="item__form-input">
        <input type="date" wire:model.defer="cat.start_date">
        <label>Start Date</label>
       </div>
      @endif
      @if ($editcategory === null)
       <div class="item__form-input-close">
        <div>{{ $category->end_date }}</div>
        <label>End Date </label>
       </div>
      @else
       <div class="item__form-input">
        <input type="date" wire:model.defer="cat.end_date">
        <label>End Date</label>
       </div>
      @endif
      <div style="display: flex;">
       @if ($editcategory === null)
        <div class="item__form-input-close">
         <div>{{ $category->sequence }}</div>
         <label>Sequence</label>
        </div>
        <div class="item__form-input-close">
         <div>{{ $category->slider_sequence }}</div>
         <label>Slider Sequence</label>
        </div>
       @else
        <div class="item__form-input">
         <input type="number" wire:model.defer="cat.sequence">
         <label>Sequence</label>
        </div>
        <div class="item__form-input">
         <input type="number" wire:model.defer="cat.slider_sequence">
         <label>Slider Sequence</label>
        </div>
       @endif
      </div>

      @if ($editcategory === null)
       <div style="display: flex; align-items: center;justify-content: flex-start;gap: 10px">
        @if ($category->active)
         <div class="simple__checkbox">
          <svg>
           <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
         </div>
         {{ _('Active') }}
        @else
         <div class="simple__checkbox--disabled">
          <svg>
           <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
         </div>
         {{ _('Inactive') }}
        @endif
       </div>
      @else
       <div style="display: flex; align-items: center;justify-content: flex-start;gap: 10px">
        <input type="checkbox" wire:model.defer="cat.active">
        <span>Active</span>
       </div>

      @endif
      @if ($editcategory === null)
       <div style="display: flex; align-items: center;justify-content: flex-start;gap: 10px">
        @if ($category->store_tab)
         <div class="simple__checkbox">
          <svg>
           <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
         </div>
         {{ _('Show in Header: Visible') }}
        @else
         <div class="simple__checkbox--disabled">
          <svg>
           <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
         </div>
         {{ _('Show in Store: Hidden') }}
        @endif
       </div>
      @else
       <div style="display: flex; align-items: center;justify-content: flex-start;gap: 10px">
        <input type="checkbox" wire:model.defer="cat.visible">
        <span>Display on Header?</span>
       </div>

      @endif
      @if ($editcategory === null)
       <div class="item__form-input-close">
        <div>{{ $category->short_description }}</div>
        <label>Short Description</label>
       </div>
      @else
       <div class="item__form-input">
        <input type="text" wire:model.defer="cat.short_description" required>
        <label>Short Description</label>
       </div>
      @endif
      @if ($editcategory === null)
       <div class="item__form-input-close">
        <div>{{ $category->meta_description }}</div>
        <label>Meta Description</label>
       </div>
      @else
       <div class="item__form-input">
        <input type="text" wire:model.defer="cat.meta_description" required>
        <label>Meta Description</label>
       </div>
      @endif
      @if ($editcategory === null)
       <div class="item__form-input-close item__form-textarea">
        <div>{!! $category->long_description !!}</div>
        <label>Long Description</label>
       </div>
      @else
       <div class="item__form-input item__form-textarea">
        <textarea wire:model.defer="cat.long_description" required></textarea>
        <label>Long Description</label>
       </div>
      @endif
      @if ($editcategory === null)
       <div class="item__form-input-close">
        <div id="seo_title">{{ $category->seo_title }}</div>
        <label>SEO Title</label>
       </div>
      @else
       <div class="item__form-input">
        <input type="text" wire:model.defer="cat.seo_title" required>
        <label>SEO Title</label>
       </div>
      @endif
      @if ($editcategory === null)
       <div class="item__form-input-close">
        <div>{{ $category->seo_id }}</div>
        <label>Friendly URL</label>
       </div>
      @else
       <div class="item__form-input">
        <input type="text" wire:model.defer="cat.seo_id" required>
        <label>Friendly URL</label>
       </div>
      @endif
      <div class="item__form-input-close">
       <div>{{ $category->created_at }}</div>
       <label>Create date / time</label>
      </div>
      <div class="item__form-input-close">
       <div>{{ $category->createdby }}</div>
       <label>Create by</label>
      </div>
      <div class="item__form-input-close">
       <div>{{ $category->updated_at }}</div>
       <label>Updated date / time</label>
      </div>
      <div class="item__form-input-close">
       <div>{{ $category->lastmodifiedby }}</div>
       <label>Last modified by</label>
      </div>
      @if ($editcategory != null)
       <button class="item__form-btn item__form-long" wire:click.prevent="savecategory()" value="Save">
        Save
       </button>
      @endif
     </div>
    </form>
   </div>
   <div class="tabs__content">
    @livewire('related-media-category', ['category' => $category])
    @livewire('related-product-category', ['category' => $category])
    @livewire('related-subcategory', ['category' => $category])
   </div>
  </div>
  <div class="modal" id="confirmationmodal">
   <div class="modal-content">
    <h1 class="modal-content-title">
     {{ __('Are you sure to delete this record?') }}
    </h1>
    <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button"
     value="Confirm">
    <input class="modal-content-btn delete" type="button"
     onclick="document.getElementById('confirmationmodal').style.display='none'" value="Cancel">

    <span class="modal-content-btn delete"
     onclick="document.getElementById('confirmationmodal').style.display='none'">
     <svg>
      <line x1="18" y1="6" x2="6" y2="18"></line>
      <line x1="6" y1="6" x2="18" y2="18"></line>
     </svg>
    </span>
   </div>
  </div>
 </div>
</div>
