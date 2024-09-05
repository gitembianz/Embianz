<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('product')" />
<livewire:productstable tableName="products" />
<x-dashboardfooter />
