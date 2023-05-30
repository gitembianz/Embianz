<x-dashboardheader />
<x-dashboardnavbar />
{{-- Display session message --}}
@if (session()->has('message') && session()->has('item_name') && session()->has('item_id'))
    <div class="alert__session" id="alertevent">
        <Span class="alert__session-text">{!! session('message') !!} , click for view - <a
                href="{{ route('show_category', ['id' => session('item_id')]) }}">{{ session('item_name') }}</a></Span>
        <button class="alert__session-btn" type="button"
            onclick="document.getElementById('alertevent').style.display='none'" data-bs-dismiss="alert"
            aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
@endif
{{-- End Section session message --}}
<x-dashboardsidebar />
<x-dashboardmodals />
{{-- Page content start --}}


<section class="content">
    <form class="item" action="{{ url('/add_category') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Item Header --}}
        <div class="item__header">
            <h1 class="item__header-title" id="title">{{ __('Create Category') }}</h1>
            <div class="item__header-buttons">
                <a class="item__header-btn" href="{{ route('category') }}"> Go Back</a>
                <button class="item__header-btn" id="resetform" type="reset">Clear form</button>
            </div>
        </div>

        {{-- Item Upload --}}
        <div class="item__upload">
            <input type="file" name="media[]" id="imgUpload" multiple
                accept="image/*,video/*"onchange="filesManager(this.files)">

            <label class="item__upload-btn" for="imgUpload">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                    stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                Upload Media
            </label>

            <table id="imageTable" class="table"></table>
        </div>

        {{-- Item Form --}}
        <div class="item__form">
            <div class="item__form-input">
                <label> Name</label>
                <input type="text" name="category" placeholder="Enter a category name" required>
            </div>
            <div class="item__form-input">
                <label>Parent</label>
                <select id="select-category" name="parrent">
                    <option value="" selected="">Select a parrent</option>
                    @foreach ($categories as $category_name)
                        <option value="{{ $category_name }}">{{ $category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="item__form-input">
                <label>Start Date</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>
            <div class="item__form-input">
                <label>End Date</label>
                <input type="date" id="end_date" name="end_date" required>
            </div>
            <div class="item__form-input">
                <label>Sequence</label>
                <input type="number" name="sequence" placeholder="Catagory sequence" required>
            </div>
            <div class="item__form-input item__form-long">
                <label>Short Description</label>
                <input type="text" name="short_description" placeholder="Short description catagory name" required>
            </div>
            <div class="item__form-input item__form-long">
                <label>Long Description</label>
                <textarea name="long_description" placeholder="Long description catagory name" cols="30" rows="10" required></textarea>
            </div>
            <div class="item__form-input item__form-long">
                <label>SEO Title</label>
                <input type="text" name="seo_title" placeholder="Enter SEO" required>
            </div>
            <input class="item__form-btn item__form-long" type="submit" value="Add New" name="submit">
        </div>
    </form>
</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardmediahanddler />
<x-dashboardscript />
<x-dashboardscriptcategory />
<x-dashboardfooter />
