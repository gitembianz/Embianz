<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('user')" />
<form class="content" method="POST" action="{{ route('new_user') }}">

 {{-- Navigation --}}
 <nav class="nav--controls">
  <h1 class="table--name">New User</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to Order Supplier Lists" tooltip-top
   href="{{ route('users') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <button class="button button--primary button--centered" tooltip="Save Price" tooltip-left type="submit">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 15l2 2l4 -4" />
   </svg>
  </button>
  <button class="button button--primary button--centered" tooltip="Reset Price" tooltip-left type="reset">
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

  {{-- Price List Name --}}
  <div class="input__tabs">
   <input type="text" name="name" required value="{{ old('name') }}">
   <label>Name</label>
  </div>
  <div class="input__tabs">
   <input type="text" name="phone"  value="{{ old('phone') }}">
   <label>Phone</label>
  </div>
  <div class="input__tabs">
   <input type="text" name="adress"  value="{{ old('adress') }}">
   <label>Address</label>
  </div>
  <div class="input__tabs">
   <input type="email" name="email" required value="{{ old('email') }}">
   <label>Email</label>
  </div>
  <div class="input__tabs">
   <input type="password" name="password" required value="{{ old('password') }}">
   <label>Password</label>
  </div>
<div class="details__checkboxes">
   {{--  Active --}}
   <div class="checkbox__details ">
    <input type="checkbox" id="usertype" name="usertype" />
    <label for="usertype">Is admin?</label>
   </div>
  </div>
  {{-- Save Button --}}
  <input class="button button--fill button--secondary details__long" type="submit" value="Add New" name="submit">
  @error('date')
  @enderror
  @foreach ($errors->all() as $error)
  <span class="error active">{{ $error }}</span>
    @endforeach
 </section>
</form>
<x-dashboardfooter />
