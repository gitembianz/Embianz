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
    <form action="{{ url('/add_pricelist') }}" method="POST">
        @csrf
        {{-- Item Header --}}

        <div class="item__header">
            <h1 class="item__header-title" id="title">{{ __('Add new price list') }}</h1>
            <div class="item__header-buttons">
                <a class="item__header-btn" href="{{ route('specs') }}" data-tooltip-center="Back to all Price lists">
                    <svg>
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                    </svg>
                </a>
                <button class="item__header-btn" id="resetform" type="reset" data-tooltip-right="Clear Form">
                    <svg>
                        <polyline points="1 4 1 10 7 10"></polyline>
                        <polyline points="23 20 23 14 17 14"></polyline>
                        <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Item Form --}}
        <div class="item__form">
            <div class="item__form-input">
                <input type="text" name="name" required>
                <label> Name</label>
            </div>
            <div class="item__form-input">
                <select name="currency">
                    <option>Select a currency</option>
                    @foreach ($currencies as $currency)
                        <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                    @endforeach
                </select>
                <label>Currency</label>
            </div>
            <div style="display: flex; align-items: center;justify-content: flex-start;gap: 10px">
                <input type="checkbox" name="active">
                <span> it's active</span>
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
