<div>
    @if (session()->has('message'))
        <div class="alert__session liveAlert" id="alertevent">
            <span class="alert__session-text">{!! session('message') !!}</span>
            <button class="alert__session-btn" type="button" data-bs-dismiss="alert" aria-hidden="true">
                <svg>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <script>
            const alertEvent = document.getElementById("alertevent");
            header.style.marginBottom = '4rem';
            alertEvent.style.opacity = '1';

            setTimeout(function() {
                alertEvent.style.opacity = '0';
                setTimeout(function() {
                    alertEvent.remove();
                    header.style.marginBottom = '0';
                }, 500);
            }, 2000);
        </script>
    @endif
    <div wire:loading.delay>
        <div class="modal" style="display:flex;">
            <div class="loader"></div>
        </div>
    </div>
    <div class="item__header">
        <h1 class="item__header-title" id="title">Pricelist - {{ $pricelist->name }}</h1>
        <div class="item__header-buttons">
            <a class="item__header-btn" href="{{ route('pricelists') }}">All Pricelist</a>
            <a class="item__header-btn" href="{{ route('newpricelist') }}">New</a>
            @if ($edititem === null)
                <input class="item__header-btn" type="button" value="Edit" wire:click.prevent="edititem()">
            @else
                <input class="item__header-btn" type="button" wire:click.prevent="saveitem()" value="Save">
                <input class="item__header-btn" type="button" wire:click.prevent="cancelitem()" value="Cancel">
            @endif
            <input class="item__header-btn delete" type="button" value="Delete" name="delete"
                wire:click.prevent="confirmItemRemoval({{ $pricelist->id }})">
        </div>
    </div>

    <div class="tab">
        <div class="tabs">
            <h3 class="tabs__page active">Details</h3>
            <h3 class="tabs__page">Related</h3>
        </div>
        <div class="tab__list">
            <div class="tabs__content active" id="Details">
                <div class="item__form">
                    @if ($edititem === null)
                        <div class="item__form-input-close">
                            <div>{{ $pricelist->name }}</div>
                            <span>Name</span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <input type="text" wire:model.defer="record.name">
                            <span> Name</span>
                        </div>
                    @endif
                    <div class="display-f align-center jus-s g-2 wid-10">
                        @if ($edititem === null)
                            <div class="item__form-input-close">
                                <div>{{ $pricelist->currency->name }}</div>
                                <span>Currency</span>
                            </div>
                            <div class="item__form-input-close">
                                <div>
                                    @if ($pricelist->active)
                                        {{ _('Active') }}
                                    @else
                                        {{ _('Inactive') }}
                                    @endif
                                </div>
                                <span>Is Active</span>
                            </div>
                        @else
                            <div class="item__form-input">
                                <select wire:model.defer="record.currency">
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class=" display-f align-center jus-s">
                                <input type="checkbox" wire:model.defer="record.active">
                                <span class="ml-1"> is active</span>
                            </div>
                        @endif
                    </div>
                    <div class="item__form-close">
                        <div>{{ $pricelist->created_at }}</div>
                        <span>Create date / time</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $pricelist->createdby }}</div>
                        <span>Create by</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $pricelist->updated_at }}</div>
                        <span>Updated date / time</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $pricelist->lastmodifiedby }}</div>
                        <span>Last modified by</span>
                    </div>
                    @if ($edititem != null)
                        <input class="item__form-btn item__form-long" wire:click.prevent="saveitem()" type="button"
                            value="Save">
                    @endif
                </div>
            </div>
            <div class="tabs__content display-f g-1">
                <livewire:related-productson-pricelist priceId="{{ $pricelist->id }}" />
            </div>

        </div>
        <div class="modal" id="confirmationmodal">
            <div class="modal-content">
                <h1 class="modal-content-title">
                    {{ __('Are you sure to delete this record?') }}
                </h1>
                <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button"
                    value="Confirm">
                <input class="modal-content-btn delete" type="button"
                    onclick="document.getElementById('confirmationmodal').style.display='none'" value="Cancel">

                <span class="modal-content-btn delete"
                    onclick="document.getElementById('confirmationmodal').style.display='none'">
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </span>
            </div>
        </div>
    </div>
</div>
