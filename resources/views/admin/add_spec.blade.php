<x-dashboardheader />
<x-dashboardnavbar />
{{-- Display session message --}}
@if (session()->has('message'))
    <div class="alert__session" id="alertevent">
        <Span class="alert__session-text">{!! session('message') !!}</Span>
        <button class="alert__session-btn" type="button"
            onclick="document.getElementById('alertevent').style.display='none'" data-bs-dismiss="alert"
            aria-hidden="true">
            <svg>
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
@endif
{{-- End Section session message --}}
<x-dashboardsidebar />
{{-- Page content start --}}


<section class="content">
    <form class="item" action="{{ url('/add_spec') }}" method="POST">
        @csrf
        {{-- Item Header --}}
        <div class="item__header">
            <h1 class="item__header-title" id="title">{{ __('Add new spec') }}</h1>
            <div class="item__header-buttons">
                <a class="item__header-btn" href="{{ route('specs') }}">All Specs</a>
                <button class="item__header-btn" id="resetform" type="reset">Clear form</button>
            </div>
        </div>

        {{-- Item Form --}}
        <div class="item__form">
            <div class="item__form-input">
                <input type="text" name="name" required>
                <span> Name</span>
            </div>

            <div class="item__form-input">
                <input type="text" name="um" required>
                <span>Unit</span>
            </div>
            <div class="item__form-input">
              <select name="spec_group">
                <?php
                $groups = ['details', 'feature', 'accessibility'];
                ?>
                <option>Select a group</option>
                @foreach ($groups as $group)
                    <option value="{{ $group }}">{{ $group }}</option>
                @endforeach
            </select>
            <span>Group</span>
          </div>
            <input class="item__form-btn  item__form-long" type="submit" value="Add New" name="submit">
        </div>
    </form>
    <a href="#" class="top-up-btn" id="topUp">
        <svg>
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </a>
</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardscript />
<x-dashboardfooter />
