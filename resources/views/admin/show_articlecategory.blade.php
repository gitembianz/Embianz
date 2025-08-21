<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('articlecategory')" />
@livewire('show-articlecategory', ['articlecategoryId' => $data->id], key($data->id))
<x-dashboardfooter />
