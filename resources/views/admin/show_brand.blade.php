<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('brand')" />
@livewire('show-brand', ['id' => $data->id])
<x-dashboardfooter />
