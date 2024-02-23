<x-store-head :canonical="'about'" :title="' Despre noi | '" />
<x-store-header />
<main>
  <!---------------------------------------------------------->
  <!------------------------Breadcrumbs----------------------->
  <div class="breadcrumbs container">
    <a class="breadcrumbs__link" href="{{ url('/') }}">
      Acasa
    </a>
    <a class="breadcrumbs__link" href="{{ url('/about') }}">
      Despre noi
    </a>
  </div>
  <!----------------------End Breadcrumbs--------------------->
  <!---------------------------------------------------------->
  <!----------------------Section Header---------------------->
  <section class="section__header container">
    <h1 class="section__title">
      Descoperă Noren
    </h1>
    <p class="section__text">
      Magazinul "Noren" reprezintă cu mândrie rezultatul colaborării strânse cu echipa Eztem Corp, consolidându-se
      ca un hub digital de excepție. Înființat cu pasiune și dedicare, "Noren" se distinge prin oferirea unei game
      variate de produse de calitate superioară, reflectând viziunea noastră comună asupra excelenței în mediul
      online.
    </p>
  </section>
  <!--------------------End Section Header-------------------->
  <!---------------------------------------------------------->
  <!-----------------------About Image------------------------>
  <!---------------------End About Image---------------------->
  <!---------------------------------------------------------->
  <!-----------------------About Info------------------------->
  <section class="section__header container">
    <img class="about__img container" src="/images/store/banner.webp" alt="contact form image">
    <p class="section__text">
      Cu o echipă de specialiști talentați în spatele nostru, am reușit să creăm nu doar un magazin online, ci o
      experiență digitală captivantă pentru clienții noștri. Fiecare detaliu al designului, fiecare linie de cod
      și fiecare imagine sunt atent lucrate pentru a aduce la viață esența și autenticitatea brandului "Noren".
    </p>
    <p class="section__text">
      În colaborarea noastră cu Eztem Corp, am îmbinat tehnologia de vârf cu creativitatea inovatoare pentru a
      oferi clienților noștri o platformă intuitivă și plăcută. Suntem convinși că "Noren" nu este doar un
      magazin, ci o destinație online unde clienții pot descoperi și experimenta produse de cea mai înaltă
      calitate.
    </p>
    <p class="section__text">
      Prin această colaborare sinergică, am reușit să creăm nu doar un magazin virtual, ci o comunitate digitală
      în jurul brandului "Noren". Ne propunem să continuăm, să inovăm și să oferim soluții digitale personalizate,
      aducând mereu ceva nou și captivant pentru clienții noștri fideli și pentru cei care abia descoperă
      universul "Noren".
    </p>
  </section>
  <!---------------------End About Info----------------------->
  <!---------------------------------------------------------->

</main>
<x-store-footer />
