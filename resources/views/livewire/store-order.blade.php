<div>
    <x-store-alert />
    @if ($back)
        <!------------------------------------------------------>
        <!-------------------- Error Message ------------------->
        <section>
            <div class="checkout container">
                <div class="section__header container">
                    <h2 class="section__title">Ups, ceva nu a mers bine!</h2>
                    <a class="section__text" href="/home">
                        Va rugam sa va intoarceti la pagina initiala
                    </a>
                </div>
            </div>
        </section>
        <!------------------ End Error Message ----------------->
        <!------------------------------------------------------>
    @else
        <!------------------------------------------------------>
        <!----------------------- Checkout --------------------->
        <section>
            <div class="section__header container">
                <h2 class="section__title">Plasare comanda</h2>
                <p class="section__text">
                    Controleaza datele
                </p>
            </div>
        </section>
        <!------------------------------------------------------>
        <section>
            <div class="checkout container">
                <!------------------------------------------------------>
                <!-------------------- Step Numbers -------------------->
                <div class="step__container">
                    <div class="step active">1</div>
                    <span class="step__line @if ($step == 1) half @else full @endif"></span>
                    <div class="step @if ($step > 1 || $step == 3) active @endif">2</div>
                    <span class="step__line @if ($step == 3) full @endif"></span>
                    <div class="step @if ($step == 3) active @endif">3</div>
                </div>
                <!------------------ End Step Numbers ------------------>
                <!------------------------------------------------------>

                <!-------------------- Step First ---------------------->
                @if ($step == 1)
                    <div class="section__header">
                        <h2 class="section__title">Detalii de livrare</h2>
                    </div>
                    <div class="checkout__header">
                        <div class="checkout__navigation">
                            <button class="checkout__button @if ($individual) active @endif"
                                wire:click="showindividual()">Persoana fizica</button>
                            <button class="checkout__button @if ($juridic) active @endif"
                                wire:click="showjuridic()"> Persoana Juridica</button>
                        </div>
                        <div class="checkout__navigation">
                            <button class="checkout__button" wire:click="resetForm">
                                <svg>
                                    <polyline points="1 4 1 10 7 10"></polyline>
                                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                                </svg>
                            </button>
                            <button class="checkout__button" wire:click.prevent="next()">
                                <svg>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="checkout__container @if ($individual) active @endif">
                        <!---------------------------------------------------->
                        <!-------------- Checkout List of Forms -------------->
                        <div class="checkout__form">
                            <!---------------------------------------------------->
                            <!------------- Checkout Header Name --------------->
                            <div class="checkout__top">
                                <span>1</span>
                                <h3>
                                    Contact de facturare &#9998;
                                </h3>
                            </div>
                            <!----------- End Checkout Header Name ------------->
                            <!---------------------------------------------------->
                            <!------------- Checkout List of Items --------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_first" placeholder="Nume">
                                <span>
                                    @error('individual_billing_first')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_last" placeholder="Prenume">
                                <span>
                                    @error('individual_billing_last')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="tel" wire:model="individual_billing_phone" placeholder="Telefon">
                                <span>
                                    @error('individual_billing_phone')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="email" wire:model="individual_billing_email" placeholder="Email">
                                <span>
                                    @error('individual_billing_email')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!----------- End Checkout List of Items ------------->
                            <!---------------------------------------------------->
                        </div>
                        <!---------------------------------------------------->
                        <div class="checkout__form">
                            <!---------------------------------------------------->
                            <!------------- Checkout Header Name --------------->
                            <div class="checkout__top">
                                <span>2</span>
                                <h3>
                                    Adresa de facturare &#9998;
                                </h3>
                            </div>
                            <!----------- End Checkout Header Name ------------->
                            <!---------------------------------------------------->
                            <!------------- Checkout List of Items --------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_address1" placeholder="Address 1*">
                                <span>
                                    @error('individual_billing_address1')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_address2"
                                    placeholder="Address 2 (optional)">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_country" placeholder="Tara">
                                <span>
                                    @error('individual_billing_country')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_county" placeholder="Judet">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_city" placeholder="Oras">
                                <span>
                                    @error('individual_billing_city')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" placeholder="Post Code" wire:model="individual_billing_zipcode"
                                    placeholder="Cod Postal">
                                <span>
                                    @error('individual_billing_zipcode')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!----------- End Checkout List of Items ------------->
                            <!---------------------------------------------------->
                        </div>
                        <!---------------------------------------------------->
                        <!---------------- Checkout Checkbox ----------------->
                        <label class="checkout__checkbox">
                            <input type="checkbox" wire:model="individual_identic">
                            <span>Adresa de livrare este identică cu adresa de facturare</span>
                        </label>
                        <!-------------- End Checkout Checkbox --------------->
                        <!---------------------------------------------------->
                        @if (!$individual_identic)
                            <div class="checkout__form">
                                <!---------------------------------------------------->
                                <!------------- Checkout Header Name --------------->
                                <div class="checkout__top">
                                    <span>3</span>
                                    <h3>
                                        Contact de livrare &#9998;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_first" placeholder="Nume">
                                    <span>
                                        @error('individual_shipping_first')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_last" placeholder="Prenume">
                                    <span>
                                        @error('individual_shipping_last')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="tel" wire:model="individual_shipping_phone" placeholder="Telefon">
                                    <span>
                                        @error('individual_shipping_phone')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="email" wire:model="individual_shipping_email" placeholder="Email">
                                    <span>
                                        @error('individual_shipping_email')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!----------- End Checkout List of Items ------------->
                                <!---------------------------------------------------->
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__form">
                                <!---------------------------------------------------->
                                <!------------- Checkout Header Name --------------->
                                <div class="checkout__top">
                                    <span>4</span>
                                    <h3>
                                        Adresa de livrare &#9998;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_address1"
                                        placeholder="Address 1*">
                                    @error('individual_shipping_address1')
                                        {{ $message }}
                                    @enderror
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_address2"
                                        placeholder="Address 2 (optional)">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_country"
                                        placeholder="Tara">
                                    <span>
                                        @error('individual_shipping_country')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_county"
                                        placeholder="Judet">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_city" placeholder="Oras">
                                    <span>
                                        @error('individual_shipping_city')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_zipcode"
                                        placeholder="Cod Postal">
                                    <span>
                                        @error('individual_shipping_zipcode')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!----------- End Checkout List of Items ------------->
                                <!---------------------------------------------------->
                            </div>
                        @endif
                        <!------------ End Checkout List of Forms ------------>
                        <!---------------------------------------------------->
                    </div>
                    <div class="checkout__container @if ($juridic) active @endif">
                        <!---------------------------------------------------->
                        <!-------------- Checkout List of Forms -------------->
                        <div class="checkout__form">
                            <!---------------------------------------------------->
                            <!------------- Checkout Header Name --------------->
                            <div class="checkout__top">
                                <span>1</span>
                                <h3>
                                    Informații Persoana Juridica &#9998;
                                </h3>
                            </div>
                            <!----------- End Checkout Header Name ------------->
                            <!---------------------------------------------------->
                            <!------------- Checkout List of Items --------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_first" placeholder="Nume">
                                <span>
                                    @error('juridic_billing_first')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_last" placeholder="Prenume">
                                <span>
                                    @error('juridic_billing_last')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="tel" wire:model="juridic_billing_phone" placeholder="Telefon">
                                <span>
                                    @error('juridic_billing_phone')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="email" wire:model="juridic_billing_email" placeholder="Email">
                                <span>
                                    @error('juridic_billing_email')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_company_name"
                                    placeholder="Denumirea Companiei">
                                <span>
                                    @error('juridic_billing_company_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_registration_code"
                                    placeholder="Cod de înregistrare">
                                <span>
                                    @error('juridic_billing_registration_code')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_registration_number"
                                    placeholder="Număr de înregistrare.">
                                <span>
                                    @error('juridic_billing_registration_number')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_bank"
                                    placeholder="Denumirea Bancii">
                                <span>
                                    @error('juridic_billing_bank')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_account" placeholder="Cont IBAN">
                                <span>
                                    @error('juridic_billing_account')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!----------- End Checkout List of Items ------------->
                            <!---------------------------------------------------->
                        </div>
                        <!---------------------------------------------------->
                        <div class="checkout__form">
                            <!---------------------------------------------------->
                            <!------------- Checkout Header Name --------------->
                            <div class="checkout__top">
                                <span>2</span>
                                <h3>
                                    Adresa de facturare &#9998;
                                </h3>
                            </div>
                            <!----------- End Checkout Header Name ------------->
                            <!---------------------------------------------------->
                            <!------------- Checkout List of Items --------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_address1" placeholder="Address 1*">
                                <span>
                                    @error('juridic_billing_address1')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_address2"
                                    placeholder="Address 2 (optional)">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_country" placeholder="Tara">
                                <span>
                                    @error('juridic_billing_country')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_county" placeholder="Judet">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_city" placeholder="Oras">
                                <span>
                                    @error('juridic_billing_city')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_zipcode" placeholder="Cod Postal">
                                <span>
                                    @error('juridic_billing_zipcode')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!----------- End Checkout List of Items ------------->
                            <!---------------------------------------------------->
                        </div>
                        <!---------------------------------------------------->
                        <!---------------- Checkout Checkbox ----------------->
                        <label class="checkout__checkbox">
                            <input type="checkbox" wire:model="juridic_identic">
                            <span>Adresa de livrare este identică cu adresa de facturare</span>
                        </label>
                        <!-------------- End Checkout Checkbox --------------->
                        <!---------------------------------------------------->
                        @if (!$juridic_identic)
                            <div class="checkout__form">
                                <!---------------------------------------------------->
                                <!------------- Checkout Header Name --------------->
                                <div class="checkout__top">
                                    <span>3</span>
                                    <h3>
                                        Contact de livrare &#9998;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="juridic_shipping_first" placeholder="Nume">
                                    <span>
                                        @error('juridic_shipping_first')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="juridic_shipping_last" placeholder="Prenume">
                                    <span>
                                        @error('juridic_shipping_last')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="tel" wire:model="juridic_shipping_phone" placeholder="Telefon">
                                    <span>
                                        @error('juridic_shipping_phone')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="email" wire:model="juridic_shipping_email" placeholder="Email">
                                    <span>
                                        @error('juridic_shipping_email')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <!----------- End Checkout List of Items ------------->
                                <!---------------------------------------------------->
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__form">
                                <!---------------------------------------------------->
                                <!------------- Checkout Header Name --------------->
                                <div class="checkout__top">
                                    <span>4</span>
                                    <h3>
                                        Adresa de livrare &#9998;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <div class="checkout__item">
                                    <input type="text" placeholder="Address 1*">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" placeholder="Address 2 (optional)">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" placeholder="Tara">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" placeholder="Judet">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" placeholder="Oras">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" placeholder="Cod Postal">
                                </div>
                                <!----------- End Checkout List of Items ------------->
                                <!---------------------------------------------------->
                            </div>
                        @endif
                        <!------------ End Checkout List of Forms ------------>
                        <!---------------------------------------------------->
                    </div>
                    <div class="section__header">
                        <h2 class="section__title">Metoda de plata</h2>
                    </div>
                    @foreach ($payments as $payment)
                        @if ($payment->name == 'Plata cash la livrare' && $cart->final_amount <= '1000')
                            @if ($payment->active)
                                <div class="payment">
                                    <label class="payment__wrapper" for="rtc" wire:click="togglepayment('rtc')">
                                        <input class="payment__checkbox" type="checkbox" wire:model.defer="rtc"
                                            id="rtc">
                                        <span>Plata cash la livrare</span>
                                    </label>
                                    <div class="payment__text @if ($rtc) active @endif">
                                        <h4>Veți plăti când comanda va fi livrată.</h4>
                                        <span>Limita maxima este de 1000 RON</span>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @if ($payment->name == 'Ordin de plata')
                            @if ($payment->active)
                                @if ($juridic)
                                    <div class="payment">
                                        <label class="payment__wrapper" for="invoice"
                                            wire:click="togglepayment('invoice')">
                                            <input class="payment__checkbox" type="checkbox"
                                                wire:model.defer="invoice" id="invoice">
                                            <span>Ordin de plata</span>
                                        </label>
                                        <div class="payment__text @if ($invoice) active @endif"">
                                            <h4>
                                                Metoda de plată utilizată de entitățile legale. După plasarea comenzii,
                                                veți primi prin e-mail factura proformă cu toate detaliile de plată.
                                            </h4>
                                        </div>

                                    </div>
                                @endif
                            @endif
                        @endif
                    @endforeach
                @endif
                <!------------------ End Step First -------------------->
                <!------------------------------------------------------>
                <!--------------------- Step Middle -------------------->
                @if ($step == 2)
                    <div class="checkout__header">
                        <button class="checkout__button" wire:click.prevent="previous()">
                            Pasul anterior
                        </button>
                        <button class="checkout__button" wire:click.prevent="confirm()">
                            Confirma Comanda
                        </button>
                    </div>
                    <div class="section__header">
                        <h2 class="section__title">Verificați detaliile dumneavoastră.</h2>
                    </div>
                    <div class="total__container">
                        <!---------------------------------------------------->
                        <!-------------- Checkout List of Forms -------------->
                        @if ($individual)
                            <div class="checkout__form">
                                <!---------------------------------------------------->
                                <!------------- Checkout Header Name --------------->
                                <div class="checkout__top">
                                    <h3>
                                        Informatii de facturare &check;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <span class="total__message">Nume si Prenume:
                                    {{ $individual_billing_first }}
                                    {{ $individual_billing_last }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Telefon:
                                    {{ $individual_billing_phone }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Email:
                                    {{ $individual_billing_email }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Adresa:
                                    {{ $individual_billing_address1 }}
                                    {{ $individual_billing_address2 }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Tara:
                                    {{ $individual_billing_country }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Judet:
                                    {{ $individual_billing_county }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Oras:
                                    {{ $individual_billing_city }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Cod Postal:
                                    {{ $individual_billing_zipcode }}</span>
                                <!----------- End Checkout List of Items ------------->
                                <!---------------------------------------------------->
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__form">
                                <!---------------------------------------------------->
                                <!------------- Checkout Header Name --------------->
                                <div class="checkout__top">
                                    <h3>
                                        Informatii de livrare &check;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <span class="total__message">Nume si Prenume:
                                    {{ $individual_shipping_first }}
                                    {{ $individual_shipping_last }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Telefon:
                                    {{ $individual_shipping_phone }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Email:
                                    {{ $individual_shipping_email }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Adresa:
                                    {{ $individual_shipping_address1 }}
                                    {{ $individual_shipping_address2 }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Tara:
                                    {{ $individual_shipping_country }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Judet:
                                    {{ $individual_shipping_county }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Oras:
                                    {{ $individual_shipping_city }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Cod Postal:
                                    {{ $individual_shipping_zipcode }}</span>
                                <!----------- End Checkout List of Items ------------->
                                <!---------------------------------------------------->
                            </div>
                        @endif
                        <!---------------------------------------------------->
                        @if ($juridic)
                            <div class="checkout__form">
                                <!---------------------------------------------------->
                                <!------------- Checkout Header Name --------------->
                                <div class="checkout__top">
                                    <h3>
                                        Informatii de facturare &check;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <span class="total__message">Nume si Prenume:
                                    {{ $juridic_billing_first }}
                                    {{ $juridic_billing_last }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Telefon:
                                    {{ $juridic_billing_phone }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Email:
                                    {{ $juridic_billing_email }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Companie:
                                    {{ $juridic_billing_company_name }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Cod de înregistrare:
                                    {{ $juridic_billing_registration_code }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Număr de înregistrare:
                                    {{ $juridic_billing_registration_number }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Denumirea Bancii:
                                    {{ $juridic_billing_bank }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">COnt IBAN:
                                    {{ $juridic_billing_account }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Adresa:
                                    {{ $juridic_billing_address1 }}
                                    {{ $juridic_billing_address2 }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Tara:
                                    {{ $juridic_billing_country }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Judet:
                                    {{ $juridic_billing_county }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Oras:
                                    {{ $juridic_billing_city }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Cod Postal:
                                    {{ $juridic_billing_zipcode }}</span>
                                <!----------- End Checkout List of Items ------------->
                                <!---------------------------------------------------->
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__form">
                                <!---------------------------------------------------->
                                <!------------- Checkout Header Name --------------->
                                <div class="checkout__top">
                                    <h3>
                                        Informatii de livrare &check;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <span class="total__message">Nume si Prenume:
                                    {{ $juridic_shipping_first }}
                                    {{ $juridic_shipping_last }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">telefon:
                                    {{ $juridic_shipping_phone }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Email:
                                    {{ $juridic_shipping_email }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Adresa:
                                    {{ $juridic_shipping_address1 }}
                                    {{ $juridic_shipping_address2 }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Tara:
                                    {{ $juridic_shipping_country }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Judet:
                                    {{ $juridic_shipping_county }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Oras:
                                    {{ $juridic_shipping_city }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Cod Postal:
                                    {{ $juridic_shipping_zipcode }}</span>
                                <!----------- End Checkout List of Items ------------->
                                <!---------------------------------------------------->
                            </div>
                        @endif
                        <!---------------------------------------------------->
                        <div class="total__info">
                            @if (!$cartItems->isEmpty())
                                @foreach ($cartItems as $cartItem)
                                    <div class="total__product">
                                        @if (count($cartItem->product->media) > 0)
                                            @foreach ($cartItem->product->media as $media)
                                                @if ($media->location->location == 'main')
                                                    @if ($media->external)
                                                        <img class="cart__list--img" src="{{ $media->path }}"
                                                            alt="{{ $media->path }}">
                                                    @else
                                                        <img class="cart__list--img"
                                                            src="/{{ $media->path }}{{ $media->name }}"
                                                            alt="{{ $media->path }}">
                                                    @endif
                                                    <?php break; ?>
                                                @endif
                                            @endforeach
                                        @else
                                            <img class="cart__list--img" src="/images/store/default/default.svg"
                                                alt="something wrong">
                                        @endif
                                        <a href="/product/{{ $cartItem->product->id }}" target="_blank"
                                            class="total__name">{{ $cartItem->product->name }}</a>
                                        <span class="total__price">
                                            {{ $cartItem->quantity }} x {{ $cartItem->product->price }}
                                            <?php $currency = $cartItem->product->product_prices->first()->pricelist->currency->name; ?>
                                            {{ $cartItem->quantity * $cartItem->price }} {{ $currency }}
                                        </span>

                                    </div>
                                @endforeach
                            @endif

                            <div class="total__item">
                                <span>Modalitate de plata</span>
                                <span>{{ $delivery }}</span>
                            </div>
                            <div class="total__item">
                                <span>Delivery Price:</span>
                                <span>
                                    @if ($cart->delivery_price == 0)
                                        Gratuit
                                    @else
                                        {{ $cart->delivery_price }} {{ $currency }}
                                    @endif
                                </span>
                            </div>
                            @if ($cart->voucher)
                                <div class="total__item">
                                    <span>Voucher:</span>
                                    <span>
                                        {{ $cart->voucher->code }}
                                        {{ intval($cart->voucher->percent) }}%
                                    </span>
                                </div>
                            @endif
                            @if (!$cartItems->isEmpty())
                                <div class="total__item">
                                    <span>total</span>
                                    @if ($cart->voucher)
                                        <?php $total = $cart->sum_amount + $cart->delivery_price; ?>
                                        <span style="text-decoration: line-through; color:red;">
                                            {{ $total }}{{ $currency }}
                                        </span>
                                        {{ $cart->final_amount }} {{ $currency }}
                                        </span>
                                    @else
                                        <span>{{ $cart->final_amount }} {{ $currency }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <!------------ End Checkout List of Forms ------------>
                        <!---------------------------------------------------->
                    </div>
                @endif
                <!------------------- End Step Middle ------------------>
                <!------------------------------------------------------>
                <!--------------------- Step Final --------------------->
                @if ($step == 3)
                    <div class="section__header">
                        <h2 class="section__title">Mulțumim!</h2>
                        <p class="section__text">Vă mulțumim pentru plata efectuată! 🎉 Am primit-o și în prezent
                            procesăm comanda dumneavoastră. Echipa noastră lucrează cu dedicație pentru a pregăti
                            produsul dumneavoastră pentru expediere. 📦🔧</p>
                        <p class="section__text">Odată ce comanda dumneavoastră este în drum spre dumneavoastră, vă vom
                            trimite un e-mail de confirmare cu informații despre urmărire. Acest lucru vă va permite să
                            urmăriți coletul și să știți când să vă așteptați la sosirea sa. 📩🚚</p>
                        <p class="section__text">Dacă aveți întrebări sau aveți nevoie de asistență, vă rugăm să nu
                            ezitați să contactați echipa noastră de suport pentru clienți. Suntem aici pentru a vă ajuta
                            și pentru a vă asigura satisfacția. 💁‍♀️💬</p>
                        <p class="section__text">Apreciem afacerea dumneavoastră și sperăm că achiziția dumneavoastră
                            vă aduce fericire. Vă mulțumim că ați ales produsele noastre și așteptăm cu nerăbdare să vă
                            mai servim în viitor. 🙏😊<br>Cu cele mai bune urări,</p>
                        <a href="{{ url('/') }}" class="logo">
                            <img src="/images/store/logo.svg" alt="logo">
                        </a>
                    </div>
                @endif
                <!------------------- End Step Final ------------------->
                <!------------------------------------------------------>
                <!------------------- Checkout Links ------------------->
                <div class="checkout__navigation">
                    @if ($step == 2)
                        <a class="checkout__link" wire:click.prevent="previous()">
                            <svg>
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                        </a>
                        <a class="checkout__link" wire:click.prevent="confirm()">
                            <svg>
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </a>
                    @elseif ($step == 1)
                        <a class="checkout__link" wire:click.prevent="next()">
                            <svg>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    @elseif ($step == 3)
                        <a class="checkout__link" wire:click.prevent="finish()">
                            Mulțumim pentru comanda dumneavoastră
                        </a>
                    @endif

                </div>
                <!----------------- End Checkout Links ----------------->
                <!------------------------------------------------------>
            </div>
        </section>
        <!--------------------- End Checkout ------------------->
        <!------------------------------------------------------>
    @endif
</div>
