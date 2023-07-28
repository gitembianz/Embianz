<div class="item">
    <x-alert />
    <div wire:loading>
        <div class="modal" style="display:flex;">
            <div class="loader"></div>
        </div>
    </div>
    <form wire:submit.prevent="store" class="item wid-10">
        <div class="item__header">
            <h1 id="title" class="item__header-title">{{ __('Store Settings') }}</h1>
            <div class="item__header-buttons">
                <a href="{{ route('storesettings') }}" class="item__header-btn">{{ __('All Store Settings') }}</a>
                <button class="item__header-btn" type="reset">Clear form</button>
            </div>
        </div>
        <div class="item__form">
            <div class="item__form-input">
                <input type="text" wire:model="parameter" required>
                <span>Parameter</span>
                <p class="real-time-validation">
                    @error('parameter')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="item__form-input">
                <input type="text" wire:model="value">
                <span>Value</span>
                <p class="real-time-validation">
                    @error('value')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="item__form-input item__form-long">
                <textarea wire:model="description"></textarea>
                <span>Description</span>
                <p class="real-time-validation">
                    @error('description')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <input class="item__form-btn item__form-long" type="submit" value="Add New">
        </div>
    </form>
</div>
