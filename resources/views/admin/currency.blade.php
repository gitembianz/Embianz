<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('currency')" />
@livewire('currenciestable', ['tableName' => 'currencies'])
<x-dashboardfooter />
