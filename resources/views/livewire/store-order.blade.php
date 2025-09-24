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
                @if ($step === 1)
                    <div class="section__header">
                        <h2 class="section__title">
                            @if (app()->has('label_order_1_title'))
                                {!! app('label_order_1_title') !!}
                            @endif
                        </h2>
                    </div>
                    @php
                        $version = app()->has('countries_version') ? app('countries_version') : 1;
                    @endphp
                    <script>
                        document.addEventListener('alpine:init', () => {
                            const currentVersion = @js($version);
                            const savedVersion = localStorage.getItem('countiesDataVersion');
                            if (savedVersion !== currentVersion) {
                                localStorage.removeItem('countiesDataCache');
                                localStorage.setItem('countiesDataVersion', currentVersion);
                            }
                            Alpine.store('checkout', {
                                countiesDataCache: JSON.parse(localStorage.getItem('countiesDataCache') || '{}'),
                                isIdentic: @js($is_identic),
                                individual: @js($individual),
                                juridic: @js($juridic),
                                selectedCounty: @js($shipping_county),
                                selectedCountyBilling: @js($billing_county),
                                ShippingCountiesList: [],
                                BillingCountiesList: [],

                                country: @js($shipping_country),
                                billingcountry: @js($billing_country),

                                saveCache() {
                                    localStorage.setItem('countiesDataCache', JSON.stringify(this.countiesDataCache));
                                },

                                async fetchShippingCountiesForCountry(countryName) {
                                    if (!countryName) {
                                        this.ShippingCountiesList = [];
                                        return;
                                    }

                                    const country = countryName.replace(/\s+/g, '_');

                                    if (this.countiesDataCache[country]) {
                                        this.ShippingCountiesList = this.countiesDataCache[country];
                                        return;
                                    }


                                    try {
                                        const res = await fetch(`/js/countries/${country}.json`);
                                        if (!res.ok) throw new Error('Not found');
                                        const data = await res.json();



















                                        this.countiesDataCache[country] = data.counties || [];
                                        this.saveCache();

                                        this.ShippingCountiesList = this.countiesDataCache[country];
                                    } catch (e) {
                                        this.ShippingCountiesList = [];
                                    }
                                },

                                async fetchBillingCountiesForCountry(countryName) {
                                    if (!countryName) {
                                        this.BillingCountiesList = [];
                                        return;
                                    }

                                    const country = countryName.replace(/\s+/g, '_');







                                    if (this.countiesDataCache[country]) {
                                        this.BillingCountiesList = this.countiesDataCache[country];
                                        return;
                                    }


                                    try {
                                        const res = await fetch(`/js/countries/${country}.json`);
                                        if (!res.ok) throw new Error('Not found');
                                        const data = await res.json();

                                        this.countiesDataCache[country] = data.counties || [];
                                        this.saveCache();



                                        this.BillingCountiesList = this.countiesDataCache[country];
                                    } catch (e) {
                                        this.BillingCountiesList = [];
                                    }
                                },

                                syncToLivewire() {
                                    const hidden = document.getElementById('hidden_is_identic');
                                    hidden.value = this.isIdentic ? 1 : 0;
                                    hidden.dispatchEvent(new Event('input', {
                                        bubbles: true
                                    }));

                                    const individualInput = document.getElementById('hidden_individual');
                                    individualInput.value = this.individual ? 1 : 0;
                                    individualInput.dispatchEvent(new Event('input', {
                                        bubbles: true
                                    }));

                                    const juridicInput = document.getElementById('hidden_juridic');
                                    juridicInput.value = this.juridic ? 1 : 0;
                                    juridicInput.dispatchEvent(new Event('input', {
                                        bubbles: true
                                    }));
                                },

                                nextstep() {
                                    const SCounty = document.getElementById('ShippingCounty');
                                    this.shipping_county = SCounty.value;
                                    const hiddenCountyInput = document.getElementById('hiddenCountyInput');
                                    hiddenCountyInput.value = SCounty.value;
                                    hiddenCountyInput.dispatchEvent(new Event('input'));

                                    const SCity = document.getElementById('ShippingCity');
                                    this.shipping_city = SCity.value;
                                    const hiddenCityInput = document.getElementById('hiddenCityInput');
                                    hiddenCityInput.value = SCity.value;
                                    hiddenCityInput.dispatchEvent(new Event('input'));

                                    @this.set('shipping_county', SCounty.value);
                                    @this.set('shipping_city', SCity.value);

                                    if (this.isIdentic) {
                                        this.selectedCountyBilling = SCounty.value;
                                        this.billingcountry = this.country;

                                        const hiddenCountyInputB = document.getElementById('hiddenCountyBillingInput');
                                        if (hiddenCountyInputB) {
                                            hiddenCountyInputB.value = SCounty.value;
                                            hiddenCountyInputB.dispatchEvent(new Event('input'));
                                        }

                                        const hiddenCityInputB = document.getElementById('hiddenBillingCityInput');
                                        if (hiddenCityInputB) {
                                            hiddenCityInputB.value = SCity.value;
                                            hiddenCityInputB.dispatchEvent(new Event('input'));
                                        }

                                        @this.set('billing_county', SCounty.value);
                                        @this.set('billing_city', SCity.value);
                                        @this.set('billing_country', this.country);

                                    } else {
                                        const BCounty = document.getElementById('BillingCounty');
                                        this.billing_county = BCounty.value;
                                        const hiddenCountyInputB = document.getElementById('hiddenCountyBillingInput');
                                        hiddenCountyInputB.value = BCounty.value;
                                        hiddenCountyInputB.dispatchEvent(new Event('input'));

                                        const BCity = document.getElementById('BillingCity');
                                        this.billing_city = BCity.value;
                                        const hiddenCityInputB = document.getElementById('hiddenBillingCityInput');
                                        hiddenCityInputB.value = BCity.value;
                                        hiddenCityInputB.dispatchEvent(new Event('input'));

                                        @this.set('billing_county', BCounty.value);
                                        @this.set('billing_city', BCity.value);
                                    }
                                },



                                setIndividual() {
                                    this.individual = true;
                                    this.juridic = false;
                                    this.syncToLivewire();
                                },

                                setJuridic() {
                                    this.juridic = true;
                                    this.individual = false;
                                    this.syncToLivewire();
                                }
                            });
                        });
                    </script>

                    <style>
                        [x-cloak] {
                            display: none !important;
                        }
                    </style>

                    <!-------------- Form -------------->
                    <div class="checkout__container active">
                        <div class="checkout__form active">
                            <div class="checkout__top">
                                <span>1</span>
                                <h3>
                                    @if (app()->has('label_order_shipping_info'))
                                        {!! app('label_order_shipping_info') !!}
                                    @endif
                                </h3>
                            </div>
                            <!-----------------------   first name shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="ShippingFirstNameParent">
                                <input type="text" wire:model.defer="shipping_first"
                                    placeholder="@if (app()->has('label_order_firstname')) {!! app('label_order_firstname') !!} @endif"
                                    autocomplete="given-name" required id="ShippingFirstName">
                                <span></span>
                                <label for="ShippingFirstName">
                                    @if (app()->has('label_order_firstname'))
                                        {!! app('label_order_firstname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   last name shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="ShippingLastNameParent">
                                <input type="text" wire:model.defer="shipping_last"
                                    placeholder="@if (app()->has('label_order_lastname')) {!! app('label_order_lastname') !!} @endif"
                                    autocomplete="family-name" required id="ShippingLastName">
                                <span></span>
                                <label for="ShippingLastName">
                                    @if (app()->has('label_order_lastname'))
                                        {!! app('label_order_lastname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   phone shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required" id="ShippingPhoneParent">
                                <input type="tel" wire:model.defer="shipping_phone"
                                    placeholder="@if (app()->has('label_order_phone')) {!! app('label_order_phone') !!} @endif"
                                    autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required id="ShippingPhone">
                                <span></span>
                                <label for="ShippingPhone">
                                    @if (app()->has('label_order_phone'))
                                        {!! app('label_order_phone') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   email shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required" id="ShippingEmailParent">
                                <input type="email" wire:model.defer="shipping_email"
                                    placeholder="@if (app()->has('label_order_email')) {!! app('label_order_email') !!} @endif"
                                    autocomplete="email" required id="ShippingEmail">
                                <span></span>
                                <label for="ShippingEmail">
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
                                    @if (app()->has('label_order_shipping_address'))
                                        {!! app('label_order_shipping_address') !!}
                                    @endif
                                </h3>
                            </div>

                            {{-- COUNTRY SELECT --}}
                            @if (app()->has('global_order_display_country') && app('global_order_display_country') === 'true')
                                <select name="selectcountry" x-model="$store.checkout.country" x-init="$watch('$store.checkout.country', value => {
                                    // reset county + city in Alpine
                                    // Also clear city input field if present
                                    const cityInput = document.getElementById('ShippingCity');
                                    if (cityInput) {
                                        cityInput.value = '';
                                        cityInput.dispatchEvent(new Event('input'));
                                    }
                                    const countyInput = document.getElementById('ShippingCounty');
                                    if (countyInput) {
                                        countyInput.value = '';
                                        countyInput.dispatchEvent(new Event('input'));
                                    }


                                    // fetch new counties
                                    $store.checkout.fetchShippingCountiesForCountry(value)
                                })"
                                    wire:model.defer="shipping_country" class="select">
                                    @foreach ($countries as $c)
                                        <option value="{{ $c['name'] }}">{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            @endif

                            {{-- SHIPPING COUNTY --}}
                            <div class="checkout__item checkout__item--required searchable active"
                                id="ShippingCountyParent" x-data="{
                                    county: @entangle('shipping_county'),
                                    open: false,
                                    countyInput: '',

                                    filteredCounties() {
                                        if (!this.countyInput) return $store.checkout.ShippingCountiesList;
                                        return $store.checkout.ShippingCountiesList.filter(c =>
                                            c.name.toLowerCase().includes(this.countyInput.toLowerCase())
                                        );
                                    },

                                    selectCounty(name) {
                                        this.countyInput = name;
                                        const hidden = document.getElementById('hiddenCountyInput');
                                        hidden.value = name;
                                        hidden.dispatchEvent(new Event('input'));
                                        this.open = false;
                                        $store.checkout.selectedCounty = name;
                                    }
                                }" x-init="countyInput = county || '';
                                $store.checkout.fetchShippingCountiesForCountry($store.checkout.country)"
                                @click.away="open = false" wire:ignore>
                                <input type="text" x-model="countyInput" @focus="open = true"
                                    @input="const hidden = document.getElementById('hiddenCountyInput');
                                      if (hidden) {
                                        hidden.value = $event.target.value;
                                        hidden.dispatchEvent(new Event('input', { bubbles: true }));
                                      }"
                                    placeholder="County" class="input" autocomplete="off" aria-label="County selection"
                                    id="ShippingCounty">
                                <input required type="hidden" id="hiddenCountyInput"
                                    wire:model.defer="shipping_county" />
                                <span></span>
                                <label for="ShippingCounty">
                                    @if (app()->has('label_order_county'))
                                        {!! app('label_order_county') !!}
                                    @endif
                                </label>

                                <div x-show="open && filteredCounties().length > 0" class="content__searchable"
                                    style="display: none;">
                                    <div class="list__searchable">
                                        <template x-for="c in filteredCounties()" :key="c.name">
                                            <button type="button" class="item__searchable"
                                                @click="selectCounty(c.name)" x-text="c.name">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-----------------------   city shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required searchable active"
                                id="ShippingCityParent" x-data="{
                                    city: @entangle('shipping_city'),
                                    open: false,
                                    citiesList: [],
                                    cityInput: '',

                                    async fetchCitiesForCounty(countyName) {
                                        if (!countyName || !$store.checkout.country) {
                                            this.citiesList = [];
                                            return;
                                        }

                                        const storeCache = Alpine.store('checkout').countiesDataCache;
                                        const country = $store.checkout.country.replace(/\s+/g, '_');
                                        const countryCounties = storeCache[country] || [];
                                        const countyData = countryCounties.find(
                                            c => c.name.toLowerCase() === countyName.toLowerCase()
                                        );

                                        this.citiesList = countyData?.cities || [];
                                    },

                                    filteredCities() {
                                        if (!this.cityInput) return this.citiesList;
                                        return this.citiesList.filter(c =>
                                            c.name.toLowerCase().includes(this.cityInput.toLowerCase())
                                        );
                                    },

                                    selectCity(name) {
                                        this.cityInput = name;
                                        this.city = name;
                                        const hidden = document.getElementById('hiddenCityInput');
                                        hidden.value = name;
                                        hidden.dispatchEvent(new Event('input'));
                                        this.open = false;
                                    }
                                }" x-init="cityInput = city || '';
                                fetchCitiesForCounty(Alpine.store('checkout').selectedCounty);
                                $watch('$store.checkout.selectedCounty', value => fetchCitiesForCounty(value));"
                                @click.away="open = false">
                                <input type="text" x-model.defer="cityInput" @focus="open = true"
                                    @input="const hidden = document.getElementById('hiddenCityInput');
                                      if (hidden) {
                                        hidden.value = $event.target.value;
                                        hidden.dispatchEvent(new Event('input', { bubbles: true }));
                                      }"
                                    placeholder="@if (app()->has('label_order_city')) {!! app('label_order_city') !!} @endif"
                                    class="input" aria-label="City selection" id="ShippingCity">

                                <input type="hidden" id="hiddenCityInput" wire:model.defer="shipping_city" />

                                <span></span>
                                <label for="ShippingCity">
                                    @if (app()->has('label_order_city'))
                                        {!! app('label_order_city') !!}
                                    @endif
                                </label>

                                <div x-show="open & filteredCities().length > 0" class="content__searchable"
                                    style="display: none;">
                                    <div class="list__searchable">
                                        <template x-for="c in filteredCities()" :key="c.name">
                                            <button type="button" class="item__searchable"
                                                @click="selectCity(c.name)" x-text="c.name"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-----------------------   address1 shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="ShippingAddressParent">
                                <input type="text" wire:model.defer="shipping_address1"
                                    placeholder="@if (app()->has('label_order_address1')) {!! app('label_order_address1') !!} @endif"
                                    autocomplete="street-address" required id="ShippingAddress">
                                <span></span>
                                <label for="ShippingAddress">
                                    @if (app()->has('label_order_address1'))
                                        {!! app('label_order_address1') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   address2 shipping  ----------------------------->
                            @if (app()->has('global_order_display_address2') && app('global_order_display_address2') === 'true')
                                <div wire:ignore class="checkout__item" id="ShippingAddress2Parent">
                                    <input type="text" wire:model.defer="shipping_address2"
                                        placeholder="@if (app()->has('label_order_address2')) {!! app('label_order_address2') !!} @endif"
                                        autocomplete="address-level2" id="ShippingAddress2">
                                    <span></span>
                                    <label for="ShippingAddress2">
                                        @if (app()->has('label_order_address2'))
                                            {!! app('label_order_address2') !!}
                                        @endif
                                    </label>
                                </div>
                            @endif

                            <!-----------------------   zipcode shipping  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item" id="ShippingPostalParent">
                                <input type="text" wire:model.defer="shipping_zipcode"
                                    placeholder="@if (app()->has('label_order_zipcode')) {!! app('label_order_zipcode') !!} @endif"
                                    autocomplete="postal-code" id="ShippingPostal">
                                <span></span>
                                <label for="ShippingPostal">
                                    @if (app()->has('label_order_zipcode'))
                                        {!! app('label_order_zipcode') !!}
                                    @endif
                                </label>
                            </div>

                        </div>


                        <label class="checkout__checkbox" x-data>
                            <input name="isIdentic" type="checkbox" x-model="$store.checkout.isIdentic"
                                @change="$store.checkout.syncToLivewire()">

                            <input type="hidden" id="hidden_is_identic" wire:model.defer="is_identic" />

                            <span>
                                @if (app()->has('label_order_identic'))
                                    {!! app('label_order_identic') !!}
                                @endif
                            </span>
                        </label>

                        <div x-data x-show="!$store.checkout.isIdentic" x-cloak
                            class="checkout__header checkout__checkbox">
                            <div x-data class="checkout__navigation">
                                <button type="button" class="checkout__button"
                                    :class="{ 'active': $store.checkout.individual }"
                                    @click="$store.checkout.setIndividual()">
                                    @if (app()->has('label_order_individual_tag'))
                                        {!! app('label_order_individual_tag') !!}
                                    @endif
                                </button>
                                <button type="button" class="checkout__button"
                                    :class="{ 'active': $store.checkout.juridic }"
                                    @click="$store.checkout.setJuridic()">
                                    @if (app()->has('label_order_juridic_tag'))
                                        {!! app('label_order_juridic_tag') !!}
                                    @endif
                                </button>
                                <input type="hidden" id="hidden_individual" wire:model.defer="individual" />
                                <input type="hidden" id="hidden_juridic" wire:model.defer="juridic" />
                            </div>
                        </div>


                        <div x-data :class="{ 'checkout__form active': !$store.checkout.isIdentic }"
                            x-show="!$store.checkout.isIdentic" x-cloak>

                            <div class="checkout__top">
                                <span>3</span>
                                <h3>
                                    @if (app()->has('label_order_billing_info'))
                                        {!! app('label_order_billing_info') !!}
                                    @endif
                                </h3>
                            </div>

                            <!-----------------------   first billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="BillingFirstNameParent">
                                <input type="text" wire:model.defer="billing_first"
                                    placeholder="@if (app()->has('label_order_firstname')) {!! app('label_order_firstname') !!} @endif"
                                    autocomplete="given-name" required id="BillingFirstName">
                                <span></span>
                                <label for="BillingFirstName">
                                    @if (app()->has('label_order_firstname'))
                                        {!! app('label_order_firstname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   last billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="BillingLastNameParent">
                                <input type="text" wire:model.defer="billing_last"
                                    placeholder="@if (app()->has('label_order_lastname')) {!! app('label_order_lastname') !!} @endif"
                                    autocomplete="family-name" required id="BillingLastName">
                                <span></span>
                                <label for="BillingLastName">
                                    @if (app()->has('label_order_lastname'))
                                        {!! app('label_order_lastname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   phone billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required" id="BillingPhoneParent">
                                <input type="tel" wire:model.defer="billing_phone"
                                    placeholder="@if (app()->has('label_order_phone')) {!! app('label_order_phone') !!} @endif"
                                    autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required
                                    id="BillingPhone">
                                <span></span>
                                <label for="BillingPhone">
                                    @if (app()->has('label_order_phone'))
                                        {!! app('label_order_phone') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   email billing   ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required" id="BillingEmailParent">
                                <input type="email" wire:model.defer="billing_email"
                                    placeholder="@if (app()->has('label_order_email')) {!! app('label_order_email') !!} @endif"
                                    autocomplete="email" required id="BillingEmail">
                                <span></span>
                                <label for="BillingEmail">
                                    @if (app()->has('label_order_email'))
                                        {!! app('label_order_email') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   company name billing  ----------------------------->
                            <div x-show="$store.checkout.juridic" x-cloak wire:ignore
                                class="checkout__item checkout__item--required" id="companyNameParent">
                                <input type="text" wire:model.defer="billing_company_name"
                                    placeholder="@if (app()->has('label_order_company_name')) {!! app('label_order_company_name') !!} @endif"
                                    autocomplete="organization" required id="companyName">
                                <span></span>
                                <label for="companyName">
                                    @if (app()->has('label_order_company_name'))
                                        {!! app('label_order_company_name') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   registration_code billing  ----------------------------->
                            <div x-show="$store.checkout.juridic" x-cloak wire:ignore
                                class="checkout__item checkout__item--required" id="registerCodeParent">
                                <input type="text" wire:model.defer="billing_registration_code"
                                    placeholder="@if (app()->has('label_order_register_code')) {!! app('label_order_register_code') !!} @endif"
                                    autocomplete="disabled" required id="registerCode">
                                <span></span>
                                <label for="registerCode">
                                    @if (app()->has('label_order_register_code'))
                                        {!! app('label_order_register_code') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   registration_number billing  ----------------------------->
                            <div x-show="$store.checkout.juridic" x-cloak wire:ignore
                                class="checkout__item checkout__item--required" id="registerNumberParent">
                                <input type="text" wire:model.defer="billing_registration_number"
                                    placeholder="@if (app()->has('label_order_register_number')) {!! app('label_order_register_number') !!} @endif"
                                    autocomplete="organization-number" required id="registerNumber">
                                <span></span>
                                <label for="registerNumber">
                                    @if (app()->has('label_order_register_number'))
                                        {!! app('label_order_register_number') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   bank billing  ----------------------------->
                            <div x-show="$store.checkout.juridic" x-cloak wire:ignore class="checkout__item"
                                id="bankNameParent">
                                <input type="text" wire:model.defer="billing_bank"
                                    placeholder="@if (app()->has('label_order_bankname')) {!! app('label_order_bankname') !!} @endif"
                                    autocomplete="disabled" id="bankName">
                                <span></span>
                                <label for="bankName">
                                    @if (app()->has('label_order_bankname'))
                                        {!! app('label_order_bankname') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   account billing  ----------------------------->
                            <div x-show="$store.checkout.juridic" x-cloak wire:ignore class="checkout__item"
                                id="IBANParent">
                                <input type="text" wire:model.defer="billing_account"
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

                        <div x-data :class="{ 'checkout__form active': !$store.checkout.isIdentic }"
                            x-show="!$store.checkout.isIdentic" x-cloak>

                            <div class="checkout__top">
                                <span>4</span>
                                <h3>
                                    @if (app()->has('label_order_billing_address'))
                                        {!! app('label_order_billing_address') !!}
                                    @endif
                                </h3>
                            </div>

                            <!-----------------------   country billing  ----------------------------->
                            @if (app()->has('global_order_display_country') && app('global_order_display_country') === 'true')
                                <select name="selectcountry" x-model="$store.checkout.billingcountry"
                                    x-init="$watch('$store.checkout.billingcountry', value => {
                                        // reset county + city in Alpine
                                        // Also clear city input field if present
                                        const cityInput = document.getElementById('BillingCity');
                                        if (cityInput) {
                                            cityInput.value = '';
                                            cityInput.dispatchEvent(new Event('input'));
                                        }
                                        const countyInput = document.getElementById('BillingCounty');
                                        if (countyInput) {
                                            countyInput.value = '';
                                            countyInput.dispatchEvent(new Event('input'));
                                        }


                                        // fetch new counties
                                        $store.checkout.fetchBillingCountiesForCountry(value)
                                    })" wire:model.defer="billing_country" class="select">
                                    @foreach ($countries as $c)
                                        <option value="{{ $c['name'] }}">{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            @endif

                            <!-----------------------   county billing  ----------------------------->
                            <div class="checkout__item checkout__item--required searchable active"
                                id="BillingCountyParent" x-data="{
                                    county: @entangle('billing_county'),
                                    open: false,
                                    billingcountyInput: '',



                                    filteredBillingCounties() {
                                        if (!this.billingcountyInput) return $store.checkout.BillingCountiesList;
                                        return $store.checkout.BillingCountiesList.filter(c =>
                                            c.name.toLowerCase().includes(this.billingcountyInput.toLowerCase())
                                        );
                                    },

                                    selectBillingCounty(name) {
                                        this.billingcountyInput = name;
                                        const hidden = document.getElementById('hiddenCountyBillingInput');
                                        hidden.value = name;
                                        hidden.dispatchEvent(new Event('input')); // notify Livewire
                                        this.open = false;
                                        $store.checkout.selectedCountyBilling = name;
                                    }
                                }" x-init="billingcountyInput = county || '';
                                $store.checkout.fetchBillingCountiesForCountry($store.checkout.billingcountry);"
                                @click.away="open = false" wire:ignore>
                                <input type="text" id="BillingCounty" x-model="billingcountyInput"
                                    @focus="open = true"
                                    @input="const hidden = document.getElementById('hiddenCountyBillingInput');
                                      if (hidden) {
                                        hidden.value = $event.target.value;
                                        hidden.dispatchEvent(new Event('input', { bubbles: true }));
                                      }"
                                    placeholder="County" class="input" autocomplete="off"
                                    aria-label="County selection" id="BillingCounty">
                                <input required type="hidden" id="hiddenCountyBillingInput"
                                    wire:model.defer="billing_county" />

                                <span></span>
                                <label for="BillingCounty">
                                    @if (app()->has('label_order_county'))
                                        {!! app('label_order_county') !!}
                                    @endif
                                </label>

                                <div x-show="open & filteredBillingCounties().length > 1" class="content__searchable"
                                    style="display: none;">
                                    <div class="list__searchable">
                                        <template x-for="c in filteredBillingCounties()" :key="c.name">
                                            <button type="button" class="item__searchable"
                                                @click="selectBillingCounty(c.name)" x-text="c.name"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-----------------------   city billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required searchable active"
                                id="BillingCityParent" x-data="{
                                    city: @entangle('billing_city'),
                                    open: false,
                                    citiesList: [],
                                    cityInput: '',

                                    async fetchBillingCitiesForCounty(countyName) {
                                        if (!countyName || !$store.checkout.billingcountry) {
                                            this.citiesList = [];
                                            return;
                                        }

                                        const storeCache = Alpine.store('checkout').countiesDataCache;
                                        const country = $store.checkout.billingcountry.replace(/\s+/g, '_');
                                        const countryCounties = storeCache[country] || [];
                                        const countyData = countryCounties.find(
                                            c => c.name.toLowerCase() === countyName.toLowerCase()
                                        );

                                        this.citiesList = countyData?.cities || [];
                                    },

                                    filteredBillingCities() {
                                        if (!this.cityInput) return this.citiesList;
                                        return this.citiesList.filter(c =>
                                            c.name.toLowerCase().includes(this.cityInput.toLowerCase())
                                        );
                                    },

                                    selectBillingCity(name) {
                                        this.cityInput = name;
                                        this.city = name;
                                        const hidden = document.getElementById('hiddenBillingCityInput');
                                        hidden.value = name;
                                        hidden.dispatchEvent(new Event('input'));
                                        this.open = false;
                                    }
                                }" x-init="cityInput = city || '';
                                fetchBillingCitiesForCounty(Alpine.store('checkout').selectedCountyBilling);
                                $watch('$store.checkout.selectedCountyBilling', value => fetchBillingCitiesForCounty(value));"
                                @click.away="open = false">
                                <input type="text" id="BillingCity" x-model="cityInput" @focus="open = true"
                                    @input="const hidden = document.getElementById('hiddenBillingCityInput');
                                      if (hidden) {
                                        hidden.value = $event.target.value;
                                        hidden.dispatchEvent(new Event('input', { bubbles: true }));
                                      }"
                                    placeholder="@if (app()->has('label_order_city')) {!! app('label_order_city') !!} @endif"
                                    class="input" aria-label="City selection" id="BillingCity">

                                <input type="hidden" id="hiddenBillingCityInput" wire:model.defer="billing_city" />

                                <span></span>
                                <label for="BillingCity">
                                    @if (app()->has('label_order_city'))
                                        {!! app('label_order_city') !!}
                                    @endif
                                </label>

                                <div x-show="open & filteredBillingCities().length > 0" class="content__searchable"
                                    style="display: none;">
                                    <div class="list__searchable">
                                        <template x-for="c in filteredBillingCities()" :key="c.name">
                                            <button type="button" class="item__searchable"
                                                @click="selectBillingCity(c.name)" x-text="c.name"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-----------------------   address1 billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item--required"
                                id="BillingAddressParent">
                                <input type="text" wire:model.defer="billing_address1"
                                    placeholder="@if (app()->has('label_order_address1')) {!! app('label_order_address1') !!} @endif"
                                    autocomplete="street-address" required id="BillingAddress">
                                <span></span>
                                <label for="BillingAddress">
                                    @if (app()->has('label_order_address1'))
                                        {!! app('label_order_address1') !!}
                                    @endif
                                </label>
                            </div>

                            <!-----------------------   address2 billing  ----------------------------->
                            @if (app()->has('global_order_display_address2') && app('global_order_display_address2') === 'true')
                                <div wire:ignore class="checkout__item" id="BillingAddress2Parent">
                                    <input type="text" wire:model.defer="billing_address2"
                                        placeholder="@if (app()->has('label_order_address2')) {!! app('label_order_address2') !!} @endif"
                                        autocomplete="address-level2" id="BillingAddress2">
                                    <span></span>
                                    <label for="BillingAddress2">
                                        @if (app()->has('label_order_address2'))
                                            {!! app('label_order_address2') !!}
                                        @endif
                                    </label>
                                </div>
                            @endif

                            <!-----------------------   zipcode billing  ----------------------------->
                            <div wire:ignore class="checkout__item checkout__item" id="BillingPostalParent">
                                <input type="text" wire:model.defer="billing_zipcode"
                                    placeholder="@if (app()->has('label_order_zipcode')) {!! app('label_order_zipcode') !!} @endif"
                                    autocomplete="postal-code" id="BillingPostal">
                                <span></span>
                                <label for="BillingPostal">
                                    @if (app()->has('label_order_zipcode'))
                                        {!! app('label_order_zipcode') !!}
                                    @endif
                                </label>
                            </div>

                        </div>
                    </div>

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
                            <div x-data x-show="!$store.checkout.juridic" x-cloak class="payment">
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

                    <script src="/script/store/order.js"></script>

                @endif
                <!------------------------------------------------------>
                @if ($step === 2)
                    <?php $disables = []; ?>
                    <div class="section__header">
                        <h2 class="section__title">
                            @if (app()->has('label_order_details_check'))
                                {!! app('label_order_details_check') !!}
                            @endif
                        </h2>
                    </div>
                    <div class="total__container">
                        <!-------------- information -------------->
                        <div class="look__form">

                            <h3>
                                @if (app()->has('label_order_delivery_check'))
                                    {!! app('label_order_delivery_check') !!}
                                @endif
                            </h3>

                            <span class="total__message">
                                @if (app()->has('label_order_fullname'))
                                    {!! app('label_order_fullname') !!}
                                @endif:
                                <strong>{{ $shipping_first }}</strong>
                                <strong>{{ $shipping_last }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_phone'))
                                    {!! app('label_order_phone') !!}
                                @endif:
                                <strong>{{ $shipping_phone }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_email'))
                                    {!! app('label_order_email') !!}
                                @endif:
                                <strong>{{ $shipping_email }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_address1'))
                                    {!! app('label_order_address1') !!}
                                @endif:
                                <strong>{{ $shipping_address1 }}</strong>
                                <strong>{{ $shipping_address2 }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_country'))
                                    {!! app('label_order_country') !!}
                                @endif:
                                <strong>{{ $shipping_country }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_county'))
                                    {!! app('label_order_county') !!}
                                @endif:
                                <strong>{{ $shipping_county }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_city'))
                                    {!! app('label_order_city') !!}
                                @endif:
                                <strong>{{ $shipping_city }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_zipcode'))
                                    {!! app('label_order_zipcode') !!}
                                @endif:
                                <strong>{{ $shipping_zipcode }}</strong>
                            </span>
                            <h3>
                                @if (app()->has('label_order_billing_check'))
                                    {!! app('label_order_billing_check') !!}
                                @endif
                            </h3>
                            <span class="total__message">
                                @if (app()->has('label_order_fullname'))
                                    {!! app('label_order_fullname') !!}
                                @endif:
                                <strong>{{ $billing_first }}</strong>
                                <strong>{{ $billing_last }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_phone'))
                                    {!! app('label_order_phone') !!}
                                @endif:
                                <strong>{{ $billing_phone }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_email'))
                                    {!! app('label_order_email') !!}
                                @endif:
                                <strong>{{ $billing_email }}</strong>
                            </span>
                            @if ($juridic)

                                <span class="total__message">
                                    @if (app()->has('label_order_company_name'))
                                        {!! app('label_order_company_name') !!}
                                    @endif:
                                    <strong>{{ $billing_company_name }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_register_code'))
                                        {!! app('label_order_register_code') !!}
                                    @endif:
                                    <strong>{{ $billing_registration_code }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_register_number'))
                                        {!! app('label_order_register_number') !!}
                                    @endif:
                                    <strong>{{ $billing_registration_number }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_bankname'))
                                        {!! app('label_order_bankname') !!}
                                    @endif:
                                    <strong>{{ $billing_bank }}</strong>
                                </span>
                                <span class="total__message">
                                    @if (app()->has('label_order_iban'))
                                        {!! app('label_order_iban') !!}
                                    @endif:
                                    <strong>{{ $billing_account }}</strong>
                                </span>
                            @endif

                            <span class="total__message">
                                @if (app()->has('label_order_address1'))
                                    {!! app('label_order_address1') !!}
                                @endif:
                                <strong>{{ $billing_address1 }}</strong>
                                <strong>{{ $billing_address2 }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_country'))
                                    {!! app('label_order_country') !!}
                                @endif:
                                <strong>{{ $billing_country }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_county'))
                                    {!! app('label_order_county') !!}
                                @endif:
                                <strong>{{ $billing_county }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_city'))
                                    {!! app('label_order_city') !!}
                                @endif:
                                <strong>{{ $billing_city }}</strong>
                            </span>
                            <span class="total__message">
                                @if (app()->has('label_order_zipcode'))
                                    {!! app('label_order_zipcode') !!}
                                @endif:
                                <strong>{{ $billing_zipcode }}</strong>
                            </span>

                        </div>


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
                                    <span>{{ number_format($cart->final_amount, 2, $decimal, $mill) }}
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
                            var dlv = document.querySelector('.dlv');
                            if (!dlv) {
                                console.error('Elementul cu clasa .dlv nu a fost găsit.');
                                return;
                            }
                            var currency = dlv.querySelector('.dlv_currency').innerText.trim();
                            var value = parseFloat(dlv.querySelector('.dlv_value').innerText.trim().replace(',', '.'));
                            var coupon = dlv.querySelector('.dlv_coupon').innerText.trim() || undefined;
                            var payment = dlv.querySelector('.dlv_payment').innerText.trim();
                            var items = [];
                            var dlv_items = dlv.querySelectorAll('.dlv_item');
                            dlv_items.forEach(dlv_item => {
                                var item_id = dlv_item.querySelector('.dlv_item-id').innerText.trim();
                                var item_name = dlv_item.querySelector('.dlv_item-name').innerText.trim();
                                var item_price = parseFloat(dlv_item.querySelector('.dlv_item-price').innerText.trim().replace(',',
                                    '.'));
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
                                payment: payment,
                                items: items
                            };
                            return dlvData;
                        };
                        var dlvData = add_payment_info();

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
                                var item_price = parseFloat(dlv_item.querySelector('.dlv_item-price').innerText.trim().replace(',',
                                    '.'));
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
                        var dlvData = add_shipping_info();
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

                @if ($step === 3)
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

                                        -{{ number_format($new_order->promotion_value, 2, $decimal, $mill) }}
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
                                    @if ($new_order->delivery_price == 0)
                                        @if (app()->has('label_cart_delivery_free'))
                                            {!! app('label_cart_delivery_free') !!}
                                        @endif
                                    @else
                                        {{ number_format($new_order->delivery_price, 2, $decimal, $mill) }}
                                        @if (app()->has('global_currency_primary_symbol'))
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
                                <span
                                    id="final__amount">{{ number_format($new_order->final_amount, 2, $decimal, $mill) }}
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
                        <button class="checkout__button" wire:click.prevent="previous()"
                            aria-label="go to previous step">
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
                            <button class="checkout__button checkout__button--confirm" wire:click.prevent="confirm"
                                wire:loading.attr="disabled" wire:loading.class="checkout__button--disabled"
                                wire:target="confirm">
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
                        <button class="checkout__button checkout__button--confirm" x-data
                            @click.prevent=" $store.checkout.syncToLivewire();$store.checkout.nextstep();
                  if ($store.checkout.isIdentic) {
                      validateShipping();
                  } else if ($store.checkout.individual) {
                      validateAllData();
                  } else {
                      validateJuridic();
                  }"
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
    @endif
</div>
