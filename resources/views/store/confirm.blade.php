<x-store-head :canonical="'confirm'" :title="' Confirmare trimitere mesaj'" :description="'Confirmare trimitere mesaj'" />
@livewire('store-header')
<main>

    <section class="section__header container">
        <h1>
            {!! app('label_redirect_title', 'Mulțumim pentru completarea formularului.') !!}
        </h1>
        <h3>
            {!! app('label_redirect_description', 'Veți fi redirecționat la pagina principală în câteva secunde...') !!}
        </h3>
        <div class="loadingio-spinner-dual-ball-8eksdpgpyip">
            <div class="ldio-sl0v29xbypi">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </section>
    <!--------------------End Section Header-------------------->
    <!---------------------------------------------------------->
    <script>
        setTimeout(function() {
            window.location.href =
                "/";
        }, 3000);
    </script>
    <!---------------------------------------------------------->
</main>
<x-store-footer />
