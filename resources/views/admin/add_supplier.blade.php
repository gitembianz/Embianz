<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('supplier')" />
<form class="content" method="POST" action="{{ route('new_supplier') }}">

    {{-- Navigation --}}
    <nav class="nav--controls">
        <h1 class="table--name">New Order Supplier</h1>
        {{-- Refresh Button --}}
        <a class="button button--primary button--centered" tooltip="Back to Order Supplier Lists" tooltip-top
            href="{{ route('suppliers') }}">
            <svg>
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        <button class="button button--primary button--centered" tooltip="Save Price" tooltip-left type="submit">
            <svg>
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M9 15l2 2l4 -4" />
            </svg>
        </button>
        <button class="button button--primary button--centered" tooltip="Reset Price" tooltip-left type="reset">
            <svg>
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
            </svg>
        </button>
    </nav>

    {{-- Tabs Body (Details) --}}
    <section style="height: calc(100% - 107.5px);" class="tabs__content details__view active">
        @csrf

        {{-- Price List Name --}}
        <div class="input__tabs">
            <input type="text" name="name" required value="{{ old('name') }}">
            <label>Name</label>
        </div>
        <div class="input__tabs">
            <input type="text" name="supplier_name" required value="{{ old('supplier_name') }}">
            <label>Supplier Name</label>
        </div>
        <div class="input__tabs">
            <input type="date" name="date" required value="{{ old('date') }}">
            <label>Date</label>
        </div>
        {{-- Price List Currency --}}
        <div class="input__tabs">
            <select name="currency" value="{{ old('currency') }}">
                @foreach ($currencies as $index => $currency)
                    <option @if ($index === 0) selected @endif value="{{ $currency->name }}">
                        {{ $currency->name }}</option>
                @endforeach
            </select>
            <label>Currency</label>
        </div>
        {{-- Price List Currency --}}
        <div class="input__tabs">
            <select name="exchange" value="{{ old('exchange') }}">
                @foreach ($exchanges as $index => $exchange)
                    <option @if ($index === 0) selected @endif value="{{ $exchange->id }}">
                        {{ $exchange->base_currency->name }} / {{ $exchange->quote_currency->name }} date -
                        {{ $exchange->date }}
                        value - {{ $exchange->value }}</option>
                @endforeach
            </select>
            <label>Exchange</label>
        </div>
        <div class="input__tabs">
            <select name="quote_currency" value="{{ old('quote_currency') }}">
                @foreach ($currencies as $index => $currency)
                    <option @if ($index === 0) selected @endif value="{{ $currency->name }}">
                        {{ $currency->name }}</option>
                @endforeach
            </select>
            <label>Quote Currency</label>
        </div>
        {{-- Save Button --}}
        <input class="button button--fill button--secondary details__long" type="submit" value="Add New"
            name="submit">
        @error('date')
            <span class="error @error('date') active @enderror">{{ $message }}</span>
        @enderror
        @error('name')
            <span class="error @error('name') active @enderror">{{ $message }}</span>
        @enderror
    </section>
</form>
<x-dashboardfooter />
