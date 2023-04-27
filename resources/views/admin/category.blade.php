<x-dashboardheader />
<x-dashbaordtopnavbar />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
<x-dashboardright />
{{-- Page content start --}}
<section style="height: auto" class="section-container  bg-bg">
    {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel ls-1 p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'"
                class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
<div class="contenttab m-1 mb-1 p-2 br-sm" >
    <div class="row talign-c">
        <div class="col-12-xs col-12-sm col-12-xl text-bg">
            <a href="{{ route('newcategory') }}" class="bg-secondary display-f align-center br-xs float-r p-1 mb-1">{{ __('Add new') }}</a>
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
                            $columns = ['Category Images','Category ID', 'Category Name', 'Category Parent', 'Category Short Description','Category Sequence'];
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
                        <th class="bg-white">Image</th>
                        <th class="bg-white">Id</th>
                        <th class="bg-white">Name</th>
                        <th class="bg-white">Catagory Parrent</th>
                        <th class="bg-white">Short Description</th>
                        <th class="bg-white">Squence</th>
                        <th class="bg-white">Action</th>
                    </tr>
                </thead>
                <tbody>
                  </tbody>
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
