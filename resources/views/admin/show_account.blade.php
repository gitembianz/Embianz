<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('account')" />
@livewire('show-account', ['accountId' => $data->id], key($data->id))
<x-dashboardfooter />
