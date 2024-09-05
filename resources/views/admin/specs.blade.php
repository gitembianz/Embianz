<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('spec')" />
@livewire('specstable', ['tableName' => 'specs'])
<x-dashboardfooter />
