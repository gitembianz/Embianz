<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('article')" />
@livewire('show-article', ['articleId' => $data->id], key($data->id))
<x-dashboardfooter />
