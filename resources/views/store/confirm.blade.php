<x-store-head :title='" Confirm message | "'  />
<x-store-header />
<main>

    <!-----------------------Contact Form----------------------->
    <section class="contact container">

    </section>
    <!---------------------End Contact Form--------------------->
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
