<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('cart')" />
@livewire('show-cart', ['cartId' => $data->id], key($data->id))
<x-dashboardfooter />
