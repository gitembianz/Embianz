<div>
    <div class="item__header">
        <h1 class="item__header-title" id="title">Category - {{ $category->name }}</h1>
        <div class="item__header-buttons">
            <a class="item__header-btn" href="{{ route('category') }}">Back</a>
            <a class="item__header-btn" href="{{ route('newcategory') }}">New</a>
            <input class="item__header-btn" type="button" value="Edit" name="edit" id="edit">
            <input class="item__header-btn delete" type="button" value="Delete" name="delete"
                wire:click.prevent="confirmCategoryRemoval({{ $category->id }})">
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

        {{-- related products --}}
    </div>
    <div class="modal" id="confirmationmodalcategory">
      <div class="modal-content">
          <h1 class="modal-content-title">
              {{ __('Are you sure to delete this category?') }}
          </h1>
          <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button"
              value="Confirm">
          <input class="modal-content-btn delete" type="button"
              onclick="document.getElementById('confirmationmodalcategory').style.display='none'" value="Cancel">

          <span class="modal-content-btn delete"
              onclick="document.getElementById('confirmationmodalcategory').style.display='none'">
              <svg>
                  <line x1="18" y1="6" x2="6" y2="18"></line>
                  <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
          </span>
      </div>
  </div>
</div>
