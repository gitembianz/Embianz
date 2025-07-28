<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('jobs')" />
@livewire('jobstable', ['tableName' => 'jobs'])
<x-dashboardfooter />
