{{--                   Modals           --}}

{{-- Confirm Delete Category modal --}}
<div class="modal" id="confirmmodal-category">
    <form class="modal-content" id="deletecategory_form" action="{{ url('/delete_category') }}" method="POST">
        @csrf
        <h1 class="modal-content-title">
            {{ __('Are you sure to delete this category ?') }}
        </h1>
        <input type="hidden" name="hiddenid" id="hiddenid">
        <input class="modal-content-btn submit" type="submit" value="Confirm" name="submit">
        <input class="modal-content-btn delete"
            onclick="document.getElementById('confirmmodal-category').style.display='none'" value="Cancel">

        <span class="modal-content-btn delete"
            onclick="document.getElementById('confirmmodal-category').style.display='none'">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </span>
    </form>
</div>
