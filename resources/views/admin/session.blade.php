<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('session')" />
@livewire('sessionstable', ['tableName' => 'user_sessions'])
<x-dashboardfooter />
