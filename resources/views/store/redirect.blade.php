<x-store-head :canonical='"Redirect"' :title='"Page not Found"' :description="'Error, page not found'"/>
<x-store-header />
<main>
  <div class="container redirect">
		<h1>
			Mulțumim pentru completarea formularului.
		</h1>
		<h3>
			Veți fi redirecționat la pagina principală în 5 secunde...
		</h3>
		<div class="loadingio-spinner-dual-ball-8eksdpgpyip">
			<div class="ldio-sl0v29xbypi">
				<div></div>
				<div></div>
				<div></div>
			</div>
		</div>

	</div>
	<script>
		// Așteaptă 5 secunde (5000 milisecunde) și apoi face redirect către o altă pagină jkjkkj
		setTimeout(function() {
			window.location.href =
				"/"; // Schimbă "pagina_de_redirect.html" cu adresa URL către care vrei să faci redirect
		}, 5000);
	</script>
	{{-- <x-support /> --}}
</main>
<x-store-footer />
