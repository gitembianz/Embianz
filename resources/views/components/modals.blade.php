{{-- modals --}}
{{-- delete single record --}}
<div class="modal" id="confirmationmodal">
    <div class="modal-content">
        <h1 class="modal-content-title">
            {{ __('Are you sure to delete this record?') }}
        </h1>
        <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button" value="Confirm">
        <input class="modal-content-btn delete" type="button"
            onclick="document.getElementById('confirmationmodal').style.display='none'" value="Cancel">

        <span class="modal-content-btn delete"
            onclick="document.getElementById('confirmationmodal').style.display='none'">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18">
                </line>
                <line x1="6" y1="6" x2="18" y2="18">
                </line>
            </svg>
        </span>
    </div>
</div>
{{-- delete myltiple records --}}
<div class="modal" id="confirmationmodalmultiple">
    <div class="modal-content">
        <h1 class="modal-content-title">
            {{ __('Are you sure to delete those records?') }}
        </h1>
        <input wire:click.prevent="deleteRecords()" class="modal-content-btn submit" type="button" value="Confirm">
        <input class="modal-content-btn delete" type="button"
            onclick="document.getElementById('confirmationmodalmultiple').style.display='none'" value="Cancel">

        <span class="modal-content-btn delete"
            onclick="document.getElementById('confirmationmodalmultiple').style.display='none'">

            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                <line x1="18" y1="6" x2="6" y2="18">
                </line>
                <line x1="6" y1="6" x2="18" y2="18">
                </line>
            </svg>
        </span>
    </div>
</div>
{{-- end modals --}}
