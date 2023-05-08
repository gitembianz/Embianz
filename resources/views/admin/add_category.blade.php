<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
<x-dashboardright />
{{-- Page content start --}}
<section class="section-container bg-bg">
 {{-- Session message --}}
    @if (session()->has('message') && session()->has('category_name') && session()->has('category_id'))
        <div class="alert bg-secondary pos-rel ls-1 talign-c mb-1 br-xs p-1" id="alertevent">
            {!! session('message') !!}
            <Span> Click for view - <a href="{{ route('show_category', ['id' => session('category_id')]) }}" class="fw-800 font-lg">{{ session('category_name') }}</a></Span>
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'" class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
    <div class="contenttab mt-1 mr-1 bg-white text-bg br-sm">
        <div class="row">
            <ul style="width: 100%">
                <li class="p-1 font-xl">
                    <form action="{{ url('/add_category') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Header --}}
                        <div class="row add_header col-12-xs col-12-sm col-12-xl jus-sb display-f">
                            <h1 id="title" class="ml-1 fw-500 ls-2 text-bg">{{ __('Create Category') }}</h1>
                            <button id="resetform" class="cursor-p mr-1 bg-white text-bg p-1" type="reset">Clear form</button>
                        </div>
                        {{-- content --}}
                        <div class="row gap-2 jus-c">
                            {{-- image category --}}
                            <div class="col-12-xs col-12-sm col-5-xl text-bg">
                                <ul>
                                    <li class="talign-c p-2">
                                        <input type="file" name="media[]" id="imgUpload" multiple accept="image/*,video/*" onchange="filesManager(this.files)">

                                        <label class="button ml-3 font-md" for="imgUpload">Upload Media</label>
                                      <table id="imageTable" class="talign-c mt-2">
                                      </table>
                                    </li>


                                </ul>
                            </div>
                            {{-- content category --}}
                            <div class="col-12-xs col-12-sm col-6-xl text-bg">
                                <ul>
                                    <li class="p-1 listelement">
                                        <div class="wid-4 display-g">
                                            <label class="font-md fw-600 text-bg mb-1 ls-1"> Name</label>
                                            <input class="p-1" type="text" name="category" placeholder="Enter a category name" required>
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
                                    <li class="listelementbutton p-1 listelement">
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
                                            <input type="number" class="p-1 wid-10" name="sequence" placeholder="Catagory sequence" required>
                                        </div>
                                    </li>

                                    <li class="p-1 listelement">
                                        <div class="wid-10 display-g">
                                            <label class="font-md fw-600 text-bg mb-1 ls-1">Short Description</label>
                                            <input class="p-1 wid-10" type="text" name="short_description" placeholder="Short description catagory name" required>
                                        </div>
                                    </li>
                                    <li class="p-1 listelement">
                                        <div class="wid-10 display-g">
                                            <label class="font-md fw-600 text-bg mb-1 ls-1">Long Description</label>
                                            <textarea name="long_description" class="p-1" placeholder="Long description catagory name" cols="30" rows="10" required></textarea>
                                        </div>
                                    </li>
                                    <li class="listelement p-1">
                                        <div class="wid-10 display-g">
                                            <label class="font-md fw-600 text-bg mb-1 ls-1">SEO Title</label>
                                            <input class="p-1" type="text" name="seo_title" placeholder="Enter SEO" required>
                                        </div>
                                    </li>
                                    <li class="p-1 font-xl">
                                        <input type="submit" class="addcategory align-center br-xs float-l p-1 mb-1" value="Add new" name="submit">
                                        <a href="{{ route('category') }}" class="backcategory float-r br-xs p-1"> Go Back</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</section>
{{-- page content end --}}
<x-dashboardmediahanddler />
<x-dashboardscript />
<x-dashboardscriptcategory />
<x-dashboardfooter />
