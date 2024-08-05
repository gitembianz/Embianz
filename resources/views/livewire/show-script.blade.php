<section class="content">
  {{-- X-Components --}}
  <x-alert />


  {{-- Delete Record --}}
  <aside>
    <div class="background background--center @if($delete) active @endif"></div>
    <div class="aside aside--confirm @if($delete) active @endif">
      <span>
          Are you sure to delete this record?
      </span>
      <button class="button button--primary button--long" wire:click.prevent="deleteSingleRecord()">
        <span>Delete</span>
      </button>
      <button class="button button--danger button--long" wire:click.prevent="cancelItemRemoval()">
        <span>Cancel</span>
      </button>
    </div>
  </aside>


  {{-- Navigation --}}
  <nav class="nav--controls">
    <h1 class="table--name">Custom Code: {{ $script->name }}</h1>
    {{-- Refresh Button --}}
    <a class="button button--primary button--centered" tooltip="Back to Custom scripts" tooltip-top href="{{ route('customscripts') }}">
      <svg><polyline points="15 18 9 12 15 6"></polyline></svg>
    </a>
    <a class="button button--primary button--centered" tooltip="Create new script" tooltip-top href="{{ route('new_script') }}">
      <svg><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
    </a>
    @if ($edititem === null)
      <button class="button button--primary button--centered" tooltip="Edit this script" tooltip-left wire:click.prevent="edititem()">
        <svg><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
      </button>
    @else
      <button class="button button--primary button--centered" tooltip="Save Edit" tooltip-left wire:click.prevent="saveitem()">
        <svg><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
      </button>
      <button class="button button--primary button--centered" tooltip="Cancel edit" tooltip-left wire:click.prevent="cancelitem()">
        <svg><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" /><path d="M10 12l4 5" /><path d="M10 17l4 -5" /></svg>
      </button>
    @endif
    <button class="button button--primary button--centered" tooltip="Delete this script" tooltip-left wire:click.prevent="confirmItemRemoval({{ $script->id }})">
      <svg><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 14l6 0" /></svg>
    </button>
  </nav>


  {{-- Tabs Body (Details) --}}
  <form style="height: calc(100% - 70px);" class="tabs__content details__view active">
    {{-- Script Name --}}
    <div class="input__tabs">
      @if ($edititem === null)
      <span class="disabled">{{ $script->name }}</span>
      @else
      <input type="text" placeholder=" " wire:model.defer="record.name" required>
      @endif
      <label>Name</label>
    </div>

    {{-- Script Type --}}
    <div class="details__checkboxes">
      <div class="input__tabs">
      @if ($edititem === null)
        <span class="disabled">{{ $script->type }}</span>
      @else
        <select wire:model.defer="record.type">
          <option value="head-top">head-top</option>
          <option value="head-bottom">head-bottom</option>
          <option value="body-top">body-top</option>
          <option value="body-bottom">body-bottom</option>
        </select>
        @endif
        <label>Code Position</label>
      </div>
    </div>

    {{-- Script Active --}}
    <div class="details__checkboxes">
      {{-- Script Active --}}
      <div class="checkbox__details">
        @if ($edititem === null)
          @if ($script->active)
            <input type="checkbox" id="active1" checked class="disabled" disabled />
            <label for="active1" class="disabled">Active</label>
          @else
            <input type="checkbox" id="active2" class="disabled" disabled />
            <label for="active2" class="disabled">Active</label>
          @endif
        @else
          <input type="checkbox" id="active3" wire:model.defer="record.active" />
          <label for="active3">Active</label>
        @endif
      </div>
    </div>


    {{-- Script Content --}}
    <div class="textarea__tabs details__long">
      @if ($edititem === null)
        <span class="disabled">{{ $script->content }}</span>
      @else
        <textarea wire:model.defer="record.content" placeholder=" " required></textarea>
      @endif
      <label>Content</label>
    </div>

    {{-- Script Create date / time --}}
    <div class="input__tabs">
      <span class="disabled">{{ $script->created_at }}</span>
      <label>Create date / time</label>
    </div>

    {{-- Script Updated date / time --}}
    <div class="input__tabs">
      <span class="disabled">{{ $script->updated_at }}</span>
      <label>Updated date / time</label>
    </div>

    {{-- Save Button --}}
    @if ($edititem != null)
      <button class="button button--fill button--secondary details__long" wire:click.prevent="saveitem()" value="Save">
        Save
      </button>
    @endif
  </form>
</section>
