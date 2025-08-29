<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('voucher')" />
<livewire:vouchertable tableName="vouchers" />
<x-dashboardfooter />
