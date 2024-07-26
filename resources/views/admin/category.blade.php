<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('category')" />
@livewire('categoriestable', ['tableName' => 'categories'])
<x-dashboardfooter />
