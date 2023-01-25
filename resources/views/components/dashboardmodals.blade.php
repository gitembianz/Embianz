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
                    <h1 id="title" class="talign-c font-xl ls-1 text-bg">{{ __('Add new category') }}</h1>
                </li>
                <li class="p-1 font-xl">
                    <form action="{{ url('/add_category') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <ul class="pt-1">
                            <li class="p-1 font-xl">
                                <button class="reset bg-bg cursor-p text-secondary float-r p-1" type="reset">Clear
                                    form</button>
                            </li>
                            <li class="p-1 pt-3 font-xl">
                                <input type="text" name="category" placeholder="Category name" required>
                            </li>
                            <li class="p-1 font-xl">
                                <input type="text" name="parrent" placeholder="Parent catagory name" required>
                            </li>
                            <li class="p-1 font-xl">
                                <input type="text" name="long_description"
                                    placeholder="Long description catagory name" required>
                            </li>
                            <li class="p-1 font-xl">
                                <input type="text" name="short_description"
                                    placeholder="Short description catagory name" required>
                            </li>
                            <li class="p-1 font-xl">
                                <input type="number" name="sequence" placeholder="Catagory sequence" required>
                            </li>
                            <li class="p-1 font-xl">
                                <div class="element">
                                    <label class="font-lg text-secondary ls-1" for="start_date">Start Date -</label>
                                    <input class="ml-1" type="date" id="start_date" name="start_date" required>
                                </div>
                            </li>
                            <li class="p-1 font-xl talign-c">
                                <div class="element">
                                    <label class="font-lg text-secondary ls-1" for="end_date">End Date -</label>
                                    <input class="ml-1" type="date" id="end_date" name="end_date" required>
                                </div>
                            </li>
                            <li class="p-1 font-xl talign-c">
                                <div class="element">
                                    <label class="font-lg text-secondary ls-1" for="end_date">Image main -</label>
                                    <input type="file" class="image ml-1"  id="category_image" name="category_image">
                                    <input type="button" class="browse1 ls-1 ml-1 talign-c cursor-p bg-hover-bg-light-2" value="or Browse"
                                    name="browse" id="browse_main">
                                </div>
                            </li>
                            <li class="p-1 font-xl talign-c">
                                <div class="element">
                                    <label class="font-lg text-secondary ls-1" for="end_date">Image search -</label>
                                    <input type="file" class="image ml-1"  id="category_image_search" name="category_image_search">
                                    <input type="button" class="browse ls-1 ml-1 talign-c cursor-p bg-hover-bg-light-2" value="or Browse"
                                    name="browse" id="browse_saerch">
                                </div>
                            </li>
                            <li class="p-1 font-xl talign-c">
                                <div class="element">
                                    <input type="number" class="ml-1" name="image_sequence" placeholder="Image sequence" required>
                                </div>
                            </li>
                            <li class="p-1 font-xl"><input type="submit" class="submit cursor-p" value="Add"
                                    name="submit" >
                            </li>
                        </ul>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- update Category modal --}}
<div id="editmodal-category" class="modal">
    <div class="content-category o-50">
        <div>
            <span onclick="document.getElementById('editmodal-category').style.display='none'"
                class="exit text-hover-secondary float-r">&times;</span>
            <ul class="pt-1 mb-2">
                <li class="p-1 pt-1 font-xl">
                    <h1 id="title" class="modaltitle talign-c font-xl ls-1 text-bg">
                    </h1>
                </li>
                <li class="p-1 font-xl">
                    <form id="editcategory_form" action="{{ url('/category_update') }}" method="POST">
                        @csrf
                        <ul class="pt-1 mb-2">

                            <li class="p-1 pt-3 font-xl">
                                <label class="font-lg text-secondary ls-1" for="end_date">Name -</label>
                                <input type="text" name="categorynameupdate" id="categoryname"
                                    placeholder="Category name" required>
                            </li>
                            <li class="p-1 font-xl">
                                <label class="font-lg text-secondary ls-1" for="end_date">Parrent -</label>
                                <input type="text" name="category_parrentupdate" id="categoryparrent"
                                    placeholder="Parent catagory name" required>
                            </li>
                            <li class="p-1 font-xl">
                                <label class="font-lg text-secondary ls-1" for="end_date">Long Description -</label>
                                <input type="text" id="categorylong_description"
                                    name="category_long_descriptionupdate"
                                    placeholder="Long description catagory name" required>
                            </li>
                            <li class="p-1 font-xl">
                                <label class="font-lg text-secondary ls-1" for="end_date">Short Description -</label>
                                <input type="text" name="category_short_descriptionupdate"
                                    id="categoryshort_description" placeholder="Short description catagory name"
                                    required>
                            </li>
                            <li class="p-1 font-xl">
                                <label class="font-lg text-secondary ls-1" for="end_date">Sequence -</label>
                                <input type="number" name="category_sequenceupdate" id="categorysequence"
                                    placeholder="Catagory sequence" required>
                            </li>
                            <li class="p-1 font-xl">
                                <div class="element">
                                    <label class="font-lg text-secondary ls-1" for="start_date">Start Date -</label>
                                    <input class="ml-1" type="date" id="categorystart_date"
                                        name="category_start_dateupdate" required>
                                </div>
                            </li>
                            <li class="p-1 font-xl talign-c">
                                <div class="element">
                                    <label class="font-lg text-secondary ls-1" for="end_date">End Date -</label>
                                    <input class="ml-1" type="date" id="categoryend_date"
                                        name="category_end_dateupdate" required>

                                </div>
                            </li>
                            <li class="p-1 font-xl">
                                <input type="submit" class="submit bg-hover-bg-light-2" value="Update"
                                    name="submit">
                                <input type="hidden" name="idupdate" id="idupdate">
                            </li>
                        </ul>
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
<!-- Modal -->
<div class="modal" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="content-category">
       
    <div>
        <span onclick="document.getElementById('imageModal').style.display='none'"
        class="exit text-hover-secondary float-r">&times;</span>
        <ul class="pt-1 mb-2">
            <li class="p-1 font-xl">
                <h1 class="title talign-c font-xl ls-1 text-secondary">
                    {{ __('Please select an image') }}
                </h1>
            </li>
            <li class="p-1 font-xl">
                <div class="modal-body" id="image-container">
                    <h1 id="test"></h1>
                  </div>
            </li>
            <li class="p-1 font-xl">
                <input type="submit" class="submit cursor-p bg-hover-bg-light-2" value="Confirm"
                                    name="submit">
                <input type="button" onclick="document.getElementById('imageModal').style.display='none'"
                                    class="submit cursor-p talign-c bg-hover-bg-light-2" value="Cancel">
            </li>
        </ul>
    </div>
    </div>
  </div>
  
  {{--  --}}