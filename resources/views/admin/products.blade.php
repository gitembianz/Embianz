<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
<x-dashboardright />
{{-- Page content start --}}
<section style="height: auto" class="section-container bg-bg">
    {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel ls-1 p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'" class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
    <div class="contenttab m-1 mb-1 p-2 br-sm" >

    <div class="row talign-c">
        <div class="col-12-xs col-12-sm col-12-xl text-bg">
            <a href="{{ route('add_products') }}" class="bg-secondary display-f align-center br-xs float-r p-1 mb-1">{{ __('Add new') }}</a>
            <div class="form-group mb-2">
                <form>
                    <div class="multiselect">
                      <div class="selectBox br-xs" onclick="showCheckboxes()">
                        <select class="p-1  bg-white">
                          <option>Toggle columns</option>
                        </select>
                        <div class="overSelect br-xs"></div>
                      </div>
                      <div id="checkboxes">

                            <?php
                            $columns = ["Product Image","Product ID","Product Name", "Product Short Description", "Product Quantity", "Product Status","Product Category"];
                            for ($i = 0; $i < count($columns); $i++) {

                             echo "<label data-column='$i' class='talign-r pt-1' for='$i'>$columns[$i]
                          <input type='checkbox' class='checkbox' id='$i' /></label>";
                            }
                          ?>

                      </div>
                    </div>
                  </form>
                  <h1 id="title" class="talign-c font-xl ls-1 text-bg">{{ __('All products') }}</h1>


            </div>
            {{-- Table Products --}}
            <table class="product-table" id="product-table">
                <thead>
                <tr>
                    <th class="bg-white" style="text-align: center">Image</th>
                    <th class="bg-white" style="text-align: center">ID</th>
                    <th class="bg-white" style="text-align: center">Name</th>
                    <th class="bg-white" style="text-align: center">Short Description</th>
                    <th class="bg-white" style="text-align: center">Quantity</th>
                    <th class="bg-white" style="text-align: center">Status</th>
                    <th class="bg-white" style="text-align: center">Category</th>
                    <th class="bg-white" style="text-align: center">Action</th>

                </tr>
            </thead>
            <tbody>
              </tbody>
            </table>
            {{--End Table Category --}}
        </div>
    </div>
    </div>
</section>
{{-- page content end --}}
<x-dashboardscript />
<x-dashboardscriptproduct />
<x-dashboardfooter />
