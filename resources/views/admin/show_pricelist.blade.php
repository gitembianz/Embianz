<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('price')" />
<livewire:show-pricelist itemId="{{ $data->id }}" />
<x-dashboardfooter />
