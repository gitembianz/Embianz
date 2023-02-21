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
    <div class="row gap-4 mt-2 mb-2 justify-center talign-c">
        {{-- page title --}}
        <div class="col-12-xs display-c col-12-sm col-12-xl m-1 text-white">
            <h1 id="title" class="talign-l text-white font-xl ml-2 ls-1 text-bg">{{ __('Create Category') }}
            </h1>
        </div>
        <ul class="pt-1">

            <li class="p-1 font-xl">
                <form action="{{ url('/add_category') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12-xs col-12-sm col-12-xl text-white">
                        <button class="reset bg-bg cursor-p text-white p-1" id="resetform" type="reset">Clear
                            form</button>
                        </div>
                    </div>
                    <div class="row gap-2 justify-center">
                        <div class="col-12-xs col-12-sm mt-1 col-5-xl text-white">
                            <ul class="pt-1">
                                <li class="p-1 listelement">
                                    <label class="font-lg text-white ls-1">Name -</label>
                                    <input type="text" name="category" placeholder="Enter a category name"
                                        required>
                                </li>
                                <li class="p-1 listelement">
                                    <label class="font-lg text-white ls-1">Parent -</label>
                                    <select id="select-category" name="category" class="select-parent p-1 bg-bg text-secondary">
                                        <option value="">Select a category</option>
                                    </select>
                                </li>
                                <li class="p-1 listelement">
                                    <label class="font-lg text-white ls-1">Long Description -</label>
                                    <input type="text" name="long_description"
                                        placeholder="Long description catagory name" required>
                                </li>
                                <li class="p-1 listelement">
                                    <label class="font-lg text-white ls-1">Short Description -</label>
                                    <input type="text" name="short_description"
                                        placeholder="Short description catagory name" required>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12-xs col-12-sm mt-1 col-5-xl text-white">
                            <ul>
                                <li class="p-1 listelement">
                                    <label class="font-lg text-white ls-1">Sequence -</label>
                                    <input type="number" name="sequence" placeholder="Catagory sequence" required>
                                </li>
                                <li class="p-1 listelement">
                                    <label class="font-lg text-white ls-1">Start Date -</label>
                                    <input class="ml-1" type="date" id="start_date" name="start_date" required>
                                </li>
                                <li class="p-1 listelement">
                                    <label class="font-lg text-white ls-1">End Date -</label>
                                    <input class="ml-1" type="date" id="end_date" name="end_date" required>
                                </li>
                                <li class="p-1 listelement">

                                    <label class="font-lg text-white ls-1">Image Sequence -</label>
                                    <input type="number" class="ml-1" name="image_sequence"
                                        placeholder="Image sequence">

                                </li>
                            </ul>
                        </div>
                        <div class="col-12-xs col-12-sm mt-1 col-8-xl text-white">
                            <ul>
                                <li class="p-1 listelement">

                                    <label class="font-lg text-white ls-1" for="end_date">Image main -</label>
                                    <input type="file" class="image ml-1" id="category_image"
                                        name="category_image" value="cars.png" multiple>
                                    <input type="button"
                                        class="browse1 ls-1 ml-1 talign-c cursor-p bg-hover-bg-light-2"
                                        value="or Browse" name="browse" id="browse_main">
                                        <div class="bloc display-n" id="bloc_image_main">
                                            <span class="ml-1 font-md talign-c text-white p-1 bg-red"
                                            id="select_image_main" name="select_image_main"></span>
                                            <img width="45px" height="45px" id="img_select_image_main">
                                            <input type="hidden" name="select_image_main_hidden" id="select_image_main_hidden">
                                        </div>


                                </li>
                                <li class="p-1 listelement">

                                    <label class="font-lg text-white ls-1" for="end_date">Image search -</label>
                                    <input type="file" class="image ml-1" id="category_image_search"
                                        name="category_image_search">
                                    <input type="button"
                                        class="browse1 ls-1 ml-1 talign-c cursor-p bg-hover-bg-light-2"
                                        value="or Browse" name="browse" id="browse_saerch">
                                        <div class="bloc display-n" id="bloc_image_search">
                                            <span class="ml-1 font-md talign-c text-white p-1 bg-red"
                                            id="select_image_search" name="select_image_search"></span>
                                            <img width="45px" height="45px" id="img_select_image_search">
                                            <input type="hidden" name="select_image_search_hidden" id="select_image_search_hidden">
                                        </div>
                                </li>

                                <li class="p-1 font-xl"><input type="submit" class="submit cursor-p"
                                        value="Add" name="submit">
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
<x-dashboardscript />
<x-dashboardscriptcategory />
<x-dashboardfooter />

