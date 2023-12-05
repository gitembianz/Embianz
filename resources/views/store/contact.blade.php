<x-store-head :title='" Contactează-ne | "' />
<x-store-header />
<main>
    <div class="contact__title">
        <div class="contact__title--text">
            <h1>
            </h1>
            <p>
            </p>
        </div>
    </div>
    <section class="contact">
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
                    @lang("components.contact.name")
                </label>
                <input id="last_name" maxlength="80" name="last_name" type="text" placeholder="@lang("components.contact.name")">
            </div>
            <div class="contact__label">
                <label for="company">
                    @lang("components.contact.company")
                </label>
                <input id="company" maxlength="80" name="company" type="text" placeholder="@lang("components.contact.company")">
            </div>
            <div class="contact__label">
                <label for="email">
                    @lang("components.contact.email")
                </label>
                <input type="email" id="email" name="email" autocomplete="email" required placeholder="E-mail"
                    maxlength="80">
            </div>
            <div class="contact__label">
                <span for="description" id="description">
                    @lang("components.contact.message")
                </span>
                <textarea name="description" required placeholder="@lang("components.contact.message_placeholder")"></textarea>
            </div>
            <button class="button" type="submit" name="submit">
                @lang("components.contact.send_button")
                <svg>
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </section>
</main>
<x-store-footer />
