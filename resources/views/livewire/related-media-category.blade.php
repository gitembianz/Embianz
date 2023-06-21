<div>

    @if (session()->has('message'))
        <div class="alert__session liveAlert" id="alertevent">
            <span class="alert__session-text">{!! session('message') !!}</span>
            <button class="alert__session-btn" type="button" data-bs-dismiss="alert" aria-hidden="true">
                <svg>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <script>
            const alertEvent = document.getElementById("alertevent");
            header.style.marginBottom = '4rem';
            alertEvent.style.opacity = '1';

            setTimeout(function() {
                alertEvent.style.opacity = '0';
                setTimeout(function() {
                    alertEvent.remove();
                    header.style.marginBottom = '0';
                }, 500);
            }, 2000);
        </script>
    @endif

    <div class="accordion">
        <button class="accordion__btn"
            wire:click.prevent="@if ($showmedia === false) $set('showmedia', true) @else $set('showmedia', false) @endif">
            {{ __('Media ') }}({{ count($files) }})
        </button>

        @if ($showmedia)
            <div class="accordion__content" id="contentDiv">
                <div class="item">
                    <div class="item">
                        <form wire:submit.prevent="save">
                            <div class="item item__upload  mb-2 pb-3 bb-2">
                                <input id="imgUpload" accept="image/*,video/*" type="file" multiple
                                    wire:model="medias">
                                <label class="item__upload-btn" for="imgUpload">
                                    <svg>
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                    {{ __('Upload related media') }}
                                </label>

                                @if ($medias)
                                    <input type="submit" id="add_media_related" class="item__form-btn item__form-long"
                                        value="Save Media">
                                    <div class="table-scroll">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Media</th>
                                                    <th>Name</th>
                                                    <th>Size</th>
                                                    <th>Type</th>
                                                    <th>Sequence</th>
                                                    <th>Location</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($medias as $media)
                                                    <tr>
                                                        <td>
                                                            @if (str_starts_with($media->getMimeType(), 'image'))
                                                                <img src="{{ $media->temporaryUrl() }}" width="50px">
                                                            @elseif (str_starts_with($media->getMimeType(), 'video'))
                                                                <video width="100px" controls>
                                                                    <source src="{{ $media->temporaryUrl() }}"
                                                                        type="{{ $media->getMimeType() }}">
                                                                    <span>{{ __('Your browser not suport video tag') }}</span>
                                                                </video>
                                                            @endif
                                                        </td>
                                                        <td>{{ $media->getClientOriginalName() }}</td>
                                                        <td>{{ $media->getSize() }} KB</td>
                                                        <td>{{ $media->getClientOriginalExtension() }}</td>
                                                        <td><input type="number" placeholder="Media sequence"
                                                                min="0" required
                                                                wire:model="file_sequences.{{ $loop->index }}"></td>
                                                        <td>
                                                            <select required
                                                                wire:model="file_locations.{{ $loop->index }}">

                                                                @php
                                                                    $firstLocation = $locations->first();
                                                                @endphp
                                                                <option value="{{ $firstLocation->id }}" selected>
                                                                    {{ $firstLocation->location }}</option>
                                                                @foreach ($locations as $index => $location)
                                                                    @if ($loop->first)
                                                                        @continue
                                                                    @endif
                                                                    <option value="{{ $location->id }}">
                                                                        {{ $location->location }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><button class="delete"
                                                                wire:click.prevent="removemedia({{ $loop->index }})">
                                                                <svg>
                                                                    <circle cx="12" cy="12"
                                                                        r="10">
                                                                    </circle>
                                                                    <line x1="15" y1="9" x2="9"
                                                                        y2="15">
                                                                    </line>
                                                                    <line x1="9" y1="9" x2="15"
                                                                        y2="15">
                                                                    </line>
                                                                </svg>
                                                            </button></td>
                                                    </tr>
                                                @endforeach
                                @endif
                                </tbody>
                                </table>
                            </div>
                        </form>

                    </div>
                    </form>
                </div>
                <div class="item">
                    @if (count($files) > 0)
                        <div class="item__form form-table"
                            @if ($checked) style="grid-template-columns: 40% 1fr 1fr 1fr" @endif>

                            <div class="item__form-input">
                                <input wire:model.debounce.200ms="search" type="text" required>
                                <span>Search Media...</span>
                            </div>
                            <div class="item__form-input">
                                <select id="perPage" wire:model="perPage">
                                    <option>10</option>
                                    <option>25</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                                <span>Per Page :</span>
                            </div>

                            <div class="dropdown" style="z-index: 5;">
                                <button
                                    wire:click.prevent="@if ($col === false) $set('col', true) @else $set('col', false) @endif"
                                    class="dropdown-button">Columns</button>
                                @if ($col)

                                    <div class="dropdown-list" style="display: flex;">
                                        @foreach ($columns as $column)
                                            <div class="dropdown-item">
                                                <input type="checkbox" wire:model="selectedColumns"
                                                    value="{{ $column }}"
                                                    {{ in_array($column, $selectedColumns) ? 'checked' : '' }}>
                                                <label>{{ $column }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="dropdown none"
                                @if ($checked) style="display: unset; z-index: 5;" @endif>
                                <button
                                    wire:click.prevent="@if ($all === false) $set('all', true); $set('col', false) @else $set('all', false) @endif"
                                    class="dropdown-button none"
                                    @if ($checked) style="display: flex" @endif>With
                                    Checked({{ count($checked) }})</button>
                                @if ($checked)
                                    @if ($all)
                                        <div class="dropdown-list" style="display: flex;">
                                            <button class="dropdown-item delete" type="button"
                                                wire:click="confirmFilesRemovalmultiple()">
                                                Delete
                                            </button>
                                            <button class="dropdown-item submit" type="button"
                                                wire:click="exportSelected()">
                                                Export
                                            </button>
                                        </div>
                                    @endif

                                @endif

                                {{-- modals --}}
                                {{-- delete single record --}}
                                <div class="modal" id="confirmationmodalmedia">
                                    <div class="modal-content">
                                        <h1 class="modal-content-title">
                                            {{ __('Are you sure to delete this media?') }}
                                        </h1>
                                        <input wire:click.prevent="deleteSingleRecord()"
                                            class="modal-content-btn submit" type="button" value="Confirm">
                                        <input class="modal-content-btn delete" type="button"
                                            onclick="document.getElementById('confirmationmodalmedia').style.display='none'"
                                            value="Cancel">


                                        <span class="modal-content-btn delete"
                                            onclick="document.getElementById('confirmationmodalmedia').style.display='none'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                viewbox="0 0 24 24" fill="none" stroke="#BBFCDE" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18">
                                                </line>
                                                <line x1="6" y1="6" x2="18" y2="18">
                                                </line>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <<<<<<< HEAD <span class="modal-content-btn delete"
                                onclick="document.getElementById('confirmationmodalmediamultiple').style.display='none'">
                                =======
                                @if ($selectPage)
                                    <div class="pt-2 talign-c">
                                        >>>>>>> 8b7bad17 (datatable border and scroll change)

                                        @if ($selectAll)
                                            <div>
                                                You have selected all <strong>{{ count($checked) }}</strong> items.
                                            </div>
                                        @else
                                            <div>
                                                You have selected <strong>{{ count($checked) }}</strong> items, Do you
                                                want
                                                to
                                                Select
                                                All?
                                                <a href="#" class="ml-2" wire:click="selectAll">Select
                                                    All</a>
                                            </div>
                                        @endif

                                @endif

                                {{-- modals --}}
                                {{-- delete single record --}}
                                <div class="modal" id="confirmationmodalmedia">
                                    <div class="modal-content">
                                        <h1 class="modal-content-title">
                                            {{ __('Are you sure to delete this media?') }}
                                        </h1>
                                        <input wire:click.prevent="deleteSingleRecord()"
                                            class="modal-content-btn submit" type="button" value="Confirm">
                                        <input class="modal-content-btn delete" type="button"
                                            onclick="document.getElementById('confirmationmodalmedia').style.display='none'"
                                            value="Cancel">

                                        <span class="modal-content-btn delete"
                                            onclick="document.getElementById('confirmationmodalmedia').style.display='none'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                viewbox="0 0 24 24" fill="none" stroke="#BBFCDE" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18">
                                                </line>
                                                <line x1="6" y1="6" x2="18" y2="18">
                                                </line>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                {{-- delete myltiple records --}}
                                <div class="modal" id="confirmationmodalmediamultiple">
                                    <div class="modal-content">
                                        <h1 class="modal-content-title">
                                            {{ __('Are you sure to delete those categories?') }}
                                        </h1>
                                        <input wire:click.prevent="deleteRecords()" class="modal-content-btn submit"
                                            type="button" value="Confirm">
                                        <input class="modal-content-btn delete" type="button"
                                            onclick="document.getElementById('confirmationmodalmediamultiple').style.display='none'"
                                            value="Cancel">

                                        <span class="modal-content-btn delete"
                                            onclick="document.getElementById('confirmationmodalmediamultiple').style.display='none'">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                viewbox="0 0 24 24" fill="none" stroke="#BBFCDE" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">

                                                <line x1="18" y1="6" x2="6" y2="18">
                                                </line>
                                                <line x1="6" y1="6" x2="18" y2="18">
                                                </line>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                {{-- end modals --}}

                                <table class="livewire-table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" wire:model="selectPage"></th>

                                            @if ($this->showColumn('Id'))
                                                <th class="cursor-p"
                                                    @if ($orderBy === 'id' && $orderAsc === '1') data-symbol="up"
                                                    @else data-symbol="down" @endif
                                                    wire:click="sortBy('id')">ID
                                                </th>
                                            @endif
                                            @if ($this->showColumn('Media'))
                                                <th class="cursor-p">Media
                                                </th>
                                            @endif
                                            @if ($this->showColumn('Name'))
                                                <th class="cursor-p"
                                                    @if ($orderBy === 'name' && $orderAsc === '1') data-symbol="up"

                                                          @else data-symbol="down" @endif
                                                    wire:click="sortBy('name')">Name
                                                </th>
                                            @endif

                                            @if ($this->showColumn('Media Location'))
                                                <th class="cursor-p">Media Location
                                                </th>
                                            @endif
                                            @if ($this->showColumn('Sequence'))
                                                <th class="cursor-p">Sequence
                                                </th>
                                            @endif

                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($files as $file)
                                            <tr class="@if ($this->isChecked($file->id)) th_checked @endif">
                                                <td data-title="Check"><input type="checkbox"
                                                        value="{{ $file->id }}" wire:model="checked">
                                                </td>

                                                @if ($this->showColumn('Id'))
                                                    <td data-title="ID">{{ $file->id }}</td>
                                                @endif
                                                @if ($this->showColumn('Media'))
                                                    <td data-title="Media">
                                                        @if (in_array($file->type, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'jfif']))
                                                            <img src="/{{ $file->path . $file->name }}"
                                                                alt="{{ $file->name }}" width="100">
                                                        @elseif (in_array($file->type, ['mp4', 'mov', 'avi']))
                                                            <video src="/{{ $file->path . $file->name }}"
                                                                width="150" controls="true"></video>
                                                        @else
                                                            {{ $file->name }}
                                                        @endif
                                                    </td>
                                                @endif
                                                @if ($this->showColumn('Name'))
                                                    <td data-title="Name">{{ $file->name }}</td>
                                                @endif


                                                @if ($this->showColumn('Media Location'))
                                                    <td data-title="Media Location">{{ $file->location->location }}
                                                    </td>
                                                @endif
                                                @if ($this->showColumn('Sequence'))
                                                    <td data-title="Sequence">{{ $file->sequence }}</td>
                                                @endif

                                                <<<<<<< HEAD <th>
                                                    </th>
                                            </tr>
                                            </thead>
                                    <tbody>
                                        @foreach ($files as $index => $file)
                                            <tr class="@if ($this->isChecked($file->id)) th_checked @endif">
                                                <td data-title="Check"><input type="checkbox"
                                                        value="{{ $file->id }}" wire:model="checked">
                                                </td>

                                                @if ($this->showColumn('Id'))
                                                    <td data-title="ID">{{ $file->id }}</td>
                                                @endif
                                                @if ($this->showColumn('Media'))
                                                    <td data-title="Media">
                                                        @if (in_array($file->type, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'jfif']))
                                                            <img src="/{{ $file->path . $file->name }}"
                                                                alt="{{ $file->name }}" width="100">
                                                        @elseif (in_array($file->type, ['mp4', 'mov', 'avi']))
                                                            <video src="/{{ $file->path . $file->name }}"
                                                                width="150" controls="true"></video>
                                                        @else
                                                            {{ $file->name }}
                                                        @endif
                                                    </td>
                                                @endif
                                                @if ($this->showColumn('Name'))
                                                    <td data-title="Name">{{ $file->name }}</td>
                                                @endif


                                                @if ($this->showColumn('Media Location'))
                                                    <td data-title="Media Location">
                                                        @if ($editedMediaIndex !== $index)
                                                            <div class="cursor-p"
                                                                wire:click.prevent="editMedia({{ $index }})">
                                                                {{ $file->location->location }}</div>
                                                        @else
                                                            <select required
                                                                wire:model.defer="filess.{{ $index }}.location_id">
                                                                @foreach ($locations as $location)
                                                                    <option value="{{ $location->id }}">
                                                                        {{ $location->location }}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </td>
                                                @endif
                                                @if ($this->showColumn('Sequence'))
                                                    <td data-title="Sequence">
                                                        @if ($editedMediaIndex !== $index)
                                                            <div class="cursor-p"
                                                                wire:click.prevent="editMedia({{ $index }})">
                                                                {{ $file->sequence }}</div>
                                                        @else
                                                            <input type="number" min="0" required
                                                                wire:model.defer="filess.{{ $index }}.sequence"
                                                                value="{{ $file->sequence }}">
                                                            @if ($errors->has('filess.' . $index . '.sequence'))
                                                                <p>{{ $errors->first('filess.' . $index . '.sequence') }}
                                                                </p>
                                                            @endif
                                                        @endif
                                                    </td>
                                                @endif

                                                <td data-title="Action">
                                                    @if ($editedMediaIndex !== $index)
                                                        <button class="edit"
                                                            wire:click.prevent="editMedia({{ $index }})">
                                                            <svg>
                                                                <path
                                                                    d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <button class="delete"
                                                            wire:click.prevent="confirmFileRemoval({{ $file->id }})">
                                                            <svg>
                                                                <circle cx="12" cy="12" r="10">
                                                                </circle>
                                                                <line x1="15" y1="9" x2="9"
                                                                    y2="15">
                                                                </line>
                                                                <line x1="9" y1="9" x2="15"
                                                                    y2="15">
                                                                </line>
                                                            </svg>
                                                        </button>
                                                    @else
                                                        <button class="edit"
                                                            wire:click.prevent="saveMedia({{ $index }} , {{ $file->id }})">
                                                            <svg>
                                                                <polyline points="20 6 9 17 4 12"></polyline>
                                                            </svg>
                                                        </button>
                                                        <button class="save" wire:click.prevent="cancelMedia()">
                                                            <svg>
                                                                <line x1="18" y1="6" x2="6"
                                                                    y2="18"></line>
                                                                <line x1="6" y1="6" x2="18"
                                                                    y2="18"></line>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div>{{ $files->links('pagination-links') }} </div>
                            @else
                        </div>
                </div>
                <div class="item">
                    <span class="talign-c p-1">
                        No Media related</span>
                </div>
        @endif
        =======
        <td data-title="Action">
            <button class="delete" wire:click.prevent="confirmFileRemoval({{ $file->id }})">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                    fill="none" stroke="#BBFCDE" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10">
                    </circle>
                    <line x1="15" y1="9" x2="9" y2="15">
                    </line>
                    <line x1="9" y1="9" x2="15" y2="15">
                    </line>
                </svg>
            </button>
        </td>
        </tr>
        @endforeach
        </tbody>
        </table>
        <div>{{ $files->links('pagination-links') }} </div>
    @else
    </div>
</div>
@if ($medias)
@else
    <div class="col-12-xs col-12-sm col-12-xl mt-1 talign-c">
        <span class="mt-1 m-a display-b text-bg wid-7 p-1 br-xs mb-2 bg-bg-light-9">No Media
            related</span>
    </div>
@endif
>>>>>>> 8b7bad17 (datatable border and scroll change)
@endif
</div>
</div>
</div>
