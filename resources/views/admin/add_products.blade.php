<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
<x-dashboardright />

{{-- Page content start --}}
<section class="section-container bg-bg">
    {{-- Display session message --}}
    @if (session()->has('message') && session()->has('item_name') && session()->has('item_id'))
    <div class="bg-secondary pos-rel ls-1 talign-c mb-1 br-xs p-1" id="alertevent">
        {!! session('message') !!}
        <Span> Click for view - <a href="{{ route('show_product', ['id' => session('item_id')]) }}" class="fw-800 font-lg">{{ session('item_name') }}</a></Span>
        <button type="button" onclick="document.getElementById('alertevent').remove()" class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
    </div>
@endif
    {{-- End Section session message --}}
    <div class="contenttab m-1 mb-2 bg-white text-bg br-sm">
        <div class="row">
            {{-- Header --}}
            <ul style="width: 100%">
                <li class="p-1 font-xl">
                    <form action="{{ url('/new_products') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row add_header col-12-xs col-12-sm col-12-xl jus-sb display-f">
                            <h1 id="title" class="ml-1 fw-500 ls-3 text-bg">{{ __('New Product') }}</h1>
                            <button id="resetform" class="cursor-p mr-1 bg-white text-bg p-1" type="reset">Clear form</button>
                        </div>
                        {{-- content --}}
                        <div class="row gap-4">
                            <div class="col-12-xs col-12-sm col-5-xl text-bg">
                                <ul>

                                    <li class="p-1 mt-1 wid-10">
                                        <label class="font-lg text-bg ls-1" >Product Image</label>
                                                <input type="file" name="media[]" id="imgUpload" multiple accept="image/*,video/*" onchange="filesManager(this.files)">

                                                <label class="button ml-3 font-md" for="imgUpload">Upload Media</label>
                                              <table id="imageTable" class="talign-c mt-2">
                                              </table>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-12-xs col-12-sm col-6-xl text-bg">
                                <ul>
                                    <li class="listelement p-1">
                                        <div class="wid-4 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1"> Product Name</label>
                                            <input class="p-1" type="text" name="product_name" placeholder="Enter a Product name" required>
                                        </div>
                                        <div class="wid-4 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Product Category</label>
                                            <select id="select-category" name="category" class="select-parent p-1 text-bg">
                                                <option value="" selected="">Select a category</option>
                                                @foreach ($categories as $category_name)
                                                    <option value="{{ $category_name }}">{{ $category_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <li class="listelement p-1">
                                        <div class="wid-4 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Product Start Date</label>
                                            <input type="date" class="p-1" id="start_date" name="start_date"  required>
                                        </div>
                                        <div class="wid-4 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Product End Date</label>
                                            <input type="date" id="end_date" class="p-1" name="end_date" required>
                                        </div>
                                    </li>

                                    <li class="listelement p-1">
                                        <div class="wid-10 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Product Short Description</label>
                                            <input class="p-1" type="text" name="short_description"  placeholder="Product short description" required>
                                        </div>
                                    </li>
                                    <li class="listelement p-1">
                                        <div class="wid-4 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Product Quantity</label>
                                            <input type="number" class="p-1" name="quantity"  placeholder="Select Quantity" required>
                                        </div>
                                        <div class="wid-4 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Product Status</label>
                                            <select id="select-category" name="status" class="select-parent p-1 text-bg">
                                                <?php
                                                $status = ['active', 'inactive', 'low stock'];
                                                ?>
                                                <option value="" selected="">Select a status</option>
                                                @foreach ($status as $status_name)
                                                    <option value="{{ $status_name }}">{{ $status_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <li class="listelement p-1">
                                        <div class="wid-10 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Product Long Description</label>
                                            <textarea name="long_description" class="p-1 wid-10" placeholder="Enter a product Long description" cols="30" rows="10" required></textarea>
                                        </div>
                                    </li>
                                    <li class="listelement p-1">
                                        <div class="wid-10 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">SEO Title</label>
                                            <input class="p-1" type="text" name="seo_title" placeholder="Enter SEO" required>
                                        </div>
                                    </li>
                                    <li class="listelement p-1">
                                        <div class="wid-10 display-b">
                                        <input type="submit"
                                            class="addcategory display-f align-center br-xs float-l p-1 mb-1" value="Add new" name="submit">
                                        <a href="{{ route('products') }}"class="backcategory display-f ml-2 float-r br-xs p-1"> Go Back</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</section>
{{-- page content end --}}
<x-dashboardmediahanddler />
<x-dashboardscriptproduct />
<x-dashboardscript />

<x-dashboardfooter />
