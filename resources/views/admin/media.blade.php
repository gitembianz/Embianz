<x-dashboardheader />
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
    {{-- <div class="row talign-c">

        @foreach ($files as $file )
        {{ $file->location->location}}
        {{ $file->sequence}}
        {{ $file->name}}
        {{ $file->tabel->name }}

        @endforeach
    </div> --}}
    @if($files->first() != NULL)
    <div class="row talign-c">
        <table>
            <thead>
                <tr>
                    <th>Location</th>
                    <th>Sequence</th>
                    <th>Media</th>
                    <th>Belong's to</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($files as $file)
                    <tr>
                        <td>
                            <span>{{ $file->location->location }}</span>
                        </td>
                        <td>
                            <span>{{ $file->sequence }}</span>
                        </td>
                        <td>
                            @if (in_array($file->type, ['jpg', 'jpeg', 'png', 'gif','svg','jfif']))
                                <img src="/{{ $file->path . $file->name }}" alt="{{ $file->name }}" width="100">
                            @elseif (in_array($file->type, ['mp4', 'mov', 'avi']))
                                <video src="/{{ $file->path . $file->name }}" width="150" controls="true"></video>
                            @else
                                {{ $file->name }}
                            @endif
                        </td>
                        <td>{{ $file->tabel->name }}</td>
                        <td>
                            <button class="cursor-p" type="button" onclick="updateFile({{ $file->id }})">Update</button>
                            <button class="cursor-p" type="button" onclick="removeFile({{ $file->id }})">Remove</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="col-12-xs col-12-sm col-5-xl text-bg">
        <ul>
            <li class="talign-c p-2">
                <input type="file" name="media[]" id="imgUpload" multiple accept="image/*,video/*" onchange="filesManager(this.files)">

                <label class="button ml-3 font-md" for="imgUpload">Upload Media</label>
              <table id="imageTable" class="talign-c mt-2">
              </table>
            </li>


        </ul>
    </div>
    @endif
</div>
</section>
{{-- page content end --}}
<x-dashboardmediahanddler />
<x-dashboardscript />
<x-dashboardscriptcategory />
<x-dashboardfooter />
