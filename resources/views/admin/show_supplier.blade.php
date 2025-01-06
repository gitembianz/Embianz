<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('supplier')" />
<livewire:show-supplier itemId="{{ $data->id }}" />

<x-dashboardfooter />
p
