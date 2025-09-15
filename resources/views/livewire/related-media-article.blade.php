<div class="accordion @if ($showmedia) active @endif">
    {{-- Accordion Header --}}
    <div class="accordion__header">
        <button
            class="button button--flexed button--fill button--primary @if ($showmedia) button--secondary active @endif"
            wire:click.prevent="@if ($showmedia === false) $set('showmedia', true) @else $set('showmedia', false) @endif">
            {{ __('Media ') }}({{ $article->media->count() }})
            <svg>
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
        <button class="button button--secondary" wire:click.prevent="uploadmedia()">
            <svg>
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
        </button>
    </div>

    {{-- Delete Record || Delete Records --}}
    <aside>
        <div class="background background--center @if ($single || $multiple) active @endif"></div>
        <div class="aside aside--confirm @if ($single || $multiple) active @endif">
            <span>
                @if ($single)
                    Are you sure to delete this record?
                @else
                    Are you sure to delete those records?
                @endif
            </span>
            @if ($single)
                <button class="button button--primary button--long" wire:click="deleteSingleRecord">
                    <span>Delete</span>
                </button>
            @else
                <button class="button button--primary button--long" wire:click="deleteRecords()">
                    <span>Delete</span>
                </button>
            @endif
            <button class="button button--danger button--long" wire:click="cancel_delete()">
                <span>Cancel</span>
            </button>
        </div>
    </aside>

    {{-- Local Upload || External Upload --}}
    <aside>
        <div class="background background--center @if ($chose == true) active @endif"></div>
        <div class="aside aside--confirm @if ($chose == true) active @endif">
            <span>
                How you will upload the media for product?
            </span>
            <input style="display: none;" id="localMedia" wire:model="media" type="file" accept="image/*,video/*"
                multiple>
            <label class="button button--primary button--long" type="button" for="localMedia">
                <span>
                    Local
                </span>
            </label>
            <button class="button button--primary button--long" wire:click="external">
                <span>
                    External
                </span>
            </button>
            <button class="button button--danger button--long" wire:click="cancel_chose()">
                <span>
                    Close
                </span>
            </button>
        </div>
    </aside>



    {{-- Logo asside --}}
    <aside>
        <div class="background background--center @if ($chose || $media || $externalmedia) active @endif"></div>
        <div style="min-height: 160px" class="aside aside--confirm @if ($chose || $media || $externalmedia) active @endif">
            @if (!$externalmedia && !$media)
                <span style="margin: 0.5rem 0; width: 100%; text-align: center">How you will upload?</span>
                <input style="display: none" id="localMedia" wire:model="media" type="file" accept="image/*">
                <label class="button button--primary button--long" type="button" for="localMedia">
                    <span>Local Pick</span>
                </label>
                <button class="button button--primary button--long" wire:click="$set('externalmedia', true)">
                    <span>External Pick</span>
                </button>
                <button class="button button--danger button--long" wire:click="closeModalLogo()">
                    <span>Close</span>
                </button>
            @elseif($externalmedia)
                <div
                    style="display: flex; align-items: center; flex-direction:column; justify-content: space-between; width: 100%;">
                    <span style="margin: 0.5rem 0; width: 100%; text-align: center; color: white">
                            Article media
                    </span>
                    <div class="input__tabs details__long">
                        <input type="url" wire:model.defer="file_link">
                        <label>Insert the url</label>
                    </div>
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top: 1rem; gap:1rem;">
                        <button style="width: 100%" class="button button--secondary button--long"
                            wire:click="saveexternal()">
                            <span>Update</span>
                        </button>
                        <button style="width: 100%" class="button button--danger button--long"
                            wire:click="$set('externalmedia', false)">
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>
            @endif
            @if ($media)
                <div
                    style="display: flex; align-items: center; flex-direction:column; justify-content: space-between; width: 100%;">
                    <span style="margin: 0.5rem 0; width: 100%; text-align: center">
                        Article media
                    </span>
                    <div class="logo_container" style="margin: auto">
                        <img loading="eager"
                                            src="data:{{ $media[0]->getMimeType() }};base64,{{ base64_encode($media[0]->get()) }}"
                                            width="50px">
                    </div>
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top: 1rem; gap:1rem;">
                        <button style="width: 100%" class="button button--secondary button--long"
                            wire:click="save()">
                            <span>Update</span>
                        </button>
                        <button style="width: 100%" class="button button--danger button--long"
                            wire:click="$set('media', null)">
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </aside>


    {{-- Table --}}
    <div class="accordion__body">
        {{-- Navigation --}}
        <nav class="nav--controls">
            {{-- Search Input --}}
            <input class="input input--long" type="text" wire:model.debounce.300ms="search"
                placeholder="Search...">
            {{-- IF CHECKED --}}
            <div class="dropdown dropdown--right" @if (!$checked) style="display:none;" @endif>
                {{-- Dropdown Button --}}
                <button class="button button--primary button--centered button--long dropdown__button"
                    tooltip="Actions with checked" tooltip-top>
                    <span>With Checked({{ count($checked) }})</span>
                </button>
                {{-- Dropdown Content --}}
                <div class="dropdown__content">
                    <div class="dropdown__container">
                        <button class="button button--primary button--long" wire:click="confirmItemsRemoval()">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            {{-- Visible Dropdown --}}
            <div class="dropdown dropdown--right" wire:ignore>
                {{-- Dropdown Button --}}
                <button class="button button--primary button--centered" tooltip="Show items in table" tooltip-left>
                    <svg>
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                    </svg>
                </button>
                {{-- Dropdown Content --}}
                <div class="dropdown__content">
                    <div class="dropdown__container">
                        @foreach ($columns as $column)
                            <label class="switch switch--primary inline">
                                <input type="checkbox" wire:model="selectedColumns" value="{{ $column }}"
                                    {{ in_array($column, $selectedColumns) ? 'checked' : '' }} />
                                <span>{{ $column }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </nav>

        {{-- Table --}}
        <div class="table">
            <table class="expandable-table">
                <thead>
                    <tr>
                        <th>
                            <div class="checkbox--primary">
                                <input type="checkbox" id="selectPage13" wire:model="selectPage" />
                                <label for="selectPage13"></label>
                            </div>
                        </th>
                        <th>
                            <button class="table--btn">
                                Media
                            </button>
                        </th>
                        @foreach ($selectedColumns as $index => $column)
                            @if ($this->showColumn($column))
                                <th @if ($index > count($selectedColumns) - 17 && $column != 'id') class="hidden" @endif>
                                    <button wire:click="sortBy('{{ $column }}')"
                                        class="table--btn @if ($orderBy === $column && $orderAsc === '1') active @endif">
                                        {{ $column }}
                                        <svg>
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </button>
                                </th>
                            @endif
                        @endforeach
                        <th>
                            <div style="display: flex;">
                                <button class="button button--secondary button--sm" style="opacity: 0;">
                                    <svg>
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </button>
                                <button class="button button--secondary button--sm" style="opacity: 0;">
                                    <svg>
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @if ($filteredMedia->isEmpty())
                        <tr>
                            <td class="table--empty" colspan="{{ count($selectedColumns) + 3 }}">No record found.
                            </td>
                        </tr>
                    @else
                        @foreach ($filteredMedia as $index => $file)
                            <tr @if ($loop->last) id="last_record" @endif
                                class="expandable-row @if ($this->isChecked($file->id)) active @endif">
                                <td>
                                    <div class="checkbox--primary">
                                        <input type="checkbox" value="{{ $file->id }}" id="{{ $file->id }}"
                                            wire:model="checked" />
                                        <label for="{{ $file->id }}"></label>
                                    </div>
                                </td>
                                <td wire:click="expandRow({{ $index }})">
                                    @if (in_array($file->extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'jfif', 'webp']))
                                        <img loading="eager" src="/{{ $file->path . $file->name }}"
                                            alt="{{ $file->name }}" width="50">
                                    @else
                                        A problem with media
                                    @endif
                                </td>
                                @foreach ($selectedColumns as $column)
                                    <td @if ($column != 'id') class="hidden" @endif
                                        data-title="{{ $column }}">
                                        @if ($column === 'name')
                                            @if ($editedMediaIndex !== $index)
                                                {{ $file->$column }}
                                            @else
                                                <div class="searchable">
                                                    <input type="text" class="input__searchable"
                                                        wire:model.defer="filess.{{ $index }}.name"
                                                        value="{{ $file->name }}">
                                                </div>
                                            @endif
                                        @elseif ($column === 'type')
                                            @if ($editedMediaIndex !== $index)
                                                {{ $file->$column }}
                                            @else
                                                <div class="searchable">
                                                    <select wire:model.defer="filess.{{ $index }}.type"
                                                        class="input__searchable">
                                                        <option value="orginal">orginal</option>
                                                        <option value="min">min</option>
                                                        <option value="main">main</option>
                                                        <option value="full">full</option>
                                                    </select>
                                                </div>
                                            @endif
                                        @else
                                            {{ $file->$column }}
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <div style="display: flex;">
                                        @if ($editedMediaIndex !== $index)
                                            <button class="button button--secondary button--sm"
                                                wire:click.prevent="editMedia({{ $index }}, {{ $file->id }})">
                                                <svg>
                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button class="button button--secondary button--sm"
                                                wire:click.prevent="confirmItemRemoval({{ $file->id }})">
                                                <svg>
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                    </path>
                                                </svg>
                                            </button>
                                        @else
                                            <button class="button button--secondary button--sm"
                                                wire:click.prevent="saveMedia({{ $index }} , {{ $file->id }})">
                                                <svg>
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </button>
                                            <button class="button button--secondary button--sm"
                                                wire:click.prevent="cancelMedia()">
                                                <svg>
                                                    <line x1="18" y1="6" x2="6"
                                                        y2="18"></line>
                                                    <line x1="6" y1="6" x2="18"
                                                        y2="18"></line>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr class="details-row  @if ($rind === $i) active @endif">
                                <td colspan="{{ count($selectedColumns) + 2 }}">
                                    <div class="details">
                                        @foreach ($selectedColumns as $column)
                                            @php
                                                if ($column === 'id') {
                                                    continue;
                                                }
                                            @endphp
                                            @if ($column === 'name')
                                                <p>
                                                    <bold>{{ $column }}</bold>
                                                    @if ($editedMediaIndex !== $index)
                                                        {{ $file->name }}
                                                    @else
                                                        <div class="searchable">
                                                            <input type="text" class="input__searchable"
                                                                wire:model.defer="filess.{{ $index }}.name"
                                                                value="{{ $file->name }}">
                                                        </div>
                                                    @endif
                                                </p>
                                            @elseif ($column === 'type')
                                                <p>
                                                    <bold>{{ $column }}</bold>
                                                    @if ($editedMediaIndex !== $index)
                                                        {{ $file->$column }}
                                                    @else
                                                        <div class="searchable">
                                                            <select wire:model.defer="filess.{{ $index }}.type"
                                                                class="input__searchable">
                                                                <option value="orginal">orginal</option>
                                                                <option value="min">min</option>
                                                                <option value="main">main</option>
                                                                <option value="full">full</option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                </p>
                                            @else
                                                <p>
                                                    <bold>{{ $column }}</bold>
                                                    {{ $file->$column }}
                                                </p>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
