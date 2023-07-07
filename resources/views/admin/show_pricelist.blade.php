<x-dashboardheader />
<x-dashboardnavbar />
{{-- Display session message --}}
@if (session()->has('message'))
    <div class="alert__session" id="alertevent">
        <Span class="alert__session-text">{!! session('message') !!}</Span>
        <button class="alert__session-btn" type="button"
            onclick="document.getElementById('alertevent').style.display='none'" data-bs-dismiss="alert"
            aria-hidden="true">
            <svg>
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    <script>
        const alertEvent = document.getElementById("alertevent");
        header.style.marginBottom = '4rem';
        alertEvent.style.opacity = '1';

        setTimeout(function() {
            alertEvent.style.opacity = '0';
            setTimeout(function() {
                alertEvent.remove();
                header.style.marginBottom = '0';
            }, 500);
        }, 2000);
    </script>
@endif
{{-- End Section session message --}}
<x-dashboardsidebar />

{{-- Page content start --}}
<section class="content">
    {{ $data->name }}
    {{-- livewire tabs --}}
    {{-- <livewire:show-category categoryId="{{ $data->id }}" /> --}}

    {{-- end livewire tabs --}}
    <a href="#" class="top-up-btn" id="topUp">

        <svg>
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </a>

</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardscript />
<x-dashboardmediahanddler />
<x-dashboardfooter />
