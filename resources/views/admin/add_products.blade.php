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
        {{-- Header --}}
        <ul>
            <li class="p-1 font-xl">
                <form  action="{{ url('/new_products') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row add_header col-12-xs col-12-sm col-12-xl jus-sb display-f">
            <h1 id="title" class="ml-1 fw-500 ls-3 text-bg">{{ __('New Product') }}</h1>
            <button id="resetform" class="cursor-p mr-1 bg-white text-bg p-1" type="reset">Clear form</button>
        </div>
         {{-- content --}}
        <div class="row gap-2 jus-c">
            <div class="col-12-xs col-12-sm col-4-xl text-bg">
                <ul>
                    <li><label class="font-lg text-bg ls-1">Product Image</label></li>
                    <li class="p-1 listelement">
                        <div class="container_img bg-bg-light-9 br-xs">
                            <figure class="image-container">
                                <img id="chosen-image">
                                <figcaption id="file-name">
                                    {{ _('No image uploaded') }}
                                </figcaption>
                            </figure>
                            <input type="file" name="product_image" id="upload-button" accept="image/*">
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
                            </div>
                        </div>
                    </li>
                    <li class="p-1 listelement">
                        <div class="container_img_multiple bg-bg-light-9 br-xs">
                            <div class="images"></div>
                            <input type="file" name="product_image" id="upload-button-multiple" accept="image/*" multiple>
                            <div class="talign-c">
                            <label for="upload-button-multiple" class="br-xs cursor-p"><svg width="64px"
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
                                </svg> &nbsp; {{ __('Select Multiple Images') }} </label>
                                <p class="font-md mt-1 display-b" id="num-of-files">{{ __('No files Chosen') }}</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-12-xs col-12-sm col-7-xl text-bg">
                <ul>
                    <li class="listelement p-1">
                        <div class="subelement display-g">
                            <label class="font-lg text-bg mb-1 ls-1"> Product Name</label>
                            <input class="p-1" type="text" name="product_name"
                                placeholder="Enter a Product name" required>
                        </div>
                        <div class="subelement display-g">
                            <label class="font-lg text-bg mb-1 ls-1">Product Category</label>
                            <select id="select-category" name="category"
                                class="select-parent p-1 text-bg">
                                <option value="" selected="">Select a category</option>
                                @foreach($categories as $category_name)
                                <option value="{{ $category_name }}">{{ $category_name }}</option>
                             @endforeach
                            </select>
                        </div>
                    </li>
                    <li class="listelement p-1">
                        <div class="subelement display-g">
                            <label class="font-lg text-bg mb-1 ls-1">Product Long Description</label>
                            
                            <textarea name="long_description" class="p-1" placeholder="Enter a product Long description" style="width: 200%"
                                cols="30" rows="10" required></textarea>
                        </div>
                    </li>
                    <li class="listelement p-1">
                        <div class="subelement display-g">
                            <label class="font-lg text-bg mb-1 ls-1">Product Short Description</label>
                            <input class="p-1" type="text" name="short_description"
                                placeholder="Product short description" required>
                        </div>
                        <div class="subelement display-g">
                            <label class="font-lg text-bg mb-1 ls-1">Product Quantity</label>
                            <input type="number" class="p-1" name="quantity" placeholder="Select Quantity"
                                required>
                        </div>
                    </li>
                    <li class="listelement p-1">
                        <div class="subelement display-g">
                            <label class="font-lg text-bg mb-1 ls-1">Product Start Date</label>
                            <input type="date" class="p-1" id="start_date" name="start_date" required>
                        </div>
                        <div class="subelement display-g">
                            <label class="font-lg text-bg mb-1 ls-1">Product End Date</label>
                            <input type="date" id="end_date" class="p-1" name="end_date" required>
                        </div>
                    </li>
                    <li class="listelement p-1">
                        <div class="subelement display-g">
                            <label class="font-lg text-bg mb-1 ls-1">Product Status</label>
                            <select id="select-category" name="status"
                                class="select-parent p-1 text-bg">
                                <?php 
                                $status = ["active", "inactive", "low stock"];
                                ?>
                                <option value="" selected="">Select a status</option>
                                @foreach($status as $status_name)
                                <option value="{{ $status_name }}">{{ $status_name }}</option>
                             @endforeach
                            </select>
                        </div>
                        <div class="subelement display-g">
                            <label class="font-lg text-bg mb-1 ls-1">SEO Title</label>
                            <input class="p-1" type="text" name="seo_title"
                                placeholder="Enter SEO" required>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="product_footer row justify-center talign-c">
                <div class="col-12-xs col-12-sm col-12-xl">
                    <ul>
                        <li>
                            <input type="submit" class="addcategory display-f align-center br-xs float-l p-1 mb-1"
                            value="Add new" name="submit">
                        <a href="{{ route('products') }}"
                        class="backcategory display-f ml-2 float-r br-xs p-1"> Go Back</a>


                        </li>
                    </ul>
                </div>
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
<x-dashboardscriptproduct />
<x-dashboardfooter />
