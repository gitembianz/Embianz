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
    <h1 id="title" class="mt-1 talign-c font-xl ls-1 text-secondary">{{ __('All categories') }}</h1>
    
    <div class="row talign-c">
        <div class="col-12-xs col-12-sm col-12-xl m-2 p-1 text-secondary">
            <div class="form-group float-r">
                <div>
                    Toggle column: <a class="toggle-vis text-hover-white cursor-p" data-column="0">ID</a> - <a class="toggle-vis text-hover-white cursor-p" data-column="1">Name</a> - <a class="toggle-vis cursor-p text-hover-white" data-column="2">Parrent</a> - <a class="toggle-vis text-hover-white cursor-p" data-column="3">Short</a> - <a class="toggle-vis text-hover-white cursor-p" data-column="4">Images</a>
                </div>
            </div>
            {{-- Table Category --}}
            <table class="category-table" id="category_table">
                <thead>
                <tr>
                    <th class="bg-sidebar-bg">Catagory Id</th>
                    <th class="bg-sidebar-bg">Catagory Name</th>
                    <th class="bg-sidebar-bg">Catagory Parrent</th>
                    <th class="bg-sidebar-bg">Catagory Short Description</th>
                    <th class="bg-sidebar-bg">Images</th>
                    <th class="bg-sidebar-bg">Action</th>
                    
                </tr>
            </thead>
                <tbody></tbody>
            </table>
            {{--End Table Category --}}
        </div>
    </div>
</section>
{{-- page content end --}}
<x-dashboardscript />
<x-dashboardfooter />