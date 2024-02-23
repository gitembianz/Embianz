<div>
	<x-alert />
	<x-loading />
	<div class="item__header">
		<h1 class="item__header-title" id="title">Specification: {{ $spec->name }}</h1>
		<div class="item__header-buttons">
			<a class="item__header-btn" href="{{ route("specs") }}" data-tooltip-left="Back to all specifications">
				<svg>
					<polyline points="11 17 6 12 11 7"></polyline>
					<polyline points="18 17 13 12 18 7"></polyline>
				</svg>
			</a>
			<a class="item__header-btn" href="{{ route("newspec") }}" data-tooltip-center="Create a new specification">
				<svg>
					<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
					<polyline points="14 2 14 8 20 8"></polyline>
					<line x1="12" y1="18" x2="12" y2="12"></line>
					<line x1="9" y1="15" x2="15" y2="15"></line>
				</svg>
			</a>
			@if ($edititem === null)
				<button class="item__header-btn" type="button" value="Edit" wire:click.prevent="edititem()" data-tooltip-center="Edit this specification">
					<svg>
						<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
						<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
					</svg>
				</button>
			@else
				<button class="item__header-btn confirm" type="button" wire:click.prevent="saveitem" value="Save" data-tooltip-center="Save this changes">
					<svg>
						<polyline points="20 6 9 17 4 12"></polyline>
					</svg>
				</button>
				<button class="item__header-btn" type="button" wire:click.prevent="cancelitem" value="Cancel" data-tooltip-center="Cancel this changes">
					<svg>
						<line x1="18" y1="6" x2="6" y2="18">
						</line>
						<line x1="6" y1="6" x2="18" y2="18">
						</line>
					</svg>
				</button>
			@endif
			<button wire:click="confirmItemRemoval({{ $spec->id }})" class="item__header-btn delete" type="button" data-tooltip-right="Delete this specification">
				<svg>
					<polyline points="3 6 5 6 21 6"></polyline>
					<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
				</svg>
			</button>
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
						<div class="item__form-input-close item__form-long">
							<div>{{ $spec->name }}</div>
							<label>Name</label>
						</div>
					@else
						<div class="item__form-input item__form-long">
							<input type="text" wire:model.defer="record.name">
							<label>Name</label>
						</div>
					@endif
					@if ($edititem === null)
						<div class="item__form-input-close">
							<div>{{ $spec->um }}</div>
							<label>Unit</label>
						</div>
					@else
						<div class="item__form-input">
							<input type="text" wire:model.defer="record.um">
							<label>Unit</label>
						</div>
					@endif
					<div class="item__form-input-close">
						<div>{{ $spec->created_at }}</div>
						<label>Create date / time</label>
					</div>
					<div class="item__form-input-close">
						<div>{{ $spec->createdby }}</div>
						<label>Create by</label>
					</div>
					<div class="item__form-input-close">
						<div>{{ $spec->updated_at }}</div>
						<label>Updated date / time</label>
					</div>
					<div class="item__form-input-close">
						<div>{{ $spec->lastmodifiedby }}</div>
						<label>Last modified by</label>
					</div>
					@if ($edititem != null)
						<button class="item__form-btn item__form-long" wire:click.prevent="saveitem()" type="button" value="Save">Save</button>
					@endif
				</div>
			</div>
		</div>
		<div class="tabs__content">
			@livewire("related-productson-spec", ["specId" => $spec->id])
		</div>
	</div>
	<div class="modal" id="confirmationmodal">
		<div class="modal-content">
			<h1 class="modal-content-title">
				{{ __("Are you sure to delete this record?") }}
			</h1>
			<input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button" value="Confirm">
			<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodal').style.display='none'" value="Cancel">

			<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodal').style.display='none'">
				<svg>
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</span>
		</div>
	</div>
</div>
