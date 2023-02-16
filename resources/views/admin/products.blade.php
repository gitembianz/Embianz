<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
{{-- Page content start --}}
<section style="height: auto" class="section-container bg-sidebar-bg-light-1">
    {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel ls-1 p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'" class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
    <h1 id="title" class="mt-1 talign-c font-xl ls-1 text-secondary">{{ __('All products') }}</h1>

    <div class="row talign-c">
        <div class="col-12-xs col-12-sm col-12-xl m-2 p-1 text-secondary">
            <div class="form-group float-r">
                <form>
                    <div class="multiselect">
                      <div class="selectBox br-xs" onclick="showCheckboxes()">
                        <select class="p-1  bg-sidebar-bg-light-1">
                          <option>Toggle columns</option>
                        </select>
                        <div class="overSelect br-xs"></div>
                      </div>
                      <div id="checkboxes">

                            <?php
                            $columns = ["Category ID", "Category Name", "Category Parent", "Category Short Description", "Category Images"];
                            for ($i = 0; $i < count($columns); $i++) {

                             echo "<label data-column='$i' class='talign-r pt-1' for='$i'>$columns[$i]
                          <input type='checkbox' class='checkbox' id='$i' /></label>";
                            }
                          ?>

                      </div>
                    </div>
                  </form>


            </div>
            {{-- Table Category --}}
            <table class="product-table" id="product-table">
                <thead>
                <tr>
                    <th class="bg-sidebar-bg">Product Name</th>
                    <th class="bg-sidebar-bg">Product Short Description</th>
                    <th class="bg-sidebar-bg">Product Quantity</th>
                    <th class="bg-sidebar-bg">Product Status</th>
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
