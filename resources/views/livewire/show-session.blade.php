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
  <h1 class="table--name">Session: {{ $session->sessions }}</h1>
  {{-- Refresh Button --}}
  <a class="button button--primary button--centered" tooltip="Back to all sessions" tooltip-top
   href="{{ route('sessions') }}">
   <svg>
    <polyline points="15 18 9 12 15 6"></polyline>
   </svg>
  </a>
  <button class="button button--primary button--centered" tooltip="Delete this session" tooltip-left
   wire:click.prevent="confirmItemRemoval({{ $session->id }})">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
    <path d="M9 14l6 0" />
   </svg>
  </button>
 </nav>

 <nav class="nav--tabs">
  <button class="button button--primary button--long button--active" id="detailsButton">
   Details
  </button>
  <button class="button button--primary button--long" id="relatedButton">
   Related
  </button>
 </nav>

 <form style="height: calc(100% - 107.5px);" class="tabs__content details__view active" id="detailsContent">
  <div class="input__tabs">
   <span class="disabled">{{ $session->sessions }}</span>
   <label>Session</label>
  </div>

  <div class="input__tabs">
   <span class="disabled">{{ $session->ip_address }}</span>
   <label>Ip Adress</label>
  </div>

  <div class="input__tabs details__long">
   <span class="disabled">{{ $session->user_agent }}</span>
   <label>User Agent</label>
  </div>

  <div class="input__tabs">
   <span class="disabled">{{ $session->promotion_cookiedid }}</span>
   <label>Promotion CookieID</label>
  </div>

  <div class="input__tabs">
   <span class="disabled">{{ $session->promotion_start_date }}</span>
   <label>Promotion Start Date</label>
  </div>

  <div class="input__tabs">
   <span class="disabled">{{ $session->promotion_expiration_date }}</span>
   <label>Promotion Expiration Date</label>
  </div>
  <div class="input__tabs">
   <span class="disabled">{{ $session->promotion_cooldown_timer }}</span>
   <label>Promotion Cooldown Timer</label>
  </div>

  <div class="input__tabs">
   <span class="disabled">{{ $session->last_activity }}</span>
   <label>Last Activity</label>
  </div>

  <div class="input__tabs">
   <span class="disabled">{{ $session->created_at }}</span>
   <label>Create date / time</label>
  </div>
  <div class="input__tabs">
   <span class="disabled">{{ $session->updated_at }}</span>
   <label>Updated At</label>
  </div>
 </form>


 <div style="height: calc(100% - 107.5px);" class="tabs__content related__view" id="relatedContent">
  @livewire('related-session', ['relatedby' => 'sessions', 'session_id' => $session->sessions], key(1))
  @livewire('related-session', ['relatedby' => 'carts', 'session_id' => $session->sessions], key(2))
  @livewire('related-session', ['relatedby' => 'orders', 'session_id' => $session->sessions], key(3))
  @livewire('related-session', ['relatedby' => 'wishlist', 'session_id' => $session->sessions], key(4))
  @livewire('related-session', ['relatedby' => 'user_promotions', 'session_id' => $session->sessions], key(5))
 </div>
</section>
