<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('supplier')" />
@livewire('supplierstable', ['tableName' => 'order__suppliers'])
<x-dashboardfooter />
