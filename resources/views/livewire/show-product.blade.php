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


    {{-- Delete Record --}}
    <aside>
        <div class="background background--center @if ($relation) active @endif"></div>
        <div class="aside aside--confirm @if ($relation) active @endif" style="min-height: 150px">
            <span>
                This product is involved in an order/cart/supplier, delete this record?
            </span>
            <button class="button button--primary button--long" wire:click.prevent="forcedeleteRecord()">
                <span>Yes</span>
            </button>
            <button class="button button--danger button--long" wire:click.prevent="cancelItemRemoval()">
                <span>No</span>
            </button>
        </div>
    </aside>


    {{-- Navigation --}}
    <nav class="nav--controls">
        <h1 class="table--name">Product: {{ $product->name }}</h1>
        {{-- Refresh Button --}}
        <a class="button button--primary button--centered" tooltip="Back to all products" tooltip-top
            href="{{ route('all_products') }}">
            <svg>
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        <a class="button button--primary button--centered" tooltip="Create new Product" tooltip-top
            href="{{ route('add_product') }}">
            <svg>
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="12" y1="18" x2="12" y2="12"></line>
                <line x1="9" y1="15" x2="15" y2="15"></line>
            </svg>
        </a>
        @if ($editproduct === null)
            <button class="button button--primary button--centered" tooltip="Edit this Product" tooltip-left
                wire:click.prevent="editproduct()">
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                    <path d="M16 5l3 3" />
                </svg>
            </button>
        @else
            <button class="button button--primary button--centered" tooltip="Save Edit" tooltip-left
                wire:click.prevent="saveproduct()">
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    <path d="M9 15l2 2l4 -4" />
                </svg>
            </button>
            <button class="button button--primary button--centered" tooltip="Cancel edit" tooltip-left
                wire:click.prevent="cancelproduct()">
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
                    <path d="M10 12l4 5" />
                    <path d="M10 17l4 -5" />
                </svg>
            </button>
        @endif
        <button class="button button--primary button--centered" tooltip="Delete this Product" tooltip-left
            wire:click.prevent="confirmProductRemoval({{ $product->id }})">
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
        {{-- Product Name --}}
        <div class="input__tabs">
            @if ($editproduct === null)
                <span class="disabled">{{ $product->name }}</span>
            @else
                <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.product_name"
                    required>
            @endif
            <label for="product__name">Name</label>
        </div>



        {{-- Product Active && IsNew --}}
        <div class="details__checkboxes">
            {{-- Product Active --}}
            <div class="checkbox__details ">
                @if ($editproduct === null)
                    @if ($product->active)
                        <input type="checkbox" id="active1" checked class="disabled" disabled />
                        <label for="active1" class="disabled">Active</label>
                    @else
                        <input type="checkbox" id="active2" class="disabled" disabled />
                        <label for="active2" class="disabled">Active</label>
                    @endif
                @else
                    <input type="checkbox" id="active3" wire:model.defer="prod.active" />
                    <label for="active3">Active</label>
                @endif
            </div>
            <div class="checkbox__details">
                @if ($editproduct === null)
                    @if ($product->is_new)
                        <input type="checkbox" id="isNew1" checked class="disabled" disabled />
                        <label for="isNew1" class="disabled">Is New</label>
                    @else
                        <input type="checkbox" id="isNew2" class="disabled" disabled />
                        <label for="isNew2" class="disabled">Is New</label>
                    @endif
                @else
                    <input type="checkbox" id="isNew3" wire:model.defer="prod.is_new" />
                    <label for="isNew3">Is New</label>
                @endif
            </div>
        </div>
        <div class="details__checkboxes">
            <div class="checkbox__details">
                @if ($editproduct === null)
                    @if ($product->is_digital)
                        <input type="checkbox" id="digital1" checked class="disabled" disabled />
                        <label for="digital1" class="disabled">Is Digital</label>
                    @else
                        <input type="checkbox" id="digital2" class="disabled" disabled />
                        <label for="digital2" class="disabled">Is Digital</label>
                    @endif
                @else
                    <input type="checkbox" id="digital3" wire:model.defer="prod.is_digital" />
                    <label for="digital3">Is Digital</label>
                @endif
            </div>
            <div class="checkbox__details">
                @if ($editproduct === null)
                    @if ($product->low_stock)
                        <input type="checkbox" id="Lowstock1" checked class="disabled" disabled />
                        <label for="Lowstock1" class="disabled">Low Stock</label>
                    @else
                        <input type="checkbox" id="Lowstock2" class="disabled" disabled />
                        <label for="Lowstock2" class="disabled">Low Stock</label>
                    @endif
                @else
                    <input type="checkbox" id="Lowstock3" wire:model.defer="prod.low_stock" />
                    <label for="Lowstock3">Low Stock</label>
                @endif
            </div>
            <div class="checkbox__details ">
                @if ($editproduct === null)
                    @if ($product->preorder)
                        <input type="checkbox" id="preorder1" checked class="disabled" disabled />
                        <label for="preorder1" class="disabled">Preorder</label>
                    @else
                        <input type="checkbox" id="preorder2" class="disabled" disabled />
                        <label for="preorder2" class="disabled">Preorder</label>
                    @endif
                @else
                    <input type="checkbox" id="preorder3" wire:model.defer="prod.preorder" />
                    <label for="preorder3">Preorder</label>
                @endif
            </div>
            {{-- Product IsNew --}}
        </div>
        {{-- Product Brand --}}
        <div class="input__tabs">
            @if ($editproduct === null)
                <span class="disabled">{{ $product->brand }}</span>
            @else
                <input type="text" placeholder=" " name="product__brand" wire:model.defer="prod.brand" required>
            @endif
            <label for="product__name">Brand</label>
        </div>

        {{-- Product Type --}}
        <div class="input__tabs">
            @if ($editproduct === null)
                <span class="disabled">{{ $product->type }}</span>
            @else
                <select wire:model.defer="prod.type">
                    <option value="parent">parent</option>
                    <option value="standard">standard</option>
                    <option value="variant">variant</option>
                </select>
            @endif
            <label for="product__name">Type</label>
        </div>
        <div class="details__checkboxes">

            {{-- Product Quantity --}}
            <div class="input__tabs">
                @if ($editproduct === null)
                    <span class="disabled">{{ $product->quantity }}</span>
                @else
                    <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.quantity"
                        required>
                @endif
                <label for="product__name">Quantity</label>
            </div>
            <div class="input__tabs">
                <span class="disabled">{{ $interimQuantity }}</span>
                <label for="product__name">Quantity Interim</label>

            </div>
        </div>
        <div class="details__checkboxes">

            <div class="input__tabs">
                @if ($editproduct === null)
                    <span class="disabled">{{ $product->supplier_name }}</span>
                @else
                    <input type="text" name="supplier_name" wire:model.defer="prod.supplier_name" required>
                @endif
                <label for="supplier_name">Supplier name</label>
            </div>
            <div class="input__tabs">
                <span class="disabled">{{ $quantitysupplier }}</span>
                <label for="product__name">Quantity Supplier</label>

            </div>
        </div>
        <div class="details__checkboxes">

            <div class="input__tabs">
                @if ($editproduct === null)
                    <span class="disabled">{{ $product->low_stock_quantity }}</span>
                @else
                    <input type="number" name="low_stock_quantity" wire:model.defer="prod.low_stock_quantity"
                        required>
                @endif
                <label for="low_stock_quantity">Low Stock Quantity</label>
            </div>


            {{-- Product Popularity --}}
            <div class="input__tabs">
                @if ($editproduct === null)
                    <span class="disabled">{{ $product->popularity }}</span>
                @else
                    <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.popularity"
                        required>
                @endif
                <label for="product__name">Popularity</label>
            </div>
        </div>
        <div class="details__checkboxes">

            {{-- Product SKU --}}
            <div class="input__tabs">
                @if ($editproduct === null)
                    <span class="disabled">{{ $product->sku }}</span>
                @else
                    <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.sku" required>
                @endif
                <label for="product__name">SKU</label>
            </div>

            {{-- Product EAN --}}
            <div class="input__tabs">
                @if ($editproduct === null)
                    <span class="disabled">{{ $product->ean }}</span>
                @else
                    <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.ean" required>
                @endif
                <label for="product__name">EAN</label>
            </div>
        </div>

        {{-- Product Short Description --}}
        <div class="input__tabs">
            @if ($editproduct === null)
                <span class="disabled">{{ $product->short_description }}</span>
            @else
                <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.short_description"
                    required>
            @endif
            <label for="product__name">Short Description</label>
        </div>

        {{-- Product Start Date --}}
        <div class="input__tabs">
            @if ($editproduct === null)
                <span class="disabled">{{ $product->start_date }}</span>
            @else
                <input type="date" placeholder=" " name="product__name" wire:model.defer="prod.start_date"
                    required>
            @endif
            <label for="product__name">Start Date</label>
        </div>

        {{-- Product End Date --}}
        <div class="input__tabs">
            @if ($editproduct === null)
                <span class="disabled">{{ $product->end_date }}</span>
            @else
                <input type="date" placeholder=" " name="product__name" wire:model.defer="prod.end_date"
                    required>
            @endif
            <label for="product__name">End Date</label>
        </div>

            {{-- Product Meta Description --}}
            <div class="input__tabs">
                @if ($editproduct === null)
                    <span class="disabled">{{ $product->meta_description }}</span>
                @else
                    <input type="text" placeholder=" " name="product__name"
                        wire:model.defer="prod.meta_description" required>
                @endif
                <label for="product__name">Meta Description</label>
            </div>
            {{-- Product Meta Description --}}
            <div class="input__tabs">
                @if ($editproduct === null)
                    <span class="disabled">{{ $product->google_category }}</span>
                @else
                    <input type="text" placeholder=" " name="google_category"
                        wire:model.defer="prod.google_category" required>
                @endif
                <label for="product__name">Google Category</label>
            </div>

        {{-- Comments --}}
        <div class="textarea__tabs details__long">
            @if ($editproduct === null)
                <span class="disabled">{{ $product->comments }}</span>
            @else
                <textarea type="text" placeholder=" " name="product__name" wire:model.defer="prod.comments"></textarea>
            @endif
            <label for="product__name">Comments</label>
        </div>

        {{-- Product Long Description --}}
        <div class="textarea__tabs details__long">
            @if ($editproduct === null)
                <span class="disabled">{!! $product->long_description !!}</span>
            @else
            @if ($editls ===null)
            <button class="button" tooltip="Edit Long Description" tooltip-left
                wire:click.prevent="editls()">
                <svg>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                    <path d="M16 5l3 3" />
                </svg>
            </button>
            @else
                {{-- Editor host (ignored by Livewire) --}}
                <div id="ck-host" wire:ignore></div>

                {{-- Hidden field for Livewire + form submit (never visible) --}}
                <input type="hidden" id="long_description_input" name="long_description"
                    wire:model.defer="prod.long_description" />

                <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.css">
                <style>
                    .ck-content {
                        font: 400 16px/1.6 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
                        color: #111;
                        min-height: 280px;
                    }

                    .ck-content * {
                        box-sizing: border-box;
                    }

                    /* wrap toolbar so all buttons remain visible on narrow containers */
                    .ck-toolbar .ck-toolbar__items {
                        flex-wrap: wrap;
                    }
                </style>

                <script>
                    (function() {
                        function mountCkEditor() {
                            const host = document.getElementById('ck-host');
                            const hiddenInput = document.getElementById('long_description_input');
                            if (!host || !hiddenInput || document.getElementById('ckframe')) return;

                            const initialHtml = @json(old('long_description', $product->long_description ?? '<p></p>'));

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
    })
    .catch(console.error);
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
                            window.addEventListener('message', function(e) {
                                if (e.source !== iframe.contentWindow || !e.data) return;
                                if (e.data.type === 'ck-height') {
                                    iframe.style.height = Math.max(320, e.data.h) + 'px';
                                } else if (e.data.type === 'ck-data') {
                                    hiddenInput.value = e.data.html;
                                    hiddenInput.dispatchEvent(new Event('input', {
                                        bubbles: true
                                    })); // Livewire sees it
                                }
                            });

                            // ensure latest HTML just before submit
                            const form = hiddenInput.closest('form');
                            if (form) {
                                form.addEventListener('submit', function() {
                                    const ed = iframe.contentWindow && iframe.contentWindow.editor;
                                    if (ed) {
                                        hiddenInput.value = ed.getData();
                                        hiddenInput.dispatchEvent(new Event('input', {
                                            bubbles: true
                                        }));
                                    }
                                });
                            }
                        }

                        // mount now (when edit mode renders)
                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', mountCkEditor, {
                                once: true
                            });
                        } else {
                            mountCkEditor();
                        }

                        // and re-mount after any Livewire DOM update that shows this block
                        document.addEventListener('livewire:load', function() {
                            if (!window.Livewire || !Livewire.hook) return;
                            Livewire.hook('message.processed', function() {
                                const host = document.getElementById('ck-host');
                                const frameExists = document.getElementById('ckframe');
                                if (host && !frameExists) mountCkEditor();
                            });
                        });
                    })();
                </script>
                @endif
            @endif

            <label for="Long Description" style="top:-25px; transform:none; color:#bbfcde;">Long Description</label>
        </div>

        {{-- Product Seo Title --}}
        <div class="input__tabs">
            @if ($editproduct === null)
                <span class="disabled">{{ $product->seo_title }}</span>
            @else
                <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.seo_title"
                    required>
            @endif
            <label for="product__name">Seo Title</label>
        </div>

        {{-- Product Friendly URL --}}
        <div class="input__tabs">
            @if ($editproduct === null)
                <span class="disabled">{{ $product->seo_id }}</span>
            @else
                <input type="text" placeholder=" " name="product__name" wire:model.defer="prod.seo_id" required>
            @endif
            <label for="product__name">Friendly URL</label>
        </div>

        {{-- Create date / time --}}
        <div class="input__tabs">
            <span class="disabled">{{ $product->created_at }}</span>
            <label>Create date / time</label>
        </div>

        {{-- Created By --}}
        <div class="input__tabs">
            <span class="disabled">{{ $product->created_by }}</span>
            <label>Created By</label>
        </div>

        {{-- Updated At --}}
        <div class="input__tabs">
            <span class="disabled">{{ $product->updated_at }}</span>
            <label>Updated At</label>
        </div>

        {{-- Last Modified By --}}
        <div class="input__tabs">
            <span class="disabled">{{ $product->last_modified_by }}</span>
            <label>Last modified by</label>
        </div>

        {{-- Save Button --}}
        @if ($editproduct != null)
            <button class="button button--fill button--secondary details__long" wire:click.prevent="saveproduct()"
                value="Save">
                Save
            </button>
        @endif
    </form>


    {{-- Tabs Body (Related) --}}
    <div style="height: calc(100% - 107.5px);" class="tabs__content related__view" id="relatedContent">
        @livewire('related-media-product', ['product' => $product])
        @livewire('related-variants', ['product' => $product])
        @livewire('related-category-product', ['product' => $product])
        @livewire('related-products', ['product' => $product])
        @livewire('related-spec-product', ['product' => $product])
        @livewire('related-pricelist', ['product' => $product])
        @livewire('product-reviews', ['product' => $product, 'tableName' => 'product_reviews'])
        @livewire('beeing-in-order', ['relatedby' => 'order', 'productid' => $product->id], key(1))
        @livewire('beeing-in-order', ['relatedby' => 'supplier', 'productid' => $product->id], key(2))
        @livewire('beeing-in-order', ['relatedby' => 'cart', 'productid' => $product->id], key(3))
        @livewire('related-cost', ['productid' => $product->id, 'tableName' => 'product_costs'], key('related-cost'))
        @livewire('related-competitors', ['product' => $product, 'tableName' => 'competitor_products'], key($product->id))

    </div>
</section>
