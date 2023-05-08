<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
<x-dashboardright />
{{-- Page content start --}}
<section class="section-container p-1 bg-bg">
    {{-- Display session message --}}
    @if (session()->has('message'))
        <div class="bg-secondary pos-rel talign-c ls-1 mb-1 br-xs p-1" id="alertevent">
            {{ session()->get('message') }}
            <button type="button" onclick="document.getElementById('alertevent').style.display='none'"
                class="exit font-lg bg-secondary float-r" data-bs-dismiss="alert" aria-hidden="true">x</button>
        </div>
    @endif
    {{-- End Section session message --}}
    <div class="contenttab bg-white text-bg p-2 br-sm">
        <div class="row jus-c">
            <div class="col-12-xs display-f jus-sb col-12-sm col-12-xl m-1 text-bg">
                <h1 id="title" class="mt-1 font-xl ls-1 text-bg">Category - {{ $data->name }}</h1>
                <div class="display-f">
                    <a href="{{ route('category') }}" class="boxsha bg-secondary display-f align-center br-xs p-1"> Go
                        Back</a>
                    <a href="{{ route('newcategory') }}"
                        class="boxsha bg-secondary ml-1 display-f align-center br-xs p-1"><span
                            class="bg-secondary"></span> Add new</a>
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
                            <label class="font-lg mb-1  text-bg ls-1" for="start_date">Category Start Date</label>
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
                            <span class="mt-1  display-b text-bg p-1 br-xs bg-bg-light-9">{{ $data->created_at }}</span>
                        </div>
                        <div class="wid-2 display-b">
                            <label class="font-lg text-bg ls-1">Create by</label>
                            <span class="mt-1 display-b text-bg p-1 br-xs bg-bg-light-9">{{ $data->createdby }}</span>
                        </div>
                        <div class="wid-2 display-b">
                            <label class="font-lg text-bg ls-1">Updated date / time</label>
                            <span class="mt-1  display-b text-bg p-1 br-xs bg-bg-light-9">{{ $data->updated_at }}</span>
                        </div>
                        <div class="wid-2 display-b">
                            <label class="font-lg text-bg ls-1">Last mofified by</label>
                            <span class="mt-1 display-b text-bg p-1 br-xs bg-bg-light-9">{{ $data->lastmodifiedby }}</span>
                        </div>
                    </li>
                    <li class="talign-c wid-10">
                        <input type="button"
                            class="edit br-xs wid-4 font-lg ls-1 text-bg cursor-p p-1 bg-secondary"
                            value="Edit" name="edit" id="edit">
                        <input type="submit" style="display: none"
                            class="edit br-xs wid-10 talign-c font-lg ls-1 text-bg cursor-p p-1  bg-secondary"
                            value="Update" id="Update">
                        <input type="button"
                            class="delete wid-4 font-lg text-bg br-xs ls-1 cursor-p p-1 bg-secondary"
                            value="Delete" name="delete" id="delete">
                    </li>
                </ul>
            </div>

        </div>
    </form>
</div>
<div class="tabcontent" id="Releated">
<div class="display-f wrap-w">
    <div class="releated wid-3 p-1 m-1 br-xs">
        <Span> {{ __('Media ') }}<span class="fw-600">({{ $count_media }})</span></Span>
        <a class="float-r align-center display-f" href="{{ route('media', ['id' => $data->id]) }}">View all <span class="fw-600 ml-1 mr-1">({{ $count_media }})</span> media  <svg class="ml-1" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20">
            <path fill="#000000" d="M0 6c0-0.128 0.049-0.256 0.146-0.354 0.195-0.195 0.512-0.195 0.707 0l8.646 8.646 8.646-8.646c0.195-0.195 0.512-0.195 0.707 0s0.195 0.512 0 0.707l-9 9c-0.195 0.195-0.512 0.195-0.707 0l-9-9c-0.098-0.098-0.146-0.226-0.146-0.354z"></path>
            </svg></a>
    </div>
    <div class="releated  wid-3 p-1 m-1 br-xs">
        <Span> {{ __('Products ') }}<span class="fw-600">({{ $count_products }})</span></Span>

        <a class="float-r" href="">View all <span class="fw-600">{{ $count_products }}</span> products</a>
    </div>

</div>
</div>
    </div>
</section>
{{-- page content end --}}
<x-dashboardscript />
<x-dashboardmediahanddler />
<x-dashboardscriptcategory />
<x-dashboardfooter />
