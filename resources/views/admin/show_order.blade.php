<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('order')" />
@livewire('show-order', ['orderId' => $data->id], key($data->id))
<x-dashboardfooter />
