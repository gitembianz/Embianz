<div>
	<x-alert />
	<x-loading />
	<form wire:submit.prevent="store">
		<div class="item__header">
			<h1 class="item__header-title" id="title">{{ __("Add Store Settings") }}</h1>
			<div class="item__header-buttons">
				<a class="item__header-btn" href="{{ route("storesettings") }}" data-tooltip-center="Back to All Store Settings">
					<svg>
						<polyline points="11 17 6 12 11 7"></polyline>
						<polyline points="18 17 13 12 18 7"></polyline>
					</svg>
				</a>
				<button class="item__header-btn" id="resetform" type="reset" data-tooltip-right="Clear Form">
					<svg>
						<polyline points="1 4 1 10 7 10"></polyline>
						<polyline points="23 20 23 14 17 14"></polyline>
						<path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
					</svg>
				</button>
			</div>
		</div>
		{{-- Item Form --}}
		<div class="item__form">
			<div class="item__form-input">
				<input type="text" wire:model="parameter" required>
				<label>Parameter</label>
				{{-- <p class="real-time-validation">
                    @error("parameter")
                        {{ $message }}
                    @enderror
                </p> --}}
			</div>
			<div class="item__form-input">
				<input type="text" wire:model="value" required>
				<label>Value</label>
				{{-- <p class="real-time-validation">
                    @error("value")
                        {{ $message }}
                    @enderror
                </p> --}}
			</div>
			<div class="item__form-input item__form-textarea">
				<textarea wire:model="description" required></textarea>
				<label>Description</label>
				{{-- <p class="real-time-validation">
                    @error("description")
                        {{ $message }}
                    @enderror
                </p> --}}
			</div>
			<input class="item__form-btn item__form-long" type="submit" value="Add New">
		</div>
	</form>
</div>
