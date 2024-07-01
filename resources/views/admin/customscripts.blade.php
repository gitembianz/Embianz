<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('scripts')" />
@livewire('custom-scripts-table', ['tableName' => 'custom_scripts'])
<x-dashboardfooter />
