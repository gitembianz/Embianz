<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('wishlist')" />
@livewire('wishliststable', ['tableName' => 'wishlist'])
<x-dashboardscript />
<x-dashboardfooter />
