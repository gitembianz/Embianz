<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
<x-dashboardright />
{{-- Page content start --}}

<section class="section-container bg-bg">
    {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel ls-1 mb-1 br-xs p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'"
                class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
    <div class="contenttab mt-1 mr-1 bg-white text-bg br-sm">
        <div class="row jus-c">
            <ul>
                <li class="p-1 font-xl">
                    <form action="{{ url('/add_category') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Header --}}
                        <div class="row add_header col-12-xs col-12-sm col-12-xl jus-sb display-f">
                            <h1 id="title" class="ml-1 fw-500 ls-3 text-bg">{{ __('Create Category') }}
                            </h1>
                            <button id="resetform" class="cursor-p mr-1 bg-white text-bg p-1" type="reset">Clear form</button>
                        </div>
                        {{-- content --}}
                        <div class="row gap-2 jus-c">
                            {{-- image category --}}
                            <div class="col-12-xs col-12-sm col-4-xl text-bg">
                                <ul>
                                    <li><label class="font-lg text-bg ls-1">Category Image</label></li>
                                    <li class="p-1 listelement">
                                        <div class="container_img bg-bg-light-9 br-xs">
                                            <figure class="image-container">
                                                <img id="chosen-image">
                                                <figcaption id="file-name">
                                                    {{ _('No image uploaded') }}
                                                </figcaption>
                                            </figure>
                                            <input type="file" name="" id="upload-button" accept="image/*">
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
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"></path>
                                                    </g>
                                                </svg> &nbsp; {{ __('Main image') }} </label>
                                                <input type="button" class="browse1 ml-1 br-xs talign-c cursor-p"
                                        value="or Browse" name="browse" id="browse_main">
                                        <input type="hidden" name="select_image_main_hidden"
                                            id="select_image_main_hidden">
                                            </div>
                                        </div>
                                        {{-- old image input / browse --}}
                                        {{-- <label class="font-lg text-bg ls-1" for="end_date">Image main -</label>
                                    <input type="file" class="image ml-1" id="category_image" name="category_image"
                                        value="cars.png" multiple>
                                    <input type="button" class="browse1 ls-1 ml-1 talign-c cursor-p bg-white"
                                        value="or Browse" name="browse" id="browse_main">
                                    <div class="bloc display-n" id="bloc_image_main">
                                        <span class="ml-1 font-md talign-c text-bg p-1 bg-red" id="select_image_main"
                                            name="select_image_main"></span>
                                        <img width="45px" height="45px" id="img_select_image_main">
                                        <input type="hidden" name="select_image_main_hidden"
                                            id="select_image_main_hidden">
                                    </div> --}}
                                    </li>
                                    <li class="p-1 listelement">

                                        {{-- <label class="font-lg text-bg ls-1" for="end_date">Image search -</label>
                                    <input type="file" class="image ml-1" id="category_image_search"
                                        name="category_image_search">
                                    <input type="button" class="browse1 ls-1 ml-1 talign-c cursor-p bg-white"
                                        value="or Browse" name="browse" id="browse_saerch">
                                    <div class="bloc display-n" id="bloc_image_search">
                                        <span class="ml-1 font-md talign-c text-bg p-1 bg-red"
                                            id="select_image_search" name="select_image_search"></span>
                                        <img width="45px" height="45px" id="img_select_image_search">
                                        <input type="hidden" name="select_image_search_hidden"
                                            id="select_image_search_hidden">
                                    </div> --}}
                                        <div class="container_img bg-bg-light-9 br-xs">
                                            <figure class="image-container">
                                                <img id="chosen-image-search">
                                                <figcaption id="file-name-search">
                                                    {{ _('No image uploaded') }}
                                                </figcaption>
                                            </figure>
                                            <input type="file" name="" id="upload-button-search"
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
                                                <input type="button" class="browse1 ls-1 ml-1 br-xs talign-c cursor-p bg-white"
                                        value="or Browse" name="browse" id="browse_saerch">
                                                </div>
                                        </div>
                                    </li>

                                    <li class="p-1 listelement">
                                        <div class="subelement display-g">
                                        <label class="font-lg text-bg mb-1 ls-1">Image Sequence</label>
                                        <input type="number" class="p-1" name="image_sequence"
                                            placeholder="Image sequence">
                                        </div>

                                    </li>
                                </ul>
                            </div>
                            {{-- content category --}}
                            <div class="col-12-xs col-12-sm col-7-xl text-bg">
                                <ul>
                                    <li class="p-1 listelement">
                                        <div class="subelement display-g">
                                            <label class="font-lg text-bg mb-1 ls-1"> Category Name</label>
                                            <input class="p-1" type="text" name="category"
                                                placeholder="Enter a category name" required>
                                        </div>
                                        <div class="subelement display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Category Parent</label>
                                            <select id="select-category" name="category"
                                                class="select-parent p-1 text-bg">
                                                <option value="" selected="">Select a parrent</option>
                                                @foreach($categories as $category_name)
                                                <option value="{{ $category_name }}">{{ $category_name }}</option>
                                             @endforeach

                                            </select>
                                        </div>
                                    </li>

                                    <li class="p-1 listelement">
                                        <div class="subelement display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Category Long Description</label>
                                            {{-- <input type="text" name="long_description"
                                        placeholder="Long description catagory name" required> --}}
                                            <textarea name="long_description" class="p-1" placeholder="Long description catagory name" style="width: 210%" id=""
                                                cols="30" rows="10" required></textarea>
                                        </div>
                                    </li>
                                    <li class="p-1 listelement">
                                        <div class="subelement display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Category Short Description</label>
                                            <input class="p-1" type="text" name="short_description"
                                                placeholder="Short description catagory name" required>
                                        </div>
                                        <div class="subelement display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Category Sequence</label>
                                            <input type="number" class="p-1" name="sequence" placeholder="Catagory sequence"
                                                required>
                                        </div>
                                    </li>
                                    <li class="listelementbutton p-1 listelement">
                                        <div class="subelement display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Category Start Date</label>
                                            <input type="date" class="p-1" id="start_date" name="start_date" required>
                                        </div>
                                        <div class="subelement display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Category End Date</label>
                                            <input type="date" id="end_date" class="p-1" name="end_date" required>
                                        </div>
                                    </li>
                                    <li class="p-1 font-xl">
                                        <input type="submit" class="addcategory display-f align-center br-xs float-l p-1 mb-1"
                                            value="Add new" name="submit">
                                        <a href="{{ route('category') }}"
                                        class="backcategory display-f float-r br-xs p-1"> Go Back</a>
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
<x-dashboardscript />
<x-dashboardscriptcategory />
<x-dashboardfooter />
