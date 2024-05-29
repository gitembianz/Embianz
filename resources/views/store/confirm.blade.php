<x-store-head :canonical="'confirm'" :title="' Confirmare trimitere mesaj | '" :description="'Confirmare trimitere mesaj'" />
<x-store-header />
<main>
	<!---------------------------------------------------------->
	<!------------------------Breadcrumbs----------------------->
	<div class="breadcrumbs container">
		<a class="breadcrumbs__link" href="{{ url("/") }}">
			Acasă
		</a>
	</div>
	<!----------------------End Breadcrumbs--------------------->
	<!---------------------------------------------------------->
	<!---------------------------------------------------------->
	<!----------------------Section Header---------------------->
	<section class="section__header container">
		<h1 class="section__title">
			Mulțumim pentru completarea formularului.
		</h1>
		<p class="section__text">
			Vei fi redirecționat la pagina principală în câteva secunde...
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
		setTimeout(function() {
			window.location.href =
				"/";
		}, 3000);
	</script>
	<!---------------------------------------------------------->
</main>
<x-store-footer />
