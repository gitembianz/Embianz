<div>
    <x-store-alert />
    @if ($back)
        <!------------------------------------------------------>
        <!-------------------- Error Message ------------------->
        <section>
            <div class="checkout container">
                <div class="section__header container">
                    <h2 class="section__title">Something went wrong!</h2>
                    <a class="section__text" href="/home">
                        Please go back
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
                <h2 class="section__title">Pagina Checkout</h2>
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
                    <span class="step__line full"></span>
                    <div class="step @if ($step > 1 || $step == 3) active @endif">2</div>
                    <span class="step__line @if ($step == 3) full @endif"></span>
                    <div class="step @if ($step == 3) active @endif">3</div>
                </div>
                <!------------------ End Step Numbers ------------------>
                <!------------------------------------------------------>

                <!-------------------- Step First ---------------------->
                @if ($step == 1)
                    <div class="section__header">
                        <h2 class="section__title">Delivery details</h2>
                    </div>
                    <div class="checkout__header">
                        <div class="checkout__navigation">
                            <button class="checkout__button @if ($individual) active @endif"
                                wire:click="showindividual()">Legal</button>
                            <button class="checkout__button @if ($juridic) active @endif"
                                wire:click="showjuridic()">Juridic</button>
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
                                    Billing Contact &#9998;
                                </h3>
                            </div>
                            <!----------- End Checkout Header Name ------------->
                            <!---------------------------------------------------->
                            <!------------- Checkout List of Items --------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_first" placeholder="First Name">
                                <span>
                                    @error("individual_billing_first")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_last" placeholder="Last Name">
                                <span>
                                    @error("individual_billing_last")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="tel" wire:model="individual_billing_phone" placeholder="Phone">
                                <span>
                                    @error("individual_billing_phone")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="email" wire:model="individual_billing_email" placeholder="Email">
                                <span>
                                    @error("individual_billing_email")
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
                                    Billing Address &#9998;
                                </h3>
                            </div>
                            <!----------- End Checkout Header Name ------------->
                            <!---------------------------------------------------->
                            <!------------- Checkout List of Items --------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_address1" placeholder="Address 1*">
                                <span>
                                    @error("individual_billing_address1")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_address2" placeholder="Address 2">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_country" placeholder="Country">
                                <span>
                                    @error("individual_billing_country")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_county" placeholder="County">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_city" placeholder="City">
                                <span>
                                    @error("individual_billing_city")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="email" placeholder="Email" wire:model="individual_billing_zipcode"
                                    placeholder="Post Code">
                                <span>
                                    @error("individual_billing_zipcode")
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
                            <span>Shipping address is identical to Billing Address</span>
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
                                        Shipping Contact &#9998;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_first"
                                        placeholder="First Name">
                                    <span>
                                        @error("individual_shipping_first")
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_last" placeholder="Last Name">
                                    <span>
                                        @error("individual_shipping_last")
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="tel" wire:model="individual_shipping_phone" placeholder="Phone">
                                    <span>
                                        @error("individual_shipping_phone")
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="email" wire:model="individual_shipping_email" placeholder="Email">
                                    <span>
                                        @error("individual_shipping_email")
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
                                        Shipping Address &#9998;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_address1"
                                        placeholder="Address 1*">
                                    @error("individual_shipping_address1")
                                        {{ $message }}
                                    @enderror
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_address2"
                                        placeholder="Address 2">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_country"
                                        placeholder="Country">
                                    <span>
                                        @error("individual_shipping_country")
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_county"
                                        placeholder="County"">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_city" placeholder="City">
                                    <span>
                                        @error("individual_shipping_city")
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_zipcode"
                                        placeholder="Post Code">
                                    <span>
                                        @error("individual_shipping_zipcode")
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
                                    Juridical information &#9998;
                                </h3>
                            </div>
                            <!----------- End Checkout Header Name ------------->
                            <!---------------------------------------------------->
                            <!------------- Checkout List of Items --------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_first" placeholder="First Name">
                                <span>
                                    @error("juridic_billing_first")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_last" placeholder="Last Name">
                                <span>
                                    @error("juridic_billing_last")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="tel" wire:model="juridic_billing_phone" placeholder="Phone">
                                <span>
                                    @error("juridic_billing_phone")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="email" wire:model="juridic_billing_email" placeholder="Email">
                                <span>
                                    @error("juridic_billing_email")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_company_name"
                                    placeholder="Company Name">
                                <span>
                                    @error("juridic_billing_company_name")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_registration_code"
                                    placeholder="Registration code">
                                <span>
                                    @error("juridic_billing_registration_code")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_registration_number"
                                    placeholder="Registration number">
                                <span>
                                    @error("juridic_billing_registration_number")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_bank" placeholder="Bank">
                                <span>
                                    @error("juridic_billing_bank")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_account" placeholder="Account">
                                <span>
                                    @error("juridic_billing_account")
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
                                    Billing Address &#9998;
                                </h3>
                            </div>
                            <!----------- End Checkout Header Name ------------->
                            <!---------------------------------------------------->
                            <!------------- Checkout List of Items --------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_address1" placeholder="Address 1*">
                                <span>
                                    @error("juridic_billing_address1")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_address2" placeholder="Address 2">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_country" placeholder="Country">
                                <span>
                                    @error("juridic_billing_country")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_county" placeholder="County">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_city" placeholder="City">
                                <span>
                                    @error("juridic_billing_city")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_zipcode" placeholder="Post Code">
                                <span>
                                    @error("juridic_billing_zipcode")
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
                            <span>Shipping address is identical to Billing Address</span>
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
                                        Delivery Contact &#9998;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="juridic_shipping_first"
                                        placeholder="First Name">
                                    <span>
                                        @error("juridic_shipping_first")
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="juridic_shipping_last" placeholder="Last Name">
                                    <span>
                                        @error("juridic_shipping_last")
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="tel" wire:model="juridic_shipping_phone" placeholder="Phone">
                                    <span>
                                        @error("juridic_shipping_phone")
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="email" wire:model="juridic_shipping_email" placeholder="Email">
                                    <span>
                                        @error("juridic_shipping_email")
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
                                        Shipping Address &#9998;
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
                                    <input type="text" placeholder="Address 2">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" placeholder="Country">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" placeholder="County">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" placeholder="City">
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" placeholder="Post Code">
                                </div>
                                <!----------- End Checkout List of Items ------------->
                                <!---------------------------------------------------->
                            </div>
                        @endif
                        <!------------ End Checkout List of Forms ------------>
                        <!---------------------------------------------------->
                    </div>
                    <div class="section__header">
                        <h2 class="section__title">Payment method</h2>
                    </div>
                    @foreach ($payments as $payment)
                        @if ($payment->name == "Plata cash la livrare" && $cart->final_amount <= "1000")
                            @if ($payment->active)
                                <div class="payment">
                                    <label class="payment__wrapper" for="rtc" wire:click="togglepayment('rtc')">
                                        <input class="payment__checkbox" type="checkbox" wire:model.defer="rtc"
                                            id="rtc">
                                        <span>Plata cash la livrare</span>
                                    </label>
                                    <div class="payment__text @if ($rtc) active @endif">
                                        <h4>You will pay when the order is
                                            delivered.</h4>
                                        <span>Limita maxima este de 1000 RON</span>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @if ($payment->name == "Ordin de plata")
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
                                                Payment method used by legal entities. After placing the order, you
                                                will receive by email the proforma invoice with all the payment details.
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
                            Previous Step
                        </button>
                        <button class="checkout__button" wire:click.prevent="confirm()">
                            Confirma Comanda
                        </button>
                    </div>
                    <div class="section__header">
                        <h2 class="section__title">Check your details</h2>
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
                                        Billing information&check;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <span class="total__message">Full Name:
                                    {{ $individual_billing_first }}
                                    {{ $individual_billing_last }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Phone:
                                    {{ $individual_billing_phone }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Email:
                                    {{ $individual_billing_email }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Address:
                                    {{ $individual_billing_address1 }}
                                    {{ $individual_billing_address2 }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Country:
                                    {{ $individual_billing_country }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">County:
                                    {{ $individual_billing_county }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">City:
                                    {{ $individual_billing_city }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Post Code:
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
                                        Shipping information &check;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <span class="total__message">Full Name:
                                    {{ $individual_shipping_first }}
                                    {{ $individual_shipping_last }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Phone:
                                    {{ $individual_shipping_phone }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Email:
                                    {{ $individual_shipping_email }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Address:
                                    {{ $individual_shipping_address1 }}
                                    {{ $individual_shipping_address2 }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Country:
                                    {{ $individual_shipping_country }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">County:
                                    {{ $individual_shipping_county }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">City:
                                    {{ $individual_shipping_city }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Post Code:
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
                                        Billing information &check;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <span class="total__message">Full Name:
                                    {{ $juridic_billing_first }}
                                    {{ $juridic_billing_last }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Phone:
                                    {{ $juridic_billing_phone }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Email:
                                    {{ $juridic_billing_email }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Company:
                                    {{ $juridic_billing_company_name }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Registration code:
                                    {{ $juridic_billing_registration_code }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Registration number:
                                    {{ $juridic_billing_registration_number }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Bank:
                                    {{ $juridic_billing_bank }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Account:
                                    {{ $juridic_billing_account }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Address:
                                    {{ $juridic_billing_address1 }}
                                    {{ $juridic_billing_address2 }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Country:
                                    {{ $juridic_billing_country }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">County:
                                    {{ $juridic_billing_county }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">City:
                                    {{ $juridic_billing_city }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Post Code:
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
                                        Shipping information &check;
                                    </h3>
                                </div>
                                <!----------- End Checkout Header Name ------------->
                                <!---------------------------------------------------->
                                <!------------- Checkout List of Items --------------->
                                <span class="total__message">Full Name:
                                    {{ $juridic_shipping_first }}
                                    {{ $juridic_shipping_last }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Phone:
                                    {{ $juridic_shipping_phone }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Email:
                                    {{ $juridic_shipping_email }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Address:
                                    {{ $juridic_shipping_address1 }}
                                    {{ $juridic_shipping_address2 }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Country:
                                    {{ $juridic_shipping_country }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">County:
                                    {{ $juridic_shipping_county }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">City:
                                    {{ $juridic_shipping_city }}</span>
                                <!---------------------------------------------------->
                                <span class="total__message">Post Code:
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
                                                @if ($media->location->location == "main")
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
                                        <h2 class="total__name">{{ $cartItem->product->name }}</h2>
                                        <span class="total__price">
                                            {{ $cartItem->quantity }} x {{ $cartItem->product->price }} =
                                            <?php $currency = $cartItem->product->product_prices->first()->pricelist->currency->name; ?>
                                            {{ $cartItem->quantity * $cartItem->price }} {{ $currency }}
                                        </span>

                                    </div>
                                @endforeach
                            @endif

                            <div class="total__item">
                                <span>payment Method</span>
                                <span>{{ $delivery }}</span>
                            </div>
                            <div class="total__item">
                                <span>Delivery Price:</span>
                                <span>
                                    @if ($cart->delivery_price == 0)
                                        Free
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
                                        {{ $cart->final_amount }}{{ $currency }}
                                        </span>
                                    @else
                                        <span>{{ $cart->final_amount }}{{ $currency }}</span>
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
                        <h2 class="section__title">Thank you!</h2>
                        <p class="section__text">Thank you for your payment! 🎉 We have received it and are currently
                            processing your order. Our team is working hard to prepare your product for shipment. 📦🔧
                        </p>
                        <p class="section__text">Once your order is on its way, we will send you a confirmation email
                            with
                            tracking information. This will allow you to keep track of your package and know when to
                            expect
                            its
                            arrival. 📩🚚</p>
                        <p class="section__text">Should you have any questions or require assistance, please don't
                            hesitate
                            to
                            contact our customer support team. We're here to help and ensure your satisfaction. 💁‍♀️💬
                        </p>
                        <p class="section__text">We appreciate your business and hope that your purchase brings you
                            happiness.
                            Thank you for choosing our products, and we look forward to serving you again in the future.
                            🙏😊<br>Best regards,</p>
                        <a href="#" class="logo">
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
                            Thank you for your order, Shopping again
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
