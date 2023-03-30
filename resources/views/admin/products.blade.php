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
    <div class="contenttab m-1 p-2 br-sm" >

    <div class="row talign-c">
        <div class="col-12-xs col-12-sm col-12-xl text-bg">
            <a href="{{ route('add_products') }}" class="boxsha bg-secondary-dark-1 display-f align-center br-xs float-r p-1 mb-1"><span class="bg-secondary-dark-1"><svg class="bg-secondary-dark-1" width="20px" height="20px" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg" fill="#35424b">
                <g id="SVGRepo_bgCarrier" stroke-width="1"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"
                    stroke="#CCCCCC" stroke-width="0.288"></g>
                <g id="SVGRepo_iconCarrier">
                    <path d="M6 12h6V6h1v6h6v1h-6v6h-1v-6H6z"></path>
                    <path fill="none" d="M0 0h24v24H0z"></path>
                </g>
            </svg></span> Add new</a>
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
                            $columns = ["Product Name", "Product Short Description", "Product Quantity", "Product Status"];
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
            {{-- Table Category --}}
            <table class="product-table" id="product-table">
                <thead>
                <tr>
                    <th class="bg-white">Product Name</th>
                    <th class="bg-white">Product Short Description</th>
                    <th class="bg-white">Product Quantity</th>
                    <th class="bg-white">Product Status</th>
                    <th class="bg-white">Action</th>

                </tr>
            </thead>
                <tbody></tbody>
            </table>
            {{--End Table Category --}}
        </div>
    </div>
    </div>
</section>
{{-- page content end --}}
<x-dashboardscriptproduct />
<x-dashboardscript />
<x-dashboardfooter />
