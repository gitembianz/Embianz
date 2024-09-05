<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('spec')" />
<livewire:show-spec itemId="{{ $data->id }}" />
<x-dashboardfooter />
