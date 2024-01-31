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
                                Reseteaza
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
                            {{-- <div class="checkout__item">
                                <input type="text" wire:model="individual_billing_country" placeholder="Tara">
                                <span>
                                    @error("individual_billing_country")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div> --}}
                            <div class="custom-select">
                                <div class="select-selected">Romania</div>
                                <div class="select-items">
                                    <div>Afghanistan</div>
                                    <div>Åland Islands</div>
                                    <div>Albania</div>
                                    <div>Algeria</div>
                                    <div>American Samoa</div>
                                    <div>AndorrA</div>
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
                                    <div>Falkland Islands (Malvinas"</div>
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
                                    <div>Holy See (Vatican City State"</div>
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
                                    <div>Moldova, Republic of</div>
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
                                </div>
                                <select class="hidden-select">
                                    <option value="1">Afghanistan</option>
                                    <option value="2">Åland Islands</option>
                                    <option value="3">Albania</option>
                                    <option value="4">Algeria</option>
                                    <option value="5">American Samoa</option>
                                    <option value="6">AndorrA</option>
                                    <option value="7">Angola</option>
                                    <option value="8">Anguilla</option>
                                    <option value="9">Antarctica</option>
                                    <option value="10">Antigua and Barbuda</option>
                                    <option value="11">Argentina</option>
                                    <option value="12">Armenia</option>
                                    <option value="13">Aruba</option>
                                    <option value="14">Australia</option>
                                    <option value="15">Austria</option>
                                    <option value="16">Azerbaijan</option>
                                    <option value="17">Bahamas</option>
                                    <option value="18">Bahrain</option>
                                    <option value="19">Bangladesh</option>
                                    <option value="20">Barbados</option>
                                    <option value="21">Belarus</option>
                                    <option value="22">Belgium</option>
                                    <option value="23">Belize</option>
                                    <option value="24">Benin</option>
                                    <option value="25">Bermuda</option>
                                    <option value="26">Bhutan</option>
                                    <option value="27">Bolivia</option>
                                    <option value="28">Bosnia and Herzegovina</option>
                                    <option value="29">Botswana</option>
                                    <option value="30">Bouvet Island</option>
                                    <option value="31">Brazil</option>
                                    <option value="32">British Indian Ocean Territory</option>
                                    <option value="33">Brunei Darussalam</option>
                                    <option value="34">Bulgaria</option>
                                    <option value="35">Burkina Faso</option>
                                    <option value="36">Burundi</option>
                                    <option value="37">Cambodia</option>
                                    <option value="38">Cameroon</option>
                                    <option value="39">Canada</option>
                                    <option value="40">Cape Verde</option>
                                    <option value="41">Cayman Islands</option>
                                    <option value="42">Central African Republic</option>
                                    <option value="43">Chad</option>
                                    <option value="44">Chile</option>
                                    <option value="45">China</option>
                                    <option value="46">Christmas Island</option>
                                    <option value="47">Cocos (Keeling) Islands</option>
                                    <option value="48">Colombia</option>
                                    <option value="49">Comoros</option>
                                    <option value="50">Congo</option>
                                    <option value="51">Congo, The Democratic Republic of the</option>
                                    <option value="52">Cook Islands</option>
                                    <option value="53">Costa Rica</option>
                                    <option value="54">Croatia</option>
                                    <option value="56">Cuba</option>
                                    <option value="57">Cyprus</option>
                                    <option value="58">Czech Republic</option>
                                    <option value="59">Denmark</option>
                                    <option value="60">Djibouti</option>
                                    <option value="61">Dominica</option>
                                    <option value="62">Dominican Republic</option>
                                    <option value="63">Ecuador</option>
                                    <option value="64">Egypt</option>
                                    <option value="65">El Salvador</option>
                                    <option value="66">Equatorial Guinea</option>
                                    <option value="67">Eritrea</option>
                                    <option value="68">Estonia</option>
                                    <option value="69">Ethiopia</option>
                                    <option value="70">Falkland Islands (Malvinas"</option>
                                    <option value="71">Faroe Islands</option>
                                    <option value="72">Fiji</option>
                                    <option value="73">Finland</option>
                                    <option value="74">France</option>
                                    <option value="75">French Guiana</option>
                                    <option value="76">French Polynesia</option>
                                    <option value="77">French Southern Territories</option>
                                    <option value="78">Gabon</option>
                                    <option value="79">Gambia</option>
                                    <option value="80">Georgia</option>
                                    <option value="81">Germany</option>
                                    <option value="82">Ghana</option>
                                    <option value="83">Gibraltar</option>
                                    <option value="84">Greece</option>
                                    <option value="85">Greenland</option>
                                    <option value="86">Grenada</option>
                                    <option value="87">Guadeloupe</option>
                                    <option value="88">Guam</option>
                                    <option value="89">Guatemala</option>
                                    <option value="90">Guernsey</option>
                                    <option value="91">Guinea</option>
                                    <option value="92">Guinea-Bissau</option>
                                    <option value="93">Guyana</option>
                                    <option value="94">Haiti</option>
                                    <option value="95">Heard Island and Mcdonald Islands</option>
                                    <option value="96">Holy See (Vatican City State"</option>
                                    <option value="97">Honduras</option>
                                    <option value="98">Hong Kong</option>
                                    <option value="99">Hungary</option>
                                    <option value="100">Iceland</option>
                                    <option value="101">India</option>
                                    <option value="102">Indonesia</option>
                                    <option value="103">Iran, Islamic Republic Of</option>
                                    <option value="104">Iraq</option>
                                    <option value="105">Ireland</option>
                                    <option value="106">Isle of Man</option>
                                    <option value="107">Israel</option>
                                    <option value="108">Italy</option>
                                    <option value="109">Jamaica</option>
                                    <option value="110">Japan</option>
                                    <option value="111">Jersey</option>
                                    <option value="112">Jordan</option>
                                    <option value="113">Kazakhstan</option>
                                    <option value="114">Kenya</option>
                                    <option value="115">Kiribati</option>
                                    <option value="116">Korea, Republic of</option>
                                    <option value="117">Kuwait</option>
                                    <option value="118">Kyrgyzstan</option>
                                    <option value="119">Latvia</option>
                                    <option value="120">Lebanon</option>
                                    <option value="121">Lesotho</option>
                                    <option value="122">Liberia</option>
                                    <option value="123">Libyan Arab Jamahiriya</option>
                                    <option value="124">Liechtenstein</option>
                                    <option value="125">Lithuania</option>
                                    <option value="126">Luxembourg</option>
                                    <option value="127">Macao</option>
                                    <option value="128">North Macedonia</option>
                                    <option value="129">Madagascar</option>
                                    <option value="130">Malawi</option>
                                    <option value="131">Malaysia</option>
                                    <option value="132">Maldives</option>
                                    <option value="133">Mali</option>
                                    <option value="134">Malta</option>
                                    <option value="135">Marshall Islands</option>
                                    <option value="136">Martinique</option>
                                    <option value="137">Mauritania</option>
                                    <option value="138">Mauritius</option>
                                    <option value="139">Mayotte</option>
                                    <option value="140">Mexico</option>
                                    <option value="141">Micronesia, Federated States of</option>
                                    <option value="142">Moldova, Republic of</option>
                                    <option value="143">Monaco</option>
                                    <option value="144">Mongolia</option>
                                    <option value="145">Montserrat</option>
                                    <option value="146">Morocco</option>
                                    <option value="147">Mozambique</option>
                                    <option value="148">Myanmar</option>
                                    <option value="149">Namibia</option>
                                    <option value="150">Nauru</option>
                                    <option value="151">Nepal</option>
                                    <option value="152">Netherlands</option>
                                    <option value="153">Netherlands Antilles</option>
                                    <option value="154">New Caledonia</option>
                                    <option value="155">New Zealand</option>
                                    <option value="156">Nicaragua</option>
                                    <option value="157">Niger</option>
                                    <option value="158">Nigeria</option>
                                    <option value="159">Niue</option>
                                    <option value="160">Norfolk Island</option>
                                    <option value="161">Northern Mariana Islands</option>
                                    <option value="162">Norway</option>
                                    <option value="163">Oman</option>
                                    <option value="164">Pakistan</option>
                                    <option value="165">Palau</option>
                                    <option value="166">Palestinian Territory, Occupied</option>
                                    <option value="167">Panama</option>
                                    <option value="168">Papua New Guinea</option>
                                    <option value="169">Paraguay</option>
                                    <option value="170">Peru</option>
                                    <option value="171">Philippines</option>
                                    <option value="172">Pitcairn Islands</option>
                                    <option value="173">Poland</option>
                                    <option value="174">Portugal</option>
                                    <option value="175">Puerto Rico</option>
                                    <option value="176">Qatar</option>
                                    <option value="177">Reunion</option>
                                    <option value="178" selected>Romania</option>
                                    <option value="179">Russian Federation</option>
                                    <option value="180">Rwanda</option>
                                    <option value="181">Saint Helena</option>
                                    <option value="182">Saint Kitts and Nevis</option>
                                    <option value="183">Saint Lucia</option>
                                    <option value="184">Saint Pierre and Miquelon</option>
                                    <option value="185">Saint Vincent and the Grenadines</option>
                                    <option value="186">Samoa</option>
                                    <option value="187">San Marino</option>
                                    <option value="188">Sao Tome and Principe</option>
                                    <option value="189">Saudi Arabia</option>
                                    <option value="190">Senegal</option>
                                    <option value="191">Serbia and Montenegro</option>
                                    <option value="192">Seychelles</option>
                                    <option value="193">Sierra Leone</option>
                                    <option value="194">Singapore</option>
                                    <option value="195">Slovakia</option>
                                    <option value="196">Slovenia</option>
                                    <option value="197">Solomon Islands</option>
                                    <option value="198">Somalia</option>
                                    <option value="199">South Africa</option>
                                    <option value="200">South Georgia and the South Sandwich Islands</option>
                                    <option value="201">Spain</option>
                                    <option value="202">Sri Lanka</option>
                                    <option value="203">Sudan</option>
                                    <option value="204">Suriname</option>
                                    <option value="205">Svalbard and Jan Mayen</option>
                                    <option value="206">Swaziland</option>
                                    <option value="207">Sweden</option>
                                    <option value="208">Switzerland</option>
                                    <option value="209">Syrian Arab Republic</option>
                                    <option value="210">Taiwan</option>
                                    <option value="211">Tajikistan</option>
                                    <option value="212">Tanzania, United Republic of</option>
                                    <option value="213">Thailand</option>
                                    <option value="214">Timor-Leste</option>
                                    <option value="215">Togo</option>
                                    <option value="216">Tokelau</option>
                                    <option value="217">Tonga</option>
                                    <option value="218">Trinidad and Tobago</option>
                                    <option value="219">Tunisia</option>
                                    <option value="220">Turkey</option>
                                    <option value="221">Turkmenistan</option>
                                    <option value="222">Turks and Caicos Islands</option>
                                    <option value="223">Tuvalu</option>
                                    <option value="224">Uganda</option>
                                    <option value="225">Ukraine</option>
                                    <option value="226">United Arab Emirates</option>
                                    <option value="227">United Kingdom</option>
                                    <option value="228">United States</option>
                                    <option value="229">United States Minor Outlying Islands</option>
                                    <option value="230">Uruguay</option>
                                    <option value="231">Uzbekistan</option>
                                    <option value="232">Vanuatu</option>
                                    <option value="233">Venezuela</option>
                                    <option value="234">Vietnam</option>
                                    <option value="235">Virgin Islands, British</option>
                                    <option value="236">Virgin Islands, U.S"</option>
                                    <option value="237">Wallis and Futuna</option>
                                    <option value="238">Western Sahara</option>
                                    <option value="239">Yemen</option>
                                    <option value="240">Zambia</option>
                                    <option value="241">Zimbabwe</option>
                                </select>
                            </div>
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
                                        @error("individual_shipping_first")
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_last"
                                        placeholder="Prenume">
                                    <span>
                                        @error("individual_shipping_last")
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <!---------------------------------------------------->
                                <div class="checkout__item">
                                    <input type="tel" wire:model="individual_shipping_phone"
                                        placeholder="Telefon">
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
                                {{-- <div class="checkout__item">
                                    <input type="text" wire:model="individual_shipping_country"
                                        placeholder="Tara">
                                    <span>
                                        @error("individual_shipping_country")
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div> --}}
                                <div class="custom-select">
                                    <div class="select-selected">Romania</div>
                                    <div class="select-items">
                                        <div>Afghanistan</div>
                                        <div>Åland Islands</div>
                                        <div>Albania</div>
                                        <div>Algeria</div>
                                        <div>American Samoa</div>
                                        <div>AndorrA</div>
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
                                        <div>Falkland Islands (Malvinas"</div>
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
                                        <div>Holy See (Vatican City State"</div>
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
                                        <div>Moldova, Republic of</div>
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
                                    </div>
                                    <select class="hidden-select">
                                        <option value="1">Afghanistan</option>
                                        <option value="2">Åland Islands</option>
                                        <option value="3">Albania</option>
                                        <option value="4">Algeria</option>
                                        <option value="5">American Samoa</option>
                                        <option value="6">AndorrA</option>
                                        <option value="7">Angola</option>
                                        <option value="8">Anguilla</option>
                                        <option value="9">Antarctica</option>
                                        <option value="10">Antigua and Barbuda</option>
                                        <option value="11">Argentina</option>
                                        <option value="12">Armenia</option>
                                        <option value="13">Aruba</option>
                                        <option value="14">Australia</option>
                                        <option value="15">Austria</option>
                                        <option value="16">Azerbaijan</option>
                                        <option value="17">Bahamas</option>
                                        <option value="18">Bahrain</option>
                                        <option value="19">Bangladesh</option>
                                        <option value="20">Barbados</option>
                                        <option value="21">Belarus</option>
                                        <option value="22">Belgium</option>
                                        <option value="23">Belize</option>
                                        <option value="24">Benin</option>
                                        <option value="25">Bermuda</option>
                                        <option value="26">Bhutan</option>
                                        <option value="27">Bolivia</option>
                                        <option value="28">Bosnia and Herzegovina</option>
                                        <option value="29">Botswana</option>
                                        <option value="30">Bouvet Island</option>
                                        <option value="31">Brazil</option>
                                        <option value="32">British Indian Ocean Territory</option>
                                        <option value="33">Brunei Darussalam</option>
                                        <option value="34">Bulgaria</option>
                                        <option value="35">Burkina Faso</option>
                                        <option value="36">Burundi</option>
                                        <option value="37">Cambodia</option>
                                        <option value="38">Cameroon</option>
                                        <option value="39">Canada</option>
                                        <option value="40">Cape Verde</option>
                                        <option value="41">Cayman Islands</option>
                                        <option value="42">Central African Republic</option>
                                        <option value="43">Chad</option>
                                        <option value="44">Chile</option>
                                        <option value="45">China</option>
                                        <option value="46">Christmas Island</option>
                                        <option value="47">Cocos (Keeling) Islands</option>
                                        <option value="48">Colombia</option>
                                        <option value="49">Comoros</option>
                                        <option value="50">Congo</option>
                                        <option value="51">Congo, The Democratic Republic of the</option>
                                        <option value="52">Cook Islands</option>
                                        <option value="53">Costa Rica</option>
                                        <option value="54">Croatia</option>
                                        <option value="56">Cuba</option>
                                        <option value="57">Cyprus</option>
                                        <option value="58">Czech Republic</option>
                                        <option value="59">Denmark</option>
                                        <option value="60">Djibouti</option>
                                        <option value="61">Dominica</option>
                                        <option value="62">Dominican Republic</option>
                                        <option value="63">Ecuador</option>
                                        <option value="64">Egypt</option>
                                        <option value="65">El Salvador</option>
                                        <option value="66">Equatorial Guinea</option>
                                        <option value="67">Eritrea</option>
                                        <option value="68">Estonia</option>
                                        <option value="69">Ethiopia</option>
                                        <option value="70">Falkland Islands (Malvinas"</option>
                                        <option value="71">Faroe Islands</option>
                                        <option value="72">Fiji</option>
                                        <option value="73">Finland</option>
                                        <option value="74">France</option>
                                        <option value="75">French Guiana</option>
                                        <option value="76">French Polynesia</option>
                                        <option value="77">French Southern Territories</option>
                                        <option value="78">Gabon</option>
                                        <option value="79">Gambia</option>
                                        <option value="80">Georgia</option>
                                        <option value="81">Germany</option>
                                        <option value="82">Ghana</option>
                                        <option value="83">Gibraltar</option>
                                        <option value="84">Greece</option>
                                        <option value="85">Greenland</option>
                                        <option value="86">Grenada</option>
                                        <option value="87">Guadeloupe</option>
                                        <option value="88">Guam</option>
                                        <option value="89">Guatemala</option>
                                        <option value="90">Guernsey</option>
                                        <option value="91">Guinea</option>
                                        <option value="92">Guinea-Bissau</option>
                                        <option value="93">Guyana</option>
                                        <option value="94">Haiti</option>
                                        <option value="95">Heard Island and Mcdonald Islands</option>
                                        <option value="96">Holy See (Vatican City State"</option>
                                        <option value="97">Honduras</option>
                                        <option value="98">Hong Kong</option>
                                        <option value="99">Hungary</option>
                                        <option value="100">Iceland</option>
                                        <option value="101">India</option>
                                        <option value="102">Indonesia</option>
                                        <option value="103">Iran, Islamic Republic Of</option>
                                        <option value="104">Iraq</option>
                                        <option value="105">Ireland</option>
                                        <option value="106">Isle of Man</option>
                                        <option value="107">Israel</option>
                                        <option value="108">Italy</option>
                                        <option value="109">Jamaica</option>
                                        <option value="110">Japan</option>
                                        <option value="111">Jersey</option>
                                        <option value="112">Jordan</option>
                                        <option value="113">Kazakhstan</option>
                                        <option value="114">Kenya</option>
                                        <option value="115">Kiribati</option>
                                        <option value="116">Korea, Republic of</option>
                                        <option value="117">Kuwait</option>
                                        <option value="118">Kyrgyzstan</option>
                                        <option value="119">Latvia</option>
                                        <option value="120">Lebanon</option>
                                        <option value="121">Lesotho</option>
                                        <option value="122">Liberia</option>
                                        <option value="123">Libyan Arab Jamahiriya</option>
                                        <option value="124">Liechtenstein</option>
                                        <option value="125">Lithuania</option>
                                        <option value="126">Luxembourg</option>
                                        <option value="127">Macao</option>
                                        <option value="128">North Macedonia</option>
                                        <option value="129">Madagascar</option>
                                        <option value="130">Malawi</option>
                                        <option value="131">Malaysia</option>
                                        <option value="132">Maldives</option>
                                        <option value="133">Mali</option>
                                        <option value="134">Malta</option>
                                        <option value="135">Marshall Islands</option>
                                        <option value="136">Martinique</option>
                                        <option value="137">Mauritania</option>
                                        <option value="138">Mauritius</option>
                                        <option value="139">Mayotte</option>
                                        <option value="140">Mexico</option>
                                        <option value="141">Micronesia, Federated States of</option>
                                        <option value="142">Moldova, Republic of</option>
                                        <option value="143">Monaco</option>
                                        <option value="144">Mongolia</option>
                                        <option value="145">Montserrat</option>
                                        <option value="146">Morocco</option>
                                        <option value="147">Mozambique</option>
                                        <option value="148">Myanmar</option>
                                        <option value="149">Namibia</option>
                                        <option value="150">Nauru</option>
                                        <option value="151">Nepal</option>
                                        <option value="152">Netherlands</option>
                                        <option value="153">Netherlands Antilles</option>
                                        <option value="154">New Caledonia</option>
                                        <option value="155">New Zealand</option>
                                        <option value="156">Nicaragua</option>
                                        <option value="157">Niger</option>
                                        <option value="158">Nigeria</option>
                                        <option value="159">Niue</option>
                                        <option value="160">Norfolk Island</option>
                                        <option value="161">Northern Mariana Islands</option>
                                        <option value="162">Norway</option>
                                        <option value="163">Oman</option>
                                        <option value="164">Pakistan</option>
                                        <option value="165">Palau</option>
                                        <option value="166">Palestinian Territory, Occupied</option>
                                        <option value="167">Panama</option>
                                        <option value="168">Papua New Guinea</option>
                                        <option value="169">Paraguay</option>
                                        <option value="170">Peru</option>
                                        <option value="171">Philippines</option>
                                        <option value="172">Pitcairn Islands</option>
                                        <option value="173">Poland</option>
                                        <option value="174">Portugal</option>
                                        <option value="175">Puerto Rico</option>
                                        <option value="176">Qatar</option>
                                        <option value="177">Reunion</option>
                                        <option value="178" selected>Romania</option>
                                        <option value="179">Russian Federation</option>
                                        <option value="180">Rwanda</option>
                                        <option value="181">Saint Helena</option>
                                        <option value="182">Saint Kitts and Nevis</option>
                                        <option value="183">Saint Lucia</option>
                                        <option value="184">Saint Pierre and Miquelon</option>
                                        <option value="185">Saint Vincent and the Grenadines</option>
                                        <option value="186">Samoa</option>
                                        <option value="187">San Marino</option>
                                        <option value="188">Sao Tome and Principe</option>
                                        <option value="189">Saudi Arabia</option>
                                        <option value="190">Senegal</option>
                                        <option value="191">Serbia and Montenegro</option>
                                        <option value="192">Seychelles</option>
                                        <option value="193">Sierra Leone</option>
                                        <option value="194">Singapore</option>
                                        <option value="195">Slovakia</option>
                                        <option value="196">Slovenia</option>
                                        <option value="197">Solomon Islands</option>
                                        <option value="198">Somalia</option>
                                        <option value="199">South Africa</option>
                                        <option value="200">South Georgia and the South Sandwich Islands</option>
                                        <option value="201">Spain</option>
                                        <option value="202">Sri Lanka</option>
                                        <option value="203">Sudan</option>
                                        <option value="204">Suriname</option>
                                        <option value="205">Svalbard and Jan Mayen</option>
                                        <option value="206">Swaziland</option>
                                        <option value="207">Sweden</option>
                                        <option value="208">Switzerland</option>
                                        <option value="209">Syrian Arab Republic</option>
                                        <option value="210">Taiwan</option>
                                        <option value="211">Tajikistan</option>
                                        <option value="212">Tanzania, United Republic of</option>
                                        <option value="213">Thailand</option>
                                        <option value="214">Timor-Leste</option>
                                        <option value="215">Togo</option>
                                        <option value="216">Tokelau</option>
                                        <option value="217">Tonga</option>
                                        <option value="218">Trinidad and Tobago</option>
                                        <option value="219">Tunisia</option>
                                        <option value="220">Turkey</option>
                                        <option value="221">Turkmenistan</option>
                                        <option value="222">Turks and Caicos Islands</option>
                                        <option value="223">Tuvalu</option>
                                        <option value="224">Uganda</option>
                                        <option value="225">Ukraine</option>
                                        <option value="226">United Arab Emirates</option>
                                        <option value="227">United Kingdom</option>
                                        <option value="228">United States</option>
                                        <option value="229">United States Minor Outlying Islands</option>
                                        <option value="230">Uruguay</option>
                                        <option value="231">Uzbekistan</option>
                                        <option value="232">Vanuatu</option>
                                        <option value="233">Venezuela</option>
                                        <option value="234">Vietnam</option>
                                        <option value="235">Virgin Islands, British</option>
                                        <option value="236">Virgin Islands, U.S"</option>
                                        <option value="237">Wallis and Futuna</option>
                                        <option value="238">Western Sahara</option>
                                        <option value="239">Yemen</option>
                                        <option value="240">Zambia</option>
                                        <option value="241">Zimbabwe</option>
                                    </select>
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
                            {{-- <div class="checkout__item">
                                <input type="text" wire:model="juridic_billing_country" placeholder="Tara">
                                <span>
                                    @error("juridic_billing_country")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div> --}}
                            <div class="custom-select">
                                <div class="select-selected">Romania</div>
                                <div class="select-items">
                                    <div>Afghanistan</div>
                                    <div>Åland Islands</div>
                                    <div>Albania</div>
                                    <div>Algeria</div>
                                    <div>American Samoa</div>
                                    <div>AndorrA</div>
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
                                    <div>Falkland Islands (Malvinas"</div>
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
                                    <div>Holy See (Vatican City State"</div>
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
                                    <div>Moldova, Republic of</div>
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
                                </div>
                                <select class="hidden-select">
                                    <option value="1">Afghanistan</option>
                                    <option value="2">Åland Islands</option>
                                    <option value="3">Albania</option>
                                    <option value="4">Algeria</option>
                                    <option value="5">American Samoa</option>
                                    <option value="6">AndorrA</option>
                                    <option value="7">Angola</option>
                                    <option value="8">Anguilla</option>
                                    <option value="9">Antarctica</option>
                                    <option value="10">Antigua and Barbuda</option>
                                    <option value="11">Argentina</option>
                                    <option value="12">Armenia</option>
                                    <option value="13">Aruba</option>
                                    <option value="14">Australia</option>
                                    <option value="15">Austria</option>
                                    <option value="16">Azerbaijan</option>
                                    <option value="17">Bahamas</option>
                                    <option value="18">Bahrain</option>
                                    <option value="19">Bangladesh</option>
                                    <option value="20">Barbados</option>
                                    <option value="21">Belarus</option>
                                    <option value="22">Belgium</option>
                                    <option value="23">Belize</option>
                                    <option value="24">Benin</option>
                                    <option value="25">Bermuda</option>
                                    <option value="26">Bhutan</option>
                                    <option value="27">Bolivia</option>
                                    <option value="28">Bosnia and Herzegovina</option>
                                    <option value="29">Botswana</option>
                                    <option value="30">Bouvet Island</option>
                                    <option value="31">Brazil</option>
                                    <option value="32">British Indian Ocean Territory</option>
                                    <option value="33">Brunei Darussalam</option>
                                    <option value="34">Bulgaria</option>
                                    <option value="35">Burkina Faso</option>
                                    <option value="36">Burundi</option>
                                    <option value="37">Cambodia</option>
                                    <option value="38">Cameroon</option>
                                    <option value="39">Canada</option>
                                    <option value="40">Cape Verde</option>
                                    <option value="41">Cayman Islands</option>
                                    <option value="42">Central African Republic</option>
                                    <option value="43">Chad</option>
                                    <option value="44">Chile</option>
                                    <option value="45">China</option>
                                    <option value="46">Christmas Island</option>
                                    <option value="47">Cocos (Keeling) Islands</option>
                                    <option value="48">Colombia</option>
                                    <option value="49">Comoros</option>
                                    <option value="50">Congo</option>
                                    <option value="51">Congo, The Democratic Republic of the</option>
                                    <option value="52">Cook Islands</option>
                                    <option value="53">Costa Rica</option>
                                    <option value="54">Croatia</option>
                                    <option value="56">Cuba</option>
                                    <option value="57">Cyprus</option>
                                    <option value="58">Czech Republic</option>
                                    <option value="59">Denmark</option>
                                    <option value="60">Djibouti</option>
                                    <option value="61">Dominica</option>
                                    <option value="62">Dominican Republic</option>
                                    <option value="63">Ecuador</option>
                                    <option value="64">Egypt</option>
                                    <option value="65">El Salvador</option>
                                    <option value="66">Equatorial Guinea</option>
                                    <option value="67">Eritrea</option>
                                    <option value="68">Estonia</option>
                                    <option value="69">Ethiopia</option>
                                    <option value="70">Falkland Islands (Malvinas"</option>
                                    <option value="71">Faroe Islands</option>
                                    <option value="72">Fiji</option>
                                    <option value="73">Finland</option>
                                    <option value="74">France</option>
                                    <option value="75">French Guiana</option>
                                    <option value="76">French Polynesia</option>
                                    <option value="77">French Southern Territories</option>
                                    <option value="78">Gabon</option>
                                    <option value="79">Gambia</option>
                                    <option value="80">Georgia</option>
                                    <option value="81">Germany</option>
                                    <option value="82">Ghana</option>
                                    <option value="83">Gibraltar</option>
                                    <option value="84">Greece</option>
                                    <option value="85">Greenland</option>
                                    <option value="86">Grenada</option>
                                    <option value="87">Guadeloupe</option>
                                    <option value="88">Guam</option>
                                    <option value="89">Guatemala</option>
                                    <option value="90">Guernsey</option>
                                    <option value="91">Guinea</option>
                                    <option value="92">Guinea-Bissau</option>
                                    <option value="93">Guyana</option>
                                    <option value="94">Haiti</option>
                                    <option value="95">Heard Island and Mcdonald Islands</option>
                                    <option value="96">Holy See (Vatican City State"</option>
                                    <option value="97">Honduras</option>
                                    <option value="98">Hong Kong</option>
                                    <option value="99">Hungary</option>
                                    <option value="100">Iceland</option>
                                    <option value="101">India</option>
                                    <option value="102">Indonesia</option>
                                    <option value="103">Iran, Islamic Republic Of</option>
                                    <option value="104">Iraq</option>
                                    <option value="105">Ireland</option>
                                    <option value="106">Isle of Man</option>
                                    <option value="107">Israel</option>
                                    <option value="108">Italy</option>
                                    <option value="109">Jamaica</option>
                                    <option value="110">Japan</option>
                                    <option value="111">Jersey</option>
                                    <option value="112">Jordan</option>
                                    <option value="113">Kazakhstan</option>
                                    <option value="114">Kenya</option>
                                    <option value="115">Kiribati</option>
                                    <option value="116">Korea, Republic of</option>
                                    <option value="117">Kuwait</option>
                                    <option value="118">Kyrgyzstan</option>
                                    <option value="119">Latvia</option>
                                    <option value="120">Lebanon</option>
                                    <option value="121">Lesotho</option>
                                    <option value="122">Liberia</option>
                                    <option value="123">Libyan Arab Jamahiriya</option>
                                    <option value="124">Liechtenstein</option>
                                    <option value="125">Lithuania</option>
                                    <option value="126">Luxembourg</option>
                                    <option value="127">Macao</option>
                                    <option value="128">North Macedonia</option>
                                    <option value="129">Madagascar</option>
                                    <option value="130">Malawi</option>
                                    <option value="131">Malaysia</option>
                                    <option value="132">Maldives</option>
                                    <option value="133">Mali</option>
                                    <option value="134">Malta</option>
                                    <option value="135">Marshall Islands</option>
                                    <option value="136">Martinique</option>
                                    <option value="137">Mauritania</option>
                                    <option value="138">Mauritius</option>
                                    <option value="139">Mayotte</option>
                                    <option value="140">Mexico</option>
                                    <option value="141">Micronesia, Federated States of</option>
                                    <option value="142">Moldova, Republic of</option>
                                    <option value="143">Monaco</option>
                                    <option value="144">Mongolia</option>
                                    <option value="145">Montserrat</option>
                                    <option value="146">Morocco</option>
                                    <option value="147">Mozambique</option>
                                    <option value="148">Myanmar</option>
                                    <option value="149">Namibia</option>
                                    <option value="150">Nauru</option>
                                    <option value="151">Nepal</option>
                                    <option value="152">Netherlands</option>
                                    <option value="153">Netherlands Antilles</option>
                                    <option value="154">New Caledonia</option>
                                    <option value="155">New Zealand</option>
                                    <option value="156">Nicaragua</option>
                                    <option value="157">Niger</option>
                                    <option value="158">Nigeria</option>
                                    <option value="159">Niue</option>
                                    <option value="160">Norfolk Island</option>
                                    <option value="161">Northern Mariana Islands</option>
                                    <option value="162">Norway</option>
                                    <option value="163">Oman</option>
                                    <option value="164">Pakistan</option>
                                    <option value="165">Palau</option>
                                    <option value="166">Palestinian Territory, Occupied</option>
                                    <option value="167">Panama</option>
                                    <option value="168">Papua New Guinea</option>
                                    <option value="169">Paraguay</option>
                                    <option value="170">Peru</option>
                                    <option value="171">Philippines</option>
                                    <option value="172">Pitcairn Islands</option>
                                    <option value="173">Poland</option>
                                    <option value="174">Portugal</option>
                                    <option value="175">Puerto Rico</option>
                                    <option value="176">Qatar</option>
                                    <option value="177">Reunion</option>
                                    <option value="178" selected>Romania</option>
                                    <option value="179">Russian Federation</option>
                                    <option value="180">Rwanda</option>
                                    <option value="181">Saint Helena</option>
                                    <option value="182">Saint Kitts and Nevis</option>
                                    <option value="183">Saint Lucia</option>
                                    <option value="184">Saint Pierre and Miquelon</option>
                                    <option value="185">Saint Vincent and the Grenadines</option>
                                    <option value="186">Samoa</option>
                                    <option value="187">San Marino</option>
                                    <option value="188">Sao Tome and Principe</option>
                                    <option value="189">Saudi Arabia</option>
                                    <option value="190">Senegal</option>
                                    <option value="191">Serbia and Montenegro</option>
                                    <option value="192">Seychelles</option>
                                    <option value="193">Sierra Leone</option>
                                    <option value="194">Singapore</option>
                                    <option value="195">Slovakia</option>
                                    <option value="196">Slovenia</option>
                                    <option value="197">Solomon Islands</option>
                                    <option value="198">Somalia</option>
                                    <option value="199">South Africa</option>
                                    <option value="200">South Georgia and the South Sandwich Islands</option>
                                    <option value="201">Spain</option>
                                    <option value="202">Sri Lanka</option>
                                    <option value="203">Sudan</option>
                                    <option value="204">Suriname</option>
                                    <option value="205">Svalbard and Jan Mayen</option>
                                    <option value="206">Swaziland</option>
                                    <option value="207">Sweden</option>
                                    <option value="208">Switzerland</option>
                                    <option value="209">Syrian Arab Republic</option>
                                    <option value="210">Taiwan</option>
                                    <option value="211">Tajikistan</option>
                                    <option value="212">Tanzania, United Republic of</option>
                                    <option value="213">Thailand</option>
                                    <option value="214">Timor-Leste</option>
                                    <option value="215">Togo</option>
                                    <option value="216">Tokelau</option>
                                    <option value="217">Tonga</option>
                                    <option value="218">Trinidad and Tobago</option>
                                    <option value="219">Tunisia</option>
                                    <option value="220">Turkey</option>
                                    <option value="221">Turkmenistan</option>
                                    <option value="222">Turks and Caicos Islands</option>
                                    <option value="223">Tuvalu</option>
                                    <option value="224">Uganda</option>
                                    <option value="225">Ukraine</option>
                                    <option value="226">United Arab Emirates</option>
                                    <option value="227">United Kingdom</option>
                                    <option value="228">United States</option>
                                    <option value="229">United States Minor Outlying Islands</option>
                                    <option value="230">Uruguay</option>
                                    <option value="231">Uzbekistan</option>
                                    <option value="232">Vanuatu</option>
                                    <option value="233">Venezuela</option>
                                    <option value="234">Vietnam</option>
                                    <option value="235">Virgin Islands, British</option>
                                    <option value="236">Virgin Islands, U.S"</option>
                                    <option value="237">Wallis and Futuna</option>
                                    <option value="238">Western Sahara</option>
                                    <option value="239">Yemen</option>
                                    <option value="240">Zambia</option>
                                    <option value="241">Zimbabwe</option>
                                </select>
                            </div>
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
                                {{-- <div class="checkout__item">
                                    <input type="text" placeholder="Tara">
                                </div> --}}
                                <div class="custom-select">
                                    <div class="select-selected">Romania</div>
                                    <div class="select-items">
                                        <div>Afghanistan</div>
                                        <div>Åland Islands</div>
                                        <div>Albania</div>
                                        <div>Algeria</div>
                                        <div>American Samoa</div>
                                        <div>AndorrA</div>
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
                                        <div>Falkland Islands (Malvinas"</div>
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
                                        <div>Holy See (Vatican City State"</div>
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
                                        <div>Moldova, Republic of</div>
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
                                    </div>
                                    <select class="hidden-select">
                                        <option value="1">Afghanistan</option>
                                        <option value="2">Åland Islands</option>
                                        <option value="3">Albania</option>
                                        <option value="4">Algeria</option>
                                        <option value="5">American Samoa</option>
                                        <option value="6">AndorrA</option>
                                        <option value="7">Angola</option>
                                        <option value="8">Anguilla</option>
                                        <option value="9">Antarctica</option>
                                        <option value="10">Antigua and Barbuda</option>
                                        <option value="11">Argentina</option>
                                        <option value="12">Armenia</option>
                                        <option value="13">Aruba</option>
                                        <option value="14">Australia</option>
                                        <option value="15">Austria</option>
                                        <option value="16">Azerbaijan</option>
                                        <option value="17">Bahamas</option>
                                        <option value="18">Bahrain</option>
                                        <option value="19">Bangladesh</option>
                                        <option value="20">Barbados</option>
                                        <option value="21">Belarus</option>
                                        <option value="22">Belgium</option>
                                        <option value="23">Belize</option>
                                        <option value="24">Benin</option>
                                        <option value="25">Bermuda</option>
                                        <option value="26">Bhutan</option>
                                        <option value="27">Bolivia</option>
                                        <option value="28">Bosnia and Herzegovina</option>
                                        <option value="29">Botswana</option>
                                        <option value="30">Bouvet Island</option>
                                        <option value="31">Brazil</option>
                                        <option value="32">British Indian Ocean Territory</option>
                                        <option value="33">Brunei Darussalam</option>
                                        <option value="34">Bulgaria</option>
                                        <option value="35">Burkina Faso</option>
                                        <option value="36">Burundi</option>
                                        <option value="37">Cambodia</option>
                                        <option value="38">Cameroon</option>
                                        <option value="39">Canada</option>
                                        <option value="40">Cape Verde</option>
                                        <option value="41">Cayman Islands</option>
                                        <option value="42">Central African Republic</option>
                                        <option value="43">Chad</option>
                                        <option value="44">Chile</option>
                                        <option value="45">China</option>
                                        <option value="46">Christmas Island</option>
                                        <option value="47">Cocos (Keeling) Islands</option>
                                        <option value="48">Colombia</option>
                                        <option value="49">Comoros</option>
                                        <option value="50">Congo</option>
                                        <option value="51">Congo, The Democratic Republic of the</option>
                                        <option value="52">Cook Islands</option>
                                        <option value="53">Costa Rica</option>
                                        <option value="54">Croatia</option>
                                        <option value="56">Cuba</option>
                                        <option value="57">Cyprus</option>
                                        <option value="58">Czech Republic</option>
                                        <option value="59">Denmark</option>
                                        <option value="60">Djibouti</option>
                                        <option value="61">Dominica</option>
                                        <option value="62">Dominican Republic</option>
                                        <option value="63">Ecuador</option>
                                        <option value="64">Egypt</option>
                                        <option value="65">El Salvador</option>
                                        <option value="66">Equatorial Guinea</option>
                                        <option value="67">Eritrea</option>
                                        <option value="68">Estonia</option>
                                        <option value="69">Ethiopia</option>
                                        <option value="70">Falkland Islands (Malvinas"</option>
                                        <option value="71">Faroe Islands</option>
                                        <option value="72">Fiji</option>
                                        <option value="73">Finland</option>
                                        <option value="74">France</option>
                                        <option value="75">French Guiana</option>
                                        <option value="76">French Polynesia</option>
                                        <option value="77">French Southern Territories</option>
                                        <option value="78">Gabon</option>
                                        <option value="79">Gambia</option>
                                        <option value="80">Georgia</option>
                                        <option value="81">Germany</option>
                                        <option value="82">Ghana</option>
                                        <option value="83">Gibraltar</option>
                                        <option value="84">Greece</option>
                                        <option value="85">Greenland</option>
                                        <option value="86">Grenada</option>
                                        <option value="87">Guadeloupe</option>
                                        <option value="88">Guam</option>
                                        <option value="89">Guatemala</option>
                                        <option value="90">Guernsey</option>
                                        <option value="91">Guinea</option>
                                        <option value="92">Guinea-Bissau</option>
                                        <option value="93">Guyana</option>
                                        <option value="94">Haiti</option>
                                        <option value="95">Heard Island and Mcdonald Islands</option>
                                        <option value="96">Holy See (Vatican City State"</option>
                                        <option value="97">Honduras</option>
                                        <option value="98">Hong Kong</option>
                                        <option value="99">Hungary</option>
                                        <option value="100">Iceland</option>
                                        <option value="101">India</option>
                                        <option value="102">Indonesia</option>
                                        <option value="103">Iran, Islamic Republic Of</option>
                                        <option value="104">Iraq</option>
                                        <option value="105">Ireland</option>
                                        <option value="106">Isle of Man</option>
                                        <option value="107">Israel</option>
                                        <option value="108">Italy</option>
                                        <option value="109">Jamaica</option>
                                        <option value="110">Japan</option>
                                        <option value="111">Jersey</option>
                                        <option value="112">Jordan</option>
                                        <option value="113">Kazakhstan</option>
                                        <option value="114">Kenya</option>
                                        <option value="115">Kiribati</option>
                                        <option value="116">Korea, Republic of</option>
                                        <option value="117">Kuwait</option>
                                        <option value="118">Kyrgyzstan</option>
                                        <option value="119">Latvia</option>
                                        <option value="120">Lebanon</option>
                                        <option value="121">Lesotho</option>
                                        <option value="122">Liberia</option>
                                        <option value="123">Libyan Arab Jamahiriya</option>
                                        <option value="124">Liechtenstein</option>
                                        <option value="125">Lithuania</option>
                                        <option value="126">Luxembourg</option>
                                        <option value="127">Macao</option>
                                        <option value="128">North Macedonia</option>
                                        <option value="129">Madagascar</option>
                                        <option value="130">Malawi</option>
                                        <option value="131">Malaysia</option>
                                        <option value="132">Maldives</option>
                                        <option value="133">Mali</option>
                                        <option value="134">Malta</option>
                                        <option value="135">Marshall Islands</option>
                                        <option value="136">Martinique</option>
                                        <option value="137">Mauritania</option>
                                        <option value="138">Mauritius</option>
                                        <option value="139">Mayotte</option>
                                        <option value="140">Mexico</option>
                                        <option value="141">Micronesia, Federated States of</option>
                                        <option value="142">Moldova, Republic of</option>
                                        <option value="143">Monaco</option>
                                        <option value="144">Mongolia</option>
                                        <option value="145">Montserrat</option>
                                        <option value="146">Morocco</option>
                                        <option value="147">Mozambique</option>
                                        <option value="148">Myanmar</option>
                                        <option value="149">Namibia</option>
                                        <option value="150">Nauru</option>
                                        <option value="151">Nepal</option>
                                        <option value="152">Netherlands</option>
                                        <option value="153">Netherlands Antilles</option>
                                        <option value="154">New Caledonia</option>
                                        <option value="155">New Zealand</option>
                                        <option value="156">Nicaragua</option>
                                        <option value="157">Niger</option>
                                        <option value="158">Nigeria</option>
                                        <option value="159">Niue</option>
                                        <option value="160">Norfolk Island</option>
                                        <option value="161">Northern Mariana Islands</option>
                                        <option value="162">Norway</option>
                                        <option value="163">Oman</option>
                                        <option value="164">Pakistan</option>
                                        <option value="165">Palau</option>
                                        <option value="166">Palestinian Territory, Occupied</option>
                                        <option value="167">Panama</option>
                                        <option value="168">Papua New Guinea</option>
                                        <option value="169">Paraguay</option>
                                        <option value="170">Peru</option>
                                        <option value="171">Philippines</option>
                                        <option value="172">Pitcairn Islands</option>
                                        <option value="173">Poland</option>
                                        <option value="174">Portugal</option>
                                        <option value="175">Puerto Rico</option>
                                        <option value="176">Qatar</option>
                                        <option value="177">Reunion</option>
                                        <option value="178" selected>Romania</option>
                                        <option value="179">Russian Federation</option>
                                        <option value="180">Rwanda</option>
                                        <option value="181">Saint Helena</option>
                                        <option value="182">Saint Kitts and Nevis</option>
                                        <option value="183">Saint Lucia</option>
                                        <option value="184">Saint Pierre and Miquelon</option>
                                        <option value="185">Saint Vincent and the Grenadines</option>
                                        <option value="186">Samoa</option>
                                        <option value="187">San Marino</option>
                                        <option value="188">Sao Tome and Principe</option>
                                        <option value="189">Saudi Arabia</option>
                                        <option value="190">Senegal</option>
                                        <option value="191">Serbia and Montenegro</option>
                                        <option value="192">Seychelles</option>
                                        <option value="193">Sierra Leone</option>
                                        <option value="194">Singapore</option>
                                        <option value="195">Slovakia</option>
                                        <option value="196">Slovenia</option>
                                        <option value="197">Solomon Islands</option>
                                        <option value="198">Somalia</option>
                                        <option value="199">South Africa</option>
                                        <option value="200">South Georgia and the South Sandwich Islands</option>
                                        <option value="201">Spain</option>
                                        <option value="202">Sri Lanka</option>
                                        <option value="203">Sudan</option>
                                        <option value="204">Suriname</option>
                                        <option value="205">Svalbard and Jan Mayen</option>
                                        <option value="206">Swaziland</option>
                                        <option value="207">Sweden</option>
                                        <option value="208">Switzerland</option>
                                        <option value="209">Syrian Arab Republic</option>
                                        <option value="210">Taiwan</option>
                                        <option value="211">Tajikistan</option>
                                        <option value="212">Tanzania, United Republic of</option>
                                        <option value="213">Thailand</option>
                                        <option value="214">Timor-Leste</option>
                                        <option value="215">Togo</option>
                                        <option value="216">Tokelau</option>
                                        <option value="217">Tonga</option>
                                        <option value="218">Trinidad and Tobago</option>
                                        <option value="219">Tunisia</option>
                                        <option value="220">Turkey</option>
                                        <option value="221">Turkmenistan</option>
                                        <option value="222">Turks and Caicos Islands</option>
                                        <option value="223">Tuvalu</option>
                                        <option value="224">Uganda</option>
                                        <option value="225">Ukraine</option>
                                        <option value="226">United Arab Emirates</option>
                                        <option value="227">United Kingdom</option>
                                        <option value="228">United States</option>
                                        <option value="229">United States Minor Outlying Islands</option>
                                        <option value="230">Uruguay</option>
                                        <option value="231">Uzbekistan</option>
                                        <option value="232">Vanuatu</option>
                                        <option value="233">Venezuela</option>
                                        <option value="234">Vietnam</option>
                                        <option value="235">Virgin Islands, British</option>
                                        <option value="236">Virgin Islands, U.S"</option>
                                        <option value="237">Wallis and Futuna</option>
                                        <option value="238">Western Sahara</option>
                                        <option value="239">Yemen</option>
                                        <option value="240">Zambia</option>
                                        <option value="241">Zimbabwe</option>
                                    </select>
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
                            Pasul anterior
                        </button>
                        <button class="checkout__button" wire:click.prevent="confirm()">
                            Confirma Comanda
                        </button>
                    </div>
                    <div class="section__header">
                        <h2 class="section__title">Verificați detaliile dumneavoastră.</h2>
                    </div>

                    <div class="checkout__error">A iesit o eroare oarecare. Te rugam sa verifici.</div>
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
                                        {{ number_format($cart->delivery_price, 2, ",", ".") }} {{ $currency }}
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
                        <input type="checkbox" name="terms" id="terms">
                        <span>Sunt de acord cu termeni si conditii</span>
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
                <div class="checkout__navigation">
                    @if ($step == 2)
                        <a class="checkout__link" wire:click.prevent="previous()">
                            <svg>
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Pasul Anterior
                        </a>
                        <a class="checkout__link" wire:click.prevent="confirm()">
                            Confirma Comanda
                            <svg>
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </a>
                    @elseif ($step == 1)
                        <a class="checkout__link" style="margin: auto" wire:click.prevent="next()">
                            Pasul următor
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

    <script>
        function initializeCustomSelects() {
            var customSelects = document.querySelectorAll(".custom-select");

            customSelects.forEach(function(select) {
                var selected = select.querySelector(".select-selected");
                var optionsContainer = select.querySelector(".select-items");
                var hiddenSelect = select.querySelector(".hidden-select");

                selected.addEventListener("click", function() {
                    optionsContainer.style.display =
                        optionsContainer.style.display === "block" ? "none" : "block";
                });

                optionsContainer.addEventListener("click", function(e) {
                    if (e.target.tagName === "DIV") {
                        selected.innerHTML = e.target.innerText;
                        optionsContainer.style.display = "none";
                        hiddenSelect.value = e.target.innerText;
                    }
                });

                window.addEventListener("click", function(e) {
                    if (!select.contains(e.target)) {
                        optionsContainer.style.display = "none";
                    }
                });
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            initializeCustomSelects();

            document.body.addEventListener("click", function() {
                // Reinițializează dropdown-urile personalizate la fiecare clic pe corpul documentului
                initializeCustomSelects();
            });
        });
    </script>
</div>
