<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('session')" />
@livewire('sessionstable', ['tableName' => 'sessions'])
<x-dashboardfooter />
