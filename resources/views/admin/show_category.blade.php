<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
 {{-- Display session message --}}
 {{-- @if (session()->has('message'))
 <div class="bg-secondary pos-rel talign-c ls-1 mb-1 br-xs p-1" id="alertevent">
     {{ session()->get('message') }}
     <button type="button" onclick="document.getElementById('alertevent').style.display='none'"
         class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
 </div>
@endif --}}
{{-- End Section session message --}}
{{-- Page content start --}}
<section class="content">
        <div class="row">
            <div class="col-12-xs display-f jus-sb col-12-sm col-12-xl mb-1">
                <h1 id="title" class="mt-1 font-xl ls-1 text-bg">Category - {{ $data->name }}</h1>
                <div class="display-f">
                    <a href="{{ route('category') }}" class="back">Back</a>
                    <a href="{{ route('newcategory') }}" class="add ml-1">New</a>
                    <input type="button" class="delete ml-1" value="Delete" name="delete" id="deletecat">
                    <input type="button" class="edit ml-1" value="Edit" name="edit" id="edit">
                </div>
            </div>
        </div>
        {{-- adding tab-bar --}}
        <div class="tab bg-bg">
            <button class="tablinks br-xs p-1 m-1" onclick="opentab(event, 'Details')" id="defaultOpen">Details</button>
            <button class="tablinks br-xs p-1 m-1" onclick="opentab(event, 'Releated')">Releated</button>
        </div>
        <div class="tabcontent br-xs" id="Details">
            <form action="{{ route('category_update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- content --}}
                <div class="row">
                    <div class="col-12-xs col-12-sm col-12-xl text-bg">
                        <ul class="wid-10">
                            <li class="listelement p-1">
                                <div class="wid-2 display-g">
                                    <label class="font-lg text-bg mb-1 ls-1"> Category Name</label>
                                    <span class="p-1 talign-c text-bg p-1 br-xs bg-bg-light-9"
                                        id="category_name">{{ $data->name }}</span>
                                </div>

                                <div class="wid-2 display-g">
                                    <label class="font-lg mb-1  text-bg ls-1">Category Parrent</label>
                                    <span class="talign-c text-bg p-1 br-xs bg-bg-light-9"
                                        id="category_parrent">{{ $data->parrent }}</span>
                                </div>
                                <div class="wid-2 display-g">
                                    <label class="font-lg mb-1  text-bg ls-1" for="start_date">Category Start
                                        Date</label>
                                    <span class=" talign-c text-bg p-1 br-xs bg-bg-light-9"
                                        id="category_start_date">{{ $data->start_date }}</span>
                                </div>
                                <div class="wid-2 display-g">
                                    <label class="font-lg mb-1  text-bg ls-1">Category End Date </label>
                                    <span class="talign-c text-bg p-1 br-xs bg-bg-light-9"
                                        id="category_end_date">{{ $data->end_date }}</span>
                                    <input type="hidden" name="hidden_id" value="{{ $data->id }}" id="hidden_id">
                                </div>
                            </li>
                            <li class="listelement p-1">
                                <div class="wid-3 display-g">
                                    <label class="font-lg mb-1 text-bg ls-1">Category Sequence</label>
                                    <span class="font-md talign-c text-bg p-1 br-xs bg-bg-light-9"
                                        id="category_sequence">{{ $data->sequence }}</span>
                                </div>
                                <div class="wid-6 display-b">
                                    <label class="font-lg text-bg ls-1">Category Short Description</label>
                                    <span class="mt-1 wid-10 display-b text-bg p-1 br-xs bg-bg-light-9"
                                        id="category_short_description">{{ $data->short_description }}</span>
                                </div>
                            </li>
                            <li class="listelement p-1">
                                <div class="wid-10 display-b">
                                    <label class="font-lg text-bg ls-1">Category Long Description</label>
                                    <span class="textareamin wid-10 mt-1 display-b text-bg p-1 br-xs bg-bg-light-9"
                                        id="category_long_description">{{ $data->long_description }}</span>
                                </div>
                            </li>
                            <li class="listelement p-1">
                                <div class="wid-10 display-b">
                                    <label class="font-lg text-bg ls-1">SEO Title</label>
                                    <span class="mt-1 wid-10 display-b text-bg p-1 br-xs bg-bg-light-9"
                                        id="seo_title">{{ $data->seo_title }}</span>
                                </div>
                            </li>
                            <li class="listelement p-1">
                                <div class="wid-2 display-b">
                                    <label class="font-lg text-bg ls-1">Create date / time</label>
                                    <span
                                        class="mt-1  display-b text-bg p-1 br-xs bg-bg-light-9">{{ $data->created_at }}</span>
                                </div>
                                <div class="wid-2 display-b">
                                    <label class="font-lg text-bg ls-1">Create by</label>
                                    <span
                                        class="mt-1 display-b text-bg p-1 br-xs bg-bg-light-9">{{ $data->createdby }}</span>
                                </div>
                                <div class="wid-2 display-b">
                                    <label class="font-lg text-bg ls-1">Updated date / time</label>
                                    <span
                                        class="mt-1  display-b text-bg p-1 br-xs bg-bg-light-9">{{ $data->updated_at }}</span>
                                </div>
                                <div class="wid-2 display-b">
                                    <label class="font-lg text-bg ls-1">Last mofified by</label>
                                    <span
                                        class="mt-1 display-b text-bg p-1 br-xs bg-bg-light-9">{{ $data->lastmodifiedby }}</span>
                                </div>
                            </li>
                            <li class="talign-c wid-10">
                                <input type="submit" style="display: none" class="add wid-10" value="Update"
                                    id="Update">
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
        <div class="tabcontent" id="Releated">
            <div class="releated wid-10 talign-c br-xs">
                <button class="collapsible"><Span> {{ __('Media ') }}<span
                            class="fw-600">({{ $count_media }})</span></Span></span></button>

                <div class="contenttabb" id="contentDiv">
                    <div class="col-12-xs col-12-sm col-12-xl talign-c">
                        <form action="{{ route('add_media', $data->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <ul>
                                <li>
                                    <div class="col-12-xs col-12-sm col-12-xl display-f jus-c">
                                        <input type="file" name="media[]" id="imgUpload" multiple
                                            accept="image/*,video/*" onchange="filesManager(this.files)">

                                        <label class="upload wid-3" for="imgUpload"><span><svg width="40px"
                                                    height="40px" viewBox="0 0 1024.00 1024.00" class="icon"
                                                    version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <path d="M77.312 286.208h503.808v559.104H77.312z"
                                                            fill="35424b"></path>
                                                        <path d="M133.632 342.016h391.68v335.36H133.632z"
                                                            fill="#FFFFFF"></path>
                                                        <path
                                                            d="M189.44 621.568h93.184L236.032 537.6zM375.808 453.632l-93.184 167.936h186.88z"
                                                            fill="#82b09b"></path>
                                                        <path
                                                            d="M637.44 621.568v83.456l337.408-165.376-211.456-432.64-252.928 122.88h110.08l120.32-58.368 127.488 259.584-230.912 113.152z"
                                                            fill="35424b"></path>
                                                    </g>
                                                </svg></span> <span class="ml-1">Upload Media</span></label>
                                        <input type="submit" id="addmediacat" style="display: none" class="upload"
                                            value="Save Media">

                                    </div>
                                    <div class="col-12-xs col-12-sm col-12-xl align-center">
                                        <table id="imageTable" class="talign-c mt-2">
                                        </table>
                                    </div>
                                </li>
                            </ul>
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
                      <input type="hidden" name="categoryid" id="categoryid" value="{{ $data->id }}">
                      <input type="hidden" name="productIdsadd" id="productIdsadd">
                      <button class="delete float-l" id="canceladdproducts" style="display: none">Cancel</button>
                      <input type="button" id="addfromcheckbox" class="add float-r" style="display: none" value="Add Products">


                        <div id="tableContainerproducts" class="wid-10 bg-bg-light-9 p-1 mt-3 br-xs" style="display: none"></div>

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
