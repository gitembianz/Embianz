<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="'countries'" />
@livewire('countriestable', ['tableName' => 'countries'])
<x-dashboardfooter />
