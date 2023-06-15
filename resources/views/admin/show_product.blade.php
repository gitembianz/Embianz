<x-dashboardheader />
<x-dashboardnavbar />
{{-- Display session message --}}
@if (session()->has('message'))
    <div class="alert__session" id="alertevent">
        <Span class="alert__session-text">{!! session('message') !!}</Span>
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

    <div class="row">
        <div class="col-12-xs display-f jus-sb col-12-sm col-12-xl m-1 text-bg">
            <h1 id="title" class="mt-1 font-xl ls-1 text-bg">View Product - <span
                    class="text-black font-lg">{{ $data->name }}</span></h1>
            <div class="display-f">
                <a href="{{ route('products') }}" class="boxsha bg-secondary display-f align-center br-xs p-1"> Go
                    Back</a>
                <a href="{{ route('add_product') }}"
                    class="boxsha bg-secondary ml-1 display-f align-center br-xs p-1"><span class="bg-secondary"></span>
                    Add new</a>
            </div>
        </div>
    </div>
    <form action="#" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- content --}}
        <div class="row gap-4 justify-center">

        </div>
    </form>
    <a href="#" class="top-up-btn" id="topUp">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
            stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </a>
</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardscript />
<x-dashboardscriptcategory />
<x-dashboardfooter />
