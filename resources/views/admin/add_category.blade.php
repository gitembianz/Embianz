<x-dashboardheader />
<x-dashboardnavbar />
@if (session()->has('message') && session()->has('category_name') && session()->has('category_id'))
    <div class="alert__session" id="alertevent">
        <Span class="alert__session-text">{!! session('message') !!} , click for view - <a
                href="{{ route('show_category', ['id' => session('category_id')]) }}">{{ session('category_name') }}</a></Span>
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
<x-dashboardsidebar />
<x-dashboardmodals />
{{-- Page content start --}}


<section class="content">
    <div class="row">
        <ul style="width: 100%">
            <li class="p-1 font-xl">
                <form action="{{ url('/add_category') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- Header --}}
                    <div class="row add_header col-12-xs col-12-sm col-12-xl jus-sb display-f">
                        <h1 id="title" class="ml-1 fw-500 ls-2 text-bg">{{ __('Create Category') }}</h1>
                        <div>
                            <a href="{{ route('category') }}" class="back font-sm"> Go Back</a>
                            <button id="resetform" class="back font-sm" type="reset">Clear form</button>
                        </div>
                    </div>
                    {{-- content --}}
                    <div class="row jus-sb">
                        {{-- image category --}}
                        <div class="col-12-xs col-12-sm col-5-xl text-bg">
                            <ul>
                                <li>
                                    <input type="file" name="media[]" id="imgUpload" multiple
                                        accept="image/*,video/*" onchange="filesManager(this.files)">

                                    <label class="upload" for="imgUpload"><span><svg width="40px" height="40px"
                                                viewBox="0 0 1024.00 1024.00" class="icon" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                    stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path d="M77.312 286.208h503.808v559.104H77.312z" fill="35424b">
                                                    </path>
                                                    <path d="M133.632 342.016h391.68v335.36H133.632z" fill="#FFFFFF">
                                                    </path>
                                                    <path
                                                        d="M189.44 621.568h93.184L236.032 537.6zM375.808 453.632l-93.184 167.936h186.88z"
                                                        fill="#82b09b"></path>
                                                    <path
                                                        d="M637.44 621.568v83.456l337.408-165.376-211.456-432.64-252.928 122.88h110.08l120.32-58.368 127.488 259.584-230.912 113.152z"
                                                        fill="35424b"></path>
                                                </g>
                                            </svg></span> <span class="ml-1">Upload Media</span></label>
                                    <table id="imageTable" class="talign-c mt-1 wid-10">
                                    </table>
                                </li>


                            </ul>
                        </div>
                        {{-- content category --}}
                        <div class="col-12-xs col-12-sm col-6-xl text-bg">
                            <ul>
                                <li class="listelement mb-1">
                                    <div class="wid-4 display-g">
                                        <label class="font-md fw-600 text-bg mb-1 ls-1"> Name</label>
                                        <input class="p-1" type="text" name="category"
                                            placeholder="Enter a category name" required>
                                    </div>
                                    <div class="wid-4 display-g">
                                        <label class="font-md fw-600 text-bg mb-1 ls-1">Parent</label>
                                        <select id="select-category" name="parrent" class="select-parent p-1 text-bg">
                                            <option value="" selected="">Select a parrent</option>
                                            @foreach ($categories as $category_name)
                                                <option value="{{ $category_name }}">{{ $category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                                <li class="listelement mb-1">
                                    <div class="wid-3 display-g">
                                        <label class="font-md fw-600 text-bg mb-1 ls-1">Start Date</label>
                                        <input type="date" class="p-1" id="start_date" name="start_date" required>
                                    </div>
                                    <div class="wid-3 display-g">
                                        <label class="font-md fw-600 text-bg mb-1 ls-1">End Date</label>
                                        <input type="date" id="end_date" class="p-1" name="end_date" required>
                                    </div>
                                    <div class="wid-3 display-g">
                                        <label class="font-md fw-600 text-bg mb-1 ls-1">Sequence</label>
                                        <input type="number" class="p-1 wid-10" name="sequence"
                                            placeholder="Catagory sequence" required>
                                    </div>
                                </li>

                                <li class="listelement mb-1">
                                    <div class="wid-10 display-g">
                                        <label class="font-md fw-600 text-bg mb-1 ls-1">Short Description</label>
                                        <input class="p-1 wid-10" type="text" name="short_description"
                                            placeholder="Short description catagory name" required>
                                    </div>
                                </li>
                                <li class="listelement mb-1">
                                    <div class="wid-10 display-g">
                                        <label class="font-md fw-600 text-bg mb-1 ls-1">Long Description</label>
                                        <textarea name="long_description" class="p-1" placeholder="Long description catagory name" cols="30"
                                            rows="10" required></textarea>
                                    </div>
                                </li>
                                <li class="listelement mb-1">
                                    <div class="wid-10 display-g">
                                        <label class="font-md fw-600 text-bg mb-1 ls-1">SEO Title</label>
                                        <input class="p-1" type="text" name="seo_title"
                                            placeholder="Enter SEO" required>
                                    </div>
                                </li>
                                <li class="listelement mb-1">
                                    <input type="submit" class="add wid-10" value="Add New" name="submit">
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </li>
        </ul>
    </div>
</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardmediahanddler />
<x-dashboardscript />
<x-dashboardscriptcategory />
<x-dashboardfooter />
