<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('product')" />
@livewire('show-product', ['productId' => $data->id])
<x-dashboardfooter />
