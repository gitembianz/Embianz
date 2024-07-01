<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('order')" />
<livewire:orderstable tableName="orders" />
<x-dashboardfooter />
