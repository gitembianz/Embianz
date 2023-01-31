<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar />
<x-dashboardmodals />
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
      <div class="col-12-xs display-c col-12-sm col-12-xl m-1 text-white">
        <h1 id="title" class="mt-1 talign-c font-xl ls-1 text-white">View - <span
          class="text-secondary">{{ $data->name }}</span> - details</h1>
          <input type="button"
                      onclick="event.preventDefault();location.href='/category'" class="cursor-p ls-1 br-xs p-1 text-secondary bg-hover-bg bg-sidebar-bg-light-1" value="Back" id="back">
      </div>
    </div>
    <div class="row gap-4 justify-center">
        <div class="col-12-xs col-12-sm mt-1 col-5-xl text-white">
            <form id="editcategory_form" action="{{ url('/category_update') }}" method="POST">
                @csrf
                    <ul class="p-1">
                        <li class="listelement p-1">
                            <label class="font-lg align-left text-white ls-1">Name -</label>
                                <span class="ml-1 font-lg talign-c text-secondary p-1 bg-bg" id="category_name">{{ $data->name }}</span>
                        </li>
                        <li class="listelement p-1">
                            <label class="font-lg text-white ls-1">Parrent -</label>
                                <span class="ml-1 font-lg talign-c text-secondary p-1 bg-bg" id="category_parrent">{{ $data->parrent }}</span>
                        </li>
                        <li class="listelement p-1">
                            <label class="font-lg text-white ls-1">Long Description -</label>
                                <span class="ml-1 font-lg talign-c text-secondary p-1 bg-bg" id="category_long_description">{{ $data->long_description }}</span>
                        </li>
                        <li class="listelement p-1">
                            <label class="font-lg text-white ls-1">Short Description -</label>
                                <span class="ml-1 font-lg talign-c text-secondary p-1 bg-bg" id="category_short_description">{{ $data->short_description }}</span>
                        </li>
                        <li class="listelement p-1">
                            <label class="font-lg text-white ls-1">Sequence -</label>
                                <span class="ml-1 font-lg talign-c text-secondary p-1 bg-bg" id="category_sequence">{{ $data->sequence }}</span>
                        </li>
                    </ul>
        </div>
        <div class="col-12-xs col-12-sm col-5-xl text-white">
            <ul>
                <li class="listelement p-1">
                        <label class="font-lg  text-white ls-1" for="start_date">Start Date -</label>
                            <span class="ml-1 font-lg talign-c text-secondary p-1 bg-bg" id="category_start_date">{{ $data->start_date }}</span>

                </li>
                <li class="listelement p-1">
                        <label class="font-lg text-white ls-1">End Date -</label>
                            <span class="ml-1 font-lg talign-c text-secondary p-1 bg-bg" id="category_end_date">{{ $data->end_date}}</span>
                        <input type="hidden" name="hidden_id" value="{{ $data->id }}" id="hidden_id">
                </li>
                <li class="listelement p-1">
                        <label class="font-lg text-white ls-1"> Image Main-</label>
                        @if (isset($data) && !is_null($data->image->first()))
                            <img id="category_image_main" src="/categories/{{ $data->image->first()->img_main_path }}"
                                alt="category_image_main" width="100" height="50">
                        @else
                        <span class="ml-1 font-lg talign-c text-secondary p-1 bg-bg" id="category_image_main">{{ __('Image not found') }}</span> 
                        @endif
                </li>
                <li class="listelement p-1">
                        <label class="font-lg text-white ls-1" for="end_date"> Image Search-</label>

                        @if (isset($data) && !is_null($data->image->first()))
                            <img id="category_image_search"
                                src="/categories/{{ $data->image->first()->img_search_path }}"
                                alt="category_image_search" width="100" height="50">
                        @else
                        <span class="ml-1 font-lg talign-c text-secondary p-1 bg-bg" id="category_image_search">{{ __('Image not found') }}</span> 
                        @endif
                </li>
                <li class="listelement p-1">
                        <label class="font-lg text-white ls-1" for="end_date">Image Sequence -</label>
                        @if (isset($data) && !is_null($data->image->first()))
                                <span class="ml-1 font-lg talign-c text-secondary p-1 bg-bg" id="category_image_sequence">{{ $data->image->first()->img_sequence}}</span>
                        @else
                        <span class="ml-1 font-lg talign-c text-secondary p-1 bg-bg" id="category_image_sequence">{{ __('Image Sequence not found') }}</span>
                        @endif
                </li>
            </ul>
        </div>
    </div>
    <div class="row gap-4 mt-1 justify-center talign-c">
        <div class="col-12-xs col-12-sm col-12-xl m-1 text-white">
            <ul>
                <li><input type="button"
                        onclick="document.getElementById('modal-category').style.display='block'; document.getElementById('viewmodal-category').style.display='none'"
                        class="cursor-p br-xs font-lg ls-1 talign-c p-1 text-secondary bg-hover-bg bg-sidebar-bg-light-1" value="Add New" id="new">
                    <input type="button" class="edit br-xs font-lg ls-1 text-secondary ml-1 cursor-p p-1 bg-hover-bg bg-sidebar-bg-light-1"
                        value="Edit" name="edit" id="edit">
                        <input type="submit" style="display: none" class="edit br-xs talign-c font-lg ls-1 text-secondary ml-1 cursor-p p-1 bg-hover-bg bg-sidebar-bg-light-1"
                        value="Update" name="edit" id="Update">

                    <input type="button" class="delete ml-1 font-lg text-secondary br-xs ls-1 cursor-p p-1 bg-hover-bg bg-sidebar-bg-light-1"
                        value="Delete" name="delete" id="delete">
                </li>
            </ul>
        </form>
        </div>
    </div>
</section>
{{-- page content end --}}
<x-dashboardscript />
<x-dashboardfooter />
