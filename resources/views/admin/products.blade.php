<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />

{{-- Page content start --}}
<section  class="content">
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
            <a href="{{ route('add_products') }}" class="addnew">{{ __('Add new') }}</a>

            <h1 id="title" class="talign-c font-xl ls-1 text-bg">{{ __('All products') }}</h1>

            {{-- Tabel by Livewire start --}}
            @livewire('productstable')
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
