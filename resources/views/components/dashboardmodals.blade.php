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


{{-- Confirm Delete Category modal --}}
<div id="confirmmodal-category" class="modal">
    <div class="content-category br-sm">
        <div>
            <span onclick="document.getElementById('confirmmodal-category').style.display='none'"
                class="exit text-hover-secondary float-r">&times;</span>
            <ul class="pt-1 mb-2">
                <li class="p-1 pt-1 font-xl">
                    <h1 class="title talign-c font-xl ls-1 text-white">
                        {{ __('Are you sure to delete this category ?') }}
                    </h1>
                </li>
                <li class="p-1 font-xl">
                    <form id="deletecategory_form" action="{{ url('/delete_category') }}" method="POST">
                        @csrf
                        <ul class="pt-1 mb-2">
                            <li class="p-1 font-xl">
                                <input type="hidden" name="hiddenid" id="hiddenid">
                                <input type="submit" class="submit br-xs cursor-p" value="Confirm" name="submit">
                                <input onclick="document.getElementById('confirmmodal-category').style.display='none'"
                                    class="submit cursor-p br-xs talign-c" value="Cancel">
                            </li>
                        </ul>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Image Selection Modal --}}
<!-- Modals Main-->
<div class="modal" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="content-category-images br-sm">
        <div>
            <span id="closeselection" class="exit font-xl cursor-p pr-1 float-r">&times;</span>
            <ul class="pt-1 mb-2">
                <li class="p-1 font-xl">
                    <h1 class="talign-c font-xl ls-1 text-bg">
                        {{ __('Please select a main image') }}
                    </h1>
                </li>
                <li class="p-1 font-xl">
                    <div id="images-container-wrapper">
                        <div class="modal-body" id="image-container">
                        </div>
                    </div>
                    <input type="hidden" id="selected-image" name="hidden">
                </li>
                <li class="p-1 font-xl">
                    <input type="submit" class="modalbutton cursor-p" value="Confirm" name="submit"
                        id="confirmimageselection">
                    <input type="button" id="closeselection1" class="modalbutton cursor-p" value="Cancel">
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Modals Search-->
<div class="modal" id="imageModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="content-category-images br-sm">
        <div>
            <span id="closeselection2" class="exit font-xl cursor-p pr-1 float-r">&times;</span>
            <ul class="pt-1 mb-2">
                <li class="p-1 font-xl">
                    <h1 class="title talign-c font-xl ls-1 text-bg">
                        {{ __('Please select a search image') }}
                    </h1>
                </li>
                <li class="p-1 font-xl">
                    <div id="images-container-wrapper">
                        <div class="modal-body" id="image-container1">
                        </div>
                    </div>
                    <input type="hidden" id="selected-image1" name="hidden">
                </li>
                <li class="p-1 font-xl">
                    <input type="submit" class="modalbutton cursor-p" value="Confirm" name="submit"
                        id="confirmimageselection1">
                    <input type="button" id="closeselection3" class="modalbutton cursor-p" value="Cancel">
                </li>
            </ul>
        </div>
    </div>
</div>
