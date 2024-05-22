<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="'store_settings'" />
<section class="content">
 @livewire('storesettingstable')
 <a href="#" class="top-up-btn" id="topUp">
  <svg>
   <polyline points="18 15 12 9 6 15"></polyline>
  </svg>
 </a>
</section>
<x-dashboardright />
<x-dashboardscript />
<x-dashboardfooter />
