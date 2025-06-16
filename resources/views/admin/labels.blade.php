<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="'labels'" />
@livewire('labelstable', ['tableName' => 'text_labels'])
<x-dashboardfooter />
