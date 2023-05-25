{{--                   Modals           --}}

{{-- Confirm Delete Category modal --}}
<div id="confirmmodal-category" class="modal">
    <div class="modal-content br-xs">
            <span onclick="document.getElementById('confirmmodal-category').style.display='none'"
                class="exit">&times;</span>
            <ul class="p-1">
                <li>
                    <h1 class="title talign-c font-xl ls-1 text-white">
                        {{ __('Are you sure to delete this category ?') }}
                    </h1>
                </li>
                <li>
                    <form id="deletecategory_form" action="{{ url('/delete_category') }}" method="POST">
                        @csrf
                        <ul>
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
