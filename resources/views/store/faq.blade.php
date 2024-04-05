<x-store-head :canonical="'faq'" :title="' FAQ | '" :description="'Întrebări și răspunsuri'"/>
<x-store-header />
<main>
	<script rel="preload" src="script/store/faq.js" as="script"></script>

	<!---------------------------------------------------------->
	<!------------------------Breadcrumbs----------------------->
	<div class="breadcrumbs container">
		<a class="breadcrumbs__link" href="{{ url("/") }}">
			Acasă
		</a>
		<a class="breadcrumbs__link" href="{{ url("/faq") }}">
			Întrebări frecvente (FAQ)
		</a>
	</div>
	<!----------------------End Breadcrumbs--------------------->
	<!---------------------------------------------------------->
	<!----------------------Section Header---------------------->
	<section class="section__header container">
		<h1 class="section__title">Întrebări frecvente (FAQ)
		</h1>
		<p>
			Aici poți găsi răspunsuri la cele mai comune și frecvente întrebări. În orice caz, ne poți contacta prin intermediul formularului de contact ori de câte ori ai nevoie <a href="{{ url("/contact") }}">aici</a>.
		</p>
	</section>
	<!--------------------End Section Header-------------------->
	<!---------------------------------------------------------->
	<!------------------------Accordions------------------------>
	{{-- Comenzi si Livrari --}}
	<section class="container faq__container ">
		<h2 class="section__title">Comenzi și Livrări</h2>
		<div class="faq__accordions">
			<div class="accordion">
				<button class="accordion-button">
					Pot să modific sau să anulez comanda?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Dacă te-ai răzgândit în legătură cu comanda sau dacă ați introdus date greșite de livrare, nu îți face griji: se poate întâmpla oricui. În astfel de situații, îți recomandăm să iei legătura cu echipa noastră. Vom putea să te asistăm în această problemă și să vedem ce se poate face pentru a satisface nevoile tale.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Am primit un produs diferit. Ce ar trebui să fac?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Dacă am expediat o comandă care nu include ceea ce ai comandat, ne pare rău pentru posibilele neplăceri. Te rugăm să ne contactezi prin formularul online pe care îl găsești aici: vom discuta împreună cea mai bună soluție care ți se potrivește. Nu uita să specifici numărul comenzii.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Am primit o comandă incompletă, cum mă puteți ajuta?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							În cazul în care un produs lipsește din livrarea ta, te rugăm să accepți scuzele noastre și vom fi bucuroși să efectuăm toate verificările necesare pentru a te asista.
							<br>
							<br>
							Completează formularul de contact disponibil aici și vom face tot ce ne stă în putință pentru a rezolva lucrurile rapid. Specifică numărul comenzii, atașează câteva fotografii ale coletului și îți vom oferi suportul pe care îl meriți.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Am plasat o comandă, dar nu am primit un email de confirmare. Ce înseamnă asta?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Confirmarea comenzii este trimisă imediat la aceeași adresă de e-mail pe care ai introdus-o în detaliile de contact.
							<br>Dacă presupui că nu ai primit acest mesaj de confirmare:
							<br><br>
							1. Verifică dosarul de spam.<br><br>
							2. Este posibil să fi introdus greșit adresa de email, ceea ce înseamnă că nu îți putem trimite alte comunicări despre comandă și urmărirea acesteia.<br><br>
							3. Plata a fost refuzată sau întârziată.<br><br>
							4. Dacă după 24 de ore nu ai primit nimic, verifică cu banca/platforma de plată, apoi contactează echipa noastră de suport <a href="{{ url("/contact") }}">aici</a>.<br><br>
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Cât timp durează să fie livrată comanda mea?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							De obicei, pregătirea și expedierea comenzii durează de la 1 la 2 zile lucrătoare, însă termenii pot varia în funcție de disponibilitatea în stoc. Dacă produsul este indisponibil, procesarea comenzii poate dura până la 14 zile lucrătoare.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Am primit un produs deteriorat. Ce ar trebui să fac?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Dacă ai primit produse deteriorate, te rugăm să faci poze produselor, ambalajelor acestora și cutiei de carton în care au fost livrate. Contactează-ne prin formularul online pe care îl găsești <a href="{{ url("/contact") }}">aici</a>. Vom discuta împreună cea mai bună soluție care ți se potrivește.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Cât costă livrarea?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Costul de livrare variază în funcție de destinație și este calculat pentru fiecare comandă în parte.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Cum pot urmări comanda mea?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Odata ce comanda este pregătită pentru a fi expediată, vei primi un e-mail de notificare, care va conține numărul de urmărire atribuit coletului tău.
							<br><br>
							Acest număr de urmărire va permite monitorizarea progresului și locației coletului în timpul tranzitului.
							<br><br>
							În cazul în care ai nevoie de asistență în legătură cu livrarea, poți contacta oricand echipa noastră de suport.
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	{{-- Plată și Securitate --}}
	<section class="container faq__container">
		<h2 class="section__title">Plată și Securitate</h2>
		<div class="faq__accordions">
			<div class="accordion">
				<button class="accordion-button">
					Prețuri, taxe și impozite
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							1. Prețurile afișate pe site sunt în funcție de regiune.<br><br>
							2. Taxele de livrare standard sunt excluse: acestea sunt calculate și adăugate la finalizarea comenzii.<br><br>
							3. La plasarea unei comenzi, clientul este responsabil să se asigure că produsul poate fi importat legal la destinație. Destinatarul este importatorul oficial și trebuie să respecte toate legile și reglementările destinației.<br><br>
							4. Comenzile expediate în afara UE pot fi supuse taxelor de import, taxelor vamale și taxelor aplicate de țara de destinație. Taxele suplimentare pentru vamă sunt acoperite exclusiv de către destinatar.<br><br>
							5. Politica vamală variază considerabil. Ar trebui să contactezi biroul vamal local pentru mai multe informații. Atunci când sunt necesare proceduri de eliberare vamală, acestea pot provoca înârzieri dincolo de estimările noastre inițiale de livrare.<br><br>
							6. Dacă destinatarul refuză să plătească taxele vamale, evitând astfel livrarea comenzii, o rambursare parțială va fi posibilă, care nu va include taxele de transport (chiar dacă inițial au fost oferite de către noi) și orice altă taxă suplimentară aplicată de țara de destinație.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Ce metode de plată sunt acceptate?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Acceptăm diverse metode de plată: Plată cu card online, Numerar la livrare, Ordin de plată etc. Metodele de plată disponibile sunt afișate în momentul plasării comenzii. Suma totală (prețul produselor + transportul) va fi dedusă după ce comanda este finalizată și aprobată.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Cum pot solicita o factură pentru comanda mea?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Poți solicita factura prin intermediul formularului de contact disponibil pe site-ul nostru <a href="{{ url("/contact") }}">aici</a>. Te rugăm să introduci numărul comenzii, codul de TVA și codul fiscal. Vei primi factura pe email în cel mai scurt timp posibil.
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	{{-- Garanție și retur --}}
	<section class="container faq__container">
		<h2 class="section__title">Garanție și Retur</h2>
		<div class="faq__accordions">
			<div class="accordion">
				<button class="accordion-button">
					Cum pot returna produsele mele?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Dreptul la retur poate fi aplicat în termenul de 14 zile de la livrare.
							<br>Contactază echipa noastră de suport și trimite poze cu produsul care dorești să-l returnezi.
							<br>
							Te rugăm să reții:
							<br><br>
							1. Produsul trebuie să fie nealterat, în cutia originală și în condiții inițiale.<br><br>
							2. Produsul nu trebuie să fie personalizat, utilizat sau spălat.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Pot să schimb produsele?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Din păcate, produsele nu pot fi schimbate, dar pot fi returnate. În orice caz, contactează echipa noastră de suport: ne-ar face plăcere să îți oferim sfaturi și îndrumări în legătură cu acest aspect.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Care este politica de garanție?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Înlocuim produsele care au fost deteriorate sau erau inutilizabile la momentul achiziției.  Garanția acoperă orice defect în material. Deteriorarea intenționată și utilizarea abuzivă a produsului poate anula garanția.
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	{{-- Compania noastră și alte contacte --}}
	<section class="container faq__container">
		<h2 class="section__title">Compania noastră și alte contacte</h2>
		<div class="faq__accordions">
			<div class="accordion">
				<button class="accordion-button">
					Reprezint o companie. Cum pot personaliza produsele mele?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Dacă ai în plan să personalizezi produsele cu logo-ul companiei, cauți un cadou special pentru un eveniment sau pentru clienți și colegi (poate prin gravarea numelor lor), echipa noastră este la dispoziția ta  pentru sfaturi personalizate. Completează formularul online pe care îl poți găsi <a href="{{ url("/contact") }}">aici</a>.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Caut o colaborare. Pe cine ar trebui să contactez?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Ne poți trimite solicitarea ta prin intermediul formularului de contact pe care îl poți găsi aici și vom lua legătură cu tine în cel mai scurt timp posibil.
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	{{-- Asistență Website --}}
	<section class="container faq__container">
		<h2 class="section__title">Asistență Website</h2>
		<div class="faq__accordions">
			<div class="accordion">
				<button class="accordion-button">
					Sunt disponibile toate produsele de pe site?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Nu toate produsele de pe site sunt disponibile în mod constant. Disponibilitatea poate varia în funcție de stocul actual și de cerere. Recomandăm verificarea paginii produsului specific pentru informații actuale privind disponibilitatea. De asemenea, poți contacta echipa noastră de suport pentru asistență suplimentară în legătură cu disponibilitatea produselor.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					De ce nu pot plasa comanda sau efectua plata pe site?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							Dacă nu poți face plăți pe site-ul nostru, te rugăm să încerci următorii pași:
							<br><br>
							1. Plățile ar putea fi respinse de către bancă (de exemplu, codul CVC/CVV greșit). În acest caz, contactează banca pentru a verifica dacă s-a întâmplat acest lucru, deoarece te poate ajuta să deblochezi cardul, astfel încât să poți încerca din nou.<br><br>
							2. Asigură-te că ai folosit o metodă de plată acceptată de magazinul nostru online;<br><br>
							3. Verifică dacă ai introdus corect informațiile de facturare și livrare (completând fiecare câmp din formular la plasarea comenzii);<br><br>
							4. Actualizează fila browser-ului fără a confirma din nou comanda.<br><br>
							5. Dacă nu este cazul niciunei opțiuni dintre cele de mai sus, încearcă să plasezi comanda pe alt browser sau șterge cookie-urile din browser și încearcă din nou.<br><br>
							6. Dacă vezi un mesaj care indică faptul că produsele nu mai sunt disponibile , verifică coșul și elimină produsele care nu sunt în stoc.
						</p>
					</div>
				</div>
			</div>
			<div class="accordion">
				<button class="accordion-button">
					Ce browsere sunt suportate?
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				<div class="accordion-wrapper">
					<div class="accordion-content">
						<p>
							1. Site-ul funcționează cu majoritatea browserelor web.<br><br>
							2. Dacă întâmpini probleme, îți recomandăm să încerci Mozilla Firefox, Safari sau Chrome.<br><br>
							3. În orice caz, ori de câte ori ai nevoie de asistență, te rugăm să contactezi echipa noastră de suport.<br><br>
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!----------------------End Accordions---------------------->
	<!---------------------------------------------------------->
	<script src="/script/store/faq.js" async defer></script>
</main>
<x-store-footer />
