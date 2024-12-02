<x-store-head :canonical="'contact'" :title="' Contactează-ne'" :description="'Contactează-ne'"/>
@livewire('store-header')

<main>
  <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" defer></script>
  <script type="text/javascript">
    var onloadCallback = function() {
      grecaptcha.render('html_element', {
        'sitekey': '6LchwKgpAAAAAML77tNGuCi7R5k5eQrqszG5DMZ4',
        'callback': verifyCallback,
        'theme': 'light', // Poți seta tema aici
        'hl': 'ro_RO'
      });
    };

    var verifyCallback = function(response) {
      if (response) {
        // reCAPTCHA a fost completat cu succes, schimbă tipul butonului la "submit"
        document.getElementById('submit_button').type = 'submit';
        document.getElementById('submit_button').disabled = false;

      } else {
        // reCAPTCHA nu a fost completat, schimbă tipul butonului la "button"
        document.getElementById('submit_button').type = 'button';
        document.getElementById('submit_button').disabled = true;
        alert("Vă rugăm să completați reCAPTCHA-ul.");
      }
    };
  </script>
	<!---------------------------------------------------------->
	<!------------------------Breadcrumbs----------------------->
	<section>
		<div class="breadcrumbs container">
			<a class="breadcrumbs__link" href="{{ url("/") }}">
				Acasă
			</a>
			<a class="breadcrumbs__link" href="{{ url("/contact") }}">
				Contacte
			</a>
		</div>
	</section>
	<!----------------------End Breadcrumbs--------------------->
	<!---------------------------------------------------------->
	<!----------------------Section Header---------------------->
	<h1 class="section__title">
		Contactează-ne
	</h1>
	<p class="section__text">
		Completează formularul sau contactează-ne direct pe e-mail <a href="#">contact@noren.ro</a>.
	</p>
	<!--------------------End Section Header-------------------->
	<!---------------------------------------------------------->
	<!-----------------------Contact Form----------------------->
	<section class="contact container">
		<form class="checkout__form active" action="https://webto.salesforce.com/servlet/servlet.WebToCase?encoding=UTF-8&orgId=00D09000008XPQu" method="POST">
      <input type=hidden name="retURL" value="{{ URL("/redirect") }}">
      <input type=hidden name='captcha_settings' value='{"keyname":"norenro","fallback":"true","orgId":"00D09000008XPQu","ts":""}'>
      <input type=hidden name="orgid" value="00D09000008XPQu">
      <input  id="00N9N000000QGVe" value="{{ app('global_site_url') }}" name="00N9N000000QGVe" type="hidden" />
      <input  id="type" type="hidden" name="type" value="Store Case" />
      <input type="hidden" name="subject" id="subject" value="subject">
      <!--  ----------------------------------------------------------------------  -->
      <!--  NOTE: These fields are optional debugging elements. Please uncomment    -->
      <!--  these lines if you wish to test in debug mode.                          -->
        {{-- <input type="hidden" name="debug" value=1> --}}
        {{-- <input type="hidden" name="debugEmail" value="stanislav.cortac@eztemcorp.com"> --}}
      <!--  ----------------------------------------------------------------------  -->
      <div class="checkout__item checkout__item--required" id="nameParent">
        <input type="text" maxlength="100" name="name" placeholder="name"id="name" autocomplete="given-name" required>
        <label for="name">Nume</label>
        <span></span>
      </div>

      <div class="checkout__item" id="companyParent">
        <input type="text" maxlength="100" name="company" id="company" placeholder="company" autocomplete="company">
        <label for="company">Numele Companiei</label>
        <span></span>
      </div>

      <div class="checkout__item checkout__item--required" id="emailParent">
        <input type="text" maxlength="100" name="email" id="email" placeholder="email" autocomplete="email" required>
        <label for="email">Email</label>
        <span></span>
      </div>

      <div class="checkout__item">
        <input type="text" maxlength="100" id="00N9N000000QGVj" maxlength="255" name="00N9N000000QGVj" placeholder="Numarul Comenzii" autocomplete="off" >
        <label for="00N9N000000QGVj">Numărul Comenzii</label>
        <span></span>
      </div>

      <div style="grid-column: 1/3" class="checkout__item checkout__item--required" id="message">
        <textarea style="height: auto; padding: 10px 20px;" name="description" rows="15" maxlength="5000" required placeholder="Spune-ne mai multe. Incepe sa scrii aici..."></textarea>
        <label for="00N9N000000QGVj">Mesaj</label>
        <span></span>
      </div>

      <div class="g-recaptcha" style="grid-column: 1/3" data-sitekey="6LfwYJkpAAAAAINvUbZhqEPmiXVLH7kWqXCDlY8k"></div>
			<div id="html_element" style="grid-column: 1/3"></div>
      <script>

        function timestamp() { var response = document.getElementById("g-recaptcha-response"); if (response == null || response.value.trim() == "") {var elems = JSON.parse(document.getElementsByName("captcha_settings")[0].value);elems["ts"] = JSON.stringify(new Date().getTime());document.getElementsByName("captcha_settings")[0].value = JSON.stringify(elems); } } setInterval(timestamp, 500);

        </script>

			<button style="grid-column: 1/3" class="contact__button g-recaptcha" disabled id="submit_button" type="submit" name="submit" aria-label="send message">
				Trimite
				<svg>
					<line x1="22" y1="2" x2="11" y2="13"></line>
					<polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
				</svg>
			</button>
		</form>

	</section>
  {{-- <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" defer></script> --}}
  <script src="/script/store/contact.js"></script>
  <script>
    applyValidations("nameParent", nameValidation, false);
    applyValidations("emailParent", emailValidation, false);
    applyValidations("companyParent", companyValidation, false);
    applyValidations("message", messageValidation, false);
  </script>
	<!---------------------End Contact Form--------------------->
	<!---------------------------------------------------------->
</main>
<x-store-footer />
