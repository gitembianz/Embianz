<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('scripts')" />
@livewire('show-script', ['scriptId' => $data->id])
<x-dashboardfooter />
