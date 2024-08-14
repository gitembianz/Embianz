<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('variant')" />
@livewire('variantstable', ['tableName' => 'variants'])
<x-dashboardfooter />
