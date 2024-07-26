<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('category')" />

<form class="content" method="POST" enctype="multipart/form-data" action="{{ route('add_category') }}">

 {{-- Navigation --}}
 <nav class="nav--controls">
  <h1 class="table--name">{{ __('New category') }}</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to Categories" tooltip-top
   href="{{ route('category') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <button class="button button--primary button--centered" tooltip="Save Category" tooltip-left type="submit">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 15l2 2l4 -4" />
   </svg>
  </button>
  <button class="button button--primary button--centered" tooltip="Reset Category" tooltip-left type="reset">
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

  {{-- Category Name --}}
  <div class="input__tabs">
   <input type="text" name="category" placeholder=" " required value="{{ old('category') }}">
   <label>Name</label>
  </div>

  {{-- Category Active && Displayed on Store --}}
  <div class="details__checkboxes">
   {{-- Category Active --}}
   <div class="checkbox__details ">
    <input type="checkbox" id="active" name="active" value="{{ old('active') }}" />
    <label for="active">Active</label>
   </div>
   {{-- Category Displayed on Store Tab --}}
   <div class="checkbox__details ">
    <input type="checkbox" id="visible" name="visible" value="{{ old('visible') }}" />
    <label for="visible">Displayed on Store Tab?</label>
   </div>
  </div>

  {{-- Category Start Date --}}
  <div class="input__tabs">
   <input type="date" id="start_date" placeholder=" " name="start_date" value="{{ old('start_date') }}">
   <label>Start Date</label>
  </div>

  {{-- Category End Date --}}
  <div class="input__tabs">
   <input type="date" id="end_date" placeholder=" " name="end_date" value="{{ old('end_date') }}">
   <label>End Date</label>
  </div>

  {{-- Category Sequence --}}
  <div class="input__tabs">
   <input type="number" min="0" name="sequence" placeholder=" " required value="{{ old('sequence') }}">
   <label>Sequence</label>
  </div>

  {{-- Category Meta Description --}}
  <div class="input__tabs">
   <input type="text" name="meta_description" placeholder=" " value="{{ old('meta_description') }}">
   <label>Meta Description</label>
  </div>

  {{-- Category Short Description --}}
  <div class="input__tabs details__long">
   <input type="text" name="short_description" placeholder=" " value="{{ old('short_description') }}">
   <label>Short Description</label>
  </div>

  {{-- Category Long Description --}}
  <div class="textarea__tabs details__long">
   <textarea name="long_description" placeholder=" ">{{ old('long_description') }}</textarea>
   <label>Long Description</label>
  </div>

  {{-- Category SEO Title --}}
  <div class="input__tabs">
   <input type="text" name="seo_title" placeholder=" " value="{{ old('seo_title') }}">
   <label>SEO Title</label>
  </div>

  {{-- Category Friendly URL --}}
  <div class="input__tabs">
   <input type="text" name="seo_id" placeholder=" " value="{{ old('seo_id') }}">
   <label>Friendly URL</label>
  </div>

  {{-- Save Button --}}
  <input class="button button--fill button--secondary details__long" type="submit" value="Add New" name="submit">
 </section>

</form>

<x-dashboardfooter />
