<x-dashboardheader />
<x-dashboardnavbar />
{{-- Display session message --}}

@if (session()->has('message'))
    <div class="alert__session" id="alertevent">
        <span class="alert__session-text">{!! session('message') !!}</span>
        <button class="alert__session-btn" type="button"
            onclick="document.getElementById('alertevent').style.display='none'" data-bs-dismiss="alert"
            aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
@endif
{{-- End Section session message --}}
<x-dashboardsidebar />
<x-dashboardmodals />

<script>
  document.addEventListener('livewire:load', function () {

          if (document.getElementById('alertevent')) {
              document.getElementById('alertevent').style.display = 'block';
          }
  });
</script>

{{-- Page content start --}}
<section class="content">
    <div class="item__header">
        <h1 id="title" class="item__header-title">{{ __('All products') }}</h1>
        <div class="item__header-buttons">
            <a href="{{ route('add_product') }}" class="item__header-btn">{{ __('Add new') }}</a>
        </div>
    </div>

    {{-- Tabel by Livewire start --}}
    @livewire('productstable')
    {{-- <livewire:productstable /> --}}
    {{-- Tabel by Livewire end --}}
    {{-- End Table Category --}}
</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardscript />
<x-dashboardscriptproduct />
<x-dashboardfooter />
