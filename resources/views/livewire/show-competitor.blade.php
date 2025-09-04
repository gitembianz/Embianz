<section class="content">
    {{-- X-Components --}}
    <x-alert />

    {{-- Delete Record --}}
    <aside>
        <div class="background background--center @if ($delete) active @endif"></div>
        <div class="aside aside--confirm @if ($delete) active @endif">
            <span>
                Are you sure to delete this record?
            </span>
            <button class="button button--primary button--long" wire:click.prevent="deleteRecord()">
                <span>Delete</span>
            </button>
            <button class="button button--danger button--long" wire:click.prevent="cancelItemRemoval()">
                <span>Cancel</span>
            </button>
        </div>
    </aside>

    {{-- Navigation --}}
    <nav class="nav--controls">
        <h1 class="table--name">Competitor: {{ $competitor->name }}</h1>
        {{-- Refresh Button --}}
        <a class="button button--primary button--centered" tooltip="Back to all competitors" tooltip-top
            href="{{ route('competitors') }}">
            <svg>
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        <a class="button button--primary button--centered" tooltip="Create new competitor" tooltip-top
            href="{{ route('newcompetitor') }}">
            <svg>
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="12" y1="18" x2="12" y2="12"></line>
                <line x1="9" y1="15" x2="15" y2="15"></line>
            </svg>
        </a>
        @if ($edititem === null)
            <button class="button button--primary button--centered" tooltip="Edit this competitor" tooltip-left
                wire:click.prevent="edit()">
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                    <path d="M16 5l3 3" />
                </svg>
            </button>
        @else
            <button class="button button--primary button--centered" tooltip="Save Edit" tooltip-left
                wire:click.prevent="save()">
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    <path d="M9 15l2 2l4 -4" />
                </svg>
            </button>
            <button class="button button--primary button--centered" tooltip="Cancel edit" tooltip-left
                wire:click.prevent="cancel()">
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
                    <path d="M10 12l4 5" />
                    <path d="M10 17l4 -5" />
                </svg>
            </button>
        @endif
        <button class="button button--primary button--centered" tooltip="Delete this article" tooltip-left
            wire:click.prevent="confirmRemoval({{ $competitor->id }})">
            <svg>
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M9 14l6 0" />
            </svg>
        </button>
    </nav>

    {{-- Tabs Header --}}
    <nav class="nav--tabs">
        <button class="button button--primary button--long button--active" id="detailsButton">
            Details
        </button>
        <button class="button button--primary button--long" id="relatedButton">
            Related
        </button>
    </nav>

    {{-- Tabs Body (Details) --}}
    <form style="height: calc(100% - 107.5px);" class="tabs__content details__view active" id="detailsContent">
        <div class="input__tabs">
            @if ($edititem === null)
                <span class="disabled">{{ $competitor->name }}</span>
            @else
                <input type="text" placeholder=" " name="article__name" wire:model.defer="record.name" required>
            @endif
            <label for="article__name">Name</label>
        </div>


        <div class="textarea__tabs details__long">
            @if ($edititem === null)
                <span class="disabled">{{ $competitor->url }}</span>
            @else
                <textarea type="text" placeholder=" " name="url" wire:model.defer="record.url"></textarea>
            @endif
            <label for="article__name">Url</label>
        </div>

        <div class="input__tabs">
            <span class="disabled">{{ $competitor->created_at }}</span>
            <label>Create date / time</label>
        </div>

        <div class="input__tabs">
            <span class="disabled">{{ $competitor->created_by }}</span>
            <label>Created By</label>
        </div>

        <div class="input__tabs">
            <span class="disabled">{{ $competitor->updated_at }}</span>
            <label>Updated At</label>
        </div>

        <div class="input__tabs">
            <span class="disabled">{{ $competitor->last_modified_by }}</span>
            <label>Last modified by</label>
        </div>

        @if ($edititem != null)
            <button class="button button--fill button--secondary details__long" wire:click.prevent="save()"
                value="Save">
                Save
            </button>
        @endif
    </form>


    {{-- Tabs Body (Related) --}}
    <div style="height: calc(100% - 107.5px);" class="tabs__content related__view" id="relatedContent">
        @livewire('competitor-products', ['competitor' => $competitor, 'tableName' => 'competitor_products'], key($competitor->id))

    </div>
</section>
