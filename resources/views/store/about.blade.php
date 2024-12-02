<x-store-head :canonical="'about'" :title="' Despre noi'" :description="'Despre noi'" />
@livewire('store-header')
<main>
	<!---------------------------------------------------------->
	<!------------------------Breadcrumbs----------------------->
	<div class="breadcrumbs container">
		<a class="breadcrumbs__link" href="{{ url("/") }}">
			Acasă
		</a>
		<a class="breadcrumbs__link" href="{{ url("/about") }}">
			Despre noi
		</a>
	</div>
	<!----------------------End Breadcrumbs--------------------->
	<!---------------------------------------------------------->
	<!----------------------Section Header---------------------->
	<section class="section__header container">
		<h1 class="section__title">
			Despre noi
		</h1>
		<p class="section__text">
			Fondat în 2024, magazinul online "noren" se distinge prin angajamentul său de a oferi o gamă variată de produse de înaltă calitate.
			Creat cu pasiune, este mai mult decât un magazin online, este o experiență captivantă pentru vizitatorii noștri.
			Fecare articol este selectat cu grijă pentru a asigura esența și autenticitatea brand-ului nostru.
		</p>
	</section>
	<!--------------------End Section Header-------------------->
	<!---------------------------------------------------------->
	<!-----------------------About Image------------------------>
	<!---------------------End About Image---------------------->
	<!---------------------------------------------------------->
	<!-----------------------About Info------------------------->
	<section class="section__header container">
		<img loading="eager" class="about__img container" src="/images/store/banner.webp" alt="contact form image">
		<p class="section__text">
			Ne dorim să transcendem conceptul tradițional de magazin online într-o experiență digitală profundă și personalizată.
		    De la design-ul intuitiv la selecția meticuloasă a produselor, totul este creat pentru a răspunde nevoilor și așteptărilor clienților noștri.
		</p>
		<p class="section__text">
			Suntem hotărâți să putem oferi mai mult decât un site de cumpărături.
			Un loc unde calitatea întâlnește satisfacția, unde produsele de top îmbogățesc stilul de viață.
		</p>
		<p class="section__text">
			Construim relații solide cu clienții noștri prin suport și asistență continuă.
			În colaborare cu Eztem Corp, inovăm și adaptăm soluții digitale pentru a oferi experiențe noi și captivante, asigurându-ne că fiecare vizitator se simte apreciat.
		</p>
	</section>
	<!---------------------End About Info----------------------->
	<!---------------------------------------------------------->

</main>
<x-store-footer />
