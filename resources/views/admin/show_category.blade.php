<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('category')" />
@livewire('show-category', ['categoryId' => $data->id])
<x-dashboardfooter />
