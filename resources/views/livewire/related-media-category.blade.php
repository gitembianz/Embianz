<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="releated wid-10 talign-c br-xs">
      <button class="collapsible"><Span> {{ __('Media ') }}<span
                  class="fw-600">({{ count($files) }})</span></Span></span></button>

      <div class="contenttabb" id="contentDiv">
          <div class="col-12-xs col-12-sm col-12-xl talign-c">
              <form action="{{ route('add_media', $categoryId) }}" method="POST" enctype="multipart/form-data">
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
          @if($files != "[]")
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
</div>
