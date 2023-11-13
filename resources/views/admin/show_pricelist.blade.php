<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('price')" />

{{-- Page content start --}}
<section class="content">
    <livewire:show-pricelist itemId="{{ $data->id }}" />
    <a href="#" class="top-up-btn" id="topUp">

        <svg>
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </a>

</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardscript />
<x-dashboardfooter />
