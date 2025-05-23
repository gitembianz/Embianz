<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('user')" />
@livewire('userstable', ['tableName' => 'users'])
<x-dashboardfooter />
