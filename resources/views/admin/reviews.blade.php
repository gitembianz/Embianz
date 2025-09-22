<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('review')" />
@livewire('reviewstable', ['tableName' => 'product_reviews'])
<x-dashboardfooter />
