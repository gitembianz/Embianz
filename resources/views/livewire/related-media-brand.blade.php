<div class="accordion @if ($showmedia) active @endif">
 {{-- Accordion Header --}}
 <div class="accordion__header">
  <button
   class="button button--flexed button--fill button--primary @if ($showmedia) button--secondary active @endif"
   wire:click.prevent="@if ($showmedia === false) $set('showmedia', true) @else $set('showmedia', false) @endif">
   {{ __('Media ') }}({{ $brand->media->count() }})
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
  <div class="background background--center @if ($delete) active @endif"></div>
  <div class="aside aside--confirm @if ($delete) active @endif">
   <span>
    Are you sure to delete this record?
   </span>
   <button class="button button--primary button--long" wire:click="deleteSingleRecord">
    <span>Delete</span>
   </button>
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
    How you will upload the media?
   </span>
   <input style="display: none;" id="localMedia" wire:model="medias" type="file"
    accept="image/png, image/jpeg, image/jpg, image/webp">

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

 {{-- Table add local Media --}}
 <aside>
  <div class="background background--center @if ($medias) active @endif"></div>
  <form class="aside aside--table @if ($medias) active @endif" wire:submit.prevent="savelocal">
   {{-- Navigation --}}
   <nav class="nav--controls">
    <h1 class="table--name">
     {{ __('Add local media') }}
    </h1>
    <button class="button button--primary button--centered" value="submit" type="submit">
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
      <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
      <path d="M14 4l0 4l-6 0l0 -4" />
     </svg>
    </button>
    <button class="button button--danger button--centered" wire:click="cancel()">
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M15 19v-2a2 2 0 0 1 2 -2h2" />
      <path d="M15 5v2a2 2 0 0 0 2 2h2" />
      <path d="M5 15h2a2 2 0 0 1 2 2v2" />
      <path d="M5 9h2a2 2 0 0 0 2 -2v-2" />
     </svg>
    </button>
   </nav>

   {{-- Table --}}
   <div class="table" style="height: calc(100% - 60px);">
    <table class="expandable-table">
     <thead>
      <tr>
       <th>
        <div class="table--btn">Media</div>
       </th>
       <th class="hidden">
        <div class="table--btn">Name</div>
       </th>
       <th class="hidden">
        <div class="table--btn">Size</div>
       </th>
       <th class="hidden">
        <div class="table--btn">Extension Type</div>
       </th>
       <th>
        <button class="button button--secondary button--sm" style="opacity: 0">
         <svg>
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
         </svg>
        </button>
       </th>
      </tr>
     </thead>
     <tbody>
      @php
       $k = 0;
      @endphp
      @foreach ($medias as $index => $media)
       <tr class="expandable-row">
        <td wire:click="expandRow3({{ $index }})" style="width: auto;">
         @if (str_starts_with($media->getMimeType(), 'image'))
          <img loading="eager" src="data:{{ $media->getMimeType() }};base64,{{ base64_encode($media->get()) }}"
           width="50px">
         @elseif (str_starts_with($media->getMimeType(), 'video'))
          <video width="100px" controls>
           <source src="data:{{ $media->getMimeType() }};base64,{{ base64_encode($media->get()) }}"
            type="{{ $media->getMimeType() }}">
           <span>{{ __('Your browser does not support the video tag') }}</span>
          </video>
         @endif
        </td>
        <td class="hidden">{{ $media->getClientOriginalName() }}</td>
        <td class="hidden" wire:click="expandRow3({{ $index }})">{{ $media->getSize() }} KB</td>
        <td class="hidden" wire:click="expandRow3({{ $index }})">{{ $media->getClientOriginalExtension() }}
        </td>
        <td>
         <button class="button button--secondary button--sm" wire:click.prevent="cancel()">
          <svg>
           <polyline points="3 6 5 6 21 6"></polyline>
           <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
           </path>
          </svg>
         </button>
        </td>
       </tr>
       <tr class="details-row @if ($rind3 === $k) active @endif">
        <td colspan="3">
         <div class="details">
          <p>
           <bold>Media Name</bold>
           {{ $media->getClientOriginalName() }}
          </p>
          <p>
           <bold>Size</bold>
           {{ $media->getSize() }} KB
          </p>
          <p>
           <bold>Extension Type</bold>
           {{ $media->getClientOriginalExtension() }}
          </p>
         </div>
        </td>
       </tr>
       @php
        $k++;
       @endphp
      @endforeach
     </tbody>
    </table>
   </div>
  </form>
 </aside>


 {{-- Table add external Media --}}
 <aside>
  <div class="background background--center @if ($externalmedia) active @endif"></div>
  <form class="aside aside--table @if ($externalmedia) active @endif">
   {{-- Navigation --}}
   <nav class="nav--controls">
    <h1 class="table--name">
     {{ __('Add external media') }}
    </h1>
    <button class="button button--primary button--centered" wire:click.prevent="saveexternal">
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
      <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
      <path d="M14 4l0 4l-6 0l0 -4" />
     </svg>
    </button>
    <button class="button button--danger button--centered" wire:click="clearall()">
     <svg>
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M15 19v-2a2 2 0 0 1 2 -2h2" />
      <path d="M15 5v2a2 2 0 0 0 2 2h2" />
      <path d="M5 15h2a2 2 0 0 1 2 2v2" />
      <path d="M5 9h2a2 2 0 0 0 2 -2v-2" />
     </svg>
    </button>
   </nav>


   {{-- Table --}}
   <div class="table" style="height: calc(100% - 60px);">
    <table class="expandable-table">
     <thead>
      <tr>
       <th style="width: auto !important;">
        <div class="table--btn">Name</div>
       </th>
       <th class="hidden">
        <div class="table--btn">Link</div>
       </th>
       <th>
        <button class="button button--secondary button--sm" style="opacity: 0">
         <svg>
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
         </svg>
        </button>
       </th>
      </tr>
     </thead>
     <tbody>
      @php
       $j = 0;
      @endphp
      @for ($i = 0; $i <= $row; $i++)
       <tr class="expandable-row">
        <td style="width: auto !important;" wire:click="expandRow2({{ $i }})" class="unhidden">
         {{ $i }}</td>
        <td style="width: auto !important;" style="width: auto;">
         <div class="searchable">
          <input placeholder="Media name" type="text" class="input__searchable"
           wire:model="file_name.{{ $i }}">
         </div>
        </td>
        <td class="hidden">
         <div class="searchable">
          <input placeholder="Media external link" type="url" class="input__searchable"
           wire:model="file_link.{{ $i }}">
         </div>
        </td>
        <td>
         <button type="button" class="button button--secondary button--sm" wire:click="clearall()">
          <svg>
           <line x1="18" y1="6" x2="6" y2="18">
           </line>
           <line x1="6" y1="6" x2="18" y2="18">
           </line>
          </svg>
         </button>
        </td>
       </tr>
       <tr class="details-row @if ($rind2 === $j) active @endif">
        <td colspan="3">
         <div class="details">
          <p>
           <bold>Link</bold>
          <div class="searchable">
           <input placeholder="Media external link" type="url" class="input__searchable"
            wire:model="file_link.{{ $i }}">
          </div>
          </p>
         </div>
        </td>
       </tr>
       @php
        $j++;
       @endphp
      @endfor
     </tbody>
    </table>
   </div>
  </form>
 </aside>




 {{-- Table --}}
 <div class="accordion__body">
  {{-- Table --}}
  <div class="table">
   <table class="expandable-table">
    <thead>
     <tr>
      <th>
       <button class="table--btn">
        Media
       </button>
      </th>
      @foreach ($selectedColumns as $index => $column)
       @php
        if ($column === 'id' || $column === 'sequence') {
            continue;
        }
       @endphp
       @if ($this->showColumn($column))
        <th @if ($index > 1) class="hidden" @endif>
         <button class="table--btn">
          {{ $column }}
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
       <td class="table--empty" colspan="{{ count($selectedColumns) + 2 }}">No record found.</td>
      </tr>
     @else
      @foreach ($filteredMedia as $index => $file)
       <tr class="expandable-row">
        <td wire:click="expandRow({{ $index }})">
         @if (in_array($file->extension, ['jpg', 'jpeg', 'png', 'gif', 'jfif', 'webp']))
          <img loading="eager" src="/{{ $file->path . $file->name }}" alt="{{ $file->name }}" width="50">
         @else
          A problem with media
         @endif
        </td>
        @foreach ($selectedColumns as $numar => $column)
         @php
          if ($column === 'id' || $column === 'sequence') {
              continue;
          }
         @endphp
         <td wire:click="expandRow({{ $index }})" @if ($numar > 1) class="hidden" @endif
          data-title="{{ $column }}">
          @if ($column === 'name')
           @if ($editedMediaIndex !== $index)
            {{ $file->$column }}
           @else
            <div class="searchable">
             <input type="text" class="input__searchable" wire:model.defer="filess.{{ $index }}.name"
              value="{{ $file->name }}">
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
             <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
           </button>
          @else
           <button class="button button--secondary button--sm"
            wire:click.prevent="saveMedia({{ $index }} , {{ $file->id }})">
            <svg>
             <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
           </button>
           <button class="button button--secondary button--sm" wire:click.prevent="cancelMedia()">
            <svg>
             <line x1="18" y1="6" x2="6" y2="18"></line>
             <line x1="6" y1="6" x2="18" y2="18"></line>
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
            if ($column === 'id' || $column === 'sequence') {
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
               <input type="text" class="input__searchable" wire:model.defer="filess.{{ $index }}.name"
                value="{{ $file->name }}">
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
