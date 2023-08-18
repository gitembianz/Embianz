<div>
    <x-alert />
    <x-loading />
    <x-modals />
    <div class="accordion">
        <div class="accordion__btn-flex">
            <button class="accordion__btn"
                wire:click.prevent="@if ($showmedia === false) $set('showmedia', true) @else $set('showmedia', false) @endif">
                {{ __('Media ') }}({{ $category->media()->count() }})
            </button>
            <button class="accordion__upload" wire:click="uploadmedia">
                <div class="item__upload-btn">
                    <svg>
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                </div>

            </button>
        </div>
        <div class="modal" id="uploadmedia">
            <div class="modal-content">
                <h1 class="modal-content-title">
                    {{ __('How you will upload?') }}
                </h1>
                <input id="imgUpload" accept="image/*,video/*" type="file" multiple wire:model="medias"
                    style="display: none">
                <label class="modal-content-btn edit" style="color: black" for="imgUpload">
                    Local
                </label>
                <input class="modal-content-btn" wire:click="external" type="button" value="External">

                <span class="modal-content-btn delete"
                    onclick="document.getElementById('uploadmedia').style.display='none'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24"
                        fill="none" stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18">
                        </line>
                        <line x1="6" y1="6" x2="18" y2="18">
                        </line>
                    </svg>
                </span>
            </div>
        </div>
        @if ($showmedia)
            <div class="accordion__content" id="contentDiv">

                @if ($medias)
                    <form wire:submit.prevent="save">
                        <div class="modal" id="modalelements" style="display: block">
                            <div class="modal-content modal--tabel">
                                <div class="panel__header">
                                    <h1 class="panel__header--title">
                                        {{ __('Add local media') }}
                                    </h1>
                                    <input type="submit" class="panel__header--input" value="Save Media">

                                </div>

                                <div class="table-scroll wid-10">

                                    <table class="table">
                                        <thead>
                                            <tr>

                                                <th>
                                                    <div class="table__header--btn">Media</div>
                                                </th>
                                                <th>
                                                    <div class="table__header--btn">Name</div>
                                                </th>
                                                <th>
                                                    <div class="table__header--btn">Size</div>
                                                </th>
                                                <th>
                                                    <div class="table__header--btn">Type</div>
                                                </th>
                                                <th>
                                                    <div class="table__header--btn">Sequence</div>
                                                </th>
                                                <th>
                                                    <div class="table__header--btn">Location</div>
                                                </th>
                                                <th>
                                                    <div class="table__header--btn float-r">Action</div>
                                                </th>

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
                                                    <td>
                                                        <div class="table__buttons"><button class="edit"
                                                                wire:click.prevent="removemedia({{ $loop->index }})">
                                                                <svg>
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path
                                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <span class="top-up-modal delete"
                                    style="position: fixed; right: 0; top: 0; width: 2.5rem; height: 2.5rem;"
                                    wire:click="cancel">
                                    <svg>
                                        <line x1="18" y1="6" x2="6" y2="18">
                                        </line>
                                        <line x1="6" y1="6" x2="18" y2="18">
                                        </line>
                                    </svg>
                                </span>
                            </div>
                            <a href="#cancelbutton" class="top-up-modal" id="topUp">
                                <svg>
                                    <polyline points="18 15 12 9 6 15"></polyline>
                                </svg>
                            </a>
                        </div>
                    </form>
                @endif
                @if ($externalmedia)
                    <form wire:submit.prevent="saveexternal">
                        <div class="modal" id="modalelements" style="display: block">
                            <div class="modal-content modal--tabel">
                                <div class="item" style="width: 100%">
                                    <p id="top1"></p>
                                    <input type="submit" class="item__form-btn" value="Save Media">
                                    <table class="table-external">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Link</th>
                                                <th>Sequence</th>
                                                <th>Location</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 1; $i <= $row; $i++)
                                                <tr>
                                                    <td>
                                                        <input required type="text"
                                                            wire:model="file_name.{{ $i }}">
                                                    </td>
                                                    <td>
                                                        <input required type="url"
                                                            wire:model="file_link.{{ $i }}">
                                                    </td>
                                                    <td>
                                                        <input required type="number" min="0"
                                                            wire:model="file_sequences.{{ $i }}">

                                                    </td>
                                                    <td>
                                                        <select required
                                                            wire:model="file_locations.{{ $i }}">
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
                                                    <td>
                                                        <div class="display-f jus-c">
                                                            @if ($i == $row)
                                                                <button type="button" class="edit"
                                                                    wire:click="plus">
                                                                    <svg>
                                                                        <line x1="12" y1="5"
                                                                            x2="12" y2="19">
                                                                        </line>
                                                                        <line x1="5" y1="12"
                                                                            x2="19" y2="12">
                                                                        </line>
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                            @if ($i != $row)
                                                                <button type="button" class="save"
                                                                    wire:click="clear({{ $i }})">
                                                                    <svg>
                                                                        <line x1="18" y1="6"
                                                                            x2="6" y2="18">
                                                                        </line>
                                                                        <line x1="6" y1="6"
                                                                            x2="18" y2="18">
                                                                        </line>
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <span class="top-up-modal delete" style="right: 5%" wire:click="clearall">

                                <svg>
                                    <line x1="18" y1="6" x2="6" y2="18">
                                    </line>
                                    <line x1="6" y1="6" x2="18" y2="18">
                                    </line>
                                </svg>
                            </span>
                            <a href="#top1" class="top-up-modal" id="topUp">
                                <svg>
                                    <polyline points="18 15 12 9 6 15"></polyline>
                                </svg>
                            </a>
                        </div>
                    </form>
                @endif
                <div>
                    @if ($category->media()->count() > 0)
                        <div class="panel__header">
                            <input class="panel__header--input" type="text" wire:model.debounce.200ms="search"
                                placeholder="Search..." style="grid-column: 1/4">
                            <div class="panel__header--bundle">
                                <div class="dropdown">
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
                                                    wire:click="confirmItemsRemoval()">
                                                    Delete
                                                </button>
                                                <button class="dropdown-item submit" type="button"
                                                    wire:click="exportSelected()">
                                                    Export
                                                </button>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @if ($selectPage)
                                @if ($selectAll)
                                    <div class="panel__header--checked">
                                        <p>
                                            You selected <strong>{{ count($checked) }}</strong> items.
                                        </p>
                                    </div>
                                @else
                                    <div class="panel__header--checked" wire:click="selectAll">
                                        <p>
                                            You selected {{ count($checked) }} items, select all?
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>
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
                                @foreach ($category->media as $index => $file)
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
                                                    <img src="{{ $file->path }}" width="100" alt="">
                                                @endif
                                            </td>
                                        @endif
                                        @if ($this->showColumn('Name'))
                                            <td data-title="Name">
                                                @if ($editedMediaIndex !== $index)
                                                    <div class="cursor-p"
                                                        wire:click.prevent="editMedia({{ $index }})">
                                                        {{ $file->name }}</div>
                                                @else
                                                    <input type="text" required class="table-edit wid-6"
                                                        wire:model.defer="filess.{{ $index }}.name"
                                                        value="{{ $file->name }}">
                                                    @if ($errors->has('filess.' . $index . '.name'))
                                                        <p>{{ $errors->first('filess.' . $index . '.name') }}
                                                        </p>
                                                    @endif
                                                @endif
                                            </td>
                                        @endif

                                        @if ($this->showColumn('Media Location'))
                                            <td data-title="Media Location">
                                                @if ($editedMediaIndex !== $index)
                                                    <div class="cursor-p"
                                                        wire:click.prevent="editMedia({{ $index }})">
                                                        {{ $file->location->location }}</div>
                                                @else
                                                    <select required class="table-edit"
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
                                                        class="table-edit wid-1"
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
                                                    wire:click.prevent="confirmItemRemoval({{ $file->id }})">
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
                                                            y2="18">
                                                        </line>
                                                        <line x1="6" y1="6" x2="18"
                                                            y2="18">
                                                        </line>
                                                    </svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <div>{{ $files->links('pagination-links') }} </div> --}}
                    @else
                        <p class="mt-2">No media related</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
