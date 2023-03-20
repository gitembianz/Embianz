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
    <div class="row jus-c">
        <div class="col-12-xs display-f jus-sb col-12-sm col-12-xl m-1 text-bg">
            <h1 id="title" class="mt-1 font-xl ls-1 text-bg">View - <span
                    class="text-black font-lg">{{ $data->name }}</span> - details</h1>
                    <div class="display-f">
                <a href="{{ route('category') }}" class="boxsha bg-secondary display-f align-center br-xs p-1"> Go Back</a>
                <a href="{{ route('newcategory') }}" class="boxsha bg-secondary ml-1 display-f align-center br-xs p-1"><span class="bg-secondary"><svg class="bg-secondary" width="20px" height="20px" viewBox="0 0 24 24"
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
        {{-- content --}}
        <div class="row gap-4 justify-center">
            {{-- image category --}}
            <div class="col-12-xs col-12-sm col-4-xl text-bg">
                <ul>
                    <li><label class="font-lg text-bg ls-1">Category Image</label></li>
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
            <div class="col-12-xs col-12-sm col-7-xl text-bg">
                <ul>
                    <li class="listelement p-1">
                        <div class="subelement display-g">
                        <label class="font-lg text-bg mb-1 ls-1"> Category Name</label>
                        <span class="boxsha p-1 font-lg talign-c text-bg p-1 br-xs bg-bg-light-9"
                            id="category_name">{{ $data->name }}</span>
                        </div>

                        <div class="subelement display-g">
                        <label class="font-lg mb-1  text-bg ls-1">Category Parrent</label>
                        <span class="boxsha font-lg talign-c text-bg p-1 br-xs bg-bg-light-9"
                            id="category_parrent">{{ $data->parrent }}</span>
                        </div>
                    </li>
                    <li class="listelement p-1">
                        <div class="subelement display-g">
                        <label class="font-lg mb-1 text-bg ls-1">Category Long Description</label>
                        <span class="boxsha font-lg talign-c text-bg p-1 br-xs bg-bg-light-9"
                            id="category_long_description">{{ $data->long_description }}</span>
                        </div>
                    </li>
                    <li class="listelement p-1">
                        <div class="subelement display-g">
                        <label class="font-lg mb-1 text-bg ls-1">Category Short Description</label>
                        <span class="boxsha font-lg talign-c text-bg p-1 br-xs bg-bg-light-9"
                            id="category_short_description">{{ $data->short_description }}</span>
                        </div>
                        <div class="subelement display-g">
                        <label class="font-lg mb-1 text-bg ls-1">Category Sequence</label>
                        <span class="boxsha font-lg talign-c text-bg p-1 br-xs bg-bg-light-9"
                            id="category_sequence">{{ $data->sequence }}</span>
                        </div>
                    </li>
                    <li class="listelement p-1">
                        <div class="subelement display-g">
                        <label class="font-lg mb-1  text-bg ls-1" for="start_date">Category Start Date</label>
                        <span class="boxsha font-lg talign-c text-bg p-1 br-xs bg-bg-light-9"
                            id="category_start_date">{{ $data->start_date }}</span>
                        </div>
                        <div class="subelement display-g">
                        <label class="font-lg mb-1  text-bg ls-1">Category End Date </label>
                        <span class="boxsha font-lg talign-c text-bg p-1 br-xs bg-bg-light-9"
                            id="category_end_date">{{ $data->end_date }}</span>
                        <input type="hidden" name="hidden_id" value="{{ $data->id }}" id="hidden_id">
                        </div>
                    </li>

                </ul>
            </div>
            <div class="row gap-4 mt-1 justify-center talign-c">
                <div class="col-12-xs col-12-sm col-12-xl m-1">
                    <ul>
                        <li>
                            <input type="button"
                                class="edit boxsha br-xs font-lg ls-1 text-bg cursor-p p-1 bg-secondary"
                                value="Edit" name="edit" id="edit">
                            <input type="submit" style="display: none"
                                class="edit br-xs talign-c font-lg ls-1 text-bg cursor-p p-1  bg-secondary"
                                value="Update" id="Update">

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
