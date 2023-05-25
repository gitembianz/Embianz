<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
 {{-- Display session message --}}
 @if (session()->has('message') && session()->has('item_name') && session()->has('item_id'))
 <div class="bg-secondary pos-rel ls-1 talign-c mb-1 br-xs p-1" id="alertevent">
     {!! session('message') !!}
     <Span> Click for view - <a href="{{ route('show_product', ['id' => session('item_id')]) }}" class="fw-800 font-lg">{{ session('item_name') }}</a></Span>
     <button type="button" onclick="document.getElementById('alertevent').remove()" class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
 </div>
@endif
 {{-- End Section session message --}}

{{-- Page content start --}}
<section class="content">
        <div class="row">
            {{-- Header --}}
            <ul style="width: 100%">
                <li class="p-1 font-xl">
                    <form action="{{ url('/new_products') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row add_header col-12-xs col-12-sm col-12-xl jus-sb display-f">
                            <h1 id="title" class="ml-1 fw-500 ls-3 text-bg">{{ __('New Product') }}</h1>
                            <div>
                            <a href="{{ route('products') }}"class="back font-sm"> Go Back</a>
                            <button id="resetform" class="back font-sm" type="reset">Clear form</button>
                            </div>
                        </div>
                        {{-- content --}}
                        <div class="row jus-sb">
                            <div class="col-12-xs col-12-sm col-5-xl text-bg">
                                <ul>

                                    <li>
                                                <input type="file" name="media[]" id="imgUpload" multiple accept="image/*,video/*" onchange="filesManager(this.files)">

                                                <label class="upload" for="imgUpload"><span><svg width="40px" height="40px" viewBox="0 0 1024.00 1024.00" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M77.312 286.208h503.808v559.104H77.312z" fill="35424b"></path><path d="M133.632 342.016h391.68v335.36H133.632z" fill="#FFFFFF"></path><path d="M189.44 621.568h93.184L236.032 537.6zM375.808 453.632l-93.184 167.936h186.88z" fill="#82b09b"></path><path d="M637.44 621.568v83.456l337.408-165.376-211.456-432.64-252.928 122.88h110.08l120.32-58.368 127.488 259.584-230.912 113.152z" fill="35424b"></path></g></svg></span> <span class="ml-1">Upload Media</span></label>
                                              <table id="imageTable" class="talign-c mt-2">
                                              </table>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-12-xs col-12-sm col-6-xl text-bg">
                                <ul>
                                    <li class="listelement mb-1">
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
                                    <li class="listelement mb-1">
                                        <div class="wid-4 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Product Start Date</label>
                                            <input type="date" class="p-1" id="start_date" name="start_date"  required>
                                        </div>
                                        <div class="wid-4 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Product End Date</label>
                                            <input type="date" id="end_date" class="p-1" name="end_date" required>
                                        </div>
                                    </li>

                                    <li class="listelement mb-1">
                                        <div class="wid-10 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Product Short Description</label>
                                            <input class="p-1" type="text" name="short_description"  placeholder="Product short description" required>
                                        </div>
                                    </li>
                                    <li class="listelement mb-1">
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
                                    <li class="listelement mb-1">
                                        <div class="wid-10 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">Product Long Description</label>
                                            <textarea name="long_description" class="p-1 wid-10" placeholder="Enter a product Long description" cols="30" rows="10" required></textarea>
                                        </div>
                                    </li>
                                    <li class="listelement mb-1">
                                        <div class="wid-10 display-g">
                                            <label class="font-lg text-bg mb-1 ls-1">SEO Title</label>
                                            <input class="p-1" type="text" name="seo_title" placeholder="Enter SEO" required>
                                        </div>
                                    </li>
                                    <li class="listelement mb-1">
                                        <input type="submit"
                                            class="add wid-10" value="Add new" name="submit">
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardmediahanddler />
<x-dashboardscriptproduct />
<x-dashboardscript />
<x-dashboardfooter />
