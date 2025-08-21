<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('articlecategory')" />
@livewire('articlecategoriestable', ['tableName' => 'article_categories'])
<x-dashboardfooter />
