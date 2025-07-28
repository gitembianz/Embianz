<div>
    <x-store-alert />
    <div style="display: none">
        <script>
            window.labels = {

                // name validation
                name_require: @if (app()->bound('label_form_name_require'))
                    "{{ app('label_form_name_require') }}"
                @else
                    ""
                @endif ,
                name_min: @if (app()->bound('label_form_name_min'))
                    "{{ app('label_form_name_min') }}"
                @else
                    ""
                @endif ,
                name_max: @if (app()->bound('label_form_name_max'))
                    "{{ app('label_form_name_max') }}"
                @else
                    ""
                @endif ,
                name_space: @if (app()->bound('label_form_name_space'))
                    "{{ app('label_form_name_space') }}"
                @else
                    ""
                @endif ,
                name_special: @if (app()->bound('label_form_name_special'))
                    "{{ app('label_form_name_special') }}"
                @else
                    ""
                @endif ,
                // lastname validation
                lastname_require: @if (app()->bound('label_form_lastname_require'))
                    "{{ app('label_form_lastname_require') }}"
                @else
                    ""
                @endif ,
                lastname_min: @if (app()->bound('label_form_lastname_min'))
                    "{{ app('label_form_lastname_min') }}"
                @else
                    ""
                @endif ,
                lastname_max: @if (app()->bound('label_form_lastname_max'))
                    "{{ app('label_form_lastname_max') }}"
                @else
                    ""
                @endif ,
                lastname_space: @if (app()->bound('label_form_lastname_space'))
                    "{{ app('label_form_lastname_space') }}"
                @else
                    ""
                @endif ,
                lastname_special: @if (app()->bound('label_form_lastname_special'))
                    "{{ app('label_form_lastname_special') }}"
                @else
                    ""
                @endif ,
                // email Validation
                email_require: @if (app()->bound('label_form_email_require'))
                    "{{ app('label_form_email_require') }}"
                @else
                    ""
                @endif ,
                email_min: @if (app()->bound('label_form_email_min'))
                    "{{ app('label_form_email_min') }}"
                @else
                    ""
                @endif ,
                email_max: @if (app()->bound('label_form_email_max'))
                    "{{ app('label_form_email_max') }}"
                @else
                    ""
                @endif ,
                email_space: @if (app()->bound('label_form_email_space'))
                    "{{ app('label_form_email_space') }}"
                @else
                    ""
                @endif ,
                email_valid: @if (app()->bound('label_form_email_valid'))
                    "{{ app('label_form_email_valid') }}"
                @else
                    ""
                @endif ,
                // phone Validation
                phone_require: @if (app()->bound('label_form_phone_require'))
                    "{{ app('label_form_phone_require') }}"
                @else
                    ""
                @endif ,
                phone_special: @if (app()->bound('label_form_phone_special'))
                    "{{ app('label_form_phone_special') }}"
                @else
                    ""
                @endif ,
                phone_dimension: @if (app()->bound('label_form_phone_dimension'))
                    "{{ app('label_form_phone_dimension') }}"
                @else
                    ""
                @endif ,
                phone_space: @if (app()->bound('label_form_phone_space'))
                    "{{ app('label_form_phone_space') }}"
                @else
                    ""
                @endif ,
                // address Validation
                address_require: @if (app()->bound('label_form_address_require'))
                    "{{ app('label_form_address_require') }}"
                @else
                    ""
                @endif ,
                address_min: @if (app()->bound('label_form_address_min'))
                    "{{ app('label_form_address_min') }}"
                @else
                    ""
                @endif ,
                address_max: @if (app()->bound('label_form_address_max'))
                    "{{ app('label_form_address_max') }}"
                @else
                    ""
                @endif ,
                address_space: @if (app()->bound('label_form_address_space'))
                    "{{ app('label_form_address_space') }}"
                @else
                    ""
                @endif ,
                // city Validation
                city_require: @if (app()->bound('label_form_city_require'))
                    "{{ app('label_form_city_require') }}"
                @else
                    ""
                @endif ,
                city_min: @if (app()->bound('label_form_city_min'))
                    "{{ app('label_form_city_min') }}"
                @else
                    ""
                @endif ,
                city_max: @if (app()->bound('label_form_city_max'))
                    "{{ app('label_form_city_max') }}"
                @else
                    ""
                @endif ,
                city_space: @if (app()->bound('label_form_city_space'))
                    "{{ app('label_form_city_space') }}"
                @else
                    ""
                @endif ,
                // county Validation
                county_require: @if (app()->bound('label_form_county_require'))
                    "{{ app('label_form_county_require') }}"
                @else
                    ""
                @endif ,
                county_min: @if (app()->bound('label_form_county_min'))
                    "{{ app('label_form_county_min') }}"
                @else
                    ""
                @endif ,
                county_max: @if (app()->bound('label_form_county_max'))
                    "{{ app('label_form_county_max') }}"
                @else
                    ""
                @endif ,
                county_space: @if (app()->bound('label_form_county_space'))
                    "{{ app('label_form_county_space') }}"
                @else
                    ""
                @endif ,
                // company Validation
                company_require: @if (app()->bound('label_form_company_require'))
                    "{{ app('label_form_company_require') }}"
                @else
                    ""
                @endif ,
                company_space: @if (app()->bound('label_form_company_space'))
                    "{{ app('label_form_company_space') }}"
                @else
                    ""
                @endif ,
                company_special: @if (app()->bound('label_form_company_special'))
                    "{{ app('label_form_company_special') }}"
                @else
                    ""
                @endif ,
                // registercode Validation
                code_require: @if (app()->bound('label_form_code_require'))
                    "{{ app('label_form_code_require') }}"
                @else
                    ""
                @endif ,
                code_space: @if (app()->bound('label_form_code_space'))
                    "{{ app('label_form_code_space') }}"
                @else
                    ""
                @endif ,
                code_special: @if (app()->bound('label_form_code_special'))
                    "{{ app('label_form_code_special') }}"
                @else
                    ""
                @endif ,
                // registernumber Validation
                number_require: @if (app()->bound('label_form_number_require'))
                    "{{ app('label_form_number_require') }}"
                @else
                    ""
                @endif ,
                number_space: @if (app()->bound('label_form_number_space'))
                    "{{ app('label_form_number_space') }}"
                @else
                    ""
                @endif ,
                number_special: @if (app()->bound('label_form_number_special'))
                    "{{ app('label_form_number_special') }}"
                @else
                    ""
                @endif ,
            };
        </script>
    </div>
    @php
        if (app()->has('global_numberformat_element')) {
            if (app('global_numberformat_element') === '.') {
                $mill = '.';
                $decimal = ',';
            } else {
                $mill = ',';
                $decimal = '.';
            }
        } else {
            $mill = '.';
            $decimal = ',';
        }
    @endphp
    @push('scripts')
        <script></script>
    @endpush
    @if (!$this->hasCartWithItems)
        <!-------------------- Error Message ------------------->
        <section>
            <div class="checkout container">
                <div class="section__header container">
                    <h1 class="section__title">
                        @if (app()->has('label_order_error_title'))
                            {!! app('label_order_error_title') !!}
                        @endif
                    </h1>
                    <a class="section__text" href="{{ url('/') }}">
                        @if (app()->has('label_order_error_description'))
                            {!! app('label_order_error_description') !!}
                        @endif
                    </a>
                </div>
            </div>
        </section>
    @else
        <section>
            <div class="checkout container">
                <!-------------------- Steps-------------------->
                <div class="step__container">
                    <div class="step active"
                        data-step="@if (app()->has('label_order_step_1')) {!! app('label_order_step_1') !!} @endif">1</div>
                    <span class="step__line @if ($step == 1) half @else full @endif"></span>
                    <div class="step @if ($step > 1 || $step == 3) active @endif"
                        data-step="@if (app()->has('label_order_step_2')) {!! app('label_order_step_2') !!} @endif">2
                    </div>
                    <span
                        class="step__line @if ($step == 2) half @elseif($step == 3) full @endif"></span>
                    <div class="step @if ($step == 3) active @endif"
                        data-step="@if (app()->has('label_order_step_3')) {!! app('label_order_step_3') !!} @endif">3
                    </div>
                </div>
                <!-------------------- Step First ---------------------->
                @if ($step == 1)
                    <div class="section__header">
                        <h2 class="section__title">
                            @if (app()->has('label_order_1_title'))
                                {!! app('label_order_1_title') !!}
                            @endif
                        </h2>
                    </div>
                    <div class="checkout__header">
                        <div class="checkout__navigation">
                            <button class="checkout__button @if ($individual) active @endif"
                                wire:click="showindividual()">
                                @if (app()->has('label_order_individual_tag'))
                                    {!! app('label_order_individual_tag') !!}
                                @endif
                            </button>
                            <button class="checkout__button @if ($juridic) active @endif"
                                wire:click="showjuridic()">
                                @if (app()->has('label_order_juridic_tag'))
                                    {!! app('label_order_juridic_tag') !!}
                                @endif
                            </button>
                        </div>
                    </div>
                    <!-------------- Individual -------------->
                    <div class="checkout__container @if ($individual) active @endif">
                        <div wire:ignore class="checkout__form active">
                            <div class="checkout__top">
                                <span>1</span>
                                <h3>
                                    @if (app()->has('label_order_billing_info'))
                                        {!! app('label_order_billing_info') !!}
                                    @endif
                                </h3>
                            </div>

                            <!-----------------------   first individual billing  ----------------------------->
                            <div class="checkout__item checkout__item--required" id="individualBillingFirstNameParent">
                                <input type="text" wire:model.defer="individual_billing_first"
                                    placeholder="@if (app()->has('label_order_firstname')) {!! app('label_order_firstname') !!} @endif"
                                    autocomplete="given-name" required id="individualBillingFirstName">
                                <span></span>
                                <label for="individualBillingFirstName">
                                    @if (app()->has('label_order_firstname'))
                                        {!! app('label_order_firstname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   last individual billing  ----------------------------->
                            <div class="checkout__item checkout__item--required" id="individualBillingLastNameParent">
                                <input type="text" wire:model.defer="individual_billing_last"
                                    placeholder="@if (app()->has('label_order_lastname')) {!! app('label_order_lastname') !!} @endif"
                                    autocomplete="family-name" required id="individualBillingLastName">
                                <span></span>
                                <label for="individualBillingLastName">
                                    @if (app()->has('label_order_lastname'))
                                        {!! app('label_order_lastname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   phone individual billing  ----------------------------->
                            <div class="checkout__item checkout__item--required" id="individualBillingPhoneParent">
                                <input type="tel" wire:model.defer="individual_billing_phone"
                                    placeholder="@if (app()->has('label_order_phone')) {!! app('label_order_phone') !!} @endif"
                                    autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required
                                    id="individualBillingPhone">
                                <span></span>
                                <label for="individualBillingPhone">
                                    @if (app()->has('label_order_phone'))
                                        {!! app('label_order_phone') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   email individual billing   ----------------------------->
                            <div class="checkout__item checkout__item--required" id="individualBillingEmailParent">
                                <input type="email" wire:model.defer="individual_billing_email"
                                    placeholder="@if (app()->has('label_order_email')) {!! app('label_order_email') !!} @endif"
                                    autocomplete="email" required id="individualBillingEmail">
                                <span></span>
                                <label for="individualBillingEmail">
                                    @if (app()->has('label_order_email'))
                                        {!! app('label_order_email') !!}
                                    @endif
                                </label>
                            </div>
                        </div>

                        <div class="checkout__form active">
                            <div class="checkout__top">
                                <span>2</span>
                                <h3>
                                    @if (app()->has('label_order_billing_address'))
                                        {!! app('label_order_billing_address') !!}
                                    @endif
                                </h3>
                            </div>

                            <!-----------------------   country individual billing   ----------------------------->
                            @if (app()->has('global_order_display_country') && app('global_order_display_country') === 'true')
                                <select wire:model="individual_billing_country" class="select">
                                    @foreach ($countries as $c)
                                        <option value="{{ $c['name'] }}">{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            @endif

                            <!-----------------------   county individual billing  ----------------------------->
                            <div class="checkout__item checkout__item--required searchable active"
                                id="individualBillingCountyParent" x-data="{
                                    countries: @js($countries),
                                    country: @entangle('individual_billing_country'),
                                    county: @entangle('individual_billing_county'),
                                    city: @entangle('individual_billing_city'),
                                    open: false,

                                    counties() {
                                        const selected = this.countries.find(c =>
                                            c.name.toLowerCase() === (this.country || '').toLowerCase()
                                        );
                                        return selected?.counties || [];
                                    },

                                    filtered() {
                                        const searchTerm = (this.county || '').toLowerCase();
                                        return this.counties().filter(c =>
                                            c.name.toLowerCase().includes(searchTerm)
                                        );
                                    },

                                    select(name) {
                                        this.county = name;
                                        this.open = false;
                                    }
                                }" @click.away="open = false"
                                wire:ignore.self>
                                <input type="text" x-model.debounce.300ms="county" @focus="open = true"
                                    placeholder="County" class="input" autocomplete="off"
                                    aria-label="County selection">

                                <span></span>
                                <label for="individualBillingCounty">
                                    @if (app()->has('label_order_county'))
                                        {!! app('label_order_county') !!}
                                    @endif
                                </label>

                                <div x-show="open" class="content__searchable" style="display: none;">
                                    <div class="list__searchable">
                                        <template x-for="c in filtered()" :key="c.name">
                                            <button type="button" class="item__searchable" @click="select(c.name)"
                                                x-text="c.name"></button>
                                        </template>
                                        <template x-if="filtered().length === 0">
                                            <button class="item__searchable">No record found</button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-----------------------   city individual billing   ----------------------------->
                            <div class="checkout__item checkout__item--required searchable active"
                                id="individualBillingCityParent" x-data="{
                                    countries: @js($countries),
                                    country: @entangle('individual_billing_country'),
                                    county: @entangle('individual_billing_county'),
                                    city: @entangle('individual_billing_city'),
                                    open: false,

                                    cities() {
                                        const selectedCountry = this.countries.find(
                                            c => c.name.toLowerCase() === (this.country || '').toLowerCase()
                                        );
                                        const selectedCounty = selectedCountry?.counties?.find(
                                            cc => cc.name.toLowerCase() === (this.county || '').toLowerCase()
                                        );
                                        return selectedCounty?.cities || [];
                                    },

                                    filtered() {
                                        const searchTerm = (this.city || '').toLowerCase();
                                        return this.cities().filter(c =>
                                            c.name.toLowerCase().includes(searchTerm)
                                        );
                                    },

                                    select(name) {
                                        this.city = name;
                                        this.open = false;
                                    }
                                }" @click.away="open = false"
                                wire:ignore.self>
                                <input type="text" x-model.debounce.300ms="city" @focus="open = true"
                                    placeholder="City" class="input" autocomplete="off" aria-label="City selection">

                                <span></span>
                                <label for="individualBillingCity">
                                    @if (app()->has('label_order_city'))
                                        {!! app('label_order_city') !!}
                                    @endif
                                </label>

                                <div x-show="open" class="content__searchable"
                                    style="display: none;">
                                    <div class="list__searchable">
                                        <template x-for="c in filtered()" :key="c.name">
                                            <button type="button" class="item__searchable" @click="select(c.name)"
                                                x-text="c.name"></button>
                                        </template>
                                        <template x-if="filtered().length === 0">
                                            <button class="item__searchable">No record found</button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-----------------------   address1 individual billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="individualBillingAddressParent">
                                <input type="text" wire:model.defer="individual_billing_address1"
                                    placeholder="@if (app()->has('label_order_address1')) {!! app('label_order_address1') !!} @endif"
                                    autocomplete="street-address" required id="individualBillingAddress">
                                <span></span>
                                <label for="individualBillingAddress">
                                    @if (app()->has('label_order_address1'))
                                        {!! app('label_order_address1') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   address2 individual billing  ----------------------------->
                            @if (app()->has('global_order_display_address2') && app('global_order_display_address2') === 'true')
                                <div wire:ignore class="checkout__item" id="individualBillingAddress2Parent">
                                    <input type="text" wire:model.defer="individual_billing_address2"
                                        placeholder="@if (app()->has('label_order_address2')) {!! app('label_order_address2') !!} @endif"
                                        autocomplete="address-level2" id="individualBillingAddress2">
                                    <span></span>
                                    <label for="individualBillingAddress2">
                                        @if (app()->has('label_order_address2'))
                                            {!! app('label_order_address2') !!}
                                        @endif
                                    </label>
                                </div>
                            @endif

                            <!-----------------------   zipcode individual billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item" id="individualBillingPostalParent">
                                <input type="text" wire:model.defer="individual_billing_zipcode"
                                    placeholder="@if (app()->has('label_order_zipcode')) {!! app('label_order_zipcode') !!} @endif"
                                    autocomplete="postal-code" id="individualBillingPostal">
                                <span></span>
                                <label for="individualBillingPostal">
                                    @if (app()->has('label_order_zipcode'))
                                        {!! app('label_order_zipcode') !!}
                                    @endif
                                </label>
                            </div>
                        </div>

                        <label class="checkout__checkbox">
                            <input type="checkbox" wire:model="individual_identic" id="individual_identic"
                                name="individual_identic">
                            <span>
                                @if (app()->has('label_order_identic'))
                                    {!! app('label_order_identic') !!}
                                @endif
                            </span>
                        </label>
                        <div class="checkout__form @if (!$individual_identic && $individual) active @endif">
                            <div class="checkout__top">
                                <span>3</span>
                                <h3>
                                    @if (app()->has('label_order_shipping_info'))
                                        {!! app('label_order_shipping_info') !!}
                                    @endif
                                </h3>
                            </div>

                            <!-----------------------   first individual shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="individualShippingFirstNameParent">
                                <input type="text" wire:model.defer="individual_shipping_first"
                                    placeholder="@if (app()->has('label_order_firstname')) {!! app('label_order_firstname') !!} @endif"
                                    autocomplete="given-name" required id="individualShippingFirstName">
                                <span></span>
                                <label for="individualShippingFirstName">
                                    @if (app()->has('label_order_firstname'))
                                        {!! app('label_order_firstname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   last individual shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="individualShippingLastNameParent">
                                <input type="text" wire:model.defer="individual_shipping_last"
                                    placeholder="@if (app()->has('label_order_lastname')) {!! app('label_order_lastname') !!} @endif"
                                    autocomplete="family-name" required id="individualShippingLastName">
                                <span></span>
                                <label for="individualShippingLastName">
                                    @if (app()->has('label_order_lastname'))
                                        {!! app('label_order_lastname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   phone individual shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="individualShippingPhoneParent">
                                <input type="tel" wire:model.defer="individual_shipping_phone"
                                    placeholder="@if (app()->has('label_order_phone')) {!! app('label_order_phone') !!} @endif"
                                    autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required
                                    id="individualShippingPhone">
                                <span></span>
                                <label for="individualShippingPhone">
                                    @if (app()->has('label_order_phone'))
                                        {!! app('label_order_phone') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   email individual shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="individualShippingEmailParent">
                                <input type="email" wire:model="individual_shipping_email"
                                    placeholder="@if (app()->has('label_order_email')) {!! app('label_order_email') !!} @endif"
                                    autocomplete="email" required id="individualShippingEmail">
                                <span></span>
                                <label for="individualShippingEmail">
                                    @if (app()->has('label_order_email'))
                                        {!! app('label_order_email') !!}
                                    @endif
                                </label>
                            </div>
                        </div>

                        <div class="checkout__form @if (!$individual_identic && $individual) active @endif">
                            <div class="checkout__top">
                                <span>4</span>
                                <h3>
                                    @if (app()->has('label_order_shipping_address'))
                                        {!! app('label_order_shipping_address') !!}
                                    @endif
                                </h3>
                            </div>
                            <!-----------------------   country individual shipping  ----------------------------->
                            @if (app()->has('global_order_display_country') && app('global_order_display_country') === 'true')
                                <select wire:model="individual_shipping_country" class="select">
                                    @foreach ($countries as $c)
                                        <option value="{{ $c['name'] }}">{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            @endif

                            <!-----------------------   county individual shipping  ----------------------------->
                            <div class="checkout__item checkout__item--required searchable active @error('individual_s_county') error @enderror"
                                id="individualShippingCountyParent" x-data="{
                                    countries: @js($countries),
                                    country: @entangle('individual_shipping_country'),
                                    county: @entangle('individual_shipping_county'),
                                    city: @entangle('individual_shipping_city'),
                                    open: false,

                                    counties() {
                                        const selected = this.countries.find(c =>
                                            c.name.toLowerCase() === (this.country || '').toLowerCase()
                                        );
                                        return selected?.counties || [];
                                    },

                                    filtered() {
                                        const searchTerm = (this.county || '').toLowerCase();
                                        return this.counties().filter(c =>
                                            c.name.toLowerCase().includes(searchTerm)
                                        );
                                    },

                                    select(name) {
                                        this.county = name;
                                        this.open = false;
                                        // Optional: if you want to call Livewire method explicitly:
                                        // $wire.selectShippingCounty(name);
                                    }
                                }"
                                @click.away="open = false" wire:ignore.self>
                                <input type="text" x-model.debounce.300ms="county" @focus="open = true"
                                    placeholder="@if (app()->has('label_order_county')) {!! app('label_order_county') !!} @endif"
                                    class="input" autocomplete="off" id="individualShippingCounty"
                                    aria-label="Shipping County" required>

                                <span>
                                    @error('individual_s_county')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <label for="individualShippingCounty">
                                    @if (app()->has('label_order_county'))
                                        {!! app('label_order_county') !!}
                                    @endif
                                </label>

                                <div x-show="open" class="content__searchable"
                                    style="display: none;">
                                    <div class="list__searchable">
                                        <template x-for="c in filtered()" :key="c.name">
                                            <button type="button" class="item__searchable" @click="select(c.name)"
                                                x-text="c.name"></button>
                                        </template>
                                        <template x-if="filtered().length === 0">
                                            <button class="item__searchable">No record found</button>
                                        </template>
                                    </div>
                                </div>
                            </div>


                            <!-----------------------   city individual shipping  ----------------------------->
                            <div class="checkout__item checkout__item--required searchable active"
                                id="individualShippingCityParent" x-data="{
                                    countries: @js($countries),
                                    country: @entangle('individual_shipping_country'),
                                    county: @entangle('individual_shipping_county'),
                                    city: @entangle('individual_shipping_city'),
                                    open: false,

                                    cities() {
                                        const selectedCountry = this.countries.find(c =>
                                            c.name.toLowerCase() === (this.country || '').toLowerCase()
                                        );
                                        const selectedCounty = selectedCountry?.counties?.find(cc =>
                                            cc.name.toLowerCase() === (this.county || '').toLowerCase()
                                        );
                                        return selectedCounty?.cities || [];
                                    },

                                    filtered() {
                                        const searchTerm = (this.city || '').toLowerCase();
                                        return this.cities().filter(c =>
                                            c.name.toLowerCase().includes(searchTerm)
                                        );
                                    },

                                    select(name) {
                                        this.city = name;
                                        this.open = false;
                                        // Optional: $wire.selectShippingCity(name);
                                    }
                                }" @click.away="open = false"
                                wire:ignore.self>
                                <input type="text" x-model.debounce.300ms="city" @focus="open = true"
                                    placeholder="@if (app()->has('label_order_city')) {!! app('label_order_city') !!} @endif"
                                    class="input" autocomplete="off" id="individualShippingCity"
                                    aria-label="Shipping City" required>

                                <span></span>
                                <label for="individualShippingCity">
                                    @if (app()->has('label_order_city'))
                                        {!! app('label_order_city') !!}
                                    @endif
                                </label>

                                <div x-show="open" class="content__searchable"
                                    style="display: none;">
                                    <div class="list__searchable">
                                        <template x-for="c in filtered()" :key="c.name">
                                            <button type="button" class="item__searchable" @click="select(c.name)"
                                                x-text="c.name"></button>
                                        </template>
                                        <template x-if="filtered().length === 0">
                                            <button class="item__searchable">No record found</button>
                                        </template>
                                    </div>
                                </div>
                            </div>


                            <!-----------------------   address1 individual shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="individualShippingAddressParent">
                                <input type="text" wire:model.defer="individual_shipping_address1"
                                    placeholder="@if (app()->has('label_order_address1')) {!! app('label_order_address1') !!} @endif"
                                    autocomplete="street-address" required id="individualShippingAddress">
                                <span></span>
                                <label for="individualShippingAddress">
                                    @if (app()->has('label_order_address1'))
                                        {!! app('label_order_address1') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   address2 individual shipping  ----------------------------->
                            @if (app()->has('global_order_display_address2') && app('global_order_display_address2') === 'true')
                                <div wire:ignore class="checkout__item" id="individualShippingAddress2Parent">
                                    <input type="text" wire:model.defer="individual_shipping_address2"
                                        placeholder="@if (app()->has('label_order_address2')) {!! app('label_order_address2') !!} @endif"
                                        autocomplete="address-level2" id="individualShippingAddress2">
                                    <span></span>
                                    <label for="individualShippingAddress2">
                                        @if (app()->has('label_order_address2'))
                                            {!! app('label_order_address2') !!}
                                        @endif
                                    </label>
                                </div>
                            @endif

                            <!-----------------------   zipcode individual shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item"
                                id="individualShippingPostalParent">
                                <input type="text" wire:model.defer="individual_shipping_zipcode"
                                    placeholder="@if (app()->has('label_order_zipcode')) {!! app('label_order_zipcode') !!} @endif"
                                    autocomplete="postal-code" id="individualShippingPostal">
                                <span></span>
                                <label for="individualShippingPostal">
                                    @if (app()->has('label_order_zipcode'))
                                        {!! app('label_order_zipcode') !!}
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-------------- End Individual -------------->
                    <!-------------- Juridic -------------->
                    <div class="checkout__container @if ($juridic) active @endif">
                        <div class="checkout__form active">
                            <div class="checkout__top">
                                <span>1</span>
                                <h3>
                                    @if (app()->has('label_order_billing_info_juridic'))
                                        {!! app('label_order_billing_info_juridic') !!}
                                    @endif
                                </h3>
                            </div>

                            <!-----------------------   first juridic billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="juridicBillingFirstNameParent">
                                <input type="text" wire:model.defer="juridic_billing_first"
                                    placeholder="@if (app()->has('label_order_firstname')) {!! app('label_order_firstname') !!} @endif"
                                    autocomplete="given-name" required id="juridicBillingFirstName">
                                <span></span>
                                <label for="juridicBillingFirstName">
                                    @if (app()->has('label_order_firstname'))
                                        {!! app('label_order_firstname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   last juridic billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="juridicBillingLastNameParent">
                                <input type="text" wire:model.defer="juridic_billing_last"
                                    placeholder="@if (app()->has('label_order_lastname')) {!! app('label_order_lastname') !!} @endif"
                                    autocomplete="family-name" required id="juridicBillingLastName">
                                <span></span>
                                <label for="juridicBillingLastName">
                                    @if (app()->has('label_order_lastname'))
                                        {!! app('label_order_lastname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   phone juridic billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="juridicBillingPhoneParent">
                                <input type="tel" wire:model.defer="juridic_billing_phone"
                                    placeholder="@if (app()->has('label_order_phone')) {!! app('label_order_phone') !!} @endif"
                                    autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required
                                    id="juridicBillingPhone">
                                <span></span>
                                <label for="juridicBillingPhone">
                                    @if (app()->has('label_order_phone'))
                                        {!! app('label_order_phone') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   email juridic billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="juridicBillingEmailParent">
                                <input type="email" wire:model.defer="juridic_billing_email"
                                    placeholder="@if (app()->has('label_order_email')) {!! app('label_order_email') !!} @endif"
                                    autocomplete="email" required id="juridicBillingEmail">
                                <span></span>
                                <label for="juridicBillingEmail">
                                    @if (app()->has('label_order_email'))
                                        {!! app('label_order_email') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   company name juridic billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required" id="companyNameParent">
                                <input type="text" wire:model.defer="juridic_billing_company_name"
                                    placeholder="@if (app()->has('label_order_company_name')) {!! app('label_order_company_name') !!} @endif"
                                    autocomplete="organization" required id="companyName">
                                <span></span>
                                <label for="companyName">
                                    @if (app()->has('label_order_company_name'))
                                        {!! app('label_order_company_name') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   registration_code juridic billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required" id="registerCodeParent">
                                <input type="text" wire:model.defer="juridic_billing_registration_code"
                                    placeholder="@if (app()->has('label_order_register_code')) {!! app('label_order_register_code') !!} @endif"
                                    autocomplete="off" required id="registerCode">
                                <span></span>
                                <label for="registerCode">
                                    @if (app()->has('label_order_register_code'))
                                        {!! app('label_order_register_code') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   registration_number juridic billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="registerNumberParent">
                                <input type="text" wire:model.defer="juridic_billing_registration_number"
                                    placeholder="@if (app()->has('label_order_register_number')) {!! app('label_order_register_number') !!} @endif"
                                    autocomplete="organization-number" required id="registerNumber">
                                <span></span>
                                <label for="registerNumber">
                                    @if (app()->has('label_order_register_number'))
                                        {!! app('label_order_register_number') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   bank juridic billing  ----------------------------->
                            <div wire:ignore class="checkout__item" id="bankNameParent">
                                <input type="text" wire:model.defer="juridic_billing_bank"
                                    placeholder="@if (app()->has('label_order_bankname')) {!! app('label_order_bankname') !!} @endif"
                                    autocomplete="off" id="bankName">
                                <span></span>
                                <label for="bankName">
                                    @if (app()->has('label_order_bankname'))
                                        {!! app('label_order_bankname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   account juridic billing  ----------------------------->
                            <div wire:ignore class="checkout__item" id="IBANParent">
                                <input type="text" wire:model.defer="juridic_billing_account"
                                    placeholder="@if (app()->has('label_order_iban')) {!! app('label_order_iban') !!} @endif"
                                    autocomplete="IBAN" id="IBAN">
                                <span></span>
                                <label for="IBAN">
                                    @if (app()->has('label_order_iban'))
                                        {!! app('label_order_iban') !!}
                                    @endif
                                </label>
                            </div>
                        </div>

                        <div class="checkout__form active">
                            <div class="checkout__top">
                                <span>2</span>
                                <h3>
                                    @if (app()->has('label_order_billing_address'))
                                        {!! app('label_order_billing_address') !!}
                                    @endif
                                </h3>
                            </div>

                            <!-----------------------   country juridic billing  ----------------------------->
                            @if (app()->has('global_order_display_country') && app('global_order_display_country') === 'true')
                                <select wire:model="juridic_billing_country" class="select">
                                    @foreach ($countries as $c)
                                        <option value="{{ $c['name'] }}">{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            @endif

                            <!-----------------------   county juridic billing  ----------------------------->
                            <div class="checkout__item checkout__item--required searchable active @error('juridic_b_county') error @enderror"
                                id="juridicBillingCountyParent" x-data="{
                                    countries: @js($countries),
                                    country: @entangle('juridic_billing_country'),
                                    county: @entangle('juridic_billing_county'),
                                    open: false,

                                    counties() {
                                        const selected = this.countries.find(c => c.name.toLowerCase() === (this.country || '').toLowerCase());
                                        return selected?.counties || [];
                                    },
                                    filtered() {
                                        return this.counties().filter(c =>
                                            c.name.toLowerCase().includes((this.county || '').toLowerCase())
                                        );
                                    },
                                    select(name) {
                                        this.county = name;
                                        this.open = false;
                                    }
                                }" @click.away="open = false"
                                wire:ignore>
                                <input type="text" x-model="county" @focus="open = true"
                                    placeholder="@if (app()->has('label_order_county')) {!! app('label_order_county') !!} @endif"
                                    class="input" autocomplete="off" required id="juridicBillingCounty">

                                <span>
                                    @error('juridic_b_county')
                                        {{ $message }}
                                    @enderror
                                </span>

                                <label for="juridicBillingCounty">
                                    @if (app()->has('label_order_county'))
                                        {!! app('label_order_county') !!}
                                    @endif
                                </label>

                                <div x-show="open" class="content__searchable">
                                    <div class="list__searchable">
                                        <template x-for="c in filtered()" :key="c.name">
                                            <button type="button" class="item__searchable" @click="select(c.name)"
                                                x-text="c.name"></button>
                                        </template>
                                        <template x-if="filtered().length === 0">
                                            <button class="item__searchable">{{ __('No record found') }}</button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-----------------------   city juridic billing  ----------------------------->
                            <div class="checkout__item checkout__item--required searchable active"
                                id="juridicBillingCityParent" x-data="{
                                    countries: @js($countries),
                                    country: @entangle('juridic_billing_country'),
                                    county: @entangle('juridic_billing_county'),
                                    city: @entangle('juridic_billing_city'),
                                    open: false,

                                    cities() {
                                        const selectedCountry = this.countries.find(c => c.name.toLowerCase() === (this.country || '').toLowerCase());
                                        const selectedCounty = selectedCountry?.counties?.find(cc => cc.name.toLowerCase() === (this.county || '').toLowerCase());
                                        return selectedCounty?.cities || [];
                                    },

                                    filtered() {
                                        const searchTerm = (this.city || '').toLowerCase();
                                        return this.cities().filter(c => c.name.toLowerCase().includes(searchTerm));
                                    },

                                    select(name) {
                                        this.city = name;
                                        this.open = false;
                                    }
                                }" @click.away="open = false"
                                wire:ignore>
                                <input type="text" x-model="city" @focus="open = true"
                                    placeholder="@if (app()->has('label_order_city')) {!! app('label_order_city') !!} @endif"
                                    class="input" autocomplete="off" required id="juridicBillingCity">

                                <span></span>

                                <label for="juridicBillingCity">
                                    @if (app()->has('label_order_city'))
                                        {!! app('label_order_city') !!}
                                    @endif
                                </label>

                                <div x-show="open" class="content__searchable">
                                    <div class="list__searchable">
                                        <template x-for="c in filtered()" :key="c.name">
                                            <button type="button" class="item__searchable" @click="select(c.name)"
                                                x-text="c.name"></button>
                                        </template>
                                        <template x-if="filtered().length === 0">
                                            <button class="item__searchable">{{ __('No record found') }}</button>
                                        </template>
                                    </div>
                                </div>
                            </div>


                            <!-----------------------   address1 juridic billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="juridicBillingAddressParent">
                                <input type="text" wire:model.defer="juridic_billing_address1"
                                    placeholder="@if (app()->has('label_order_address1')) {!! app('label_order_address1') !!} @endif"
                                    autocomplete="street-address" required id="juridicBillingAddress">
                                <span></span>
                                <label for="juridicBillingAddress">
                                    @if (app()->has('label_order_address1'))
                                        {!! app('label_order_address1') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   address2 juridic billing  ----------------------------->
                            @if (app()->has('global_order_display_address2') && app('global_order_display_address2') === 'true')
                                <div wire:ignore class="checkout__item" id="juridicBillingAddress2Parent">
                                    <input type="text" wire:model.defer="juridic_billing_address2"
                                        placeholder="@if (app()->has('label_order_address2')) {!! app('label_order_address2') !!} @endif"
                                        autocomplete="address-level2" id="juridicBillingAddress2">
                                    <span></span>
                                    <label for="juridicBillingAddress2">
                                        @if (app()->has('label_order_address2'))
                                            {!! app('label_order_address2') !!}
                                        @endif
                                    </label>
                                </div>
                            @endif

                            <!-----------------------   zipcode juridic billing  ----------------------------->
                            <div wire:ignore class="checkout__item" id="juridicBillingPostalParent">
                                <input type="text" wire:model.defer="juridic_billing_zipcode"
                                    placeholder="@if (app()->has('label_order_zipcode')) {!! app('label_order_zipcode') !!} @endif"
                                    autocomplete="postal-code" id="juridicBillingPostal">
                                <span></span>
                                <label for="juridicBillingPostal">
                                    @if (app()->has('label_order_zipcode'))
                                        {!! app('label_order_zipcode') !!}
                                    @endif
                                </label>
                            </div>
                        </div>

                        <!-----------------------   Juridic IDentic  ----------------------------->
                        <label class="checkout__checkbox">
                            <input type="checkbox" wire:model="juridic_identic" id="juridic_identic"
                                name="juridic_identic">
                            <span>
                                @if (app()->has('label_order_identic'))
                                    {!! app('label_order_identic') !!}
                                @endif
                            </span>
                        </label>
                        <div class="checkout__form @if (!$juridic_identic && $juridic) active @endif">
                            <div class="checkout__top">
                                <span>3</span>
                                <h3>
                                    @if (app()->has('label_order_shipping_info'))
                                        {!! app('label_order_shipping_info') !!}
                                    @endif
                                </h3>
                            </div>

                            <!-----------------------   first juridic shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="juridicShippingFirstNameParent">
                                <input type="text" wire:model.defer="juridic_shipping_first" placeholder="Prenume"
                                    autocomplete="given-name" required id="juridicShippingFirstName">
                                <span></span>
                                <label for="juridicShippingFirstName">
                                    @if (app()->has('label_order_firstname'))
                                        {!! app('label_order_firstname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   last juridic shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="juridicShippingLastNameParent">
                                <input type="text" wire:model.defer="juridic_shipping_last"
                                    placeholder="@if (app()->has('label_order_lastname')) {!! app('label_order_lastname') !!} @endif"
                                    autocomplete="family-name" required id="juridicShippingLastName">
                                <span></span>
                                <label for="juridicShippingLastName">
                                    @if (app()->has('label_order_lastname'))
                                        {!! app('label_order_lastname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   phone juridic shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="juridicShippingPhoneParent">
                                <input type="tel" wire:model.defer="juridic_shipping_phone"
                                    placeholder="@if (app()->has('label_order_phone')) {!! app('label_order_phone') !!} @endif"
                                    autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required
                                    id="juridicShippingPhone">
                                <span></span>
                                <label for="juridicShippingPhone">
                                    @if (app()->has('label_order_phone'))
                                        {!! app('label_order_phone') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   email juridic shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="juridicShippingEmailParent">
                                <input type="email" wire:model.defer="juridic_shipping_email"
                                    placeholder="@if (app()->has('label_order_email')) {!! app('label_order_email') !!} @endif"
                                    autocomplete="email" required id="juridicShippingEmail">
                                <span></span>
                                <label for="juridicShippingEmail">
                                    @if (app()->has('label_order_email'))
                                        {!! app('label_order_email') !!}
                                    @endif
                                </label>
                            </div>
                        </div>

                        <div class="checkout__form @if (!$juridic_identic && $juridic) active @endif">
                            <div class="checkout__top">
                                <span>4</span>
                                <h3>
                                    @if (app()->has('label_order_shipping_address'))
                                        {!! app('label_order_shipping_address') !!}
                                    @endif
                                </h3>
                            </div>

                            <!-----------------------   country juridic shipping  ----------------------------->
                            @if (app()->has('global_order_display_country') && app('global_order_display_country') === 'true')
                                <select wire:model="juridic_shipping_country" class="select">
                                    @foreach ($countries as $c)
                                        <option value="{{ $c['name'] }}">{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            @endif

                            <!-----------------------   county juridic shipping  ----------------------------->
                            <div class="checkout__item checkout__item--required searchable active @error('juridic_s_county') error @enderror"
                                id="juridicShippingCountyParent" x-data="{
                                    countries: @js($countries),
                                    country: @entangle('juridic_shipping_country'),
                                    county: @entangle('juridic_shipping_county'),
                                    open: false,

                                    counties() {
                                        const selected = this.countries.find(c => c.name.toLowerCase() === (this.country || '').toLowerCase());
                                        return selected?.counties || [];
                                    },

                                    filtered() {
                                        const search = (this.county || '').toLowerCase();
                                        return this.counties().filter(c => c.name.toLowerCase().includes(search));
                                    },

                                    select(name) {
                                        this.county = name;
                                        this.open = false;
                                    }
                                }" @click.away="open = false"
                                wire:ignore>
                                <input type="text" x-model="county" @focus="open = true"
                                    placeholder="@if (app()->has('label_order_county')) {!! app('label_order_county') !!} @endif"
                                    autocomplete="county" required id="juridicShippingCounty" class="input">

                                <span>
                                    @error('juridic_s_county')
                                        {{ $message }}
                                    @enderror
                                </span>

                                <label for="juridicShippingCounty">
                                    @if (app()->has('label_order_county'))
                                        {!! app('label_order_county') !!}
                                    @endif
                                </label>

                                <div x-show="open" class="content__searchable">
                                    <div class="list__searchable">
                                        <template x-for="c in filtered()" :key="c.name">
                                            <button type="button" class="item__searchable" @click="select(c.name)"
                                                x-text="c.name"></button>
                                        </template>
                                        <template x-if="filtered().length === 0">
                                            <button class="item__searchable">{{ __('No record found') }}</button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-----------------------   city juridic shipping  ----------------------------->
                            <div class="checkout__item checkout__item--required searchable active"
                                id="juridicShippingCityParent" x-data="{
                                    countries: @js($countries),
                                    country: @entangle('juridic_shipping_country'),
                                    county: @entangle('juridic_shipping_county'),
                                    city: @entangle('juridic_shipping_city'),
                                    open: false,

                                    cities() {
                                        const selectedCountry = this.countries.find(c => c.name.toLowerCase() === (this.country || '').toLowerCase());
                                        const selectedCounty = selectedCountry?.counties?.find(cc => cc.name.toLowerCase() === (this.county || '').toLowerCase());
                                        return selectedCounty?.cities || [];
                                    },

                                    filtered() {
                                        const searchTerm = (this.city || '').toLowerCase();
                                        return this.cities().filter(c => c.name.toLowerCase().includes(searchTerm));
                                    },

                                    select(name) {
                                        this.city = name;
                                        this.open = false;
                                    }
                                }" @click.away="open = false"
                                wire:ignore>
                                <input type="text" x-model="city" @focus="open = true"
                                    placeholder="@if (app()->has('label_order_city')) {!! app('label_order_city') !!} @endif"
                                    autocomplete="off" required id="juridicShippingCity" class="input">

                                <span></span>

                                <label for="juridicShippingCity">
                                    @if (app()->has('label_order_city'))
                                        {!! app('label_order_city') !!}
                                    @endif
                                </label>

                                <div x-show="open" class="content__searchable">
                                    <div class="list__searchable">
                                        <template x-for="c in filtered()" :key="c.name">
                                            <button type="button" class="item__searchable" @click="select(c.name)"
                                                x-text="c.name"></button>
                                        </template>
                                        <template x-if="filtered().length === 0">
                                            <button class="item__searchable">{{ __('No record found') }}</button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-----------------------   address1 juridic shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="juridicShippingAddressParent">
                                <input type="text" wire:model.defer="juridic_shipping_address1"
                                    placeholder="@if (app()->has('label_order_address1')) {!! app('label_order_address1') !!} @endif"
                                    autocomplete="street-address" required id="juridicShippingAddress">
                                <span></span>
                                <label for="juridicShippingAddress">
                                    @if (app()->has('label_order_address1'))
                                        {!! app('label_order_address1') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   address2 juridic shipping  ----------------------------->
                            @if (app()->has('global_order_display_address2') && app('global_order_display_address2') === 'true')
                                <div wire:ignore class="checkout__item" id="juridicShippingAddress2Parent">
                                    <input type="text" wire:model.defer="juridic_shipping_address2"
                                        placeholder="@if (app()->has('label_order_address2')) {!! app('label_order_address2') !!} @endif"
                                        autocomplete="address-level2" id="juridicShippingAddress2">
                                    <span></span>
                                    <label for="juridicShippingAddress2">
                                        @if (app()->has('label_order_address2'))
                                            {!! app('label_order_address2') !!}
                                        @endif
                                    </label>
                                </div>
                            @endif

                            <!-----------------------   zipcode juridic shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item" id="juridicShippingPostalParent">
                                <input type="text" wire:model.defer="juridic_shipping_zipcode"
                                    placeholder="@if (app()->has('label_order_zipcode')) {!! app('label_order_zipcode') !!} @endif"
                                    autocomplete="postal-code" id="juridicShippingPostal">
                                <span></span>
                                <label for="juridicShippingPostal">
                                    @if (app()->has('label_order_zipcode'))
                                        {!! app('label_order_zipcode') !!}
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-------------- End Juridic -------------->

                    <!-------------- Payment -------------->
                    <div class="section__header">
                        <h2 class="section__title">
                            @if (app()->has('label_order_payment_method'))
                                {!! app('label_order_payment_method') !!}
                            @endif
                        </h2>
                    </div>

                    <!-------------- cash -------------->
                    @if ($cash['active'] != 0)
                        <div class="payment">
                            <label class="payment__wrapper" for="rtc" wire:click="togglepayment('rtc')">
                                <input class="payment__checkbox" type="checkbox" wire:model.defer="rtc"
                                    id="rtc">
                                <span>
                                    @if (app()->has('label_order_cash_title'))
                                        {!! app('label_order_cash_title') !!}
                                    @endif
                                </span>
                            </label>
                            <div class="payment__text @if ($rtc) active @endif">
                                <h3>
                                    @if (app()->has('label_order_cash_description'))
                                        {!! app('label_order_cash_description') !!}
                                    @endif
                                </h3>
                                @if (app()->has('global_cash_limit') && app('global_cash_limit') != 0)
                                    <span>
                                        @if (app()->has('label_order_cash_limit_text'))
                                            {!! app('label_order_cash_limit_text') !!}
                                        @endif {{ app('global_cash_limit') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                    <!-------------- card -------------->
                    @if ($card['active'] != 0)
                        <div class="payment">
                            <label class="payment__wrapper" for="crd" wire:click="togglepayment('crd')">
                                <input class="payment__checkbox" type="checkbox" wire:model.defer="crd"
                                    id="crd">
                                <span>
                                    @if (app()->has('label_order_cart_stripe_title'))
                                        {!! app('label_order_cart_stripe_title') !!}
                                    @endif
                                </span>
                            </label>
                            <div class="payment__text @if ($crd) active @endif">
                                <h3>
                                    @if (app()->has('label_order_cart_stripe_description'))
                                        {!! app('label_order_cart_stripe_description') !!}
                                    @endif
                                </h3>
                            </div>
                        </div>
                    @endif
                    <!-------------- ordin -------------->
                    @if ($ordin['active'] != 0)
                        @if ($juridic)
                            <div class="payment">
                                <label class="payment__wrapper" for="invoice" wire:click="togglepayment('invoice')">
                                    <input class="payment__checkbox" type="checkbox" wire:model.defer="invoice"
                                        id="invoice">
                                    <span>
                                        @if (app()->has('label_order_invoice_title'))
                                            {!! app('label_order_invoice_title') !!}
                                        @endif
                                    </span>
                                </label>
                                <div class="payment__text @if ($invoice) active @endif">
                                    <h3>
                                        @if (app()->has('label_order_invoice_description'))
                                            {!! app('label_order_invoice_description') !!}
                                        @endif
                                    </h3>
                                </div>
                            </div>
                        @endif
                    @endif
                    <!-------------- End Payment -------------->


                    <!------------------------- Scrpt for Checkout --------------------------->
                    <script src="/script/store/order.js"></script>
                    {{-- <div class="dlv" style="display: none">
      <span class="dlv_currency">
       @if (app()->has('global_currency_primary_name'))
        {!! app('global_currency_primary_name') !!}
       @endif
      </span>
      <span class="dlv_value">{{ $cart->final_amount }}</span>
      <span class="dlv_coupon">{{ optional($cart->voucher)->code }}</span>
      @foreach ($cart->cartItems as $cartItem)
       <div class="dlv_item">
        <span class="dlv_item-id">{{ $cartItem->product->id }}</span>
        <span class="dlv_item-name">{{ $cartItem->product->name }}</span>
        <span class="dlv_item-price">{{ $cartItem->price }}</span>
        <span class="dlv_item-quantity">{{ $cartItem->quantity }}</span>
       </div>
      @endforeach
     </div>

     <script>
      function begin_checkout() {
       var dlv = document.querySelector('.dlv');
       if (!dlv) {
        console.error('Elementul cu clasa .dlv nu a fost găsit.');
        return;
       }
       var currency = dlv.querySelector('.dlv_currency').innerText.trim();
       var value = parseFloat(dlv.querySelector('.dlv_value').innerText.trim().replace(',', '.'));
       var coupon = dlv.querySelector('.dlv_coupon').innerText.trim() || undefined;
       var items = [];
       var dlv_items = dlv.querySelectorAll('.dlv_item');
       dlv_items.forEach(dlv_item => {
        var item_id = dlv_item.querySelector('.dlv_item-id').innerText.trim();
        var item_name = dlv_item.querySelector('.dlv_item-name').innerText.trim();
        var item_price = parseFloat(dlv_item.querySelector('.dlv_item-price').innerText.trim().replace(',', '.'));
        var item_quantity = parseInt(dlv_item.querySelector('.dlv_item-quantity').innerText.trim(), 10);
        var item = {
         item_id: item_id,
         item_name: item_name,
         price: item_price,
         quantity: item_quantity
        };
        items.push(item);
       });
       var dlvData = {
        currency: currency,
        value: value,
        coupon: coupon,
        items: items
       };
       return dlvData;
      };
      var dlvData = begin_checkout();
      dataLayer.push({
       ecommerce: null
      });
      dataLayer.push({
       event: "begin_checkout",
       ecommerce: {
        currency: dlvData.currency,
        value: dlvData.value,
        coupon: dlvData.coupon,
        items: dlvData.items
       }
      });
     </script> --}}
                    <!-------------- End Script -------------->

                @endif
                <!------------------------------------------------------>
                @if ($step == 2)
                    <?php $disables = []; ?>
                    <div class="section__header">
                        <h2 class="section__title">
                            @if (app()->has('label_order_details_check'))
                                {!! app('label_order_details_check') !!}
                            @endif
                        </h2>
                    </div>
                    <div class="total__container">
                        <!-------------- Individual information -------------->
                        @if ($individual)
                            <div class="look__form">
                                <h3>
                                    @if (app()->has('label_order_billing_check'))
                                        {!! app('label_order_billing_check') !!}
                                    @endif
                                </h3>
                                <span class="total__message">
                                    @if (app()->has('label_order_fullname'))
                                        {!! app('label_order_fullname') !!}
                                    @endif:
                                    <strong>{{ $individual_billing_first }}</strong>
                                    <strong>{{ $individual_billing_last }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_phone'))
                                        {!! app('label_order_phone') !!}
                                    @endif:
                                    <strong>{{ $individual_billing_phone }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_email'))
                                        {!! app('label_order_email') !!}
                                    @endif:
                                    <strong>{{ $individual_billing_email }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_address1'))
                                        {!! app('label_order_address1') !!}
                                    @endif:
                                    <strong>{{ $individual_billing_address1 }}</strong>
                                    <strong>{{ $individual_billing_address2 }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_country'))
                                        {!! app('label_order_country') !!}
                                    @endif:
                                    <strong>{{ $individual_billing_country }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_county'))
                                        {!! app('label_order_county') !!}
                                    @endif:
                                    <strong>{{ $individual_billing_county }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_city'))
                                        {!! app('label_order_city') !!}
                                    @endif:
                                    <strong>{{ $individual_billing_city }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_zipcode'))
                                        {!! app('label_order_zipcode') !!}
                                    @endif:
                                    <strong>{{ $individual_billing_zipcode }}</strong>
                                </span>

                                <h3>
                                    @if (app()->has('label_order_delivery_check'))
                                        {!! app('label_order_delivery_check') !!}
                                    @endif
                                </h3>

                                <span class="total__message">
                                    @if (app()->has('label_order_fullname'))
                                        {!! app('label_order_fullname') !!}
                                    @endif:
                                    <strong>{{ $individual_shipping_first }}</strong>
                                    <strong>{{ $individual_shipping_last }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_phone'))
                                        {!! app('label_order_phone') !!}
                                    @endif:
                                    <strong>{{ $individual_shipping_phone }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_email'))
                                        {!! app('label_order_email') !!}
                                    @endif:
                                    <strong>{{ $individual_shipping_email }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_address1'))
                                        {!! app('label_order_address1') !!}
                                    @endif:
                                    <strong>{{ $individual_shipping_address1 }}</strong>
                                    <strong>{{ $individual_shipping_address2 }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_country'))
                                        {!! app('label_order_country') !!}
                                    @endif:
                                    <strong>{{ $individual_shipping_country }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_county'))
                                        {!! app('label_order_county') !!}
                                    @endif:
                                    <strong>{{ $individual_shipping_county }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_city'))
                                        {!! app('label_order_city') !!}
                                    @endif:
                                    <strong>{{ $individual_shipping_city }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_zipcode'))
                                        {!! app('label_order_zipcode') !!}
                                    @endif:
                                    <strong>{{ $individual_shipping_zipcode }}</strong>
                                </span>
                            </div>
                        @else
                            <div class="look__form">
                                <!------------- Juridic Information --------------->
                                <h3>
                                    @if (app()->has('label_order_billing_check'))
                                        {!! app('label_order_billing_check') !!}
                                    @endif
                                </h3>
                                <span class="total__message">
                                    @if (app()->has('label_order_fullname'))
                                        {!! app('label_order_fullname') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_first }}</strong>
                                    <strong>{{ $juridic_billing_last }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_phone'))
                                        {!! app('label_order_phone') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_phone }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_email'))
                                        {!! app('label_order_email') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_email }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_company_name'))
                                        {!! app('label_order_company_name') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_company_name }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_register_code'))
                                        {!! app('label_order_register_code') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_registration_code }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_register_number'))
                                        {!! app('label_order_register_number') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_registration_number }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_bankname'))
                                        {!! app('label_order_bankname') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_bank }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_iban'))
                                        {!! app('label_order_iban') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_account }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_address1'))
                                        {!! app('label_order_address1') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_address1 }}</strong>
                                    <strong>{{ $juridic_billing_address2 }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_country'))
                                        {!! app('label_order_country') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_country }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_county'))
                                        {!! app('label_order_county') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_county }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_city'))
                                        {!! app('label_order_city') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_city }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_zipcode'))
                                        {!! app('label_order_zipcode') !!}
                                    @endif:
                                    <strong>{{ $juridic_billing_zipcode }}</strong>
                                </span>

                                <h3>
                                    @if (app()->has('label_order_delivery_check'))
                                        {!! app('label_order_delivery_check') !!}
                                    @endif
                                </h3>

                                <span class="total__message">
                                    @if (app()->has('label_order_fullname'))
                                        {!! app('label_order_fullname') !!}
                                    @endif:
                                    <strong>{{ $juridic_shipping_first }}</strong>
                                    <strong>{{ $juridic_shipping_last }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_phone'))
                                        {!! app('label_order_phone') !!}
                                    @endif:
                                    <strong>{{ $juridic_shipping_phone }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_email'))
                                        {!! app('label_order_email') !!}
                                    @endif:
                                    <strong>{{ $juridic_shipping_email }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_address1'))
                                        {!! app('label_order_address1') !!}
                                    @endif:
                                    <strong>{{ $juridic_shipping_address1 }}</strong>
                                    <strong>{{ $juridic_shipping_address2 }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_country'))
                                        {!! app('label_order_country') !!}
                                    @endif:
                                    <strong>{{ $juridic_shipping_country }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_county'))
                                        {!! app('label_order_county') !!}
                                    @endif:
                                    <strong>{{ $juridic_shipping_county }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_city'))
                                        {!! app('label_order_city') !!}
                                    @endif:
                                    <strong>{{ $juridic_shipping_city }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_zipcode'))
                                        {!! app('label_order_zipcode') !!}
                                    @endif:
                                    <strong>{{ $juridic_shipping_zipcode }}</strong>
                                </span>
                            </div>
                        @endif
                        <!---------------------------------------------------->
                        <div class="total__info">
                            @if ($cart->cartItems)
                                @foreach ($cart->cartItems as $index => $cartItem)
                                    @php
                                        $disabled[$index] = false;
                                        $nonquantity[$index] = false;
                                        if (
                                            $cartItem->product->active != true ||
                                            $cartItem->product->start_date > now()->format('Y-m-d') ||
                                            $cartItem->product->end_date < now()->format('Y-m-d')
                                        ) {
                                            $disabled[$index] = true;
                                            $this->emit('isdisabled');
                                        }
                                        if (
                                            $cartItem->product->quantity < $cartItem->quantity &&
                                            !$cartItem->product->preorder
                                        ) {
                                            $nonquantity[$index] = true;
                                            $this->emit('isdisabled');
                                        }
                                    @endphp
                                    <div class="total__product">
                                        <span class="total__quantity">{{ $cartItem->quantity }} x</span>
                                        @if ($cartItem->product->media->first())
                                            <img class="cart__list--img" title="{{ $cartItem->product->name }}"
                                                src="/{{ $cartItem->product->media->first()->path }}{{ $cartItem->product->media->first()->name }}"
                                                alt="{{ $cartItem->product->media->first()->name }} {{ $cartItem->product->name }}">
                                        @else
                                            <img title="Default image" class="cart__list--img"
                                                src="/images/store/default/default70.webp" alt="something wrong">
                                        @endif
                                        <!------------------------- No Quantity of Cart Item --------------------------->
                                        @if ($nonquantity[$index])
                                            <div class="leftbar__link--text">
                                                <a href="{{ route('product', ['product' => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== '' ? $cartItem->product->seo_id : $cartItem->product->id]) }}"
                                                    target="_blank"
                                                    class="total__name">{{ $cartItem->product->name }}</a>
                                                <span class="item__product--error">
                                                    @if (app()->has('label_product_quantity_error'))
                                                        {!! app('label_product_quantity_error') !!}
                                                    @endif {{ $cartItem->product->quantity }}
                                                </span>
                                                <a class="item__product--link" href="{{ url('/cart') }}">
                                                    @if (app()->has('label_product_quantity_modify'))
                                                        {!! app('label_product_quantity_modify') !!}
                                                    @endif
                                                </a>
                                            </div>
                                        @else
                                            <a href="{{ route('product', ['product' => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== '' ? $cartItem->product->seo_id : $cartItem->product->id]) }}"
                                                target="_blank"
                                                class="total__name">{{ $cartItem->product->name }}</a>
                                        @endif
                                        <!------------------------- Indisponibble of Cart Item --------------------------->
                                        @if ($disabled[$index])
                                            <div class="item__product--disabled">
                                                <span>
                                                    @if (app()->has('label_product_status_indisponible'))
                                                        {!! app('label_product_status_indisponible') !!}
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                        <span class="total__price">
                                            {{ number_format($cartItem->quantity * $cartItem->price, 2, $decimal, $mill) }}
                                            @if (app()->has('global_currency_primary_symbol'))
                                                {!! app('global_currency_primary_symbol') !!}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                                <!------------ Order summary amount ------------>
                                <div class="total__item">
                                    <span>
                                        @if (app()->has('label_order_payment_method'))
                                            {!! app('label_order_payment_method') !!}
                                        @endif
                                    </span>
                                    <span>{{ $payment['description'] }}</span>
                                </div>
                                @if ($cart->promotion_value > 0)
                                    <div class="total__item">
                                        <span>
                                            @if (app()->has('label_cart_promotion_tag'))
                                                {!! app('label_cart_promotion_tag') !!}
                                            @endif
                                        </span>
                                        <span>

                                            -{{ number_format($cart->promotion_value, 2, $decimal, $mill) }}
                                            @if (app()->has('global_currency_primary_symbol'))
                                                {!! app('global_currency_primary_symbol') !!}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                                <div class="total__item">
                                    <span>
                                        @if (app()->has('label_cart_delivery_tag'))
                                            {!! app('label_cart_delivery_tag') !!}
                                        @endif
                                    </span>
                                    <span>
                                        @if ($cart->delivery_price == 0)
                                            @if (app()->has('label_cart_delivery_free'))
                                                {!! app('label_cart_delivery_free') !!}
                                            @endif
                                        @else
                                            {{ number_format($cart->delivery_price, 2, $decimal, $mill) }}
                                            @if (app()->has('global_currency_primary_symbol'))
                                                {!! app('global_currency_primary_symbol') !!}
                                            @endif
                                        @endif
                                    </span>
                                </div>
                                @if ($cart->voucher && $cart->voucher_value > 0)
                                    <div class="total__item">
                                        <span>
                                            @if (app()->has('label_cart_voucher_tag'))
                                                {!! app('label_cart_voucher_tag') !!}
                                            @endif
                                        </span>
                                        <span>
                                            -{{ number_format($cart->voucher_value, 2, $decimal, $mill) }}
                                            @if (app()->has('global_currency_primary_symbol'))
                                                {!! app('global_currency_primary_symbol') !!}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                                <div class="total__item">
                                    <span>
                                        @if (app()->has('label_cart_total_tag'))
                                            {!! app('label_cart_total_tag') !!}
                                        @endif
                                    </span>
                                    <span>{{ number_format($cart->final_amount - $cart->promotion_value, 2, $decimal, $mill) }}
                                        @if (app()->has('global_currency_primary_symbol'))
                                            {!! app('global_currency_primary_symbol') !!}
                                        @endif
                                    </span>
                                </div>
                                @if ($modification)
                                    <span class="item__text--disabled">
                                        @if (app()->has('label_cart_order_error'))
                                            {!! app('label_cart_order_error') !!}
                                        @endif
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <label id="termsbutton" class="checkout__terms @if ($errorterms && $terms == false) error @endif">
                        <input type="checkbox" wire:model="terms" name="terms">
                        <span>
                            @if (app()->has('label_terms_checkout'))
                                {!! app('label_terms_checkout') !!}
                            @endif
                        </span>
                    </label>

                    <!-- DataLayer Script pentru Step 2 -->
                    <div class="dlv" style="display: none">
                        <span class="dlv_currency">
                            @if (app()->has('global_currency_primary_name'))
                                {!! app('global_currency_primary_name') !!}
                            @endif
                        </span>
                        <span class="dlv_value">{{ $cart->final_amount - $cart->promotion_value }}</span>
                        <span class="dlv_coupon">{{ optional($cart->voucher)->code }}</span>
                        <span class="dlv_payment">{{ $payment['description'] }}</span>
                        @foreach ($cart->cartItems as $cartItem)
                            <div class="dlv_item">
                                <span class="dlv_item-id">{{ $cartItem->product->id }}</span>
                                <span class="dlv_item-name">{{ $cartItem->product->name }}</span>
                                <span class="dlv_item-price">{{ $cartItem->price }}</span>
                                <span class="dlv_item-quantity">{{ $cartItem->quantity }}</span>
                            </div>
                        @endforeach
                    </div>
                    <script>
                        function add_payment_info() {
                            // Variables
                            var dlv = document.querySelector('.dlv');
                            // Check if dlv element exists
                            if (!dlv) {
                                console.error('Elementul cu clasa .dlv nu a fost găsit.');
                                return;
                            }
                            // Get all Values
                            var currency = dlv.querySelector('.dlv_currency').innerText.trim();
                            var value = parseFloat(dlv.querySelector('.dlv_value').innerText.trim().replace(',', '.'));
                            var coupon = dlv.querySelector('.dlv_coupon').innerText.trim() || undefined;
                            var payment = dlv.querySelector('.dlv_payment').innerText.trim();
                            var items = [];
                            // Get all items
                            var dlv_items = dlv.querySelectorAll('.dlv_item');
                            // Loop through each item
                            dlv_items.forEach(dlv_item => {
                                var item_id = dlv_item.querySelector('.dlv_item-id').innerText.trim();
                                var item_name = dlv_item.querySelector('.dlv_item-name').innerText.trim();
                                var item_price = parseFloat(dlv_item.querySelector('.dlv_item-price').innerText.trim().replace(',',
                                    '.'));
                                var item_quantity = parseInt(dlv_item.querySelector('.dlv_item-quantity').innerText.trim(), 10);

                                // Create item object
                                var item = {
                                    item_id: item_id,
                                    item_name: item_name,
                                    price: item_price,
                                    quantity: item_quantity
                                };

                                // Push item in the array
                                items.push(item);
                            });

                            // Create the data object
                            var dlvData = {
                                currency: currency,
                                value: value,
                                coupon: coupon,
                                payment: payment,
                                items: items
                            };
                            // Show the object
                            // console.log(dlvData);
                            return dlvData;
                        };
                        var dlvData = add_payment_info();

                        // Trimiterea datelor la Google Analytics folosind dataLayer.push
                        dataLayer.push({
                            ecommerce: null
                        });
                        dataLayer.push({
                            event: "add_payment_info",
                            ecommerce: {
                                currency: dlvData.currency,
                                value: dlvData.value,
                                coupon: dlvData.coupon,
                                payment: dlvData.payment,
                                items: dlvData.items
                            }
                        });
                        dataLayer.push({
                            ecommerce: null
                        });
                    </script>
                    <script>
                        function add_shipping_info() {
                            // Variables
                            var dlv = document.querySelector('.dlv');
                            // Check if dlv element exists
                            if (!dlv) {
                                console.error('Elementul cu clasa .dlv nu a fost găsit.');
                                return;
                            }
                            // Get all Values
                            var currency = dlv.querySelector('.dlv_currency').innerText.trim();
                            var value = parseFloat(dlv.querySelector('.dlv_value').innerText.trim().replace(',', '.'));
                            var coupon = dlv.querySelector('.dlv_coupon').innerText.trim() || undefined;
                            var items = [];
                            // Get all items
                            var dlv_items = dlv.querySelectorAll('.dlv_item');
                            // Loop through each item
                            dlv_items.forEach(dlv_item => {
                                var item_id = dlv_item.querySelector('.dlv_item-id').innerText.trim();
                                var item_name = dlv_item.querySelector('.dlv_item-name').innerText.trim();
                                var item_price = parseFloat(dlv_item.querySelector('.dlv_item-price').innerText.trim().replace(',',
                                    '.'));
                                var item_quantity = parseInt(dlv_item.querySelector('.dlv_item-quantity').innerText.trim(), 10);

                                // Create item object
                                var item = {
                                    item_id: item_id,
                                    item_name: item_name,
                                    price: item_price,
                                    quantity: item_quantity
                                };

                                // Push item in the array
                                items.push(item);
                            });

                            // Create the data object
                            var dlvData = {
                                currency: currency,
                                value: value,
                                coupon: coupon,
                                items: items
                            };
                            // Show the object

                            return dlvData;
                        };
                        var dlvData = add_shipping_info();
                        // Trimiterea datelor la Google Analytics folosind dataLayer.push
                        dataLayer.push({
                            event: "add_shipping_info",
                            ecommerce: {
                                currency: dlvData.currency,
                                value: dlvData.value,
                                coupon: dlvData.coupon,
                                items: dlvData.items
                            }
                        });
                    </script>
                @endif
                <!------------------- Script for blade terms ------------------>
                <script>
                    window.addEventListener('beforeunload', function() {
                        Livewire.emit('saveFormToSession');
                    });
                    window.addEventListener('terms__error', event => {
                        var element = document.getElementById('termsbutton');
                        if (element) {
                            element.scrollIntoView();
                        }
                    });
                    window.addEventListener('goup', event => {
                        window.scroll({
                            top: 0,
                            left: 0,
                            behavior: 'smooth'
                        });
                    });
                </script>
    @endif

    <!------------------------------------------>
    @if ($step == 3)
        <section class="section__header container">
            <h1 class="section__title">
                @if (app()->has('label_order_tag'))
                    {!! app('label_order_tag') !!}
                @endif {{ $new_order->order_number }}
            </h1>
        </section>

        <div class="section__header">
            <h2>
                @if (app()->has('label_order_default_text_confirmation'))
                    {!! app('label_order_default_text_confirmation') !!}
                @endif
            </h2>
        </div>
        <div class="total__container">
            <!------------- Order details --------------->
            <div class="look__form">
                <h3>
                    @if (app()->has('label_order_billing_check'))
                        {!! app('label_order_billing_check') !!}
                    @endif
                </h3>
                <span class="total__message">
                    @if (app()->has('label_order_fullname'))
                        {!! app('label_order_fullname') !!}
                    @endif:
                    <strong>{{ $new_order->account->name }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_phone'))
                        {!! app('label_order_phone') !!}
                    @endif:
                    <strong>{{ $new_order->billing->phone }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_email'))
                        {!! app('label_order_email') !!}
                    @endif:
                    <strong>{{ $new_order->billing->email }}</strong>
                </span>
                @if ($juridic)
                    <span class="total__message">
                        @if (app()->has('label_order_company_name'))
                            {!! app('label_order_company_name') !!}
                        @endif:
                        <strong>{{ $new_order->account->company_name }}</strong>
                    </span>
                    <span class="total__message">
                        @if (app()->has('label_order_register_code'))
                            {!! app('label_order_register_code') !!}
                        @endif:
                        <strong>{{ $new_order->account->registration_code }}</strong>
                    </span>
                    <span class="total__message">
                        @if (app()->has('label_order_register_number'))
                            {!! app('label_order_register_number') !!}
                        @endif:
                        <strong>{{ $new_order->account->registration_number }}</strong>
                    </span>
                    <span class="total__message">
                        @if (app()->has('label_order_bankname'))
                            {!! app('label_order_bankname') !!}
                        @endif:
                        <strong>{{ $new_order->account->bank_name }}</strong>
                    </span>
                    <span class="total__message">
                        @if (app()->has('label_order_iban'))
                            {!! app('label_order_iban') !!}
                        @endif:
                        <strong>{{ $new_order->account->account }}</strong>
                    </span>
                @endif
                <span class="total__message">
                    @if (app()->has('label_order_address1'))
                        {!! app('label_order_address1') !!}
                    @endif:
                    <strong>{{ $new_order->billing->address1 }}</strong>
                    <strong>{{ $new_order->billing->address2 }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_country'))
                        {!! app('label_order_country') !!}
                    @endif:
                    <strong>{{ $new_order->billing->country }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_county'))
                        {!! app('label_order_county') !!}
                    @endif:
                    <strong>{{ $new_order->billing->county }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_city'))
                        {!! app('label_order_city') !!}
                    @endif:
                    <strong>{{ $new_order->billing->city }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_zipcode'))
                        {!! app('label_order_zipcode') !!}
                    @endif:
                    <strong>{{ $new_order->billing->zipcode }}</strong>
                </span>

                <h3>
                    @if (app()->has('label_order_delivery_check'))
                        {!! app('label_order_delivery_check') !!}
                    @endif
                </h3>

                <span class="total__message">
                    @if (app()->has('label_order_fullname'))
                        {!! app('label_order_fullname') !!}
                    @endif:
                    <strong>{{ $new_order->shipping->first_name }}</strong>
                    <strong>{{ $new_order->shipping->last_name }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_phone'))
                        {!! app('label_order_phone') !!}
                    @endif:
                    <strong>{{ $new_order->shipping->phone }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_email'))
                        {!! app('label_order_email') !!}
                    @endif:
                    <strong>{{ $new_order->shipping->email }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_address1'))
                        {!! app('label_order_address1') !!}
                    @endif:
                    <strong>{{ $new_order->shipping->address1 }}</strong>
                    <strong>{{ $new_order->shipping->address2 }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_county'))
                        {!! app('label_order_county') !!}
                    @endif:
                    <strong>{{ $new_order->shipping->country }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_county'))
                        {!! app('label_order_county') !!}
                    @endif:
                    <strong>{{ $new_order->shipping->county }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_city'))
                        {!! app('label_order_city') !!}
                    @endif:
                    <strong>{{ $new_order->shipping->city }}</strong>
                </span>
                <span class="total__message">
                    @if (app()->has('label_order_zipcode'))
                        {!! app('label_order_zipcode') !!}
                    @endif:
                    <strong>{{ $new_order->shipping->zipcode }}</strong>
                </span>
            </div>
            <!----------------------- Order sumary ----------------------------->
            <div class="total__info">
                @foreach ($new_order->orders as $cartItem)
                    <div class="total__product">
                        <span class="total__quantity">
                            {{ $cartItem->quantity }} x
                        </span>
                        @if ($cartItem->product->media->where('type', 'min')->first())
                            <img class="cart__list--img" title="{{ $cartItem->product->name }}"
                                src="/{{ $cartItem->product->media->where('type', 'min')->first()->path }}{{ $cartItem->product->media->where('type', 'min')->first()->name }}"
                                alt="{{ $cartItem->product->media->where('type', 'min')->first()->name }} {{ $cartItem->product->name }}">
                        @else
                            <img title="default image" class="cart__list--img"
                                src="/images/store/default/default70.webp" alt="something wrong">
                        @endif
                        <a href="{{ route('product', ['product' => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== '' ? $cartItem->product->seo_id : $cartItem->product->id]) }}"
                            target="_blank" class="total__name">{{ $cartItem->product->name }}</a>
                        <span class="total__price">
                            {{ number_format($cartItem->quantity * $cartItem->price, 2, $decimal, $mill) }}
                            @if (app()->has('global_currency_primary_symbol'))
                                {!! app('global_currency_primary_symbol') !!}
                            @endif
                        </span>
                    </div>
                @endforeach
                <div class="total__item">
                    <span>
                        @if (app()->has('label_order_payment_method'))
                            {!! app('label_order_payment_method') !!}
                        @endif
                    </span>
                    <span>{{ $new_order->payment->description }}</span>
                </div>
                @if ($new_order->promotion_value > 0)

                    <div class="total__item">
                        <span>
                            @if (app()->has('label_cart_promotion_tag'))
                                {!! app('label_cart_promotion_tag') !!}
                            @endif
                        </span>
                        <span>

                            -{{ number_format($new_order->promotion_value, 2, $decimal, $mill) }} @if (app()->has('global_currency_primary_symbol'))
                                {!! app('global_currency_primary_symbol') !!}
                            @endif
                        </span>
                    </div>
                @endif
                <div class="total__item">
                    <span>
                        @if (app()->has('label_cart_delivery_tag'))
                            {!! app('label_cart_delivery_tag') !!}
                        @endif
                    </span>
                    <span>
                        @if ($new_order->delivery_price == 0)
                            @if (app()->has('label_cart_delivery_free'))
                                {!! app('label_cart_delivery_free') !!}
                            @endif
                        @else
                            {{ number_format($new_order->delivery_price, 2, $decimal, $mill) }} @if (app()->has('global_currency_primary_symbol'))
                                {!! app('global_currency_primary_symbol') !!}
                            @endif
                        @endif
                    </span>
                </div>
                @if ($new_order->voucher && $new_order->voucher_value > 0)
                    <div class="total__item">
                        <span>
                            @if (app()->has('label_cart_voucher_tag'))
                                {!! app('label_cart_voucher_tag') !!}
                            @endif
                        </span>
                        <span>
                            -{{ number_format($new_order->voucher_value, 2, $decimal, $mill) }}
                            @if (app()->has('global_currency_primary_symbol'))
                                {!! app('global_currency_primary_symbol') !!}
                            @endif

                        </span>
                    </div>
                @endif
                <div class="total__item">
                    <span>
                        @if (app()->has('label_cart_total_tag'))
                            {!! app('label_cart_total_tag') !!}
                        @endif
                    </span>
                    <span id="final__amount">{{ number_format($new_order->final_amount, 2, $decimal, $mill) }}
                        @if (app()->has('global_currency_primary_symbol'))
                            {!! app('global_currency_primary_symbol') !!}
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                Livewire.emit('orderprocess');
            });
        </script>
        <div class="dlv" style="display: none">
            <span class="dlv_currency">
                @if (app()->has('global_currency_primary_name'))
                    {!! app('global_currency_primary_name') !!}
                @endif
            </span>
            <span class="dlv_value">{{ $new_order->final_amount }}</span>
            <span class="dlv_coupon">{{ optional($new_order->voucher)->code }}</span>
            <span class="dlv_transaction">{{ $new_order->order_number }}</span>
            {{-- <span class="dlv_tax">{{  }}</span> --}}
            <span class="dlv_shipping">
                @if ($new_order->delivery_price == 0)
                    Gratuit
                @else
                    {{ $new_order->delivery_price }} @if (app()->has('global_currency_primary_symbol'))
                        {!! app('global_currency_primary_symbol') !!}
                    @endif
                @endif
            </span>
            @foreach ($new_order->orders as $cartItem)
                <div class="dlv_item">
                    <span class="dlv_item-id">{{ $cartItem->product->id }}</span>
                    <span class="dlv_item-name">{{ $cartItem->product->name }}</span>
                    <span class="dlv_item-price">{{ $cartItem->price }}</span>
                    <span class="dlv_item-quantity">{{ $cartItem->quantity }}</span>
                </div>
            @endforeach
        </div>
        <script>
            async function purchase() {
                var dlv = document.querySelector('.dlv');
                if (!dlv) {
                    console.error('Elementul cu clasa .dlv nu a fost găsit.');
                    return;
                }

                var currency = dlv.querySelector('.dlv_currency').innerText.trim();
                var value = parseFloat(dlv.querySelector('.dlv_value').innerText.trim().replace(',', '.'));
                var coupon = dlv.querySelector('.dlv_coupon').innerText.trim() || undefined;
                var transaction_id = dlv.querySelector('.dlv_transaction').innerText.trim();
                var shipping = parseFloat(dlv.querySelector('.dlv_shipping').innerText.trim().replace(',', '.'));

                var items = [];
                var dlv_items = dlv.querySelectorAll('.dlv_item');
                dlv_items.forEach(dlv_item => {
                    var item_id = dlv_item.querySelector('.dlv_item-id').innerText.trim();
                    var item_name = dlv_item.querySelector('.dlv_item-name').innerText.trim();
                    var item_price = parseFloat(dlv_item.querySelector('.dlv_item-price').innerText.trim().replace(
                        ',', '.'));
                    var item_quantity = parseInt(dlv_item.querySelector('.dlv_item-quantity').innerText.trim(), 10);
                    items.push({
                        item_id,
                        item_name,
                        price: item_price,
                        quantity: item_quantity
                    });
                });

                return {
                    currency,
                    value,
                    coupon,
                    transaction_id,
                    shipping,
                    items
                };
            }

            async function extractShippingData() {
                const lookForm = document.querySelector('.look__form');
                if (!lookForm) {
                    console.error('Shipping information not found.');
                    return;
                }

                const firstName = lookForm.querySelectorAll('h3')[1].nextElementSibling.querySelectorAll('strong')[0]
                    .innerText
                    .trim();
                const lastName = lookForm.querySelectorAll('h3')[1].nextElementSibling.querySelectorAll('strong')[1]
                    .innerText
                    .trim();
                const phone = lookForm.querySelectorAll('h3')[1].nextElementSibling.nextElementSibling.querySelector(
                        'strong')
                    .innerText.trim();
                const email = lookForm.querySelectorAll('h3')[1].nextElementSibling.nextElementSibling.nextElementSibling
                    .querySelector('strong').innerText.trim();
                const street = lookForm.querySelectorAll('h3')[1].nextElementSibling.nextElementSibling.nextElementSibling
                    .nextElementSibling.querySelectorAll('strong')[0].innerText.trim();
                const region = lookForm.querySelectorAll('h3')[1].nextElementSibling.nextElementSibling.nextElementSibling
                    .nextElementSibling.nextElementSibling.querySelector('strong').innerText.trim();
                const city = lookForm.querySelectorAll('h3')[1].nextElementSibling.nextElementSibling.nextElementSibling
                    .nextElementSibling.nextElementSibling.nextElementSibling.querySelector('strong').innerText.trim();
                const postalCode = lookForm.querySelectorAll('h3')[1].nextElementSibling.nextElementSibling
                    .nextElementSibling
                    .nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.querySelector('strong')
                    .innerText
                    .trim();
                const country = 'RO';

                return {
                    email: email.toLowerCase().trim(),
                    phone_number: phone.trim(),
                    address: {
                        first_name: firstName.toLowerCase().trim(),
                        last_name: lastName.toLowerCase().trim(),
                        street: street.toLowerCase().trim(),
                        city: city.toLowerCase().trim(),
                        region: region.toLowerCase().trim(),
                        postal_code: postalCode.trim(),
                        country: country.toLowerCase().trim()
                    }
                };
            }

            async function pushPurchaseEvent() {
                const dlvData = await purchase();
                const userData = await extractShippingData();

                if (!dlvData || !userData) {
                    console.error("Missing data for the purchase event.");
                    return;
                }

                dataLayer.push({
                    ecommerce: null
                }); // Clear any previous ecommerce data
                dataLayer.push({
                    event: "purchase",
                    ecommerce: {
                        currency: dlvData.currency,
                        value: dlvData.value,
                        coupon: dlvData.coupon,
                        transaction_id: dlvData.transaction_id,
                        shipping: dlvData.shipping,
                        items: dlvData.items,
                        user_data: {
                            email: userData.email,
                            phone_number: userData.phone_number,
                            address: userData.address
                        }
                    }
                });
            }

            pushPurchaseEvent();
        </script>



    @endif
    <!------------------- Controls ------------------->
    <div class="checkout__header" style="flex-direction: row !important">
        @if ($step == 2)
            <button class="checkout__button" wire:click.prevent="previous()" aria-label="go to previous step">
                <svg>
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                @if (app()->has('label_order_previous_step'))
                    {!! app('label_order_previous_step') !!}
                @endif
            </button>
            @if ($modification)
                <button class="checkout__button checkout__button--confirm item__button--disabled">
                    @if (app()->has('label_order_confirm_step'))
                        {!! app('label_order_confirm_step') !!}
                    @endif
                    <svg>
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                </button>
            @else
                <button class="checkout__button checkout__button--confirm" wire:click.prevent="confirm()">
                    @if (app()->has('label_order_confirm_step'))
                        {!! app('label_order_confirm_step') !!}
                    @endif
                    <svg>
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                </button>
            @endif
        @elseif ($step == 1 && $this->hasCartWithItems)
            <button class="checkout__link checkout__button--confirm"
                @if ($individual && $individual_identic) onclick="validateIndividual(this)" @elseif ($individual && !$individual_identic) onclick="validateIndividualIdentic(this)" @elseif($juridic && $juridic_identic) onclick="validateJuridic(this)" @else onclick="validateJuridicIdentic(this)" @endif
                wire:click.prevent="next()" aria-label="go to next step" style="margin: 0 auto;">
                @if (app()->has('label_order_next_step'))
                    {!! app('label_order_next_step') !!}
                @endif
                <svg>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        @endif
    </div>
</div>
</section>
</div>
