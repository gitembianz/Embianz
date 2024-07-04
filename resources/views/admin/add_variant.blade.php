<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('variants')" />
<form class="content" method="POST" action="{{ route('add_variant') }}">

 @csrf
 {{-- Navigation --}}
 <nav class="nav--controls">
  <h1 class="table--name">New variant</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to Variants" tooltip-top
   href="{{ route('variants') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <button class="button button--primary button--centered" tooltip="Save variant" tooltip-left type="submit">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 15l2 2l4 -4" />
   </svg>
  </button>
  <button class="button button--primary button--centered" tooltip="Reset form" tooltip-left type="reset">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
   </svg>
  </button>
 </nav>


 {{-- Tabs Body (Details) --}}
 <section style="height: calc(100% - 107.5px);" class="tabs__content details__view active">
  {{--  Name --}}
  <div class="input__tabs">
   <input type="text" placeholder=" " name="name" required>
   <label>Name</label>
  </div>

  {{-- Spec Sequence --}}
  <div class="input__tabs">
   <input type="number" placeholder=" " name="sequence" required>
   <label>Sequence</label>
  </div>

  {{-- Save Button --}}
  <input class="button button--fill button--secondary details__long" type="submit" value="Add New" name="submit">
 </section>
</form>
<x-dashboardfooter />
