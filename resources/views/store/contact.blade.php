<x-store-head :canonical="'contact'" :title="' Contactează-ne | '" :description="'Contactează-ne'"/>
<x-store-header />
<main>
  <script type="text/javascript">
    var onloadCallback = function() {
      grecaptcha.render('html_element', {
        'sitekey': '6LdlrpkpAAAAAMIgwDHss_aQtKVPiC2eDlpAx1MW',
        'callback': verifyCallback,
        'theme': 'light' // Poți seta tema aici
      });
    };

    var verifyCallback = function(response) {
      if (response) {
        // reCAPTCHA a fost completat cu succes, schimbă tipul butonului la "submit"
        document.getElementById('submit_button').type = 'submit';
      } else {
        // reCAPTCHA nu a fost completat, schimbă tipul butonului la "button"
        document.getElementById('submit_button').type = 'button';
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
		Completează formularul sau contactează-ne direct pe e-mail <a href="#">noren@gmail.com</a>.
	</p>
	<!--------------------End Section Header-------------------->
	<!---------------------------------------------------------->
	<!-----------------------Contact Form----------------------->
	<section class="contact container">
		<form class="contact__form" action="https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" method="POST">
			<input type=hidden name="oid" value="00D09000008XPQu">
			{{-- <input type=hidden name="retURL" value="{{ "/confirm" }}"> --}}
			<input type=hidden name="retURL" value="{{ url("/redirect") }}">
			<select id="00N9N000000PrL5" name="00N9N000000PrL5" title="Exact Source" hidden>
				<option value="www.eztemcorp.com">noren.ro</option>
			</select>
			<select id="lead_source" name="lead_source" hidden>
				<option value="Web">Web</option>
			</select>
			<div class="contact__label">
				<label for="last_name">
					<span class="red__label">*</span> Nume
				</label>
				<input id="last_name" maxlength="80" name="last_name" type="text" placeholder="Nume" required>
			</div>
			<div class="contact__label">
				<label for="company">
					Companie
				</label>
				<input id="company" maxlength="80" name="company" type="text" placeholder="Companie">
			</div>
			<div class="contact__label">
				<label for="email">
					<span class="red__label">*</span> Email
				</label>
				<input type="email" id="email" name="email" autocomplete="email" required placeholder="E-mail" maxlength="80">
			</div>
			<div class="contact__label">
				<label for="nr_order">
					Numarul Comenzii
				</label>
				<input type="text" id="nr_order" name="nr_order" placeholder="Numarul Comenzii" maxlength="80">
			</div>
			<div class="contact__label">
				<span for="description" id="description">
					<span class="red__label">*</span> Mesaj
				</span>
				<textarea name="description" rows="30" required placeholder="Spune-ne mai multe. Incepe sa scrii aici..."></textarea>
			</div>

      <div class="g-recaptcha" data-sitekey="6LfwYJkpAAAAAINvUbZhqEPmiXVLH7kWqXCDlY8k"></div>
			<div id="html_element"></div>


			<button class="contact__button g-recaptcha" id="submit_button" type="submit" name="submit" aria-label="send message">
				Trimite
				<svg>
					<line x1="22" y1="2" x2="11" y2="13"></line>
					<polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
				</svg>
			</button>
		</form>
		{{-- <img class="contact__img" src="/images/store/contact-form.webp" alt="contact form image"> --}}
	</section>
  <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
	<!---------------------End Contact Form--------------------->
	<!---------------------------------------------------------->
</main>
<x-store-footer />
