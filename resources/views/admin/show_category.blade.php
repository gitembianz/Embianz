<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
{{-- Page content start --}}
<section class="section-container bg-sidebar-bg-light-1">
  {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel ls-1 p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'" class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
    <h1 id="title" class="mt-1 talign-c font-xl ls-1 text-white">View {{$data->name}} details</h1>
    <div class="row talign-c">
      <div class="col-12-xs col-12-sm col-12-xl m-2 p-1 text-white">
        <h2>{{$data->name}}</h2>
        <img src="/categories/{{$data->image->first()->img_main_path}}" alt="" width="50" height="50" >
      </div>
  </div>

</section>
{{-- page content end --}}
<x-dashboardscript />
<x-dashboardfooter />