<x-store-head :canonical='"404 | "' :title='"Page not Found | "' :description="'Error, page not found  | '"/>
<x-store-header />
<main>

  <div class="container redirect">
		<h1>
			404 - Pagina nu a fost gasita
		</h1>
		<h3>
			Veți fi redirecționat la pagina principală în 5 secunde...
		</h3>
    <a href="{{ url("/") }}">Întoarcete la pagina principală</a>
  </div>
  <script>
		setTimeout(function() {
			window.location.href =
				"/"; 
		}, 5000);
	</script>
</main>
<x-store-footer />
