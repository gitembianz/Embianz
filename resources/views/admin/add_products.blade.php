<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
<x-dashboardright />
{{-- Page content start --}}
<section class="section-container bg-sidebar-bg-light-1">
    {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel ls-1 p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'"
                class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
    <div class="row gap-4 mt-2 mb-2 justify-center talign-c">
        {{-- page title --}}
        <div class="col-12-xs display-c col-12-sm col-12-xl m-1 text-white">
            <h1 id="title" class="mt-1 talign-c font-xl ls-1 text-white">Add new Product</h1>

        </div>
    </div>
    <form  action="#" method="POST">
        @csrf
        <div class="row justify-center">
            <div class="col-12-xs col-12-sm col-4-xl text-white">
                <ul class="p-1">
                    <li class="listelement p-1">
                        <label class="font-lg text-white ls-1">Enter a product name -</label>
                        <input type="text" class="bg-bg p-1  text-white" name="product_name" placeholder="Enter here"
                            required>
                    </li>
                    <li class="listelement p-1">

                    </li>
                    <li class="listelement p-1">

                    </li>
                    <li class="listelement p-1">

                    </li>
                    <li class="listelement p-1">

                    </li>
                </ul>
            </div>
            <div class="col-12-xs col-12-sm col-4-xl text-white">
                <ul class="p-1">
                    <li class="listelement p-1">
                        <label class="font-lg text-white ls-1">Enter a product name -</label>
                        <input type="text" class="bg-bg p-1  text-white" name="product_name" placeholder="Enter here"
                            required>
                    </li>
                    <li class="listelement p-1">

                    </li>
                    <li class="listelement p-1">

                    </li>
                    <li class="listelement p-1">



                    </li>
                    <li class="listelement p-1">

                    </li>
                </ul>
            </div>
            <div class="col-12-xs col-12-sm col-4-xl text-white">
                <ul class="p-1">
                    <li class="listelement p-1">
                        <label class="font-lg text-white ls-1">Enter a product name -</label>
                        <input type="text" class="bg-bg p-1  text-white" name="product_name" placeholder="Enter here"
                            required>
                    </li>
                    <li class="listelement p-1">

                    </li>
                    <li class="listelement p-1">

                    </li>
                    <li class="listelement p-1">



                    </li>
                    <li class="listelement p-1">

                    </li>
                </ul>
            </div>
            <div class="row gap-4 mt-1 justify-center talign-c">
                <div class="col-12-xs col-12-sm col-12-xl m-1 text-white">
                    <ul>
                        <li>
                            <input type="submit"
                                class="edit br-xs talign-c font-lg ls-1 text-secondary ml-1 cursor-p p-1 bg-hover-bg bg-sidebar-bg-light-1"
                                value="Add Product" name="add_product" id="add_product">


                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</section>
{{-- page content end --}}
<x-dashboardscript />
<x-dashboardscriptproduct />
<x-dashboardfooter />

