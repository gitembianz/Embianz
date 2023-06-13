<x-dashboardheader />
<x-dashboardnavbar />
{{-- Display session message --}}
@if (session()->has('message'))
    <div class="alert__session" id="alertevent">
        <Span class="alert__session-text">{!! session('message') !!}</Span>
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
    <div class="item__header">
        <h1 class="item__header-title" id="title">Category - {{ $data->name }}</h1>
        <div class="item__header-buttons">
            <a class="item__header-btn" href="{{ route('category') }}">Back</a>
            <a class="item__header-btn" href="{{ route('newcategory') }}">New</a>
            <input class="item__header-btn" type="button" value="Edit" name="edit" id="edit">
            <input class="item__header-btn delete" type="button" value="Delete" name="delete" id="deletecat">
        </div>
    </div>


    {{-- adding tab-bar --}}
    <div class="tab ">
        <button class="tablinks item__header-btn" onclick="opentab(event, 'Details')" id="defaultOpen">Details</button>
        <button class="tablinks item__header-btn" onclick="opentab(event, 'Releated')">Releated</button>
    </div>

    <div class="tabcontent br-xs" id="Details">
        {{-- Item Form --}}
        <form class="item__form" action="{{ route('category_update', $data->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            {{-- content --}}

            <div class="item__form-input-close">
                <div id="category_name">{{ $data->name }}</div>
                <span>Category Name</span>
            </div>
            <div class="item__form-input-close">
                <div id="category_parrent">{{ $data->parrent }}</div>
                <span>Category Parrent</span>
            </div>
            <div class="item__form-input-close">
                <div id="category_start_date">{{ $data->start_date }}</div>
                <span for="start_date">Category Start Date</span>
            </div>
            <div class="item__form-input-close">
                <div id="category_end_date">{{ $data->end_date }}</div>
                <span>Category End Date </span>
                <input type="hidden" name="hidden_id" value="{{ $data->id }}" id="hidden_id">
            </div>
            <div class="item__form-input-close">
                <div id="category_sequence">{{ $data->sequence }}</div>
                <span>Category Sequence</span>
            </div>
            <div class="item__form-input-close item__form-long">
                <div id="category_short_description">{{ $data->short_description }}</div>
                <span>Category Short Description</span>
            </div>
            <div class="item__form-input-close item__form-long">
                <div id="category_long_description">{{ $data->long_description }}</div>
                <span>Category Long Description</span>
            </div>
            <div class="item__form-input-close item__form-long">
                <div id="seo_title">{{ $data->seo_title }}</div>
                <span>SEO Title</span>
            </div>
            <div class="item__form-close">
                <div>{{ $data->created_at }}</div>
                <span>Create date / time</span>
            </div>
            <div class="item__form-close">
                <div>{{ $data->createdby }}</div>
                <span>Create by</span>
            </div>
            <div class="item__form-close">
                <div>{{ $data->updated_at }}</div>
                <span>Updated date / time</span>
            </div>
            <div class="item__form-close">
                <div>{{ $data->lastmodifiedby }}</div>
                <span>Last modified by</span>
            </div>

            <input class="item__form-btn item__form-long" type="submit" style="display: none" value="Update"
                id="Update">
        </form>
    </div>

    <div class="tabcontent" id="Releated">
        <div class="releated wid-10 talign-c br-xs">
            <button class="collapsible"><Span> {{ __('Media ') }}<span
                        class="fw-600">({{ $count_media }})</span></Span></span></button>

            <div class="contenttabb" id="contentDiv">
                <div class="col-12-xs col-12-sm col-12-xl talign-c">
                    <form action="{{ route('add_media', $data->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
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
                          <input type="submit" id="addmediacat" style="display: none" class="upload"
                                        value="Save Media">

                          <table id="imageTable" class="table"></table>
                      </div>

                    </form>
                </div>
                @if ($files->first() != null)
                    <div class="col-12-xs col-12-sm col-12-xl align-center">
                        <table id="mediaTable">
                            <thead>
                                <tr>
                                    <th>Media</th>
                                    <th>Location</th>
                                    <th>Sequence</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($files as $file)
                                    <tr>
                                        <td>
                                            @if (in_array($file->type, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'jfif']))
                                                <img src="/{{ $file->path . $file->name }}"
                                                    alt="{{ $file->name }}" width="100">
                                            @elseif (in_array($file->type, ['mp4', 'mov', 'avi']))
                                                <video src="/{{ $file->path . $file->name }}" width="150"
                                                    controls="true"></video>
                                            @else
                                                {{ $file->name }}
                                            @endif
                                        </td>
                                        <td>{{ $file->location->location }}</td>
                                        <td>{{ $file->sequence }}</td>

                                        <td><button class="cursor-p" type="button"
                                                onclick="removeFile({{ $file->id }})">x</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="col-12-xs col-12-sm col-12-xl mt-1 talign-c">
                        <span class="mt-1 m-a display-b text-bg wid-7 p-1 br-xs mb-2 bg-bg-light-9">No Media
                            related</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="releated mt-1 wid-10 talign-c br-xs">

            <button class="collapsible"><Span> {{ __('Products ') }}<span
                        class="fw-600">({{ $count_products }})</span></Span></button>


            <div class="contenttabb" id="contentDivp">
                <div class="col-12-xs col-12-sm col-12-xl mt-1 talign-c">
                    <form id="deleteProductsForm" action="{{ route('deleteSelectedProducts') }}" method="POST">
                        @csrf
                        <input type="submit" id="deletefromcheckbox" class="delete float-r mb-1"
                            style="display: none" value="">
                    </form>
                    <button id="addProductButton" class="add float-r mr-1 mb-1"> Add new product</button>
                </div>
                <div class="col-12-xs col-12-sm col-12-xl mt-1  display-b talign-c">
                    <form id="addProductsForm" class="mb-1 p-1" style="display: none"
                        action="{{ route('addSelectedProducts', $data->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="productIdsadd">
                        <button class="delete float-l" id="calceladdproducts"> Cancel</button>
                        <input type="submit" id="addfromcheckbox" class="add float-r" style="display: none"
                            value="">
                    </form>
                    <div id="tableContainerproducts" class="wid-10 bg-bg-light-9 p-1 mt-3 br-xs"
                        style="display: none"></div>


                </div>
                @if ($products->first() != null)
                    <div class="col-12-xs col-12-sm col-12-xl mt-1 pt-1 align-center">

                        <table id="productsTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td><input type="checkbox" class="product-checkbox"
                                                value="{{ $product->product->id }}"></td>
                                        <!-- Add checkbox column -->
                                        <td><a href="/show_product/{{ $product->product->id }}"
                                                class="link-name">{{ $product->product->name }}</a></td>
                                        <td>{{ $product->product->product_status }}</td>
                                        <td>{{ $product->product->quantity }}</td>
                                        <td><button class="cursor-p" type="button"
                                                onclick="removeProduct({{ $product->product->id }})">x</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="col-12-xs col-12-sm col-12-xl mt-1 talign-c">
                        <span class="mt-4 m-a display-b text-bg wid-7 p-1 br-xs mb-2 bg-bg-light-9">No products
                            related</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardscript />
<x-dashboardmediahanddler />
<x-dashboardscriptcategory />
<x-dashboardfooter />
