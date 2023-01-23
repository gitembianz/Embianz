<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
{{-- Page content start --}}
<section class="section-container bg-sidebar-bg-light-1">
    {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel ls-1 p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'"
                class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
    <h1 id="title" class="mt-1 talign-c font-xl ls-1 text-white">View {{ $data->name }} details</h1>
    <div class="row talign-c">
        <div class="col-12-xs col-12-sm col-12-xl m-1 text-white">
            {{-- <h2>{{ $data->name }}</h2> --}}
            {{-- <img src="/categories/{{$data->image->first()->img_main_path}}" alt="" width="50" height="50" > --}}
            <ul class="mb-2">
                <li class="font-xl">
                    <form id="editcategory_form" action="{{ url('/category_update') }}" method="POST">
                        @csrf
                        <ul class="mb-2">
                            <li class="p-1 pt-3 font-xl">
                                <label class="font-lg text-white ls-1" for="end_date">Name -</label>
                                <input type="text" class="text-secondary p-1 bg-bg" name="categoryname"
                                    id="category_name" value="{{ $data->name }}" readonly>
                            </li>
                            <li class="p-1 font-xl">
                                <label class="font-lg text-white ls-1" for="end_date">Parrent -</label>
                                <input class="text-secondary p-1 bg-bg" type="text" name="category_parrent"
                                    id="category_parrent" value="{{ $data->parrent }}" readonly>
                            </li>
                            <li class="p-1 font-xl">
                                <label class="font-lg text-white ls-1" for="end_date">Long Description -</label>
                                <input type="text" class="text-secondary p-1 bg-bg" id="category_long_description"
                                    name="category_long_description" value="{{ $data->long_description }}" readonly>
                            </li>
                            <li class="p-1 font-xl">
                                <label class="font-lg text-white ls-1" for="end_date">Short Description -</label>
                                <input type="text" class="text-secondary p-1 bg-bg" name="category_short_description"
                                    id="category_short_description" value="{{ $data->short_description }}" readonly>
                            </li>
                            <li class="p-1 font-xl">
                                <label class="font-lg text-white ls-1" for="end_date">Sequence -</label>
                                <input type="number" class="text-secondary p-1 bg-bg" name="category_sequence"
                                    id="category_sequence" value="{{ $data->sequence }}" readonly>
                            </li>
                            <li class="p-1 font-xl">
                                <div class="element">
                                    <label class="font-lg  text-white ls-1" for="start_date">Start Date -</label>
                                    <input class="ml-1 text-secondary p-1 bg-bg" type="date" id="category_start_date"
                                        name="category_start_date" value="{{ $data->start_date }}" readonly>
                                </div>
                            </li>
                            <li class="p-1 font-xl talign-c">
                                <div class="element">
                                    <label class="font-lg text-white ls-1" for="end_date">End Date -</label>
                                    <input class="ml-1 text-secondary p-1 bg-bg" type="date" id="category_end_date"
                                        name="category_end_date" value="{{ $data->end_date }}" readonly>
                                    <input type="hidden" name="hidden_id" id="hidden_id">
                                </div>
                            </li>
                            <li class="p-1 font-xl talign-c">
                                <div class="element">
                                    <label class="font-lg text-white ls-1" for="end_date"> Image Main-</label>
                                    {{-- <img id="category_image" width="50" height="50"> --}}
                                    @if (isset($data) && !is_null($data->image->first()))
                                        <img id="category_image_main"
                                            src="/categories/{{ $data->image->first()->img_main_path }}"
                                            alt="category_image_main" width="100" height="100">
                                    @else
                                        <img id="category_image_main" src="/categories/defaultcategory.jpg"
                                            alt="category_image_main" width="100" height="100">
                                    @endif

                                </div>
                            </li>
                        </ul>
                    </form>
                </li>
                <li><input
                        onclick="document.getElementById('modal-category').style.display='block'; document.getElementById('viewmodal-category').style.display='none'"
                        class="cursor-p ls-1 talign-c bg-hover-bg-light-2" value="Add New" id="new">
                    <input type="button" class="edit ls-1 ml-1 talign-c cursor-p bg-hover-bg-light-2" value="Edit"
                        name="edit" id="edit">

                    <input type="button" class="delete ml-1 ls-1 talign-c cursor-p bg-hover-bg-light-2"
                        value="Delete" name="delete" id="delete">
                </li>
            </ul>
        </div>
    </div>
</section>
{{-- page content end --}}
<x-dashboardscript />
<x-dashboardfooter />
