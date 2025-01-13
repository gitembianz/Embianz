<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('brand')" />
<livewire:brandstable tableName="brands" />
<x-dashboardfooter />
