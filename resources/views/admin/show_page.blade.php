<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('pages')" />
@livewire('show-page', ['pageId' => $data->id])
<x-dashboardfooter />
