<div>
    <x-store-alert />
    @if ($back)
        <!------------------------------------------------------>
        <!-------------------- Error Message ------------------->
        <section>
            <div class="checkout container">
                <div class="section__header container">
                    <h2 class="section__title">Ups, a aparut o eroare!</h2>
                    <a class="section__text" href="{{ url("/") }}">
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
        <section>
            <div class="checkout container">
                <!------------------------------------------------------>
                <!-------------------- Step Numbers -------------------->
                <div class="step__container">
                    <div class="step active" data-step="Inregistrare Date">1</div>
                    <span class="step__line @if ($step == 1) half @else full @endif"></span>
                    <div class="step @if ($step > 1 || $step == 3) active @endif" data-step="Plasare comanda">2
                    </div>
                    <span class="step__line @if ($step == 3) full @endif"></span>
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
                            <button class="checkout__button @if ($juridic) active @endif"
                                wire:click="showjuridic()"> Persoana Juridica</button>
                        </div>
                        <div class="checkout__navigation">
                            <button class="checkout__button" wire:click="resetForm">
                                <svg>
                                    <polyline points="1 4 1 10 7 10"></polyline>
                                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                                </svg>
                                Șterge Datele
                            </button>
                            <button class="checkout__button" wire:click.prevent="next()">
                                Pasul urmator
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
                        <div class="checkout__form active">
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
                                    @error("individual_billing_first")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_last" placeholder="Prenume">
                                <span>
                                    @error("individual_billing_last")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="tel" wire:model="individual_billing_phone" placeholder="Telefon">
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
                        <div class="checkout__form active">
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
                                    @error("individual_billing_address1")
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
                            {{-- <div class="custom-select">
                                <div class="select-selected">{{ $individual_billing_country }}</div>
                                <div class="select-items">
                                    <div>Afghanistan</div>
                                    <div>Åland Islands</div>
                                    <div>Albania</div>
                                    <div>Algeria</div>
                                    <div>American Samoa</div>
                                    <div>Andorra</div>
                                    <div>Angola</div>
                                    <div>Anguilla</div>
                                    <div>Antarctica</div>
                                    <div>Antigua and Barbuda</div>
                                    <div>Argentina</div>
                                    <div>Armenia</div>
                                    <div>Aruba</div>
                                    <div>Australia</div>
                                    <div>Austria</div>
                                    <div>Azerbaijan</div>
                                    <div>Bahamas</div>
                                    <div>Bahrain</div>
                                    <div>Bangladesh</div>
                                    <div>Barbados</div>
                                    <div>Belarus</div>
                                    <div>Belgium</div>
                                    <div>Belize</div>
                                    <div>Benin</div>
                                    <div>Bermuda</div>
                                    <div>Bhutan</div>
                                    <div>Bolivia</div>
                                    <div>Bosnia and Herzegovina</div>
                                    <div>Botswana</div>
                                    <div>Bouvet Island</div>
                                    <div>Brazil</div>
                                    <div>British Indian Ocean Territory</div>
                                    <div>Brunei Darussalam</div>
                                    <div>Bulgaria</div>
                                    <div>Burkina Faso</div>
                                    <div>Burundi</div>
                                    <div>Cambodia</div>
                                    <div>Cameroon</div>
                                    <div>Canada</div>
                                    <div>Cape Verde</div>
                                    <div>Cayman Islands</div>
                                    <div>Central African Republic</div>
                                    <div>Chad</div>
                                    <div>Chile</div>
                                    <div>China</div>
                                    <div>Christmas Island</div>
                                    <div>Cocos (Keeling) Islands</div>
                                    <div>Colombia</div>
                                    <div>Comoros</div>
                                    <div>Congo</div>
                                    <div>Congo, The Democratic Republic of the</div>
                                    <div>Cook Islands</div>
                                    <div>Costa Rica</div>
                                    <div>Croatia</div>
                                    <div>Cuba</div>
                                    <div>Cyprus</div>
                                    <div>Czech Republic</div>
                                    <div>Denmark</div>
                                    <div>Djibouti</div>
                                    <div>Dominica</div>
                                    <div>Dominican Republic</div>
                                    <div>Ecuador</div>
                                    <div>Egypt</div>
                                    <div>El Salvador</div>
                                    <div>Equatorial Guinea</div>
                                    <div>Eritrea</div>
                                    <div>Estonia</div>
                                    <div>Ethiopia</div>
                                    <div>Falkland Islands (Malvinas)</div>
                                    <div>Faroe Islands</div>
                                    <div>Fiji</div>
                                    <div>Finland</div>
                                    <div>France</div>
                                    <div>French Guiana</div>
                                    <div>French Polynesia</div>
                                    <div>French Southern Territories</div>
                                    <div>Gabon</div>
                                    <div>Gambia</div>
                                    <div>Georgia</div>
                                    <div>Germany</div>
                                    <div>Ghana</div>
                                    <div>Gibraltar</div>
                                    <div>Greece</div>
                                    <div>Greenland</div>
                                    <div>Grenada</div>
                                    <div>Guadeloupe</div>
                                    <div>Guam</div>
                                    <div>Guatemala</div>
                                    <div>Guernsey</div>
                                    <div>Guinea</div>
                                    <div>Guinea-Bissau</div>
                                    <div>Guyana</div>
                                    <div>Haiti</div>
                                    <div>Heard Island and Mcdonald Islands</div>
                                    <div>Holy See (Vatican City State)</div>
                                    <div>Honduras</div>
                                    <div>Hong Kong</div>
                                    <div>Hungary</div>
                                    <div>Iceland</div>
                                    <div>India</div>
                                    <div>Indonesia</div>
                                    <div>Iran, Islamic Republic Of</div>
                                    <div>Iraq</div>
                                    <div>Ireland</div>
                                    <div>Isle of Man</div>
                                    <div>Israel</div>
                                    <div>Italy</div>
                                    <div>Jamaica</div>
                                    <div>Japan</div>
                                    <div>Jersey</div>
                                    <div>Jordan</div>
                                    <div>Kazakhstan</div>
                                    <div>Kenya</div>
                                    <div>Kiribati</div>
                                    <div>Korea, Republic of</div>
                                    <div>Kuwait</div>
                                    <div>Kyrgyzstan</div>
                                    <div>Latvia</div>
                                    <div>Lebanon</div>
                                    <div>Lesotho</div>
                                    <div>Liberia</div>
                                    <div>Libyan Arab Jamahiriya</div>
                                    <div>Liechtenstein</div>
                                    <div>Lithuania</div>
                                    <div>Luxembourg</div>
                                    <div>Macao</div>
                                    <div>North Macedonia</div>
                                    <div>Madagascar</div>
                                    <div>Malawi</div>
                                    <div>Malaysia</div>
                                    <div>Maldives</div>
                                    <div>Mali</div>
                                    <div>Malta</div>
                                    <div>Marshall Islands</div>
                                    <div>Martinique</div>
                                    <div>Mauritania</div>
                                    <div>Mauritius</div>
                                    <div>Mayotte</div>
                                    <div>Mexico</div>
                                    <div>Micronesia, Federated States of</div>
                                    <div>Republic of Moldova</div>
                                    <div>Monaco</div>
                                    <div>Mongolia</div>
                                    <div>Montserrat</div>
                                    <div>Morocco</div>
                                    <div>Mozambique</div>
                                    <div>Myanmar</div>
                                    <div>Namibia</div>
                                    <div>Nauru</div>
                                    <div>Nepal</div>
                                    <div>Netherlands</div>
                                    <div>Netherlands Antilles</div>
                                    <div>New Caledonia</div>
                                    <div>New Zealand</div>
                                    <div>Nicaragua</div>
                                    <div>Niger</div>
                                    <div>Nigeria</div>
                                    <div>Niue</div>
                                    <div>Norfolk Island</div>
                                    <div>Northern Mariana Islands</div>
                                    <div>Norway</div>
                                    <div>Oman</div>
                                    <div>Pakistan</div>
                                    <div>Palau</div>
                                    <div>Palestinian Territory, Occupied</div>
                                    <div>Panama</div>
                                    <div>Papua New Guinea</div>
                                    <div>Paraguay</div>
                                    <div>Peru</div>
                                    <div>Philippines</div>
                                    <div>Pitcairn Islands</div>
                                    <div>Poland</div>
                                    <div>Portugal</div>
                                    <div>Puerto Rico</div>
                                    <div>Qatar</div>
                                    <div>Reunion</div>
                                    <div>Romania</div>
                                    <div>Russian Federation</div>
                                    <div>Rwanda</div>
                                    <div>Saint Helena</div>
                                    <div>Saint Kitts and Nevis</div>
                                    <div>Saint Lucia</div>
                                    <div>Saint Pierre and Miquelon</div>
                                    <div>Saint Vincent and the Grenadines</div>
                                    <div>Samoa</div>
                                    <div>San Marino</div>
                                    <div>Sao Tome and Principe</div>
                                    <div>Saudi Arabia</div>
                                    <div>Senegal</div>
                                    <div>Serbia and Montenegro</div>
                                    <div>Seychelles</div>
                                    <div>Sierra Leone</div>
                                    <div>Singapore</div>
                                    <div>Slovakia</div>
                                    <div>Slovenia</div>
                                    <div>Solomon Islands</div>
                                    <div>Somalia</div>
                                    <div>South Africa</div>
                                    <div>South Georgia and the South Sandwich Islands</div>
                                    <div>Spain</div>
                                    <div>Sri Lanka</div>
                                    <div>Sudan</div>
                                    <div>Suriname</div>
                                    <div>Svalbard and Jan Mayen</div>
                                    <div>Swaziland</div>
                                    <div>Sweden</div>
                                    <div>Switzerland</div>
                                    <div>Syrian Arab Republic</div>
                                    <div>Taiwan</div>
                                    <div>Tajikistan</div>
                                    <div>Tanzania, United Republic of</div>
                                    <div>Thailand</div>
                                    <div>Timor-Leste</div>
                                    <div>Togo</div>
                                    <div>Tokelau</div>
                                    <div>Tonga</div>
                                    <div>Trinidad and Tobago</div>
                                    <div>Tunisia</div>
                                    <div>Turkey</div>
                                    <div>Turkmenistan</div>
                                    <div>Turks and Caicos Islands</div>
                                    <div>Tuvalu</div>
                                    <div>Uganda</div>
                                    <div>Ukraine</div>
                                    <div>United Arab Emirates</div>
                                    <div>United Kingdom</div>
                                    <div>United States</div>
                                    <div>United States Minor Outlying Islands</div>
                                    <div>Uruguay</div>
                                    <div>Uzbekistan</div>
                                    <div>Vanuatu</div>
                                    <div>Venezuela</div>
                                    <div>Vietnam</div>
                                    <div>Virgin Islands, British</div>
                                    <div>Virgin Islands, U.S"</div>
                                    <div>Wallis and Futuna</div>
                                    <div>Western Sahara</div>
                                    <div>Yemen</div>
                                    <div>Zambia</div>
                                    <div>Zimbabwe</div>
                                </div> --}}
                            <select wire:model="individual_billing_country" class="select">
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
                            {{-- </div> --}}
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_county" placeholder="Judet">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_city" placeholder="Oras">
                                <span>
                                    @error("individual_billing_city")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" placeholder="Post Code" wire:model="individual_billing_zipcode"
                                    placeholder="Cod Postal">
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
                            <span>Adresa de livrare este identică cu adresa de facturare</span>
                        </label>
                        <!-------------- End Checkout Checkbox --------------->
                        <!---------------------------------------------------->
                        {{-- @if (!$individual_identic) --}}
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
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_shipping_first" placeholder="Nume">
                                <span>
                                    @error("individual_shipping_first")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_shipping_last" placeholder="Prenume">
                                <span>
                                    @error("individual_shipping_last")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="tel" wire:model="individual_shipping_phone" placeholder="Telefon">
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
                                    placeholder="Address 2 (optional)">
                            </div>
                            <!---------------------------------------------------->

                            {{-- <div class="custom-select">
                                <div class="select-selected">{{ $individual_shipping_country }}</div>
                                <div class="select-items">
                                    <div>Afghanistan</div>
                                    <div>Åland Islands</div>
                                    <div>Albania</div>
                                    <div>Algeria</div>
                                    <div>American Samoa</div>
                                    <div>Andorra</div>
                                    <div>Angola</div>
                                    <div>Anguilla</div>
                                    <div>Antarctica</div>
                                    <div>Antigua and Barbuda</div>
                                    <div>Argentina</div>
                                    <div>Armenia</div>
                                    <div>Aruba</div>
                                    <div>Australia</div>
                                    <div>Austria</div>
                                    <div>Azerbaijan</div>
                                    <div>Bahamas</div>
                                    <div>Bahrain</div>
                                    <div>Bangladesh</div>
                                    <div>Barbados</div>
                                    <div>Belarus</div>
                                    <div>Belgium</div>
                                    <div>Belize</div>
                                    <div>Benin</div>
                                    <div>Bermuda</div>
                                    <div>Bhutan</div>
                                    <div>Bolivia</div>
                                    <div>Bosnia and Herzegovina</div>
                                    <div>Botswana</div>
                                    <div>Bouvet Island</div>
                                    <div>Brazil</div>
                                    <div>British Indian Ocean Territory</div>
                                    <div>Brunei Darussalam</div>
                                    <div>Bulgaria</div>
                                    <div>Burkina Faso</div>
                                    <div>Burundi</div>
                                    <div>Cambodia</div>
                                    <div>Cameroon</div>
                                    <div>Canada</div>
                                    <div>Cape Verde</div>
                                    <div>Cayman Islands</div>
                                    <div>Central African Republic</div>
                                    <div>Chad</div>
                                    <div>Chile</div>
                                    <div>China</div>
                                    <div>Christmas Island</div>
                                    <div>Cocos (Keeling) Islands</div>
                                    <div>Colombia</div>
                                    <div>Comoros</div>
                                    <div>Congo</div>
                                    <div>Congo, The Democratic Republic of the</div>
                                    <div>Cook Islands</div>
                                    <div>Costa Rica</div>
                                    <div>Croatia</div>
                                    <div>Cuba</div>
                                    <div>Cyprus</div>
                                    <div>Czech Republic</div>
                                    <div>Denmark</div>
                                    <div>Djibouti</div>
                                    <div>Dominica</div>
                                    <div>Dominican Republic</div>
                                    <div>Ecuador</div>
                                    <div>Egypt</div>
                                    <div>El Salvador</div>
                                    <div>Equatorial Guinea</div>
                                    <div>Eritrea</div>
                                    <div>Estonia</div>
                                    <div>Ethiopia</div>
                                    <div>Falkland Islands (Malvinas)</div>
                                    <div>Faroe Islands</div>
                                    <div>Fiji</div>
                                    <div>Finland</div>
                                    <div>France</div>
                                    <div>French Guiana</div>
                                    <div>French Polynesia</div>
                                    <div>French Southern Territories</div>
                                    <div>Gabon</div>
                                    <div>Gambia</div>
                                    <div>Georgia</div>
                                    <div>Germany</div>
                                    <div>Ghana</div>
                                    <div>Gibraltar</div>
                                    <div>Greece</div>
                                    <div>Greenland</div>
                                    <div>Grenada</div>
                                    <div>Guadeloupe</div>
                                    <div>Guam</div>
                                    <div>Guatemala</div>
                                    <div>Guernsey</div>
                                    <div>Guinea</div>
                                    <div>Guinea-Bissau</div>
                                    <div>Guyana</div>
                                    <div>Haiti</div>
                                    <div>Heard Island and Mcdonald Islands</div>
                                    <div>Holy See (Vatican City State)</div>
                                    <div>Honduras</div>
                                    <div>Hong Kong</div>
                                    <div>Hungary</div>
                                    <div>Iceland</div>
                                    <div>India</div>
                                    <div>Indonesia</div>
                                    <div>Iran, Islamic Republic Of</div>
                                    <div>Iraq</div>
                                    <div>Ireland</div>
                                    <div>Isle of Man</div>
                                    <div>Israel</div>
                                    <div>Italy</div>
                                    <div>Jamaica</div>
                                    <div>Japan</div>
                                    <div>Jersey</div>
                                    <div>Jordan</div>
                                    <div>Kazakhstan</div>
                                    <div>Kenya</div>
                                    <div>Kiribati</div>
                                    <div>Korea, Republic of</div>
                                    <div>Kuwait</div>
                                    <div>Kyrgyzstan</div>
                                    <div>Latvia</div>
                                    <div>Lebanon</div>
                                    <div>Lesotho</div>
                                    <div>Liberia</div>
                                    <div>Libyan Arab Jamahiriya</div>
                                    <div>Liechtenstein</div>
                                    <div>Lithuania</div>
                                    <div>Luxembourg</div>
                                    <div>Macao</div>
                                    <div>North Macedonia</div>
                                    <div>Madagascar</div>
                                    <div>Malawi</div>
                                    <div>Malaysia</div>
                                    <div>Maldives</div>
                                    <div>Mali</div>
                                    <div>Malta</div>
                                    <div>Marshall Islands</div>
                                    <div>Martinique</div>
                                    <div>Mauritania</div>
                                    <div>Mauritius</div>
                                    <div>Mayotte</div>
                                    <div>Mexico</div>
                                    <div>Micronesia, Federated States of</div>
                                    <div>Republic of Moldova</div>
                                    <div>Monaco</div>
                                    <div>Mongolia</div>
                                    <div>Montserrat</div>
                                    <div>Morocco</div>
                                    <div>Mozambique</div>
                                    <div>Myanmar</div>
                                    <div>Namibia</div>
                                    <div>Nauru</div>
                                    <div>Nepal</div>
                                    <div>Netherlands</div>
                                    <div>Netherlands Antilles</div>
                                    <div>New Caledonia</div>
                                    <div>New Zealand</div>
                                    <div>Nicaragua</div>
                                    <div>Niger</div>
                                    <div>Nigeria</div>
                                    <div>Niue</div>
                                    <div>Norfolk Island</div>
                                    <div>Northern Mariana Islands</div>
                                    <div>Norway</div>
                                    <div>Oman</div>
                                    <div>Pakistan</div>
                                    <div>Palau</div>
                                    <div>Palestinian Territory, Occupied</div>
                                    <div>Panama</div>
                                    <div>Papua New Guinea</div>
                                    <div>Paraguay</div>
                                    <div>Peru</div>
                                    <div>Philippines</div>
                                    <div>Pitcairn Islands</div>
                                    <div>Poland</div>
                                    <div>Portugal</div>
                                    <div>Puerto Rico</div>
                                    <div>Qatar</div>
                                    <div>Reunion</div>
                                    <div>Romania</div>
                                    <div>Russian Federation</div>
                                    <div>Rwanda</div>
                                    <div>Saint Helena</div>
                                    <div>Saint Kitts and Nevis</div>
                                    <div>Saint Lucia</div>
                                    <div>Saint Pierre and Miquelon</div>
                                    <div>Saint Vincent and the Grenadines</div>
                                    <div>Samoa</div>
                                    <div>San Marino</div>
                                    <div>Sao Tome and Principe</div>
                                    <div>Saudi Arabia</div>
                                    <div>Senegal</div>
                                    <div>Serbia and Montenegro</div>
                                    <div>Seychelles</div>
                                    <div>Sierra Leone</div>
                                    <div>Singapore</div>
                                    <div>Slovakia</div>
                                    <div>Slovenia</div>
                                    <div>Solomon Islands</div>
                                    <div>Somalia</div>
                                    <div>South Africa</div>
                                    <div>South Georgia and the South Sandwich Islands</div>
                                    <div>Spain</div>
                                    <div>Sri Lanka</div>
                                    <div>Sudan</div>
                                    <div>Suriname</div>
                                    <div>Svalbard and Jan Mayen</div>
                                    <div>Swaziland</div>
                                    <div>Sweden</div>
                                    <div>Switzerland</div>
                                    <div>Syrian Arab Republic</div>
                                    <div>Taiwan</div>
                                    <div>Tajikistan</div>
                                    <div>Tanzania, United Republic of</div>
                                    <div>Thailand</div>
                                    <div>Timor-Leste</div>
                                    <div>Togo</div>
                                    <div>Tokelau</div>
                                    <div>Tonga</div>
                                    <div>Trinidad and Tobago</div>
                                    <div>Tunisia</div>
                                    <div>Turkey</div>
                                    <div>Turkmenistan</div>
                                    <div>Turks and Caicos Islands</div>
                                    <div>Tuvalu</div>
                                    <div>Uganda</div>
                                    <div>Ukraine</div>
                                    <div>United Arab Emirates</div>
                                    <div>United Kingdom</div>
                                    <div>United States</div>
                                    <div>United States Minor Outlying Islands</div>
                                    <div>Uruguay</div>
                                    <div>Uzbekistan</div>
                                    <div>Vanuatu</div>
                                    <div>Venezuela</div>
                                    <div>Vietnam</div>
                                    <div>Virgin Islands, British</div>
                                    <div>Virgin Islands, U.S"</div>
                                    <div>Wallis and Futuna</div>
                                    <div>Western Sahara</div>
                                    <div>Yemen</div>
                                    <div>Zambia</div>
                                    <div>Zimbabwe</div>
                                </div> --}}
                            <select wire:model="individual_shipping_country" class="select">
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
                            {{-- </div> --}}
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_shipping_county" placeholder="Judet">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_shipping_city" placeholder="Oras">
                                <span>
                                    @error("individual_shipping_city")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="individual_shipping_zipcode"
                                    placeholder="Cod Postal">
                                <span>
                                    @error("individual_shipping_zipcode")
                                        {{ $message }}
                                    @enderror
                                </span>
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
                        <div class="checkout__form active">
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
                                    @error("juridic_billing_first")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_last" placeholder="Prenume">
                                <span>
                                    @error("juridic_billing_last")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="tel" wire:model="juridic_billing_phone" placeholder="Telefon">
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
                                    placeholder="Denumirea Companiei">
                                <span>
                                    @error("juridic_billing_company_name")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_registration_code"
                                    placeholder="Cod de înregistrare">
                                <span>
                                    @error("juridic_billing_registration_code")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_registration_number"
                                    placeholder="Număr de înregistrare.">
                                <span>
                                    @error("juridic_billing_registration_number")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_bank"
                                    placeholder="Denumirea Bancii">
                                <span>
                                    @error("juridic_billing_bank")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_account" placeholder="Cont IBAN">
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
                        <div class="checkout__form active">
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
                                    @error("juridic_billing_address1")
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
                            {{-- <div class="custom-select">
                                <div class="select-selected">{{ $juridic_billing_country }}</div>
                                <div class="select-items">
                                    <div>Afghanistan</div>
                                    <div>Åland Islands</div>
                                    <div>Albania</div>
                                    <div>Algeria</div>
                                    <div>American Samoa</div>
                                    <div>Andorra</div>
                                    <div>Angola</div>
                                    <div>Anguilla</div>
                                    <div>Antarctica</div>
                                    <div>Antigua and Barbuda</div>
                                    <div>Argentina</div>
                                    <div>Armenia</div>
                                    <div>Aruba</div>
                                    <div>Australia</div>
                                    <div>Austria</div>
                                    <div>Azerbaijan</div>
                                    <div>Bahamas</div>
                                    <div>Bahrain</div>
                                    <div>Bangladesh</div>
                                    <div>Barbados</div>
                                    <div>Belarus</div>
                                    <div>Belgium</div>
                                    <div>Belize</div>
                                    <div>Benin</div>
                                    <div>Bermuda</div>
                                    <div>Bhutan</div>
                                    <div>Bolivia</div>
                                    <div>Bosnia and Herzegovina</div>
                                    <div>Botswana</div>
                                    <div>Bouvet Island</div>
                                    <div>Brazil</div>
                                    <div>British Indian Ocean Territory</div>
                                    <div>Brunei Darussalam</div>
                                    <div>Bulgaria</div>
                                    <div>Burkina Faso</div>
                                    <div>Burundi</div>
                                    <div>Cambodia</div>
                                    <div>Cameroon</div>
                                    <div>Canada</div>
                                    <div>Cape Verde</div>
                                    <div>Cayman Islands</div>
                                    <div>Central African Republic</div>
                                    <div>Chad</div>
                                    <div>Chile</div>
                                    <div>China</div>
                                    <div>Christmas Island</div>
                                    <div>Cocos (Keeling) Islands</div>
                                    <div>Colombia</div>
                                    <div>Comoros</div>
                                    <div>Congo</div>
                                    <div>Congo, The Democratic Republic of the</div>
                                    <div>Cook Islands</div>
                                    <div>Costa Rica</div>
                                    <div>Croatia</div>
                                    <div>Cuba</div>
                                    <div>Cyprus</div>
                                    <div>Czech Republic</div>
                                    <div>Denmark</div>
                                    <div>Djibouti</div>
                                    <div>Dominica</div>
                                    <div>Dominican Republic</div>
                                    <div>Ecuador</div>
                                    <div>Egypt</div>
                                    <div>El Salvador</div>
                                    <div>Equatorial Guinea</div>
                                    <div>Eritrea</div>
                                    <div>Estonia</div>
                                    <div>Ethiopia</div>
                                    <div>Falkland Islands (Malvinas)</div>
                                    <div>Faroe Islands</div>
                                    <div>Fiji</div>
                                    <div>Finland</div>
                                    <div>France</div>
                                    <div>French Guiana</div>
                                    <div>French Polynesia</div>
                                    <div>French Southern Territories</div>
                                    <div>Gabon</div>
                                    <div>Gambia</div>
                                    <div>Georgia</div>
                                    <div>Germany</div>
                                    <div>Ghana</div>
                                    <div>Gibraltar</div>
                                    <div>Greece</div>
                                    <div>Greenland</div>
                                    <div>Grenada</div>
                                    <div>Guadeloupe</div>
                                    <div>Guam</div>
                                    <div>Guatemala</div>
                                    <div>Guernsey</div>
                                    <div>Guinea</div>
                                    <div>Guinea-Bissau</div>
                                    <div>Guyana</div>
                                    <div>Haiti</div>
                                    <div>Heard Island and Mcdonald Islands</div>
                                    <div>Holy See (Vatican City State)</div>
                                    <div>Honduras</div>
                                    <div>Hong Kong</div>
                                    <div>Hungary</div>
                                    <div>Iceland</div>
                                    <div>India</div>
                                    <div>Indonesia</div>
                                    <div>Iran, Islamic Republic Of</div>
                                    <div>Iraq</div>
                                    <div>Ireland</div>
                                    <div>Isle of Man</div>
                                    <div>Israel</div>
                                    <div>Italy</div>
                                    <div>Jamaica</div>
                                    <div>Japan</div>
                                    <div>Jersey</div>
                                    <div>Jordan</div>
                                    <div>Kazakhstan</div>
                                    <div>Kenya</div>
                                    <div>Kiribati</div>
                                    <div>Korea, Republic of</div>
                                    <div>Kuwait</div>
                                    <div>Kyrgyzstan</div>
                                    <div>Latvia</div>
                                    <div>Lebanon</div>
                                    <div>Lesotho</div>
                                    <div>Liberia</div>
                                    <div>Libyan Arab Jamahiriya</div>
                                    <div>Liechtenstein</div>
                                    <div>Lithuania</div>
                                    <div>Luxembourg</div>
                                    <div>Macao</div>
                                    <div>North Macedonia</div>
                                    <div>Madagascar</div>
                                    <div>Malawi</div>
                                    <div>Malaysia</div>
                                    <div>Maldives</div>
                                    <div>Mali</div>
                                    <div>Malta</div>
                                    <div>Marshall Islands</div>
                                    <div>Martinique</div>
                                    <div>Mauritania</div>
                                    <div>Mauritius</div>
                                    <div>Mayotte</div>
                                    <div>Mexico</div>
                                    <div>Micronesia, Federated States of</div>
                                    <div>Republic of Moldova</div>
                                    <div>Monaco</div>
                                    <div>Mongolia</div>
                                    <div>Montserrat</div>
                                    <div>Morocco</div>
                                    <div>Mozambique</div>
                                    <div>Myanmar</div>
                                    <div>Namibia</div>
                                    <div>Nauru</div>
                                    <div>Nepal</div>
                                    <div>Netherlands</div>
                                    <div>Netherlands Antilles</div>
                                    <div>New Caledonia</div>
                                    <div>New Zealand</div>
                                    <div>Nicaragua</div>
                                    <div>Niger</div>
                                    <div>Nigeria</div>
                                    <div>Niue</div>
                                    <div>Norfolk Island</div>
                                    <div>Northern Mariana Islands</div>
                                    <div>Norway</div>
                                    <div>Oman</div>
                                    <div>Pakistan</div>
                                    <div>Palau</div>
                                    <div>Palestinian Territory, Occupied</div>
                                    <div>Panama</div>
                                    <div>Papua New Guinea</div>
                                    <div>Paraguay</div>
                                    <div>Peru</div>
                                    <div>Philippines</div>
                                    <div>Pitcairn Islands</div>
                                    <div>Poland</div>
                                    <div>Portugal</div>
                                    <div>Puerto Rico</div>
                                    <div>Qatar</div>
                                    <div>Reunion</div>
                                    <div>Romania</div>
                                    <div>Russian Federation</div>
                                    <div>Rwanda</div>
                                    <div>Saint Helena</div>
                                    <div>Saint Kitts and Nevis</div>
                                    <div>Saint Lucia</div>
                                    <div>Saint Pierre and Miquelon</div>
                                    <div>Saint Vincent and the Grenadines</div>
                                    <div>Samoa</div>
                                    <div>San Marino</div>
                                    <div>Sao Tome and Principe</div>
                                    <div>Saudi Arabia</div>
                                    <div>Senegal</div>
                                    <div>Serbia and Montenegro</div>
                                    <div>Seychelles</div>
                                    <div>Sierra Leone</div>
                                    <div>Singapore</div>
                                    <div>Slovakia</div>
                                    <div>Slovenia</div>
                                    <div>Solomon Islands</div>
                                    <div>Somalia</div>
                                    <div>South Africa</div>
                                    <div>South Georgia and the South Sandwich Islands</div>
                                    <div>Spain</div>
                                    <div>Sri Lanka</div>
                                    <div>Sudan</div>
                                    <div>Suriname</div>
                                    <div>Svalbard and Jan Mayen</div>
                                    <div>Swaziland</div>
                                    <div>Sweden</div>
                                    <div>Switzerland</div>
                                    <div>Syrian Arab Republic</div>
                                    <div>Taiwan</div>
                                    <div>Tajikistan</div>
                                    <div>Tanzania, United Republic of</div>
                                    <div>Thailand</div>
                                    <div>Timor-Leste</div>
                                    <div>Togo</div>
                                    <div>Tokelau</div>
                                    <div>Tonga</div>
                                    <div>Trinidad and Tobago</div>
                                    <div>Tunisia</div>
                                    <div>Turkey</div>
                                    <div>Turkmenistan</div>
                                    <div>Turks and Caicos Islands</div>
                                    <div>Tuvalu</div>
                                    <div>Uganda</div>
                                    <div>Ukraine</div>
                                    <div>United Arab Emirates</div>
                                    <div>United Kingdom</div>
                                    <div>United States</div>
                                    <div>United States Minor Outlying Islands</div>
                                    <div>Uruguay</div>
                                    <div>Uzbekistan</div>
                                    <div>Vanuatu</div>
                                    <div>Venezuela</div>
                                    <div>Vietnam</div>
                                    <div>Virgin Islands, British</div>
                                    <div>Virgin Islands, U.S"</div>
                                    <div>Wallis and Futuna</div>
                                    <div>Western Sahara</div>
                                    <div>Yemen</div>
                                    <div>Zambia</div>
                                    <div>Zimbabwe</div>
                                </div> --}}
                            <select wire:model="juridic_billing_country" class="select">
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
                            {{-- </div> --}}
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_county" placeholder="Judet">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_city" placeholder="Oras">
                                <span>
                                    @error("juridic_billing_city")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_zipcode" placeholder="Cod Postal">
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
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_shipping_first" placeholder="Nume">
                                <span>
                                    @error("juridic_shipping_first")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_shipping_last" placeholder="Prenume">
                                <span>
                                    @error("juridic_shipping_last")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="tel" wire:model="juridic_shipping_phone" placeholder="Telefon">
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
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_shipping_address1"
                                    placeholder="Address 1*">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_shipping_address2"
                                    placeholder="Address 2 (optional)">
                            </div>
                            <!---------------------------------------------------->

                            {{-- <div class="custom-select">
                                <div class="select-selected">{{ $juridic_shipping_country }}</div>
                                <div class="select-items">
                                    <div>Afghanistan</div>
                                    <div>Åland Islands</div>
                                    <div>Albania</div>
                                    <div>Algeria</div>
                                    <div>American Samoa</div>
                                    <div>Andorra</div>
                                    <div>Angola</div>
                                    <div>Anguilla</div>
                                    <div>Antarctica</div>
                                    <div>Antigua and Barbuda</div>
                                    <div>Argentina</div>
                                    <div>Armenia</div>
                                    <div>Aruba</div>
                                    <div>Australia</div>
                                    <div>Austria</div>
                                    <div>Azerbaijan</div>
                                    <div>Bahamas</div>
                                    <div>Bahrain</div>
                                    <div>Bangladesh</div>
                                    <div>Barbados</div>
                                    <div>Belarus</div>
                                    <div>Belgium</div>
                                    <div>Belize</div>
                                    <div>Benin</div>
                                    <div>Bermuda</div>
                                    <div>Bhutan</div>
                                    <div>Bolivia</div>
                                    <div>Bosnia and Herzegovina</div>
                                    <div>Botswana</div>
                                    <div>Bouvet Island</div>
                                    <div>Brazil</div>
                                    <div>British Indian Ocean Territory</div>
                                    <div>Brunei Darussalam</div>
                                    <div>Bulgaria</div>
                                    <div>Burkina Faso</div>
                                    <div>Burundi</div>
                                    <div>Cambodia</div>
                                    <div>Cameroon</div>
                                    <div>Canada</div>
                                    <div>Cape Verde</div>
                                    <div>Cayman Islands</div>
                                    <div>Central African Republic</div>
                                    <div>Chad</div>
                                    <div>Chile</div>
                                    <div>China</div>
                                    <div>Christmas Island</div>
                                    <div>Cocos (Keeling) Islands</div>
                                    <div>Colombia</div>
                                    <div>Comoros</div>
                                    <div>Congo</div>
                                    <div>Congo, The Democratic Republic of the</div>
                                    <div>Cook Islands</div>
                                    <div>Costa Rica</div>
                                    <div>Croatia</div>
                                    <div>Cuba</div>
                                    <div>Cyprus</div>
                                    <div>Czech Republic</div>
                                    <div>Denmark</div>
                                    <div>Djibouti</div>
                                    <div>Dominica</div>
                                    <div>Dominican Republic</div>
                                    <div>Ecuador</div>
                                    <div>Egypt</div>
                                    <div>El Salvador</div>
                                    <div>Equatorial Guinea</div>
                                    <div>Eritrea</div>
                                    <div>Estonia</div>
                                    <div>Ethiopia</div>
                                    <div>Falkland Islands (Malvinas)</div>
                                    <div>Faroe Islands</div>
                                    <div>Fiji</div>
                                    <div>Finland</div>
                                    <div>France</div>
                                    <div>French Guiana</div>
                                    <div>French Polynesia</div>
                                    <div>French Southern Territories</div>
                                    <div>Gabon</div>
                                    <div>Gambia</div>
                                    <div>Georgia</div>
                                    <div>Germany</div>
                                    <div>Ghana</div>
                                    <div>Gibraltar</div>
                                    <div>Greece</div>
                                    <div>Greenland</div>
                                    <div>Grenada</div>
                                    <div>Guadeloupe</div>
                                    <div>Guam</div>
                                    <div>Guatemala</div>
                                    <div>Guernsey</div>
                                    <div>Guinea</div>
                                    <div>Guinea-Bissau</div>
                                    <div>Guyana</div>
                                    <div>Haiti</div>
                                    <div>Heard Island and Mcdonald Islands</div>
                                    <div>Holy See (Vatican City State)</div>
                                    <div>Honduras</div>
                                    <div>Hong Kong</div>
                                    <div>Hungary</div>
                                    <div>Iceland</div>
                                    <div>India</div>
                                    <div>Indonesia</div>
                                    <div>Iran, Islamic Republic Of</div>
                                    <div>Iraq</div>
                                    <div>Ireland</div>
                                    <div>Isle of Man</div>
                                    <div>Israel</div>
                                    <div>Italy</div>
                                    <div>Jamaica</div>
                                    <div>Japan</div>
                                    <div>Jersey</div>
                                    <div>Jordan</div>
                                    <div>Kazakhstan</div>
                                    <div>Kenya</div>
                                    <div>Kiribati</div>
                                    <div>Korea, Republic of</div>
                                    <div>Kuwait</div>
                                    <div>Kyrgyzstan</div>
                                    <div>Latvia</div>
                                    <div>Lebanon</div>
                                    <div>Lesotho</div>
                                    <div>Liberia</div>
                                    <div>Libyan Arab Jamahiriya</div>
                                    <div>Liechtenstein</div>
                                    <div>Lithuania</div>
                                    <div>Luxembourg</div>
                                    <div>Macao</div>
                                    <div>North Macedonia</div>
                                    <div>Madagascar</div>
                                    <div>Malawi</div>
                                    <div>Malaysia</div>
                                    <div>Maldives</div>
                                    <div>Mali</div>
                                    <div>Malta</div>
                                    <div>Marshall Islands</div>
                                    <div>Martinique</div>
                                    <div>Mauritania</div>
                                    <div>Mauritius</div>
                                    <div>Mayotte</div>
                                    <div>Mexico</div>
                                    <div>Micronesia, Federated States of</div>
                                    <div>Republic of Moldova</div>
                                    <div>Monaco</div>
                                    <div>Mongolia</div>
                                    <div>Montserrat</div>
                                    <div>Morocco</div>
                                    <div>Mozambique</div>
                                    <div>Myanmar</div>
                                    <div>Namibia</div>
                                    <div>Nauru</div>
                                    <div>Nepal</div>
                                    <div>Netherlands</div>
                                    <div>Netherlands Antilles</div>
                                    <div>New Caledonia</div>
                                    <div>New Zealand</div>
                                    <div>Nicaragua</div>
                                    <div>Niger</div>
                                    <div>Nigeria</div>
                                    <div>Niue</div>
                                    <div>Norfolk Island</div>
                                    <div>Northern Mariana Islands</div>
                                    <div>Norway</div>
                                    <div>Oman</div>
                                    <div>Pakistan</div>
                                    <div>Palau</div>
                                    <div>Palestinian Territory, Occupied</div>
                                    <div>Panama</div>
                                    <div>Papua New Guinea</div>
                                    <div>Paraguay</div>
                                    <div>Peru</div>
                                    <div>Philippines</div>
                                    <div>Pitcairn Islands</div>
                                    <div>Poland</div>
                                    <div>Portugal</div>
                                    <div>Puerto Rico</div>
                                    <div>Qatar</div>
                                    <div>Reunion</div>
                                    <div>Romania</div>
                                    <div>Russian Federation</div>
                                    <div>Rwanda</div>
                                    <div>Saint Helena</div>
                                    <div>Saint Kitts and Nevis</div>
                                    <div>Saint Lucia</div>
                                    <div>Saint Pierre and Miquelon</div>
                                    <div>Saint Vincent and the Grenadines</div>
                                    <div>Samoa</div>
                                    <div>San Marino</div>
                                    <div>Sao Tome and Principe</div>
                                    <div>Saudi Arabia</div>
                                    <div>Senegal</div>
                                    <div>Serbia and Montenegro</div>
                                    <div>Seychelles</div>
                                    <div>Sierra Leone</div>
                                    <div>Singapore</div>
                                    <div>Slovakia</div>
                                    <div>Slovenia</div>
                                    <div>Solomon Islands</div>
                                    <div>Somalia</div>
                                    <div>South Africa</div>
                                    <div>South Georgia and the South Sandwich Islands</div>
                                    <div>Spain</div>
                                    <div>Sri Lanka</div>
                                    <div>Sudan</div>
                                    <div>Suriname</div>
                                    <div>Svalbard and Jan Mayen</div>
                                    <div>Swaziland</div>
                                    <div>Sweden</div>
                                    <div>Switzerland</div>
                                    <div>Syrian Arab Republic</div>
                                    <div>Taiwan</div>
                                    <div>Tajikistan</div>
                                    <div>Tanzania, United Republic of</div>
                                    <div>Thailand</div>
                                    <div>Timor-Leste</div>
                                    <div>Togo</div>
                                    <div>Tokelau</div>
                                    <div>Tonga</div>
                                    <div>Trinidad and Tobago</div>
                                    <div>Tunisia</div>
                                    <div>Turkey</div>
                                    <div>Turkmenistan</div>
                                    <div>Turks and Caicos Islands</div>
                                    <div>Tuvalu</div>
                                    <div>Uganda</div>
                                    <div>Ukraine</div>
                                    <div>United Arab Emirates</div>
                                    <div>United Kingdom</div>
                                    <div>United States</div>
                                    <div>United States Minor Outlying Islands</div>
                                    <div>Uruguay</div>
                                    <div>Uzbekistan</div>
                                    <div>Vanuatu</div>
                                    <div>Venezuela</div>
                                    <div>Vietnam</div>
                                    <div>Virgin Islands, British</div>
                                    <div>Virgin Islands, U.S"</div>
                                    <div>Wallis and Futuna</div>
                                    <div>Western Sahara</div>
                                    <div>Yemen</div>
                                    <div>Zambia</div>
                                    <div>Zimbabwe</div>
                                </div> --}}
                            <select wire:model="juridic_shipping_country" class="select">
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
                            {{-- </div> --}}
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_shipping_county" placeholder="Judet">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_shipping_city" placeholder="Oras">
                            </div>
                            <!---------------------------------------------------->
                            <div class="checkout__item">
                                <input type="text" wire:model="juridic_shipping_zipcode"
                                    placeholder="Cod Postal">
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
                    @foreach ($payments as $payment)
                        @if ($payment->name == "Plata cash la livrare" && $cart->final_amount <= "1000")
                            @if ($payment->active)
                                <div class="payment">
                                    <label class="payment__wrapper" for="rtc"
                                        wire:click="togglepayment('rtc')">
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
                            <svg>
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>Pasul anterior
                        </button>
                        <button class="checkout__button" wire:click.prevent="confirm()">
                            Confirma Comanda <svg>
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="section__header">
                        <h2 class="section__title">Verificați detaliile dumneavoastră.</h2>
                    </div>
                    @if ($errorterms && $terms == false)
                        <div class="checkout__error">Pentru a procesa comanda trebuie sa acceptati termenii si
                            conditiile!</div>
                    @endif
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
                                <span class="total__message">Oras:
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
                                <span class="total__message">Oras:
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
                                <span class="total__message">Oras:
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
                                <span class="total__message">Oras:
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
                                        <span class="total__price">
                                            {{ $cartItem->quantity }} x
                                        </span>
                                        @if ($cartItem->product->media->first())
                                            <img class="cart__list--img"
                                                src="/{{ $cartItem->product->media->first()->path }}{{ $cartItem->product->media->first()->name }}"
                                                alt="{{ $cartItem->product->media->first()->path }}">
                                        @else
                                            <img class="cart__list--img" src="/images/store/default/default70.webp"
                                                alt="something wrong">
                                        @endif
                                        <a href="/product/{{ $cartItem->product->id }}" target="_blank"
                                            class="total__name">{{ $cartItem->product->name }}</a>
                                        <span class="total__price">

                                            {{-- {{ $cartItem->product->price }} --}}
                                            <?php $currency = $cartItem->product->product_prices->first()->pricelist->currency->name; ?>
                                            {{ number_format($cartItem->quantity * $cartItem->price, 2, ",", ".") }}
                                            {{ $currency }}
                                        </span>

                                    </div>
                                @endforeach

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
                                            {{ number_format($cart->delivery_price, 2, ",", ".") }}
                                            {{ $currency }}
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
                                <div class="total__item">
                                    <span>total</span>
                                    @if ($cart->voucher)
                                        <span style="text-decoration: line-through; color:red;">
                                            {{ number_format($cart->sum_amount + $cart->delivery_price, 2, ",", ".") }}{{ $currency }}
                                        </span>
                                        {{ number_format($cart->final_amount, 2, ",", ".") }} {{ $currency }}
                                        </span>
                                    @else
                                        <span>{{ number_format($cart->final_amount, 2, ",", ".") }}
                                            {{ $currency }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <!------------ End Checkout List of Forms ------------>
                        <!---------------------------------------------------->
                    </div>
                    <label class="checkout__terms">
                        <input type="checkbox" wire:model="terms" name="terms" id="terms">
                        <span>Sunt de acord cu <a href="{{ url("/terms") }}">termenii si conditiile</a></span>
                    </label>
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
                        <a href="{{ url("/") }}" class="logo">
                            <img src="/images/store/svg/noren-black.svg" alt="logo">
                        </a>
                    </div>
                @endif
                <!------------------- End Step Final ------------------->
                <!------------------------------------------------------>
                <!------------------- Checkout Links ------------------->
                <div class="checkout__header">
                    @if ($step == 2)
                        <a class="checkout__button" wire:click.prevent="previous()">
                            <svg>
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Pasul Anterior
                        </a>
                        <a class="checkout__button" wire:click.prevent="confirm()">
                            Confirma Comanda
                            <svg>
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </a>
                    @elseif ($step == 1)
                        <a class="checkout__link" wire:click.prevent="next()">
                            Pasul următor
                            <svg>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
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
