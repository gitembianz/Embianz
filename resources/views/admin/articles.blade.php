<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('article')" />
@livewire('articlestable', ['tableName' => 'articles'])
<x-dashboardfooter />
