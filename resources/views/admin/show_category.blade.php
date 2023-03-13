<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
<x-dashboardright />
{{-- Page content start --}}
<section class="section-container p-1 bg-bg">
    {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel ls-1 mb-1 br-xs p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'"
                class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
    <div class="contenttab bg-white text-bg p-2 br-sm">
    <div class="row gap-4 mt-2 mb-2 justify-center talign-c">
        <div class="col-12-xs display-c col-12-sm col-12-xl m-1 text-bg">
            <h1 id="title" class="mt-1 talign-c font-xl ls-1 text-bg">View - <span
                    class="text-bg fw-800">{{ $data->name }}</span> - details</h1>
                    <div style="right: 10%" class="display-f pos-abs">
                <a href="{{ route('category') }}" class="bg-secondary-dark-1 display-f align-center br-xs p-1 mb-1"> Go Back</a>
                <a href="{{ route('newcategory') }}" class="bg-secondary-dark-1 ml-1 display-f align-center br-xs p-1 mb-1"><span class="bg-secondary-dark-1"><svg class="bg-secondary-dark-1" width="20px" height="20px" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg" fill="#35424b">
                    <g id="SVGRepo_bgCarrier" stroke-width="1"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"
                        stroke="#CCCCCC" stroke-width="0.288"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M6 12h6V6h1v6h6v1h-6v6h-1v-6H6z"></path>
                        <path fill="none" d="M0 0h24v24H0z"></path>
                    </g>
                </svg></span> Add new</a>
            </div>
        </div>
    </div>
    <form  action="{{ route('category_update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row gap-4 justify-center">
            <div class="col-12-xs col-12-sm col-5-xl text-bg br-xs bg-secondary-light-7">
                <ul class="p-1">
                    <li class="listelement p-1">
                        <label class="font-lg align-left text-bg ls-1">Name -</label>
                        <span class="ml-1 font-lg talign-c text-bg p-1 br-xs bg-secondary-light-4"
                            id="category_name">{{ $data->name }}</span>
                    </li>
                    <li class="listelement p-1">
                        <label class="font-lg text-bg ls-1">Parrent -</label>
                        <span class="ml-1 font-lg talign-c text-bg p-1 br-xs bg-secondary-light-4"
                            id="category_parrent">{{ $data->parrent }}</span>
                    </li>
                    <li class="listelement p-1">
                        <label class="font-lg text-bg ls-1">Long Description -</label>
                        <span class="ml-1 font-lg talign-c text-bg p-1 br-xs bg-secondary-light-4"
                            id="category_long_description">{{ $data->long_description }}</span>
                    </li>
                    <li class="listelement p-1">
                        <label class="font-lg text-bg ls-1">Short Description -</label>
                        <span class="ml-1 font-lg talign-c text-bg p-1 br-xs bg-secondary-light-4"
                            id="category_short_description">{{ $data->short_description }}</span>
                    </li>
                    <li class="listelement p-1">
                        <label class="font-lg text-bg ls-1">Sequence -</label>
                        <span class="ml-1 font-lg talign-c text-bg p-1 br-xs bg-secondary-light-4"
                            id="category_sequence">{{ $data->sequence }}</span>
                    </li>
                </ul>
            </div>
            <div class="col-12-xs col-12-sm col-5-xl br-xs bg-secondary-light-7">
                <ul>
                    <li class="listelement p-1">
                        <label class="font-lg  text-bg ls-1" for="start_date">Start Date -</label>
                        <span class="ml-1 font-lg talign-c text-bg p-1 br-xs bg-secondary-light-4"
                            id="category_start_date">{{ $data->start_date }}</span>
                    </li>
                    <li class="listelement p-1">
                        <label class="font-lg text-bg ls-1">End Date -</label>
                        <span class="ml-1 font-lg talign-c text-bg p-1 br-xs bg-secondary-light-4"
                            id="category_end_date">{{ $data->end_date }}</span>
                        <input type="hidden" name="hidden_id" value="{{ $data->id }}" id="hidden_id">
                    </li>
                    <li class="listelement p-1">
                        <label class="font-lg text-bg ls-1"> Image Main-</label>
                        @if (isset($data) && !is_null($data->image->first()))
                            <img id="category_image_main" src="/categories/{{ $data->image->first()->img_main_path }}"
                                alt="category_image_main" width="100" height="50">
                        @else
                            <span class="ml-1 font-lg talign-c text-bg p-1 br-xs bg-secondary-light-4"
                                id="category_image_main">{{ __('Image not found') }}</span>
                        @endif
                    </li>
                    <li class="listelement p-1">
                        <label class="font-lg text-bg ls-1" for="end_date"> Image Search-</label>

                        @if (isset($data) && !is_null($data->image->first()))
                            <img id="category_image_search"
                                src="/categories/{{ $data->image->first()->img_search_path }}"
                                alt="category_image_search" width="100" height="50">
                        @else
                            <span class="ml-1 font-lg talign-c text-bg p-1 br-xs bg-secondary-light-4"
                                id="category_image_search">{{ __('Image not found') }}</span>
                        @endif
                    </li>
                    <li class="listelement p-1">
                        <label class="font-lg text-bg ls-1" for="end_date">Image Sequence -</label>
                        @if (isset($data) && !is_null($data->image->first()))
                            <span class="ml-1 font-lg talign-c text-bg p-1 br-xs bg-secondary-light-4"
                                id="category_image_sequence">{{ $data->image->first()->img_sequence }}</span>
                        @else
                            <span class="ml-1 font-lg talign-c text-bg p-1 br-xs bg-secondary-light-4"
                                id="category_image_sequence">{{ __('Image Sequence not found') }}</span>
                        @endif
                    </li>
                </ul>
            </div>
            <div class="row gap-4 mt-1 justify-center talign-c">
                <div class="col-12-xs col-12-sm col-12-xl m-1">
                    <ul>
                        <li>
                            <input type="button"
                                class="edit br-xs font-lg ls-1 text-bg ml-1 cursor-p p-1 bg-secondary-light-1"
                                value="Edit" name="edit" id="edit">
                            <input type="submit" style="display: none"
                                class="edit br-xs talign-c font-lg ls-1 text-bg ml-1 cursor-p p-1  bg-secondary-light-1"
                                value="Update" name="edit" id="Update">

                            <input type="button"
                                class="delete ml-1 font-lg text-bg br-xs ls-1 cursor-p p-1 bg-secondary-light-1"
                                value="Delete" name="delete" id="delete">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>
</section>
{{-- page content end --}}
<x-dashboardscript />
<x-dashboardscriptcategory />
<x-dashboardfooter />