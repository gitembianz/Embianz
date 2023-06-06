<x-dashboardheader />
<x-dashboardnavbar  />
<x-dashboardsidebar />
<x-dashboardmodals />


{{-- Page content start --}}
<section  class="content">
    <div class="row">
        <div class="col-12-xs col-12-sm col-12-xl">
            <a href="{{ route('newcategory') }}" class="addnew">{{ __('Add') }}</a>
            <div class="form-group mb-1">
                <form>
                    <div class="multiselect bg-white">
                        <div class="selectBox br-xs bg-white" onclick="showCheckboxes()">
                            <select class="p-1  bg-white">
                                <option>Columns</option>
                            </select>
                            <div class="overSelect br-xs"></div>
                        </div>
                        <div id="checkboxes">
                            <?php
                            $columns = ['Images','ID', 'Name', 'Parent', 'Short Description','Sequence'];
                            for ($i = 0; $i < count($columns); $i++) {
                                echo "<label data-column='$i' class='talign-l pt-1' for='$i'>$columns[$i]
                                                      <input type='checkbox' class='checkbox float-r' id='$i' /></label>";
                            }
                            ?>
                        </div>
                    </div>
    <h1  class="title talign-c font-xl ls-1 text-bg">{{ __('All categories') }}</h1>

                </form>
            </div>
            {{-- Table Categorys --}}
            <table class="category-table" style="width: 100% !important" id="category_table">
                <thead>
                    <tr>
                        <th class="bg-white">Image</th>
                        <th class="bg-white">Id</th>
                        <th class="bg-white">Name</th>
                        <th class="bg-white">Catagory Parrent</th>
                        <th class="bg-white">Short Description</th>
                        <th class="bg-white">Squence</th>
                    </tr>
                </thead>
                <tbody>
                  </tbody>
            </table>
            {{-- End Table Category --}}
        </div>
    </div>
</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardscript />
<x-dashboardscriptcategory />
<x-dashboardfooter />
