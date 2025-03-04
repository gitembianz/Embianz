<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('exchange')" />
<livewire:exchangestable tableName="exchanges" />

<x-dashboardfooter />
