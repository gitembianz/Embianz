<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('articlecategory')" />
<form class="content" method="POST" enctype="multipart/form-data" action="{{ route('store_articlecategory') }}">

    {{-- Navigation --}}
    <nav class="nav--controls">
        <h1 class="table--name">New Article Category</h1>
        {{-- Refresh Button --}}
        <a class="button button--primary button--centered" tooltip="Back to article categories" tooltip-top
            href="{{ route('articlecategory') }}">
            <svg>
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        <button class="button button--primary button--centered" tooltip="Save article" tooltip-left type="submit">
            <svg>
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M9 15l2 2l4 -4" />
            </svg>
        </button>
        <button class="button button--primary button--centered" tooltip="Reset article" tooltip-left type="reset">
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

        {{-- article Name --}}
        <div class="input__tabs">
            <input type="text" name="name" placeholder=" " required value="{{ old('name') }}">
            <label>Name</label>
        </div>
        <div class="details__checkboxes">
            <div class="checkbox__details ">
                <input type="checkbox" id="active" name="active" value="{{ old('active') }}" />
                <label for="active">Active</label>
            </div>
        </div>

        <div class="textarea__tabs details__long">
            <textarea name="short_description" placeholder=" ">{{ old('short_description') }}</textarea>
            <label>Short Description</label>
        </div>

        {{-- article Start Date --}}
        <div class="input__tabs">
            <input type="date" id="start_date" placeholder=" " name="start_date" value="{{ old('start_date') }}">
            <label>Start Date</label>
        </div>

        {{-- article End Date --}}
        <div class="input__tabs">
            <input type="date" id="end_date" placeholder=" " name="end_date" value="{{ old('end_date') }}">
            <label>End Date</label>
        </div>

        {{-- article SEO Title --}}
        <div class="input__tabs">
            <input type="text" name="seo_title" placeholder=" " value="{{ old('seo_title') }}">
            <label>SEO Title</label>
        </div>

        {{-- article SEO Title --}}
        <div class="input__tabs">
            <input type="text" name="seo_id" placeholder=" " value="{{ old('seo_id') }}">
            <label>SEO ID</label>
        </div>

        {{-- Save Button --}}
        <input class="button button--fill button--secondary details__long" type="submit" value="Add New"
            name="submit">

        @error('end_date')
            <span class="error @error('end_date') active @enderror">{{ $message }}</span>
        @enderror
        @error('start_date')
            <span class="error @error('start_date') active @enderror">{{ $message }}</span>
        @enderror
    </section>

</form>
<x-dashboardfooter />
