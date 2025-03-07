<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('pages')" />
"@livewire('pagestable', ['tableName' => 'static__pages'])"
<x-dashboardfooter />
