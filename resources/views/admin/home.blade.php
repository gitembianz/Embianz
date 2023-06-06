<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
{{-- Page content start --}}
<section class="section-container bg-bg">
<div>
  <h2 class="text-white talign-c mt-2">Home Dashboard</h2>

</div>
</section>
{{-- page content end --}}
<?php $todolists = ""; ?>
<x-dashboardright :todolists="$todolists" />
<x-dashboardscript />
<x-dashboardfooter />
