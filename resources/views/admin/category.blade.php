<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
<x-dashboardright />
{{-- Page content start --}}
<section style="height: auto" class="section-container p-1 bg-bg">
    {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel ls-1 p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'"
                class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
<div class="contenttab p-2 br-sm" >
    <div class="row talign-c">
        <div class="col-12-xs col-12-sm col-12-xl m-2 p-1 text-bg">
            <a href="{{ route('newcategory') }}" class="bg-secondary-dark-1 display-f align-center br-xs float-r p-1 mb-1"><span class="bg-secondary-dark-1"><svg class="bg-secondary-dark-1" width="20px" height="20px" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg" fill="#35424b">
                <g id="SVGRepo_bgCarrier" stroke-width="1"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"
                    stroke="#CCCCCC" stroke-width="0.288"></g>
                <g id="SVGRepo_iconCarrier">
                    <path d="M6 12h6V6h1v6h6v1h-6v6h-1v-6H6z"></path>
                    <path fill="none" d="M0 0h24v24H0z"></path>
                </g>
            </svg></span> Add new</a>
            <div class="form-group">
                <form>
                    <div class="multiselect bg-white">
                        <div class="selectBox br-xs bg-white" onclick="showCheckboxes()">
                            <select class="p-1  bg-white">
                                <option>Toggle columns</option>
                            </select>
                            <div class="overSelect br-xs"></div>
                        </div>
                        <div id="checkboxes">
                            <?php
                            $columns = ['Category ID', 'Category Name', 'Category Parent', 'Category Short Description', 'Category Images'];
                            for ($i = 0; $i < count($columns); $i++) {
                                echo "<label data-column='$i' class='talign-r pt-1' for='$i'>$columns[$i]
                                                      <input type='checkbox' class='checkbox' id='$i' /></label>";
                            }
                            ?>
                        </div>
                    </div>
    <h1  class="title talign-c font-xl ls-1 text-bg">{{ __('All categories') }}</h1>

                </form>
            </div>
            {{-- Table Category --}}
            <table class="category-table" id="category_table">
                <thead>
                    <tr>
                        <th class="bg-white">Catagory Id</th>
                        <th class="bg-white">Catagory Name</th>
                        <th class="bg-white">Catagory Parrent</th>
                        <th class="bg-white">Catagory Short Description</th>
                        <th class="bg-white">Images</th>
                        <th class="bg-white">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            {{-- End Table Category --}}
        </div>
    </div>
</div>
</section>
{{-- page content end --}}
<x-dashboardscript />
<x-dashboardscriptcategory />
<x-dashboardfooter />
