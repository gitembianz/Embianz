<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('promotion')" />
<form class="content" method="POST" enctype="multipart/form-data" action="{{ route('store_promotion') }}">

 {{-- Navigation --}}
 <nav class="nav--controls">
  <h1 class="table--name">New promotion</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to Promotions" tooltip-top
   href="{{ route('all_promotions') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <button class="button button--primary button--centered" tooltip="Save promotion" tooltip-left type="submit">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 15l2 2l4 -4" />
   </svg>
  </button>
  <button class="button button--primary button--centered" tooltip="Reset promotion" tooltip-left type="reset">
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

  {{-- Name --}}
  <div class="input__tabs">
   <input type="text" name="name" value="{{ old('name') }}">
   <label>Name</label>
  </div>

  {{-- Type --}}
  <div class="input__tabs">
   <select name="type" value="{{ old('type') }}">
    <option selected value="counter">counter</option>
    <option value="amount">amount</option>
   </select>
   <label>Type</label>
  </div>

  {{-- Details --}}
  <div class="textarea__tabs details__long">
   <textarea name="details">{{ old('details') }}</textarea>
   <label>Details</label>
  </div>
  {{-- Percent --}}
  <div class="input__tabs">
   <input type="number" name="percent" value="{{ old('percent') }}">
   <label>Percent %</label>
  </div>

  {{-- Value --}}
  <div class="input__tabs">
   <input type="number" name="value" value="{{ old('value') }}">
   <label>Value</label>
  </div>
  <div class="details__checkboxes">

   {{-- promotion Start Date --}}
   <div class="input__tabs">
    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}">
    <label>Start Date</label>
   </div>

   {{-- promotion End Date --}}
   <div class="input__tabs">
    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}">
    <label>End Date</label>
   </div>
  </div>


  {{-- promotion Popularity --}}
  <div class="input__tabs">
   <input type="number" min="0" name="cooldown" value="{{ old('cooldown') }}">
   <label>Cooldowm Timer(min)</label>
  </div>
  {{-- promotion Popularity --}}
  <div class="input__tabs">
   <input type="number" min="0" name="amount" value="{{ old('amount') }}">
   <label>Cart Amount</label>
  </div>

  <div class="input__tabs">
   <input type="number" min="0" name="cookie" value="{{ old('cookie') }}">
   <label>Cookie time(days)</label>
  </div>
  <div class="details__checkboxes">

   {{-- promotion Active --}}
   <div class="checkbox__details ">
    <input type="checkbox" id="active" name="active" value="{{ old('active') }}" />
    <label for="active">Active</label>
   </div>
  </div>

  {{-- Save Button --}}
  <input class="button button--fill button--secondary details__long" type="submit" value="Add New" name="submit">

  @error('end_date')
   <span class="error @error('end_date') active @enderror">{{ $message }}</span>
  @enderror
  @error('cart_amount')
   <span class="error @error('cart_amount') active @enderror">{{ $message }}</span>
  @enderror
  @error('cart_amount')
   <span class="error @error('cart_amount') active @enderror">{{ $message }}</span>
  @enderror
 </section>

</form>
<x-dashboardfooter />
