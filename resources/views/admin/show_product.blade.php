<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
<x-dashboardright />
{{-- Page content start --}}
<section class="section-container p-1 bg-bg">
    {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel talign-c ls-1 mb-1 br-xs p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'"
                class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
    <div class="contenttab bg-white text-bg p-2 br-sm">
        <div class="row jus-c">
            <div class="col-12-xs display-f jus-sb col-12-sm col-12-xl m-1 text-bg">
                <h1 id="title" class="mt-1 font-xl ls-1 text-bg">View Product - <span
                        class="text-black font-lg">{{ $data->name }}</span></h1>
                <div class="display-f">
                    <a href="{{ route('products') }}" class="boxsha bg-secondary display-f align-center br-xs p-1"> Go
                        Back</a>
                    <a href="{{ route('add_products') }}"
                        class="boxsha bg-secondary ml-1 display-f align-center br-xs p-1"><span
                            class="bg-secondary"></span> Add new</a>
                </div>
            </div>
        </div>
        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- content --}}
            <div class="row gap-4 justify-center">

            </div>
        </form>
    </div>
</section>
{{-- page content end --}}
<x-dashboardscript />
<x-dashboardscriptcategory />
<x-dashboardfooter />
