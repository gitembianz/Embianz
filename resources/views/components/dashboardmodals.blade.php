{{--                   Modals           --}}
{{-- ////////////////////////////////// --}}
{{-- Modal User --}}
<div id="modal-user" class="modal-user bg-white-dark-2">
    <div class="content-user">
        <div>
            <span onclick="document.getElementById('modal-user').style.display='none'"
                class="exit text-hover-secondary-dark-5 float-r">&times;</span>
            <ul class="pt-5 mb-2 ">
                <li class="p-1 pt-5 font-xl"><a class="text-secondary text-hover-secondary-light-3"
                        href="{{ route('profile.show') }}">{{ __('Edit Profile') }}</a></li>
                <li class="p-1 font-xl"><a class="text-secondary text-hover-secondary-light-3"
                        href="{{ route('logout') }}">{{ __('Logout') }}</a></li>
            </ul>
        </div>
    </div>
</div>

{{-- Modal Add new Category --}}
<div id="modal-category" class="modal">
    <div class="content-category o-50">
        <div>
            <span onclick="document.getElementById('modal-category').style.display='none'"
                class="exit text-hover-secondary float-r">&times;</span>
            <ul class="pt-1">
                <li class="p-1 pt-1 font-xl">
                    <h1 id="title" class="talign-c text-white font-xl ls-1 text-bg">{{ __('Add new category') }}
                    </h1>
                    
                </li>
                <li class="p-1 font-xl">
                    <form action="{{ url('/add_category') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12-xs col-12-sm col-12-xl text-white">
                            <button class="reset bg-bg cursor-p text-white p-1" type="reset">Clear
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
                                        <label class="font-lg text-white ls-1">Parrent -</label>
                                        <input type="text" name="parrent" placeholder="Enter a parrent catagory name"
                                            required>
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
                                            placeholder="Image sequence" required>

                                    </li>
                                </ul>
                            </div>
                            <div class="col-12-xs col-12-sm mt-1 col-8-xl text-white">
                                <ul>
                                    <li class="p-1 listelement">

                                        <label class="font-lg text-white ls-1" for="end_date">Image main -</label>
                                        <input type="file" class="image ml-1" id="category_image"
                                            name="category_image" value="cars.png">
                                        <input type="button"
                                            class="browse1 ls-1 ml-1 talign-c cursor-p bg-hover-bg-light-2"
                                            value="or Browse" name="browse" id="browse_main">
                                            <div class="bloc">
                                                <span class="ml-1 font-md talign-c text-white p-1 bg-red"
                                                id="select_image_main"></span>
                                                <img width="25px" height="25px" id="img_select_image_main">
                                            </div>
                                            

                                    </li>
                                    <li class="p-1 listelement">

                                        <label class="font-lg text-white ls-1" for="end_date">Image search -</label>
                                        <input type="file" class="image ml-1" id="category_image_search"
                                            name="category_image_search">
                                        <input type="button"
                                            class="browse1 ls-1 ml-1 talign-c cursor-p bg-hover-bg-light-2"
                                            value="or Browse" name="browse" id="browse_saerch">

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
    </div>
</div>

{{-- Confirm Delete Category modal --}}
<div id="confirmmodal-category" class="modal">
    <div class="content-category">
        <div>
            <span onclick="document.getElementById('confirmmodal-category').style.display='none'"
                class="exit text-hover-secondary float-r">&times;</span>
            <ul class="pt-1 mb-2">
                <li class="p-1 pt-1 font-xl">
                    <h1 class="title talign-c font-xl ls-1 text-secondary">
                        {{ __('Are you sure to delete this Category ?') }}
                    </h1>
                </li>
                <li class="p-1 font-xl">
                    <form id="deletecategory_form" action="{{ url('/delete_category') }}" method="POST">
                        @csrf
                        <ul class="pt-1 mb-2">
                            <li class="p-1 font-xl">
                                <input type="hidden" name="hiddenid" id="hiddenid">
                                <input type="submit" class="submit cursor-p bg-hover-bg-light-2" value="Confirm"
                                    name="submit">
                                <input onclick="document.getElementById('confirmmodal-category').style.display='none'"
                                    class="submit cursor-p talign-c bg-hover-bg-light-2" value="Cancel">
                            </li>
                        </ul>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
{{-- Image Selection Modal --}}
<!-- Modals -->
<div class="modal" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="content-category-images">

        <div>
            <span id="closeselection" class="exit text-hover-secondary float-r">&times;</span>
            <ul class="pt-1 mb-2">
                <li class="p-1 font-xl">
                    <h1 class="title talign-c font-xl ls-1 text-white">
                        {{ __('Please select an image') }}
                    </h1>
                </li>
                <li class="p-1 font-xl">
                    <div id="images-container-wrapper" style="height: 500px; overflow-y: scroll;">
                        <div class="modal-body bg-bg-dark-1" id="image-container">
                            
                        </div>
                        <div id="lazy-load-observer"></div>
                      </div>
                      <input type="hidden" id="selected-image" name="hidden">
                    
                </li>
                <li class="p-1 font-xl">
                    <input type="submit" class="submit cursor-p bg-hover-bg-light-2" value="Confirm" name="submit"
                        id="confirmimageselection">
                    <input type="button" id="closeselection1" class="submit cursor-p talign-c bg-hover-bg-light-2"
                        value="Cancel">
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="modal" id="imageModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="content-category-images">
        <div>
            <span id="closeselection" class="exit text-hover-secondary float-r">&times;</span>
            <ul class="pt-1 mb-2">
                <li class="p-1 font-xl">
                    <h1 class="title talign-c font-xl ls-1 text-white">
                        {{ __('Please select an image') }}
                    </h1>
                </li>
                <li class="p-1 font-xl">
                    <div class="modal-body bg-bg-dark-1" id="image-container1">
                        <input type="hidden" id="selected-image1" name="hidden">
                    </div>
                </li>
                <li class="p-1 font-xl">
                    <input type="submit" class="submit cursor-p bg-hover-bg-light-2" value="Confirm" name="submit"
                        id="confirmimageselection1">
                    <input type="button" id="closeselection1" class="submit cursor-p talign-c bg-hover-bg-light-2"
                        value="Cancel">
                </li>
            </ul>
        </div>
    </div>
</div>

 