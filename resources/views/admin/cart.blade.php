<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('cart')" />
<livewire:cartstable tableName="carts" />
<x-dashboardfooter />
