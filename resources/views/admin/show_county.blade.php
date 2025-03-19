<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="'countries'" />

<livewire:show-county itemId="{{ $data->id }}" />
<x-dashboardfooter />
