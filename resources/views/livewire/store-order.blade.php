<div>
 <x-store-alert />
 @if ($back)
  <!------------------------------------------------------>
  <!-------------------- Error Message ------------------->
  <section>
   <div class="checkout container">
    <div class="section__header container">
     <h1 class="section__title">Ups, a aparut o eroare!</h1>
     <a class="section__text" href="{{ url('/') }}">
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

  <!------------------------------------------------------>
  <input id="step__container_bulins" type="hidden">
  <section>
   <div class="checkout container">
    <!------------------------------------------------------>
    <!-------------------- Step Numbers -------------------->
    <div class="step__container">
     <div class="step active" data-step="Inregistrare Date">1</div>
     <span class="step__line @if ($step == 1) half @else full @endif"></span>
     <div class="step @if ($step > 1 || $step == 3) active @endif" data-step="Plasare comanda">2
     </div>
     <span
      class="step__line @if ($step == 2) half @elseif($step == 3) full @endif"></span>
     <div class="step @if ($step == 3) active @endif" data-step="Confirmare">3
     </div>
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
       <button class="checkout__button @if ($juridic) active @endif" wire:click="showjuridic()">
        Persoana Juridica</button>
      </div>
      <div class="checkout__navigation">
       <button class="checkout__button" wire:click="resetForm">
        <svg>
         <polyline points="1 4 1 10 7 10"></polyline>
         <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
        </svg>
        Șterge Datele
       </button>
       <button class="checkout__button checkout__button--confirm"
        @if ($individual && $individual_identic) onclick="validateIndividual(this)" @elseif ($individual && !$individual_identic) onclick="validateIndividualIdentic(this)" @elseif($juridic && $juridic_identic) onclick="validateJuridic(this)" @else onclick="validateJuridicIdentic(this)" @endif
        wire:click.prevent="next()">
        Pasul următor
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
      <div wire:ignore class="checkout__form active">
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

       <div class="checkout__item checkout__item--required" id="individualShippingFirstName">
        <input type="text" wire:model="individual_billing_first" name="individualShippingFirstName"
         placeholder="Nume" autocomplete="given-name" required>
        <span>
         @error('individual_billing_first')
          {{ $message }}
         @enderror
        </span>
        <label for="individualShippingFirstName">Nume</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="individualShippingLastName">
        <input type="text" wire:model="individual_billing_last" name="individualShippingLastName"
         placeholder="Prenume" autocomplete="family-name" required>
        <span>
         @error('individual_billing_last')
          {{ $message }}
         @enderror
        </span>
        <label for="individualShippingLastName">Prenume</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="individualShippingPhone">
        <input type="tel" wire:model="individual_billing_phone" name="individualShippingPhone" placeholder="Telefon"
         autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required>
        <span>
         @error('individual_billing_phone')
          {{ $message }}
         @enderror
        </span>
        <label for="individualShippingPhone">Telefon</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="individualShippingEmail">
        <input type="email" wire:model="individual_billing_email" name="individualShippingEmail" placeholder="Email"
         autocomplete="email" required>
        <span>
         @error('individual_billing_email')
          {{ $message }}
         @enderror
        </span>
        <label for="individualShippingEmail">Email</label>
       </div>
       <!----------- End Checkout List of Items ------------->
       <!---------------------------------------------------->
      </div>
      <!---------------------------------------------------->
      <div wire:ignore class="checkout__form active">
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
       <div class="checkout__item checkout__item--required" id="individualShippingAddress">
        <input type="text" wire:model="individual_billing_address1" name="individualShippingAddress"
         placeholder="Adresa 1" autocomplete="street-address" required>
        <span>
         @error('individual_billing_address1')
          {{ $message }}
         @enderror
        </span>
        <label for="individualShippingAddress">Adresa 1</label>
       </div>
       {{-- <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_address2"
                                    placeholder="Address 2 (optional)">
                            </div> --}}
       <div class="checkout__item" id="individualShippingAddress2">
        <input type="text" wire:model="individual_billing_address2" name="individualShippingAddress2"
         placeholder="Adresa 2 (opțional)" autocomplete="address-level2">
        <span></span>
        <label for="individualShippingAddress2">Adresa 2 (opțional)</label>
       </div>
       <!------------------------------------------------------------------->

       <select wire:model="individual_billing_country" class="select" aria-label="select a country">
        <option value="Afghanistan">Afghanistan</option>
        <option value="Åland Islands">Åland Islands</option>
        <option value="Albania">Albania</option>
        <option value="Algeria">Algeria</option>
        <option value="American Samoa">American Samoa</option>
        <option value="Andorra">Andorra</option>
        <option value="Angola">Angola</option>
        <option value="Anguilla">Anguilla</option>
        <option value="Antarctica">Antarctica</option>
        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
        <option value="Argentina">Argentina</option>
        <option value="Armenia">Armenia</option>
        <option value="Aruba">Aruba</option>
        <option value="Australia">Australia</option>
        <option value="Austria">Austria</option>
        <option value="Azerbaijan">Azerbaijan</option>
        <option value="Bahamas">Bahamas</option>
        <option value="Bahrain">Bahrain</option>
        <option value="Bangladesh">Bangladesh</option>
        <option value="Barbados">Barbados</option>
        <option value="Belarus">Belarus</option>
        <option value="Belgium">Belgium</option>
        <option value="Belize">Belize</option>
        <option value="Benin">Benin</option>
        <option value="Bermuda">Bermuda</option>
        <option value="Bhutan">Bhutan</option>
        <option value="Bolivia">Bolivia</option>
        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
        <option value="Botswana">Botswana</option>
        <option value="Bouvet Island">Bouvet Island</option>
        <option value="Brazil">Brazil</option>
        <option value="British Indian Ocean Territory">British Indian Ocean Territory
        </option>
        <option value="Brunei Darussalam">Brunei Darussalam</option>
        <option value="Bulgaria">Bulgaria</option>
        <option value="Burkina Faso">Burkina Faso</option>
        <option value="Burundi">Burundi</option>
        <option value="Cambodia">Cambodia</option>
        <option value="Cameroon">Cameroon</option>
        <option value="Canada">Canada</option>
        <option value="Cape Verde">Cape Verde</option>
        <option value="Cayman Islands">Cayman Islands</option>
        <option value="Central African Republic">Central African Republic</option>
        <option value="Chad">Chad</option>
        <option value="Chile">Chile</option>
        <option value="China">China</option>
        <option value="Christmas Island">Christmas Island</option>
        <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
        <option value="Colombia">Colombia</option>
        <option value="Comoros">Comoros</option>
        <option value="Congo">Congo</option>
        <option value="Congo, The Democratic Republic of the">Congo, The Democratic
         Republic of the</option>
        <option value="Cook Islands">Cook Islands</option>
        <option value="Costa Rica">Costa Rica</option>
        <option value="Croatia">Croatia</option>
        <option value="Cuba">Cuba</option>
        <option value="Cyprus">Cyprus</option>
        <option value="Czech Republic">Czech Republic</option>
        <option value="Denmark">Denmark</option>
        <option value="Djibouti">Djibouti</option>
        <option value="Dominica">Dominica</option>
        <option value="Dominican Republic">Dominican Republic</option>
        <option value="Ecuador">Ecuador</option>
        <option value="Egypt">Egypt</option>
        <option value="El Salvador">El Salvador</option>
        <option value="Equatorial Guinea">Equatorial Guinea</option>
        <option value="Eritrea">Eritrea</option>
        <option value="Estonia">Estonia</option>
        <option value="Ethiopia">Ethiopia</option>
        <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
        <option value="Faroe Islands">Faroe Islands</option>
        <option value="Fiji">Fiji</option>
        <option value="Finland">Finland</option>
        <option value="France">France</option>
        <option value="French Guiana">French Guiana</option>
        <option value="French Polynesia">French Polynesia</option>
        <option value="French Southern Territories">French Southern Territories</option>
        <option value="Gabon">Gabon</option>
        <option value="Gambia">Gambia</option>
        <option value="Georgia">Georgia</option>
        <option value="Germany">Germany</option>
        <option value="Ghana">Ghana</option>
        <option value="Gibraltar">Gibraltar</option>
        <option value="Greece">Greece</option>
        <option value="Greenland">Greenland</option>
        <option value="Grenada">Grenada</option>
        <option value="Guadeloupe">Guadeloupe</option>
        <option value="Guam">Guam</option>
        <option value="Guatemala">Guatemala</option>
        <option value="Guernsey">Guernsey</option>
        <option value="Guinea">Guinea</option>
        <option value="Guinea-Bissau">Guinea-Bissau</option>
        <option value="Guyana">Guyana</option>
        <option value="Haiti">Haiti</option>
        <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands
        </option>
        <option value="Holy See (Vatican City State)">Holy See (Vatican City State)
        </option>
        <option value="Honduras">Honduras</option>
        <option value="Hong Kong">Hong Kong</option>
        <option value="Hungary">Hungary</option>
        <option value="Iceland">Iceland</option>
        <option value="India">India</option>
        <option value="Indonesia">Indonesia</option>
        <option value="Iran, Islamic Republic Of">Iran, Islamic Republic Of</option>
        <option value="Iraq">Iraq</option>
        <option value="Ireland">Ireland</option>
        <option value="Isle of Man">Isle of Man</option>
        <option value="Israel">Israel</option>
        <option value="Italy">Italy</option>
        <option value="Jamaica">Jamaica</option>
        <option value="Japan">Japan</option>
        <option value="Jersey">Jersey</option>
        <option value="Jordan">Jordan</option>
        <option value="Kazakhstan">Kazakhstan</option>
        <option value="Kenya">Kenya</option>
        <option value="Kiribati">Kiribati</option>
        <option value="Korea, Republic of">Korea, Republic of</option>
        <option value="Kuwait">Kuwait</option>
        <option value="Kyrgyzstan">Kyrgyzstan</option>
        <option value="Latvia">Latvia</option>
        <option value="Lebanon">Lebanon</option>
        <option value="Lesotho">Lesotho</option>
        <option value="Liberia">Liberia</option>
        <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
        <option value="Liechtenstein">Liechtenstein</option>
        <option value="Lithuania">Lithuania</option>
        <option value="Luxembourg">Luxembourg</option>
        <option value="Macao">Macao</option>
        <option value="North Macedonia">North Macedonia</option>
        <option value="Madagascar">Madagascar</option>
        <option value="Malawi">Malawi</option>
        <option value="Malaysia">Malaysia</option>
        <option value="Maldives">Maldives</option>
        <option value="Mali">Mali</option>
        <option value="Malta">Malta</option>
        <option value="Marshall Islands">Marshall Islands</option>
        <option value="Martinique">Martinique</option>
        <option value="Mauritania">Mauritania</option>
        <option value="Mauritius">Mauritius</option>
        <option value="Mayotte">Mayotte</option>
        <option value="Mexico">Mexico</option>
        <option value="Micronesia, Federated States of">Micronesia, Federated States of
        </option>
        <option value="Republic of Moldova">Republic of Moldova</option>
        <option value="Monaco">Monaco</option>
        <option value="Mongolia">Mongolia</option>
        <option value="Montserrat">Montserrat</option>
        <option value="Morocco">Morocco</option>
        <option value="Mozambique">Mozambique</option>
        <option value="Myanmar">Myanmar</option>
        <option value="Namibia">Namibia</option>
        <option value="Nauru">Nauru</option>
        <option value="Nepal">Nepal</option>
        <option value="Netherlands">Netherlands</option>
        <option value="Netherlands Antilles">Netherlands Antilles</option>
        <option value="New Caledonia">New Caledonia</option>
        <option value="New Zealand">New Zealand</option>
        <option value="Nicaragua">Nicaragua</option>
        <option value="Niger">Niger</option>
        <option value="Nigeria">Nigeria</option>
        <option value="Niue">Niue</option>
        <option value="Norfolk Island">Norfolk Island</option>
        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
        <option value="Norway">Norway</option>
        <option value="Oman">Oman</option>
        <option value="Pakistan">Pakistan</option>
        <option value="Palau">Palau</option>
        <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied
        </option>
        <option value="Panama">Panama</option>
        <option value="Papua New Guinea">Papua New Guinea</option>
        <option value="Paraguay">Paraguay</option>
        <option value="Peru">Peru</option>
        <option value="Philippines">Philippines</option>
        <option value="Pitcairn Islands">Pitcairn Islands</option>
        <option value="Poland">Poland</option>
        <option value="Portugal">Portugal</option>
        <option value="Puerto Rico">Puerto Rico</option>
        <option value="Qatar">Qatar</option>
        <option value="Reunion">Reunion</option>
        <option value="Romania">Romania</option>
        <option value="Russian Federation">Russian Federation</option>
        <option value="Rwanda">Rwanda</option>
        <option value="Saint Helena">Saint Helena</option>
        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
        <option value="Saint Lucia">Saint Lucia</option>
        <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines
        </option>
        <option value="Samoa">Samoa</option>
        <option value="San Marino">San Marino</option>
        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
        <option value="Saudi Arabia">Saudi Arabia</option>
        <option value="Senegal">Senegal</option>
        <option value="Serbia and Montenegro">Serbia and Montenegro</option>
        <option value="Seychelles">Seychelles</option>
        <option value="Sierra Leone">Sierra Leone</option>
        <option value="Singapore">Singapore</option>
        <option value="Slovakia">Slovakia</option>
        <option value="Slovenia">Slovenia</option>
        <option value="Solomon Islands">Solomon Islands</option>
        <option value="Somalia">Somalia</option>
        <option value="South Africa">South Africa</option>
        <option value="South Georgia and the South Sandwich Islands">South Georgia and the
         South Sandwich Islands</option>
        <option value="Spain">Spain</option>
        <option value="Sri Lanka">Sri Lanka</option>
        <option value="Sudan">Sudan</option>
        <option value="Suriname">Suriname</option>
        <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
        <option value="Swaziland">Swaziland</option>
        <option value="Sweden">Sweden</option>
        <option value="Switzerland">Switzerland</option>
        <option value="Syrian Arab Republic">Syrian Arab Republic</option>
        <option value="Taiwan">Taiwan</option>
        <option value="Tajikistan">Tajikistan</option>
        <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
        <option value="Thailand">Thailand</option>
        <option value="Timor-Leste">Timor-Leste</option>
        <option value="Togo">Togo</option>
        <option value="Tokelau">Tokelau</option>
        <option value="Tonga">Tonga</option>
        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
        <option value="Tunisia">Tunisia</option>
        <option value="Turkey">Turkey</option>
        <option value="Turkmenistan">Turkmenistan</option>
        <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
        <option value="Tuvalu">Tuvalu</option>
        <option value="Uganda">Uganda</option>
        <option value="Ukraine">Ukraine</option>
        <option value="United Arab Emirates">United Arab Emirates</option>
        <option value="United Kingdom">United Kingdom</option>
        <option value="United States">United States</option>
        <option value="United States Minor Outlying Islands">United States Minor Outlying
         Islands</option>
        <option value="Uruguay">Uruguay</option>
        <option value="Uzbekistan">Uzbekistan</option>
        <option value="Vanuatu">Vanuatu</option>
        <option value="Venezuela">Venezuela</option>
        <option value="Vietnam">Vietnam</option>
        <option value="Virgin Islands, British">Virgin Islands, British</option>
        <option value="Virgin Islands, U.S"">Virgin Islands, U.S"</option>
        <option value="Wallis and Futuna">Wallis and Futuna</option>
        <option value="Western Sahara">Western Sahara</option>
        <option value="Yemen">Yemen</option>
        <option value="Zambia">Zambia</option>
        <option value="Zimbabwe">Zimbabwe</option>
       </select>
       <!---------------------------------------------------->
       {{-- <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_county" placeholder="Judet">
                            </div> --}}
       <div class="checkout__item checkout__item--required" id="individualShippingCounty">
        <input type="text" wire:model="individual_billing_county" name="individualShippingCounty"
         placeholder="Localitate (oraș, comună sau sat)" autocomplete="county" required>
        <span></span>
        <label for="individualShippingCounty">Localitate (oraș, comună sau sat)</label>
       </div>
       <!---------------------------------------------------->
       {{-- <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_city"
                                    placeholder="Localitate (oras, comună sau sat)">
                                <span>

                                </span>
                            </div> --}}
       <div class="checkout__item checkout__item--required" id="individualShippingCity">
        <input type="text" wire:model="individual_billing_city" name="individualShippingCity" placeholder="Oraș"
         autocomplete="city" required>
        <span>
         @error('individual_billing_city')
          {{ $message }}
         @enderror
        </span>
        <label for="individualShippingCity">Oraș</label>
       </div>
       <!---------------------------------------------------->
       {{-- <div class="checkout__item">
                                <input type="text" placeholder="Post Code" wire:model="individual_billing_zipcode"
                                    placeholder="Cod Postal">
                                <span>

                                </span>
                            </div> --}}
       <div class="checkout__item checkout__item--required" id="individualShippingPostal">
        <input type="text" wire:model="individual_billing_zipcode" name="individualShippingPostal"
         placeholder="Cod Poștal" autocomplete="postal-code" required>
        <span>
         @error('individual_billing_zipcode')
          {{ $message }}
         @enderror
        </span>
        <label for="individualShippingPostal">Cod Poștal</label>
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
      <div class="checkout__form @if (!$individual_identic && $individual) active @endif">
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
       {{-- <div class="checkout__item">
                                <input type="text" wire:model="individual_shipping_first" placeholder="Nume">
                                <span>

                                </span>
                            </div> --}}
       <div wire:ignore class="checkout__item checkout__item--required" id="individualBillingFirstName">
        <input type="text" wire:model="individual_shipping_first" name="individualBillingFirstName"
         placeholder="Nume" autocomplete="given-name" required>
        <span>
         @error('individual_shipping_first')
          {{ $message }}
         @enderror
        </span>
        <label for="individualBillingFirstName">Nume</label>
       </div>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item checkout__item--required" id="individualBillingLastName">
        <input type="text" wire:model="individual_shipping_last" name="individualBillingLastName"
         placeholder="Prenume" autocomplete="family-name" required>
        <span>
         @error('individual_shipping_last')
          {{ $message }}
         @enderror
        </span>
        <label for="individualBillingLastName">Prenume</label>
       </div>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item checkout__item--required" id="individualBillingEmail">
        <input type="email" wire:model="individual_shipping_phone" name="individualBillingEmail"
         placeholder="Email" autocomplete="email" required>
        <span>
         @error('individual_shipping_email')
          {{ $message }}
         @enderror
        </span>
        <label for="individualBillingEmail">Email</label>
       </div>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item checkout__item--required" id="individualBillingPhone">
        <input type="tel" wire:model="individual_shipping_email" name="individualBillingPhone"
         placeholder="Telefon" autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required>
        <span>
         @error('individual_shipping_phone')
          {{ $message }}
         @enderror
        </span>
        <label for="individualBillingPhone">Telefon</label>
       </div>
       <!----------- End Checkout List of Items ------------->
       <!---------------------------------------------------->
      </div>
      <!---------------------------------------------------->
      <div class="checkout__form @if (!$individual_identic && $individual) active @endif">
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
       <div wire:ignore class="checkout__item checkout__item--required" id="individualBillingAddress">
        <input type="text" wire:model="individual_shipping_address1" name="individualBillingAddress"
         placeholder="Adresa 1" autocomplete="street-address" required>
        <span>
         @error('individual_shipping_address1')
          {{ $message }}
         @enderror
        </span>
        <label for="individualBillingAddress">Adresa 1</label>
       </div>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item" id="individualBillingAddress2">
        <input type="text" wire:model="individual_shipping_address2" name="individualBillingAddress2"
         placeholder="Adresa 2 (optional)" autocomplete="address-level2">
        <span></span>
        <label for="individualBillingAddress2">Adresa 2 (opțional)</label>
       </div>
       <!---------------------------------------------------->
       <select wire:ignore wire:model="individual_shipping_country" class="select" aria-label="select a country">
        <option value="Afghanistan">Afghanistan</option>
        <option value="Åland Islands">Åland Islands</option>
        <option value="Albania">Albania</option>
        <option value="Algeria">Algeria</option>
        <option value="American Samoa">American Samoa</option>
        <option value="Andorra">Andorra</option>
        <option value="Angola">Angola</option>
        <option value="Anguilla">Anguilla</option>
        <option value="Antarctica">Antarctica</option>
        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
        <option value="Argentina">Argentina</option>
        <option value="Armenia">Armenia</option>
        <option value="Aruba">Aruba</option>
        <option value="Australia">Australia</option>
        <option value="Austria">Austria</option>
        <option value="Azerbaijan">Azerbaijan</option>
        <option value="Bahamas">Bahamas</option>
        <option value="Bahrain">Bahrain</option>
        <option value="Bangladesh">Bangladesh</option>
        <option value="Barbados">Barbados</option>
        <option value="Belarus">Belarus</option>
        <option value="Belgium">Belgium</option>
        <option value="Belize">Belize</option>
        <option value="Benin">Benin</option>
        <option value="Bermuda">Bermuda</option>
        <option value="Bhutan">Bhutan</option>
        <option value="Bolivia">Bolivia</option>
        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
        <option value="Botswana">Botswana</option>
        <option value="Bouvet Island">Bouvet Island</option>
        <option value="Brazil">Brazil</option>
        <option value="British Indian Ocean Territory">British Indian Ocean Territory
        </option>
        <option value="Brunei Darussalam">Brunei Darussalam</option>
        <option value="Bulgaria">Bulgaria</option>
        <option value="Burkina Faso">Burkina Faso</option>
        <option value="Burundi">Burundi</option>
        <option value="Cambodia">Cambodia</option>
        <option value="Cameroon">Cameroon</option>
        <option value="Canada">Canada</option>
        <option value="Cape Verde">Cape Verde</option>
        <option value="Cayman Islands">Cayman Islands</option>
        <option value="Central African Republic">Central African Republic</option>
        <option value="Chad">Chad</option>
        <option value="Chile">Chile</option>
        <option value="China">China</option>
        <option value="Christmas Island">Christmas Island</option>
        <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
        <option value="Colombia">Colombia</option>
        <option value="Comoros">Comoros</option>
        <option value="Congo">Congo</option>
        <option value="Congo, The Democratic Republic of the">Congo, The Democratic
         Republic of the</option>
        <option value="Cook Islands">Cook Islands</option>
        <option value="Costa Rica">Costa Rica</option>
        <option value="Croatia">Croatia</option>
        <option value="Cuba">Cuba</option>
        <option value="Cyprus">Cyprus</option>
        <option value="Czech Republic">Czech Republic</option>
        <option value="Denmark">Denmark</option>
        <option value="Djibouti">Djibouti</option>
        <option value="Dominica">Dominica</option>
        <option value="Dominican Republic">Dominican Republic</option>
        <option value="Ecuador">Ecuador</option>
        <option value="Egypt">Egypt</option>
        <option value="El Salvador">El Salvador</option>
        <option value="Equatorial Guinea">Equatorial Guinea</option>
        <option value="Eritrea">Eritrea</option>
        <option value="Estonia">Estonia</option>
        <option value="Ethiopia">Ethiopia</option>
        <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
        <option value="Faroe Islands">Faroe Islands</option>
        <option value="Fiji">Fiji</option>
        <option value="Finland">Finland</option>
        <option value="France">France</option>
        <option value="French Guiana">French Guiana</option>
        <option value="French Polynesia">French Polynesia</option>
        <option value="French Southern Territories">French Southern Territories</option>
        <option value="Gabon">Gabon</option>
        <option value="Gambia">Gambia</option>
        <option value="Georgia">Georgia</option>
        <option value="Germany">Germany</option>
        <option value="Ghana">Ghana</option>
        <option value="Gibraltar">Gibraltar</option>
        <option value="Greece">Greece</option>
        <option value="Greenland">Greenland</option>
        <option value="Grenada">Grenada</option>
        <option value="Guadeloupe">Guadeloupe</option>
        <option value="Guam">Guam</option>
        <option value="Guatemala">Guatemala</option>
        <option value="Guernsey">Guernsey</option>
        <option value="Guinea">Guinea</option>
        <option value="Guinea-Bissau">Guinea-Bissau</option>
        <option value="Guyana">Guyana</option>
        <option value="Haiti">Haiti</option>
        <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands
        </option>
        <option value="Holy See (Vatican City State)">Holy See (Vatican City State)
        </option>
        <option value="Honduras">Honduras</option>
        <option value="Hong Kong">Hong Kong</option>
        <option value="Hungary">Hungary</option>
        <option value="Iceland">Iceland</option>
        <option value="India">India</option>
        <option value="Indonesia">Indonesia</option>
        <option value="Iran, Islamic Republic Of">Iran, Islamic Republic Of</option>
        <option value="Iraq">Iraq</option>
        <option value="Ireland">Ireland</option>
        <option value="Isle of Man">Isle of Man</option>
        <option value="Israel">Israel</option>
        <option value="Italy">Italy</option>
        <option value="Jamaica">Jamaica</option>
        <option value="Japan">Japan</option>
        <option value="Jersey">Jersey</option>
        <option value="Jordan">Jordan</option>
        <option value="Kazakhstan">Kazakhstan</option>
        <option value="Kenya">Kenya</option>
        <option value="Kiribati">Kiribati</option>
        <option value="Korea, Republic of">Korea, Republic of</option>
        <option value="Kuwait">Kuwait</option>
        <option value="Kyrgyzstan">Kyrgyzstan</option>
        <option value="Latvia">Latvia</option>
        <option value="Lebanon">Lebanon</option>
        <option value="Lesotho">Lesotho</option>
        <option value="Liberia">Liberia</option>
        <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
        <option value="Liechtenstein">Liechtenstein</option>
        <option value="Lithuania">Lithuania</option>
        <option value="Luxembourg">Luxembourg</option>
        <option value="Macao">Macao</option>
        <option value="North Macedonia">North Macedonia</option>
        <option value="Madagascar">Madagascar</option>
        <option value="Malawi">Malawi</option>
        <option value="Malaysia">Malaysia</option>
        <option value="Maldives">Maldives</option>
        <option value="Mali">Mali</option>
        <option value="Malta">Malta</option>
        <option value="Marshall Islands">Marshall Islands</option>
        <option value="Martinique">Martinique</option>
        <option value="Mauritania">Mauritania</option>
        <option value="Mauritius">Mauritius</option>
        <option value="Mayotte">Mayotte</option>
        <option value="Mexico">Mexico</option>
        <option value="Micronesia, Federated States of">Micronesia, Federated States of
        </option>
        <option value="Republic of Moldova">Republic of Moldova</option>
        <option value="Monaco">Monaco</option>
        <option value="Mongolia">Mongolia</option>
        <option value="Montserrat">Montserrat</option>
        <option value="Morocco">Morocco</option>
        <option value="Mozambique">Mozambique</option>
        <option value="Myanmar">Myanmar</option>
        <option value="Namibia">Namibia</option>
        <option value="Nauru">Nauru</option>
        <option value="Nepal">Nepal</option>
        <option value="Netherlands">Netherlands</option>
        <option value="Netherlands Antilles">Netherlands Antilles</option>
        <option value="New Caledonia">New Caledonia</option>
        <option value="New Zealand">New Zealand</option>
        <option value="Nicaragua">Nicaragua</option>
        <option value="Niger">Niger</option>
        <option value="Nigeria">Nigeria</option>
        <option value="Niue">Niue</option>
        <option value="Norfolk Island">Norfolk Island</option>
        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
        <option value="Norway">Norway</option>
        <option value="Oman">Oman</option>
        <option value="Pakistan">Pakistan</option>
        <option value="Palau">Palau</option>
        <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied
        </option>
        <option value="Panama">Panama</option>
        <option value="Papua New Guinea">Papua New Guinea</option>
        <option value="Paraguay">Paraguay</option>
        <option value="Peru">Peru</option>
        <option value="Philippines">Philippines</option>
        <option value="Pitcairn Islands">Pitcairn Islands</option>
        <option value="Poland">Poland</option>
        <option value="Portugal">Portugal</option>
        <option value="Puerto Rico">Puerto Rico</option>
        <option value="Qatar">Qatar</option>
        <option value="Reunion">Reunion</option>
        <option value="Romania">Romania</option>
        <option value="Russian Federation">Russian Federation</option>
        <option value="Rwanda">Rwanda</option>
        <option value="Saint Helena">Saint Helena</option>
        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
        <option value="Saint Lucia">Saint Lucia</option>
        <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines
        </option>
        <option value="Samoa">Samoa</option>
        <option value="San Marino">San Marino</option>
        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
        <option value="Saudi Arabia">Saudi Arabia</option>
        <option value="Senegal">Senegal</option>
        <option value="Serbia and Montenegro">Serbia and Montenegro</option>
        <option value="Seychelles">Seychelles</option>
        <option value="Sierra Leone">Sierra Leone</option>
        <option value="Singapore">Singapore</option>
        <option value="Slovakia">Slovakia</option>
        <option value="Slovenia">Slovenia</option>
        <option value="Solomon Islands">Solomon Islands</option>
        <option value="Somalia">Somalia</option>
        <option value="South Africa">South Africa</option>
        <option value="South Georgia and the South Sandwich Islands">South Georgia and the
         South Sandwich Islands</option>
        <option value="Spain">Spain</option>
        <option value="Sri Lanka">Sri Lanka</option>
        <option value="Sudan">Sudan</option>
        <option value="Suriname">Suriname</option>
        <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
        <option value="Swaziland">Swaziland</option>
        <option value="Sweden">Sweden</option>
        <option value="Switzerland">Switzerland</option>
        <option value="Syrian Arab Republic">Syrian Arab Republic</option>
        <option value="Taiwan">Taiwan</option>
        <option value="Tajikistan">Tajikistan</option>
        <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
        <option value="Thailand">Thailand</option>
        <option value="Timor-Leste">Timor-Leste</option>
        <option value="Togo">Togo</option>
        <option value="Tokelau">Tokelau</option>
        <option value="Tonga">Tonga</option>
        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
        <option value="Tunisia">Tunisia</option>
        <option value="Turkey">Turkey</option>
        <option value="Turkmenistan">Turkmenistan</option>
        <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
        <option value="Tuvalu">Tuvalu</option>
        <option value="Uganda">Uganda</option>
        <option value="Ukraine">Ukraine</option>
        <option value="United Arab Emirates">United Arab Emirates</option>
        <option value="United Kingdom">United Kingdom</option>
        <option value="United States">United States</option>
        <option value="United States Minor Outlying Islands">United States Minor Outlying
         Islands</option>
        <option value="Uruguay">Uruguay</option>
        <option value="Uzbekistan">Uzbekistan</option>
        <option value="Vanuatu">Vanuatu</option>
        <option value="Venezuela">Venezuela</option>
        <option value="Vietnam">Vietnam</option>
        <option value="Virgin Islands, British">Virgin Islands, British</option>
        <option value="Virgin Islands, U.S"">Virgin Islands, U.S"</option>
        <option value="Wallis and Futuna">Wallis and Futuna</option>
        <option value="Western Sahara">Western Sahara</option>
        <option value="Yemen">Yemen</option>
        <option value="Zambia">Zambia</option>
        <option value="Zimbabwe">Zimbabwe</option>
       </select>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item checkout__item--required" id="individualBillingCounty">
        <input type="text" wire:model="individual_shipping_county" name="individualBillingCounty"
         placeholder="Localitate (oraș, comună sau sat)" autocomplete="county" required>
        <span></span>
        <label for="individualBillingCounty">Localitate (oraș, comună sau sat)</label>
       </div>
       <!---------------------------------------------------->

       <div wire:ignore class="checkout__item checkout__item--required" id="individualBillingCity">
        <input type="text" wire:model="individual_shipping_city" name="individualBillingCity" placeholder="Oraș"
         autocomplete="city" required>
        <span>
         @error('individual_shipping_city')
          {{ $message }}
         @enderror
        </span>
        <label for="individualBillingCity">Oraș</label>
       </div>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item checkout__item--required" id="individualBillingPostal">
        <input type="text" wire:model="individual_shipping_zipcode" name="individualBillingPostal"
         placeholder="Cod Poștal" autocomplete="postal-code" required>
        <span>
         @error('individual_shipping_zipcode')
          {{ $message }}
         @enderror
        </span>
        <label for="individualBillingPostal">Cod Poștal</label>
       </div>
       <!----------- End Checkout List of Items ------------->
       <!---------------------------------------------------->
      </div>
      <!------------ End Checkout List of Forms ------------>
      <!---------------------------------------------------->
     </div>
     <div class="checkout__container @if ($juridic) active @endif">
      <!---------------------------------------------------->
      <!-------------- Checkout List of Forms -------------->
      <div wire:ignore class="checkout__form active">
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
       <div class="checkout__item checkout__item--required" id="juridicShippingFirstName">
        <input type="text" wire:model="juridic_billing_first" name="juridicShippingFirstName" placeholder="Nume"
         autocomplete="given-name" required>
        <span>
         @error('juridic_billing_first')
          {{ $message }}
         @enderror
        </span>
        <label for="juridicShippingFirstName">Nume</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="juridicShippingLastName">
        <input type="text" wire:model="juridic_billing_last" name="juridicShippingLastName" placeholder="Prenume"
         autocomplete="family-name" required>
        <span>
         @error('juridic_billing_last')
          {{ $message }}
         @enderror
        </span>
        <label for="juridicShippingLastName">Prenume</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="juridicShippingPhone">
        <input type="tel" wire:model="juridic_billing_phone" name="juridicShippingPhone" placeholder="Telefon"
         autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required>
        <span>
         @error('juridic_billing_phone')
          {{ $message }}
         @enderror
        </span>
        <label for="juridicShippingPhone">Telefon</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="juridicShippingEmail">
        <input type="email" wire:model="juridic_billing_email" name="juridicShippingEmail" placeholder="Email"
         autocomplete="email" required>
        <span>
         @error('juridic_billing_email')
          {{ $message }}
         @enderror
        </span>
        <label for="juridicShippingEmail">Email</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="companyName">
        <input type="text" wire:model="juridic_billing_company_name" name="companyName"
         placeholder="Numele Companiei" autocomplete="organization" required>
        <span>
         @error('juridic_billing_company_name')
          {{ $message }}
         @enderror
        </span>
        <label for="companyName">Numele Companiei</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="registerCode">
        <input type="text" wire:model="juridic_billing_registration_code" name="registerCode"
         placeholder="Cod de înregistrare" autocomplete="organization-code" required>
        <span>
         @error('juridic_billing_registration_code')
          {{ $message }}
         @enderror
        </span>
        <label for="registerCode">Cod de înregistrare</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="registerNumber">
        <input type="text" wire:model="juridic_billing_registration_number" name="registerNumber"
         placeholder="Număr de înregistrare" autocomplete="organization-number" required>
        <span>
         @error('juridic_billing_registration_number')
          {{ $message }}
         @enderror
        </span>
        <label for="registerNumber">Număr de înregistrare</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item" id="bankName">
        <input type="text" wire:model="juridic_billing_bank" name="bankName" placeholder="Numele Băncii"
         autocomplete="bank-name">
        <span>
         @error('juridic_billing_bank')
          {{ $message }}
         @enderror
        </span>
        <label for="bankName">Numele Băncii</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item" id="IBAN">
        <input type="text" wire:model="juridic_billing_account" name="IBAN" placeholder="IBAN"
         autocomplete="IBAN">
        <span>
         @error('juridic_billing_account')
          {{ $message }}
         @enderror
        </span>
        <label for="IBAN">IBAN</label>
       </div>
       <!----------- End Checkout List of Items ------------->
       <!---------------------------------------------------->
      </div>
      <!---------------------------------------------------->
      <div wire:ignore class="checkout__form active">
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
       <div class="checkout__item checkout__item--required" id="juridicShippingAddress">
        <input type="text" wire:model="juridic_billing_address1" name="juridicShippingAddress"
         placeholder="Adresa 1" autocomplete="street-address" required>
        <span>
         @error('juridic_billing_address1')
          {{ $message }}
         @enderror
        </span>
        <label for="juridicShippingAddress">Adresa 1</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item" id="juridicShippingAddress2">
        <input type="text" wire:model="juridic_billing_address2" name="juridicShippingAddress2"
         placeholder="Adresa 2 (optional)" autocomplete="address-level2">
        <span></span>
        <label for="juridicShippingAddress2">Adresa 2 (optional)</label>
       </div>
       <!---------------------------------------------------->
       <select wire:model="juridic_billing_country" class="select" aria-label="select a country">
        <option value="Afghanistan">Afghanistan</option>
        <option value="Åland Islands">Åland Islands</option>
        <option value="Albania">Albania</option>
        <option value="Algeria">Algeria</option>
        <option value="American Samoa">American Samoa</option>
        <option value="Andorra">Andorra</option>
        <option value="Angola">Angola</option>
        <option value="Anguilla">Anguilla</option>
        <option value="Antarctica">Antarctica</option>
        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
        <option value="Argentina">Argentina</option>
        <option value="Armenia">Armenia</option>
        <option value="Aruba">Aruba</option>
        <option value="Australia">Australia</option>
        <option value="Austria">Austria</option>
        <option value="Azerbaijan">Azerbaijan</option>
        <option value="Bahamas">Bahamas</option>
        <option value="Bahrain">Bahrain</option>
        <option value="Bangladesh">Bangladesh</option>
        <option value="Barbados">Barbados</option>
        <option value="Belarus">Belarus</option>
        <option value="Belgium">Belgium</option>
        <option value="Belize">Belize</option>
        <option value="Benin">Benin</option>
        <option value="Bermuda">Bermuda</option>
        <option value="Bhutan">Bhutan</option>
        <option value="Bolivia">Bolivia</option>
        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
        <option value="Botswana">Botswana</option>
        <option value="Bouvet Island">Bouvet Island</option>
        <option value="Brazil">Brazil</option>
        <option value="British Indian Ocean Territory">British Indian Ocean Territory
        </option>
        <option value="Brunei Darussalam">Brunei Darussalam</option>
        <option value="Bulgaria">Bulgaria</option>
        <option value="Burkina Faso">Burkina Faso</option>
        <option value="Burundi">Burundi</option>
        <option value="Cambodia">Cambodia</option>
        <option value="Cameroon">Cameroon</option>
        <option value="Canada">Canada</option>
        <option value="Cape Verde">Cape Verde</option>
        <option value="Cayman Islands">Cayman Islands</option>
        <option value="Central African Republic">Central African Republic</option>
        <option value="Chad">Chad</option>
        <option value="Chile">Chile</option>
        <option value="China">China</option>
        <option value="Christmas Island">Christmas Island</option>
        <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
        <option value="Colombia">Colombia</option>
        <option value="Comoros">Comoros</option>
        <option value="Congo">Congo</option>
        <option value="Congo, The Democratic Republic of the">Congo, The Democratic
         Republic of the</option>
        <option value="Cook Islands">Cook Islands</option>
        <option value="Costa Rica">Costa Rica</option>
        <option value="Croatia">Croatia</option>
        <option value="Cuba">Cuba</option>
        <option value="Cyprus">Cyprus</option>
        <option value="Czech Republic">Czech Republic</option>
        <option value="Denmark">Denmark</option>
        <option value="Djibouti">Djibouti</option>
        <option value="Dominica">Dominica</option>
        <option value="Dominican Republic">Dominican Republic</option>
        <option value="Ecuador">Ecuador</option>
        <option value="Egypt">Egypt</option>
        <option value="El Salvador">El Salvador</option>
        <option value="Equatorial Guinea">Equatorial Guinea</option>
        <option value="Eritrea">Eritrea</option>
        <option value="Estonia">Estonia</option>
        <option value="Ethiopia">Ethiopia</option>
        <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
        <option value="Faroe Islands">Faroe Islands</option>
        <option value="Fiji">Fiji</option>
        <option value="Finland">Finland</option>
        <option value="France">France</option>
        <option value="French Guiana">French Guiana</option>
        <option value="French Polynesia">French Polynesia</option>
        <option value="French Southern Territories">French Southern Territories</option>
        <option value="Gabon">Gabon</option>
        <option value="Gambia">Gambia</option>
        <option value="Georgia">Georgia</option>
        <option value="Germany">Germany</option>
        <option value="Ghana">Ghana</option>
        <option value="Gibraltar">Gibraltar</option>
        <option value="Greece">Greece</option>
        <option value="Greenland">Greenland</option>
        <option value="Grenada">Grenada</option>
        <option value="Guadeloupe">Guadeloupe</option>
        <option value="Guam">Guam</option>
        <option value="Guatemala">Guatemala</option>
        <option value="Guernsey">Guernsey</option>
        <option value="Guinea">Guinea</option>
        <option value="Guinea-Bissau">Guinea-Bissau</option>
        <option value="Guyana">Guyana</option>
        <option value="Haiti">Haiti</option>
        <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands
        </option>
        <option value="Holy See (Vatican City State)">Holy See (Vatican City State)
        </option>
        <option value="Honduras">Honduras</option>
        <option value="Hong Kong">Hong Kong</option>
        <option value="Hungary">Hungary</option>
        <option value="Iceland">Iceland</option>
        <option value="India">India</option>
        <option value="Indonesia">Indonesia</option>
        <option value="Iran, Islamic Republic Of">Iran, Islamic Republic Of</option>
        <option value="Iraq">Iraq</option>
        <option value="Ireland">Ireland</option>
        <option value="Isle of Man">Isle of Man</option>
        <option value="Israel">Israel</option>
        <option value="Italy">Italy</option>
        <option value="Jamaica">Jamaica</option>
        <option value="Japan">Japan</option>
        <option value="Jersey">Jersey</option>
        <option value="Jordan">Jordan</option>
        <option value="Kazakhstan">Kazakhstan</option>
        <option value="Kenya">Kenya</option>
        <option value="Kiribati">Kiribati</option>
        <option value="Korea, Republic of">Korea, Republic of</option>
        <option value="Kuwait">Kuwait</option>
        <option value="Kyrgyzstan">Kyrgyzstan</option>
        <option value="Latvia">Latvia</option>
        <option value="Lebanon">Lebanon</option>
        <option value="Lesotho">Lesotho</option>
        <option value="Liberia">Liberia</option>
        <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
        <option value="Liechtenstein">Liechtenstein</option>
        <option value="Lithuania">Lithuania</option>
        <option value="Luxembourg">Luxembourg</option>
        <option value="Macao">Macao</option>
        <option value="North Macedonia">North Macedonia</option>
        <option value="Madagascar">Madagascar</option>
        <option value="Malawi">Malawi</option>
        <option value="Malaysia">Malaysia</option>
        <option value="Maldives">Maldives</option>
        <option value="Mali">Mali</option>
        <option value="Malta">Malta</option>
        <option value="Marshall Islands">Marshall Islands</option>
        <option value="Martinique">Martinique</option>
        <option value="Mauritania">Mauritania</option>
        <option value="Mauritius">Mauritius</option>
        <option value="Mayotte">Mayotte</option>
        <option value="Mexico">Mexico</option>
        <option value="Micronesia, Federated States of">Micronesia, Federated States of
        </option>
        <option value="Republic of Moldova">Republic of Moldova</option>
        <option value="Monaco">Monaco</option>
        <option value="Mongolia">Mongolia</option>
        <option value="Montserrat">Montserrat</option>
        <option value="Morocco">Morocco</option>
        <option value="Mozambique">Mozambique</option>
        <option value="Myanmar">Myanmar</option>
        <option value="Namibia">Namibia</option>
        <option value="Nauru">Nauru</option>
        <option value="Nepal">Nepal</option>
        <option value="Netherlands">Netherlands</option>
        <option value="Netherlands Antilles">Netherlands Antilles</option>
        <option value="New Caledonia">New Caledonia</option>
        <option value="New Zealand">New Zealand</option>
        <option value="Nicaragua">Nicaragua</option>
        <option value="Niger">Niger</option>
        <option value="Nigeria">Nigeria</option>
        <option value="Niue">Niue</option>
        <option value="Norfolk Island">Norfolk Island</option>
        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
        <option value="Norway">Norway</option>
        <option value="Oman">Oman</option>
        <option value="Pakistan">Pakistan</option>
        <option value="Palau">Palau</option>
        <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied
        </option>
        <option value="Panama">Panama</option>
        <option value="Papua New Guinea">Papua New Guinea</option>
        <option value="Paraguay">Paraguay</option>
        <option value="Peru">Peru</option>
        <option value="Philippines">Philippines</option>
        <option value="Pitcairn Islands">Pitcairn Islands</option>
        <option value="Poland">Poland</option>
        <option value="Portugal">Portugal</option>
        <option value="Puerto Rico">Puerto Rico</option>
        <option value="Qatar">Qatar</option>
        <option value="Reunion">Reunion</option>
        <option value="Romania">Romania</option>
        <option value="Russian Federation">Russian Federation</option>
        <option value="Rwanda">Rwanda</option>
        <option value="Saint Helena">Saint Helena</option>
        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
        <option value="Saint Lucia">Saint Lucia</option>
        <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines
        </option>
        <option value="Samoa">Samoa</option>
        <option value="San Marino">San Marino</option>
        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
        <option value="Saudi Arabia">Saudi Arabia</option>
        <option value="Senegal">Senegal</option>
        <option value="Serbia and Montenegro">Serbia and Montenegro</option>
        <option value="Seychelles">Seychelles</option>
        <option value="Sierra Leone">Sierra Leone</option>
        <option value="Singapore">Singapore</option>
        <option value="Slovakia">Slovakia</option>
        <option value="Slovenia">Slovenia</option>
        <option value="Solomon Islands">Solomon Islands</option>
        <option value="Somalia">Somalia</option>
        <option value="South Africa">South Africa</option>
        <option value="South Georgia and the South Sandwich Islands">South Georgia and the
         South Sandwich Islands</option>
        <option value="Spain">Spain</option>
        <option value="Sri Lanka">Sri Lanka</option>
        <option value="Sudan">Sudan</option>
        <option value="Suriname">Suriname</option>
        <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
        <option value="Swaziland">Swaziland</option>
        <option value="Sweden">Sweden</option>
        <option value="Switzerland">Switzerland</option>
        <option value="Syrian Arab Republic">Syrian Arab Republic</option>
        <option value="Taiwan">Taiwan</option>
        <option value="Tajikistan">Tajikistan</option>
        <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
        <option value="Thailand">Thailand</option>
        <option value="Timor-Leste">Timor-Leste</option>
        <option value="Togo">Togo</option>
        <option value="Tokelau">Tokelau</option>
        <option value="Tonga">Tonga</option>
        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
        <option value="Tunisia">Tunisia</option>
        <option value="Turkey">Turkey</option>
        <option value="Turkmenistan">Turkmenistan</option>
        <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
        <option value="Tuvalu">Tuvalu</option>
        <option value="Uganda">Uganda</option>
        <option value="Ukraine">Ukraine</option>
        <option value="United Arab Emirates">United Arab Emirates</option>
        <option value="United Kingdom">United Kingdom</option>
        <option value="United States">United States</option>
        <option value="United States Minor Outlying Islands">United States Minor Outlying
         Islands</option>
        <option value="Uruguay">Uruguay</option>
        <option value="Uzbekistan">Uzbekistan</option>
        <option value="Vanuatu">Vanuatu</option>
        <option value="Venezuela">Venezuela</option>
        <option value="Vietnam">Vietnam</option>
        <option value="Virgin Islands, British">Virgin Islands, British</option>
        <option value="Virgin Islands, U.S"">Virgin Islands, U.S"</option>
        <option value="Wallis and Futuna">Wallis and Futuna</option>
        <option value="Western Sahara">Western Sahara</option>
        <option value="Yemen">Yemen</option>
        <option value="Zambia">Zambia</option>
        <option value="Zimbabwe">Zimbabwe</option>
       </select>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="juridicShippingCounty">
        <input type="text" wire:model="juridic_billing_county" name="juridicShippingCounty"
         placeholder="Localitate (oraș, comună sau sat)" autocomplete="county" required>
        <span></span>
        <label for="juridicShippingCounty">Localitate (oraș, comună sau sat)</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="juridicShippingCity">
        <input type="text" wire:model="juridic_billing_city" name="juridicShippingCity" placeholder="Oraș"
         autocomplete="city" required>
        <span>
         @error('juridic_billing_city')
          {{ $message }}
         @enderror
        </span>
        <label for="juridicShippingCity">Oraș</label>
       </div>
       <!---------------------------------------------------->
       <div class="checkout__item checkout__item--required" id="juridicShippingPostal">
        <input type="text" wire:model="juridic_billing_zipcode" name="juridicShippingPostal"
         placeholder="Cod Poștal" autocomplete="postal-code" required>
        <span>
         @error('juridic_billing_zipcode')
          {{ $message }}
         @enderror
        </span>
        <label for="juridicShippingPostal">Cod Poștal</label>
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
      <div class="checkout__form @if (!$juridic_identic && $juridic) active @endif">
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
       <div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingFirstName">
        <input type="text" wire:model="juridic_shipping_first" name="juridicBillingFirstName"
         placeholder="Nume" autocomplete="given-name" required>
        <span>
         @error('juridic_shipping_first')
          {{ $message }}
         @enderror
        </span>
        <label for="juridicBillingFirstName">Nume</label>
       </div>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingLastName">
        <input type="text" wire:model="juridic_shipping_last" name="juridicBillingLastName"
         placeholder="Prenume" autocomplete="family-name" required>
        <span>
         @error('juridic_shipping_last')
          {{ $message }}
         @enderror
        </span>
        <label for="juridicBillingLastName">Prenume</label>
       </div>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingPhone">
        <input type="tel" wire:model="juridic_shipping_phone" name="juridicBillingPhone"
         placeholder="Telefon" autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required>
        <span>
         @error('juridic_shipping_phone')
          {{ $message }}
         @enderror
        </span>
        <label for="juridicBillingPhone">Telefon</label>
       </div>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingEmail">
        <input type="email" wire:model="juridic_shipping_email" name="juridicBillingEmail" placeholder="Email"
         autocomplete="email" required>
        <span>
         @error('juridic_shipping_email')
          {{ $message }}
         @enderror
        </span>
        <label for="juridicBillingEmail">Email</label>
       </div>
       <!----------- End Checkout List of Items ------------->
       <!---------------------------------------------------->
      </div>
      <!---------------------------------------------------->
      <div class="checkout__form @if (!$juridic_identic && $juridic) active @endif"">
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
       <div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingAddress">
        <input type="text" wire:model="juridic_shipping_address1" name="juridicBillingAddress"
         placeholder="Adresa 1" autocomplete="street-address" required>
        <span></span>
        <label for="juridicBillingAddress">Adresa</label>
       </div>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item" id="juridicBillingAddress2">
        <input type="text" wire:model="juridic_shipping_address2" name="juridicBillingAddress2"
         placeholder="Adresa 2 (Opțional)" autocomplete="address-level2">
        <span></span>
        <label for="juridicBillingAddress2">Adresa 2 (Opțional)</label>
       </div>
       <!---------------------------------------------------->
       <select wire:ignore wire:model="juridic_shipping_country" class="select" aria-label="select a country">
        <option value="Afghanistan">Afghanistan</option>
        <option value="Åland Islands">Åland Islands</option>
        <option value="Albania">Albania</option>
        <option value="Algeria">Algeria</option>
        <option value="American Samoa">American Samoa</option>
        <option value="Andorra">Andorra</option>
        <option value="Angola">Angola</option>
        <option value="Anguilla">Anguilla</option>
        <option value="Antarctica">Antarctica</option>
        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
        <option value="Argentina">Argentina</option>
        <option value="Armenia">Armenia</option>
        <option value="Aruba">Aruba</option>
        <option value="Australia">Australia</option>
        <option value="Austria">Austria</option>
        <option value="Azerbaijan">Azerbaijan</option>
        <option value="Bahamas">Bahamas</option>
        <option value="Bahrain">Bahrain</option>
        <option value="Bangladesh">Bangladesh</option>
        <option value="Barbados">Barbados</option>
        <option value="Belarus">Belarus</option>
        <option value="Belgium">Belgium</option>
        <option value="Belize">Belize</option>
        <option value="Benin">Benin</option>
        <option value="Bermuda">Bermuda</option>
        <option value="Bhutan">Bhutan</option>
        <option value="Bolivia">Bolivia</option>
        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
        <option value="Botswana">Botswana</option>
        <option value="Bouvet Island">Bouvet Island</option>
        <option value="Brazil">Brazil</option>
        <option value="British Indian Ocean Territory">British Indian Ocean Territory
        </option>
        <option value="Brunei Darussalam">Brunei Darussalam</option>
        <option value="Bulgaria">Bulgaria</option>
        <option value="Burkina Faso">Burkina Faso</option>
        <option value="Burundi">Burundi</option>
        <option value="Cambodia">Cambodia</option>
        <option value="Cameroon">Cameroon</option>
        <option value="Canada">Canada</option>
        <option value="Cape Verde">Cape Verde</option>
        <option value="Cayman Islands">Cayman Islands</option>
        <option value="Central African Republic">Central African Republic</option>
        <option value="Chad">Chad</option>
        <option value="Chile">Chile</option>
        <option value="China">China</option>
        <option value="Christmas Island">Christmas Island</option>
        <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
        <option value="Colombia">Colombia</option>
        <option value="Comoros">Comoros</option>
        <option value="Congo">Congo</option>
        <option value="Congo, The Democratic Republic of the">Congo, The Democratic
         Republic of the</option>
        <option value="Cook Islands">Cook Islands</option>
        <option value="Costa Rica">Costa Rica</option>
        <option value="Croatia">Croatia</option>
        <option value="Cuba">Cuba</option>
        <option value="Cyprus">Cyprus</option>
        <option value="Czech Republic">Czech Republic</option>
        <option value="Denmark">Denmark</option>
        <option value="Djibouti">Djibouti</option>
        <option value="Dominica">Dominica</option>
        <option value="Dominican Republic">Dominican Republic</option>
        <option value="Ecuador">Ecuador</option>
        <option value="Egypt">Egypt</option>
        <option value="El Salvador">El Salvador</option>
        <option value="Equatorial Guinea">Equatorial Guinea</option>
        <option value="Eritrea">Eritrea</option>
        <option value="Estonia">Estonia</option>
        <option value="Ethiopia">Ethiopia</option>
        <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
        <option value="Faroe Islands">Faroe Islands</option>
        <option value="Fiji">Fiji</option>
        <option value="Finland">Finland</option>
        <option value="France">France</option>
        <option value="French Guiana">French Guiana</option>
        <option value="French Polynesia">French Polynesia</option>
        <option value="French Southern Territories">French Southern Territories</option>
        <option value="Gabon">Gabon</option>
        <option value="Gambia">Gambia</option>
        <option value="Georgia">Georgia</option>
        <option value="Germany">Germany</option>
        <option value="Ghana">Ghana</option>
        <option value="Gibraltar">Gibraltar</option>
        <option value="Greece">Greece</option>
        <option value="Greenland">Greenland</option>
        <option value="Grenada">Grenada</option>
        <option value="Guadeloupe">Guadeloupe</option>
        <option value="Guam">Guam</option>
        <option value="Guatemala">Guatemala</option>
        <option value="Guernsey">Guernsey</option>
        <option value="Guinea">Guinea</option>
        <option value="Guinea-Bissau">Guinea-Bissau</option>
        <option value="Guyana">Guyana</option>
        <option value="Haiti">Haiti</option>
        <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald
         Islands</option>
        <option value="Holy See (Vatican City State)">Holy See (Vatican City State)
        </option>
        <option value="Honduras">Honduras</option>
        <option value="Hong Kong">Hong Kong</option>
        <option value="Hungary">Hungary</option>
        <option value="Iceland">Iceland</option>
        <option value="India">India</option>
        <option value="Indonesia">Indonesia</option>
        <option value="Iran, Islamic Republic Of">Iran, Islamic Republic Of</option>
        <option value="Iraq">Iraq</option>
        <option value="Ireland">Ireland</option>
        <option value="Isle of Man">Isle of Man</option>
        <option value="Israel">Israel</option>
        <option value="Italy">Italy</option>
        <option value="Jamaica">Jamaica</option>
        <option value="Japan">Japan</option>
        <option value="Jersey">Jersey</option>
        <option value="Jordan">Jordan</option>
        <option value="Kazakhstan">Kazakhstan</option>
        <option value="Kenya">Kenya</option>
        <option value="Kiribati">Kiribati</option>
        <option value="Korea, Republic of">Korea, Republic of</option>
        <option value="Kuwait">Kuwait</option>
        <option value="Kyrgyzstan">Kyrgyzstan</option>
        <option value="Latvia">Latvia</option>
        <option value="Lebanon">Lebanon</option>
        <option value="Lesotho">Lesotho</option>
        <option value="Liberia">Liberia</option>
        <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
        <option value="Liechtenstein">Liechtenstein</option>
        <option value="Lithuania">Lithuania</option>
        <option value="Luxembourg">Luxembourg</option>
        <option value="Macao">Macao</option>
        <option value="North Macedonia">North Macedonia</option>
        <option value="Madagascar">Madagascar</option>
        <option value="Malawi">Malawi</option>
        <option value="Malaysia">Malaysia</option>
        <option value="Maldives">Maldives</option>
        <option value="Mali">Mali</option>
        <option value="Malta">Malta</option>
        <option value="Marshall Islands">Marshall Islands</option>
        <option value="Martinique">Martinique</option>
        <option value="Mauritania">Mauritania</option>
        <option value="Mauritius">Mauritius</option>
        <option value="Mayotte">Mayotte</option>
        <option value="Mexico">Mexico</option>
        <option value="Micronesia, Federated States of">Micronesia, Federated States of
        </option>
        <option value="Republic of Moldova">Republic of Moldova</option>
        <option value="Monaco">Monaco</option>
        <option value="Mongolia">Mongolia</option>
        <option value="Montserrat">Montserrat</option>
        <option value="Morocco">Morocco</option>
        <option value="Mozambique">Mozambique</option>
        <option value="Myanmar">Myanmar</option>
        <option value="Namibia">Namibia</option>
        <option value="Nauru">Nauru</option>
        <option value="Nepal">Nepal</option>
        <option value="Netherlands">Netherlands</option>
        <option value="Netherlands Antilles">Netherlands Antilles</option>
        <option value="New Caledonia">New Caledonia</option>
        <option value="New Zealand">New Zealand</option>
        <option value="Nicaragua">Nicaragua</option>
        <option value="Niger">Niger</option>
        <option value="Nigeria">Nigeria</option>
        <option value="Niue">Niue</option>
        <option value="Norfolk Island">Norfolk Island</option>
        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
        <option value="Norway">Norway</option>
        <option value="Oman">Oman</option>
        <option value="Pakistan">Pakistan</option>
        <option value="Palau">Palau</option>
        <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied
        </option>
        <option value="Panama">Panama</option>
        <option value="Papua New Guinea">Papua New Guinea</option>
        <option value="Paraguay">Paraguay</option>
        <option value="Peru">Peru</option>
        <option value="Philippines">Philippines</option>
        <option value="Pitcairn Islands">Pitcairn Islands</option>
        <option value="Poland">Poland</option>
        <option value="Portugal">Portugal</option>
        <option value="Puerto Rico">Puerto Rico</option>
        <option value="Qatar">Qatar</option>
        <option value="Reunion">Reunion</option>
        <option value="Romania">Romania</option>
        <option value="Russian Federation">Russian Federation</option>
        <option value="Rwanda">Rwanda</option>
        <option value="Saint Helena">Saint Helena</option>
        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
        <option value="Saint Lucia">Saint Lucia</option>
        <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines
        </option>
        <option value="Samoa">Samoa</option>
        <option value="San Marino">San Marino</option>
        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
        <option value="Saudi Arabia">Saudi Arabia</option>
        <option value="Senegal">Senegal</option>
        <option value="Serbia and Montenegro">Serbia and Montenegro</option>
        <option value="Seychelles">Seychelles</option>
        <option value="Sierra Leone">Sierra Leone</option>
        <option value="Singapore">Singapore</option>
        <option value="Slovakia">Slovakia</option>
        <option value="Slovenia">Slovenia</option>
        <option value="Solomon Islands">Solomon Islands</option>
        <option value="Somalia">Somalia</option>
        <option value="South Africa">South Africa</option>
        <option value="South Georgia and the South Sandwich Islands">South Georgia and the
         South Sandwich Islands</option>
        <option value="Spain">Spain</option>
        <option value="Sri Lanka">Sri Lanka</option>
        <option value="Sudan">Sudan</option>
        <option value="Suriname">Suriname</option>
        <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
        <option value="Swaziland">Swaziland</option>
        <option value="Sweden">Sweden</option>
        <option value="Switzerland">Switzerland</option>
        <option value="Syrian Arab Republic">Syrian Arab Republic</option>
        <option value="Taiwan">Taiwan</option>
        <option value="Tajikistan">Tajikistan</option>
        <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
        <option value="Thailand">Thailand</option>
        <option value="Timor-Leste">Timor-Leste</option>
        <option value="Togo">Togo</option>
        <option value="Tokelau">Tokelau</option>
        <option value="Tonga">Tonga</option>
        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
        <option value="Tunisia">Tunisia</option>
        <option value="Turkey">Turkey</option>
        <option value="Turkmenistan">Turkmenistan</option>
        <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
        <option value="Tuvalu">Tuvalu</option>
        <option value="Uganda">Uganda</option>
        <option value="Ukraine">Ukraine</option>
        <option value="United Arab Emirates">United Arab Emirates</option>
        <option value="United Kingdom">United Kingdom</option>
        <option value="United States">United States</option>
        <option value="United States Minor Outlying Islands">United States Minor Outlying
         Islands</option>
        <option value="Uruguay">Uruguay</option>
        <option value="Uzbekistan">Uzbekistan</option>
        <option value="Vanuatu">Vanuatu</option>
        <option value="Venezuela">Venezuela</option>
        <option value="Vietnam">Vietnam</option>
        <option value="Virgin Islands, British">Virgin Islands, British</option>
        <option value="Virgin Islands, U.S"">Virgin Islands, U.S"</option>
        <option value="Wallis and Futuna">Wallis and Futuna</option>
        <option value="Western Sahara">Western Sahara</option>
        <option value="Yemen">Yemen</option>
        <option value="Zambia">Zambia</option>
        <option value="Zimbabwe">Zimbabwe</option>
       </select>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingCounty">
        <input type="text" wire:model="juridic_shipping_county" name="juridicBillingCounty"
         placeholder="Localitate (oraș, comună sau sat)" autocomplete="county" required>
        <span></span>
        <label for="juridicBillingCounty">Localitate (oraș, comună sau sat)</label>
       </div>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingCity">
        <input type="text" wire:model="juridic_shipping_city" name="juridicBillingCity" placeholder="Oraș"
         autocomplete="city" required>
        <span></span>
        <label for="juridicBillingCity">Oraș</label>
       </div>
       <!---------------------------------------------------->
       <div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingPostal">
        <input type="text" wire:model="juridic_shipping_zipcode" name="juridicBillingPostal"
         placeholder="Cod Poștal" autocomplete="postal-code" required>
        <span></span>
        <label for="juridicBillingPostal">Cod Poștal</label>
       </div>
       <!----------- End Checkout List of Items ------------->
       <!---------------------------------------------------->
      </div>
      <!------------ End Checkout List of Forms ------------>
      <!---------------------------------------------------->
     </div>
     <div class="section__header">
      <h2 class="section__title">Metoda de plata</h2>
     </div>
     @if ($cash['active'] != 0)
      <div class="payment">
       <label class="payment__wrapper" for="rtc" wire:click="togglepayment('rtc')">
        <input class="payment__checkbox" type="checkbox" wire:model.defer="rtc" id="rtc">
        <span>{{ $cash['description'] }}</span>
       </label>
       <div class="payment__text @if ($rtc) active @endif">
        <h3>Veți plăti când comanda va fi livrată.</h3>
        <span>Limita maxima este de 1000 RON</span>
       </div>
      </div>
     @endif
     @if ($card['active'] != 0)
      <div class="payment">
       <label class="payment__wrapper" for="crd" wire:click="togglepayment('crd')">
        <input class="payment__checkbox" type="checkbox" wire:model.defer="crd" id="crd">
        <span>{{ $card['description'] }}</span>
       </label>
       <div class="payment__text @if ($crd) active @endif">
        <h3>Veți plăti online cu cardul la finalizarea comenzii.</h3>
       </div>
      </div>
     @endif
     @if ($ordin['active'] != 0)
      @if ($juridic)
       <div class="payment">
        <label class="payment__wrapper" for="invoice" wire:click="togglepayment('invoice')">
         <input class="payment__checkbox" type="checkbox" wire:model.defer="invoice" id="invoice">
         <span>{{ $ordin['description'] }}</span>
        </label>
        <div class="payment__text @if ($invoice) active @endif">
         <h3>
          Metoda de plată utilizată de entitățile legale. După plasarea comenzii,
          veți primi prin e-mail factura proformă cu toate detaliile de plată.
         </h3>
        </div>

       </div>
      @endif
     @endif
     <script src="/script/store/order.js"></script>
     <script>
      applyValidations("individualShippingFirstName", firstNameValidation, false);
      applyValidations("individualShippingLastName", lastNameValidation, false);
      applyValidations("individualShippingEmail", emailValidation, false);
      applyValidations("individualShippingPhone", phoneValidation, false);
      applyValidations("individualShippingAddress", addressValidations, false);
      applyValidations("individualShippingCounty", addressValidations, false);
      applyValidations("individualShippingCity", addressValidations, false);
      applyValidations("individualShippingPostal", addressValidations, false);
      applyValidations("individualBillingFirstName", firstNameValidation, false);
      applyValidations("individualBillingLastName", lastNameValidation, false);
      applyValidations("individualBillingEmail", emailValidation, false);
      applyValidations("individualBillingPhone", phoneValidation, false);
      applyValidations("individualBillingAddress", addressValidations, false);
      applyValidations("individualBillingCounty", addressValidations, false);
      applyValidations("individualBillingCity", addressValidations, false);
      applyValidations("individualBillingPostal", addressValidations, false);
      // ----------------------------------------------------------------------------
      applyValidations("juridicShippingFirstName", firstNameValidation, false);
      applyValidations("juridicShippingLastName", lastNameValidation, false);
      applyValidations("juridicShippingEmail", emailValidation, false);
      applyValidations("juridicShippingPhone", phoneValidation, false);
      applyValidations("juridicShippingAddress", addressValidations, false);
      applyValidations("juridicShippingCounty", addressValidations, false);
      applyValidations("juridicShippingCity", addressValidations, false);
      applyValidations("juridicShippingPostal", addressValidations, false);
      applyValidations("companyName", registerCode, false);
      applyValidations("registerNumber", companyName, false);
      applyValidations("registerCode", registerNumber, false);
      applyValidations("juridicBillingFirstName", firstNameValidation, false);
      applyValidations("juridicBillingLastName", lastNameValidation, false);
      applyValidations("juridicBillingEmail", emailValidation, false);
      applyValidations("juridicBillingPhone", phoneValidation, false);
      applyValidations("juridicBillingAddress", addressValidations, false);
      applyValidations("juridicBillingCounty", addressValidations, false);
      applyValidations("juridicBillingCity", addressValidations, false);
      applyValidations("juridicBillingPostal", addressValidations, false);
     </script>
    @endif
    <!------------------ End Step First -------------------->
    <!------------------------------------------------------>
    <!--------------------- Step Middle -------------------->
    @if ($step == 2)
     <div class="checkout__header">
      <button class="checkout__button" wire:click.prevent="previous()">
       <svg>
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
       </svg>Pasul anterior
      </button>
      <button class="checkout__button checkout__button--confirm" wire:click.prevent="confirm()">
       Confirmă <svg>
        <polyline points="9 11 12 14 22 4"></polyline>
        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
       </svg>
      </button>
     </div>
     <div class="section__header">
      <h2 class="section__title">Verificați detaliile dumneavoastră.</h2>
     </div>
     <div class="total__container">
      <!---------------------------------------------------->
      <!-------------- Checkout List of Forms -------------->
      @if ($individual)
       <div class="look__form">
        <!---------------------------------------------------->
        <!------------- Checkout Header Name --------------->
        <h3>
         Informatii de facturare &check;
        </h3>
        <!----------- End Checkout Header Name ------------->
        <!---------------------------------------------------->
        <!------------- Checkout List of Items --------------->
        <span class="total__message">Nume si Prenume:
         <strong>{{ $individual_billing_first }}</strong>
         <strong>{{ $individual_billing_last }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Telefon:
         <strong>{{ $individual_billing_phone }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Email:
         <strong>{{ $individual_billing_email }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Adresa:
         <strong>{{ $individual_billing_address1 }}</strong>
         <strong>{{ $individual_billing_address2 }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Tara:
         <strong>{{ $individual_billing_country }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Judet:
         <strong>{{ $individual_billing_county }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Localitate (oras, comună sau sat):
         <strong>{{ $individual_billing_city }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Cod Postal:
         <strong>{{ $individual_billing_zipcode }}</strong></span>
        <!----------- End Checkout List of Items ------------->
        <!---------------------------------------------------->
        <!---------------------------------------------------->
        <!------------- Checkout Header Name --------------->
        <h3>
         Informatii de livrare &check;
        </h3>
        <!----------- End Checkout Header Name ------------->
        <!---------------------------------------------------->
        <!------------- Checkout List of Items --------------->
        <span class="total__message">Nume si Prenume:
         <strong>{{ $individual_shipping_first }}</strong>
         <strong>{{ $individual_shipping_last }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Telefon:
         <strong>{{ $individual_shipping_phone }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Email:
         <strong>{{ $individual_shipping_email }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Adresa:
         <strong>{{ $individual_shipping_address1 }}</strong>
         <strong>{{ $individual_shipping_address2 }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Tara:
         <strong>{{ $individual_shipping_country }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Judet:
         <strong>{{ $individual_shipping_county }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Localitate (oras, comună sau sat):
         <strong>{{ $individual_shipping_city }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Cod Postal:
         <strong>{{ $individual_shipping_zipcode }}</strong></span>
        <!----------- End Checkout List of Items ------------->
        <!---------------------------------------------------->
       </div>
      @endif
      <!---------------------------------------------------->
      @if ($juridic)
       <div class="checkout__form">
        <!---------------------------------------------------->
        <!------------- Checkout Header Name --------------->
        <h3>
         Informatii de facturare &check;
        </h3>
        <!----------- End Checkout Header Name ------------->
        <!---------------------------------------------------->
        <!------------- Checkout List of Items --------------->
        <span class="total__message">Nume si Prenume:
         <strong>{{ $juridic_billing_first }}</strong>
         <strong>{{ $juridic_billing_last }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Telefon:
         <strong>{{ $juridic_billing_phone }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Email:
         <strong>{{ $juridic_billing_email }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Companie:
         <strong>{{ $juridic_billing_company_name }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Cod de înregistrare:
         <strong>{{ $juridic_billing_registration_code }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Număr de înregistrare:
         <strong>{{ $juridic_billing_registration_number }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Denumirea Bancii:
         <strong>{{ $juridic_billing_bank }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">COnt IBAN:
         <strong>{{ $juridic_billing_account }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Adresa:
         <strong>{{ $juridic_billing_address1 }}</strong>
         <strong>{{ $juridic_billing_address2 }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Tara:
         <strong>{{ $juridic_billing_country }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Judet:
         <strong>{{ $juridic_billing_county }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Localitate (oras, comună sau sat):
         <strong>{{ $juridic_billing_city }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Cod Postal:
         <strong>{{ $juridic_billing_zipcode }}</strong></span>
        <!----------- End Checkout List of Items ------------->
        <!---------------------------------------------------->
        <!---------------------------------------------------->
        <!------------- Checkout Header Name --------------->
        <h3>
         Informatii de livrare &check;
        </h3>
        <!----------- End Checkout Header Name ------------->
        <!---------------------------------------------------->
        <!------------- Checkout List of Items --------------->
        <span class="total__message">Nume si Prenume:
         <strong>{{ $juridic_shipping_first }}</strong>
         <strong>{{ $juridic_shipping_last }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">telefon:
         <strong>{{ $juridic_shipping_phone }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Email:
         <strong>{{ $juridic_shipping_email }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Adresa:
         <strong>{{ $juridic_shipping_address1 }}</strong>
         <strong>{{ $juridic_shipping_address2 }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Tara:
         <strong>{{ $juridic_shipping_country }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Judet:
         <strong>{{ $juridic_shipping_county }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Localitate (oras, comună sau sat):
         <strong>{{ $juridic_shipping_city }}</strong></span>
        <!---------------------------------------------------->
        <span class="total__message">Cod Postal:
         <strong>{{ $juridic_shipping_zipcode }}</strong></span>
        <!----------- End Checkout List of Items ------------->
        <!---------------------------------------------------->
       </div>
       <!---------------------------------------------------->
      @endif
      <!---------------------------------------------------->
      <div class="total__info">
       @if (!$cartItems->isEmpty())
        @foreach ($cartItems as $cartItem)
         <div class="total__product">
          <span class="total__quantity">
           {{ $cartItem->quantity }} x
          </span>
          @if ($cartItem->product->media->first())
           <img class="cart__list--img"
            src="/{{ $cartItem->product->media->first()->path }}{{ $cartItem->product->media->first()->name }}"
            alt="{{ $cartItem->product->media->first()->name }} {{ $cartItem->product->name }}">
          @else
           <img class="cart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
          @endif
          <a
           href="{{ route('product', ['product' => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== '' ? $cartItem->product->seo_id : $cartItem->product->id]) }}"
           target="_blank" class="total__name">{{ $cartItem->product->name }}</a>
          <span class="total__price">

           {{-- {{ $cartItem->product->price }} --}}
           <?php $currency = $cartItem->product->product_prices->first()->pricelist->currency->name; ?>
           {{ number_format($cartItem->quantity * $cartItem->price, 2, ',', '.') }}
           {{ $currency }}
          </span>

         </div>
        @endforeach

        <div class="total__item">
         <span>Modalitate de plata</span>
         <span>{{ $payment['description'] }}</span>
        </div>
        <div class="total__item">
         <span>Delivery Price:</span>
         <span>
          @if (app('global_delivery_price') == 0)
           Gratuit
          @else
           {{ number_format(app('global_delivery_price'), 2, ',', '.') }}
           {{ $currency }}
          @endif
         </span>
        </div>
        @if ($cart->voucher && $cart->voucher_value > 0)
         <div class="total__item">
          <span>Voucher:</span>
          <span>
           -{{ number_format($cart->voucher_value, 2, ',', '.') }}
           {{ $currency }}

          </span>
         </div>
        @endif
        <div class="total__item">
         <span>Total</span>

         <span>{{ number_format($cart->final_amount, 2, ',', '.') }}
          {{ $currency }}
         </span>
        </div>
       @endif
      </div>
      <!------------ End Checkout List of Forms ------------>
      <!---------------------------------------------------->
     </div>
     <label id="termsbutton" class="checkout__terms @if ($errorterms && $terms == false) error @endif">
      <input type="checkbox" wire:model="terms" name="terms">
      <span>Sunt de acord cu <a href="{{ url('/terms') }}" target="_blank">termenii si
        conditiile</a></span>

     </label>
    @endif
    {{-- script for terms error --}}
    <script>
     window.addEventListener('terms__error', event => {
      var element = document.getElementById('termsbutton');
      if (element) {
       element.scrollIntoView();
      }
     });
     window.addEventListener('next_step', event => {
      var element = document.getElementById('step__container_bulins');
      if (element) {
       element.scrollIntoView();
      }
     });
     window.addEventListener('final_step', event => {
      var element = document.getElementById('step__container_bulins');
      if (element) {
       element.scrollIntoView();
      }
     });
    </script>
    {{-- end script for terms error --}}
    <!------------------- End Step Middle ------------------>
    <!------------------------------------------------------>
    <!--------------------- Step Final --------------------->
    @if ($step == 3)
     <div class="section__header">
      <h2 class="section__title">Comanda cu numarul {{ $orderNumber }} a fost plasata!</h2>
      <p class="section__text">Vă mulțumim pentru plata efectuată! 🎉 Am primit-o și în prezent
       procesăm comanda dumneavoastră. Echipa noastră lucrează cu dedicație pentru a pregăti
       produsul dumneavoastră pentru expediere. 📦🔧</p>
      <p class="section__text">Odată ce comanda dumneavoastră este în drum spre dumneavoastră, vă
       vom
       trimite un e-mail de confirmare cu informații despre urmărire. Acest lucru vă va permite să
       urmăriți coletul și să știți când să vă așteptați la sosirea sa. 📩🚚</p>
      <p class="section__text">Dacă aveți întrebări sau aveți nevoie de asistență, vă rugăm să nu
       ezitați să contactați echipa noastră de suport pentru clienți. Suntem aici pentru a vă ajuta
       și pentru a vă asigura satisfacția. 💁‍♀️💬</p>
      <p class="section__text">Apreciem afacerea dumneavoastră și sperăm că achiziția dumneavoastră
       vă aduce fericire. Vă mulțumim că ați ales produsele noastre și așteptăm cu nerăbdare să vă
       mai servim în viitor. 🙏😊<br>Cu cele mai bune urări,</p>
      <a href="{{ url('/') }}" class="logo" aria-label="go to home page">
       <img src="/images/store/svg/noren-black.svg" alt="logo">
      </a>
     </div>
    @endif
    <!------------------- End Step Final ------------------->
    <!------------------------------------------------------>
    <!------------------- Checkout Links ------------------->
    <div class="checkout__header">
     @if ($step == 2)
      <button class="checkout__button" wire:click.prevent="previous()" aria-label="go to previous step">
       <svg>
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
       </svg>
       Pasul Anterior
      </button>
      <button class="checkout__button checkout__button--confirm" wire:click.prevent="confirm()"
       aria-label="confirm button">
       Confirmă
       <svg>
        <polyline points="9 11 12 14 22 4"></polyline>
        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
       </svg>
      </button>
     @elseif ($step == 1)
      <button class="checkout__link checkout__button--confirm"
       @if ($individual && $individual_identic) onclick="validateIndividual(this)" @elseif ($individual && !$individual_identic) onclick="validateIndividualIdentic(this)" @elseif($juridic && $juridic_identic) onclick="validateJuridic(this)" @else onclick="validateJuridicIdentic(this)" @endif
       wire:click.prevent="next()" aria-label="go to next step" style="margin: 0 auto;">
       Pasul următor
       <svg>
        <line x1="5" y1="12" x2="19" y2="12"></line>
        <polyline points="12 5 19 12 12 19"></polyline>
       </svg>
      </button>
     @endif

    </div>
    <!------------------------------------------------------>

    <!----------------- End Checkout Links ----------------->
    <!------------------------------------------------------>
   </div>
  </section>
  <!--------------------- End Checkout ------------------->
  <!------------------------------------------------------>
 @endif
 <!---------------------------------------------------------->
 <!--------------------- support button --------------------->
 <x-help-button />
 <!------------------- End support button ------------------->
 <!---------------------------------------------------------->
</div>
