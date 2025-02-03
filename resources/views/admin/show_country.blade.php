<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="'countries'" />

<livewire:show-country itemId="{{ $data->id }}" />
<x-dashboardfooter />
