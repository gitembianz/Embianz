<div>
    {{-- session part --}}
    @if (session()->has('message'))
        <div class="alert__session" id="alertevent">
            <span class="alert__session-text">{!! session('message') !!}</span>
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
    {{-- end session --}}
    <div class="releated wid-10 talign-c br-xs">
        <button
            wire:click.prevent="@if ($showmedia === false) $set('showmedia', true) @else $set('showmedia', false) @endif"
            class="collapsible"><Span> {{ __('Media ') }}<span
                    class="fw-600">({{ count($files) }})</span></Span></span></button>
        @if ($showmedia)
            <div class="contenttabb" id="contentDiv">
                <div class="col-12-xs col-12-sm col-12-xl talign-c">
                    <form action="{{ route('add_media', $categoryId) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="item__upload">
                            <input type="file" name="media[]" id="imgUpload" multiple
                                accept="image/*,video/*"onchange="filesManager(this.files)">

                            <label class="item__upload-btn" for="imgUpload">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                    viewbox="0 0 24 24" fill="none" stroke="#BBFCDE" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
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

                        <div class="dropdown" onclick="OpenDropdown()">
                            <span class="dropdown-name">Columns</span>

                            <div class="dropdown-content">
                                @foreach ($columns as $column)
                                    <div class="display-f jus-fs wid-10 align-center">
                                        <input class="dropdown-item mr-1" type="checkbox" wire:model="selectedColumns"
                                            value="{{ $column }}"
                                            {{ in_array($column, $selectedColumns) ? 'checked' : '' }}>
                                        <label>{{ $column }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if ($checked)
                            <div class="dropdown" onclick="OpenDropdown()">
                                <span class="dropdown-name">With Checked ({{ count($checked) }})</span>

                                <div class="dropdown-content">
                                    <button class="dropdown-item delete" type="button"
                                        wire:click="confirmFilesRemovalmultiple()">
                                        Delete
                                    </button>
                                    <button class="dropdown-item submit" type="button" wire:click="exportSelected()">
                                        Export
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($selectPage)
                        <div class="pt-2 talign-c">

                            @if ($selectAll)
                                <div>
                                    You have selected all <strong>{{ count($checked) }}</strong> items.
                                </div>
                            @else
                                <div>
                                    You have selected <strong>{{ count($checked) }}</strong> items, Do you want to
                                    Select
                                    All?
                                    <a href="#" class="ml-2" wire:click="selectAll">Select All</a>
                                </div>
                            @endif

                        </div>
                    @endif

                    {{-- modals --}}
                    {{-- delete single record --}}
                    <div class="modal" id="confirmationmodalmedia">
                        <div class="modal-content">
                            <h1 class="modal-content-title">
                                {{ __('Are you sure to delete this media?') }}
                            </h1>
                            <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit"
                                type="button" value="Confirm">
                            <input class="modal-content-btn delete" type="button"
                                onclick="document.getElementById('confirmationmodalmedia').style.display='none'"
                                value="Cancel">

                            <span class="modal-content-btn delete"
                                onclick="document.getElementById('confirmationmodalmedia').style.display='none'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                    viewbox="0 0 24 24" fill="none" stroke="#BBFCDE" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
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

                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </span>
                        </div>
                    </div>
                    {{-- end modals --}}

                    {{-- Livewire table --}}

                    <table class="livewire-table">


                        <thead>
                            <tr>
                                <th><input type="checkbox" wire:model="selectPage"></th>

                                @if ($this->showColumn('Id'))
                                    <th class="cursor-p"
                                        @if ($orderBy === 'id' && $orderAsc === '1') data-symbol="up"
        @else
            data-symbol="down" @endif
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
    @else
        data-symbol="down" @endif
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
                                    <td data-title="Check"><input type="checkbox" value="{{ $file->id }}"
                                            wire:model="checked">
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
                                                <video src="/{{ $file->path . $file->name }}" width="150"
                                                    controls="true"></video>
                                            @else
                                                {{ $file->name }}
                                            @endif
                                        </td>
                                    @endif
                                    @if ($this->showColumn('Name'))
                                        <td data-title="Name">{{ $file->name }}</td>
                                    @endif

                                    @if ($this->showColumn('Media Location'))
                                        <td data-title="Media Location">{{ $file->location->location }}</td>
                                    @endif
                                    @if ($this->showColumn('Sequence'))
                                        <td data-title="Sequence">{{ $file->sequence }}</td>
                                    @endif

                                    <td data-title="Action">
                                        <button class="delete"
                                            wire:click.prevent="confirmFileRemoval({{ $file->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                viewBox="0 0 24 24" fill="none" stroke="#BBFCDE" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
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
                    <div class="col-12-xs col-12-sm col-12-xl mt-1 talign-c">
                        <span class="mt-1 m-a display-b text-bg wid-7 p-1 br-xs mb-2 bg-bg-light-9">No Media
                            related</span>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
