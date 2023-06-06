<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />

{{-- Page content start --}}
<section  class="content">
  <div class="item__header" style="grid-template-columns: 1fr 7rem">
    <h1 id="title" class="item__header-title">{{ __('All products') }}</h1>
    <div class="item__header-buttons">
      <a href="{{ route('add_products') }}" class="item__header-btn">{{ __('Add new') }}</a>
    </div>
  </div>
    <div class="row">
    {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel ls-1 p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'" class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}




        <div class="col-12-xs col-12-sm col-12-xl">

            {{-- Tabel by Livewire start --}}
            {{-- @livewire('productstable') --}}
            <livewire:productstable />
              {{-- Tabel by Livewire end --}}
            {{--End Table Category --}}
        </div>
    </div>
</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardscript />
<x-dashboardscriptproduct />
<x-dashboardfooter />
