<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('scripts')" />
<form class="content" method="POST" enctype="multipart/form-data" action="{{ route('add_script') }}">

 {{-- Navigation --}}
 <nav class="nav--controls">
  <h1 class="table--name">Add new custom code</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to Custom codes" tooltip-top
   href="{{ route('customscripts') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <button class="button button--primary button--centered" tooltip="Save Code" tooltip-left type="submit">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 15l2 2l4 -4" />
   </svg>
  </button>
  <button class="button button--primary button--centered" tooltip="Reset Code" tooltip-left type="reset">
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

  {{--  Name --}}
  <div class="input__tabs">
   <input type="text" name="name" placeholder=" " required value="{{ old('name') }}">
   <label>Name</label>
  </div>

  {{-- Code Position --}}
  <div class="input__tabs">
   <select name="type" value="{{ old('type') }}">
    <option selected value="head-top">head-top</option>
    <option value="head-bottom">head-bottom</option>
    <option value="body-top">body-top</option>
    <option value="body-bottom">body-bottom</option>
   </select>
   <label>Code position</label>
  </div>

  {{--  Active && Displayed on Store --}}
  <div class="details__checkboxes details__long">
   {{--  Active --}}
   <div class="checkbox__details ">
    <input type="checkbox" id="active" name="active" placeholder=" " value="{{ old('active') ? 'checked' : '' }}" />
    <label for="active">Active</label>
   </div>
  </div>

  {{-- Content --}}
  <div class="textarea__tabs details__long">
   <textarea name="content" placeholder=" ">{{ old('content') }}</textarea>
   <label>Content</label>
  </div>

  {{-- Save Button --}}
  <input class="button button--fill button--secondary details__long" type="submit" value="Add New" name="submit">
 </section>
</form>
<x-dashboardfooter />
