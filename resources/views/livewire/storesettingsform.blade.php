<div class="item">
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
