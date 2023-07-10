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
    <div class="item__header">
        <h1 class="item__header-title" id="title">Spec - {{ $spec->name }}</h1>
        <div class="item__header-buttons">
            <a class="item__header-btn" href="{{ route('specs') }}">All Specs</a>
            <a class="item__header-btn" href="{{ route('newspec') }}">New</a>
            @if ($edititem === null)
                <input class="item__header-btn" type="button" value="Edit" wire:click.prevent="edititem()">
            @else
                <input class="item__header-btn" type="button" wire:click.prevent="saveitem()" value="Save">
                <input class="item__header-btn" type="button" wire:click.prevent="cancelitem()" value="Cancel">
            @endif
            <input class="item__header-btn delete" type="button" value="Delete" name="delete"
                wire:click.prevent="confirmItemRemoval({{ $spec->id }})">
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
                            <div>{{ $spec->name }}</div>
                            <span>Spec Name</span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <input type="text" wire:model.defer="record.name">
                            <span> Name</span>
                        </div>
                    @endif
                    @if ($edititem === null)
                        <div class="item__form-input-close">
                            <div>{{ $spec->um }}</div>
                            <span>Spec Unit</span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <input type="text" wire:model.defer="record.um">
                            <span> Unit</span>
                        </div>
                    @endif

                    @if ($edititem === null)
                        <div class="item__form-input-close">
                            <div>{{ $spec->spec_group }}</div>
                            <span for="start_date">Spec Group</span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <select wire:model.defer="record.spec_group">
                                <?php
                                $groups = ['details', 'feature', 'accessibility'];
                                ?>
                                <option>Select a group</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="item__form-close">
                        <div>{{ $spec->created_at }}</div>
                        <span>Create date / time</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $spec->createdby }}</div>
                        <span>Create by</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $spec->updated_at }}</div>
                        <span>Updated date / time</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $spec->lastmodifiedby }}</div>
                        <span>Last modified by</span>
                    </div>
                    @if ($edititem != null)
                        <input class="item__form-btn item__form-long" wire:click.prevent="saveitem()" type="button"
                            value="Save">
                    @endif
                </div>
            </div>
            <div class="tabs__content display-f g-1">
                <livewire:related-productson-spec specId="{{ $spec->id }}" />
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
