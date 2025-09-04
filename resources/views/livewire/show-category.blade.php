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
        <h1 class="table--name">Category: {{ strip_tags($category->name) }}</h1>
        {{-- Refresh Button --}}
        <a class="button button--primary button--centered" tooltip="Back to all categories" tooltip-top
            href="{{ route('category') }}">
            <svg>
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        <button class="button button--primary button--centered display--desktop" tooltip="Generate related products"
            tooltip-top wire:click.prevent="Productrelated()">
            <svg>
                <polyline points="16 3 21 3 21 8"></polyline>
                <line x1="4" y1="20" x2="21" y2="3"></line>
                <polyline points="21 16 21 21 16 21"></polyline>
                <line x1="15" y1="15" x2="21" y2="21"></line>
                <line x1="4" y1="4" x2="9" y2="9"></line>
            </svg>
        </button>
        <a class="button button--primary button--centered" tooltip="Create new category" tooltip-top
            href="{{ route('newcategory') }}">
            <svg>
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="12" y1="18" x2="12" y2="12"></line>
                <line x1="9" y1="15" x2="15" y2="15"></line>
            </svg>
        </a>
        @if ($editcategory === null)
            <button class="button button--primary button--centered" tooltip="Edit this category" tooltip-left
                wire:click.prevent="editcategory()">
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                    <path d="M16 5l3 3" />
                </svg>
            </button>
        @else
            <button class="button button--primary button--centered" tooltip="Save Edit" tooltip-left
                wire:click.prevent="savecategory()">
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    <path d="M9 15l2 2l4 -4" />
                </svg>
            </button>
            <button class="button button--primary button--centered" tooltip="Cancel edit" tooltip-left
                wire:click.prevent="cancelcategory()">
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
                    <path d="M10 12l4 5" />
                    <path d="M10 17l4 -5" />
                </svg>
            </button>
        @endif
        <button class="button button--primary button--centered" tooltip="Delete this category" tooltip-left
            wire:click.prevent="confirmItemRemoval({{ $category->id }})">
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
        {{-- Category Name --}}
        <div class="input__tabs">
            @if ($editcategory === null)
                <span class="disabled">{{ $category->name }}</span>
            @else
                <input type="text" wire:model.defer="cat.name" required>
            @endif
            <label for="category__name">Name</label>
        </div>

        {{-- Category Active && Store Tab --}}
        <div class="details__checkboxes">
            {{-- Category Active --}}
            <div class="checkbox__details ">
                @if ($editcategory === null)
                    @if ($category->active)
                        <input type="checkbox" id="active1" checked class="disabled" disabled />
                        <label for="active1" class="disabled">Active</label>
                    @else
                        <input type="checkbox" id="active2" class="disabled" disabled />
                        <label for="active2" class="disabled">Active</label>
                    @endif
                @else
                    <input type="checkbox" id="active3" wire:model.defer="cat.active" />
                    <label for="active3">Active</label>
                @endif
            </div>
            {{-- Category Store Tab --}}
            <div class="checkbox__details">
                @if ($editcategory === null)
                    @if ($category->store_tab)
                        <input type="checkbox" id="store_tab1" checked class="disabled" disabled />
                        <label for="store_tab1" class="disabled">Show in store</label>
                    @else
                        <input type="checkbox" id="store_tab2" class="disabled" disabled />
                        <label for="store_tab2" class="disabled">Show in store</label>
                    @endif
                @else
                    <input type="checkbox" id="store_tab3" wire:model.defer="cat.visible" />
                    <label for="store_tab3">Show in store</label>
                @endif
            </div>
        </div>
        <div class="details__checkboxes">

            <div class="checkbox__details ">
                @if ($editcategory === null)
                    @if ($category->preload_image)
                        <input type="checkbox" id="preload1" checked class="disabled" disabled />
                        <label for="preload1" class="disabled">Preload image</label>
                    @else
                        <input type="checkbox" id="preload2" class="disabled" disabled />
                        <label for="preload2" class="disabled">Preload image</label>
                    @endif
                @else
                    <input type="checkbox" id="preload3" wire:model.defer="cat.preload" />
                    <label for="preload3">Preload image</label>
                @endif
            </div>
            <div class="checkbox__details ">
                @if ($editcategory === null)
                    @if ($category->display_variant_price)
                        <input type="checkbox" id="varprice1" checked class="disabled" disabled />
                        <label for="varprice1" class="disabled">Display Variant Price</label>
                    @else
                        <input type="checkbox" id="varprice2" class="disabled" disabled />
                        <label for="varprice2" class="disabled">Display Variant Price</label>
                    @endif
                @else
                    <input type="checkbox" id="varprice3" wire:model.defer="cat.varprice" />
                    <label for="varprice3">Display Variant Price</label>
                @endif
            </div>
        </div>

        <div class="checkbox__details ">
            @if ($editcategory === null)
                @if ($category->one_product_page_category)
                    <input type="checkbox" id="oneproduct1" checked class="disabled" disabled />
                    <label for="oneproduct1" class="disabled">Is one product page system category?</label>
                @else
                    <input type="checkbox" id="oneproduct2" class="disabled" disabled />
                    <label for="oneproduct2" class="disabled">Is one product page system category?</label>
                @endif
            @else
                <input type="checkbox" id="oneproduct3" wire:model.defer="cat.oneproduct" />
                <label for="oneproduct3">Is one product page system category?</label>
            @endif
        </div>
        <div class="details__checkboxes">

            {{-- Category Start Date --}}
            <div class="input__tabs">
                @if ($editcategory === null)
                    <span class="disabled">{{ $category->start_date }}</span>
                @else
                    <input type="date" wire:model.defer="cat.start_date" required>
                @endif
                <label for="category__name">Start Date</label>
            </div>

            {{-- Category End Date --}}
            <div class="input__tabs">
                @if ($editcategory === null)
                    <span class="disabled">{{ $category->end_date }}</span>
                @else
                    <input type="date" wire:model.defer="cat.end_date" required>
                @endif
                <label for="category__name">End Date</label>
            </div>
        </div>
        <div class="details__checkboxes">

            {{-- Category Slider Sequence --}}
            <div class="input__tabs">
                @if ($editcategory === null)
                    <span class="disabled">{{ $category->slider_sequence }}</span>
                @else
                    <input type="number" wire:model.defer="cat.slider_sequence" required>
                @endif
                <label for="category__name">Slider Sequence</label>
            </div>

            {{-- Category Sequence --}}
            <div class="input__tabs">
                @if ($editcategory === null)
                    <span class="disabled">{{ $category->sequence }}</span>
                @else
                    <input type="number" wire:model.defer="cat.sequence" required>
                @endif
                <label for="category__name">Sequence</label>
            </div>

        </div>

        {{-- Category Meta Description --}}
        <div class="input__tabs details__long">
            @if ($editcategory === null)
                <span class="disabled">{{ $category->meta_description }}</span>
            @else
                <input type="text" wire:model.defer="cat.meta_description" required>
            @endif
            <label for="category__name">Meta Description</label>
        </div>


        {{-- Category Short Description --}}
        <div class="input__tabs">
            @if ($editcategory === null)
                <span class="disabled">{{ $category->short_description }}</span>
            @else
                <input type="text" wire:model.defer="cat.short_description" required>
            @endif
            <label for="category__name">Short Description</label>
        </div>

        {{-- Category Displayed Items --}}
        <div class="input__tabs">
            @if ($editcategory === null)
                <span class="disabled">{{ $category->accepted_items }}</span>
            @else
                <select wire:model.defer="cat.acc_items">
                    <option value="default">default</option>
                    <option value="parents">parents</option>
                </select>
            @endif
            <label for="category__name">Displayed items</label>
        </div>

        {{-- Category Long Description --}}
<div class="textarea__tabs details__long">
  @if ($editcategory === null)
    <div class="disabled">{!! $category->long_description !!}</div>
  @else
    {{-- Editor host (ignored by Livewire) --}}
    <div id="ck-host" wire:ignore></div>

    {{-- Hidden field for Livewire + form submit (never visible) --}}
    <input
      type="hidden"
      id="long_description_input"
      name="long_description"
      wire:model.defer="cat.long_description"
    />

    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.css">
    <style>
      .ck-content { font: 400 16px/1.6 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif; color:#111; min-height:280px; }
      .ck-content * { box-sizing:border-box; }
      /* wrap toolbar so all buttons remain visible on narrow containers */
      .ck-toolbar .ck-toolbar__items { flex-wrap: wrap; }
    </style>

    <script>
    (function () {
      function mountCkEditor() {
        const host = document.getElementById('ck-host');
        const hiddenInput = document.getElementById('long_description_input');
        if (!host || !hiddenInput || document.getElementById('ckframe')) return;

        const initialHtml = @json(old('long_description', $category->long_description ?? '<p></p>'));

        const srcdoc = `
<!doctype html><html><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.css">
  <style>
    html,body{margin:0;height:100%}
    .ck-content{min-height:280px;line-height:1.6}
    .ck-editor{max-width:100%}
    .ck-toolbar .ck-toolbar__items{flex-wrap:wrap}
  </style>
</head><body>
  <div id="editor">${initialHtml}</div>
  <script src="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.umd.js"><\/script>
  <script>
  (function(){
    var CK = window.CKEDITOR;
    CK.ClassicEditor.create(document.querySelector('#editor'), {
      licenseKey: '{{ app('global_ckeditor_license') }}',

     plugins: [
        // essentials & UX
        CK.Essentials, CK.Paragraph, CK.Autoformat, CK.PasteFromOffice, CK.RemoveFormat,
        CK.FindAndReplace, CK.SelectAll, CK.Clipboard, CK.Undo,
        // text formatting
        CK.Bold, CK.Italic, CK.Underline, CK.Strikethrough, CK.Subscript, CK.Superscript,
        CK.Code, CK.Highlight, CK.Font, CK.Alignment,
        // structure
        CK.Heading, CK.BlockQuote, CK.HorizontalLine, CK.PageBreak,
        // lists
        CK.List, CK.ListProperties, CK.TodoList, CK.Indent, CK.IndentBlock,
        // links & media
        CK.Link, CK.AutoLink, CK.MediaEmbed,
        // images
        CK.Image, CK.ImageCaption, CK.ImageStyle, CK.ImageToolbar, CK.LinkImage,
        // tables
        CK.Table, CK.TableToolbar, CK.TableProperties, CK.TableCellProperties,
        // source view + general HTML support
        CK.SourceEditing,
        CK.GeneralHtmlSupport
      ],

      toolbar: [
        'sourceEditing','|',
        'undo','redo','findAndReplace','selectAll','|',
        'heading','|',
        'bold','italic','underline','strikethrough','subscript','superscript','code','removeFormat','|',
        'highlight','link','blockQuote','codeBlock','horizontalLine','pageBreak','|',
        'bulletedList','numberedList','todoList','outdent','indent','|',
        'alignment','|',
        'fontSize','fontFamily','fontColor','fontBackgroundColor','|',
        'insertTable','mediaEmbed'
      ],

      list: { properties: { styles:true, startIndex:true, reversed:true } },
      image: { toolbar: ['imageTextAlternative','|','imageStyle:inline','imageStyle:block','imageStyle:side','|','linkImage'] },
      table: { contentToolbar: ['tableColumn','tableRow','mergeTableCells','tableProperties','tableCellProperties'] },

      // 🔓 Allow ALL HTML tags/attrs/classes/styles in Source view
      htmlSupport: {
        allow: [
          {
            name: /.*/,        // any tag: div, section, iframe, video, etc.
            attributes: true,  // any attributes (including data-*)
            classes: true,     // any classes
            styles: true       // any inline styles
          }
        ]
      }
    })
    .then(function(editor){
      window.editor = editor;

      // auto-resize + send live data up to parent
      function pingHeight(){
        var h = document.documentElement.scrollHeight || document.body.scrollHeight || 320;
        parent.postMessage({ type:'ck-height', h:h }, '*');
      }
      pingHeight();
      editor.model.document.on('change:data', function(){
        pingHeight();
        parent.postMessage({ type:'ck-data', html: editor.getData() }, '*');
      });
      window.addEventListener('resize', pingHeight);
      setTimeout(pingHeight, 50);
    }).catch(console.error);
  })();
  <\/script>
</body></html>`;

        // mount iframe
        const iframe = document.createElement('iframe');
        iframe.id = 'ckframe';
        iframe.srcdoc = srcdoc;
        iframe.style.width = '100%';
        iframe.style.minHeight = '320px';
        iframe.style.border = '1px solid #ddd';
        iframe.style.borderRadius = '8px';
        iframe.style.background = '#fff';
        host.appendChild(iframe);

        // messages from iframe (height + data)
        window.addEventListener('message', function (e) {
          if (e.source !== iframe.contentWindow || !e.data) return;
          if (e.data.type === 'ck-height') {
            iframe.style.height = Math.max(320, e.data.h) + 'px';
          } else if (e.data.type === 'ck-data') {
            hiddenInput.value = e.data.html;
            hiddenInput.dispatchEvent(new Event('input', { bubbles: true })); // Livewire sees it
          }
        });

        // ensure latest HTML just before submit
        const form = hiddenInput.closest('form');
        if (form) {
          form.addEventListener('submit', function () {
            const ed = iframe.contentWindow && iframe.contentWindow.editor;
            if (ed) {
              hiddenInput.value = ed.getData();
              hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
          });
        }
      }

      // mount now (when edit mode renders)
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', mountCkEditor, { once: true });
      } else {
        mountCkEditor();
      }

      // and re-mount after any Livewire DOM update that shows this block
      document.addEventListener('livewire:load', function () {
        if (!window.Livewire || !Livewire.hook) return;
        Livewire.hook('message.processed', function () {
          const host = document.getElementById('ck-host');
          const frameExists = document.getElementById('ckframe');
          if (host && !frameExists) mountCkEditor();
        });
      });
    })();
    </script>
  @endif

  <label style="top:-25px; transform:none; color:#bbfcde;">Long Description</label>
</div>
        {{-- Category Long Description Bottom--}}
      

        <div class="textarea__tabs details__long">
  @if ($editcategory === null)
    <span class="disabled">{!! $category->long_description_bottom !!}</span>
  @else
    {{-- Editor host (ignored by Livewire) --}}
    <div id="ck-host-bottom" wire:ignore></div>

    {{-- Hidden field for Livewire + form submit (never visible) --}}
    <input
      type="hidden"
      id="long_description_bottom_input"
      name="long_description_bottom"
      wire:model.defer="cat.long_description_bottom"
    />

    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.css">
    <style>
      .ck-content { font: 400 16px/1.6 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif; color:#111; min-height:280px; }
      .ck-content * { box-sizing:border-box; }
      /* wrap toolbar so all buttons remain visible on narrow containers */
      .ck-toolbar .ck-toolbar__items { flex-wrap: wrap; }
    </style>

    <script>
    (function () {
      function mountCkEditor() {
        const host = document.getElementById('ck-host-bottom');
        const hiddenInput = document.getElementById('long_description_bottom_input');
        if (!host || !hiddenInput || document.getElementById('ckframe2')) return;

        const initialHtml = @json(old('long_description_bottom', $category->long_description_bottom ?? '<p></p>'));

        const srcdoc = `
<!doctype html><html><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.css">
  <style>
    html,body{margin:0;height:100%}
    .ck-content{min-height:280px;line-height:1.6}
    .ck-editor{max-width:100%}
    .ck-toolbar .ck-toolbar__items{flex-wrap:wrap}
  </style>
</head><body>
  <div id="editor">${initialHtml}</div>
  <script src="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.umd.js"><\/script>
  <script>
  (function(){
    var CK = window.CKEDITOR;
    CK.ClassicEditor.create(document.querySelector('#editor'), {
      licenseKey: '{{ app('global_ckeditor_license') }}',

     plugins: [
        // essentials & UX
        CK.Essentials, CK.Paragraph, CK.Autoformat, CK.PasteFromOffice, CK.RemoveFormat,
        CK.FindAndReplace, CK.SelectAll, CK.Clipboard, CK.Undo,
        // text formatting
        CK.Bold, CK.Italic, CK.Underline, CK.Strikethrough, CK.Subscript, CK.Superscript,
        CK.Code, CK.Highlight, CK.Font, CK.Alignment,
        // structure
        CK.Heading, CK.BlockQuote, CK.HorizontalLine, CK.PageBreak,
        // lists
        CK.List, CK.ListProperties, CK.TodoList, CK.Indent, CK.IndentBlock,
        // links & media
        CK.Link, CK.AutoLink, CK.MediaEmbed,
        // images
        CK.Image, CK.ImageCaption, CK.ImageStyle, CK.ImageToolbar, CK.LinkImage,
        // tables
        CK.Table, CK.TableToolbar, CK.TableProperties, CK.TableCellProperties,
        // source view + general HTML support
        CK.SourceEditing,
        CK.GeneralHtmlSupport
      ],

      toolbar: [
        'sourceEditing','|',
        'undo','redo','findAndReplace','selectAll','|',
        'heading','|',
        'bold','italic','underline','strikethrough','subscript','superscript','code','removeFormat','|',
        'highlight','link','blockQuote','codeBlock','horizontalLine','pageBreak','|',
        'bulletedList','numberedList','todoList','outdent','indent','|',
        'alignment','|',
        'fontSize','fontFamily','fontColor','fontBackgroundColor','|',
        'insertTable','mediaEmbed'
      ],

      list: { properties: { styles:true, startIndex:true, reversed:true } },
      image: { toolbar: ['imageTextAlternative','|','imageStyle:inline','imageStyle:block','imageStyle:side','|','linkImage'] },
      table: { contentToolbar: ['tableColumn','tableRow','mergeTableCells','tableProperties','tableCellProperties'] },

      // 🔓 Allow ALL HTML tags/attrs/classes/styles in Source view
      htmlSupport: {
        allow: [
          {
            name: /.*/,        // any tag: div, section, iframe, video, etc.
            attributes: true,  // any attributes (including data-*)
            classes: true,     // any classes
            styles: true       // any inline styles
          }
        ]
      }
    })
    .then(function(editor){
      window.editor = editor;

      // auto-resize + send live data up to parent
      function pingHeight(){
        var h = document.documentElement.scrollHeight || document.body.scrollHeight || 320;
        parent.postMessage({ type:'ck-height', h:h }, '*');
      }
      pingHeight();
      editor.model.document.on('change:data', function(){
        pingHeight();
        parent.postMessage({ type:'ck-data', html: editor.getData() }, '*');
      });
      window.addEventListener('resize', pingHeight);
      setTimeout(pingHeight, 50);
    }).catch(console.error);
  })();
  <\/script>
</body></html>`;

        // mount iframe
        const iframe = document.createElement('iframe');
        iframe.id = 'ckframe2';
        iframe.srcdoc = srcdoc;
        iframe.style.width = '100%';
        iframe.style.minHeight = '320px';
        iframe.style.border = '1px solid #ddd';
        iframe.style.borderRadius = '8px';
        iframe.style.background = '#fff';
        host.appendChild(iframe);

        // messages from iframe (height + data)
        window.addEventListener('message', function (e) {
          if (e.source !== iframe.contentWindow || !e.data) return;
          if (e.data.type === 'ck-height') {
            iframe.style.height = Math.max(320, e.data.h) + 'px';
          } else if (e.data.type === 'ck-data') {
            hiddenInput.value = e.data.html;
            hiddenInput.dispatchEvent(new Event('input', { bubbles: true })); // Livewire sees it
          }
        });

        // ensure latest HTML just before submit
        const form = hiddenInput.closest('form');
        if (form) {
          form.addEventListener('submit', function () {
            const ed = iframe.contentWindow && iframe.contentWindow.editor;
            if (ed) {
              hiddenInput.value = ed.getData();
              hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
          });
        }
      }

      // mount now (when edit mode renders)
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', mountCkEditor, { once: true });
      } else {
        mountCkEditor();
      }

      // and re-mount after any Livewire DOM update that shows this block
      document.addEventListener('livewire:load', function () {
        if (!window.Livewire || !Livewire.hook) return;
        Livewire.hook('message.processed', function () {
          const host = document.getElementById('ck-host-bottom');
          const frameExists = document.getElementById('ckframe2');
          if (host && !frameExists) mountCkEditor();
        });
      });
    })();
    </script>
  @endif

  <label style="top:-25px; transform:none; color:#bbfcde;">Long Description Bottom</label>
</div>
        {{-- Category Long Description --}}
        <div class="textarea__tabs details__long">
            @if ($editcategory === null)
                <span class="disabled">{{ $category->long_description_bottom }}</span>
            @else
                <textarea type="text" wire:model.defer="cat.long_description_bottom" required></textarea>
            @endif
            <label for="category__name">Long Description Bottom</label>
        </div>

        {{-- Category Seo Title --}}
        <div class="input__tabs">
            @if ($editcategory === null)
                <span class="disabled">{{ $category->seo_title }}</span>
            @else
                <input type="text" wire:model.defer="cat.seo_title" required>
            @endif
            <label for="category__name">Seo Title</label>
        </div>

        {{-- Category Friendly URL --}}
        <div class="input__tabs">
            @if ($editcategory === null)
                <span class="disabled">{{ $category->seo_id }}</span>
            @else
                <input type="text" wire:model.defer="cat.seo_id" required>
            @endif
            <label for="category__name">Friendly URL</label>
        </div>

        {{-- Create date / time --}}
        <div class="input__tabs">
            <span class="disabled">{{ $category->created_at }}</span>
            <label>Create date / time</label>
        </div>

        {{-- Created By --}}
        <div class="input__tabs">
            <span class="disabled">{{ $category->createdby }}</span>
            <label>Created By</label>
        </div>

        {{-- Updated At --}}
        <div class="input__tabs">
            <span class="disabled">{{ $category->updated_at }}</span>
            <label>Updated At</label>
        </div>

        {{-- Last Modified By --}}
        <div class="input__tabs">
            <span class="disabled">{{ $category->lastmodifiedby }}</span>
            <label>Last modified by</label>
        </div>

        {{-- Save Button --}}
        @if ($editcategory != null)
            <button class="button button--fill button--secondary details__long" wire:click.prevent="savecategory()"
                value="Save">
                Save
            </button>
        @endif
    </form>


    {{-- Tabs Body (Related) --}}
    <div style="height: calc(100% - 107.5px);" class="tabs__content related__view" id="relatedContent">
        @livewire('related-media-category', ['category' => $category])
        @livewire('related-product-category', ['category' => $category])
        @livewire('related-subcategory', ['category' => $category])
    </div>
</section>
