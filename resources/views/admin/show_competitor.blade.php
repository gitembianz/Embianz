<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('competitor')" />
@livewire('show-competitor', ['competitorId' => $data->id], key($data->id))
<x-dashboardfooter />
