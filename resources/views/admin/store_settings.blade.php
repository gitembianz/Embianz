<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="'store_settings'" />
@livewire('storesettingstable', ['tableName' => 'store__settings'])
<x-dashboardfooter />
