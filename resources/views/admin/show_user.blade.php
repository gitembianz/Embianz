<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('user')" />
<livewire:show-user itemId="{{ $data->id }}" />

<x-dashboardfooter />
