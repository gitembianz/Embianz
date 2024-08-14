<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('voucher')" />
<form class="content" method="POST" enctype="multipart/form-data" action="{{ route('add_voucher') }}">
 @csrf


 {{-- Navigation --}}
 <nav class="nav--controls">
  <h1 class="table--name">New voucher</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to Products" tooltip-top
   href="{{ route('vouchers') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <button class="button button--primary button--centered" tooltip="Save product" tooltip-left type="submit">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 15l2 2l4 -4" />
   </svg>
  </button>
  <button class="button button--primary button--centered" tooltip="Reset product" tooltip-left type="reset">
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
   <input type="text" name="name" placeholder=" " required value="{{ old('name') }}">
   <label>Name</label>
  </div>

  {{-- Product Code --}}
  <div class="input__tabs">
   <input type="text" name="code" placeholder=" " required value="{{ old('code') }}">
   <label>Code</label>
  </div>

  {{-- Product Percent --}}
  <div class="input__tabs">
   <input type="number" name="percent" placeholder=" " value="{{ old('percent') }}">
   <label>Percent %</label>
  </div>

  {{-- Product Value --}}
  <div class="input__tabs">
   <input type="number" name="value" placeholder=" " value="{{ old('value') }}">
   <label>Value</label>
  </div>


  {{-- Product Start Date --}}
  <div class="input__tabs">
   <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}">
   <label>Start Date</label>
  </div>

  {{-- Product End Date --}}
  <div class="input__tabs">
   <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}">
   <label>End Date</label>
  </div>

  {{-- Product Single Use --}}
  <div class="details__checkboxes">
   {{-- Product Active --}}
   <div class="checkbox__details ">
    <input type="checkbox" id="single_use" name="single_use" value="{{ old('single_use') ? 'checked' : '' }}" />
    <label for="single_use">Single Use</label>
   </div>
  </div>

  {{-- Save Button --}}
  <input class="button button--fill button--secondary details__long" type="submit" value="Add New" name="submit">

  @error('percent')
   <span class="error @error('end_date') active @enderror">{{ $message }}</span>
  @enderror
  @error('value')
   <span class="error @error('sku') active @enderror">{{ $message }}</span>
  @enderror
  @error('start_date')
   <span class="error @error('sku') active @enderror">{{ $message }}</span>
  @enderror
  @error('end_date')
   <span class="error @error('sku') active @enderror">{{ $message }}</span>
  @enderror

 </section>
</form>
<x-dashboardfooter />
