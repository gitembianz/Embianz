<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('category')" />
{{-- Page content start --}}

<section class="content">
    <form action="{{ url('/add_category') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Item Header --}}
        <div class="item__header">
            <h1 class="item__header-title" id="title">{{ __('Create Category') }}</h1>
            <div class="item__header-buttons">
                <button class="item__header-btn" type="submit" data-tooltip-right="Add Category">
                    <svg>
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
                <a class="item__header-btn" href="{{ route('category') }}"
                    data-tooltip-center="Back to all Price lists">
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
                <input type="text" name="category" required value="{{ old('category') }}">
                <label>Category Name</label>
            </div>
            <div class="item__form-input">
                <input type="number" min="0" name="sequence" required value="{{ old('sequence') }}">
                <label>Sequence</label>
            </div>
            <div class="item__form-input">
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}">
                <label>Start Date</label>
            </div>
            <div class="item__form-input">
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}">
                <label>End Date</label>
                @error('end_date')
                    <span style="position: absolute;
    top: 2.5rem;
    color: red;">{{ $message }}</span>
                @enderror
            </div>
            <div style="display: flex; align-items: center;justify-content: flex-start;gap: 10px">
                <input type="checkbox" name="active" value="{{ old('active') }}">
                <span>Active</span>
            </div>
            <div style="display: flex; align-items: center;justify-content: flex-start;gap: 10px">
                <input type="checkbox" name="visible" value="{{ old('visible') }}">
                <span>Displayed on Store Tab?</span>
            </div>
            <div class="item__form-input item__form-long">
                <input type="text" name="short_description" value="{{ old('short_description') }}">
                <label>Short Description</label>
            </div>
            <div class="item__form-input item__form-textarea">
                <textarea name="long_description">{{ old('long_description') }}</textarea>
                <label>Long Description</label>
            </div>
            <div class="item__form-input">
                <input type="text" name="seo_title" value="{{ old('seo_title') }}">
                <label>SEO Title</label>
            </div>
            <div class="item__form-input">
                <input type="text" name="seo_id" value="{{ old('seo_id') }}">
                <label>Friendly URL</label>
                @error('seo_id')
                    <span style="position: absolute; top: 2.5rem; color: red;">{{ $message }}</span>
                @enderror
            </div>
            <input class="item__form-btn item__form-long" type="submit" value="Add New" name="submit">
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
