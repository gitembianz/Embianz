<div class="details container">
    <x-loading />
    <x-alert />
    @if ($back)
        <h1 class="section__title">Something went wrong!</h1>
        <div class="details__btns">
            <a href="/home">Please go back</a>
        </div>
    @else
        <div class="details__steps">
            <div class="details__steps--item active">1</div>
            <span class="details__steps--line active"></span>
            <div class="details__steps--item @if ($step > 1 || $step == 3) active @endif">2</div>
            <span class="details__steps--line @if ($step == 3) active @endif"></span>
            <div class="details__steps--item @if ($step == 3) active @endif">3</div>
        </div>

        @if ($step == 1)
            <h1 class="section__title">Delivery details</h1>

            <div class="details__tab">
                <div class="details__tab--header" style="justify-content: space-between">
                    <div>
                        <button wire:click="showindividual()"
                            class="details__tab--btn @if ($individual) active @endif"
                            data-tooltip="Get this product for your personal enjoyment">Individual</button>
                        <button wire:click="showjuridic()"
                            class="details__tab--btn @if ($juridic) active @endif"
                            data-tooltip="Purchase this item from company">Legal
                            Person
                        </button>
                    </div>
                    <div class="details__btns">
                        <a wire:click="resetForm" data-tooltip="Clear form"><svg>
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                            </svg>
                        </a>
                        <a wire:click.prevent="next()" data-tooltip="Go to next step">
                            <svg>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="details__tab--content">
                    <div class="details__tab--pane @if ($individual) active @endif">
                        <!-- here is billing contact -->
                        <div class="details__content-add">
                            <span class="details__content-add--number">1</span>
                            <div class="details__content-add--column">
                                <span class="details__content-add--text">Billing Contact &#9998;</span>
                                <div>
                                    <input class="details__content-add--input" type="text"
                                        wire:model="individual_billing_first" placeholder="First Name">
                                    <p class="real-time-validation">
                                        @error('individual_billing_first')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" type="text"
                                        wire:model="individual_billing_last" placeholder="Last Name">
                                    <p class="real-time-validation">
                                        @error('individual_billing_last')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" type="text"
                                        wire:model="individual_billing_phone" placeholder="Phone">
                                    <p class="real-time-validation">
                                        @error('individual_billing_phone')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" type="text"
                                        wire:model="individual_billing_email" placeholder="Email">
                                    <p class="real-time-validation">
                                        @error('individual_billing_email')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>

                        </div>
                        <!-- here is billing address -->
                        <div class="details__content-add">
                            <span class="details__content-add--number">2</span>
                            <div class="details__content-add--column">
                                <span class="details__content-add--text">Billing Address &#9998;</span>
                                <div>
                                    <input class="details__content-add--input" wire:model="individual_billing_address1"
                                        type="text" placeholder="Address 1*">
                                    <p class="real-time-validation">
                                        @error('individual_billing_address1')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>

                                    <input class="details__content-add--input" wire:model="individual_billing_address2"
                                        type="text" placeholder="Address 2">
                                </div>

                                <div>
                                    <input class="details__content-add--input" wire:model="individual_billing_country"
                                        type="text" placeholder="Country">
                                    <p class="real-time-validation">
                                        @error('individual_billing_country')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>

                                    <input class="details__content-add--input" wire:model="individual_billing_county"
                                        type="text" placeholder="County">
                                </div>

                                <div>
                                    <input class="details__content-add--input" wire:model="individual_billing_city"
                                        type="text" placeholder="City">
                                    <p class="real-time-validation">
                                        @error('individual_billing_city')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="individual_billing_zipcode"
                                        type="text" placeholder="Post Code">
                                    <p class="real-time-validation">
                                        @error('individual_billing_zipcode')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="details__content--checkbox @if ($individual_identic) active @endif">
                            Shipping address is identical to Billing Address
                            <label class="checkbox">
                                <input wire:model="individual_identic" type="checkbox">
                                <span></span>
                            </label>
                        </div>

                        @if (!$individual_identic)
                            <!-- here is delivery contact -->
                            <div class="details__content-add">
                                <span class="details__content-add--number">3</span>
                                <div class="details__content-add--column">
                                    <span class="details__content-add--text">Shipping Contact &#9998;</span>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_first" type="text"
                                            placeholder="First Name">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_first')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_last" type="text"
                                            placeholder="Last Name">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_last')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_phone" type="text"
                                            placeholder="Phone">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_phone')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_email" type="text"
                                            placeholder="Email">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_email')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Here is shipping address -->
                            <div class="details__content-add">
                                <span class="details__content-add--number">4</span>
                                <div class="details__content-add--column">
                                    <span class="details__content-add--text">Shipping Address &#9998;</span>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_address1" type="text"
                                            placeholder="Address 1*">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_address1')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>

                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_address2" type="text"
                                            placeholder="Address 2">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_country" type="text"
                                            placeholder="Country">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_country')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>

                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_county" type="text"
                                            placeholder="County">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_city" type="text" placeholder="City">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_city')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_zipcode" type="text"
                                            placeholder="Post Code">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_zipcode')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="details__tab--pane @if ($juridic) active @endif">
                        <!-- here is billing contact -->
                        <div class="details__content-add">
                            <span class="details__content-add--number">1</span>
                            <div class="details__content-add--column">
                                <span class="details__content-add--text">Juridical information &#9998;</span>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_first"
                                        type="text" placeholder="First Name">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_first')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_last"
                                        type="text" placeholder="Last Name">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_last')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_phone"
                                        type="text" placeholder="Phone">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_phone')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_email"
                                        type="text" placeholder="Email">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_email')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input"
                                        wire:model="juridic_billing_company_name" type="text"
                                        placeholder="Company Name">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_company_name')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input"
                                        wire:model="juridic_billing_registration_code" type="text"
                                        placeholder="Registration code">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_registration_code')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input"
                                        wire:model="juridic_billing_registration_number" type="text"
                                        placeholder="Registration number">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_registration_number')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_bank"
                                        type="text" placeholder="Bank">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_bank')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_account"
                                        type="text" placeholder="Account">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_account')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- here is billing address -->
                        <div class="details__content-add">
                            <span class="details__content-add--number">2</span>
                            <div class="details__content-add--column">
                                <span class="details__content-add--text">Billing Address &#9998;</span>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_address1"
                                        type="text" placeholder="Address 1*">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_address1')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>

                                    <input class="details__content-add--input" type="text"
                                        placeholder="Address 2">
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_country"
                                        type="text" placeholder="Country">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_country')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>

                                    <input class="details__content-add--input" type="text" placeholder="County">
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_city"
                                        type="text" placeholder="City">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_city')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_zipcode"
                                        type="text" placeholder="Post Code">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_zipcode')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="details__content--checkbox @if ($juridic_identic) active @endif">
                            Shipping address is identical to Billing Address
                            <label class="checkbox">
                                <input wire:model="juridic_identic" type="checkbox">
                                <span></span>
                            </label>
                        </div>
                        @if (!$juridic_identic)
                            <!-- here is delivery contact -->
                            <div class="details__content-add">
                                <span class="details__content-add--number">3</span>
                                <div class="details__content-add--column">
                                    <span class="details__content-add--text">Delivery Contact &#9998;</span>
                                    <div>
                                        <input class="details__content-add--input" wire:model="juridic_shipping_first"
                                            type="text" placeholder="First Name">
                                        <p class="real-time-validation">
                                            @error('juridic_shipping_first')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" wire:model="juridic_shipping_last"
                                            type="text" placeholder="Last Name">
                                        <p class="real-time-validation">
                                            @error('juridic_shipping_last')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" wire:model="juridic_shipping_phone"
                                            type="text" placeholder="Phone">
                                        <p class="real-time-validation">
                                            @error('juridic_shipping_phone')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" wire:model="juridic_shipping_email"
                                            type="text" placeholder="Email">
                                        <p class="real-time-validation">
                                            @error('juridic_shipping_email')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Here is shipping address -->
                            <div class="details__content-add">
                                <span class="details__content-add--number">4</span>
                                <div class="details__content-add--column">
                                    <span class="details__content-add--text">Shipping Address &#9998;</span>
                                    {{-- <input class="details__content-add--input" type="text" placeholder="First Name">
                            <input class="details__content-add--input" type="text" placeholder="Last Name">
                            <input class="details__content-add--input" type="text" placeholder="Phone">
                            <input class="details__content-add--input" type="text" placeholder="Email"> --}}
                                    <div>
                                        <input class="details__content-add--input" type="text"
                                            placeholder="Address 1*">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" type="text"
                                            placeholder="Address 2">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" type="text"
                                            placeholder="Country">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" type="text"
                                            placeholder="County">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" type="text" placeholder="City">
                                    </div>
                                    <div>

                                        <input class="details__content-add--input" type="text"
                                            placeholder="Post Code">
                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <h1 class="section__title">Payment method</h1>
            <div class="details__accordion">
                @foreach ($payments as $payment)
                    @if ($payment->name == 'Plata cash la livrare' && $cart->final_amount <= '1000')
                        @if ($payment->active)
                            <label class="details__accordion--item" for="rtc" wire:click="togglepayment('rtc')">
                                <div
                                    class="details__accordion-header @if ($rtc) active @endif">
                                    <input type="checkbox" wire:model.defer="rtc" id="rtc">
                                    <span class="details__accordion-header--img">
                                        <svg>
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </span>
                                    <h4 class="details__accordion-header--text">
                                        Plata cash la livrare
                                    </h4>
                                </div>
                                <div class="details__accordion-wrap"
                                    @if ($rtc) style="max-height: 200px" @endif>
                                    <div class="details__accordion-content">
                                        <p class="details__accordion-content--text">You will pay when the order is
                                            delivered.<br>
                                            <span class="details__accordion-content--span">
                                                Limita maxima este de 1000 RON
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </label>
                        @endif
                    @endif
                    @if ($payment->name == 'Ordin de plata')
                        @if ($payment->active)
                            @if ($juridic)
                                <label class="details__accordion--item" for="invoice"
                                    wire:click="togglepayment('invoice')">
                                    <div
                                        class="details__accordion-header @if ($invoice) active @endif">
                                        <input type="checkbox" wire:model.defer="invoice" id="invoice">
                                        <span class="details__accordion-header--img">
                                            <svg>
                                                <path
                                                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                </path>
                                                <polyline points="22,6 12,13 2,6"></polyline>
                                            </svg>
                                        </span>
                                        <h4 class="details__accordion-header--text">
                                            Ordin de plata
                                        </h4>
                                    </div>
                                    <div class="details__accordion-wrap"
                                        @if ($invoice) style="max-height: 200px" @endif>
                                        <div class="details__accordion-content">
                                            <p class="details__accordion-content--text">
                                                Payment method used by legal entities. After placing the order, you will
                                                receive by
                                                email
                                                the proforma invoice with all the payment details.
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @endif
                    @endif
                @endforeach
            </div>
        @endif
        @if ($step == 2)
            <div class="details__tab--header" style="justify-content: space-between; width: 100%">
                <div class="details__btns">
                    <a wire:click.prevent="previous()" data-tooltip="Go to previous step">
                        <svg>
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                    </a>
                    <a class="confirmOrder" wire:click.prevent="confirm()" data-tooltip="Confirm order">
                        Confirma Comanda
                        <svg>
                            <polyline points="9 11 12 14 22 4"></polyline>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                    </a>
                </div>
                <h1 class="section__title">Check your details</h1>

            </div>
            <div class="checking__wrapper">
                <div class="checking__cart">
                    <div class="checking__cart--items">
                        @if (!$cartItems->isEmpty())
                            @foreach ($cartItems as $cartItem)
                                <div class="checking__content-item">
                                    <span class="cart__list--much">{{ $cartItem->quantity }} x</span>

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
                                    <span class="cart__list--text">{{ $cartItem->product->name }}</span>
                                    <span class="cart__list--much">
                                        <?php $currency = $cartItem->product->product_prices->first()->pricelist->currency->name; ?>
                                        {{ $cartItem->quantity * $cartItem->price }} {{ $currency }}
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- Here is Card Information -->
                    <div style="display: flex;flex-direction: row;justify-content: space-between"
                        class="checking__content-complete">
                        <span class="checking__content-create--text">Payment Method: {{ $delivery }}
                            &check;</span>
                        <span class="checking__content-create--text">Delivery Price: @if ($cart->delivery_price == 0)
                                Free
                            @else
                                {{ $cart->delivery_price }} {{ $currency }}
                            @endif
                        </span>
                        @if ($cart->voucher)
                            <span class="checking__content-create--text">Voucher: {{ $cart->voucher->code }}
                                {{ intval($cart->voucher->percent) }}% </span>
                        @endif
                    </div>
                    <div class="checking__content-price">
                        @if (!$cartItems->isEmpty())
                            <span class="checking__content-complete--text">Total:</span>
                            @if ($cart->voucher)
                                <?php $total = $cart->sum_amount + $cart->delivery_price; ?>
                                <span><span
                                        style="text-decoration: line-through; color:red; margin-right:1rem">{{ $total }}{{ $currency }}</span>{{ $cart->final_amount }}{{ $currency }}</span>
                            @else
                                <span class="checking__content-create--text">{{ $cart->final_amount }}
                                    {{ $currency }}</span>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="checking">
                    <!-- here is billing information -->
                    @if ($individual)
                        <div class="checking__content-complete">
                            <span style="font-weight: bold" class="checking__content-create--text">Billing information
                                &check;</span>
                            <span class="checking__content-complete--text">Full Name: {{ $individual_billing_first }}
                                {{ $individual_billing_last }}</span>
                            <span class="checking__content-complete--text">Phone:
                                {{ $individual_billing_phone }}</span>
                            <span class="checking__content-complete--text">Email:
                                {{ $individual_billing_email }}</span>
                            <span class="checking__content-complete--text">Address: {{ $individual_billing_address1 }}
                                {{ $individual_billing_address2 }}</span>
                            <span class="checking__content-complete--text">Country:
                                {{ $individual_billing_country }}</span>
                            <span class="checking__content-complete--text">County:
                                {{ $individual_billing_county }}</span>
                            <span class="checking__content-complete--text">City: {{ $individual_billing_city }}</span>
                            <span class="checking__content-complete--text">Post Code:
                                {{ $individual_billing_zipcode }}</span>
                        </div>
                        <!-- here is shipping information -->
                        <div class="checking__content-complete">
                            <span style="font-weight: bold" class="checking__content-create--text">Shipping
                                information
                                &check;</span>
                            <span class="checking__content-complete--text">Full Name: {{ $individual_shipping_first }}
                                {{ $individual_shipping_last }}</span>
                            <span class="checking__content-complete--text">Phone:
                                {{ $individual_shipping_phone }}</span>
                            <span class="checking__content-complete--text">Email:
                                {{ $individual_shipping_email }}</span>
                            <span class="checking__content-complete--text">Address:
                                {{ $individual_shipping_address1 }}
                                {{ $individual_shipping_address2 }}</span>
                            <span class="checking__content-complete--text">Country:
                                {{ $individual_shipping_country }}</span>
                            <span class="checking__content-complete--text">County:
                                {{ $individual_shipping_county }}</span>
                            <span class="checking__content-complete--text">City:
                                {{ $individual_shipping_city }}</span>
                            <span class="checking__content-complete--text">Post Code:
                                {{ $individual_shipping_zipcode }}</span>
                        </div>
                    @endif
                    @if ($juridic)
                        <div class="checking__content-complete">
                            <span class="checking__content-create--text">Billing information &check;</span>
                            <span class="checking__content-complete--text">Full Name: {{ $juridic_billing_first }}
                                {{ $juridic_billing_last }}</span>
                            <span class="checking__content-complete--text">Phone:
                                {{ $juridic_billing_phone }}</span>
                            <span class="checking__content-complete--text">Email:
                                {{ $juridic_billing_email }}</span>
                            <span class="checking__content-complete--text">Company:
                                {{ $juridic_billing_company_name }}</span>
                            <span class="checking__content-complete--text">Registration code:
                                {{ $juridic_billing_registration_code }}</span>
                            <span class="checking__content-complete--text">Registration number:
                                {{ $juridic_billing_registration_number }}</span>
                            <span class="checking__content-complete--text">Bank:
                                {{ $juridic_billing_bank }}</span>
                            <span class="checking__content-complete--text">Account:
                                {{ $juridic_billing_account }}</span>
                            <span class="checking__content-complete--text">Address: {{ $juridic_billing_address1 }}
                                {{ $juridic_billing_address2 }}</span>
                            <span class="checking__content-complete--text">Country:
                                {{ $juridic_billing_country }}</span>
                            <span class="checking__content-complete--text">County:
                                {{ $juridic_billing_county }}</span>
                            <span class="checking__content-complete--text">City: {{ $juridic_billing_city }}</span>
                            <span class="checking__content-complete--text">Post Code:
                                {{ $juridic_billing_zipcode }}</span>
                        </div>
                        <!-- here is shipping information -->
                        <div class="checking__content-complete">
                            <span class="checking__content-create--text">Shipping information &check;</span>
                            <span class="checking__content-complete--text">Full Name: {{ $juridic_shipping_first }}
                                {{ $juridic_shipping_last }}</span>
                            <span class="checking__content-complete--text">Phone:
                                {{ $juridic_shipping_phone }}</span>
                            <span class="checking__content-complete--text">Email:
                                {{ $juridic_shipping_email }}</span>
                            <span class="checking__content-complete--text">Address: {{ $juridic_shipping_address1 }}
                                {{ $juridic_shipping_address2 }}</span>
                            <span class="checking__content-complete--text">Country:
                                {{ $juridic_shipping_country }}</span>
                            <span class="checking__content-complete--text">County:
                                {{ $juridic_shipping_county }}</span>
                            <span class="checking__content-complete--text">City: {{ $juridic_shipping_city }}</span>
                            <span class="checking__content-complete--text">Post Code:
                                {{ $juridic_shipping_zipcode }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        @if ($step == 3)
            <h1 class="section__title">Thank you!</h1>
            <p>Thank you for your payment! 🎉 We have received it and are currently processing your order. Our team is
                working hard to prepare your product for shipment. 📦🔧</p>
            <p>Once your order is on its way, we will send you a confirmation email with tracking information. This will
                allow you to keep track of your package and know when to expect its arrival. 📩🚚</p>
            <p>Should you have any questions or require assistance, please don't hesitate to contact our customer
                support
                team. We're here to help and ensure your satisfaction. 💁‍♀️💬</p>
            <p>We appreciate your business and hope that your purchase brings you happiness. Thank you for choosing our
                products, and we look forward to serving you again in the future. 🙏😊<br>Best regards,</p>
            <h2 class="logo">Embianz</h2>
        @endif
        <div class="details__btns">
            @if ($step == 2)
                <a wire:click.prevent="previous()" data-tooltip="Go to previous step">
                    <svg>
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                </a>
                <a wire:click.prevent="confirm()" data-tooltip="Confirm order">
                    <svg>
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                </a>
            @elseif ($step == 1)
                <a wire:click.prevent="next()" data-tooltip="Go to next step">
                    <svg>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            @elseif ($step == 3)
                <a wire:click.prevent="finish()" data-tooltip="Go back to store">
                    Thank you for your order, Shopping again
                </a>
            @endif

        </div>
    @endif

</div>
