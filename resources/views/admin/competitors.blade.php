<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('competitor')" />
@livewire('competitorstable', ['tableName' => 'competitors'])
<x-dashboardfooter />
