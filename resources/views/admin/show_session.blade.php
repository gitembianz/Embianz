<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('session')" />
@livewire('show-session', ['sessionId' => $data->id])
<x-dashboardfooter />
