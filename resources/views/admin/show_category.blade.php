<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />


{{-- Page content start --}}
<section class="content">


    {{-- livewire tabs --}}
    <livewire:show-category categoryId="{{ $data->id }}" />

    {{-- end livewire tabs --}}

</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardscript />
<x-dashboardmediahanddler />
<x-dashboardscriptcategory />
<x-dashboardfooter />
