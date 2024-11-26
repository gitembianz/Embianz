<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('promotion')" />
@livewire('promotiontable', ['tableName' => 'promotions'])
<x-dashboardfooter />
