<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('account')" />
<livewire:accountstable tableName="accounts" />
<x-dashboardfooter />
