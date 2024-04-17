<div>
	<x-store-alert />
	@if ($back)
		<!------------------------------------------------------>
		<!-------------------- Error Message ------------------->
		<section>
			<div class="checkout container">
				<div class="section__header container">
					<h1 class="section__title">A apărut o eroare!</h1>
					<a class="section__text" href="{{ url("/") }}">
						Te rugam să te întorci la pagina principală
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
		<section>
			<div class="checkout container">
				<!------------------------------------------------------>
				<!-------------------- Step Numbers -------------------->
				<div class="step__container">
					<div class="step active" data-step="Înregistrare Date">1</div>
					<span class="step__line @if ($step == 1) half @else full @endif"></span>
					<div class="step @if ($step > 1 || $step == 3) active @endif" data-step="Plasare Comandă">2
					</div>
					<span class="step__line @if ($step == 2) half @elseif($step == 3) full @endif"></span>
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
							<button class="checkout__button @if ($individual) active @endif" wire:click="showindividual()">Persoană Fizică</button>
							<button class="checkout__button @if ($juridic) active @endif" wire:click="showjuridic()">
								Persoană Juridica</button>
						</div>
						<div class="checkout__navigation">
							<button class="checkout__button" wire:click="resetForm">
								<svg>
									<polyline points="1 4 1 10 7 10"></polyline>
									<path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
								</svg>
								Șterge Datele
							</button>
							<button class="checkout__button checkout__button--confirm" @if ($individual && $individual_identic) onclick="validateIndividual(this)" @elseif ($individual && !$individual_identic) onclick="validateIndividualIdentic(this)" @elseif($juridic && $juridic_identic) onclick="validateJuridic(this)" @else onclick="validateJuridicIdentic(this)" @endif wire:click.prevent="next()">
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

							<div class="checkout__item checkout__item--required" id="individualShippingFirstNameParent">
								<input type="text" wire:model="individual_billing_first" name="individualShippingFirstName" placeholder="Prenume" autocomplete="family-name" required id="individualShippingFirstName">
								<span>
								</span>
								<label for="individualShippingFirstName">Prenume</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="individualShippingLastNameParent">
								<input type="text" wire:model="individual_billing_last" name="individualShippingLastName" placeholder="Nume" autocomplete="given-name" required id="individualShippingLastName">
								<span>
								</span>
								<label for="individualShippingLastName">Nume</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="individualShippingPhoneParent">
								<input type="tel" wire:model="individual_billing_phone" name="individualShippingPhone" placeholder="Telefon" autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required id="individualShippingPhone">
								<span>
								</span>
								<label for="individualShippingPhone">Telefon</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="individualShippingEmailParent">
								<input type="email" wire:model="individual_billing_email" name="individualShippingEmail" placeholder="Email" autocomplete="email" required id="individualShippingEmail">
								<span>
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
							<div class="checkout__item checkout__item--required" id="individualShippingAddressParent">
								<input type="text" wire:model="individual_billing_address1" name="individualShippingAddress" placeholder="Adresa 1" autocomplete="street-address" required id="individualShippingAddress">
								<span>
								</span>
								<label for="individualShippingAddress">Adresa 1</label>
							</div>

							<div class="checkout__item" id="individualShippingAddress2Parent">
								<input type="text" wire:model="individual_billing_address2" name="individualShippingAddress2" placeholder="Adresa 2 (opțional)" autocomplete="address-level2" id="individualShippingAddress2">
								<span></span>
								<label for="individualShippingAddress2">Adresa 2 (opțional)</label>
							</div>
							<!------------------------------------------------------------------->

							<select wire:model="individual_billing_country" class="select" id="individual_billing_country" aria-label="select a country">
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
								<option value="Virgin Islands, U.S">Virgin Islands, U.S"</option>
								<option value="Wallis and Futuna">Wallis and Futuna</option>
								<option value="Western Sahara">Western Sahara</option>
								<option value="Yemen">Yemen</option>
								<option value="Zambia">Zambia</option>
								<option value="Zimbabwe">Zimbabwe</option>
							</select>
							<!---------------------------------------------------->

							<div class="checkout__item checkout__item--required" id="individualShippingCountyParent">
								<input type="text" wire:model="individual_billing_county" name="individualShippingCounty" placeholder="Localitate (oraș, comună sau sat)" autocomplete="county" required id="individualShippingCounty">
								<span></span>
								<label for="individualShippingCounty">Județ</label>
							</div>
							<!---------------------------------------------------->

							<div class="checkout__item checkout__item--required" id="individualShippingCityParent">
								<input type="text" wire:model="individual_billing_city" name="individualShippingCity" placeholder="Județ" autocomplete="county" required id="individualShippingCity">
								<span>
								</span>
								<label for="individualShippingCity">Localitate (oraș, comună sau sat)</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="individualShippingPostalParent">
								<input type="text" wire:model="individual_billing_zipcode" name="individualShippingPostal" placeholder="Cod Poștal" autocomplete="postal-code" required id="individualShippingPostal">
								<span>
								</span>
								<label for="individualShippingPostal">Cod Poștal</label>
							</div>
							<!----------- End Checkout List of Items ------------->
							<!---------------------------------------------------->
						</div>
						<!---------------------------------------------------->
						<!---------------- Checkout Checkbox ----------------->
						<label class="checkout__checkbox">
							<input type="checkbox" wire:model="individual_identic" id="individual_identic" name="individual_identic">
							<span>Detaliile pentru facturare și livrare sunt identice</span>
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

							<div wire:ignore class="checkout__item checkout__item--required" id="individualBillingFirstNameParent">
								<input type="text" wire:model="individual_shipping_first" name="individualBillingFirstName" placeholder="Nume" autocomplete="given-name" required id="individualBillingFirstName">
								<span>
								</span>
								<label for="individualBillingFirstName">Prenume</label>
							</div>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item checkout__item--required" id="individualBillingLastNameParent">
								<input type="text" wire:model="individual_shipping_last" name="individualBillingLastName" placeholder="Prenume" autocomplete="family-name" required id="individualBillingLastName">
								<span>
								</span>
								<label for="individualBillingLastName">Nume</label>
							</div>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item checkout__item--required" id="individualBillingPhoneParent">
								<input type="tel" wire:model="individual_shipping_phone" name="individualBillingPhone" placeholder="Telefon" autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required id="individualBillingPhone">
								<span>
								</span>
								<label for="individualBillingPhone">Telefon</label>
							</div>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item checkout__item--required" id="individualBillingEmailParent">
								<input type="email" wire:model="individual_shipping_email" name="individualBillingEmail" placeholder="Email" autocomplete="email" required id="individualBillingEmail">
								<span>
								</span>
								<label for="individualBillingEmail">Email</label>
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
							<div wire:ignore class="checkout__item checkout__item--required" id="individualBillingAddressParent">
								<input type="text" wire:model="individual_shipping_address1" name="individualBillingAddress" placeholder="Adresa 1" autocomplete="street-address" required id="individualBillingAddress">
								<span>
								</span>
								<label for="individualBillingAddress">Adresa 1</label>
							</div>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item" id="individualBillingAddress2Parent">
								<input type="text" wire:model="individual_shipping_address2" name="individualBillingAddress2" placeholder="Adresa 2 (optional)" autocomplete="address-level2" id="individualBillingAddress2">
								<span></span>
								<label for="individualBillingAddress2">Adresa 2 (opțional)</label>
							</div>
							<!---------------------------------------------------->
							<select wire:ignore wire:model="individual_shipping_country" class="select" id="individual_shipping_country" aria-label="select a country">
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
								<option value="Virgin Islands, U.S">Virgin Islands, U.S"</option>
								<option value="Wallis and Futuna">Wallis and Futuna</option>
								<option value="Western Sahara">Western Sahara</option>
								<option value="Yemen">Yemen</option>
								<option value="Zambia">Zambia</option>
								<option value="Zimbabwe">Zimbabwe</option>
							</select>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item checkout__item--required" id="individualBillingCountyParent">
								<input type="text" wire:model="individual_shipping_county" name="individualBillingCounty" placeholder="Județ" autocomplete="county" required id="individualBillingCounty">
								<span></span>
								<label for="individualBillingCounty">Județ</label>
							</div>
							<!---------------------------------------------------->

							<div wire:ignore class="checkout__item checkout__item--required" id="individualBillingCityParent">
								<input type="text" wire:model="individual_shipping_city" name="individualBillingCity" placeholder="Localitate (oraș, comună sau sat)" autocomplete="off" required id="individualBillingCity">
								<span>
								</span>
								<label for="individualBillingCity">Localitate (oraș, comună sau sat)</label>
							</div>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item checkout__item--required" id="individualBillingPostalParent">
								<input type="text" wire:model="individual_shipping_zipcode" name="individualBillingPostal" placeholder="Cod Poștal" autocomplete="postal-code" required id="individualBillingPostal">
								<span>
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
							<div class="checkout__item checkout__item--required" id="juridicShippingFirstNameParent">
								<input type="text" wire:model="juridic_billing_first" name="juridicShippingFirstName" placeholder="Prenume" autocomplete="given-name" required id="juridicShippingFirstName">
								<span>
								</span>
								<label for="juridicShippingFirstName">Prenume</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="juridicShippingLastNameParent">
								<input type="text" wire:model="juridic_billing_last" name="juridicShippingLastName" placeholder="Nume" autocomplete="family-name" required id="juridicShippingLastName">
								<span>
								</span>
								<label for="juridicShippingLastName">Nume</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="juridicShippingPhoneParent">
								<input type="tel" wire:model="juridic_billing_phone" name="juridicShippingPhone" placeholder="Telefon" autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required id="juridicShippingPhone">
								<span>
								</span>
								<label for="juridicShippingPhone">Telefon</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="juridicShippingEmailParent">
								<input type="email" wire:model="juridic_billing_email" name="juridicShippingEmail" placeholder="Email" autocomplete="email" required id="juridicShippingEmail">
								<span>
								</span>
								<label for="juridicShippingEmail">Email</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="companyNameParent">
								<input type="text" wire:model="juridic_billing_company_name" name="companyName" placeholder="Numele Companiei" autocomplete="organization" required id="companyName">
								<span>
								</span>
								<label for="companyName">Numele Companiei</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="registerCodeParent">
								<input type="text" wire:model="juridic_billing_registration_code" name="registerCode" placeholder="Cod de înregistrare" autocomplete="off" required id="registerCode">
								<span>
								</span>
								<label for="registerCode">Cod de înregistrare</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="registerNumberParent">
								<input type="text" wire:model="juridic_billing_registration_number" name="registerNumber" placeholder="Număr de înregistrare" autocomplete="organization-number" required id="registerNumber">
								<span>
								</span>
								<label for="registerNumber">Număr de înregistrare</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item" id="bankNameParent">
								<input type="text" wire:model="juridic_billing_bank" name="bankName" placeholder="Numele Băncii" autocomplete="off" id="bankName">
								<span>
								</span>
								<label for="bankName">Numele Băncii</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item" id="IBANParent">
								<input type="text" wire:model="juridic_billing_account" name="IBAN" placeholder="IBAN" autocomplete="IBAN" id="IBAN">
								<span>
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
							<div class="checkout__item checkout__item--required" id="juridicShippingAddressParent">
								<input type="text" wire:model="juridic_billing_address1" name="juridicShippingAddress" placeholder="Adresa 1" autocomplete="street-address" required id="juridicShippingAddress">
								<span>
								</span>
								<label for="juridicShippingAddress">Adresa 1</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item" id="juridicShippingAddress2Parent">
								<input type="text" wire:model="juridic_billing_address2" name="juridicShippingAddress2" placeholder="Adresa 2 (optional)" autocomplete="address-level2" id="juridicShippingAddress2">
								<span></span>
								<label for="juridicShippingAddress2">Adresa 2 (optional)</label>
							</div>
							<!---------------------------------------------------->
							<select wire:model="juridic_billing_country" class="select" id="juridic_billing_country" aria-label="select a country">
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
								<option value="Virgin Islands, U.S">Virgin Islands, U.S"</option>
								<option value="Wallis and Futuna">Wallis and Futuna</option>
								<option value="Western Sahara">Western Sahara</option>
								<option value="Yemen">Yemen</option>
								<option value="Zambia">Zambia</option>
								<option value="Zimbabwe">Zimbabwe</option>
							</select>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="juridicShippingCountyParent">
								<input type="text" wire:model="juridic_billing_county" name="juridicShippingCounty" placeholder="Județ" autocomplete="county" required id="juridicShippingCounty">
								<span></span>
								<label for="juridicShippingCounty">Județ</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="juridicShippingCityParent">
								<input type="text" wire:model="juridic_billing_city" name="juridicShippingCity" placeholder="Localitate (oraș, comună sau sat)" autocomplete="off" required id="juridicShippingCity">
								<span>
								</span>
								<label for="juridicShippingCity">Localitate (oraș, comună sau sat)</label>
							</div>
							<!---------------------------------------------------->
							<div class="checkout__item checkout__item--required" id="juridicShippingPostalParent">
								<input type="text" wire:model="juridic_billing_zipcode" name="juridicShippingPostal" placeholder="Cod Poștal" autocomplete="postal-code" required id="juridicShippingPostal">
								<span>
								</span>
								<label for="juridicShippingPostal">Cod Poștal</label>
							</div>
							<!----------- End Checkout List of Items ------------->
							<!---------------------------------------------------->
						</div>
						<!---------------------------------------------------->
						<!---------------- Checkout Checkbox ----------------->
						<label class="checkout__checkbox">
							<input type="checkbox" wire:model="juridic_identic" id="juridic_identic" name="juridic_identic">
							<span>Detaliile pentru facturare și livrare sunt identice</span>
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
							<div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingFirstNameParent">
								<input type="text" wire:model="juridic_shipping_first" name="juridicBillingFirstName" placeholder="Prenume" autocomplete="given-name" required id="juridicBillingFirstName">
								<span>
								</span>
								<label for="juridicBillingFirstName">Prenume</label>
							</div>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingLastNameParent">
								<input type="text" wire:model="juridic_shipping_last" name="juridicBillingLastName" placeholder="Nume" autocomplete="family-name" required id="juridicBillingLastName">
								<span>
								</span>
								<label for="juridicBillingLastName">Nume</label>
							</div>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingPhoneParent">
								<input type="tel" wire:model="juridic_shipping_phone" name="juridicBillingPhone" placeholder="Telefon" autocomplete="tel" pattern="[0-9]*" inputmode="numeric" required id="juridicBillingPhone">
								<span>
								</span>
								<label for="juridicBillingPhone">Telefon</label>
							</div>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingEmailParent">
								<input type="email" wire:model="juridic_shipping_email" name="juridicBillingEmail" placeholder="Email" autocomplete="email" required id="juridicBillingEmail">
								<span>
								</span>
								<label for="juridicBillingEmail">Email</label>
							</div>
							<!----------- End Checkout List of Items ------------->
							<!---------------------------------------------------->
						</div>
						<!---------------------------------------------------->
						<div class="checkout__form @if (!$juridic_identic && $juridic) active @endif">
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
							<div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingAddressParent">
								<input type="text" wire:model="juridic_shipping_address1" name="juridicBillingAddress" placeholder="Adresa 1" autocomplete="street-address" required id="juridicBillingAddress">
								<span></span>
								<label for="juridicBillingAddress">Adresa</label>
							</div>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item" id="juridicBillingAddress2Parent">
								<input type="text" wire:model="juridic_shipping_address2" name="juridicBillingAddress2" placeholder="Adresa 2 (Opțional)" autocomplete="address-level2" id="juridicBillingAddress2">
								<span></span>
								<label for="juridicBillingAddress2">Adresa 2 (Opțional)</label>
							</div>
							<!---------------------------------------------------->
							<select wire:model="juridic_shipping_country" class="select" id="juridic_shipping_country" aria-label="select a country">
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
								<option value="Virgin Islands, U.S">Virgin Islands, U.S"</option>
								<option value="Wallis and Futuna">Wallis and Futuna</option>
								<option value="Western Sahara">Western Sahara</option>
								<option value="Yemen">Yemen</option>
								<option value="Zambia">Zambia</option>
								<option value="Zimbabwe">Zimbabwe</option>
							</select>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingCountyParent">
								<input type="text" wire:model="juridic_shipping_county" name="juridicBillingCounty" placeholder="Județ" autocomplete="county" required id="juridicBillingCounty">
								<span></span>
								<label for="juridicBillingCounty">Județ</label>
							</div>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingCityParent">
								<input type="text" wire:model="juridic_shipping_city" name="juridicBillingCity" placeholder="Localitate (oraș, comună sau sat)" autocomplete="off" required id="juridicBillingCity">
								<span></span>
								<label for="juridicBillingCity">Localitate (oraș, comună sau sat)</label>
							</div>
							<!---------------------------------------------------->
							<div wire:ignore class="checkout__item checkout__item--required" id="juridicBillingPostalParent">
								<input type="text" wire:model="juridic_shipping_zipcode" name="juridicBillingPostal" placeholder="Cod Poștal" autocomplete="postal-code" required id="juridicBillingPostal">
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
						<h2 class="section__title">Metoda de plată</h2>
					</div>
					@if ($card["active"] != 0)
						<div class="payment">
							<label class="payment__wrapper" for="crd" wire:click="togglepayment('crd')">
								<input class="payment__checkbox" type="checkbox" wire:model.defer="crd" id="crd">
								<span>{{ $card["description"] }}</span>
							</label>
							<div class="payment__text @if ($crd) active @endif">
								<h3>Vei plăti online cu cardul la finalizarea comenzii.</h3>
							</div>
						</div>
					@endif
					@if ($cash["active"] != 0)
						<div class="payment">
							<label class="payment__wrapper" for="rtc" wire:click="togglepayment('rtc')">
								<input class="payment__checkbox" type="checkbox" wire:model.defer="rtc" id="rtc">
								<span>{{ $cash["description"] }}</span>
							</label>
							<div class="payment__text @if ($rtc) active @endif">
								<h3>Vei plăti când comanda va fi livrată.</h3>
								@if (app()->has('global_cash_limit') && app('global_cash_limit') != 0)
								<span>Limita maxima este de {{ app('global_cash_limit') }}</span>
								@endif
							</div>
						</div>
					@endif

					@if ($ordin["active"] != 0)
						@if ($juridic)
							<div class="payment">
								<label class="payment__wrapper" for="invoice" wire:click="togglepayment('invoice')">
									<input class="payment__checkbox" type="checkbox" wire:model.defer="invoice" id="invoice">
									<span>{{ $ordin["description"] }}</span>
								</label>
								<div class="payment__text @if ($invoice) active @endif">
									<h3>
										Metoda de plată utilizată de entitățile legale. După plasarea comenzii,
										vei primi prin e-mail factura proformă cu toate detaliile de plată.
									</h3>
								</div>

							</div>
						@endif
					@endif
					<script src="/script/store/order.js"></script>
					<script>
						applyValidations("individualShippingFirstNameParent", firstNameValidation, false);
						applyValidations("individualShippingLastNameParent", lastNameValidation, false);
						applyValidations("individualShippingEmailParent", emailValidation, false);
						applyValidations("individualShippingPhoneParent", phoneValidation, false);
						applyValidations("individualShippingAddressParent", addressValidations, false);
						applyValidations("individualShippingCountyParent", addressValidations, false);
						applyValidations("individualShippingCityParent", addressValidations, false);
						applyValidations("individualShippingPostalParent", addressValidations, false);
						applyValidations("individualBillingFirstNameParent", firstNameValidation, false);
						applyValidations("individualBillingLastNameParent", lastNameValidation, false);
						applyValidations("individualBillingEmailParent", emailValidation, false);
						applyValidations("individualBillingPhoneParent", phoneValidation, false);
						applyValidations("individualBillingAddressParent", addressValidations, false);
						applyValidations("individualBillingCountyParent", addressValidations, false);
						applyValidations("individualBillingCityParent", addressValidations, false);
						applyValidations("individualBillingPostalParent", addressValidations, false);
						// ----------------------------------------------------------------------------
						applyValidations("juridicShippingFirstNameParent", firstNameValidation, false);
						applyValidations("juridicShippingLastNameParent", lastNameValidation, false);
						applyValidations("juridicShippingEmailParent", emailValidation, false);
						applyValidations("juridicShippingPhoneParent", phoneValidation, false);
						applyValidations("juridicShippingAddressParent", addressValidations, false);
						applyValidations("juridicShippingCountyParent", addressValidations, false);
						applyValidations("juridicShippingCityParent", addressValidations, false);
						applyValidations("juridicShippingPostalParent", addressValidations, false);
						applyValidations("companyNameParent", companyName, false);
						applyValidations("registerNumberParent", registerNumber, false);
						applyValidations("registerCodeParent", registerCode, false);
						applyValidations("juridicBillingFirstNameParent", firstNameValidation, false);
						applyValidations("juridicBillingLastNameParent", lastNameValidation, false);
						applyValidations("juridicBillingEmailParent", emailValidation, false);
						applyValidations("juridicBillingPhoneParent", phoneValidation, false);
						applyValidations("juridicBillingAddressParent", addressValidations, false);
						applyValidations("juridicBillingCountyParent", addressValidations, false);
						applyValidations("juridicBillingCityParent", addressValidations, false);
						applyValidations("juridicBillingPostalParent", addressValidations, false);
					</script>

					<script>
            if (typeof dataLayer !== 'undefined') {
              dataLayer.push({
                'event': 'checkoutStep',
                'step': 1,
                'option': 'Detalii Comanda'
              });
            };
					</script>

				@endif
				<!------------------ End Step First -------------------->
				<!------------------------------------------------------>
				<!--------------------- Step Middle -------------------->
				@if ($step == 2)
				<?php
				$disables =[];
				?>
					<div class="checkout__header">
						<button class="checkout__button" wire:click.prevent="previous()">
							<svg>
								<line x1="19" y1="12" x2="5" y2="12"></line>
								<polyline points="12 19 5 12 12 5"></polyline>
							</svg>Pasul anterior
						</button>
						@if ($modification)
						<button class="checkout__button checkout__button--confirm item__button--disabled">
							Confirmă <svg>
								<polyline points="9 11 12 14 22 4"></polyline>
								<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
							</svg>
						</button>
						@else
						<button class="checkout__button checkout__button--confirm" wire:click.prevent="confirm()">
							Confirmă <svg>
								<polyline points="9 11 12 14 22 4"></polyline>
								<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
							</svg>
						</button>
						@endif
					</div>
					<div class="section__header">
						<h2 class="section__title">Verifică detaliile comenzii.</h2>
					</div>
					<div class="total__container">
						<!---------------------------------------------------->
						<!-------------- Checkout List of Forms -------------->
						@if ($individual)
							<div class="look__form">
								<!---------------------------------------------------->
								<!------------- Checkout Header Name --------------->
								<h3>
									Informații de facturare&check;
								</h3>
								<!----------- End Checkout Header Name ------------->
								<!---------------------------------------------------->
								<!------------- Checkout List of Items --------------->
								<span class="total__message">Nume și Prenume:
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
								<span class="total__message">Țara:
									<strong>{{ $individual_billing_country }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Județ:
									<strong>{{ $individual_billing_county }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Localitate (oraș, comună sau sat):
									<strong>{{ $individual_billing_city }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod Poștal:
									<strong>{{ $individual_billing_zipcode }}</strong></span>
								<!----------- End Checkout List of Items ------------->
								<!---------------------------------------------------->
								<!---------------------------------------------------->
								<!------------- Checkout Header Name --------------->
								<h3>
									Informații de livrare &check;
								</h3>
								<!----------- End Checkout Header Name ------------->
								<!---------------------------------------------------->
								<!------------- Checkout List of Items --------------->
								<span class="total__message">Nume și Prenume:
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
								<span class="total__message">Țara:
									<strong>{{ $individual_shipping_country }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Județ:
									<strong>{{ $individual_shipping_county }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Localitate (oraș, comună sau sat):
									<strong>{{ $individual_shipping_city }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod Poștal:
									<strong>{{ $individual_shipping_zipcode }}</strong></span>
								<!----------- End Checkout List of Items ------------->
								<!---------------------------------------------------->
							</div>
						@else
							<div class="look__form">
								<!---------------------------------------------------->
								<!------------- Checkout Header Name --------------->
								<h3>
									Informații de facturare&check;
								</h3>
								<!----------- End Checkout Header Name ------------->
								<!---------------------------------------------------->
								<!------------- Checkout List of Items --------------->
								<span class="total__message">Nume și Prenume:
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
								<span class="total__message">Cont IBAN:
									<strong>{{ $juridic_billing_account }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Adresa:
									<strong>{{ $juridic_billing_address1 }}</strong>
									<strong>{{ $juridic_billing_address2 }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Țara:
									<strong>{{ $juridic_billing_country }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Județ:
									<strong>{{ $juridic_billing_county }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Localitate (oraș, comună sau sat):
									<strong>{{ $juridic_billing_city }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod Poștal:
									<strong>{{ $juridic_billing_zipcode }}</strong></span>
								<!----------- End Checkout List of Items ------------->
								<!---------------------------------------------------->
								<!---------------------------------------------------->
								<!------------- Checkout Header Name --------------->
								<h3>
									Informații de livrare &check;
								</h3>
								<!----------- End Checkout Header Name ------------->
								<!---------------------------------------------------->
								<!------------- Checkout List of Items --------------->
								<span class="total__message">Nume și Prenume:
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
								<span class="total__message">Țara:
									<strong>{{ $juridic_shipping_country }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Județ:
									<strong>{{ $juridic_shipping_county }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Localitate (oraș, comună sau sat):
									<strong>{{ $juridic_shipping_city }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod Poștal:
									<strong>{{ $juridic_shipping_zipcode }}</strong></span>
								<!----------- End Checkout List of Items ------------->
								<!---------------------------------------------------->
							</div>
							<!---------------------------------------------------->
						@endif
						<!---------------------------------------------------->
						<div class="total__info">
							@if (!$cartItems->isEmpty())
								@foreach ($cartItems as $index => $cartItem)
								<?php
								$disabled[$index] = false;
								$nonquantity[$index] = false;
								if (($cartItem->product->active != true) || ($cartItem->product->start_date > now()->format('Y-m-d')) || ($cartItem->product->end_date < now()->format('Y-m-d'))){
									$disabled[$index] = true;
 									$this->emit('isdisabled');
								}
								if ($cartItem->product->quantity < $cartItem->quantity) {
					    $nonquantity[$index] = true;
					     									$this->emit('isdisabled');

					}
								?>
									<div class="total__product">
										<span class="total__quantity">
											{{ $cartItem->quantity }} x
										</span>
										@if ($cartItem->product->media->first())
											<img loading="lazy" class="cart__list--img" src="/{{ $cartItem->product->media->first()->path }}{{ $cartItem->product->media->first()->name }}" alt="{{ $cartItem->product->media->first()->name }} {{ $cartItem->product->name }}">
										@else
											<img loading="lazy" class="cart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
										@endif
								@if ($nonquantity[$index])

								<div class="leftbar__link--text">
									<a href="{{ route("product", ["product" => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== "" ? $cartItem->product->seo_id : $cartItem->product->id]) }}" target="_blank" class="total__name">{{ $cartItem->product->name }}
									</a>
								<span class="item__product--error">Stoc disponibil pentru acest produs: {{ $cartItem->product->quantity }}</span>

						<a class="item__product--link" href="{{ url("/cart") }}">Modifică cantitatea</a>
								</div>
								@else
<a href="{{ route("product", ["product" => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== "" ? $cartItem->product->seo_id : $cartItem->product->id]) }}" target="_blank" class="total__name">{{ $cartItem->product->name }}
									</a>
								<span class="total__price">
									<?php $currency = $cartItem->product->product_prices->first()->pricelist->currency->symbol; ?>
									{{ number_format($cartItem->quantity * $cartItem->price, 2, ",", ".") }}
									{{ $currency }}
								</span>
								@endif

								@if ($disabled[$index])
								<div class="item__product--disabled">
						 		 <span>Produs Indisponibil</span>
								</div>
								@endif
									</div>
								@endforeach

								<div class="total__item">
									<span>Modalitate de plată</span>
									<span>{{ $payment["description"] }}</span>
								</div>
								<div class="total__item">
									<span>Cost livrare</span>
									<span>
										@if ($cart->delivery_price == 0)
											Gratuit
										@else
											{{ $cart->delivery_price }} {{ $currency }}
										@endif
									</span>
								</div>
								@if ($cart->voucher && $cart->voucher_value > 0)
									<div class="total__item">
										<span>Voucher:</span>
										<span>
											-{{ number_format($cart->voucher_value, 2, ",", ".") }} {{ $currency }}
										</span>
									</div>
								@endif
								<div class="total__item">
									<span>Total</span>
									<span>{{ number_format($cart->final_amount, 2, ",", ".") }} {{ $currency }}
									</span>
								</div>
								@if ($modification)

								<span class="item__text--disabled">Cantitatea anumitor produse nu mai este disponibilă, sau ai cel puțin un produs indisponibil adaugat in coș!</span>
								@endif
							@endif
						</div>
						<!------------ End Checkout List of Forms ------------>
						<!---------------------------------------------------->
					</div>

					<label id="termsbutton" class="checkout__terms @if ($errorterms && $terms == false) error @endif">
						<input type="checkbox" wire:model="terms" name="terms">
						<span>Sunt de acord cu <a href="{{ url("/terms") }}" target="_blank">Termenii și
								condițiile</a></span>

					</label>
					@if ($payment_cancel)
						<script>
							window.addEventListener('DOMContentLoaded', function() {
								Livewire.emit('alert__modal', ['message' => 'A aparut o eroare la plata!']);
							});
						</script>
					@endif

					<!-- DataLayer Script pentru Step 2 -->
					<script>
            if (typeof dataLayer !== 'undefined') {
						dataLayer.push({
							'event': 'checkoutStep',
							'step': 2,
							'option': 'Plasare Comandă'
						});
            };
					</script>
				@endif
				{{-- script for terms error --}}
				<script>
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
				{{-- end script for terms error --}}
				<!------------------- End Step Middle ------------------>
				<!------------------------------------------------------>
				<!--------------------- Step Final --------------------->
				@if ($step == 3)
				 <section class="section__header container">
        <h1 class="section__title">
          Comanda {{ $new_order->order_number }}
        </h1>
      </section>
					<div class="section__header">

						@if (app()->has("global_order_default_text_confirmation") && app("global_order_default_text_confirmation") != "")
							{!! app("global_order_default_text_confirmation") !!}
						@else
							<h2>Comanda a fost plasata cu succes!</h2>
						@endif

					</div>
					<div class="total__container">
						{{-- <h2>Comanda {{ $new_order->order_number }}</h2> --}}
						<!---------------------------------------------------->
						<!-------------- Checkout List of Forms -------------->
						@if ($individual)
							<div class="look__form">
								<!---------------------------------------------------->
								<!------------- Checkout Header Name --------------->
								<h3>
									Informații de facturare&check;
								</h3>
								<!----------- End Checkout Header Name ------------->
								<!---------------------------------------------------->
								<!------------- Checkout List of Items --------------->
								<span class="total__message">Nume și Prenume:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->first_name }}</strong>
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->last_name }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Telefon:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->phone }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Email:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->email }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Adresa:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->address1 }}</strong>
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->address2 }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Țara:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->country }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Județ:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->county }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Localitate (oraș, comună sau sat):
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->city }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod Poștal:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->zipcode }}</strong></span>
								<!----------- End Checkout List of Items ------------->
								<!---------------------------------------------------->
								<!---------------------------------------------------->
								<!------------- Checkout Header Name --------------->
								<h3>
									Informații de livrare &check;
								</h3>
								<!----------- End Checkout Header Name ------------->
								<!---------------------------------------------------->
								<!------------- Checkout List of Items --------------->
								<span class="total__message">Nume și Prenume:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->first_name }}</strong>
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->last_name }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Telefon:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->phone }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Email:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->email }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Adresa:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->address1 }}</strong>
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->address2 }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Țara:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->country }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Județ:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->county }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Localitate (oraș, comună sau sat):
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->city }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod Poștal:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->zipcode }}</strong></span>
								<!----------- End Checkout List of Items ------------->
								<!---------------------------------------------------->
							</div>
						@endif
						<!---------------------------------------------------->
						@if ($juridic)
							<div class="look__form">
								<!---------------------------------------------------->
								<!------------- Checkout Header Name --------------->
								<h3>
									Informații de facturare&check;
								</h3>
								<!----------- End Checkout Header Name ------------->
								<!---------------------------------------------------->
								<!------------- Checkout List of Items --------------->
								<span class="total__message">Nume și Prenume:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->first_name }}</strong>
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->last_name }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Telefon:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->phone }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Email:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->email }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Companie:
									<strong>{{ $new_order->account->company_name }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod de înregistrare:
									<strong>{{ $new_order->account->registration_code }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Număr de înregistrare:
									<strong>{{ $new_order->account->registration_number }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Denumirea Bancii:
									<strong>{{ $new_order->account->bank_name }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">COnt IBAN:
									<strong>{{ $new_order->account->account }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Adresa:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->address1 }}</strong>
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->address2 }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Țara:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->country }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Județ:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->county }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Localitate (oraș, comună sau sat):
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->city }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod Poștal:
									<strong>{{ $new_order->account->addresses->where("type", "billing")->first()->zipcode }}</strong></span>
								<!----------- End Checkout List of Items ------------->
								<!---------------------------------------------------->
								<!---------------------------------------------------->
								<!------------- Checkout Header Name --------------->
								<h3>
									Informații de livrare &check;
								</h3>
								<!----------- End Checkout Header Name ------------->
								<!---------------------------------------------------->
								<!------------- Checkout List of Items --------------->
								<span class="total__message">Nume și Prenume:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->first_name }}</strong>
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->last_name }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Telefon:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->phone }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Email:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->email }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Adresa:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->address1 }}</strong>
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->address2 }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Țara:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->country }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Județ:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->county }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Localitate (oraș, comună sau sat):
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->city }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod Poștal:
									<strong>{{ $new_order->account->addresses->where("type", "shipping")->first()->zipcode }}</strong></span>
								<!----------- End Checkout List of Items ------------->
								<!---------------------------------------------------->
							</div>
							<!---------------------------------------------------->
						@endif
						<!---------------------------------------------------->
						<div class="total__info">
							@foreach ($new_order->orders as $cartItem)
								<div class="total__product">
									<span class="total__quantity">
										{{ $cartItem->quantity }} x
									</span>
									@if ($cartItem->product->media->where("type", "min")->first())
										<img loading="lazy" class="cart__list--img" src="/{{ $cartItem->product->media->where("type", "min")->first()->path }}{{ $cartItem->product->media->where("type", "min")->first()->name }}" alt="{{ $cartItem->product->media->where("type", "min")->first()->name }} {{ $cartItem->product->name }}">
									@else
										<img loading="lazy" class="cart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
									@endif
									<a href="{{ route("product", ["product" => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== "" ? $cartItem->product->seo_id : $cartItem->product->id]) }}" target="_blank" class="total__name">{{ $cartItem->product->name }}</a>
									<span class="total__price">

										{{-- {{ $cartItem->product->price }} --}}
										<?php $currency = $new_order->currency->symbol; ?>
										{{ number_format($cartItem->quantity * $cartItem->price, 2, ",", ".") }}
										{{ $currency }}
									</span>

								</div>
							@endforeach

							<div class="total__item">
								<span>Modalitate de plată</span>
								<span>{{ $new_order->payment->description  }}</span>
							</div>
							<div class="total__item">
								<span>Cost livrare</span>
								<span>
									@if ($new_order->delivery_price == 0)
										Gratuit
									@else
										{{ $new_order->delivery_price }} {{ $currency }}
									@endif
								</span>
							</div>
							@if ($new_order->voucher && $new_order->voucher_value > 0)
								<div class="total__item">
									<span>Voucher:</span>
									<span>
										-{{ number_format($new_order->voucher_value, 2, ",", ".") }}
										{{ $currency }}

									</span>
								</div>
							@endif
							<div class="total__item">
								<span>Total</span>

								<span id="final__amount">{{ number_format($new_order->final_amount, 2, ",", ".") }}
									{{ $currency }}
								</span>
							</div>
						</div>
						<!------------ End Checkout List of Forms ------------>
						<!---------------------------------------------------->
					</div>
					<script>
						window.addEventListener('DOMContentLoaded', function() {
							Livewire.emit('orderprocess');
						});
					</script>
					<script>
            const finalAmountElement = document.getElementById("final__amount").innerText;

            // presupunând că valoarea și valuta sunt separate printr-un spațiu
            let [value, currency] = finalAmountElement.split(' ');

            currency = "RON";

            // console.log(value, currency);

            if(typeof dataLayer !== 'undefined') {
              dataLayer.push({
                'event': 'finalAmount',
                'amount': value,
                'currency': currency
              });
            }
					</script>
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
						@if ($modification)
						<button class="checkout__button checkout__button--confirm item__button--disabled">
							Confirmă <svg>
								<polyline points="9 11 12 14 22 4"></polyline>
								<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
							</svg>
						</button>
						@else
						<button class="checkout__button checkout__button--confirm" wire:click.prevent="confirm()">
							Confirmă <svg>
								<polyline points="9 11 12 14 22 4"></polyline>
								<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
							</svg>
						</button>
						@endif
					@elseif ($step == 1)
						<button class="checkout__link checkout__button--confirm" @if ($individual && $individual_identic) onclick="validateIndividual(this)" @elseif ($individual && !$individual_identic) onclick="validateIndividualIdentic(this)" @elseif($juridic && $juridic_identic) onclick="validateJuridic(this)" @else onclick="validateJuridicIdentic(this)" @endif wire:click.prevent="next()" aria-label="go to next step"
							style="margin: 0 auto;">
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
