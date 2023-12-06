<x-store-head :title='" Contactează-ne | "' />
<x-store-header />
<main>
    <!---------------------------------------------------------->
    <!------------------------Breadcrumbs----------------------->
    <section>
        <div class="breadcrumbs container">
            <a class="breadcrumbs__link" href="{{ url("/") }}">
                Acasa
            </a>
            <a class="breadcrumbs__link" href="{{ url("/contact") }}">
                Contacte
            </a>
        </div>
    </section>
    <!----------------------End Breadcrumbs--------------------->
    <!---------------------------------------------------------->
    <!----------------------Section Header---------------------->
    <section class="section__header container">
        <h2 class="section__title">
            Contactează-ne
        </h2>
        <p class="section__text">
            Completează formularul sau contactează-ne direct pe e-mail <a href="#">noren@gmail.com</a>.
        </p>
    </section>
    <!--------------------End Section Header-------------------->
    <!---------------------------------------------------------->
    <section class="contact container">
        <form class="contact__form" action="https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8"
            method="POST">
            <input type=hidden name="oid" value="00D09000008XPQu">
            <input type=hidden name="retURL" value="{{ "/" }}">
            <select id="00N9N000000PrL5" name="00N9N000000PrL5" title="Exact Source" hidden>
                <option value="www.eztemcorp.com">noren.ro</option>
            </select>
            <select id="lead_source" name="lead_source" hidden>
                <option value="Web">Web</option>
            </select>
            <div class="contact__label">
                <label for="last_name">
                    Nume
                </label>
                <input id="last_name" maxlength="80" name="last_name" type="text" placeholder="Nume">
            </div>
            <div class="contact__label">
                <label for="company">
                    Companie
                </label>
                <input id="company" maxlength="80" name="company" type="text" placeholder="Companie">
            </div>
            <div class="contact__label">
                <label for="email">
                    Email
                </label>
                <input type="email" id="email" name="email" autocomplete="email" required placeholder="E-mail"
                    maxlength="80">
            </div>
            <div class="contact__label">
                <label for="nr_order">
                    Numarul Comenzii
                </label>
                <input type="text" id="nr_order" name="nr_order" placeholder="Numarul Comenzii" maxlength="80">
            </div>
            <div class="contact__label">
                <span for="description" id="description">
                    Mesaj
                </span>
                <textarea name="description" required placeholder="Spune-ne mai multe. Incepe sa scrii aici..."></textarea>
            </div>
            <button class="contact__button" type="submit" name="submit">
                Trimite
                <svg>
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
        <img class="contact__img" src="/images/store/contact-form.webp" alt="contact form image">
    </section>

</main>
<x-store-footer />
