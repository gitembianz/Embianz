<x-store-head :title='" Confirm message | "' />
<x-store-header />
<main>
    <!---------------------------------------------------------->
    <!------------------------Breadcrumbs----------------------->
    <section>
        <div class="breadcrumbs container">
            <a class="breadcrumbs__link" href="{{ url("/") }}">
                Acasa
            </a>
        </div>
    </section>
    <!----------------------End Breadcrumbs--------------------->
    <!---------------------------------------------------------->
    <!---------------------------------------------------------->
    <!----------------------Section Header---------------------->
    <section class="section__header container">
        <h2 class="section__title">
            Mulțumim pentru completarea formularului.
        </h2>
        <p class="section__text">
            Veți fi redirecționat la pagina principală în 5 secunde...
        </p>
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
        // Așteaptă 5 secunde (5000 milisecunde) și apoi face redirect către o altă pagină
        setTimeout(function() {
            window.location.href =
                "/"; // Schimbă "pagina_de_redirect.html" cu adresa URL către care vrei să faci redirect
        }, 5000);
    </script>
    <!---------------------------------------------------------->
</main>
<x-store-footer />
