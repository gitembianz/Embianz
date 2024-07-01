<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('payment')" />
@livewire('paymentstable', ['tableName' => 'payments'])
<x-dashboardfooter />
