<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('price')" />
<livewire:priceliststable tableName="price_lists"/>
<x-dashboardfooter />
