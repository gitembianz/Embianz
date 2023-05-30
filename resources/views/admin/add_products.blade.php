<x-dashboardheader />
<x-dashboardnavbar />
{{-- Display session message --}}
@if (session()->has('message') && session()->has('item_name') && session()->has('item_id'))
    <div class="alert__session" id="alertevent">
        <Span class="alert__session-text">{!! session('message') !!} , click for view - <a
                href="{{ route('show_product', ['id' => session('item_id')]) }}">{{ session('item_name') }}</a></Span>
        <button class="alert__session-btn" type="button"
            onclick="document.getElementById('alertevent').style.display='none'" data-bs-dismiss="alert"
            aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
@endif
{{-- End Section session message --}}
<x-dashboardsidebar />
<x-dashboardmodals />

{{-- Page content start --}}
<section class="content">
    <form class="item" action="{{ url('/new_products') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Item Header --}}
        <div class="item__header">
            <h1 id="title" class="item__header-title">{{ __('New Product') }}</h1>
            <div class="item__header-buttons">
                <a href="{{ route('products') }}"class="item__header-btn"> Go Back</a>
                <button id="resetform" class="item__header-btn" type="reset">Clear form</button>
            </div>
        </div>


        <div class="item__upload">
            <input type="file" name="media[]" id="imgUpload" multiple
                accept="image/*,video/*"onchange="filesManager(this.files)">

            <label class="item__upload-btn" for="imgUpload">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                    stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                Upload Media
            </label>

            <table id="imageTable" class="table">
            </table>
        </div>

        <div class="item__form">
            <div class="item__form-input">
                <span> Product Name</span>
                <input type="text" name="product_name" placeholder="Enter a Product name" required>
            </div>
            <div class="item__form-input">
                <span>Product Category</span>
                <select id="select-category" name="category">
                    <option value="" selected="">Select a category</option>
                    @foreach ($categories as $category_name)
                        <option value="{{ $category_name }}">{{ $category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="item__form-input">
                <span>Product Start Date</span>
                <input type="date" class="p-1" id="start_date" name="start_date" required>
            </div>
            <div class="item__form-input">
                <span>Product End Date</span>
                <input type="date" id="end_date" class="p-1" name="end_date" required>
            </div>
            <div class="item__form-input">
                <span>Product Short Description</span>
                <input type="text" name="short_description" placeholder="Product short description" required>
            </div>
            <div class="item__form-input">
                <span>Product Quantity</span>
                <input type="number" name="quantity" placeholder="Select Quantity" required>
            </div>
            <div class="item__form-input">
                <span>Product Status</span>
                <select id="select-category" name="status">
                    <?php
                    $status = ['active', 'inactive', 'low stock'];
                    ?>
                    <option value="" selected="">Select a status</option>
                    @foreach ($status as $status_name)
                        <option value="{{ $status_name }}">{{ $status_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="item__form-input">
                <span>Product Long Description</span>
                <textarea name="long_description" placeholder="Enter a product Long description" required></textarea>
            </div>
            <div class="item__form-input">
                <span>SEO Title</span>
                <input type="text" name="seo_title" placeholder="Enter SEO" required>
            </div>
            <input class="item__form-btn" type="submit" value="Add new" name="submit">
        </div>
    </form>
</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardmediahanddler />
<x-dashboardscriptproduct />
<x-dashboardscript />
<x-dashboardfooter />



{{-- <div class="row">
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
              <div class="row jus-sb">
                  <div class="col-12-xs col-12-sm col-5-xl text-bg">
                      <ul>

                          <li>
                              <input type="file" name="media[]" id="imgUpload" multiple
                                  accept="image/*,video/*" onchange="filesManager(this.files)">

                              <label class="upload" for="imgUpload"><span><svg width="40px" height="40px"
                                          viewBox="0 0 1024.00 1024.00" class="icon" version="1.1"
                                          xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                          <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                              stroke-linejoin="round"></g>
                                          <g id="SVGRepo_iconCarrier">
                                              <path d="M77.312 286.208h503.808v559.104H77.312z" fill="35424b">
                                              </path>
                                              <path d="M133.632 342.016h391.68v335.36H133.632z" fill="#FFFFFF">
                                              </path>
                                              <path
                                                  d="M189.44 621.568h93.184L236.032 537.6zM375.808 453.632l-93.184 167.936h186.88z"
                                                  fill="#82b09b"></path>
                                              <path
                                                  d="M637.44 621.568v83.456l337.408-165.376-211.456-432.64-252.928 122.88h110.08l120.32-58.368 127.488 259.584-230.912 113.152z"
                                                  fill="35424b"></path>
                                          </g>
                                      </svg></span> <span class="ml-1">Upload Media</span></label>
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
                                  <input class="p-1" type="text" name="product_name"
                                      placeholder="Enter a Product name" required>
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
                                  <input type="date" class="p-1" id="start_date" name="start_date" required>
                              </div>
                              <div class="wid-4 display-g">
                                  <label class="font-lg text-bg mb-1 ls-1">Product End Date</label>
                                  <input type="date" id="end_date" class="p-1" name="end_date" required>
                              </div>
                          </li>

                          <li class="listelement mb-1">
                              <div class="wid-10 display-g">
                                  <label class="font-lg text-bg mb-1 ls-1">Product Short Description</label>
                                  <input class="p-1" type="text" name="short_description"
                                      placeholder="Product short description" required>
                              </div>
                          </li>
                          <li class="listelement mb-1">
                              <div class="wid-4 display-g">
                                  <label class="font-lg text-bg mb-1 ls-1">Product Quantity</label>
                                  <input type="number" class="p-1" name="quantity"
                                      placeholder="Select Quantity" required>
                              </div>
                              <div class="wid-4 display-g">
                                  <label class="font-lg text-bg mb-1 ls-1">Product Status</label>
                                  <select id="select-category" name="status"
                                      class="select-parent p-1 text-bg">
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
                                  <textarea name="long_description" class="p-1 wid-10" placeholder="Enter a product Long description" cols="30"
                                      rows="10" required></textarea>
                              </div>
                          </li>
                          <li class="listelement mb-1">
                              <div class="wid-10 display-g">
                                  <label class="font-lg text-bg mb-1 ls-1">SEO Title</label>
                                  <input class="p-1" type="text" name="seo_title"
                                      placeholder="Enter SEO" required>
                              </div>
                          </li>
                          <li class="listelement mb-1">
                              <input type="submit" class="add wid-10" value="Add new" name="submit">
                          </li>
                      </ul>
                  </div>
              </div>
          </form>
      </li>
  </ul>
</div> --}}
