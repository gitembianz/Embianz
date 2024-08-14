<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('product')" />
<form class="content" method="POST" enctype="multipart/form-data" action="{{ route('new_products') }}">

 {{-- Navigation --}}
 <nav class="nav--controls">
  <h1 class="table--name">New product</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to Products" tooltip-top
   href="{{ route('all_products') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <button class="button button--primary button--centered" tooltip="Save Product" tooltip-left type="submit">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 15l2 2l4 -4" />
   </svg>
  </button>
  <button class="button button--primary button--centered" tooltip="Reset Product" tooltip-left type="reset">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
   </svg>
  </button>
 </nav>

 {{-- Tabs Body (Details) --}}
 <section style="height: calc(100% - 107.5px);" class="tabs__content details__view active">
  @csrf

  {{-- Product Name --}}
  <div class="input__tabs">
   <input type="text" name="product_name" placeholder=" " required value="{{ old('product_name') }}">
   <label>Name</label>
  </div>

  {{-- Product Active && Displayed on Store --}}
  <div class="details__checkboxes">
   {{-- Product Active --}}
   <div class="checkbox__details ">
    <input type="checkbox" id="active" name="active" value="{{ old('active') }}" />
    <label for="active">Active</label>
   </div>
   {{-- Product Displayed on is_new --}}
   <div class="checkbox__details ">
    <input type="checkbox" id="is_new" name="is_new" value="{{ old('is_new') }}" />
    <label for="is_new">Product is new?</label>
   </div>
  </div>

  {{-- Product Start Date --}}
  <div class="input__tabs">
   <input type="date" id="start_date" placeholder=" " name="start_date" value="{{ old('start_date') }}">
   <label>Start Date</label>
  </div>

  {{-- Product End Date --}}
  <div class="input__tabs">
   <input type="date" id="end_date" placeholder=" " name="end_date" value="{{ old('end_date') }}">
   <label>End Date</label>
  </div>

  {{-- Product SKU --}}
  <div class="input__tabs">
   <input type="text" name="sku" placeholder=" " required value="{{ old('sku') }}">
   <label>SKU</label>
  </div>

  {{-- Product EAN --}}
  <div class="input__tabs">
   <input type="text" name="ean" placeholder=" " required value="{{ old('ean') }}">
   <label>EAN</label>
  </div>

  {{-- Product Quantity --}}
  <div class="input__tabs">
   <input type="number" name="quantity" placeholder=" " value="{{ old('quantity') }}">
   <label>Quantity</label>
  </div>

  {{-- Product Popularity --}}
  <div class="input__tabs">
   <input type="number" min="0" placeholder=" " name="popularity" required value="{{ old('popularity') }}">
   <label>Popularity</label>
  </div>

  {{-- Product Short Description --}}
  <div class="input__tabs details__long">
   <input type="text" name="short_description" placeholder=" " value="{{ old('short_description') }}">
   <label>Short Description</label>
  </div>

  {{-- Product Meta Description --}}
  <div class="input__tabs details__long">
   <input type="text" name="meta_description" placeholder=" " value="{{ old('meta_description') }}">
   <label>Meta Description</label>
  </div>

  {{-- Product Long Description --}}
  <div class="textarea__tabs details__long">
   <textarea name="long_description" placeholder=" ">{{ old('long_description') }}</textarea>
   <label>Long Description</label>
  </div>

  {{-- Product SEO Title --}}
  <div class="input__tabs">
   <input type="text" name="seo_title" placeholder=" " value="{{ old('seo_title') }}">
   <label>SEO Title</label>
  </div>

  {{-- Product SEO Title --}}
  <div class="input__tabs">
   <input type="text" name="seo_id" placeholder=" " value="{{ old('seo_id') }}">
   <label>Friendly URL</label>
  </div>

  {{-- Save Button --}}
  <input class="button button--fill button--secondary details__long" type="submit" value="Add New" name="submit">

  @error('end_date')
   <span class="error @error('end_date') active @enderror">{{ $message }}</span>
  @enderror
  @error('sku')
   <span class="error @error('sku') active @enderror">{{ $message }}</span>
  @enderror
  @error('ean')
   <span class="error @error('ean') active @enderror">{{ $message }}</span>
  @enderror
  @error('seo_id')
   <span class="error @error('seo_id') active @enderror">{{ $message }}</span>
  @enderror

 </section>

</form>
<x-dashboardfooter />
