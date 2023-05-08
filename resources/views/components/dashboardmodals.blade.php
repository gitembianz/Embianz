{{--                   Modals           --}}

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
