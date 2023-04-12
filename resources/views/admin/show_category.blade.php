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
                <h1 id="title" class="mt-1 font-xl ls-1 text-bg">View category - <span
                        class="text-black font-lg">{{ $data->name }}</span></h1>
                <div class="display-f">
                    <a href="{{ route('category') }}" class="boxsha bg-secondary display-f align-center br-xs p-1"> Go
                        Back</a>
                    <a href="{{ route('newcategory') }}"
                        class="boxsha bg-secondary ml-1 display-f align-center br-xs p-1"><span
                            class="bg-secondary"><svg class="bg-secondary" width="20px" height="20px"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#35424b">
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
        <form action="{{ route('category_update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- content --}}
            <div class="row gap-4 justify-center">
                {{-- image category --}}
                <div class="col-12-xs col-12-sm col-4-xl text-bg">
                    <ul>
                        <li><label class="font-lg text-bg ls-1">Category Image</label></li>
                        <li class="listelement p-1">
                            <div class="container_img bg-bg-light-9 br-xs">
                                <figure class="image-container">
                                    <img id="chosen-image">
                                    @if (isset($data) && !is_null($data->image->first()) && !is_null($data->image->first()->img_main_path))
                                        <img id="chosen-image"
                                            src="/categories/{{ $data->image->first()->img_main_path }}"
                                            alt="category_image_main" width="100" height="50">
                                        <figcaption id="file-name">
                                            {{ _('Main Image is ') . $data->image->first()->img_main_path }}
                                        </figcaption>
                                    @else
                                        <figcaption id="file-name">
                                            {{ _('No main image uploaded') }}
                                        </figcaption>
                                    @endif
                                </figure>
                                <div id="uploadcontrollersm" class="display-n">
                                    <input type="file" name="category_image_main" id="upload-button"
                                        accept="image/*">
                                    <div class="display-f">
                                        <label for="upload-button" class="br-xs cursor-p"><svg width="64px"
                                                height="64px" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                    stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path
                                                        d="M13 4H8.8C7.11984 4 6.27976 4 5.63803 4.32698C5.07354 4.6146 4.6146 5.07354 4.32698 5.63803C4 6.27976 4 7.11984 4 8.8V15.2C4 16.8802 4 17.7202 4.32698 18.362C4.6146 18.9265 5.07354 19.3854 5.63803 19.673C6.27976 20 7.11984 20 8.8 20H15.2C16.8802 20 17.7202 20 18.362 19.673C18.9265 19.3854 19.3854 18.9265 19.673 18.362C20 17.7202 20 16.8802 20 15.2V11"
                                                        stroke="#35424b" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path
                                                        d="M4 16L8.29289 11.7071C8.68342 11.3166 9.31658 11.3166 9.70711 11.7071L13 15M13 15L15.7929 12.2071C16.1834 11.8166 16.8166 11.8166 17.2071 12.2071L20 15M13 15L15.25 17.25"
                                                        stroke="#35424b" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path d="M18 8V3M18 3L16 5M18 3L20 5" stroke="#35424b"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    </path>
                                                </g>
                                            </svg> &nbsp; {{ __('Main image') }} </label>
                                        <input type="button" class="browse1 ml-1 br-xs talign-c cursor-p"
                                            value="or Browse" name="browse" id="browse_main">
                                        <input type="hidden" name="select_image_main_hidden"
                                            id="select_image_main_hidden">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="listelement p-1">
                            <div class="container_img bg-bg-light-9 br-xs">
                                <figure class="image-container">
                                    <img id="chosen-image-search">
                                    @if (isset($data) && !is_null($data->image->first()) && !is_null($data->image->first()->img_search_path))
                                        <img id="chosen-image-search"
                                            src="/categories/{{ $data->image->first()->img_search_path }}"
                                            alt="category_image_search" width="100" height="50">
                                        <figcaption id="file-name-search">
                                            {{ _('Search Image is ') . $data->image->first()->img_search_path }}
                                        </figcaption>
                                    @else
                                        <figcaption id="file-name-search">
                                            {{ _('No search image uploaded') }}
                                        </figcaption>
                                    @endif
                                </figure>
                                <div id="uploadcontrollerss" class="display-n">
                                    <input type="file" name="category_image_search" id="upload-button-search"
                                        accept="image/*">
                                    <div class="display-f">
                                        <label for="upload-button-search" class="br-xs cursor-p"><svg width="64px"
                                                height="64px" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                    stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path
                                                        d="M13 4H8.8C7.11984 4 6.27976 4 5.63803 4.32698C5.07354 4.6146 4.6146 5.07354 4.32698 5.63803C4 6.27976 4 7.11984 4 8.8V15.2C4 16.8802 4 17.7202 4.32698 18.362C4.6146 18.9265 5.07354 19.3854 5.63803 19.673C6.27976 20 7.11984 20 8.8 20H15.2C16.8802 20 17.7202 20 18.362 19.673C18.9265 19.3854 19.3854 18.9265 19.673 18.362C20 17.7202 20 16.8802 20 15.2V11"
                                                        stroke="#35424b" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path
                                                        d="M4 16L8.29289 11.7071C8.68342 11.3166 9.31658 11.3166 9.70711 11.7071L13 15M13 15L15.7929 12.2071C16.1834 11.8166 16.8166 11.8166 17.2071 12.2071L20 15M13 15L15.25 17.25"
                                                        stroke="#35424b" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path d="M18 8V3M18 3L16 5M18 3L20 5" stroke="#35424b"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </g>
                                            </svg> &nbsp; {{ __('Search image') }}</label>
                                        <input type="button"
                                            class="browse1 ls-1 ml-1 br-xs talign-c cursor-p bg-white"
                                            value="or Browse" name="browse" id="browse_search">
                                        <input type="hidden" name="select_image_search_hidden"
                                            id="select_image_search_hidden">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="listelement p-1">
                            <div class="subelement40 display-g">
                                <label class="font-lg text-bg mb-1 ls-1">Image Sequence</label>
                                @if (isset($data) && !is_null($data->image->first()) && !is_null($data->image->first()->img_sequence))
                                    <span class="boxsha font-lg talign-c text-bg p-1 br-xs bg-bg-light-9"
                                        id="category_image_sequence">{{ $data->image->first()->img_sequence }}</span>
                                @else
                                    <span class="talign-c text-bg p-1 br-xs bg-bg-light-9"
                                        id="category_image_sequence">{{ __('Not found') }}</span>
                                @endif
                            </div>
                            <div class="subelement40 display-g">
                                <label class="font-lg mb-1 text-bg ls-1">Category Sequence</label>
                                <span class="font-md talign-c text-bg p-1 br-xs bg-bg-light-9"
                                    id="category_sequence">{{ $data->sequence }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-12-xs col-12-sm col-7-xl text-bg">
                    <ul>
                        <li class="listelement p-1">
                            <div class="subelement40 display-g">
                                <label class="font-lg text-bg mb-1 ls-1"> Category Name</label>
                                <span class="p-1 talign-c text-bg p-1 br-xs bg-bg-light-9"
                                    id="category_name">{{ $data->name }}</span>
                            </div>

                            <div class="subelement40 display-g">
                                <label class="font-lg mb-1  text-bg ls-1">Category Parrent</label>
                                <span class="talign-c text-bg p-1 br-xs bg-bg-light-9"
                                    id="category_parrent">{{ $data->parrent }}</span>
                            </div>
                        </li>
                        <li class="listelement p-1">
                            <div class="subelement40 display-g">
                                <label class="font-lg mb-1  text-bg ls-1" for="start_date">Category Start Date</label>
                                <span class=" talign-c text-bg p-1 br-xs bg-bg-light-9"
                                    id="category_start_date">{{ $data->start_date }}</span>
                            </div>
                            <div class="subelement40 display-g">
                                <label class="font-lg mb-1  text-bg ls-1">Category End Date </label>
                                <span class="talign-c text-bg p-1 br-xs bg-bg-light-9"
                                    id="category_end_date">{{ $data->end_date }}</span>
                                <input type="hidden" name="hidden_id" value="{{ $data->id }}" id="hidden_id">
                            </div>
                        </li>
                        <li class="listelement p-1">
                            <div class="subelement100 display-b">
                                <label class="font-lg text-bg ls-1">Category Long Description</label>
                                <span class="mt-1 display-b text-bg p-1 br-xs bg-bg-light-9"
                                    id="category_long_description">{{ $data->long_description }}</span>
                            </div>
                        </li>
                        <li class="listelement p-1">
                            <div class="subelement100 display-b">
                                <label class="font-lg text-bg ls-1">Category Short Description</label>
                                <span class="mt-1 display-b text-bg p-1 br-xs bg-bg-light-9"
                                    id="category_short_description">{{ $data->short_description }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="row gap-4 mt-1 justify-center talign-c">
                    <div class="col-12-xs col-12-sm col-12-xl m-1">
                        <ul>
                            <li>
                                <input type="button"
                                    class="edit br-xs font-lg ls-1 text-bg cursor-p p-1 bg-secondary"
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
